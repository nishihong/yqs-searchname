<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Ad.php
 * +------------------------------------------------------------+
 * 管理员模型
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 1.0
 *
 * Modified at : 2020-03-25 22:17:50
 *
 */

namespace app\common\model;

class Ad extends Common
{

    /**
     * 广告图片
     */
    public function AdImage()
    {
        return $this->hasOne('File', 'id', 'ad_image_id');
    }

    /**
     * 广告图片
     */
    public function AdWapImage()
    {
        return $this->hasOne('File', 'id', 'ad_wap_image_id');
    }

    /**
     * 获取完整的广告信息
     *
     * @access public
     * @return array
     */
    public function getAdList()
    {
        $ad_list = $this->order("ad_order_id ASC,id DESC")->select();

        //接口数据
        $datas = [];
        if (!empty($ad_list)) {
            $time = time();
            foreach ($ad_list as $key => $vo) {
                if (($time > $vo['ad_start_time'] && $time < $vo['ad_end_time']) || ($vo['ad_end_time'] == 0)) {
                    //标题
                    $datas[$key]['title'] = $vo['ad_title'];
                    //跳转地址
                    $datas[$key]['url'] = $vo['ad_url'];
                    //是否弹窗新窗口
                    $datas[$key]['target'] = $vo['ad_target'];

                    //生成页面图片路径-pc与手机
                    if ($vo['ad_image_id']) {
                        $datas[$key]['root_path'] = model("File")->getRootPath($vo['ad_image_id']);
                    }

                    if ($vo['ad_wap_image_id']) {
                        $datas[$key]['wap_root_path'] = model("File")->getRootPath($vo['ad_wap_image_id']);
                    }
                }
                unset($ad_list[$key]['id']);
            }
        }
        return $datas;
    }

}
