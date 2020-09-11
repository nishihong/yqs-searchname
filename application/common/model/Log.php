<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Log
 * +------------------------------------------------------------+
 * 日志模型
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

class Log extends Common 
{
    //数据库表
    protected $table 		= 'dj_log';

    /**
	 * 记录管理系统操作日志
	 *
	 * @descript 操作内容
	 *
	 */
    public function addLog($descript)
    {
	    if(in_array(request()->controller(), array('Log','Upload','ErrorPage'))) return true;

	    if(request()->controller() == 'Index'){
	        $descript = '进入首页';
	    }

	    $content = $descript;
	    $data = [];
	    $data['action_id'] = (int)session('admin_id');
	    $data['content'] = $content;
	    $data['name'] = defined('USER_USERNAME') ? USER_USERNAME : '空';
	    $data['url'] = input('server.REQUEST_URI');
	    $data['time'] = time();
	    $data['ip'] = get_client_ip();

	    $res = $this->insert($data);

	    return $res;
	}
}
