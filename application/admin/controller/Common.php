<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Common
 * +------------------------------------------------------------+
 * 后台公共类
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020-03-28 14:12:53
 *
 */
namespace app\admin\controller;

use think\Collection;
use think\Controller;
use think\response\Json;
use think\response\Redirect;
use Tree\Tree;

class Common extends Controller
{
	//当前页面标题
	private $_title	        = null;

    //当前页面追踪信息
    private $_trail	        = [];

    //前一个访问URL地址
    private $_http_referer  = '';
	
	//当前登陆用户的ID
	protected $_admin_id    = 0;
	
	//真实姓名
	protected $_surename    = null;
	
	//用户组ID
	protected $_group_id    = 0;
	
	//当前登录用户对后台可访问资源列表
	protected $_resources   = null;

	//当前登录的用户信息
    protected $_admin       = [];

    //是否显示数据库debug信息
    protected $_debug       = true;

    /**
     * 初始化
     */
	public function initialize() 
    {
		//访问来源
        $this->_http_referer = input('?request.http_referer', '') ? input('?server.HTTP_REFERER', '') : '';
		//主题
		$skin = config('logic.skin');
		$this->assign('skin', $skin);

		//解决上传的问题
        if(request()->controller() == 'Upload'){
            $sid = input('post.'.session_name());
            if(!empty($sid)) session_id($sid);
        }

        //是否显示数据库debug信息
        $this->_debug = config('database.debug');
		
		//检查登陆
		$admin_id = session('admin_id');
		if ($admin_id > 0) {
			//提取用户信息
			$admin = model('Admin')->getBasic($admin_id);
			if (!empty($admin)) {
				$this->_admin       = $admin;
				$this->_admin_id    = $admin['id'];
				$this->_group_id    = $admin['group_id'];
                $this->_surename    = $admin['admin_surename'] ? $admin['admin_surename'] : $admin['admin_username'];
				$this->_nickname    = $admin['admin_nickname'];

				$this->assign("admin", $admin);
                $this->assign("group_id", $this->_group_id);
                               
			}
									
			//获取当前用户可操作的权限列表
			if ($this->_resources === null){
			    $this->_resources = model('Group')->getMenu($this->_group_id);
			}
			//验证对当前操作是否访问权限
			if(Acl::hasAcl(request()->controller(), request()->action(), $this->_resources) !== true){
			    $this->error('对不起无权进行“'. Acl::$action_name .'”操作', url('Index/index'));
			}
			$this->assign('navlist', $this->_resources['topMenu']);
			$this->assign('childMenu', $this->_resources['childMenu']);
			$this->assign('curMenu', $this->_resources['curMenu']);
            $this->assign('fields', $this->_resources['fields']);
			// dump($this->_resources['childMenu']);exit;

			$logdes = isset($this->_resources['curMenu']['title']) ? $this->_resources['curMenu']['title'] : '';
			$this->addStep($logdes);
			
			!defined('USER_USERNAME') ? define('USER_USERNAME', isset($admin['admin_username']) ? $admin['admin_username'] : '') : '';
			
			//记录日志
            $request = input('request.');
            if(!empty($request)){
                foreach ($request as $k=>$v){
                    if(empty($v)) unset($request[$k]);
                }
            }
            $str = json_encode($request, JSON_UNESCAPED_UNICODE);
            $logdes .= " - ".$str;
			model('Log')->addLog($logdes);
			
			unset($resources, $request);
		}else{
		    if ('passport' == strtolower(request()->controller())) return false;

		    //用户未登录则直接跳转到登录界面
		    cookie('referer_url', request()->isAjax() ? url('Index/index') : input('server.REQUEST_URI'));
			if(request()->isAjax()){
				$this->error('对不起，登录超时，请重新登录', url('passport/login'));
			}else{
				$this->redirect(url('passport/login'));
			}
		}
	}

    /**
     * 通用列表
     */
	public function index()
    {
        return $this->help_index();
    }

    /**
     * 排序
     * @param string $table 表名
     * @param string $field 字段
     */
    // public function order($table = '', $field = 'order_id')
    // {
    //     $table = $table ? $table : request()->controller();
    //     //排序
    //     if(request()->isPost()){
    //         $order_id = input('param.order_id');
    //         if(!empty($order_id) && is_array($order_id)){
    //             $msg = '';
    //             try {
    //                 foreach($order_id as $k=>$v){
    //                     $v = $v ? (int)$v : 9999;
    //                     model($table)->where('id='.$k)->update([
    //                         $field => $v
    //                     ]);
    //                 }
    //                 $res = true;
    //             }catch (\Exception $e){
    //                 if($this->_debug) $msg = $e->getMessage();
    //                 $res = false;
    //             }

    //             if($res){
    //                 $this->success("排序成功", url(request()->controller().'/index'));
    //             }else{
    //                 $this->error("排序失败".$msg);
    //             }

    //         }else{
    //             $this->error("非法操作");
    //         }
    //     }else{
    //         $this->error("非法请求");
    //     }
    // }

    /**
     * 操作状态
     * @param string $table 表名
     * @param string $field 字段
     */
    public function status($table = '', $field = 'status'){
        $id = input('param.id','','int');
        $s  = input('param.s','','int');

        $table = $table ? $table : request()->controller();

        if($id > 0 && in_array($s, array(1,2))){
            $Model = model($table);

            $msg = '';
            try {
                $Model->where("id=".$id)->update([
                    $field => $s
                ]);
                $res = true;
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if($res){
                $this->success("操作成功", url(request()->controller() . '/index'));
            }else{
                $this->error("操作失败".$msg);
            }

        }else{
            $this->error("非法操作");
        }
    }

    /**
     * 通用删除方法
     */
    public function delete(){
        $this->help_delete();
    }

    /**
     * 通用数据列表-帮助方法
     * @param array $condition 查询条件
     * @param string $table 查询表
     */
    protected function help_index($condition = [], $table = ''){
        //排序
        $order = $this->orderstr();

        $table = $table ? $table : request()->controller();
        $count	 = model($table)->where($condition)->count();

        if($count > 0){
            //从表中获取值
            $datas = model($table)->where($condition)->order($order)->paginate();

            if ($count > config('paginate.list_rows')){
                $this->assign('pageHtml', $datas->render());
            }

            $this->assign('datas', $datas);
        }

        $tpl = request()->action();
        return $this->fetch($tpl);
    }

    /**
     * 通用数据库插入-帮助方法
     *
     * @param array $fields  需要验证的字段
     * @param array $assign  模板的变量
     * @param bool $isunique 字段唯一新检查
     * @param string $table  表名
     *
     * ===============================================================================================================
     * 实例
     *
     *       $fields['admin_confirm'] = [
     *          'title'     => '确认密码',                      //字段的名称
     *          'pfilter'   => 'int',                          //字段的请求过滤（防止sql注入）
     *          'dfilter'   => 'empty|email|qq|mobile|neq',    //字段的请求格式过滤 '|'表示可以多重过滤 neq必须配合field字段使用 [1,2]数组格式的只能做安全过滤（如性别过滤）
     *          'field'     => 'admin_password',               //判断字段与某个字段是否一致
     *          'isadd'     => false,                          //是否加入数据
     *          'dfunc'     => 'encrypt_pwd',                  //字段加入函数
     *          'value'     => '值'                            //插入数据的默认值
     *      ];
     * ===============================================================================================================
     *
     */
	protected function help_add($fields = [], $assign =[], $isunique = false, $table = ''){
        $table = $table ? $table : request()->controller();
        $Model = model($table);

        if(request()->isPost()) {
            //唯一性检查
            if ($isunique) {
                $unique = input('post.unique');
                if ($unique == true) {
                    $this->check_unique();
                    exit;
                }
            }

            $data = $this->assembly($fields);

            $msg = '';
            try {
                $res = $Model->insert($data, false, true);
            } catch (\Exception $e) {
                if ($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if ($res) {
                $this->success('添加成功', url(request()->controller() . '/index'));
            } else {
                $this->error("添加失败" . $msg);
            }
        }

        $tpl = request()->action();
        if($tpl == 'add') $tpl = 'edit';
        return $this->fetch($tpl, $assign);
    }

    /**
     * 通用数据库更新-帮助方法
     *
     * @param array $fields  需要验证的字段
     * @param array $assign  模板的变量
     * @param bool $isunique 字段唯一新检查
     * @param string $table  表名
     *
     * ===============================================================================================================
     * 实例
     *
     *       $fields['admin_confirm'] = [
     *          'title'     => '确认密码',                      //字段的名称
     *          'pfilter'   => 'int',                          //字段的请求过滤（防止sql注入）
     *          'dfilter'   => 'empty|email|qq|mobile|neq',    //字段的请求格式过滤 '|'表示可以多重过滤 neq必须配合field字段使用 [1,2]数组格式的只能做安全过滤（如性别过滤）
     *          'field'     => 'admin_password',               //判断字段与某个字段是否一致
     *          'isadd'     => false,                          //是否加入数据
     *          'isempty'   => true,                           //空判断 isadd字段为false有效
     *          'dfunc'     => 'encrypt_pwd',                  //字段加入函数
     *          'value'     => '值'                            //插入数据的默认值
     *      ];
     * ===============================================================================================================
     *
     */
    public function help_edit($fields = [], $assign =[], $isunique = false, $table = ''){
        $id = input('param.id', '', 'int');
        if($id == 0){
            $this->error("非法操作");
        }

        $table = $table ? $table : request()->controller();

        $list = model($table)->where('id='.$id)->find();

        if(request()->isPost()){
            //唯一性检查
            if($isunique){
                $unique = input('post.unique');
                if($unique == true){
                    $this->check_unique();
                    exit;
                }
            }

            $data = $this->assembly($fields);

            $msg = '';
            try{
                model($table)->where('id='.$id)->update($data);
                $res = true;
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if($res){
                $this->success('修改成功', url(request()->controller().'/index'));
            }else{
                $this->error("修改失败".$msg);
            }
        }

        $assign['data'] = $list;
        $tpl = request()->action();
        if($tpl == 'add') $tpl = 'edit';
        return $this->fetch($tpl, $assign);
    }

    /**
     * 拼装数据
     * @param array $fields 字段
     * @return array
     */
    protected function assembly($fields = [], $req = 'post'){
        //logic.php 查找相关匹配
        $preg = config('logic.preg');
        //拼装数据
        $data = [];
        if(!empty($fields)){
            foreach ($fields as $k=>$v){
                if(isset($v['pfilter'])){
                    $params = input($req.'.'.$k, '', $v['pfilter']);
                }else{
                    $params = input($req.'.'.$k);
                }

                if(isset($v['dfilter'])){
                    $title      = $v['title'];
                    if(is_array($v['dfilter'])){
                        if(!in_array($params, $v['dfilter'])){
                            $this->error($title.'非法操作');
                        }
                    }else{
                        $dfilter    = explode('|', $v['dfilter']);
                        if(!empty($dfilter)){
                            foreach ($dfilter as $vo){
                                if($vo == 'empty'){
                                    if(empty($params)){
                                        $this->error($title.'不能为空');
                                    }
                                }elseif(in_array($vo, array_keys($preg))){
                                    if(!empty($params)) {
                                        if (!preg_match($preg[$vo], $params)) {
                                            $this->error($title . '格式有误');
                                        }
                                    }
                                }elseif($vo == 'neq'){
                                    if(isset($v['field'])){
                                        $f = input($req.'.'.$v['field']);
                                        if($params != $f){
                                            $this->error($title.'不一致');
                                        }
                                    }
                                }
                            }
                        }
                        unset($dfilter);
                    }
                }

                if(isset($v['isadd']) && $v['isadd'] == false){

                }else{
                    if(isset($v['value'])){
                        $data[$k] = $v['value'];
                    }else{
                        $data[$k] = $params;
                    }
                }

                if(isset($v['dfunc']) && isset($data[$k])){
                    $data[$k] = $v['dfunc']($data[$k]);
                }

                unset($params);
            }
        }

        return $data;
    }

    /**
     * 通用删除逻辑-帮助方法
     * @param int $type  1真实删除 2逻辑删除（表必须有is_delete字段）
     * @param string $table
     * @param string $url
     */
    protected function help_delete($type = 1, $table = '', $url = ''){
        $id     = input('param.id', 0, 'int');
        $items  = input('post.items');

        if(!empty($items)){
            foreach ($items as $k=>$v){
                $items[$k] = (int)$v;
            }
        }else{
            $items = $id;
        }

        if(!empty($items)){
            $table = $table ? $table : request()->controller();
            $Model = model($table);
            $msg = '';
            try{
                if($type == 1) {
                    $Model->where('id', 'in', $items)->delete();
                }else{
                    $Model->where('id', 'in', $items)->update(['is_delete' => 2]);
                }
                $res = true;
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if($res){
                $url = $url ? $url : url(request()->controller().'/index');
                $this->success("删除成功", $url);
            }else{
                $this->error("删除失败".$msg);
            }

        }else{
            $this->error("非法操作");
        }
    }

    /**
	 * 数据库排序组装
     * @param string $field_default
     * @param string $type_default
     * @param string $table_alias_default
     * @return string
     */
    protected function orderstr($field_default = 'id' , $type_default = 'DESC' , $table_alias_default = ''){
	    //排序
	    $order_field = input('param.order_field', $field_default);
	    $order_type  = input('param.order_type', $type_default);

        $order = '';
        if(is_array($order_field)){
            foreach ($order_field as $k=>$v){
                if(!empty($order)){
                    $order .= ',';
                }
                if($table_alias_default) {
                    $order .= $table_alias_default . '.' . $v . ' ' . $order_type;
                }else{
                    $order .= $v . ' ' . $order_type;
                }
            }
        }else{
            $order = $order_field.' '.$order_type;
        }

        if($order_type == 'ASC'){
            $order_type = 'DESC';
        }else{
            $order_type = 'ASC';
        }

	    $this->assign([
	        'order_type'    => $order_type,
	        'order_field'   => $order_field
	    ]);
	    return $order;
	}

    /**
	 * 检查唯一
     * @param string $mod
     */
	protected function check_unique($mod = ''){
		//字段
		$field = input('post.field');
		//值
		$value = input('post.value');

        $mod = $mod ? $mod : request()->controller();
		$Model	 = model($mod);
		//获取数据表的primary key
		$key = $Model->getPk();
		//获取表字段
        $fields = $Model->getTableFields();

        $map = [];

        $map[] = [$field,'=',$value];
        if(in_array('is_delete', $fields)){
            $map[] = ['is_delete','=',1];
        }
        // if(in_array($mod, ['huawei_cloud_host','aliyun_host','tianyi_host','ten_cloud_host']) && in_array('cloud_status', $fields)){
        //     $map[] = ['cloud_status', 'in', [0,1]];
        // }

        $id = input('param.'.$key, '', 'int');

		//排除本身
		if ($id > 0){
            $map[] = [$key, '<>', $id];
		}

		$count = $Model->where($map)->count();

		if($count == 0){
            $this->success("正确");
		}else{
            $this->error("已被占用");
		}
	}

	/**
	 * 页面导航 -> 增加导航至
	 *
	 * @param type $title
	 * @param type $url
	 * @return \CommonAction
	 */
	protected function addStep($title = '', $url = '') {
		$this->_title	= empty($this->_title) ? $title : $title . ' - ' . $this->_title;
		$this->_trail	= ['title'=>$title, 'url' => $url];

		return $this;
	}

	/**
	 * 加载模板输出
	 * @access protected
	 * @param  string $template 模板文件名
	 * @param  array  $vars     模板输出变量
	 * @param  array  $config   模板参数
	 * @return mixed
	 */
	protected function fetch($template = '', $vars = [], $config = [])
	{
	    $this->assign('_title', $this->_title);
	    $this->assign('_trail', $this->_trail);
	    return $this->view->fetch($template, $vars, $config);
	}

    /**
	 * 成功信息
     * @param string $data
     * @param string $url
     */
    /*public function success($data = '', $url=''){
        $this->msgbox($data, 1, $url);
    }

    /**
	 * 错误信息
     * @param string $data
     * @param string $url
     */
    /*public function error($data = '', $url=''){
        $this->msgbox($data, 0, $url);
    }

    /**
	 * 警告信息
     * @param string $data
     * @param string $url
     */
    /*public function warn($data = '', $url=''){
        $this->msgbox($data, 2, $url);
    }

    /**
	 * 显示提示信息
     * @param string $data 提醒数据
     * @param int $code 信息代号
     * @param string $url 跳转地址
     * @param int $wait 等待时间
     * @return \FastD\Http\Response|mixed|\think\response\Json
     */
	/*public function msgbox($data = '', $code = 1 ,$url = '', $wait = 3){
		if(empty($url)){
			$url = url(request()->controller().'/'.request()->action(), input('get.'));//指定url默认域名
		}

        $data = is_array($data) ? $data : array($data);

		//ajax请求这直接输出json格式数据
		if (request()->isAjax()){
			return json($data, $code = $code, $header = [], $options = []);
		}

		return $this->fetch('public/msgbox', [
            'data'	=> $data,
            'code'	=> $code,
            'url'	=> $url,
            'wait'	=> $wait,
		]);
	}*/
    protected function _channel(){
        $datas = [];//cache('channel');
        if(empty($datas)){
            $data = model('Channel')->order('order_id ASC')->select();
            $tree = new Tree($data);
            $datas = $tree->build_tree(true);
        }
        $list = array();
        if(!empty($datas)){
            foreach($datas as $key=>$vo){
                if(is_object($datas[$key])){
                    if(is_array($vo)){
                        $datas[$key]['seo'] 	 = unserialize($vo['seo']);
                        $datas[$key]['template'] = unserialize($vo['template']);
                    }

                    //禁止关联
                    if($vo['category'] == config('CHANNEL_DENY')){
                        $datas[$key]['_deny'] = "不可操作";
                        //外部链接
                    }elseif($vo['category'] == config('CHANNEL_OUTLINK')){
                        //外部地址
                        if($vo['linktype'] == 0){
                            $datas[$key]['href'] = $vo['outlink'];
                        }else{
                            $params = $vo['params'] ? $vo['params']."&hcid=".$vo['hcid'] : "hcid=".$vo['hcid'];
                            if($vo['module_identy'] == 'page'){
                                $datas[$key]['href'] = url("base/edit", $params);
                            }else{
                                //$datas[$key]['href'] = U($vo['module_name']."/".$vo['action_name'], $params);
                                $datas[$key]['href'] = url("base/index", $params);
                            }
                        }
                    }else{
                        $params = $vo['params'] ? $vo['params']."&hcid=".$vo['id'] : "hcid=".$vo['id'];
                        if($vo['module_identy'] == 'page'){
                            $datas[$key]['href'] = url("base/page", $params);
                        }else{
                            //$datas[$key]['href'] = U($vo['module_name']."/".$vo['action_name'], $params);
                            $datas[$key]['href'] = url("base/index", $params);
                        }
                    }

                }
            }
            cache('channel', $datas);
           // F('channel', $datas, 'SiteData/Runtime/Admin/Temp/');
        }

        return $datas;

    }

    /**
     * 获取栏目信息 更新缓存
     */
    protected function _data(){
        cache('channel_tree', null);
        /*$model = model('Channel');
        $datas = $model->field('id,pid,name,module_identy,order_id,display,showad,nav,category,bread,target')
            ->order('order_id ASC')
            ->select();
        cache('channel_tree', null);
        return $datas;*/
    }

    /**
     * 对栏目进行归类形成一棵树
     */
    protected function _tree()
    {
        // $datas = cache('channel_tree');

        // if(empty($datas)){
            $model = model('Channel');
            $datas = $model->field('id,pid,name,module_identy,order_id,display,showad,nav,category,bread,target')
                ->order('order_id ASC')
                ->select()
                ->toArray();
            cache('channel_tree', $datas);
        // }

        if ($datas){
            $tree = new Tree($datas);
            $data = $tree->build_tree();
        }
        return $data;

    }

    /**
     * 权限控制
     */
    public function get_power()
    {
        //权限控制
        $groupid = $this->_group_id;
        if($groupid == 22){
            $str = $this->_admin_id;
        }elseif($groupid == 35 || $groupid == 47){
            $adminarr = model('Admin')->field('id')->where(array('admin_team'=>$this->_admin['admin_team'],'group_id'=>22))->select();
            if(!empty($adminarr)){
                $ids = '';
                foreach($adminarr as $key=>$vo){
                    $ids .= $vo['id'].",";
                }
                $ids = rtrim($ids, ",");

                $str = array('in', $ids);
            }else{
                $str = false;
            }
        }else{
            $str = false;
        }

        return $str;
    }
}