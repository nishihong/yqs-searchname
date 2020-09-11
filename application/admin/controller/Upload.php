<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Upload 
 * +------------------------------------------------------------+
 * 文件上传类
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 2.0
 *
 * Modified at : 2019-03-06 10:42
 * 
 * 上传文件挂件，调用方式
 * {:Upload('Upload/image', array('id'=>'admin_image', 'name'=>'admin_image','value'=>$data['admin_image'],'path'=>'admin_image','ext'=>'img_ext'))}
 */

namespace app\admin\controller;

use app\admin\controller\Common;
use UploadFile\UploadFile;

class Upload extends Common 
{
    
	private $_file_ext = [
		//图片扩展名
		'img_ext' => [
 			'ext'       => '*.jpg;*.jpeg;*.png;*.gif;*.ico;',
 			'ext_name'  => ['jpg','jpeg','png','gif','ico'],
		],

		//xls,doc扩展名
		'excel_ext' => [
			'ext'       => '*.xls;*xlsx;*.doc;*.docx',
			'ext_name'  => ['xls','xlsx','doc','docx'],
        ],
	];

    //文件上传路径
	private $_file_path = './upload/admin/';
	
	//上传控件初始化配置
	private $_config = array(
		'title'			=> '',              //控件标题
		'ext'			=> 'img_ext',       //文件扩展名，对应$_file_ext中的键值
		'action_name'	=> 'image',         //方法名
		'win'			=> 1,               //是否显示上传文件弹出框
		'path'			=> 'image',         //文件上传路径
		'id'			=> 'image_id',      //表单域id
		'name'			=> 'image_id',      //表单域变量名
		'multi'			=> false,           //是否允许同时上传多个文件
	);
	
	//第一次上传
	private $_firstTime = false;

    /**
     * 运行上传组件
     * @param null $params
     * @return mixed
     */
	public function run($params = null){
        $data   = extend(input('get.'), input('param.'));
	    $params = extend($params, $data);
		$win    = isset($params['win']) ? $params['win'] : 0;

		//是否显示上传控件
		if ($win){
			$this->assign([
				'show_control'  => true,//显示上传图片框
				'controller_id' => 'uploadify-'.time(),//控制器id
			]);
		}
		
		//用户配置和组件默认配置信息进行合并
		$this->_config = extend($this->_config, $params);
		$this->_config['id'] = str_replace(array('[', ']', '\'', '"'), '_', $this->_config['name']);

		if ($this->_firstTime == false){
			$this->_firstTime = true;
		}

		$this->assign([
		    'first_time'    => $this->_firstTime,
        ]);


		$action_name = isset($params['action_name']) ? $params['action_name'] : '';

		if($action_name == 'history'){
            return $this->$action_name($this->_config);
        }else{
            return $this->upload($action_name);
        }

	}
    //上传控件初始化
    public function file(){
        $exts = $this->_file_ext[$this->_config['ext']]['ext'];
        $title = $this->_config['title'] ? $this->_config['title'] : '选择文件';
        //获取文件
        if ($this->_config['value']){
            $this->assign('datas', $this->_getFile($this->_config['value']));
        }
        $this->assign(array(
            'title'=>$title,
            'exts'=>$exts,
        ));
        $this->display('Upload:index');
    }
    /**
     * 上传文件展示
     * @param string $name
     * @return mixed
     */
	public function upload($name = 'image'){
	    if($name == 'image'){
            $ext    = 'img_ext';
            $title  = '选择图片';

            $this->_config['ext'] = 'img_ext';
        }elseif ($name == 'excel'){
            $ext    = 'excel_ext';
            $title  = '选择文件';

            $this->_config['ext'] = 'excel_ext';
        }else{
            $ext    = 'img_ext';
            $title  = '选择图片';
        }

	    $exts = $this->_file_ext[$ext]['ext'];

	    if(isset($this->_config['value'])){
	        $datas = $this->_getFile($this->_config['value']);
	    }else{
            $datas = '';
        }

        $this->assign([
            'title'     => $title,
            'ext'       => $ext,
            'exts'      => $exts,
            'datas'     => $datas,
            'params'    => $this->_config
        ]);

	    return $this->fetch('upload/index');
	}

    /**
     * 上传文件处理
     * @return \FastD\Http\Response|\think\response\Json
     */
	public function index(){
        if (count($_FILES) > 0){
            $ext = input('param.ext');

            $msg = '';
            $status = 2;
            $savepath = format_dir($this->_config['path'].'/'.date('Ym').'/');
            $upload = new UploadFile();

            $upload->savePath = $this->_file_path.$savepath;

            if(!in_array($ext, ['img_ext','excel_ext'])){
                $msg = "非法操作";
            }

            if($ext == 'img_ext'){
                $upload->allowExts = $this->_file_ext['img_ext']['ext_name'];
            }else{
                $upload->allowExts = $this->_file_ext['excel_ext']['ext_name'];
            }

            //文件上传成功后将信息保存到数据库中
            if ($upload->upload()){
                $saveinfo = $upload->getUploadFileInfo();

                if(!empty($saveinfo)){
                    $root_path = str_replace('./upload','/upload',$saveinfo[0]['savepath'].$saveinfo[0]['savename']);
                    $data = [
                        'name' 		 => $saveinfo[0]['name'],//原始文件名
                        'file_name'  => $saveinfo[0]['savename'],//保存文件名
                        'file_size'  => $saveinfo[0]['size'],//文件名大小
                        'file_ext'   => $saveinfo[0]['extension'],//文件扩展名
                        'save_path'	 => $saveinfo[0]['savepath'],//保存文件目录
                        'file_path'  => $saveinfo[0]['savepath'].$saveinfo[0]['savename'],//保存文件路径
                        'root_path'  => $root_path,//保存文件根目录
                        'upload_time'=> time(),//上传时间
                        //'admin_id'   => session('admin_id'),//操作人id
                        'action_user'=> $this->_surename,//操作人
                    ];

                    try {
                        model('File')->insert($data);
                        $id = model('File')->getLastInsID();
                        $isimg = in_array($data['file_ext'], $this->_file_ext['img_ext']['ext_name']) ? 1 : 0;//是否为图片
                        $msg = [
                            'id'	=> $id,
                            'path'	=> $data['root_path'],//图片根目录
                            'image' => $isimg,//是否为图片
                            'pos'	=> $isimg ? getPos($data['file_path']) : 0,//获取图片位置
                        ];
                        $status = 1;
                    }catch (\Exception $e){
                        $msg = "数据插入失败，请重新上传".$e->getMessage();

                    }

                }else{
                    $msg = "系统出错，上传文件失败";
                }
            }else{
                $msg = $upload->getErrorMsg();
            }
        }else{
            $msg = "没有选择任何文件";
        }

        return json(["info"=>$msg,"url"=>"","status"=>$status]);
	}

    /**
     * 查看历史上传
     * @param null $params
     * @return mixed
     */
	public function history($params = null){
        $group_id = $this->_group_id;

        if($group_id != 1){
            $where = 'admin_id='.$this->_admin_id;
        }else{
            $where = '';
        }

        if($params['ext'] =='img_ext'){
            $params['action_name'] = 'image';
        }else{
            $params['action_name'] = 'excel';
        }

        $ext_name = $this->_file_ext[$params['ext']]['ext_name'];//文件扩展名过滤

        $datas = model('File')
            ->where('file_ext','in', implode(",", $ext_name))
            ->where($where)
            ->limit(0,30)
            ->order('upload_time DESC')
            ->select();

        $this->assign([
            'params'        => $params,
            'datas'         => $datas,
            'image_exts'    => $this->_file_ext['img_ext']['ext_name']
        ]);

		return $this->fetch('upload/history');
	}

    /**
     * 删除文件
     * @return \FastD\Http\Response|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
	public function delete(){
		$id = input('post.id', '', 'int');

        $group_id = $this->_group_id;

        if($group_id != 1){
            $where = 'admin_id='.$this->_admin_id;
        }else{
            $where = '';
        }

        $msg    = '系统出错，操作失败';
        $status = 2;
		if ($id  > 0){
			$data = model('File')->find($id);

            if (is_file($data['file_path'])){
                @unlink($data['file_path']);
            }

            try {
                model('File')->where($where)->where('id='.$id)->delete();
                $msg = "文件删除成功";
                $status = 1;
            }catch (\Exception $e){
                $msg = "文件删除失败";
            }
		}
        return json(["info"=>$msg,"url"=>"","status"=>$status]);
	}
	
	//显示文件列表
	public function showlist(){
		$width = (int) $this->_config['width'];
		$height = (int) $this->_config['height'];
		$datas = getFiles($this->_config['value'], true, $width > 0 ? $width : 130, $height > 0 ? $height : 130, $this->_dbconfig);
		if($datas){
			if (self::$_firstTime === FALSE){
				self::$_firstTime = 1;
				$this->assign('first_time', 1);
			}else{
				self::$_firstTime ++;
				$this->assign('first_time', self::$_firstTime);
			}
			
			$this->assign('datas', $datas)
				->assign('image_exts', self::$IMAGE_ARR)
				->display();
		}
	}
	

	private function _getFile($file_id = '', $width=100, $height=100){
		if (empty($file_id)) return ;
		if ($file_id){
		    if(is_array($file_id)){
                $file_id = implode(',',$file_id);
		        $where = "(id in ($file_id))";
            }else{
                $where['id'] = $file_id;
            }
			$datas = $model = model('File')->where($where)->field('id,name,file_ext,file_path,root_path')->select();
			if ($datas){
				foreach ($datas as $key=>$vo){
					$datas[$key]['path'] = $vo['root_path'];
					if (in_array($vo['file_ext'], $this->_file_ext['img_ext']['ext_name'])){
						$datas[$key]['is_image'] = 1;
						$datas[$key]['pos'] = $this->getPos($vo['file_path']);
					}else{
						$datas[$key]['is_image'] = 0;
					}
				}
			}
			return $datas;
		}
	}

    function getPos($img, $width=100, $height=100){
        if (!is_file($img))  return false;
        $survey = getimagesize($img);
        //图像文件不存在
        if (false === $survey) return false;
        $top = $left = 0;
        if ($survey[0] <= $width && $survey[1] <= $height){
            $w = $survey[0];
            $h = $survey[1];
        }elseif ($survey[0] <= $width && $survey[1] > $height){
            $h = $height;
            $w = $survey[0] * ($height / $survey[1]);
        }elseif ($survey[0] > $width && $survey[1] <= $height){
            $w = $width;
            $h = $survey[1] * ($width / $survey[0]);
        }else{
            $h = $survey[1] * ($width / $survey[0]);
            if ($h <= $height){
                $w = $survey[0] >= $width ? $width : $survey[0];
            }else{
                $h = $survey[1] >= $height ? $height : $survey[1];
                $w = $survey[0] * ($height / $survey[1]);
            }
        }

        $top = ($height - $h + 1 - 1) / 2;
        $left = ($width - $w + 1 - 1) / 2;

        return array(
            'width' => (int)$w,
            'height' => (int)$h,
            'left' => (int)$left,
            'top' => (int)$top
        );
    }
	
}
