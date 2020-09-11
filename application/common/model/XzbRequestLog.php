<?php
/**
 *
 * +------------------------------------------------------------+
 * @category XzbRequestLog
 * +------------------------------------------------------------+
 * 请求日志管理
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at : 2020-9-1 10:47:29
 *
 */

namespace app\common\model;

class XzbRequestLog extends Common
{
    /**
     * 获取每天的请求数
     * @param array $condition  查询条件
     * @return array
     */
    public function get_lists($condition)
    {
        $datas  = $this->field('count(*) as click_num,addtime_day')
                ->where($condition)
                ->group('addtime_day')
                ->order('addtime_day asc')
                ->select()
                ->toArray();
        return $datas;
    }
}
