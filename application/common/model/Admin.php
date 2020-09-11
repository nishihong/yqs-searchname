<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Admin.php
 * +------------------------------------------------------------+
 * 管理员模型
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 1.0
 *
 * Modified at : 2020-03-25 22:17:50
 *
 */
 
namespace app\common\model;

use app\common\model\Common;

class Admin extends Common 
{
	/**
	 * 覆盖表前缀 nish
	 * @author nish
	 * Modified at : 2019年4月10日 09:44:37
	 */
	protected $table = 'dj_admin'; //表名(包含了前缀)

	/**
	 * 获取指定用户名的用户基本信息
	 * 
	 * @param Int	$id	用户ID
	 * @return Array
	 */
	public function getBasic($id) {
		$admin = self::get($id);
		return $admin;
	}

	/**
	 * 获取指定用户名的用户基本信息
	 * 
	 * @param Int	$p_name	用户名
	 * @return Array
	 */
	public function getBasicByName($p_name) {
		$admin = self::where('admin_username', $p_name)->find();
		return $admin;
	}

	/**
	 * 登陆用户
	 * 
	 * @param String $p_username	用户名
	 * @param String $p_password	用户密码
	 * 
	 */
	public function login($p_name, $p_pwd) 
	{
		if (empty($p_name) || empty($p_pwd)) {
			return -1;
		}

		//进行判断
		$cond['admin_username']	 = $p_name;
		$cond['admin_status']	 = 1;
		$userArr = self::where($cond)->find();
		if (empty($userArr)) {
			return -2;
		}
		if ($userArr['admin_password'] != encrypt_pwd($p_pwd)) {
			return -3;
		}

		return $userArr;
	}

	/**
	 * 退出登陆时清除信息动作
	 */
	public function logout() {
		session('admin_id', null);
		session('admin_username', null);
		session_destroy();
	}

	/**
	 * 登录错误消息
	 * @param string $error_id 错误代码
	 */
	public function login_error($error_id){
		switch ($error_id) {
			case '-1':
				return '手机号码不存在';
			case '-2':
				return '用户名不存在';
			case '-3':
				return '密码错误';
			case '-4':
				return '账号被锁定';
			default:
				return '错误类型未知';
		}
	}
  
}
