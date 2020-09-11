<?php


/**
 * 后台排序
 * @content 字段内容
 * @dfield 默认字段名称与数据库字段名一致
 * @type 默认排序类型 ASC DESC
 * @field 字段名是否是选中的
 */
function get_order($content, $dfield, $order_type, $order_field){
    $get = input('param.', []);
    if (!empty($get)) {
        $list = extend($get, ['order_field'=>$dfield, 'order_type'=>$order_type]);
    }else{
        $list = ['order_field' => $dfield, 'order_type' => $order_type];
    }
    $url = get_url($list);
    if($dfield == $order_field){
        if($order_type == 'ASC'){
            $title = $content."升序";
            $class = "order-underline order-asc";
        }else{
            $title = $content."降序";
            $class = "order-underline order-desc";
        }
    }else{
        $title = $content;
        $class = "order-underline";
    }

    $a = '<a href="'.$url.'" title="'.$title.'" class="'.$class.'">'.$content.'</a>';

    return $a;
}

/**
 * 获取图片路径
 */
function img_src($id){

    $data =  model('File')->where(array('id'=>$id))->find();
    return $data['root_path'];
}

/**
 * 转码操作
 */
function transcode($str){
    $str  = strtolower(trim($str));
    $code = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
    $str  = mb_convert_encoding($str, 'ASCII', $code);
    $str  = str_replace('?','',$str);

    return $str;
}

/**
 * 左侧栏目菜单是否隐藏
 */
function left_menu_hidden($mod){
    $is_show = true;
    if($mod == 'SystemLadder'){
        $is_show = false;
    }else if($mod == 'SystemWatersupply'){
        $is_show = false;
    }
    return $is_show;
}

if (!function_exists('get_confmsg')) {
    /**
     * 获取配置信息
     */
    function get_confmsg($type = 'base', $identy = ''){
        $condition = array();
        if($type){
            $condition['type'] = $type;
        }
        if($identy){
            $condition['identy'] = $identy;
        }

        if($type && $identy){
            $data = model('System')->where($condition)->value('attvalue');
        }else{
            $data = model('System')->field('attvalue')->where($condition)->select();
        }

        return $data;
    }
}

/**
 *
 * +------------------------------------------------------------+
 * @name html_editor
 * +------------------------------------------------------------+
 * 调用副文本编辑器
 * +------------------------------------------------------------+
 *
 * @author anzai <sba3198178@126.com>
 * @version 1.0
 *
 * @example
 *
 * @param string    $p_fileType 要上传的文件类型[image|file|flash|media]
 * @param string    $p_name     内容ID
 * @param string    $p_value    默认内容
 * @param int       $p_width    宽度
 * @param int       $p_height   高度
 * @param string    $p_bar      编辑使用的控件序列[默认default]
 *
 */
function html_editor($p_fileType, $p_name='content', $p_value='', $p_width='97%', $p_height=220, $p_bar='default', $loaded=false){
    static $toolbar=null, $load=false, $idx=0;
    if ($toolbar === null){
        // $toolbar = require_once ROOT .'Conf/Logic/toolbar.cfg.php';
        $toolbar = config('logic.toolbar');
    }
    $basePath = '/static/js/kindeditor/';
    $jsvar = 'FORM_EDITORS['.$idx.']';
    $idx++;
    $item = isset($toolbar[$p_bar]) ? $toolbar[$p_bar] : $toolbar['default'];
    $html = '';
    if ($load === false && $loaded === false){
        $html = '<script charset="utf-8" src="'. $basePath .'kindeditor-min.js"></script>';
        $html .= '<script charset="utf-8" src="'. $basePath .'lang/zh_CN.js"></script>';
        $load = true;
    }

    $html .= '<textarea id="'.$p_name.'" name="'.$p_name.'" style="width:'.$p_width.';height:'.$p_height.'px;">'. $p_value .'</textarea>';
    // $html .= '<script>if(!FORM_EDITORS){var FORM_EDITORS = [];}KindEditor.ready(function(K){'.$jsvar.' = K.create("#'.$p_name.'" ,{allowFileManager:true,fileManagerJson:"'. url('Attachment/kindeditorfilemanage') .'",pasteType:1,items:'.$item.',uploadJson:"'. url('Attachment/kindeditorupload', 'cprs=1&from=Kindeditor&file_type='. $p_fileType) .'", afterCreate : function(){this.sync();}, afterBlur:function(){this.sync();}});});</script>';
    $html .= '<script>if(!FORM_EDITORS){var FORM_EDITORS = [];}KindEditor.ready(function(K){'.$jsvar.' = K.create("#'.$p_name.'" ,{allowFileManager:true,fileManagerJson:"'. url('Attachment/kindeditorfilemanage') .'",pasteType:1, afterCreate : function(){this.sync();}, afterBlur:function(){this.sync();}});});</script>';
    return $html;
}
//输出安全的html
function H($text, $tags = null) {
    $text	=	trim($text);
    //完全过滤注释
    $text	=	preg_replace('/<!--?.*-->/','',$text);
    //完全过滤动态代码
    $text	=	preg_replace('/<\?|\?'.'>/','',$text);
    //完全过滤js
    $text	=	preg_replace('/<script?.*\/script>/','',$text);

    $text	=	str_replace('[','&#091;',$text);
    $text	=	str_replace(']','&#093;',$text);
    $text	=	str_replace('|','&#124;',$text);
    //过滤换行符
    $text	=	preg_replace('/\r?\n/','',$text);
    //br
    $text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
    $text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
    //过滤危险的属性，如：过滤on事件lang js
    while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1],$text);
    }
    while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].$mat[3],$text);
    }
    if(empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
    }
    //允许的HTML标签
    $text	=	preg_replace('/<('.$tags.')( [^><\[\]]*)>/i','[\1\2]',$text);
    $text = preg_replace('/<\/('.$tags.')>/Ui','[/\1]',$text);
    //过滤多余html
    $text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
    //过滤合法的html标签
    while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
    }
    //转换引号
    while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
    }
    //过滤错误的单个引号
    while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
    }
    //转换其它所有不合法的 < >
    $text	=	str_replace('<','&lt;',$text);
    $text	=	str_replace('>','&gt;',$text);
    $text	=	str_replace('"','&quot;',$text);
    //反转换
    $text	=	str_replace('[','<',$text);
    $text	=	str_replace(']','>',$text);
    $text	=	str_replace('|','"',$text);
    //过滤多余空格
    $text	=	str_replace('  ',' ',$text);
    return $text;
}

/**
 * 获取拼音首字母
 */
function get_pinyin($name)
{
    $py = new \ChinesePinyin\ChinesePinyin();
    $str = $py->TransformUcwords($name);
    return $str;
}

/**
 * 时间转化成秒
 * @param time int 时间
 * @param time_type string 时间类型
 * @return int
 */
function get_second($time, $time_type) {
    switch ($time_type) {
        case 'secend':
            $num = 1;
            break;
        case 'minute':
            $num = 60;
            break;
        case 'hour':
            $num = 60*60;
            break;
        case 'day':
            $num = 60*60*24;
            break;
        case 'month':
            $num = 60*60*24*30;
            break;
        case 'year':
            $num = 60*60*24*365;
            break;
        default:
            $num = 1;
            break;
    }

    $result = $time * $num;
    return ceil($result);
}

if (!function_exists('get_top_domain')) {
    /**
     * 获取域名中的一级域名
     * @param string $to_virify_url
     * @return string
     */
    function get_top_domain($to_virify_url = '')
    {
        $url   = $to_virify_url ? $to_virify_url : $_SERVER['HTTP_HOST'];
        $data = explode('.', $url);
        $co_ta = count($data);

        //判断是否是双后缀
        $zi_tow = true;
        $host_cn = 'com.cn,net.cn,org.cn,gov.cn';
        $host_cn = explode(',', $host_cn);
        foreach($host_cn as $host){
            if(strpos($url,$host)){
                $zi_tow = false;
            }
        }

        //如果是返回FALSE ，如果不是返回true
        if($zi_tow == true){

            // 是否为当前域名
            if($url == 'localhost'){
                $host = $data[$co_ta-1];
            }
            else{
                $host = $data[$co_ta-2].'.'.$data[$co_ta-1];
            }
        }
        else{
            $host = $data[$co_ta-3].'.'.$data[$co_ta-2].'.'.$data[$co_ta-1];
        }
        return $host;
    }
}


if (!function_exists('set_color_num')) {
    /**
     * 获取域名中的一级域名
     * @param string $to_virify_url
     * @return string
     */
    function set_color_num($num = 0)
    {
        if ($num == 0) {
            $color = 'blue';
        } elseif ($num > 0) {
            $color = 'red';
        } else {
            $color = 'green';
        }
        $string = "<font color='{$color}'>{$num}</font>";

        return $string;
    }
}