<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category 配置 
 * +------------------------------------------------------------+
 * 后台设置
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020年3月8日 15:41:29
 *
 */

return [

    //栏目类型
    'category'=>[
        'CHANNEL_DENY'=>0,           //栏目禁止关联内容
        'CHANNEL_ALLOW'=>1,          //栏目允许关联内容
        'CHANNEL_OUTLINK'=>2,        //栏目为外链形式
        'CHANNEL_PAGE'=>3            //单网页
    ],

    'toolbar'=>[
        'default' => '["source", "|", "undo", "redo", "|", "image", "multiimage", "|", "preview", "plainpaste", "wordpaste", "|", "justifyleft", "justifycenter", "justifyright",
        "justifyfull", "insertorderedlist", "insertunorderedlist", "indent", "outdent", "|" ,"clearhtml", "quickformat", 
        "formatblock", "fontsize", "|", "forecolor", "hilitecolor", "bold",
        "italic", "underline", "strikethrough", "removeformat", "|", "table", "hr", "emoticons", "baidumap", "link", "unlink" , "fullscreen"]',

        'weixin' => '["source", "image", "|", "plainpaste", "wordpaste", "|", "justifyleft", "justifycenter", "justifyright",
        "justifyfull", "insertorderedlist", "insertunorderedlist", "indent", "outdent", "|" ,"clearhtml", "quickformat", 
        "formatblock", "fontsize", "|", "forecolor", "hilitecolor", "bold",
        "italic", "underline", "strikethrough", "removeformat", "|", "table", "hr", "emoticons", "link", "unlink" , "fullscreen"]'
    ]
];