<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Group
 * +------------------------------------------------------------+
 * 用户组管理
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020-03-29 15:41:20
 *
 */

namespace app\admin\controller;

use app\admin\controller\Common;

class Group extends Common 
{
    /**
     * 用户组添加
     */
    public function add()
    {
        return $this->help_add([
            'group_name' => [
                'title'     => '角色组名称',
                'dfilter'   => 'empty'
            ],
            'group_resources' => [
                'title'     => '可操作权限',
                'dfunc'     => 'json_encode'
            ],
        ], ['resources'=>config("menu.")]);
    }

    /**
     * 用户组修改
     */
    public function edit(){
        return $this->help_edit([
            'group_name' => [
                'title'     => '角色组名称',
                'dfilter'   => 'empty'
            ],
            'group_resources' => [
                'title'     => '可操作权限',
                'dfunc'     => 'json_encode'
            ],
        ], ['resources'=>config("menu.")]);
    }

    /**
     * 设置字段权限
     */
    public function set(){
        $id = input('param.id','','int');

        $Model = model('Group');
        $data = $Model->find($id);
        
        if(request()->isPost()){
            $group_fields = input('param.group_fields','');
            if(!empty($group_fields)){
                $group_fields = json_encode($group_fields);
            }
            try {
                $Model->where("id=".$id)->update(['group_fields'=>$group_fields]);
                $res = true;
            }catch (\Exception $e){
                if($this->_debug) $msg = $e->getMessage();
                $res = false;
            }

            if($res){
                $this->success("设置成功", url(request()->controller() . '/index'));
            }else{
                $this->error("设置失败".$msg);
            }
        }
        
        return $this->fetch('set', [
            'resources' => config('field.'),
            'data'      => $data,
        ]);
    }
}