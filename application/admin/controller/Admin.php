<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Admin
 * +------------------------------------------------------------+
 * 管理员管理
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at : 2018-07-13 11:04
 *
 */

namespace app\admin\controller;

use app\admin\controller\Common;

class Admin extends Common 
{

    /**
     * 管理员列表
     */
    public function index()
    {
        //用户组
        $group_id = input('get.group_id','','int');
        //关键字
        $keywords = input('get.keywords');

        $condition = [];
        $keywords_condition = [];
        $condition['a.is_delete'] = 1;
        if($this->_group_id!=1){
            $condition['a.id'] = $this->_admin_id;
        }

        if($keywords){
            $keywords_condition[] = ['a.admin_username|a.admin_nickname|a.admin_surename', 'like', '%'.$keywords.'%'];
        }
        if($group_id){
            $condition['a.group_id'] = $group_id;
        }

        //排序
        $order = $this->orderstr(['id'], 'DESC', 'a');

        $Model = model('Admin');

        //从表中获取值
        $datas = $Model->alias(['dj_admin'=>'a','dj_group'=>'b'])
            ->leftJoin('dj_group', 'a.group_id=b.id')
            ->where($condition)
            ->where($keywords_condition)
            ->field('a.*,b.group_name')
            ->order($order)
            ->paginate();

        $this->assign('pageHtml', $datas->render());
        $this->assign('datas', $datas);
        
        return $this->fetch('index', ['roles'=>model('Group')->select()]);
    }

    /**
     * 添加管理员
     */
    public function add(){
        return $this->help_add([
            'group_id' => [
                'title' => '角色组',
                'pfilter' => 'int',
                'dfilter' => 'empty'
            ],
            'admin_username' => [
                'title' => '用户名',
                'dfilter' => 'empty'
            ],
            'admin_sex' => [
                'title' => '性别',
                'pfilter' => 'int',
                'dfilter' => [1,2]
            ],
            'admin_nickname' => [
                'title' => '昵称',
            ],
            'admin_surename' => [
                'title' => '真实姓名',
            ],
            'admin_password' => [
                'title' => '密码',
                'dfilter' => 'empty|pwd',
                'dfunc' => 'encrypt_pwd'
            ],
            'admin_confirm' => [
                'title' => '确认密码',
                'dfilter' => 'empty|pwd|neq',
                'field' => 'admin_password',
                'isadd' => false
            ],
            'admin_image' => [
                'title' => '头像',
                'pfilter' => 'int'
            ],
            'admin_qq' => [
                'title' => 'QQ',
                'dfilter' => 'empty|qq'
            ],
            'admin_email' => [
                'title' => '邮箱',
                'dfilter' => 'empty|email'
            ],
            'admin_mobile' => [
                'title' => '手机号',
                'dfilter' => 'empty|mobile'
            ],
            'admin_addtime' => [
                'value' => time()
            ],
        ], ['roles'=>model('Group')->select()], true);
    }

    /**
     * 修改管理员
     */
    public function edit(){
        return $this->help_edit([
            'group_id' => [
                'title' => '角色组',
                'pfilter' => 'int',
                'dfilter' => 'empty'
            ],
            'admin_username' => [
                'title' => '用户名',
                'dfilter' => 'empty'
            ],
            'admin_sex' => [
                'title' => '性别',
                'pfilter' => 'int',
                'dfilter' => [1,2]
            ],
            'admin_nickname' => [
                'title' => '昵称',
            ],
            'admin_surename' => [
                'title' => '真实姓名',
            ],
            'admin_image' => [
                'title' => '头像',
                'pfilter' => 'int'
            ],
            'admin_qq' => [
                'title' => 'QQ',
                'dfilter' => 'empty|qq'
            ],
            'admin_email' => [
                'title' => '邮箱',
                'dfilter' => 'empty|email'
            ],
            'admin_mobile' => [
                'title' => '手机号',
                'dfilter' => 'empty|mobile'
            ],
        ], ['roles'=>model('Group')->select()], true);
    }

    /**
     * 修改密码
     */
    public function edit_pwd(){
        $id = input('param.id', '', 'int');
        if($this->_group_id != 1 && $id != $this->_admin_id){
            $this->error("权限不够无法修改");
        }

        return $this->help_edit([
            'admin_password' => [
                'title' => '密码',
                'dfilter' => 'empty|pwd',
                'dfunc' => 'encrypt_pwd'
            ],
            'admin_confirm' => [
                'title' => '确认密码',
                'dfilter' => 'empty|pwd|neq',
                'field' => 'admin_password',
                'isadd' => false
            ],
        ]);
    }

    /**
     * 传统写法-添加管理员
     */
    public function add_old(){
        $Model = model('Admin');

        if(request()->isPost()){

            $unique = input('post.unique');
            if($unique == true){
                $this->check_unique();
                exit;
            }

            $preg = config('logic.preg');
            $data = [];

            $data['group_id']          = input('post.group_id', '', 'int');
            $data['admin_username']    = input('post.admin_username');
            $data['admin_sex']         = input('post.admin_sex', '', 'int');
            $data['admin_nickname']    = input('post.admin_nickname');
            $data['admin_surename']    = input('post.admin_surename');
            $data['admin_password']    = input('post.admin_password');
            $data['admin_confirm']     = input('post.admin_confirm');
            $data['admin_image']       = input('post.admin_image', '', 'int');
            $data['admin_qq']          = input('post.admin_qq');
            $data['admin_email']       = input('post.admin_email');
            $data['admin_mobile']      = input('post.admin_mobile');

            if($data['group_id'] == 0){
                $this->error("角色组不能为空");
            }

            if(empty($data['admin_username'])){
                $this->error("用户名不能为空");
            }

            if(!in_array($data['admin_sex'], [1,2])){
                $this->error("性别有误");
            }

            if(empty($data['admin_password'])){
                $this->error("密码不能为空");
            }

            if(empty($data['admin_confirm'])){
                $this->error("确认密码不能为空");
            }

            if($data['admin_password'] != $data['admin_confirm']){
                $this->error("密码和确认密码不一致");
            }

            if(empty($data['admin_qq'])){
                $this->error("QQ不能为空");
            }
            if(!preg_match($preg['qq'], $data['admin_qq'])){
                $this->error("QQ格式有误");
            }
            if(empty($data['admin_email'])){
                $this->error("E-mail不能为空");
            }
            if(!preg_match($preg['email'], $data['admin_email'])){
                $this->error("E-mail格式有误");
            }
            if(empty($data['admin_mobile'])){
                $this->error("手机号不能为空");
            }
            if(!preg_match($preg['mobile'], $data['admin_mobile'])){
                $this->error("手机号格式有误");
            }

            $data['admin_password'] = encrypt_pwd($data['admin_password']);
            unset($data['admin_confirm']);
            $data['admin_addtime'] = time();

            $msg = '';
            try{
                $res = $Model->insert($data);
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if($res){
                $this->success('添加管理员成功', url(request()->controller().'/index'));
            }else{
                $this->error("添加管理员失败".$msg);
            }
        }

        return $this->fetch('edit', ['roles'=>model('Group')->select()]);
    }

    /**
     * 传统写法-修改用户
     */
    public function edit_old(){
        $id = input('param.id', '', 'int');

        if($id == 0){
            $this->error("非法操作");
        }

        if($this->_group_id != 1 && $id != $this->_admin_id){
            $this->error("权限不够无法修改");
        }

        $Model = model('Admin');
        $list = $Model->where('id='.$id)->find();

        if(request()->isPost()){
            $unique = input('post.unique');
            if($unique == true){
                $this->check_unique();
                exit;
            }

            $preg = config('logic.preg');
            $data = [];

            $data['group_id']          = input('post.group_id', '', 'int');
            $data['admin_username']    = input('post.admin_username');
            $data['admin_sex']         = input('post.admin_sex', '', 'int');
            $data['admin_nickname']    = input('post.admin_nickname');
            $data['admin_surename']    = input('post.admin_surename');
            $data['admin_password']    = input('post.admin_password');
            $data['admin_confirm']     = input('post.admin_confirm');
            $data['admin_image']       = input('post.admin_image', '', 'int');
            $data['admin_qq']          = input('post.admin_qq');
            $data['admin_email']       = input('post.admin_email');
            $data['admin_mobile']      = input('post.admin_mobile');

            if($data['group_id'] == 0){
                $this->error("角色组不能为空");
            }

            if(empty($data['admin_username'])){
                $this->error("用户名不能为空");
            }

            if(!in_array($data['admin_sex'], array(1,2))){
                $this->error("性别有误");
            }

            if(!empty($data['admin_password'])){
                if($data['admin_password'] != $data['admin_confirm']){
                    $this->error("密码和确认密码不一致");
                }
                $data['admin_password'] = encrypt_pwd($data['admin_password']);
                unset($data['admin_confirm']);
            }else{
                unset($data['admin_password'],$data['admin_confirm']);
            }

            if(empty($data['admin_qq'])){
                $this->error("QQ不能为空");
            }
            if(!preg_match($preg['qq'], $data['admin_qq'])){
                $this->error("QQ格式有误");
            }
            if(empty($data['admin_email'])){
                $this->error("E-mail不能为空");
            }
            if(!preg_match($preg['email'], $data['admin_email'])){
                $this->error("E-mail格式有误");
            }
            if(empty($data['admin_mobile'])){
                $this->error("手机号不能为空");
            }
            if(!preg_match($preg['mobile'], $data['admin_mobile'])){
                $this->error("手机号格式有误");
            }

            $msg = '';
            try{
                $Model->where(array('id'=>$id))->update($data);
                $res = true;
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if($res){
                $this->success('修改管理员成功', url(request()->controller().'/index'));
            }else{
                $this->error("修改管理员失败".$msg);
            }
        }

        return $this->fetch('edit', [
            'roles' => model('Group')->select(),
            'data'	=> $list
        ]);
    }

    /**
     * 查看管理员
     */
    public function view(){
        $id = input('param.id','','int');

        if($id == 0){
            $this->error("非法操作");
        }

        if($this->_group_id != 1 && $id != $this->_admin_id){
            $this->error("权限不够无法修改");
        }

        $list = model('Admin')->table('dj_admin a')
            ->leftJoin('dj_group b ', 'a.group_id=b.id')
            ->leftJoin('dj_file c', 'a.admin_image=c.id')
            ->where(['a.id'=>$id,'a.is_delete'=>1])
            ->field('a.*,b.group_name,c.root_path')
            ->find();

        return $this->fetch('view', [
            'data'        => $list,
            'admin_status_list'      => config('logic.admin_status_list')
        ]);
    }

    /**
     * 改变样式
     */
    public function style(){
        $id = input('param.id', '', 'int');
        if($id == 0){
            $this->error("非法操作");
        }

        if(request()->isPost()) {
            $style = input('post.style');
            $Model = model('Admin');

            try {
                $Model->where("id=" . $id)->update(['admin_style' => $style]);
                $res = true;
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            $msg = '';
            if ($res) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败'.$msg);
            }
        }else{
            $this->error("非法请求");
        }
    }

    /**
     * 管理员逻辑删除
     */
    public function delete(){
        $this->help_delete(2);
    }

}