<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Attachment
 * +------------------------------------------------------------+
 * 附件
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * Created on 2019年3月27日 11:28:33
 */

namespace app\admin\controller;
use app\admin\controller\Common;

class Attachment extends Common {
	/**
	 * 定义允许上传的文件扩展名
	 */
	private $_extArr	 = array(
		'image'	 => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
		'flash'	 => array('swf', 'flv'),
		'video'	 => array('flv'),
		'voice'	 => array('mp3', 'wav', 'wma', 'wmv', 'mid'),
		'file'	 => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
	);
	
	/**
	 * 根据类型设置默认保存路径
	 */
	private $_pathArr = array(
		'ReplyFocusOnNews'	=> '/upload/ReplyFocus/',
		'ReplyNoKeyword'	=> '/upload/ReplyNoKeyword/',
		'Kindeditor'		=> '/upload/Kindeditor/',
		'ShopGood'			=> '/upload/ShopGood/',
		'ShopSlide'			=> '/upload/ShopSlide/',
		'default'			=> '/upload/',
		'SingleNews'		=> '/upload/Material/SingleNews/',			//单图文
		'MultiNews'			=> '/upload/Material/MultiNews/',			//多图文
		'Pic'				=> '/upload/Material/Pic/',					//图片
		'Voice'				=> '/upload/Material/Voice/',				//语音
		'CompanyImage'		=> '/upload/Company/',						//公司
		'SaleImage'			=> '/upload/Sale/',							//销售渠道
	);
	
	/**
	 * 析构函数
	 * 判断是否有登陆
	 */
	public function __construct()
	{
		parent::__construct();

	}
	
	/**
	 * 默认页
	 */
	public function index()
	{
		$from		 = $this->_GET('from');
		$defaultId	 = $this->_GET('default_id');
		$fileType	 = $this->_GET('file_type');
		$autoFlush	 = (int)$this->_GET('auto_flush');
		$multi		 = (int)$this->_GET('multi');
		$tbTitle	 = $this->_GET('tb_title');
		
		
		$fileExts = isset($this->_extArr[$fileType]) ? $this->_extArr[$fileType] : $this->_extArr['file'];
		$fileExts = '*.' .implode(';*.', $fileExts);
		
		
		$this->assign('controller_id' ,'uploadify-'.time());
		$this->assign('from',		$from);
		$this->assign('defaultId',	$defaultId);
		$this->assign('fileType',	$fileType);
		$this->assign('autoFlush',	$autoFlush);
		$this->assign('multi',		$multi);
		$this->assign('fileExts',		$fileExts);
		$this->assign('tbTitle',		$tbTitle);
		$this->assign('sessionName', session_name());
		$this->assign('sessionId',	 session_id());
		$this->display('index');
	}
	
	/**
	 * 显示附件列表
	 */
	public function showList()
	{
		$from		 = $this->_GET('from');
		$defaultId	 = $this->_GET('default_id');
		$fileType	 = $this->_GET('file_type');
		$autoFlush	 = $this->_GET('auto_flush');
		$multi		 = (int)$this->_GET('multi');
		$tbTitle	 = $this->_GET('tb_title');
		
    	$count = D('Attachment')->where("user_id='{$this->_user_id}' AND file_type='{$fileType}'")->count();
		
		import("ORG.Util.Page"); //导入分页类
    	$p = new Page ($count, 20);
		
		$attachmentList = D('Attachment')->where("user_id='{$this->_user_id}' AND file_type='{$fileType}'")->order("id DESC")->limit("{$p->firstRow}, {$p->listRows}")->select();
		
		
		$this->assign('controller_id' ,'uploadify-'.time());
		$this->assign('from',		$from);
		$this->assign('defaultId',	$defaultId);
		$this->assign('fileType',	$fileType);
		$this->assign('autoFlush',	$autoFlush);
		$this->assign('multi',		$multi);
		$this->assign('tbTitle',		$tbTitle);
		$this->assign('imageExtArr', $this->_extArr['image']);
		$this->assign('attachmentList', $attachmentList);
		$this->assign('page', $p->show());
		$this->display('show_list');
	}

	/**
	 * 上传
	 */
	public function upload()
	{
		if (count($_FILES) > 0){
			
			$from		 = $this->_GET('from');
			$fileType	 = $this->_GET('file_type');

			$fileExts = isset($this->_extArr[$fileType]) ? $this->_extArr[$fileType] : $this->_extArr['file'];
			
			import("ORG.Util.UploadFile");
			
			$path = isset($this->_pathArr[$from]) ? $this->_pathArr[$from] : $this->_pathArr['default'].$from.'/';
			$savePath = ROOT .'/'. $path;
			@mkdirs($savePath);
			
			$Upload = new UploadFile('', $fileExts, '', $savePath, 'time');
			
			//文件上传成功后将信息保存到数据库中
			if ($Upload->upload()){
				$saveinfo = $Upload->getUploadFileInfo();
				
				$data = array(
					'user_id'	 => $this->_admin_user_id,
					'from'		 => $from,
					'name' 		 => $saveinfo[0]['name'],
					'file_type'  => $fileType,
					'file_name'  => $saveinfo[0]['savename'],
					'file_size'  => $saveinfo[0]['size'],
					'file_ext'   => $saveinfo[0]['extension'],
					'save_path'	 => $path,
					'file_path'  => $path.$saveinfo[0]['savename'],
					'upload_time'=> time(),
					'action_user'=> $this->_admin_user_surename
				);
				
				$model = D('Attachment');
				
				//插入到数据库中
				if ($model->add($data)){
					$isimg = $fileType == 'image' ? 1 : 0;
					$this->success(array(
						'id'	=> $model->getLastInsID(), 
						'path'	=> '/'.$data['file_path'],
						'name'  => $data['name'],
						'image' => $isimg,
						'pos'	=> $isimg ? getPos($savePath.$data['file_name']) : 0
					));
				}else{
					$this->error('保存数据出错，上传文件失败');
				}
			}else{
				$this->error($Upload->getErrorMsg());
			}
		}else{
			$this->error('没有选择任何文件');
		}
	}
	
	/**
	 * 删除文件
	 */
	public function delete()
	{
		$id = $this->_POST('id');
		if(empty($id)){
			$this->error("请选择文件！");
		}
		$attachmentArr = D('Attachment')->where("id='{$id}'")->find();
		if(empty($attachmentArr) || $attachmentArr['user_id'] != $this->_user_id){
			$this->error("未找到此附件！");
		}
		
		@unlink(ROOT . $attachmentArr['file_path']);
		D('Attachment')->where("id='{$id}'")->delete();
		
		$this->success("删除成功！");
	}
	
	/**
	 * KINDEDITOR编辑器上传
	 */
	public function kindeditorUpload()
	{
		if (count($_FILES) > 0){
			
			$from		 = $this->_GET('from');
			$fileType	 = $this->_GET('file_type');

			$fileExts = isset($this->_extArr[$fileType]) ? $this->_extArr[$fileType] : $this->_extArr['file'];
			
			import("ORG.Util.UploadFile");
			
			$path = (isset($this->_pathArr[$from]) ? $this->_pathArr[$from] : $this->_pathArr['default']). $this->_user_id .'/';
			$savePath = ROOT .'/'. $path;
			@mkdirs($savePath);
			
			$Upload = new UploadFile('', $fileExts, '', $savePath, 'time');
			//文件上传成功后将信息保存到数据库中
			if ($Upload->upload()){
				$saveinfo = $Upload->getUploadFileInfo();
				
				$data = array(
					'file_path'  => $path.$saveinfo[0]['savename']
				);
				
				echo json_encode(array('error' => 0, 'url' => '/'.$data['file_path']));
				exit;
			}else{
				echo json_encode(array('error' => 1, 'message' => $Upload->getErrorMsg()));
				exit;
			}
		}else{
			echo json_encode(array('error' => 1, 'message' => '没有选择任何文件'));
			exit;
		}
	}
	
	/**
	 * KINDEDITOR编辑器上传
	 */
	public function kindeditorFileManage()
	{
		$path = $this->_pathArr['Kindeditor']. $this->_user_id .'/';
		$savePath = ROOT .'/'. $path;
		
		$current_path = $savePath;
		$current_url = '/'. $path;
		$current_dir_path = '/'. $path;
		$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		
		//遍历目录取得文件信息
		$file_list = array();
		if ($handle = opendir($current_path)) {
			$i = 0;
			while (false !== ($filename = readdir($handle))) {
				if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true; //是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
					$file_list[$i]['filesize'] = 0; //文件大小
					$file_list[$i]['is_photo'] = false; //是否图片
					$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $this->_extArr['image']);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename; //文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
				$i++;
			}
			closedir($handle);
		}
		
		usort($file_list, 'cmp_func');

		$result = array();
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
		//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
		//当前目录的URL
		$result['current_url'] = $current_url;
		//文件数
		$result['total_count'] = count($file_list);
		//文件列表数组
		$result['file_list'] = $file_list;

		//输出JSON字符串
		echo json_encode($result);
	}
}
	
/**
 * 获取图片位置
 * 
 * @param type $img			图片在服务器上的地址
 * @param type $width		宽度，默认100
 * @param type $height		高度，默认100
 * @return boolean
 */
// function getPos($img, $width = 100, $height = 100)
// {
// 	$survey	 = getimagesize($img);
// 	//图像文件不存在
// 	if(false === $survey)
// 		return false;
// 	$h		 = $survey[1] * ($width / $survey[0]);
// 	$top	 = $left	 = 0;
// 	if($h <= $height) {
// 		$top = ($height - $h) / 2;
// 		if($survey[0] >= $width) {
// 			$w = $width;
// 		}else {
// 			$w		 = $survey[0];
// 			$left	 = ($width - $w) / 2;
// 		}
// 	}else {
// 		if($survey[1] >= $height) {
// 			$h = $height;
// 		}else {
// 			$h	 = $survey[1];
// 			$top = ($height - $h) / 2;
// 		}

// 		$w		 = $survey[0] * ($height / $survey[1]);
// 		$left	 = ($width - $w) / 2;
// 	}

// 	return array(
// 		'width'	 => (int) $w,
// 		'height' => (int) $h,
// 		'left'	 => (int) $left,
// 		'top'	 => (int) $top
// 	);
// }