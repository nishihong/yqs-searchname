<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category ActivityAction 
 * +------------------------------------------------------------+
 * 活动页
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2018
 * @version 1.0
 *
 * Modified at : 2018年7月24日 15:13:57
 *
 */
namespace app\admin\controller;
use app\admin\controller\Admin;
use think\App;


class ErrorPage extends Admin {

    public function index(){
        return $this->fetch('404');
    }
}
?>
