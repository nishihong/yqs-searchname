<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category ChannelAction 
 * +------------------------------------------------------------+
 * 栏目管理控制器
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020-03-29 15:41:02
 *
 */

namespace app\admin\controller;

use app\admin\controller\Common;

class Channel extends Common 
{
	public function __construct(){
		parent::__construct();
		$datas = $this->_channel();
		$this->assign('channels_list', $datas);
	}
	
	/**
	 * 栏目列表
	 */
	public function index(){
	    //取出栏目信息
		$data = $this->_tree();
		$this->assign('data_tree', $data);
		$this->module();
		return $this->fetch();
	}
		
	/**
	 * 栏目插入操作
	 */	
	public function add(){

		
		if(request()->isPost()){
			$data = input('post.');

			if(!empty($data) && is_array($data)){
				foreach ($data as $key=>$vo){
					if(is_array($vo)){
						$data[$key] = serialize($vo);
					}else{
                        $data[$key] = ($vo);
                    }
				}
			}
			
			//跳转
			if(!isset($data['CONTINUE_EDIT'])){
				$url = url('index');
			}else{
			    $url = '';
			}
			unset($data['CONTINUE_EDIT']);
			$res = model('Channel')->insert($data);
			if($res !== false){
			    //更新栏目缓存
			    $this->_data();

				$this->success("栏目添加成功", $url);
			}else{
				$this->error("栏目添加失败");
			}
		}
        //取出栏目信息
        $data = $this->_tree();
        $this->assign('data_tree', $data);

        $this->module();
		return $this->fetch('edit');
	}
	
	/**
	 * 栏目修改操作
	 */
	public function edit(){

		
		if (input('param.action_name') == 'status'){
			$this->_status();
		}
        //取出栏目信息
        $data = $this->_tree();
        $this->assign('data_tree', $data);

        $this->module();
		$id = intval(input('param.id'));
		$model = model('Channel');
		$rs = $model->find($id);
		$rs['seo'] ? $rs['seo'] = unserialize($rs['seo']) : '';
		$this->assign("data", $rs);
		
		if(request()->isPost()){
			$data = input('post.');
			if(!empty($data) && is_array($data)){
				foreach ($data as $key=>$vo){
                    if(is_array($vo)){
                        $data[$key] = serialize($vo);
                    }else{
                        $data[$key] = ($vo);
                    }
				}
			}
			
			//跳转
			if(!isset($data['CONTINUE_EDIT'])){
				$url = url('index');
			}else{
			    $url = '';
			}
			unset($data['CONTINUE_EDIT']);
			unset($data['category']);

			try {
			    //修改操作
                model('Channel')->where('id='.$id)->update($data);

                //更新栏目缓存
                $this->_data();
            } catch (\Exception $e) {
                $this->error("栏目修改失败-".$e->getMessage());
            }

            //success操作有抛异常，不要放入try内
            $this->success("栏目修改成功", $url);
		}
	
		return $this->fetch('edit');
	}
	
	/**
	 * 删除
	 */
    public function delete(){
        $id = intval(input('id'));
        $model = model('Channel');
        try{
            $model->where('id='.$id)->delete();
        }catch (\Exception $exception){
            $this->error("栏目删除失败");
        }
        $this->_data();
        $this->success("栏目删除成功");
    }


	/**
	 * 排序
	 */
	public function my_order(){
		if (request()->isPost()){
			$model = model('Channel');
			$order_item = input('post.order_item');
				
			if (!empty($order_item)){
				foreach ($order_item as $id => $order_id){
					$order_id = (int) $order_id;
					$order_id = $order_id > 0 && $order_id < 99 ? $order_id : 99;
						
					$id = intval($id);
						
					$model->where("id=".$id)->update(array('order_id'=>$order_id));
				}
                $this->_data();
				$this->success('栏目批量排序成功', url('index'));
			}
		}else{
			$this->redirect(url('index'));
		}
	}
	
	/**
	 * 模块信息
	 */
	public function module(){
		$module = model('module')->order('order_id ASC')->select();
	
		$this->assign('module', $module);
	}
	
	/**
	 * 更新栏目状态（导航和新窗口打开）
	 */
	private function _status(){
		$column = input('param.column');
		
		$status = (int)  input('param.status');
		$status = $status>0 ? '0' : '1';
		
		$id		= intval(input('param.id'));
		
		$model = model('Channel');
		try{
            $model->where("id=".$id)->update(array($column => $status));
        }catch (\Exception $exception){
            $this->error('系统出错，操作失败', url('index'));
        }

        $this->_data();
        $this->success('状态更新成功', url('index'));
	}

	public function get_data(){
        $data['site_id']        = input('post.site_id');
        $data['pid']            = input('post.pid');
        $data['name']           = input('post.name');
        $data['aliases']        = input('post.aliases');
        $data['module_identy']  = input('post.module_identy');
        $data['module_name']    = input('post.module_name');
        $data['action_name']    = input('post.action_name');
        $data['params']         = input('post.params');
        $data['order_id']       = input('post.order_id');
        $data['image']          = input('post.image');

        $data['category']       = input('post.category');
        $data['linktype']       = input('post.linktype');
        $data['outlink']        = input('post.outlink');
        $data['hcid']           = input('post.hcid');
        $data['nav']            = input('post.nav');
        $data['target']         = input('post.target');
        $data['bread']          = input('post.bread');
        $data['display']        = input('post.display');
        $data['static']         = input('post.static');
        $data['template']       = input('post.template');

        $data['seo']            = input('post.seo');
        $data['descript']       = input('post.descript');
        $data['aside_content']  = input('post.aside_content');
        $data['extra_html']     = input('post.extra_html');
        $data['showad']         = input('post.showad');

        return $data;
    }
}

?>