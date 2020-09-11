<?php
/**
 * @filename setting.php
 * @author nish
 * @version 1.0
 * @copyright http://www.onlyni.com
 *
 * Modified at : 2018-07-15 15:34
 *
 * 系统设置配置文件
 */
return [
    'base'	 => [
        'WEB_TITLE' => ['title'	 => '网站标题', 'type' => 'text'],
        // 'WEB_LOGO' => ['title' => '网站Logo', 'type'	 => 'upload'],
        'WEB_KEYWORDS' => ['title' => '网站关键字', 'type' => 'text'],
        'WEB_DESCRIPTION' => ['title' => '网站描述', 'type' => 'text'],
        'WEB_COPYRIGHT_INFO' => ['title' => '版权信息', 'type'  => 'textarea'],
        'WEB_COPYRIGHT' => ['title' => '版权所有', 'type' => 'textarea'],
    ],

    'ad_index'	 => [
        'INDEX_DJSC_YX_URL' => ['title' => '首页电竞赛程地址', 'type' => 'text', 'desc' => '位置在首页电竞赛程右下边'],
        'INDEX_DJSC_YX_IMAGE' => ['title' => '首页电竞赛程图片', 'type' => 'upload', 'desc' => '位置在首页电竞赛程右下边'],

        'INDEX_ZDJS_DB_URL' => ['title' => '首页战队介绍地址', 'type' => 'text', 'desc' => '位置在首页战队介绍最下面'],
        'INDEX_ZDJS_DB_IMAGE' => ['title' => '首页战队介绍图片', 'type' => 'upload', 'desc' => '位置在首页电竞赛程最下面'],
    ],

    'ad_yxsp'   => [
        'YXSP_DYG_URL' => ['title' => '游戏视频地址', 'type' => 'text', 'desc' => '位置在游戏视频第一个视频'],

    ],

    'ad_djws'   => [
        'DJWS_DYG_URL' => ['title' => '电竞外设地址', 'type' => 'text', 'desc' => '位置在电竞外设第一个地址'],
        'DJWS_DYG_IMAGE' => ['title' => '电竞外设图片', 'type' => 'upload', 'desc' => '位置在电竞外设第一个图片'],
    ],
];