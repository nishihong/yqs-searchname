<?php
/**
 * @filename menu.php
 * @author nish
 * @version 1.0
 * @copyright http://www.onlyni.com
 *
 * Modified at : 2018-07-15 15:40
 *
 * 后台基本设置
 */
//调用方法 config('logic.') 
return [
    //皮肤
    'skin'	=>	[
        ['name'=>'green.css', 'title'=>'绿色', 'color'=>'#037C11'],
        ['name'=>'blue.css', 'title'=>'蓝色', 'color'=>'#135EA5'],
        ['name'=>'darkgreen.css', 'title'=>'深蓝色', 'color'=>'#19B697'],
        ['name'=>'purple.css', 'title'=>'紫色', 'color'=>'#5859AB'],
        ['name'=>'yellow.css', 'title'=>'黄色', 'color'=>'#EC9A1B'],
        ['name'=>'black.css', 'title'=>'黑色', 'color'=>'#4E5056'],
    ],
    
    'NOFOUND_RECORDS' => '找不到任何相关记录！',
    
    'PAGE_NUM'  => 10,

    'VERSION' => '1.0.0',
    
    //文件上传路径
    'uploads' => '/upload/index/',
    //正则匹配的格式
    'preg' => [
        //电子邮箱
        'email'     => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        //手机号
        'mobile'    => '/^((\(\d{2,3}\))|(\d{3}\-))?(13|15|18|14|17|16)\d{9}$/',
        //QQ号
        'qq'        => '/^[1-9]\d{4,12}$/',
        //IP
        'ip'        => '/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/',
        //身份证
        'card'      => '/^(\d{14}|\d{17})(\d|[xX])$/',
        //普通密码
        'pwd'       => '/^(.){6,20}$/',
        //强密码
        'password'  => '/^([0-9]+[a-zA-Z]+)|([a-zA-Z][0-9]+)|(([a-zA-Z]+|[0-9]+)[^A-Za-z0-9]+)|([^A-Za-z0-9]+([a-zA-Z]+|[0-9]+)).*$/',
        //url地址
        'url'       => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
        //纯数字
        'number'    => '/^\d+$/',
        //微信支付js授权目录
        'jsapi_path'=>'/^(http|https):\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*\/$/',
        //货币
        'currency'  =>'/^\d+(\.\d+)?$/',
	    'decimal_number'=>'/^\d+(.?\d{1,2})?$/' //正数，最多包含两位小数
     ],

    //状态
    'status_list' => [
        0  => '禁用',
        1  => '启用',
    ],

    //状态
    'admin_status_list' => [
        '1'  => '启用',
        '2'  => '禁用'
    ],

    // 是否下载点击列表
    'is_down_click_list' => [
        1  => '是',
        0  => '否'
    ],


    // 是否第一列表
    'is_top_list' => [
        1  => '是',
        2  => '否'
    ],

    // seo搜索条件
    'seo_search_type_list' => [
        1  => '百度第一',
        2  => '百度非第一',
        3  => '360第一',
        4  => '360非第一',
        5  => '搜狗第一',
        6  => '搜狗非第一',
        7  => '神马第一',
        8  => '神马非第一',
    ],

    // seo对比搜索条件
    'seo_contrast_search_type_list' => [
        1  => '百度增加',
        2  => '百度减少',
        3  => '360增加',
        4  => '360减少',
        5  => '搜狗增加',
        6  => '搜狗减少',
        7  => '神马增加',
        8  => '神马减少',
    ],
];