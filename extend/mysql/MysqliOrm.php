<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Mysql
 * +------------------------------------------------------------+
 * 原生 Mysql 工具类
 * 提供一系列的Mysql方法
 * +------------------------------------------------------------+
 *
 * @author Zqw
 * @copyright http://api.threelink.cn/ 2019
 * @version 1.0
 *
 * Create at : 2019-07-08 11:41
 *
 */
namespace mysql;

class MysqliOrm
{
    /**
     * 主机名
     * @var mixed
     */
    public $host;

    /**
     * 端口
     * @var mixed
     */
    public $port;

    /**
     * 用户名
     * @var mixed
     */
    public $login;

    /**
     * 密码
     * @var mixed
     */
    public $password;

    /**
     * 数据库名
     * @var mixed
     */
    public $dbname;

    /**
     * 数据库链接
     * @var \mysqli
     */
    public $link;

    /**
     * 表名
     * @var
     */
    private $table_name;

    /**
     * 预获取的字段名
     * @var
     */
    private $field;

    /**
     * 排序
     * @var
     */
    private $order;

    /**
     * limit限制条数
     * @var
     */
    private $limit;

    /**查询where条件
     * @var
     */
    private $where;

    /**
     * 查询关联join
     * @var array
     */
    private $join_arr = [];


    /**
     * MysqliOrm constructor.
     * @param $host
     * @param $port
     * @param $login
     * @param $password
     * @param $dbname
     */
    function __construct($host = '', $port = '', $login = '', $password = '', $dbname = '')
    {
        $this->host = $host ?: config('database.hostname');
        $this->port = $port ?: config('database.hostport');
        $this->login = $login ?: config('database.username');
        $this->password = $password ?: config('database.password');
        $this->dbname = $dbname ?: config('database.database');

        $this->link = mysqli_connect($this->host, $this->login, $this->password, $this->dbname);
        if (!$this->link) {
            throw new \Exception('数据库链接失败',103);
        }

    }


    /**
     * 新增数据
     * @param $data
     * @return array|bool|int|string
     * @throws \Exception
     */
    public function add($data)
    {
        $insert_key_str = implode(',', array_keys($data));
        $insert_val_str = "";
        foreach (array_values($data) as $v) {
            $insert_val_str .= "'".$v . "',";
        }
        $insertSql = sprintf("INSERT INTO %s(%s) VALUES (%s)", $this->table_name, $insert_key_str, trim($insert_val_str,','));
        return $this->query($insertSql);
    }

    /**
     * 批量插入
     * @param $data 预批量插入的数据，该数据为二维数组
     * @return array|bool|int|string
     * @throws \Exception
     */
    public function insertAll($data)
    {
        if (count(reset($data)) == 0) {
            return false;
        }
        //处理key
        $insert_key_str = implode(',', array_keys($data[0]));
        //处理value
        $insert_val_str = "";
        foreach ($data as $v) {
            $insert_val_str .= '(';
            foreach (array_values($v) as $b_v) {
                $insert_val_str .= "'".$b_v . "',";
            }
            $insert_val_str = trim($insert_val_str, ',');
            $insert_val_str .= '),';

        }
        $insertSql = sprintf("INSERT INTO %s(%s) VALUES %s", $this->table_name, $insert_key_str, trim($insert_val_str,','));
        return $this->query($insertSql);
    }

    /**
     * 搜索多条数据
     * @return array|bool|int|string
     * @throws \Exception
     */
    public function select()
    {
        $querySql = sprintf("SELECT %s FROM %s", $this->field ? : '*', $this->table_name);
        if (count($this->join_arr) > 0) {
            foreach ($this->join_arr as $v) {
                $querySql .= " " . $v;
            }
        }
        if (!empty($this->where)) {
            $querySql .= " WHERE " . $this->where;
        }
        if (!empty($this->order)) {
            $querySql .= " ORDER BY " . $this->order;
        }
        if (!empty($this->limit)) {
            $querySql .= " LIMIT " . $this->limit;
        }
        return $this->query($querySql);
    }


    /**
     * 搜索单条数据
     * @param string  $pri_id       主键
     * @return bool|mixed
     * @throws \Exception
     */
    public function find($pri_id = "")
    {
        if (!empty($pri_id)) {
            $this->where = "id=" . $pri_id;
        }
        $res = $this->select();
        return isset($res[0]) ? $res[0] : false;
    }


    /**
     * 获取某值
     * @param $single_field
     * @return mixed
     * @throws \Exception
     */
    public function value($single_field)
    {
        $this->field($single_field);
        $res = $this->find();
        return $res[$single_field];
    }


    /**
     * 修改
     * @param $list
     * @return array|bool|int|string
     * @throws \Exception
     */
    public function save($list)
    {
        if (empty($this->table_name)) {
            return false;
        }

        $updateFields = [];
        foreach ($list as $key => $value) {
            $up_value = addslashes($value);
            $updateFields[] = "`$key`='$up_value'";
        }
        $updateFields = implode(',', $updateFields);
        $querySql = sprintf("UPDATE %s SET %s", $this->table_name, $updateFields);

        if (!empty($this->where)) {
            $querySql .= ' WHERE ' . $this->where;
        }

        return $this->query($querySql);
    }


    /**
     * 获取表名
     * @param $tablename
     * @return $this
     */
    public function table($tablename)
    {
        $this->table_name = $tablename;
        return $this;
    }


    /**
     * 字段取值
     * @param $field
     * @return $this
     */
    public function field($field)
    {
        if (is_array($field)) {
            $this->field = implode(',', $field);
        } else {
            $this->field = $field;
        }
        return $this;
    }


    /**
     * 条件
     * @param $where
     * @param $is_string
     * @return $this
     */
    public function where($where, $is_string = "")
    {
        if ($is_string == '_string') {
            $this->where = $where;
        } else {
            if (is_array($where)) {
                $crondsArr = [];
                foreach ($where as $key => $value) {
                    $fieldValue = $value;
                    if (is_array($fieldValue)) {
                        $crondsArr[] = "$key " . $fieldValue[0] . ' ' . addslashes($fieldValue[1]);
                    } else {
                        $fieldValue = addslashes($fieldValue);
                        $crondsArr[] = "$key='$fieldValue'";
                    }
                }
                $this->where = implode(' AND ', $crondsArr);
            } else {
                $this->where = $where;
            }
        }
        return $this;
    }


    /**
     * 连表
     * @param $str
     * @return $this
     */
    public function join($str)
    {
         $this->join_arr[] = $str;
         return $this;
    }


    /**
     * 排序操作
     * @param $order
     * @return $this
     */
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }


    /**
     * limit操作
     * @param $limit
     * @param $limit_count
     * @return $this
     */
    public function limit($limit, $limit_count)
    {
        if (!$limit_count) {
            $this->limit = $limit;
        } else {
            $this->limit = $limit . "," . $limit_count;
        }
        return $this;
    }


    /**
     * 执行查询
     * @param $sql
     * @return array|bool|int|string
     * @throws \Exception
     */
    public function query($sql)
    {
        try {
            $query_prefix = strtolower(trim(substr($sql, 0, 6)));
            $result = mysqli_query($this->link, $sql);
            if (!$result) {
                return false;
            }
            switch ($query_prefix) {
                case "select":
                    $res = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $res[] = $row;
                    }
                    break;
                case "insert":
                    $res = mysqli_insert_id($this->link);
                    break;
                case "update":
                    $res = mysqli_affected_rows($this->link);
                    break;
                default:
                    $res = false;
            }
            $this->clear_attribute();

            if ($error = mysqli_error($this->link)) {
                throw new \Exception($error,101);
            } else {
                if ($res) {
                    return $res;
                } else {
                    throw new \Exception('数据库执行结果为空',102);
                }
            }

        } catch (\Exception $e) {
            if (!in_array($e->getCode(), ['101', '102', '103'])) {
                throw new \Exception('query捕获异常-FILE:'.$e->getFile().';LINE:'.$e->getLine().';MSG:'.$e->getMessage());
            }
        }
    }


    /**
     * 开启事务
     * @return bool
     */
    public function startTrans()
    {
        return mysqli_begin_transaction($this->link);
    }


    /**
     * 提交事务
     * @return bool
     */
    public function commit()
    {
        return mysqli_commit($this->link);
    }


    /**
     * 事务回滚
     */
    public function rollback()
    {
        return mysqli_rollback($this->link);
    }


    /**
     * 关闭连接
     */
    public function close()
    {
        return mysqli_close($this->link);
    }


    /**
     * 清除该类的属性
     * 注：由于该类主要用于swoole,swoole又是常驻内存的程序，php的垃圾回收机制在这里发挥
     *      不了作用，因为所有属性使用完都要清空
     */
    private function clear_attribute()
    {
        unset($this->table_name);
        unset($this->order);
        unset($this->limit);
        unset($this->where);
        $this->join_arr = [];
        $this->field = '*';
    }

}