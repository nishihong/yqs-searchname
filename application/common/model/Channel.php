<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Channel
 * +------------------------------------------------------------+
 * 栏目模型
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

use app\common\model\Common;

class Channel extends Common 
{
	protected $_validate = array(
		// array('entitle', '', '该英文目录已被使用', 0, 'unique'),
	);
	protected $table = 'dj_channel';
	
	/**
	 * 获取所有栏目，并按二级显示在child里头
	 */
	public function getChannels()
	{ $channelMap = array(); $listTmp = $this->order('order_id ASC, id ASC')->select();
		if(!empty($listTmp)){
			foreach ($listTmp as $val){
				if($val['parent_id'] == 0){
					$channelMap[$val['id']] = $val;
					$channelMap[$val['id']]['_child'] = array();
					foreach ($listTmp as $val1){
						if($val1['parent_id'] == $val['id']){
							$channelMap[$val['id']]['_child'][$val1['id']] = $val1;
						}
					}
				}
			}
		}
		return $channelMap;
	}

	public function getChannelidByEntitle($entitle){
		$map['entitle'] = $entitle;
		$rs = $this->where($map)->find();
		return $rs['id'];
	}

    //获取导航
    public function nav_select($pid=0){
       $sql['pid'] = $pid;
       $sql['display'] = 1;
       $sql['nav'] = 1;
       $data = $this->where($sql)->order('order_id')->select();       
       foreach($data as $k=>$v){
           $data[$k]['pid_data'] = $this->nav_select($v['id']);
       }
       return $data;
    }

}

    
?>
