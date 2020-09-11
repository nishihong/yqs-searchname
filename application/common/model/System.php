<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category System 
 * +------------------------------------------------------------+
 * 系统配置模型
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * @version 2.0
 *
 * Modified at : 2019-03-25 15:04
 *
 */

namespace app\common\model;
use app\common\model\Common;

class System extends Common {
    //数据库表
    protected $table 		= 'dj_system';

    /**
     * 根据key获取值
     * @param string $p_key
     * @return mixed|string
     */
	public function value($p_key)
	{
		$tmp = $this->where("identy='{$p_key}'")->find();
		if(!empty($tmp)){
			return $tmp['attvalue'];
		}
		return '';
	}

    /**
     * 保存配置
     * @param string $type
     * @return bool
     */
	public function submitData($type = 'base')
	{
		$config = input('post.config');

		foreach ($config as $identy => $value){
			if (empty($identy)) continue;
            $condition = [];
			$condition['type']   = $type;
			$condition['identy'] = $identy;

			$set = [];
			$set['attvalue'] = $value;
			$set['type'] 	 = $type;

			//如果对应值存在，则更新；不存在，则插入
			$mcount = $this->where($condition)->count();
			if ($mcount > 0){
				$this->where($condition)->update($set);
			}else{
				$set['identy'] 	= $identy;
				$set['type'] 	= $type;
				$this->insert($set);
			}
		}
		return true;
	}

    /**
     * 获取相关配置信息
     * @param $type
     */
	public function getValue($type = 'base')
	{
		$datas = $this->where("type='".$type."'")->select();

		$tmp = [];
		if ($datas) {
			foreach ($datas as $vo){
				$tmp[$vo['identy']] = $vo['attvalue'];
			}
		}
		return $tmp;
	}
}

?>