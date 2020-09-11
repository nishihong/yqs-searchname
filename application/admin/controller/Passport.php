<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category passport
 * +------------------------------------------------------------+
 * 宣传页及登录入口文件
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * Created on 2019-11-29 17:13:21
 */
 
namespace app\admin\controller;

use app\admin\controller\Common;
use think\captcha\Captcha;

class Passport extends Common
{
	/**
	 * 后台登录
	 */
	public function login()
    {
		//用户已登录则直接跳转到用户中心
		$url = cookie('referer_url');

		//用户id已存在
		if ($this->_admin_id > 0){
			cookie('referer_url', false);
            if(empty($url)){
                $url = $this->login_url($this->_group_id);
            }
			if(request()->isAjax()){
                $this->success('登录成功', $url);
			}else{
			    $this->redirect($url);
			}
		}else{		
    		if (request()->isPost() && request()->isAjax()){
    			$username    = trim(input('post.username'));
    			$password    = trim(input('post.password'));
    			$verify      = strtolower(input('post.verify'));//验证码
    			if (empty($username)){
    				$this->error('用户名不能为空');
    			}
    			if (empty($password)){
    				$this->error('登录密码不能为空');
    			}
    			//登录出错，必须有验证码
    			if(session('login_error') == 1){
    				if(empty($verify)){
    					$this->error('验证码不能为空');
    				}
    				if (!captcha_check($verify)){				    
    					$this->error('验证码错误');
    				}
    			}
                
    			$Model = model('Admin');
    			$result = $Model->login($username, $password);

    			if (!empty($result) && is_object($result)){
                    if(empty($url)){
                        $url = $this->login_url($result['group_id']);
                    }
                    
                    $this->login_act($result);			        
			        session('login_error', '0');//登录成功
			        cookie('referer_url', false);
			        $this->success('登录成功', $url);
    			}else{
    			    session('login_error', '1');
    			    $this->error($Model->login_error($result));
    			}
    		}
		}
		
		return $this->fetch();
	}
	
	/**
	 * 生成验证码
	 */
	public function verify()
    {		
        $config = [
             // 验证码字体大小
            'fontSize'  => 18,
            // 验证码位数
            'length'    => 4,
            // 关闭验证码杂点
            'useNoise'  => false,
            // 验证码图片高度
            'imageH'    => 40,
            // 验证码图片宽度
            'imageW'    => 125,
            // 验证码过期时间（s）
            'expire'    => 1800,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
	}
	
	/*
	 * 注销登录
	 */
	public function logout()
    {
		model('Admin')->logout();
		$this->success('您已安全退出后台管理系统', url('passport/login'));
	}	
	
	
	/**
	 * 登录信息
	 * 
	 */
	private function login_act($result)
    {	    
	    session('admin_id',       $result['id']);
	    session('admin_username', $result['admin_username']);
	       
        $login_ip   = get_client_ip();              
        $login_list = get_local_ipaddress($login_ip);

	    // 更新用户状态
	    $data['admin_login_time']         = time();
	    $data['admin_login_ip']	          = $login_ip;
	    $data['admin_login_address']	  = $login_list['country'];
	    model("Admin")->where('id=' . $result['id'])->update($data);
	    
	    //插入日志
        model("Login")->addLoginLog($result['id'], $result['admin_username']);
	}

    /**
     * 返回登录时自动跳转的链接
     */
    private function login_url($group_id)
    {
        $menu = $this->_resources;
        if($menu === null){
            $menu = model('Group')->getMenu($group_id);
        }
        if($menu['resources']['Index_index'][1] == 1){
            $url = url('Index/index');
        }else{
            $menu = array_values($menu['topMenu']);
            $url = $menu[0]['url'];
        }

        $url = empty($url) ? url('Index/index') : $url;
        return $url;
    }
}
