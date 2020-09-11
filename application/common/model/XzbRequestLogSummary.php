<?php
/**
 *
 * +------------------------------------------------------------+
 * @category XzbRequestLogSummary
 * +------------------------------------------------------------+
 * 请求日志管理
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 1.0
 *
 * Modified at : 2020-9-7 09:32:52
 *
 */

namespace app\common\model;

class XzbRequestLogSummary extends Common
{
    /**
     * 获取某天的汇总数据
     * @param int $time_day  某天的时间戳
     * @return array
     */
    public function getDayData($time_day)
    {
        $condition = [];
        $condition[] = ['addtime_day', '=', $time_day];
        // $all_num = model('XzbRequestLog')->where($condition)->count();

        $all_search_nums = model('XzbRequestLog')
                            ->where($condition)
                            ->field('
                                SUM(IF(is_down=1,1,0)) AS ad_num,
                                SUM(IF(refer=1,1,0)) AS baidu_num,
                                SUM(IF(refer=2,1,0)) AS so_num,
                                SUM(IF(refer=3,1,0)) AS sogou_num,
                                SUM(IF(refer=4,1,0)) AS sm_num,
                                SUM(IF(refer=5,1,0)) AS other_num
                            ')
                            ->find();
        // dump($all_search_nums);exit;

        $ad_num = (int)$all_search_nums['ad_num'];
        $baidu_num = (int)$all_search_nums['baidu_num'];
        $so_num = (int)$all_search_nums['so_num'];
        $sogou_num = (int)$all_search_nums['sogou_num'];
        $sm_num = (int)$all_search_nums['sm_num'];
        $other_num = (int)$all_search_nums['other_num'];

        // 总和用php算，减少mysql压力
        $all_num = $baidu_num + $so_num + $sogou_num + $sm_num + $other_num;

        $data = [
            'time_day'  => $time_day,
            'all_num'  => $all_num,
            'ad_num'  => $ad_num,
            'baidu_num'  => $baidu_num,
            'so_num'  => $so_num,
            'sogou_num'  => $sogou_num,
            'sm_num'  => $sm_num,
            'other_num'  => $other_num,
        ];

        return $data;
    }
}
