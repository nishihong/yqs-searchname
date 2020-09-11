<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Log
 * +------------------------------------------------------------+
 * 日志
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * Created on 2019年3月27日 11:28:33
 *
 */

namespace app\admin\controller;

use app\admin\controller\Common;
use Carbon\Carbon;

class Log extends Common 
{
    /**
     * 日志列表
     */
	public function index($id = '')
	{
		$name 			= input('param.name');
	    $start_time 	= urldecode(input('param.start_time'));
	    $end_time 		= urldecode(input('param.end_time'));
	    $ip 			= input('param.ip');
	    $content 		= input('param.content');
	    $url 			= input('param.url');

		$condition = [];
		// 时间搜索
		$start_time = empty($start_time) ? date('Y-m-d', strtotime("-7 day")) : $start_time;
        $end_time   = empty($end_time) ? date('Y-m-d') : $end_time;
        $condition[] = ['time','between', [strtotime($start_time." 00:00:00"),strtotime($end_time." 23:59:59")]];
		if($name){
    		$condition[] = ['name', '=', $name];
		}
		if($ip){
    		$condition[] = ['ip', '=', $ip];
		}
		if($content){
    		$condition[] = ['content', 'like', '%'.$content.'%'];
		}
		if($url){
    		$condition[] = ['url', 'like', '%'.$url.'%'];
		}
		if($id){
    		$condition[] = ['action_id', '=', $id];
		}

		//排序
        $order = $this->orderstr(['id'], 'DESC');

		$datas = model('Log')->where($condition)->order($order)->paginate();

		return $this->fetch('index', [
			'pageHtml' 		=> $datas->render(),
			'datas' 		=> $datas,
			'start_time' 	=> $start_time,
			'end_time' 		=> $end_time,
		]);	   
	}
		
	/**
     * 操作日志
     */
	public function my()
	{
		return $this->index($this->_admin_id);
	}

	/**
	 * 日志删除
	 */
	public function delete()
	{
        $this->help_delete();
	}

	/**
     * 后台登录日志
     */
	public function login_log()
	{
	    $keyword 		= input('get.keyword');
	    $start_time 	= urldecode(input('param.start_time'));
	    $end_time 		= urldecode(input('param.end_time'));
	    $address 		= input('get.address');
	    
		$condition = [];	
		// 时间搜索
		$start_time = empty($start_time) ? date('Y-m-d', strtotime("-7 day")) : $start_time;
        $end_time   = empty($end_time) ? date('Y-m-d') : $end_time;
        $condition[] = ['a.login_addtime','between', [strtotime($start_time." 00:00:00"),strtotime($end_time." 23:59:59")]];
		if($this->_group_id != 1){
			$condition[] = ['a.object_id', '=', $id];
		}
		if($keyword){
	       	$condition[] = ['b.admin_username', 'like', '%'.$keyword.'%'];
	    }
	    if($address){
	       	$condition[] = ['a.login_address', 'like', '%'.$address.'%'];
	    }

		//排序
        $order = $this->orderstr(['login_addtime','id'], 'DESC', 'a');

		$Model = model('Login');

		//从表中获取值
		$datas = $Model->alias(['dj_login'=>'a', 'dj_admin'=>'b'])
					->leftJoin('dj_admin', 'a.object_id=b.id')
					->field("a.*,b.admin_username as username,b.id as uid")
					->where($condition)
					->order($order)
					->paginate();

		return $this->fetch('login_log', [
			'pageHtml' 		=> $datas->render(),
			'datas' 		=> $datas,
			'start_time' 	=> $start_time,
			'end_time' 		=> $end_time,
		]);
	}

	/**
	 * 登录日志删除
	 */
	public function login_log_delete(){
        $this->help_delete(1, 'login', request()->controller().'/login_log');
	}

	/**
	 * 用户访问日志
	 */
	public function user_visit_log()
	{
		$name 		= input('param.name');
	    $start_time 	= urldecode(input('param.start_time'));
	    $end_time 		= urldecode(input('param.end_time'));
	    $ip 			= input('param.ip');
	    $content 		= input('param.content');
	    $url 			= input('param.url');

		$condition = [];
		// 时间搜索
		$start_time = empty($start_time) ? date('Y-m-d', strtotime("-7 day")) : $start_time;
        $end_time   = empty($end_time) ? date('Y-m-d') : $end_time;
        $condition[] = ['time','between', [strtotime($start_time." 00:00:00"),strtotime($end_time." 23:59:59")]];
		if($name){
    		$condition[] = ['name', '=', $name];
		}
		if($ip){
    		$condition[] = ['ip', '=', $ip];
		}
		if($content){
    		$condition[] = ['content', 'like', '%'.$content.'%'];
		}
		if($url){
    		$condition[] = ['url', 'like', '%'.$url.'%'];
		}

		$datas = model('UserVisitLog')->where($condition)->order('id DESC')->paginate();
		// dump($datas);exit();
		return $this->fetch('user_visit_log', [
			'pageHtml' 		=> $datas->render(),
			'datas' 		=> $datas,
			'start_time' 	=> $start_time,
			'end_time' 		=> $end_time,
		]);
	}


	/**
     * 图片日志
     */
	public function img_log()
	{
	    $start_time 	= urldecode(input('param.start_time'));
	    $end_time 		= urldecode(input('param.end_time'));
	    $img_url 		= input('get.img_url');
	    
		$condition = [];	
		// 时间搜索
		$start_time = empty($start_time) ? date('Y-m-d', strtotime("-7 day")) : $start_time;
        $end_time   = empty($end_time) ? date('Y-m-d') : $end_time;
        $condition[] = ['time','between', [strtotime($start_time." 00:00:00"),strtotime($end_time." 23:59:59")]];
	    if($img_url){
	       	$condition[] = ['img_url', 'like', '%'.$img_url.'%'];
	    }

		//排序
		$order = $this->orderstr(['id'], 'DESC');

		//从表中获取值
		$datas = model('ImgLog')->where($condition)->order($order)->paginate();

		return $this->fetch('img_log', [
			'pageHtml' 		=> $datas->render(),
			'datas' 		=> $datas,
			'start_time' 	=> $start_time,
			'end_time' 		=> $end_time,
		]);
	}

	/**
	 * 登录日志删除
	 */
	public function img_log_delete(){
        $this->help_delete(1, 'img_log', request()->controller().'/img_log');
	}
}

