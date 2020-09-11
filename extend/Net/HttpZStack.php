<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * Http 工具类
 * 提供一系列的Http方法
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */

namespace Net;

class HttpZStack{//类定义开始
	public $isPost     = false;
	public $TimeOut    = 3;
	public $PostData   = null;
    public $header     = false;
    public $username   = '';
    public $password   = '';

    public $httpheader = '';
	public $type       = 'GET';
	
	function getdata($url){
    	$content = '';
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HEADER, $this->header);
		// curl_setopt($ch, CURLOPT_POST,$this->isPost);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpheader);
		
        // 判断类型
        switch ($this->type){
            case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, true);break;
            case "POST": curl_setopt($ch, CURLOPT_POST,true);break;
            case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");break;
            case "PATCH": curl_setopt($ch, CULROPT_CUSTOMREQUEST, 'PATCH');break;
            case "DELETE":curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");break;
        }

        if($this->PostData){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->PostData);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT,$this->TimeOut);
		$content = curl_exec($ch);
		curl_close($ch);
    	return $content;
    }
}//类定义结束

?>