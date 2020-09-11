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
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at : 2019年4月28日 09:34:57
 * 
 * 上传文件挂件，调用方式
 * {:Upload('Upload/image', array('id'=>'admin_image', 'name'=>'admin_image','value'=>$data['admin_image'],'path'=>'admin_image','ext'=>'img_ext'))}
 */

namespace app\index\controller;

use app\index\controller\Common;

class Upload extends Common {

	private $_file_ext = array(
		//所有扩展名
		'all_ext' => array(
			'ext'=>'*.*',
			'ext_name'=>array(),
		),
		//图片扩展名
		'img_ext' => array(
		    'ext'=>'*.*',
 			'ext_name'=>array('jpg','jpeg','png'),
		),
		//xls扩展名
		'xls_ext' => array(
			'ext'=>'*.xls',
			'ext_name'=>array('xls'),
		),
	);
	
	//文件上传路径
	private $_file_path = 'upload/index/';
	
	//上传控件初始化配置
	private $_config = array(
		'title'			=> '',//控件标题
		'ext'			=> 'all_ext',//文件扩展名，对应$_file_ext中的键值
		'action_name'	=> 'file',//方法名
		'win'			=> 1,//是否显示上传文件弹出框
		'path'			=> 'file',//文件上传路径
		'id'			=> 'file_id',//表单域id
		'name'			=> 'file_id',//表单域变量名
		'multi'			=> false,//是否允许同时上传多个文件
	);
	
	//第一次上传
	private $_firstTime = false;
	

	//上传文件处理，VAR_AJAX_SUBMIT=>cprs表示是Ajax提交方式
	public function index(){
	    //重写file
	    $t = input('param.t');
	    $data = $_FILES[$t];
	    unset($_FILES);
	    $_FILES[$t] = $data;	    
	    
		if (count($_FILES) > 0){
			$savepath = format_dir($this->_config['path'].'/'.date('Ym').'/');

			require_once '../source/Util/UploadFile.class.php';
			$upload = new \UploadFile();

			$upload->savePath = $this->_file_path.$savepath;
			$upload->allowExts = $this->_file_ext['img_ext']['ext_name'];
			$upload->maxSize = 5*1024*1024;

			//文件上传成功后将信息保存到数据库中
			if ($upload->upload()){
				$saveinfo = $upload->getUploadFileInfo();
				if(!empty($saveinfo)){
					$data = array(
						'name' 		 => $saveinfo[0]['name'],//原始文件名
						'file_name'  => $saveinfo[0]['savename'],//保存文件名
						'file_size'  => $saveinfo[0]['size'],//文件名大小
						'file_ext'   => $saveinfo[0]['extension'],//文件扩展名
						'save_path'	 => $saveinfo[0]['savepath'],//保存文件目录
						'file_path'  => $saveinfo[0]['savepath'].$saveinfo[0]['savename'],//保存文件路径
						'root_path'  => '/'.$saveinfo[0]['savepath'].$saveinfo[0]['savename'],//保存文件根目录
						'upload_time'=> time(),//上传时间
						'action_user'=> $this->_surename,//操作人
					);

					try {
					    $id = model('File')->insert($data, false, true);
					    $isimg = in_array($data['file_ext'], $this->_file_ext['img_ext']['ext_name']) ? 1 : 0;//是否为图片
					    $msg = [
					        'id'	=> $id,
					        'path'	=> $data['root_path'],//图片根目录
					        'image' => $isimg,//是否为图片
					        'pos'	=> $isimg ? getPos($data['file_path']) : 0,//获取图片位置
					    ];
					    $status = 1;
					}catch (\Exception $e){
					    $msg = "数据插入失败，请重新上传";

					}

				}else{
					$this->error('系统出错，上传文件失败');
				}
			}else{
				$this->error($upload->getErrorMsg());
				//$this->error('系统出错，上传文件失败');
			}
		}else{
			$this->error('没有选择任何文件');
		}

		return json(["info"=>$msg,"url"=>"","status"=>$status]);
	}

	//上传文件处理，VAR_AJAX_SUBMIT=>cprs表示是Ajax提交方式 财务系统的file表
	public function caiwu_index(){
		//重写file
	    $t = input('param.t');
	    $data = $_FILES[$t];
	    unset($_FILES);
	    $_FILES[$t] = $data;
	    
		if (count($_FILES) > 0){
			$savepath = format_dir($this->_config['path'].'/'.date('Ym').'/');

			require_once '../source/Util/UploadFile.class.php';
			$upload = new \UploadFile();

			$upload->savePath = $this->_file_path.$savepath;
			$upload->allowExts = $this->_file_ext['img_ext']['ext_name'];
			$upload->maxSize = 5*1024*1024;

			//文件上传成功后将信息保存到数据库中
			if ($upload->upload()){
				$saveinfo = $upload->getUploadFileInfo();
				if(!empty($saveinfo)){
					$data = array(
						'name' 		 => $saveinfo[0]['name'],//原始文件名
						'file_name'  => $saveinfo[0]['savename'],//保存文件名
						'file_size'  => $saveinfo[0]['size'],//文件名大小
						'file_ext'   => $saveinfo[0]['extension'],//文件扩展名
						'save_path'	 => $saveinfo[0]['savepath'],//保存文件目录
						'file_path'  => $saveinfo[0]['savepath'].$saveinfo[0]['savename'],//保存文件路径
						'root_path'  => '/'.$saveinfo[0]['savepath'].$saveinfo[0]['savename'],//保存文件根目录
						'upload_time'=> time(),//上传时间
						'action_user'=> $this->_surename,//操作人
						'file_system'=> 2,
					);

					try {
					    $id = db('File')->insert($data, false, true);
					    $isimg = in_array($data['file_ext'], $this->_file_ext['img_ext']['ext_name']) ? 1 : 0;//是否为图片
					    $msg = [
					        'id'	=> $id,
					        'path'	=> $data['root_path'],//图片根目录
					        'image' => $isimg,//是否为图片
					        'pos'	=> $isimg ? getPos($data['file_path']) : 0,//获取图片位置
					    ];
					    $status = 1;
					}catch (\Exception $e){
					    $msg = "数据插入失败，请重新上传";

					}

				}else{
					$this->error('系统出错，上传文件失败');
				}
			}else{
				$this->error($upload->getErrorMsg());
				//$this->error('系统出错，上传文件失败');
			}
		}else{
			$this->error('没有选择任何文件');
		}

		return json(["info"=>$msg,"url"=>"","code"=>$status]);
	}

    public function getPos($img, $width=100, $height=100){
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
?>