<?php
/**
 *
 * +------------------------------------------------------------+
 * @category SeoLog
 * +------------------------------------------------------------+
 * Seo记录
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 1.0
 *
 * Modified at : 2020-9-8 10:51:08
 *
 */

namespace app\common\model;

class SeoLog extends Common
{
    /**
     * 获取需要搜索的列表
     * @param int $time_day  时间
     * @param int $type  搜索类型 1百度 2360 3搜狗 4神马
     * @return array
     */
    public function get_lists($time_day, $type = 1)
    {
    	switch ($type) {
    		case '1':
    			$all_title = $this->field('id,title,url,baidu_search_times')
			                ->where([
			                    // ['baidu_top', 'neq', 1],
			                    // ['baidu_search_times', '<', 5],
			                    ['baidu_top', 'eq', 0],
			                    ['time_day', '=', $time_day]
			                ])
			                ->limit(12)
			                ->order('baidu_search_times ASC, id ASC')
			                ->select();
    			break;
    		
    		case '2':
    			$all_title = $this->field('id,title,url')
			                ->where([
			                    ['so_top', '=', 0],
			                    ['time_day', '=', $time_day]
			                ])
			                ->limit(20)
			                ->order('id ASC')
			                ->select();
			    break;

    		case '3':
    			$all_title = $this->field('id,title,url')
			                ->where([
			                    ['sogou_top', '=', 0],
			                    ['time_day', '=', $time_day]
			                ])
			                ->limit(12)
			                ->order('id ASC')
			                ->select();
			    break;

    		case '4':
    			$all_title = $this->field('id,title,url')
			                ->where([
			                    ['sm_top', '=', 0],
			                    ['time_day', '=', $time_day]
			                ])
			                ->limit(50)
			                ->order('id ASC')
			                ->select();
			    break;

    		default:
    			$all_title = [];
    			break;
    	}

        return $all_title;
    }
}
