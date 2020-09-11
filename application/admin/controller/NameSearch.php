<?php
/**
 *
 * +------------------------------------------------------------+
 * @category NameSearch
 * +------------------------------------------------------------+
 * 广告管理
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2019
 * @version 1.0
 *
 * Modified at : 2020-9-11 09:38:33
 *
 */

namespace app\admin\controller;

use think\Db;

class NameSearch extends Common
{
	
	/**
	 * 列表管理
	 */
	public function index()
    {
		//名称
        $name = input('param.name');
        $type = input('param.type');

		$condition = [];
		//搜索关键字
        if($name){
            $condition[] = ['name', '=', $name];
        }
        if($type){
            $condition[] = ['type', '=', $type];
        }

		//排序
        $order = $this->orderstr(['id'], 'DESC');
		
		$Model	 = model("NameSearch");
		// 从表中获取值
        $datas = $Model->where($condition)->order($order)->paginate(50);

		return $this->fetch('index', [
			'pageHtml' => $datas->render(),
			'datas' => $datas,
			'name' => $name,
			'type' => $type,
		]);
	}

	/**
	 * 批量添加
	 **/
    public function add_all()
    {

        if (request()->isPost()){
	        $content = input('post.content');

	        if (empty($content)) {
	        	$this->error('内容不为空');
	        }

	        // 处理内容数据，拆分 中文分割号转英文，防止用户误输入
	        $content = str_replace(["//s*/", "\r\n", "\r", "\n", "；"], ';', $content);
	        $content = explode(';', $content);
	        $content = array_unique($content);
	        // if (is_array($content) && count($content) > 200) {
	        //     $this->error('提交数目一次不能大于200条');
	        // }

	        $adds = [];
	        foreach ($content as $key => $vo) {
	        	$list = explode('|', $vo);
	        	// dump($list);exit;	
	        	$data = [
	        		'name' => $list[0],
	        		'type' => $list[1],
                    'price' => $list[2],
	        	]; 

	        	$adds[] = $data;
	        }

        	try {
            	model('NameSearch')->insertAll($adds);
        	} catch (Exception $e) {
                $this->error("添加失败" . $e->getMessage());
        	}

        	$this->success("添加成功",url(request()->controller().'/index'));
        }

        return $this->fetch('add_all');
    }

    /**
     * 删除
     **/
    // public function delete()
    // {
        
    //     $id = $this->request->param('id');
        
    //     try {
    //         // 先删除
    //         model('NameSearch')->where(['id' => $id])->delete();

    //     } catch (Exception $e) {
    //         $this->error("删除失败" . $e->getMessage());
    //     }

    //     $this->success("删除成功",url(request()->controller().'/index'));
        
    // }

}

