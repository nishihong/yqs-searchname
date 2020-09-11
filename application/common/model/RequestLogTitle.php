<?php
/**
 *
 * +------------------------------------------------------------+
 * @category RequestLogTitle
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

class RequestLogTitle extends Common
{
    /**
     * 获取某天标题的汇总数据
     * @param int $time_day  某天的时间戳
     * @return array
     */
    public function getDayData($time_day)
    {
        // 获取所有标题列表
        $all_title = model('RequestConfig')->field('url,title')->group('url')->select();
        // dump($all_title);exit;

        $datas = [];
        // 所有标题 计算结果
        foreach ($all_title as $k => $vo) {
            if (empty($vo['url'])) {
                continue;
            }

            // 昨天该标题的数据
            $yesterday_condition = [];
            $yesterday_condition[] = ['url', '=', $vo['url']];
            $yesterday_condition[] = ['time_day', '=', $time_day - 86400];
            $yesterday_info = $this->where($yesterday_condition)->find();

            $condition = [];
            $condition[] = ['url', '=', $vo['url']];
            $condition[] = ['addtime_day', '=', $time_day];
            // dump($condition);exit;

            $ad_num = model('RequestLog')->where($condition)
                            ->where([
                                ['ad_url', 'neq', ''],
                            ])
                            ->count();

            $all_search_nums = model('RequestLog')
                            ->where($condition)
                            ->field('
                                SUM(IF(refer=1,1,0)) AS baidu_num,
                                SUM(IF(refer=2,1,0)) AS so_num,
                                SUM(IF(refer=3,1,0)) AS sogou_num,
                                SUM(IF(refer=4,1,0)) AS sm_num,
                                SUM(IF(refer=5,1,0)) AS other_num
                            ')
                            ->find();

            $baidu_num = (int)$all_search_nums['baidu_num'];
            $so_num = (int)$all_search_nums['so_num'];
            $sogou_num = (int)$all_search_nums['sogou_num'];
            $sm_num = (int)$all_search_nums['sm_num'];
            $other_num = (int)$all_search_nums['other_num'];

            // 总和用php算，减少mysql压力
            $all_num = $baidu_num + $so_num + $sogou_num + $sm_num + $other_num;

            $data = [
                'title'  => $vo['title'],
                'url'  => $vo['url'],
                'time_day'  => $time_day,
                'all_num'  => $all_num,
                'ad_num'  => $ad_num,
                'baidu_num'  => $baidu_num,
                'so_num'  => $so_num,
                'sogou_num'  => $sogou_num,
                'sm_num'  => $sm_num,
                'other_num'  => $other_num,

                'all_differ_num'  => $all_num - $yesterday_info['all_num'],
                'ad_differ_num'  => $ad_num - $yesterday_info['ad_num'],
                'baidu_differ_num'  => $baidu_num - $yesterday_info['baidu_num'],
                'so_differ_num'  => $so_num - $yesterday_info['so_num'],
                'sogou_differ_num'  => $sogou_num - $yesterday_info['sogou_num'],
                'sm_differ_num'  => $sm_num - $yesterday_info['sm_num'],
                'other_differ_num'  => $other_num - $yesterday_info['other_num'],
            ];

            $datas[] = $data;

            if ($k % 20 == 0) {
                sleep(1);
            }
        }
        // dump($datas);exit;

        return $datas;
    }
}
