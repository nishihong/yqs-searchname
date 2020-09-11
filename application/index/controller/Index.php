<?php
/**
 * +------------------------------------------------------------+
 * @category Index
 * +------------------------------------------------------------+
 * 首页
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2019年12月9日 14:14:43
 *
 */

namespace app\index\controller;

use app\index\controller\Common;
use app\common\model\NameSearch;

class Index extends Common
{
    /**
     * 首页详情
     */
    public function index()
    {
        //名称
        $name = input('param.name');
        $datas = [];

        if ($name) {
            $nameSearchModel = new NameSearch();
            $datas = $nameSearchModel->where([
                                ['name', '=', $name]
                            ])->select();
        }

        return view('index', [
            'datas' => $datas,
            'name' => $name,
        ]);
    }
}
