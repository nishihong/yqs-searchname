<?php
/**
 * 
 * +------------------------------------------------------------+
 * @category Acl 
 * +------------------------------------------------------------+
 * 后台资源管理类
 * +------------------------------------------------------------+
 *
 * @author nish
 * @copyright http://www.onlyni.com 2020
 * @version 1.0
 *
 * Modified at : 2020-03-29 15:23:07
 *
 */

namespace app\admin\controller;

use think\Controller;

//当前登录用户是否为超级管理员
defined('IS_SUPER_LOGIN') ? '' : define('IS_SUPER_LOGIN', (int) session('login_utype') == 1);

class Acl extends Controller
{
	//当前登录用户所属用户组ID
    public static $group_id = null;
	
	//系统资源列表
    private static $_resources = null;
	
	//本次操作名
	public static $action_name = null;

	/**
	 * 
	 * +------------------------------------------------------------+
	 * @name createLink
	 * +------------------------------------------------------------+
	 * 创建一个超链接标签
	 * +------------------------------------------------------------+
	 * @example
	 *
	 * @param string $note 文字
	 * @param string $a
	 * @param string $m
	 * @param mixed $params
	 * @param string $attr
	 * @param boolean $txt 无权限操作时是否显示文字描述
	 *
	 */
    public static function a($note, $a='', $m='', $params='', $attr='', $txt=false)
    {
		if (!self::hasAcl($m, $a)) return $txt ? $note : null;
		$a = empty($a) ? request()->action() : $a;
		$m = empty($m) ? request()->controller() : $m;
		$url = url($m.'/'.$a, $params);
		$title = stripos($attr, 'title') !== false ? '' : ' title="'.strip_tags($note).'" ';
		$a = '<a'.$title.' href="'.$url.'" '.$attr.' >'.$note.'</a>';
		
		return $a;
	}

    public static function addLink($note, $a='', $m='', $params='', $txt=false)
    {
        $note = '<span class="ico ico-add"></span>' . $note;
        $attr = 'class="mini-btn mini-btn-green"';
        return self::a($note, $a, $m, $params, $attr, $txt);
    }
	
	//获取当前正在操作的资源名称
    public static function getAction($module='', $action='', $identy='')
    {
		$module = strtolower(empty($module) ? request()->controller() : $module);
		$action = strtolower(empty($action) ? request()->action() : $action);
		$key = $module . (empty($identy) ? '' : '_'. $identy ) . '_' . $action;
		$resources = self::$_resources === null ? self::getAcl() : self::$_resources;
		return isset($resources['resource'][$key]) ? $resources['resource'][$key] : '';
	}
	
	/**
	 * 
	 * @param type 模块
	 * @param type 操作
	 * @return boolean true表示有操作权限，false表示没有操作权限
	 */
	 public static function hasAcl($module='', $action='', $p_resources=null)
	 {
		//超级管理员拥有对所有资源进行访问的权限
		if(IS_SUPER_LOGIN) return true;
		
		$module = (empty($module) ? request()->controller() : $module);
		$action = (empty($action) ? request()->action() : $action);
		
		if($p_resources !== null){
			self::$_resources = $p_resources;
		}
		
		$resources = self::$_resources;
		
		$key = $module . '_' . $action;
		if (isset($resources['resources'][$key])){
			self::$action_name = $resources['resources'][$key][0];
			return $resources['resources'][$key][1] == 1 ? true : false;
		}
		
		return true;
	}

	public static function hasView($identy)
	{
		return IS_SUPER_LOGIN || (isset(self::$_permissions[$identy]) && self::$_permissions[$identy]>0) ? true : false;
	}
}
