<?php
/**
 *
 * +------------------------------------------------------------+
 * @category UserActionCheck
 * +------------------------------------------------------------+
 * 错误自定义
 * +------------------------------------------------------------+
 *
 * @author wmz
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at :
 *
 */

namespace app\common\exception;


use Exception;
use think\exception\Handle;


class Http extends Handle {

    public function render(Exception $exception){

        if(config('app_debug')){
            return parent::render($exception);
        }else{
            header("Location:".url('ErrorPage/index'));
        }
    }


}

?>
