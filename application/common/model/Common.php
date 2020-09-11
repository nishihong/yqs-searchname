<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Common
 * +------------------------------------------------------------+
 * 公共模型
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 1.0
 *
 * Modified at : 2019-04-09 11:18
 *
 */

namespace app\common\model;

use think\Model;

class Common extends Model 
{
    /**
     * 获取单条记录
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getOne($condition = [], $field = '*', $order = 'order_id')
    {
        $data = $this->field($field)->where($condition)->order($order)->find();
        return $data;
    }

    /**
     * 获取带附件单条记录
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function getSingle($condition = [], $field = 'a.*,b.root_path', $order = 'a.order_id', $limit = ''){
        $table = $this->getTable();
        $product = $this->table($table.' a')
            ->leftJoin('c_file b', 'a.image = b.id')
            ->field($field)
            ->where($condition)
            ->order($order)
            ->limit($limit)
            ->find();

        return $product;
    }

    /**
     * 获取多条记录
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getAll($condition = [], $field = '*', $order = 'order_id', $limit = ''){
        $data = $this->field($field)->where($condition)->order($order)->limit($limit)->select();
        return $data;
    }

    /**
     * 获取带附件多条记录
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function getList($condition = [], $field = 'a.*,b.root_path', $order = 'a.order_id', $limit = ''){
        $table = $this->getTable();
        $data = $this->table($table.' a')
            ->leftJoin('c_file b', 'a.image = b.id')
            ->field($field)
            ->where($condition)
            ->order($order)
            ->limit($limit)
            ->select();

        return $data;
    }

    /**
     * 获取栏目单个字段属性
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return string
     */
    public function getField($condition = [], $field = 'id', $order = 'order_id'){
        $data = $this->where($condition)->order($order)->value($field);
        return $data;
    }
}
?>