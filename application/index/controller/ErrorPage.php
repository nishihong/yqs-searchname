<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category ErrorPage 
 * +------------------------------------------------------------+
 * 报错页面
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020-03-28 23:06:25
 *
 */

namespace app\index\controller;

use app\index\controller\Common;

class ErrorPage extends Common{

    public function index(){
        return $this->fetch('404');
    }
}
