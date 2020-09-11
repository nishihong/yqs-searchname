<?php
/**
 * @filename app.php
 * @author nish
 * @version 1.0
 * @copyright http://www.onlyni.com
 *
 * Modified at : 2018-07-15 15:48
 * 
 * 如果有设置 就会覆盖全局配置-配置文件
 * 
 */
return [
    'CODE_SUCCESS' => 1,//操作成功状态标识码
    'CODE_ERROR' => 0,//错标识码
    'CODE_WARN' => 2,//警告信息标识码
    'CODE_INFO' => 3,//普通信息提示标识码
    'CODE_ERROR_RELOAD' => 4,//错标识码

    //栏目类型
    'CHANNEL_DENY' => '0', //栏目禁止关联内容
    'CHANNEL_ALLOW' => '1', //栏目允许关联内容
    'CHANNEL_OUTLINK' => '2', //栏目为外链形式
    'CHANNEL_PAGE' => '3', //单网页

    //时间格式设置
    'DT_YMD' => 'Y-m-d',
    'DT_YMDH' => 'Y-m-d H',
    'DT_YMDHI' => 'Y-m-d H:i',
    'DT_YMDHIS' => 'Y-m-d H:i:s',
    'DT_HIS' => 'H:i:s',
    'DT_HI' => 'H:i',
    'DT_YYMMDD' => 'Y年m月d日',
    //时间变量
    'TIMESTAMP' => time(),
    'AUTHKEY' => '@#*&^HFROPKFEDSGFSDBM&^'
];