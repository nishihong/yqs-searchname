<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 解除未定义数组下标0的报错
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
use think\facade\Env;

/**
 * 缓存信息
 * @param $key
 * @param $value  为null删除缓存
 * @param string $type
 * @return mixed
 */
function buffer($key, $value = '', $type = 'default', $tag = 'cloud_index'){
    if($type == 'default'){
        $conf = null;
    }else{
        //没有安装内存扩展就是文件缓存
        if(($type == 'redis' && extension_loaded('redis')) || ($type == 'memcache' && extension_loaded('memcache')) || ($type == 'memcached' && extension_loaded('memcached'))) {
            $conf = config('cache.' . $type);
        }else{
            $conf = null;
        }
    }

    if(is_null($conf)) {
        $res = cache($key, $value);
    }else{
        if ($value === '') {
            $res = cache($conf)->get($key);
        } elseif (is_null($value)) {
            $res = cache($conf)->rm($key);
        } else {
            $res = cache($conf, $value = '', $options = null, $tag)->set($key, $value);
        }
    }

    // 测试先为空
    // $res = [];
    return $res;
}

/**
 * 获取原生的连接（默认是Redis）
 * @param string $type
 * @return mixed
 */
function get_cache_conn($type = 'redis'){
    $conf = config('cache.'.$type);
    $conn = cache($conf)->handler();
    return $conn;
}

/**
 * 安全过滤请求
 */
function filter_req($str)
{
    $str = trim($str);
    $str = htmlspecialchars($str, ENT_QUOTES);

    // 转码的问题有点奇怪，需要处理 nish 2020-9-5 14:43:22
    $str = urldecode($str);
    $str = urldecode($str);
    return $str;
}

/**
 * 安全解析
 */
function filter_decode($str){
    $str = trim($str);
    $str = htmlspecialchars_decode($str, ENT_QUOTES);
    return $str;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 获取IP地址的归属地
 */
function get_ip_address($ips){
    $ip = new \ip\Ip();
    $addr = $ip -> ip2addr($ips);

    return $addr;
}

/**
 * 获取当前客户端
 */
function get_client(){
    $browser = determinebrowser();
    $system = determineplatform();

    return $system."/".$browser;
}

/**
 * 获取后台单个配置的结果
 */
function confOne($type = 'api', $value = 'SMS_URL')
{
    $list = array();
    $list = model('System')->where("type='{$type}' AND identy='{$value}'")->field('attvalue')->find();
    return $list['attvalue'];
}

/**
 * 获取后台配置的参数
 * 数据中心表的配置
 */
function Conf($type = 'base')
{
    $list = array();
    $list = db()->table('kk_system')->where("type='{$type}'")->select();
    return $list;
}

/**
 * 获取后台配置的参数
 * 数据中心表的配置
 */
function Conf_Single($type = 'base', $value = '')
{
    $list = array();
    $list = db()->table('kk_system')->where("type='{$type}' AND identy='{$value}'")->field('attvalue')->find();
    return $list['attvalue'];
}

//智能合并多个数组
function extend($arr = [], $list = []){

    /*$args = func_get_args();
    $arr = array();
    if (!empty($args)){
        foreach ($args as $vo){
            $vo = is_array($vo) ? $vo : (empty($vo) ? array() : array($vo));
            $arr = array_merge($arr, $vo);
        }
    }

    if(!empty($arr)){
        foreach($arr as $k=>$v){
            $arr[$k] = $v;
        }
    }*/

    $datas = [];
    if (!empty($arr) && empty($list)) {
        $datas = $arr;
    } elseif (empty($arr) && !empty($list)) {
        $datas = $list;
    } elseif (!empty($arr) && !empty($list)){
        $datas = array_merge($arr, $list);
    }

    if (isset($datas['s']) && strpos($datas['s'], '.html') !== false) {
        unset($datas['s']);
    }
    return $datas;
}

/**
 * 文件上传类实例化
 * @param type $url
 * @param type $vars
 * @param type $layer
 * @return boolean
 */
function Upload($url, $vars=array(), $layer='controller') {
    $info   =   pathinfo($url);

    $_GET['action_name'] =   $info['basename'];
    $module =   $info['dirname'];

    $class  =   controller($module, $layer);
    if($class){
        if(is_string($vars)) {
            parse_str($vars,$vars);
        }
        return $class->run($vars);
    }else{
        return false;
    }
}

/**
 * 获取IP地址的归属地
 */
function get_local_ipaddress($ips){
    $ip = new \ip\Ip();
    $addr = $ip -> ip2addr($ips);

    return $addr;
}

/**
 * 加密方法
 *
 * @param String $p_string	要加密的字符串
 * @return String
 * @deprecated
 */
function encrypt_pwd($p_string)
{
    //TODO 为前端加密预留，可扩展
    $p_string = md5($p_string);
    return md5(crypt($p_string, substr($p_string, 0, 2)));
}

/**
 *
 * +------------------------------------------------------------+
 * @name format_dir
 * +------------------------------------------------------------+
 * 规划化路径名
 * +------------------------------------------------------------+
 *
 * @param string $pathname
 *
 */
function format_dir($pathname){
    if ($pathname){
        $pathname = str_replace(array('\\','/./', '/.//'), '/', $pathname . '/');
        $pathname = preg_replace('/\/{2,}/','/', $pathname);
        $pathname = preg_replace('/([^\/\.]+?)\.\/'.'/','\\1/', $pathname);
    }
    return $pathname;
}

function is_https() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}

/**
 * 获取基础域名
 */
function get_base_domain()
{
    $base_domain = $_SERVER['HTTP_HOST'];
    if(is_https()){
        $base_domain = "https://".$base_domain;
    }else{
        $base_domain = "http://".$base_domain;
    }
    return $base_domain;
}

/**
 * 生成随机码
 * @param $length
 */
function rand_num($length = 4, $number=false, $surstring='') {
    $chars = ($number ? '0123456789' : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') . $surstring;
    $password = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}


//获取访问用户的浏览器的信息
function determinebrowser () {
    $Agent = $_SERVER['HTTP_USER_AGENT'];

    $browseragent   = ""; //浏览器
    $browserversion = ""; //浏览器的版本
    if (preg_match('/MSIE ([0-9].[0-9]{1,2})/i',$Agent,$version)) {
        $browserversion=$version[1];
        $browseragent="Internet Explorer";
    } else if (preg_match('/Opera\/([0-9]{1,2}.[0-9]{1,2})/i',$Agent,$version)) {
        $browserversion=$version[1];
        $browseragent="Opera";
    } else if (preg_match( '/Firefox\/([0-9.]{1,5})/i',$Agent,$version)) {
        $browserversion=$version[1];
        $browseragent="Firefox";
    }else if (preg_match( '/Chrome\/([0-9.]{1,3})/i',$Agent,$version)) {
        $browserversion=$version[1];
        $browseragent="Chrome";
    }
    else if (preg_match( '/Safari\/([0-9.]{1,3})/i',$Agent,$version)) {
        $browseragent="Safari";
        $browserversion="";
    }
    else {
        $browserversion="";
        $browseragent="Unknown";
    }
    return $browseragent;
}

// 获取操作系统的信息
function determineplatform () {
    $Agent = $_SERVER['HTTP_USER_AGENT'];

    $browserplatform = '';
    if (preg_match('/win/i',$Agent) && strpos($Agent, '95')) {
        $browserplatform="Windows 95";
    }
    elseif (preg_match('/win 9x/i',$Agent) && strpos($Agent, '4.90')) {
        $browserplatform="Windows ME";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/98/i',$Agent)) {
        $browserplatform="Windows 98";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt 5.0/i',$Agent)) {
        $browserplatform="Windows 2000";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt 5.1/i',$Agent)) {
        $browserplatform="Windows XP";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt 6.0/i',$Agent)) {
        $browserplatform="Windows Vista";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt 6.1/i',$Agent)) {
        $browserplatform="Windows 7";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt 6.3/i',$Agent)) {
        $browserplatform="Windows 8";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt 6.4/i',$Agent)) {
        $browserplatform="Windows 10";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/32/i',$Agent)) {
        $browserplatform="Windows 32";
    }
    elseif (preg_match('/win/i',$Agent) && preg_match('/nt/i',$Agent)) {
        $browserplatform="Windows NT";
    }elseif (preg_match('/Mac OS/i',$Agent)) {
        $browserplatform="Mac OS";
    }
    elseif (preg_match('/linux/i',$Agent)) {
        $browserplatform="Linux";
    }
    elseif (preg_match('/unix/i',$Agent)) {
        $browserplatform="Unix";
    }
    elseif (preg_match('/sun/i',$Agent) && preg_match('/os/i',$Agent)) {
        $browserplatform="SunOS";
    }
    elseif (preg_match('/ibm/i',$Agent) && preg_match('/os/i',$Agent)) {
        $browserplatform="IBM OS/2";
    }
    elseif (preg_match('/Mac/i',$Agent) && preg_match('/PC/i',$Agent)) {
        $browserplatform="Macintosh";
    }
    elseif (preg_match('/PowerPC/i',$Agent)) {
        $browserplatform="PowerPC";
    }
    elseif (preg_match('/AIX/i',$Agent)) {
        $browserplatform="AIX";
    }
    elseif (preg_match('/HPUX/i',$Agent)) {
        $browserplatform="HPUX";
    }
    elseif (preg_match('/NetBSD/i',$Agent)) {
        $browserplatform="NetBSD";
    }
    elseif (preg_match('/BSD/i',$Agent)) {
        $browserplatform="BSD";
    }
    elseif (preg_match('/OSF1/i',$Agent)) {
        $browserplatform="OSF1";
    }
    elseif (preg_match('/IRIX/i',$Agent)) {
        $browserplatform="IRIX";
    }
    elseif (preg_match('/FreeBSD/i',$Agent)) {
        $browserplatform="FreeBSD";
    }
    if ($browserplatform=='') {$browserplatform = "Unknown"; }
    return $browserplatform;
}

//只支持中文
function getNeedBetween($kw,$mark1,$mark2){
    $st =strpos($kw,$mark1);
    $ed =strpos($kw,$mark2);
    if(($st===false||$ed===false)||$st>=$ed)
        return '';
    $kw=substr($kw,($st+3),($ed-$st-3));
    return $kw;
}

/**
 * 获取图片的位置
 * @param $img
 * @param int $width
 * @param int $height
 * @return array|bool
 */
function getPos($img, $width=100, $height=100){
    if (!is_file($img))  return false;
    $survey = getimagesize($img);
    //图像文件不存在
    if (false === $survey) return false;
    $top = $left = 0;
    if ($survey[0] <= $width && $survey[1] <= $height){
        $w = $survey[0];
        $h = $survey[1];
    }elseif ($survey[0] <= $width && $survey[1] > $height){
        $h = $height;
        $w = $survey[0] * ($height / $survey[1]);
    }elseif ($survey[0] > $width && $survey[1] <= $height){
        $w = $width;
        $h = $survey[1] * ($width / $survey[0]);
    }else{
        $h = $survey[1] * ($width / $survey[0]);
        if ($h <= $height){
            $w = $survey[0] >= $width ? $width : $survey[0];
        }else{
            $h = $survey[1] >= $height ? $height : $survey[1];
            $w = $survey[0] * ($height / $survey[1]);
        }
    }

    $top = ($height - $h + 1 - 1) / 2;
    $left = ($width - $w + 1 - 1) / 2;

    return array(
        'width' => (int)$w,
        'height' => (int)$h,
        'left' => (int)$left,
        'top' => (int)$top
    );
}

/**
 * 删除指定文件夹
 *
 * @param String $dir	真路径
 * @return boolean
 */
function delDir($p_dir)
{
    if(is_dir($p_dir)){
        $dh		 = opendir($p_dir);
        while ($file	 = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $p_dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    delDir($fullpath);
                }
            }
        }
        closedir($dh);
        if (rmdir($p_dir)) {
            return true;
        } else {
            return false;
        }
    }else{
        return false;
    }
}

//循环创建目录
function mk_dir($dir, $mode = 0777) {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!mk_dir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}

/**
 * 获取图片路径
 * @param string $type 前台配置或者后台配置
 * @param string $key  缩略图或者文件
 */
function getImagePath($type = 'ilogic', $key = 'crop'){
    $path = config($type.'.crop');
    // $path = '.'.$path.date('Ym').'/';
    // mk_dir($path);
    return $path;
}

/**
 * 过滤字符串
 */
function filter_str($str){

    $str = trim($str);
    $str = htmlspecialchars_decode($str, ENT_QUOTES);
    $str = strip_tags($str);
    $str = str_replace("&nbsp", '', $str);
    $str = str_replace("\\n", '', $str);
    $str = str_replace("　", '', $str);
    $str = str_replace("<br>", '', $str);

    return $str;
}

/**
 * 字符截取 支持UTF8/GBK
 *
 * @param String	$string		要截取的字符串
 * @param Int		$length		截取长度
 * @param String	$dot		截取成功，增加标识“...”等
 * @return String	截取后的字符串
 */
function cut_str($string, $length, $dot = '...')
{
    $strlen	 = strlen($string);
    if ($strlen <= $length)
        return $string;
    $string	 = str_replace(array (' ', '&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array ('∵', ' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if (strtolower('utf-8') == 'utf-8') {
        $length	 = intval($length - strlen($dot) - $length / 3);
        $n		 = $tn		 = $noc	 = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }
            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }
        $strcut	 = substr($string, 0, $n);
        $strcut	 = str_replace(array ('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array (' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    } else {
        $dotlen		 = strlen($dot);
        $maxi		 = $length - $dotlen - 1;
        $current_str = '';
        $search_arr	 = array ('&', ' ', '"', "'", '“', '”', '—', '<', '>', '·', '…', '∵');
        $replace_arr = array ('&amp;', '&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', ' ');
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key		 = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }
    return $strcut . $dot;
}

/**
 * 字符串转化为数字
 * @return int
 */
function string_to_int($string) {
    return (int)$string;
}


/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

/**
 * 生成随机密码
 * @param length int 长度
 * @return string
 */
function generate_password($length=8) {
    // 密码字符集，可任意添加你需要的字符
    $chars      = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password   = '';
    for ( $i = 0; $i < $length; $i++ ){
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $password;
}

/**
 * 根据带宽大小显示不一样的单位数据
 * @param data
 * @return string
 */
function get_bps_monitor_data($data) {
    if($data>=1000*1000*1000){
        $result = round($data/(1000*1000*1000),2).'Gbps';
    }elseif($data>=1000*1000){
        $result = round($data/(1000*1000),2).'Mbps';
    }elseif($data>=1000){
        $result = round($data/(1000),2).'Kbps';
    }else{
        $result = round($data,2).'bps';
    }

    return $result;
}

/**
 * 根据流量大小显示不一样的单位数据
 * @param data
 * @return string
 */
function get_flow_monitor_data($data) {
    if($data>=1024*1024*1024){
        $result = round($data/(1024*1024*1024),2).'GB';
    }elseif($data>=1024*1024){
        $result = round($data/(1024*1024),2).'MB';
    }elseif($data>=1024){
        $result = round($data/(1024),2).'KB';
    }else{
        $result = round($data,2).'B';
    }

    return $result;
}

/**
 * 华为云cdn根据流量大小显示不一样的单位数据
 * @param data
 * @return string
 */
function huawei_cdn_flux_data_count($data,$rate = 1024) {
    if($data>=$rate*$rate*$rate*$rate*$rate){
        $result = round($data/($rate*$rate*$rate*$rate*$rate),2).'PB';
    }elseif($data>=$rate*$rate*$rate*$rate){
        $result = round($data/($rate*$rate*$rate*$rate),4).'TB';
    }elseif($data>=$rate*$rate*$rate){
        $result = round($data/($rate*$rate*$rate),2).'GB';
    }elseif($data>=$rate*$rate){
        $result = round($data/($rate*$rate),2).'MB';
    }elseif($data>=$rate){
        $result = round($data/($rate),2).'KB';
    }else{
        $result = round($data,2).'B';
    }

    return $result;
}

/**
 * 华为云cdn根据带宽大小显示不一样的单位数据
 * @param data
 * @return string
 */
function huawei_cdn_bandwidth_data_count($data,$rate = 1000) {
    if($data>=$rate*$rate*$rate){
        $result = round($data/($rate*$rate*$rate),2).'Gbit/s';
    }elseif($data>=$rate*$rate){
        $result = round($data/($rate*$rate),2).'Mbit/s';
    }elseif($data>=$rate){
        $result = round($data/($rate),2).'Kbit/s';
    }else{
        $result = round($data,2).'bit/s';
    }

    return $result;
}

/**
 * 秒转化成中文
 * @param time int 时间
 * @return string
 */
function get_cn_time_in_zw($time) {
    if($time>=60*60*24*365){
        $result = '年';
    }elseif($time>=60*60*24*30){
        $result = '月';
    }elseif($time>=60*60*24){
        $result = '天';
    }elseif($time>=60*60){
        $result = '小时';
    }elseif($time>=60){
        $result = '分钟';
    }else{
        $result = '秒';
    }

    return $result;
}

/**
 * 秒转化成sz
 * @param time int 时间
 * @return string
 */
function get_cn_time_in_sz($time) {
    if($time>=60*60*24*365){
        $result = $time/(60*60*24*365);
    }elseif($time>=60*60*24*30){
        $result = $time/(60*60*24*30);
    }elseif($time>=60*60*24){
        $result = $time/(60*60*24);
    }elseif($time>=60*60){
        $result = $time/(60*60);
    }elseif($time>=60){
        $result = $time/(60);
    }else{
        $result = $time;
    }

    return round($result);
}

/**
 * 秒转化成中文
 * @param time int 时间
 * @return string
 */
function get_cn_time_in_zw_tc($time) {
    if($time>=60*60*24){
        $result = '天';
    }elseif($time>=60*60){
        $result = '小时';
    }elseif($time>=60){
        $result = '分钟';
    }else{
        $result = '秒';
    }

    return $result;
}

/**
 * 秒转化成sz
 * @param time int 时间
 * @return string
 */
function get_cn_time_in_sz_tc($time) {
    if($time>=60*60*24){
        $result = $time/(60*60*24);
    }elseif($time>=60*60){
        $result = $time/(60*60);
    }elseif($time>=60){
        $result = $time/(60);
    }else{
        $result = $time;
    }

    return round($result);
}

/**
 * 获取我的客户页面的cookies
 * root_name 路径
 */
function get_wdkh_cookies($root_name = ''){
    // 取cookie的值
    $filename = $root_name;
    $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
    //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
    $cookies = fread($handle, filesize ($filename));
    fclose($handle);

    // 解析 python存的cookies 用于curl
    $cookies_arr = json_decode($cookies,true);
    $cookies = '';
    foreach ($cookies_arr as $key => $value) {
        $cookies .= $value['name'].'='.$value['value'].';';
    }

    return $cookies;
}

/**
 * 获取网页的cookies某个值
 * root_name 路径
 */
function get_cookies_name($root_name = '', $name=''){
    // 取cookie的值
    $filename = $root_name;
    $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
    //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
    $cookies = fread($handle, filesize ($filename));
    fclose($handle);

    // 解析 python存的cookies 用于curl
    $cookies_arr = json_decode($cookies,true);
    $result = '';
    foreach ($cookies_arr as $key => $value) {
        if($value['name']==$name){
            $result = $value['value'];
        }
    }

    return $result;
}

/**
 * csrf_token的值
 * root_name 路径名称
 */
function get_csrf_token($root_name = ''){
    // 取cookie的值
    $filename = $root_name;
    $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
    //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
    $cookies = fread($handle, filesize ($filename));
    fclose($handle);

    return $cookies;
}

/**
 * 提交post表单功能
 * string $url 提交链接
 * string $cookies cookies
 * array $post 提交表单数据
 */
function submit_post_content_by_weibo($url, $cookies, $post){

    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息

    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面

    $headers = array(
    //     // ':authority:finance.aliyun.com',
    //     // ':method:POST',
    //     // ':path:/order/pay?spm=5176.2020520140.107.d30.b1057136Kd5S2q&order_id=202114576150230&auth_code=b4ac42fa8f9f20bb0f7620e888e43c0d',
    //     // ':scheme:https',
    //     // 'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
    //     // // 'accept-encoding:gzip, deflate, br',
    //     // 'accept-language:zh-CN,zh;q=0.9',
    //     // 'cache-control: no-cache',
    //     // // 'content-length:352',
    //     // 'content-type:application/x-www-form-urlencoded',
    //     // 'origin:https://finance.aliyun.com',
    //     'referer:https://finance.aliyun.com/order/pay?order_id=202115370820230&auth_code=bbec1b6c4573ba0a6bd393ebc2e9631c',
    //     // 'pragma: no-cache',
    //     // 'upgrade-insecure-requests: 1',
    //     // 'user-agent:"Mozilla/5.0 (iPod; U; CPU iPhone OS 2_1 like Mac OS X; ja-jp) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5F137 Safari/525.20"',
    //     'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',

        // 设置参数 破解 
        'Referer:https://weibo.com/mygroups?gid=4139497870308518&wvr=6&leftnav=1',
        
        'Host:weibo.com',
        'Origin:weibo.com',
        'X-Requested-With:XMLHttpRequest',
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
    // curl_setopt($ch, CURLOPT_AUTOREFERER, true);  

    curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));//

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容
    curl_close($ch);
    return $rs; //返回字符串
}

/**
 * 提交post表单功能
 * string $url 提交链接
 * string $cookies cookies
 * array $post 提交表单数据
 */
function submit_post_content($url='', $cookies='', $post=[]){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息

    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面

    // $headers = array(
    //     // ':authority:finance.aliyun.com',
    //     // ':method:POST',
    //     // ':path:/order/pay?spm=5176.2020520140.107.d30.b1057136Kd5S2q&order_id=202114576150230&auth_code=b4ac42fa8f9f20bb0f7620e888e43c0d',
    //     // ':scheme:https',
    //     // 'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
    //     // // 'accept-encoding:gzip, deflate, br',
    //     // 'accept-language:zh-CN,zh;q=0.9',
    //     // 'cache-control: no-cache',
    //     // // 'content-length:352',
    //     // 'content-type:application/x-www-form-urlencoded',
    //     // 'origin:https://finance.aliyun.com',
    //     'referer:https://finance.aliyun.com/order/pay?order_id=202115370820230&auth_code=bbec1b6c4573ba0a6bd393ebc2e9631c',
    //     // 'pragma: no-cache',
    //     // 'upgrade-insecure-requests: 1',
    //     // 'user-agent:"Mozilla/5.0 (iPod; U; CPU iPhone OS 2_1 like Mac OS X; ja-jp) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5F137 Safari/525.20"',
    //     'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',
    // );
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    // curl_setopt($ch, CURLOPT_AUTOREFERER, true);

    curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));//

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容
    curl_close($ch);
    return $rs; //返回字符串
}

/**
 * 提交post表单功能
 * string $url 提交链接
 * string $cookies cookies
 * array $post 提交表单数据
 */
function huawei_submit_post_content($url='', $cookies='', $post=[], $cftk=''){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息

    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面

    $headers = array(
        // 华为
        'cftk:'.$cftk,
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));//

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容
    curl_close($ch);
    return $rs; //返回字符串
}

/**
 * 华为云客户对应的客户经理列表操作 专门使用
 * 提交post表单功能
 * string $url 提交链接
 * string $cookies cookies
 * array $post 提交表单数据
 */
function huawei_customer_operator_submit_post_content($url='', $cookies='', $post=[], $cftk=''){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    $headers = array(
        // 华为
        'cftk:'.$cftk,
        'Content-Type: application/json',
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容
    curl_close($ch);
    return $rs; //返回字符串
}

/**
 * 提交get表单功能
 * string $url 提交链接
 * string $cookies cookies
 */
function submit_get_content($url='', $cookies=''){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息

    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面

    curl_setopt($ch, CURLOPT_COOKIE, $cookies);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容
    curl_close($ch);
    return $rs; //返回字符串
}

/**
 * 获取当前页面cookie
 * string $url 提交链接
 * string $cookies 设置cookie信息保存在指定的文件夹中
 */
function get_page_cookie($url, $cookie){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 1); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//设置跟踪页面的跳转，有时候你打开一个链接，在它内部又会跳到另外一个，就是这样理解
    // curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); //设置cookie信息保存在指定的文件夹中

    // curl_setopt($ch, CURLOPT_NOBODY, false);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array());
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行CURL

    // echo curl_getinfo($ch,CURLINFO_HTTP_CODE);

    // if(curl_errno($ch)){
    //     echo 'Curl error: '.curl_error($ch);exit(); //这里是设置个错误信息的反馈
    // }

    curl_close($ch); //关闭会话

    return $rs; //返回字符串
}

/**
 * 模拟登录
 * string $url 提交链接
 * string $cookies 设置cookie信息保存在指定的文件夹中
 * string $post 参数
 * 过CURL模拟登录并获取数据一些网站需要权限认证，必须登录网站后，才能有效地抓取网页并采集内容，这就需要curl来设置cookie完成模拟登录网页
 */
function login_post_cookie($url, $cookie, $post){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); //设置cookie信息保存在指定的文件夹中

    curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));//要执行的信息
    $rs = curl_exec($ch); //执行CURL

    // if(curl_errno($ch)){
    //     echo 'Curl error: '.curl_error($ch);exit(); //这里是设置个错误信息的反馈
    // }

    curl_close($ch); //关闭会话

    return $rs; //返回字符串
}

/**
 * 获取数据
 * string $url 提交链接
 * string $cookies 设置cookie信息保存在指定的文件夹中
 * get_content()中用curlopt_cookiefile可以读取到登录保存的cookie信息 最后讲页面内容返回.我们的目的是获取到模拟登录后的信息，也就是只有正常登录成功后菜能获取的有用的信息
 */
function login_get_content($url, $cookie){
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 1); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);//设置cookie信息保存在指定的文件夹中
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //发送

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容

    // if(curl_errno($ch)){
    //     echo 'Curl error: '.curl_error($ch);exit(); //这里是设置个错误信息的反馈
    // }

    curl_close($ch); //关闭会话
    return $rs; //返回字符串
}

/**
 * 神马搜索获取cookie
 * string $url 提交链接
 * string $cookies 设置cookie信息保存在指定的文件夹中
 * get_content()中用curlopt_cookiefile可以读取到登录保存的cookie信息 最后讲页面内容返回.我们的目的是获取到模拟登录后的信息，也就是只有正常登录成功后菜能获取的有用的信息
 */
function smGetCookie($url, $cookie)
{
    // dump($cookie);exit;
    // 
    $ch = curl_init(); //初始化curl模块
    curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
    curl_setopt($ch, CURLOPT_HEADER, 1); //是否显示头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪重定向页面
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);//设置cookie信息保存在指定的文件夹中
    // curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //发送
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);

    $header = array(
        "Cookie: sec=".get_csrf_token(Env::get('root_path').'source/spider/search/sm_sec.txt'), //预计每半小时更新一次
        // "User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Mobile Safari/537.36", //360
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0", //火狐
    );
    // dump($header);exit;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //SSL 报错时使用
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //SSL 报错时使用

    $rs = curl_exec($ch); //执行curl转去页面内容

    if(curl_errno($ch)){
        echo 'Curl error: '.curl_error($ch);exit(); //这里是设置个错误信息的反馈
    }

    curl_close($ch); //关闭会话
    return $rs; //返回字符串
}

/**
 * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
 * 注意：服务器需要开通fopen配置
 * @param $word 要写入日志里的文本内容 默认值：空值
 */
function logResults($word='') {
    $fp = fopen(Env::get('root_path')."source/log/pay/dz-log-".date("Y-m-d").".txt",'a');
    flock($fp, LOCK_EX);
    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}

/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSorts($para) {
    ksort($para);
    reset($para);
    return $para;
}

/**
 * 签名验证-快接支付
 * $datas 数据数组
 * $key 密钥
 */
function local_sign($datas = array(), $key = ''){
    $str = urldecode(http_build_query(argSorts(paraFilters1($datas))));
    $sign = md5($str."&key=".$key);
    return $sign;
}

/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilters($para) {
    $para_filter = array();
    foreach ($para as $key => $val) {
        if($key == "sign" || $key == "sign_type" || $val == "")continue;
        else    $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilters1($para) {
    $para_filter = array();
    foreach ($para as $key => $val) {
        if($key == "sign" || $val == "")continue;
        else    $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len=6,$type='',$addChars='') {
    $str ='';
    switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
    }
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    if($type!=4) {
        $chars   =   str_shuffle($chars);
        $str     =   substr($chars,0,$len);
    }else{
        // 中文随机字
        for($i=0;$i<$len;$i++){
          $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
        }
    }
    return $str;
}

/**
 * 对象转数组
 *
 * @param Object $p_object  对象
 * @return Array
 */
function objectToArray($p_object)
{
    $_array = is_object($p_object) ? get_object_vars($p_object) : $p_object;
    $array = [];
    if(isset($_array)){
        foreach ($_array as $key => $value) {
            $value       = (is_array($value) || is_object($value)) ? objectToArray($value) : $value;
            $array[$key] = $value;
        }
    }
    return $array;
}

//ip转换为数字
function ip_int($ip){
    return sprintf("%u",ip2long($ip));
}

/**
 * 新增邮箱日志
 *
 * @object_id 用户ID
 * @email 手机号
 * @content 发送内容
 * @status 发送状态 1成功 2失败
 * @type 登录类型 1管理员 2用户
 * @el_category 短信类型 1服务器续费 2...
 * @el_system 系统类型 1财务 2快快云
 *
 */
function email_log($object_id, $email, $content, $status = 1, $type = 1, $el_category = 1, $el_system=2){
    $res = model('EmailLog')->insert(array(
        'object_id'    => $object_id,
        'el_category'  => $el_category,
        'el_type'      => $type,
        'el_email'     => $email,
        'el_status'    => $status,
        'el_content'   => $content,
        'el_system'    => $el_system,
        'el_addtime'   => time()
    ));

    return $res;
}

/*
 * 获取指定日期所在星期的开始时间与结束时间
 */
function getWeekRange($date){
    $ret = array();
    $timestamp = strtotime($date);
    $w = strftime('%u',$timestamp);
    $ret['sdate'] = date('Y-m-d 00:00:00',$timestamp-($w-1)*86400);
    $ret['edate'] = date('Y-m-d 23:59:59',$timestamp+(7-$w)*86400);
    return $ret;
}

/*
 * 获取指定日期所在月的开始日期与结束日期
 */
function getMonthRange($date){
    $ret = array();
    $timestamp = strtotime($date);
    $mdays = date('t',$timestamp);
    $ret['sdate'] = date('Y-m-1 00:00:00',$timestamp);
    $ret['edate'] = date('Y-m-'.$mdays.' 23:59:59',$timestamp);
    return $ret;
}

/**
 * 获取时间段内的 时间戳
 * @param $fromTime 开始时间
 * @param $toTime   结束时间
 * @return array
 */
function calTime($fromTime, $toTime){
    //计算时间差
    $newTime    = $toTime - $fromTime;
    $day        = round($newTime / 86400);
    $data = [];
    for($i = 0 ; $i <= $day; $i++){
        $data[] = date('Y-m-d', strtotime("+{$i} day", $fromTime));
    }
    return $data;
}

/*
 * 以上两个函数的应用
 */
function getFilter($n){
    $ret = array();
    switch($n){
        case 1:// 昨天
            $ret['sdate'] = date('Y-m-d 00:00:00',strtotime('-1 day'));
            $ret['edate'] = date('Y-m-d 23:59:59',strtotime('-1 day'));
            break;
        case 2://本星期
            $ret = getWeekRange(date('Y-m-d'));
            break;
        case 3://上一个星期
            $strDate = date('Y-m-d',strtotime('-1 week'));
            $ret = getWeekRange($strDate);
            break;
        case 4: //上上星期
            $strDate = date('Y-m-d',strtotime('-2 week'));
            $ret = getWeekRange($strDate);
            break;
        case 5: //本月
            $ret = getMonthRange(date('Y-m-d'));
            break;
        case 6://上月
            $strDate = date('Y-m-d',strtotime('-1 month'));
            $ret = getMonthRange($strDate);
            break;
    }
    return $ret;
}

/**
 * js unescape php 实现
 * @param $string the string want to be escaped
 * @param $str
 * @param $ret
 */
function php_unescape($str){
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i ++) {
        if ($str[$i] == '%' && $str[$i + 1] == 'u') {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f) {
                $ret .= chr($val);
            } else {
                if ($val < 0x800) {
                    $ret .= chr(0xc0 | ($val >> 6)) .
                     chr(0x80 | ($val & 0x3f));
                } else {
                    $ret .= chr(0xe0 | ($val >> 12)) .
                     chr(0x80 | (($val >> 6) & 0x3f)) .
                     chr(0x80 | ($val & 0x3f));
                }
            }
            $i += 5;
        } else
            if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else {
                $ret .= $str[$i];
            }
    }
    return $ret;
}

/**
 * 加密解密字符串
 * @param $string 明文 或 密文
 * @param $operation DECODE表示解密,其它表示加密
 * @param $key 密钥
 * @param $expiry 密文有效期
 * @author nish
 */
function authcode($string, $operation = 'DECODE', $key = 'nish', $expiry = 0){
    // 过滤特殊字符
    if($operation == 'DECODE') {
       $string = str_replace('[a]','+',$string);
       $string = str_replace('[b]','&',$string);
       $string = str_replace('[c]','/',$string);
    }
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;
    // 密匙
    $key = md5($key ? $key : 'nish');
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
     // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        $ustr = $keyc.str_replace('=', '', base64_encode($result));
        // 过滤特殊字符
        $ustr = str_replace('+','[a]',$ustr);
        $ustr = str_replace('&','[b]',$ustr);
        $ustr = str_replace('/','[c]',$ustr);
        return $ustr;
    }
}

/**
* 输出  导出 csv格式
*/
function export_csv($filename,$data) {
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
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

if (!function_exists('get_url')) {
    /**
     * 组装URL地址
     *
     * @param array $data 传入的数组参数
     *
     * @return string
     */
    function get_url($data = [])
    {
        $url = '';
        if (!empty($data)) {
            $param = '?' . http_build_query($data);
        } else {
            $param = '';
        }

        $url = url(request()->controller().'/'.request()->action()) . $param;

        return $url;
    }
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

if (!function_exists('oneToTwo')) {
    /**
     * 一维数组转二维数组
     */
    function oneToTwo($arr = [], $key = 'id', $value = 'name')
    {
        $data = [];
        if (!empty($arr)) {
            $i = 0;
            foreach ($arr as $k => $v) {
                $data[$i][$key]     = $k;
                $data[$i][$value]   = $v;

                $i++;
            }
        }
        return $data;
    }
}

if (!function_exists('array_column_for_id')) {
    /**
     * 将二维数组中的某字段提出至索引
     */
    function array_column_for_id($arr = [], $key = 'id')
    {
        $data = [];
        foreach ($arr as $k => $v) {
            $v_id = $v[$key];
            unset($v[$key]);
            $data[$v_id] = $v;
        }
        return $data;
    }
}

if (!function_exists('one_to_two')) {
    /**
     * 一维数组转二维数组 v2.0
     */
    function one_to_two($arr = [], $key = 'id')
    {
        $data = [];
        if (!empty($arr)) {
            $i = 0;
            foreach ($arr as $k => $v) {
                $data[$i]   = $v;
                $data[$i][$key]     = $k;
                $i++;
            }
        }
        return $data;
    }
}

if (!function_exists('is_serialized_string')) {
    /**
     * 判断是不是被序列化后的字符串
     * @note : 判断a: s: 开头 或 ; } 结尾
     * @param $data
     * @return bool
     */
    function is_serialized_string($data) {
        if (preg_match( '/^(a|s):[0-9]+:.*(;|})$/s', trim($data)))
            return true;
        return false;
    }
}


if (!function_exists('get_millisecond')) {
    /**
     * 获取13位时间戳
     * @return string
     */
    function get_millisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1) + floatval($t2)) * 1000);
    }
}


if (!function_exists('get_msec_to_mescdate')) {
    /**
     * 毫秒转日期
     * @param $msectime 毫秒时间戳
     * @return string
     */
    function get_msec_to_mescdate($msectime)
    {
        $msectime = $msectime * 0.001;
        if (strstr($msectime,'.')) {
            sprintf("%01.3f", $msectime);
            list($usec, $sec) = explode(".", $msectime);
            $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
        } else {
            $usec = $msectime;
            $sec = "000";
        }
        $date = date("YmdHisx", $usec);
        return $mescdate = str_replace('x', $sec, $date);
    }
}


if (!function_exists('format_data')) {
    /**
     * 所有数字格式化成字符串
     * @param $p_data
     * @return
     */
    function format_data($p_data)
    {
        if (is_array($p_data)) {
            if (!empty($p_data)) {
                foreach ($p_data as $key=>$val) {
                    $p_data[$key] = $this->_format_data($val);
                }
            }
        } elseif (is_numeric($p_data)) {
            return (string) $p_data;
        } elseif ($p_data === null) {
            return '';
        }

        return $p_data;
    }
}

if (!function_exists('unicode_encode')) {
    /**
     * $str 原始中文字符串
     * $encoding 原始字符串的编码，默认GBK
     * $prefix 编码后的前缀，默认"&#"
     * $postfix 编码后的后缀，默认";"
     */
    function unicode_encode($str, $encoding = 'GBK', $prefix = '&#', $postfix = ';') {
        $str = iconv($encoding, 'UCS-2', $str);
        $arrstr = str_split($str, 2);
        $unistr = '';
        for($i = 0, $len = count($arrstr); $i < $len; $i++) {
            $dec = hexdec(bin2hex($arrstr[$i]));
            $unistr .= $prefix . $dec . $postfix;
        } 
        return $unistr;
    } 
}


if (!function_exists('unicode_decode')) {
    /**
     * $str Unicode编码后的字符串
     * $decoding 原始字符串的编码，默认GBK
     * $prefix 编码字符串的前缀，默认"&#"
     * $postfix 编码字符串的后缀，默认";"
     */
    function unicode_decode($unistr, $encoding = 'GBK', $prefix = '&#', $postfix = ';') {
        $arruni = explode($prefix, $unistr);
        $unistr = '';
        for($i = 1, $len = count($arruni); $i < $len; $i++) {
            if (strlen($postfix) > 0) {
                $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
            } 
            $temp = intval($arruni[$i]);
            $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
        } 
        return iconv('UCS-2', $encoding, $unistr);
    }
}


if (!function_exists('img_exists')) {
    /**
     * 判断图片是否存在
     * $url 外网地址
     */
    function img_exists($url) 
    {
        try {
            if(file_get_contents($url,0,null,0,1))
                return true;
            else
                return false;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}


if (!function_exists('get_between_string')) {
    /**
     * php截取指定两个字符之间字符串
     * $input 内容
     * $start 外网地址
     * $end 结尾
     */
    function get_between_string($input, $start, $end) {
        $substr = substr(
            $input, 
            strlen($start)+strpos($input, $start),
            (strlen($input) - strpos($input, $end))*(-1)
        );

        return $substr;
    }
}


if (!function_exists('edit_url')) {
    /**
     * 替换url中的/
     * $url 内容
     */
    function edit_url($url, $is_encode = 1) {
        if ($is_encode == 1) {
            $url = str_replace('/', '!@#$', $url);
        } else {
            $url = str_replace('!@#$', '/', $url);
        }

        return $url;
    }
}

if (!function_exists('getSearchUrl')) {
    /**
     * $search_type 对应搜索引擎
     */
    function getSearchUrl($search_type, $title = '') {
        switch ($search_type) {
            case '1':
            case '2':
                $url = 'http://m.baidu.com/s?word=' . $title;
                break;

            case '3':
            case '4':
                $url = 'https://m.so.com/s?q=' . $title;
                break;

            case '5':
            case '6':
                $url = 'https://m.sogou.com/web/searchList.jsp?keyword=' . $title;
                break;

            case '7':
            case '8':
                $url = 'https://m.sm.cn/s?q=' . $title;
                break;
            
            default:
                $url = '';
                break;
        }
        return $url;
    }
}

if (!function_exists('getRandUseragent')) {
    /** 
     * 获取随机的useragent
     **/
    function getRandUseragent() {
        $useragent = array(
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1) Gecko/20070309 Firefox/2.0.0.3',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1) Gecko/20070803 Firefox/1.5.0.12',
            'Opera/9.27 (Windows NT 5.2; U; zh-cn)',
            'Opera/8.0 (Macintosh; PPC Mac OS X; U; en)',
            'Mozilla/5.0 (Macintosh; PPC Mac OS X; U; en) Opera 8.0',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13',
            'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.12) Gecko/20080219 Firefox/2.0.0.12 Navigator/9.0.0.6',
            'Mozilla/5.0 (iPhone; U; CPU like Mac OS X) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/4A93 Safari/419.3',

            // 网上搜索的
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)",
            "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2pre) Gecko/20070215 K-Ninja/2.1.1",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/20080705 Firefox/3.0 Kapiko/3.0",
            "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko Fedora/1.9.0.8-1.fc10 Kazehakase/0.5.6",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",
            "Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1) Gecko/20070309 Firefox/2.0.0.3",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1) Gecko/20070803 Firefox/1.5.0.12",
            "Opera/9.27 (Windows NT 5.2; U; zh-cn)",
            "Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13",
            "Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 ",
            "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6; en-US) AppleWebKit/530.9 (KHTML, like Gecko) Chrome/ Safari/530.9 ",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.11 (KHTML, like Gecko) Ubuntu/11.10 Chromium/27.0.1453.93 Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36",

            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36',

             //PC端的UserAgent
            "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50",
            "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0",
            "Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.30729; .NET CLR 3.5.30729; InfoPath.3; rv:11.0) like Gecko",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0",
            "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
            "Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
            "Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11",
            "Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; The World)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SE 2.X MetaSr 1.0; SE 2.X MetaSr 1.0; .NET CLR 2.0.50727; SE 2.X MetaSr 1.0)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
             //移动端口
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",
            "Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",
            "Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",
            "Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Opera/9.80 (Android 2.3.4; Linux; Opera Mobi/build-1107180945; U; en-GB) Presto/2.8.149 Version/11.10",
            "Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13",
            "Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+",
            "Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.70 Safari/534.6 TouchPad/1.0",
            "NOKIA5700/ UCWEB7.0.2.37/28/999",
            "Openwave/ UCWEB7.0.2.37/28/999",
            "Mozilla/4.0 (compatible; MSIE 6.0; ) Opera/UCWEB7.0.2.37/28/999",
            "Mozilla/5.0 (Linux; Android 6.0; 1503-M02 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.2 TBS/036558 Safari/537.36 MicroMessenger/6.3.25.861 NetType/WIFI Language/zh_CN",
        );

        return array_rand($useragent);
    }
}