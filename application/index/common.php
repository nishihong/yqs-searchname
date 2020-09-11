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

/**
 * 阿里云拼接 cpu和内存
 * 根据region地区 选择类型
 */
function cpu_and_mem_by_aliyun($cpu,$mem,$region){
    $flag = false;
    // if(in_array($region, array('cn-hangzhou','cn-shanghai','cn-qingdao','cn-beijing','cn-zhangjiakou','cn-huhehaote','cn-shenzhen'))){
    // 	$flag = true;
    // }

    if($cpu == 1){
        if($mem == 1){
            $result = 'ecs.xn4.small';
        }elseif($mem == 2){
            $result = 'ecs.n4.small';
        }elseif($mem == 4){
            $result = 'ecs.mn4.small';
        }elseif($mem == 8){
            $result = 'ecs.e4.small';
        }
    }elseif($cpu == 2){
        if($mem == 2){
            $result = 'ecs.t5-c1m1.large';
        }elseif($mem == 4){
            $result = $flag ? 'ecs.n4.large':'ecs.sn1ne.large';
        }elseif($mem == 8){
            $result = $flag ? 'ecs.mn4.large':'ecs.sn2ne.large';
        }elseif($mem == 16){
            $result = 'ecs.se1ne.large';
        }
    }elseif($cpu == 4){
        if($mem == 4){
            $result = 'ecs.t5-c1m1.xlarge';
        }elseif($mem == 8){
            $result = $flag ? 'ecs.n4.xlarge':'ecs.sn1ne.xlarge';
        }elseif($mem == 16){
            $result = $flag ? 'ecs.mn4.xlarge':'ecs.sn2ne.xlarge';
        }elseif($mem == 32){
            $result = 'ecs.se1ne.xlarge';
        }
    }elseif($cpu == 8){
        if($mem == 8){
            $result = 'ecs.t5-c1m1.2xlarge';
        }elseif($mem == 16){
            $result = 'ecs.sn1ne.2xlarge';
        }elseif($mem == 32){
            $result = 'ecs.sn2ne.2xlarge';
        }elseif($mem == 64){
            $result = 'ecs.se1ne.2xlarge';
        }
    }elseif($cpu == 12){
        if($mem == 24){
            $result = 'ecs.sn1ne.3xlarge';
        }elseif($mem == 48){
            $result = 'ecs.sn2ne.3xlarge';
        }
    }elseif($cpu == 16){
        if($mem == 16){
            $result = 'ecs.t5-c1m1.4xlarge';
        }elseif($mem == 32){
            $result = 'ecs.sn1ne.4xlarge';
        }elseif($mem == 64){
            $result = 'ecs.sn2ne.4xlarge';
        }elseif($mem == 128){
            $result = 'ecs.se1ne.4xlarge';
        }
    }elseif($cpu == 24){
        if($mem == 48){
            $result = 'ecs.sn1ne.6xlarge';
        }elseif($mem == 96){
            $result = 'ecs.sn2ne.6xlarge';
        }
    }elseif($cpu == 32){
        if($mem == 64){
            $result = 'ecs.sn1ne.8xlarge';
        }elseif($mem == 128){
            $result = 'ecs.sn2ne.8xlarge';
        }
    }elseif($cpu == 64){
        if($mem == 128){
            $result = 'ecs.c5.16xlarge';
        }elseif($mem == 256){
            $result = 'ecs.g5.16xlarge';
        }
    }

    return $result;
}

/**
 * 腾讯云拼接 cpu和内存
 *
 */
function cpu_and_mem($cpu,$mem){
    if($cpu == 1){
        $cpu_name = 'SMALL';
    }elseif($cpu == 2){
        $cpu_name = 'MEDIUM';
    }elseif($cpu == 4){
        $cpu_name = 'LARGE';
    }elseif($cpu == 8){
        $cpu_name = '2XLARGE';
    }elseif($cpu == 12){
        $cpu_name = '3XLARGE';
    }elseif($cpu == 16){
        $cpu_name = '4XLARGE';
    }elseif($cpu == 24){
        $cpu_name = '6XLARGE';
    }elseif($cpu == 32){
        $cpu_name = '8XLARGE';
    }elseif($cpu == 48){
        $cpu_name = '12XLARGE';
    }elseif($cpu == 64){
        $cpu_name = '16XLARGE';
    }
    return 'S2.'.$cpu_name.$mem;
}

/**
 * 阿里云分销订单显示时间类型
 * $price_type 时间类型
 */
function aliyun_buy_time_type($price_type){
    $arr = explode(":", $price_type);

    $result = '';
    if($arr[0] == 1){
        $result = '按年 '.$arr[1].'年';
    }elseif($arr[0] == 2){
        $result = '按月 '.$arr[1].'月';
    }elseif ($arr[0] == 3) {
        $result = '按天 '.$arr[1].'天';
    }elseif ($arr[0] == 4) {
        $result = '按小时 '.$arr[1].'小时';
    }elseif ($arr[0] == 5) {
        $result = '按分钟 '.$arr[1].'分钟';
    }

    return $result;
}

/**
 * 阿里云分销订单到期时间
 * $price_type 时间类型
 */
function aliyun_end_time($start_time, $price_type){
    $arr = explode(":", $price_type);

    $end_time = '';
    if($arr[0] == 1){
        $end_time = strtotime('+'.$arr[1].' year', $start_time);
    }elseif($arr[0] == 2){
        $end_time = strtotime('+'.$arr[1].' month', $start_time);
    }elseif ($arr[0] == 3) {
        $end_time = strtotime('+'.$arr[1].' day', $start_time);
    }elseif ($arr[0] == 4) {
        $end_time = strtotime('+'.$arr[1].' hour', $start_time);
    }elseif ($arr[0] == 5) {
        $end_time = strtotime('+'.$arr[1].' second', $start_time);
    }else{
        $end_time = $start_time;
    }

    return $end_time;
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

/**
 * 生成唯一实例id
 * @param prefix string 默认前缀
 * @param length int 长度
 * @return string
 */
function get_instance_ids($prefix='', $length=20) {
    // 密码字符集，可任意添加你需要的字符
    $chars  = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key    = $prefix;
    for ( $i = 0; $i < $length; $i++ ){
        $key .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    $application = model('CloudHost')->where("instance_ids='".$key."'")->find();
    if($application){
        get_instance_ids($prefix,$length);
    }else{
        return $key;
    }
}

function exception_code2message($code ,$default = '请稍后再试'){
    $code_message = [
        '101' => '实例类型无效，该参数不支持，请重新选择！',
        '102' => '实例类型售罄，请选择其他实例类型',
        '103' => '指定实例配置已经售罄，请您更换实例规格或者更换地域创建。',
        '104' => '网络异常，请稍后再试。',
        '105' => '购买失败，请联系销售解决。',
        '106' => '续费失败，请稍后再试。',
        '107' => '升级失败，请稍后再试。',
        '108' => '网络异常，请稍后再试。',
        '109' => '该实例降配已达到最大允许次数！',
        '110' => '降配失败，请稍后再试。！',

        '201' => '购买失败，当前配置云盘已售罄。',
        '202' => '请先停止主机。',
    ];

    return isset($code_message[$code]) ? $code_message[$code] : $default;
}

/**
 * 获取简单验证码地址
 * @return string
 */
function captcha_src_simple(){
    return url('Passport/verify');
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