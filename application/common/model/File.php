<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category File
 * +------------------------------------------------------------+
 * 文件模型
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com
 * @version 1.0
 *
 * Modified at : 2020-03-25 22:17:50
 *
 */
 
namespace app\common\model;

use app\common\model\Common;

class File extends Common 
{

	/**
     * 获取图片完整路径
     *
     * @access public
     * @param $id
     * @return string
     */
    public function getRootPath($id = '')
    {
        if ($id) {
            $path = $this->where('id', $id)->value("root_path");
            return $path;
        }
        return '';
    }
}
