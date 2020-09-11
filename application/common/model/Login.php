<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Login
 * +------------------------------------------------------------+
 * 登录日志模型
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at : 2019年4月10日 10:07:23
 *
 */

namespace app\common\model;

use app\common\model\Common;

class Login extends Common 
{
	protected $table = 'dj_login'; //表名(包含了前缀)


	/**
	 * 记录登录日志
	 *
	 * @object_id 用户ID
	 * @object_name 用户姓名
	 * @type 登录类型 1管理员 2商户
	 *
	 */
    public function addLoginLog($object_id, $object_name, $type = 1)
    {
	    $ip         = get_client_ip();
	    $address    = get_local_ipaddress($ip);
	    $login_address = $address['country']." ".$address['area'];

	    //登录记录
	    $res = $this->insert([
	        'object_id'       => $object_id,
	        'object_name'     => $object_name,
	        'login_type'      => $type,
	        'login_ip'        => get_client_ip(),
	        'login_address'   => $login_address,
	        'login_addtime'   => time()
	    ]);

	    return $res;
	}
}
