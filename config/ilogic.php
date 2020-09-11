<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category 配置 
 * +------------------------------------------------------------+
 * 配置
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020年3月8日 15:41:29
 *
 */

//调用方法 config('ilogic.')
return [
    //文件上传路径
    'uploads'               => '/upload/index/',

    //裁剪图片的路径
    'crop'                  => '/upload/index/crop/',

    //默认列表图片
    'default_img'           => '/upload/image/default.png',

    //默认图片
    'channel_default_img'   => '/upload/image/channel.png',

    'NOFOUND_RECORDS' => '找不到任何相关记录！',

    'VERSION' => '1.0.0',
    
    //redis
    'redis' =>  [
        'host' => '127.0.0.1',
        'port' => '6379',
    ],


    //统计来源
    'request_refer_list' =>  [
        '1' => '百度',
        '2' => '360',
        '3' => '搜狗',
        '4' => '神马',
        '5' => '直接访问',
    ],
];