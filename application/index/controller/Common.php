<?php
/**
 *
 * +------------------------------------------------------------+
 * @category Common
 * +------------------------------------------------------------+
 * 公共类
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at : 2019年12月9日 17:24:07
 *
 */

namespace app\index\controller;

use think\Controller;

class Common extends Controller
{

    //当前页面标题
    private $_title	        = null;

    //当前登陆用户的ID
    protected $_user_id     = 0;

    //当前登陆用户的帐户
    protected $_username    = 0;

    //当前登陆用户的姓名
    protected $_surename    = 0;

    //当前登录的用户信息
    protected $_userdata    = [];

    public function initialize() 
    {
        //载入配置
        $system = model('System')->field('identy,attvalue')
                              // ->where(array('type'=>'base'))
                              ->order('id DESC')
                              ->select();
        $configs = [];
        foreach ($system as $key=>$vo){
            // 如果是图片先转化为图片链接 判断里面包含IMAGE
            if(strpos($vo['identy'], 'IMAGE') !== false){
                $root_path = model('File')->where('id='.$vo['attvalue'])->value('root_path');
                $vo['attvalue'] = $root_path;
            }

            $configs[$vo['identy']] = $vo['attvalue'];
        }

        // dump($configs);exit;

        $this->assign('configs', $configs);

        $module_name = strtolower(request()->controller());
        $action_name = request()->action();

        // 判断body的class属性
        switch ($module_name) {
          case 'index':
            $body_class = "";
            break;
            
          case 'racenews':
            if ($action_name == 'view') {
              $body_class = "djyl_djyl jianjing_waishe";
            } else {
              $body_class = "zixun";
            }
            break;

          case 'gamevideo':
            $body_class = "djyl_djyl jianjing_waishe";
            break;

          case 'gamefun':
            if ($action_name == 'view') {
              $body_class = "djyl_djyl jianjing_waishe";
            } else {
              $body_class = "dianjing_yule";
            }
            break;

          case 'gameteam':
            $body_class = "game_team";
            break;

          case 'gamewaishe':
            if ($action_name == 'view') {
              $body_class = "zixun yxlm_bb";
            } else {
              $body_class = "jianjing_waishe";
            }
            break;
          
          default:
            $body_class = "";
            break;
        }

        $this->assign([
            'module_name' => $module_name,
            'action_name' => $action_name,
            'body_class' => $body_class,
        ]);
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

        $Model	 = db($mod);
        //获取数据表的primary key
        $key = $Model->getPk();
        //获取表字段
        $fields = $Model->getTableFields();

        $map = [];

        $map[] = [$field,'=',$value];
        if(in_array('is_delete', $fields)){
            $map[] = ['is_delete','=',1];
        }

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
     * 空控制重定向
     */
    // public function _empty()
    // {
    //     $this->redirect(config('app_host'));
    // }
}