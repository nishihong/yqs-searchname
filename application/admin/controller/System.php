<?php
/**
 *
 * +------------------------------------------------------------+
 * @category System
 * +------------------------------------------------------------+
 * 系统设置
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 2.0
 *
 * Modified at : 2020-03-29 15:42:00
 *
 */

namespace app\admin\controller;

use app\admin\controller\Common;
use think\paginator\driver\Bootstrap;

class System extends Common 
{
    /**
     * redis管理可操作人员 //线上:倪
     */
    private $auth_user_arr = ['1'];


    /**
     * 系统设置
     */
    public function index($type = 'base', $title = '系统参数设置')
    {
        $list = config('setting.'.$type);
        $Model = model('System');
        
        if(request()->isPost()){
            $status = $Model->submitData($type);

            // 跳转路径
            switch ($type) {
                case 'base':
                    $url = 'index';
                    break;
                
                default:
                    $url = $type;
                    break;
            }

            $this->success('修改成功', url(request()->controller().'/'.$url));
        }
        // dump($list);exit();
        $this->assign('data', $Model->getValue($type));
        $this->assign('list', $list);
        $this->assign('title', $title);

        return $this->fetch('index');
    }

    /**
     * 广告设置
     */
    public function ad_index()
    {
        $this->index('ad_index', '广告设置');

        return $this->fetch('index');
    }

    /**
     * 广告设置
     */
    public function ad_yxsp()
    {
        $this->index('ad_yxsp', '广告设置');

        return $this->fetch('index');
    }
    
    /**
	 * 广告设置
	 */
	public function ad_djws()
    {
        $this->index('ad_djws', '广告设置');

        return $this->fetch('index');
	}


	/**
	 * 缓存管理
	 */
	public function cache(){
	    if(request()->isPost()) {
	    	// 文件
            @delDir(env('runtime_path'));

            $this->success('删除文件缓存成功！', url(request()->controller() . '/cache'));
        }
        return $this->fetch();
    }

    /**
	 * memcache缓存管理
	 */
	public function clear_memcache(){
        // memcache
        if(extension_loaded('memcache')){
        	cache(config('cache.memcache'))->clear();
        }
        // memcached
        if(extension_loaded('memcached')){
        	cache(config('cache.memcached'))->clear();
        }

        $this->success('删除memcache缓存成功！', url(request()->controller() . '/cache'));
    }


    /**
     * redis缓存列表页
     */
    public function redis_conf_index()
    {
        $key    = input("param.cache_key");
        $type   = input("param.cache_type");

        $redis = cache(config('cache.redis'))->handler();

        $keys = $redis->keys('*');

        $datas = [];
        foreach ($keys as $v) {
            $data = $this->get_redis_data_for_key($v);
            if (in_array($data['type'], ['1','2','3','4','5'])) {
                $datas[] = [
                    'key'       => $v,
                    'type'      => $data['type_name'],
                    'type_id'   => $redis->type($v),
                    'ttl'       => $data['ttl'],
                    'intro'     => $data['intro'],
                    'value'     => $redis->type($v) == '1' ? $data['data'] : '',
                ];
            }
        }

        //数组搜素 - 类型
        if($type){
            $datas = array_filter($datas, function($data) use ($type) {
                return $data['type_id'] == $type;
            });
        }

        //数组搜素 - 键名模糊搜索
        if($key){
            $datas = array_filter($datas, function($data) use ($key) {
                return strpos($data['key'], $key) !== false;
            });
        }

        //数组分页
        $curPage  = input('get.page') ? input('get.page') : 1;//接收前段分页传值
        $listRow  = 12;//每页12行记录
        $showdata = array_slice($datas, ($curPage - 1) * $listRow, $listRow, true);// 数组中根据条件取出一段值，并返回
        $p = Bootstrap::make($showdata, $listRow, $curPage, count($datas), false, [
            'var_page' => 'page',
            'path'     => url(),//这里根据需要修改url
            'query'    => [],
            'fragment' => '',
        ]);

        return $this->fetch('', [
            'total_count'   => count($datas),
            'pageHtml'      => $p->render(),
            'datas'         => $p->toArray()['data']
        ]);
    }


    /**
     * redis查看和编辑
     */
    public function redis_conf_add()
    {
        $key  = trim(input("param.key"));
        $type = trim(input("param.type_id"));

        $redis = cache(config('cache.redis'))->handler();
        switch ($type)
        {
            //String
            case 1:
                $data = $redis->get($key);
                break;
            //Set
            case 2:
                break;
            //List
            case 3:
                break;
            //Zset
            case 4:
                break;
            //Hash
            case 5:
                break;
            default:
                $data= '';
                break;
        }

        if (request()->isPost()) {

            //线上:黑，倪，忠，盾    本地:黑
            if (!in_array($this->_admin_id, $this->auth_user_arr)) {
                $this->error('权限不足！');
            }

            $key    = input("post.key");
            $type   = input("post.type");
            $ttl    = input("post.ttl") > 0 ? input("post.ttl") : 0;
            $field  = input("post.hash_field");
            $value  = $_POST['cache_value'];

            if (empty($key)) {
                $this->error('键名不为空');
            }
            if (!in_array($type, ['1','2','3','4','5'])) {
                $this->error('类型有误');
            }
            if ($type == 1 && $ttl < 0) {
                $this->error('过期时间大于等于0');
            }
            if ($type == 5 && empty($field)) {
                $this->error('field不能为空');
            }
            if (empty($value)) {
                $this->error('值不能为空');
            }

            switch ($type)
            {
                //String
                case 1:
                    if ($ttl == '0' || $ttl == '-1') {
                        $res = $redis->set($key, $value);
                    } else {
                        $res = $redis->setex($key, $ttl, $value);
                    }
                    break;
                //Set
                case 2:
                    $this->error('该类型暂未开放');
                    break;
                //List
                case 3:
                    $res = $redis->rPush($key, $value);
                    break;
                //Zset
                case 4:
                    $this->error('该类型暂未开放');
                    break;
                //Hash
                case 5:
                    $res = $redis->hSet($key, $field, $value);
                    break;
                default:
                    $data= '';
                    break;
            }
            if ($res) {
                $this->success('操作redis缓存成功！', 'redis_conf_index');
            } else {
                $this->error('操作redis缓存失败');
            }
        }

        return $this->fetch('', [
            'type' => $type,
            'data' => $data,
        ]);
    }


    /**
     * redis缓存展示页面
     */
    public function redis_conf_show()
    {
        $key    = input("param.key");
        $type   = input("param.type");
        $data = $this->get_redis_data_for_key($key);
        switch ($type)
        {
            case 1:

                $this->assign([
                    'data' => $data,
                    'is_seri' => is_serialized_string($data['data'])
                ]);
                break;
            case 3:
                $this->assign([
                    'data' => $data,
                    'is_seri' => 1
                ]);
                break;
            case 5:
                $this->assign([
                    'data' => $data,
                    'is_seri' => 1
                ]);
                break;
        }

        return $this->fetch();
    }


    /**
     * 编辑队列中的某行的值
     */
    public function edit_list_data()
    {
        $this->redis_role();

        $key    = input("param.key");
        $index  = input("param.index");
        $value  = $_GET['value'];
        $redis  = cache(config('cache.redis'))->handler();
        $res    = $redis->Lset($key, $index, $value);

        return json([
            'code' => $res ? 0 : 1,
        ]);

    }


    /**
     * list新增行
     */
    public function add_list_row()
    {
        $this->redis_role();

        $key   = input("param.key");
        $value = $_GET['value'];

        $redis = cache(config('cache.redis'))->handler();
        $res   = $redis->rPush($key, $value);

        return json([
            'code' => $res ? 0 : 1,
        ]);
    }


    /**
     * list删除指定行
     * @note 先编辑行值为特定值，然后移除值为特定值的行
     */
    public function del_list_row()
    {
        $this->redis_role();

        $key        = input("param.key");
        $index      = input("param.index");
        $value      = '---KK_VALUE_REMOVED_BY_RDM---';
        $redis      = cache(config('cache.redis'))->handler();
        $ori_value  = $redis->Lrange($key, $index, $index);
        $edit_res   = $redis->Lset($key, $index, $value);
        if ($edit_res) {
            $res = $redis->Lrem($key, $value, 0);
            if (!$res) {
                $redis->Lset($key, $index, $ori_value);
            }
        } else {
            $res = false;
        }

        return json([
            'code' => $res ? 0 : 1,
        ]);
    }


    /**
     * redis删除指定key的缓存
     */
    public function redis_conf_del()
    {
        $this->redis_role();

        $key = trim(input("param.key"));
        if (empty($key)) {
            $this->error('key不能为空');
        }

        $redis = cache(config('cache.redis'))->handler();
        if ($redis->delete($key)) {
            $this->success('删除redis缓存成功！', url(request()->controller() . '/redis_conf_index'));
        } else {
            $this->error('删除redis缓存失败');
        }
    }


    /**
     * hash删除指定行
     * @note 先编辑行值为特定值，然后移除值为特定值的行
     */
    public function del_hash_row()
    {
        $this->redis_role();

        $key        = input("param.key");
        $field      = input("param.index");
        $redis      = cache(config('cache.redis'))->handler();
        //HDEL cdn_user_ranking_list_key 2019-11-07_2019-11-07__bandwidth desc
        $res = $redis->Hdel($key, $field);
        return json([
            'code' => $res ? 0 : 1,
        ]);
    }


    /**
     * 新增一行hash
     */
    public function add_hash_row()
    {
        $this->redis_role();

        $key        = input("param.key");
        $field      = input("param.field");
        $value      = input("param.value");
        $redis      = cache(config('cache.redis'))->handler();
        $res = $redis->Hset($key, $field, $value);
        return json([
            'code' => $res ? 0 : 1,
        ]);
    }

    /**
     * ajax设置某个键的过期时间
     */
    public function set_key_ttl()
    {
        $this->redis_role();

        $key    = input("param.key");
        $ttl    = input("param.ttl");

        $redis = cache(config('cache.redis'))->handler();
        if ($ttl == '0' || $ttl == '-1') {
            if ($redis->ttl($key) == -1) {
                $res = true;
            } else {
                $res = $redis->persist($key);
            }
        } else {
            $res = $redis->expire($key, $ttl);
        }

        return json([
            'code' => $res ? 0 : 1,
        ]);
    }


    /**
     * ajax转换数据格式
     */
    public function translate_value()
    {
        $target_show = input("param.target_show");
        $key         = input("param.key");
        $index       = input("param.index");

        $redis = cache(config('cache.redis'))->handler();
        $type = $redis->type($key);
        switch ($type)
        {
            //list
            case '3':
                $value = $redis->lrange($key, $index, $index);
                $value = reset($value);
                break;

            //hash
            case '5':
                $value = $redis->Hget($key, $index);
                break;
            default:
                $value = '';
                break;
        }

        switch ($target_show)
        {
            case 'cache_value_ori':
                $data = $value;
                break;
            case 'cache_value_seri':
                $data = serialize($value);
                break;
            case 'cache_value_unseri':
                if (is_serialized_string($value)) {
                    $data = unserialize($value);
                    $data = dump($data, false);
                } else {
                    $data = $value;
                }
                break;
            case 'cache_value_json':
                $data = json_encode($value, 320);
                break;
            case 'cache_value_unjson':
                $data = json_decode($value, true);
                break;
        }
        return json([
            'data' => $data,
        ]);
    }


    /**
     * ajax获取某个键的缓存值
     */
    public function get_redis_data()
    {
        $key = input("param.key");
        $data = $this->get_redis_data_for_key($key);
        return json($data);
    }


    /**
     * 获取某键的值
     */
    private function get_redis_data_for_key($key)
    {
        $redis = cache(config('cache.redis'))->handler();
        $type  = $redis->type($key);
        $ttl   = $redis->ttl($key);
        $data  = $intro = $type_name = '';
        switch ($type)
        {
            //String
            case 1:
                $data  = $redis->get($key);
                $intro = mb_substr($redis->get($key), 0, 100);
                $type_name = "String";
                break;
            //Set
            case 2:
                $type_name = "Set";
                break;
            //List
            case 3:
                $data = $redis->lrange($key, 0, 10);
                $intro = mb_substr(json_encode($redis->lrange($key, 0, 10), 320), 0, 100);
                $type_name = "List";
                break;
            //Zset
            case 4:
                $type_name = "Zset";
                break;
            //Hash
            case 5:
                $data = $redis->hGetAll($key);
                $intro = mb_substr(json_encode($redis->hGetAll($key), 320), 0, 100);
                $type_name = "Hash";
                break;
            default:
                $data= '';
                break;
        }

        return [
            'key'       => $key,
            'type'      => $type,
            'type_name' => $type_name,
            'data'      => $data,
            'intro'     => $intro,
            'ttl'       => $ttl
        ];
    }


    /**
     * 指定人操作
     */
    private function redis_role()
    {
        if (!in_array($this->_admin_id, $this->auth_user_arr)) {
            echo json_encode(['code' => 999]);exit;
        }
    }
}
