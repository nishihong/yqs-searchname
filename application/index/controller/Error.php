<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Error
 * +------------------------------------------------------------+
 * 空控制器
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 
 * @version 1.0
 *
 * Modified at : 2019-09-20 16:02
 *
 */
namespace app\index\controller;

use think\Controller;

class Error1 extends Controller
// class Error extends Controller
{

    /**
     * 空控制重定向
     */
    public function _empty()
    {
        $this->redirect(config('app_host'));
    }
}