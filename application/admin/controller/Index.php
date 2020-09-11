<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Index
 * +------------------------------------------------------------+
 * 首页
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020-03-29 15:41:20
 *
 */
 
namespace app\admin\controller;

use app\admin\controller\Common;

class Index extends Common
{
    
	/**
	 * 首页
	 */
	public function index()
	{
		return $this->fetch('index', [
			'logs' => model('Log')->where('action_id='.$this->_admin_id)->order('id DESC')->limit(5)->select()
		]);
	}

}
