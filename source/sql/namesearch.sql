/*
 Navicat Premium Data Transfer

 Source Server         : yunqishi
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : namesearch

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 11/09/2020 15:59:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for dj_admin
-- ----------------------------
DROP TABLE IF EXISTS `dj_admin`;
CREATE TABLE `dj_admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NULL DEFAULT NULL,
  `admin_order_id` int(5) NULL DEFAULT 9999 COMMENT '排序',
  `admin_team` tinyint(1) NULL DEFAULT 0 COMMENT '销售所属队 1火狼队 2蓝天队',
  `admin_username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `admin_password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `admin_nickname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `admin_surename` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  `admin_image` int(11) NOT NULL DEFAULT 0 COMMENT '头像',
  `admin_sex` tinyint(1) NULL DEFAULT 1 COMMENT '性别 1男 2女',
  `admin_mobile` varchar(11) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '手机号',
  `admin_qq` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'qq号码',
  `admin_email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '电子邮箱',
  `admin_status` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1启用 2禁止',
  `admin_addtime` int(10) NULL DEFAULT 0 COMMENT '添加时间',
  `admin_login_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `admin_login_address` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT 'IP归属地',
  `admin_login_time` int(10) NULL DEFAULT 0 COMMENT '最后一次登录的时间',
  `admin_old_id` int(11) NULL DEFAULT -1 COMMENT '老系统id -1 代表是自己创建的',
  `admin_style` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT 'blue.css' COMMENT '用户样式',
  `admin_click_time` int(10) NOT NULL DEFAULT 0 COMMENT '点击时间',
  `admin_order` int(11) NOT NULL DEFAULT 0 COMMENT '官网排序',
  `admin_is_index` tinyint(1) NOT NULL DEFAULT 2 COMMENT '是否显示到官网 1是 2否',
  `admin_click_limit` tinyint(2) NOT NULL DEFAULT 0 COMMENT '每天允许点击上线的次数',
  `is_delete` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否删除 1未删除 2删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of dj_admin
-- ----------------------------
INSERT INTO `dj_admin` VALUES (1, 1, 9999, 3, 'nsh', '116585683bc3cd4c23508d64e8fbb795', '测试1', '测试1', 20749, 1, '16548951545', '165456', '2165418@qq.com', 1, 1497321165, '127.0.0.1', 'IANA', 1599787646, -1, 'darkgreen.css', 1547433828, 33, 1, 100, 1);
INSERT INTO `dj_admin` VALUES (2, 3, 9999, 0, 'test', '369713322b787642b695fae54c716809', '测试', '测试', 0, 1, '13455678912', '123456', '123456@qq.com', 1, 1575616004, '127.0.0.1', 'IANA', 1599093572, -1, 'blue.css', 0, 0, 2, 0, 1);
INSERT INTO `dj_admin` VALUES (3, 2, 9999, 0, 'hbj', '369713322b787642b695fae54c716809', '黄总', '黄总', 0, 1, '15345612345', '123456', '123456@qq.com', 1, 1599030518, '127.0.0.1', 'IANA', 1599040640, -1, 'blue.css', 0, 0, 2, 0, 1);

-- ----------------------------
-- Table structure for dj_file
-- ----------------------------
DROP TABLE IF EXISTS `dj_file`;
CREATE TABLE `dj_file`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原文件名',
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '重命名后的文件名',
  `file_size` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件大小',
  `file_ext` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件格式',
  `save_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '存储路径',
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '文件所在的文件夹',
  `root_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `upload_time` int(10) NULL DEFAULT NULL COMMENT '上传时间',
  `action_user` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上传用户',
  `file_system` tinyint(1) NULL DEFAULT 1 COMMENT '上传的系统 1财务系统 2快快云',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文件表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for dj_group
-- ----------------------------
DROP TABLE IF EXISTS `dj_group`;
CREATE TABLE `dj_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户组名称',
  `group_type` tinyint(1) NULL DEFAULT 1 COMMENT '类型 1管理员 2超级管理员',
  `group_resources` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '可操作权限列表',
  `group_fields` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '字段设置',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of dj_group
-- ----------------------------
INSERT INTO `dj_group` VALUES (1, '超级管理员', 2, '', NULL);
INSERT INTO `dj_group` VALUES (2, '编辑', 1, '[\"index_index\",\"request_index\",\"request_add\",\"request_edit\",\"request_delete\",\"request_title_list\",\"request_click_log\",\"request_ad_log\",\"request_ad_list\",\"request_ad_url_title_list\",\"statistics_monitor\",\"admin_style\"]', '[\"username_edit\",\"user_recommend_commission\"]');
INSERT INTO `dj_group` VALUES (3, '下载吧管理员', 1, '[\"index_index\",\"xzbrequest_index\",\"xzbrequest_title_list\",\"xzbrequest_click_log\",\"xzbrequest_ad_log\",\"xzbrequest_ad_list\",\"xzbrequest_ad_url_title_list\",\"xzbstatistics_monitor\",\"admin_style\"]', NULL);

-- ----------------------------
-- Table structure for dj_log
-- ----------------------------
DROP TABLE IF EXISTS `dj_log`;
CREATE TABLE `dj_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作者',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作内容',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '对应操作的资源链接地址',
  `time` int(10) NOT NULL DEFAULT 0 COMMENT '操作时间',
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作所在IP地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 314 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dj_log
-- ----------------------------
INSERT INTO `dj_log` VALUES (1, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789001, '127.0.0.1');
INSERT INTO `dj_log` VALUES (2, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789002, '127.0.0.1');
INSERT INTO `dj_log` VALUES (3, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789003, '127.0.0.1');
INSERT INTO `dj_log` VALUES (4, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789115, '127.0.0.1');
INSERT INTO `dj_log` VALUES (5, 1, 'nsh', '名称列表 - {\"content\":\"test|生效|2.91\"}', '/admin.php/name_search/add_all.html', 1599789127, '127.0.0.1');
INSERT INTO `dj_log` VALUES (6, 1, 'nsh', '名称列表 - {\"content\":\"test|生效|2.91\"}', '/admin.php/name_search/add_all.html', 1599789146, '127.0.0.1');
INSERT INTO `dj_log` VALUES (7, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789148, '127.0.0.1');
INSERT INTO `dj_log` VALUES (8, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789152, '127.0.0.1');
INSERT INTO `dj_log` VALUES (9, 1, 'nsh', '名称列表 - {\"content\":\"test|gdff|dfad\\r\\ntest|gdff|dfad2\\r\\ntest|gdff|dfad54254\\r\\ntest|gdff|dfad22\\r\\ntest|gdff|dfad\"}', '/admin.php/name_search/add_all.html', 1599789170, '127.0.0.1');
INSERT INTO `dj_log` VALUES (10, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789172, '127.0.0.1');
INSERT INTO `dj_log` VALUES (11, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789204, '127.0.0.1');
INSERT INTO `dj_log` VALUES (12, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789204, '127.0.0.1');
INSERT INTO `dj_log` VALUES (13, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/5.html', 1599789207, '127.0.0.1');
INSERT INTO `dj_log` VALUES (14, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789208, '127.0.0.1');
INSERT INTO `dj_log` VALUES (15, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789213, '127.0.0.1');
INSERT INTO `dj_log` VALUES (16, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/4.html', 1599789215, '127.0.0.1');
INSERT INTO `dj_log` VALUES (17, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789216, '127.0.0.1');
INSERT INTO `dj_log` VALUES (18, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/3.html', 1599789219, '127.0.0.1');
INSERT INTO `dj_log` VALUES (19, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789220, '127.0.0.1');
INSERT INTO `dj_log` VALUES (20, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/2.html', 1599789222, '127.0.0.1');
INSERT INTO `dj_log` VALUES (21, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789222, '127.0.0.1');
INSERT INTO `dj_log` VALUES (22, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599789223, '127.0.0.1');
INSERT INTO `dj_log` VALUES (23, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599789224, '127.0.0.1');
INSERT INTO `dj_log` VALUES (24, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599789225, '127.0.0.1');
INSERT INTO `dj_log` VALUES (25, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599789226, '127.0.0.1');
INSERT INTO `dj_log` VALUES (26, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599789228, '127.0.0.1');
INSERT INTO `dj_log` VALUES (27, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599789229, '127.0.0.1');
INSERT INTO `dj_log` VALUES (28, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599789230, '127.0.0.1');
INSERT INTO `dj_log` VALUES (29, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789231, '127.0.0.1');
INSERT INTO `dj_log` VALUES (30, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789233, '127.0.0.1');
INSERT INTO `dj_log` VALUES (31, 1, 'nsh', '名称列表 - {\"content\":\"ddd|gfgad|dd12\\r\\nddd|gfgad|dd1232\\r\\nddd|gfgad|dd12156\\r\\nddd|gfgad|dd1246\\r\\nddd|gfgad|dd12\"}', '/admin.php/name_search/add_all.html', 1599789258, '127.0.0.1');
INSERT INTO `dj_log` VALUES (32, 1, 'nsh', '名称列表 - {\"content\":\"ddd|gfgad|dd12\\r\\nddd|gfgad|dd1232\\r\\nddd|gfgad|dd12156\\r\\nddd|gfgad|dd1246\\r\\nddd|gfgad|dd12\"}', '/admin.php/name_search/add_all.html', 1599789278, '127.0.0.1');
INSERT INTO `dj_log` VALUES (33, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789279, '127.0.0.1');
INSERT INTO `dj_log` VALUES (34, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789282, '127.0.0.1');
INSERT INTO `dj_log` VALUES (35, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789282, '127.0.0.1');
INSERT INTO `dj_log` VALUES (36, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789283, '127.0.0.1');
INSERT INTO `dj_log` VALUES (37, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789355, '127.0.0.1');
INSERT INTO `dj_log` VALUES (38, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/8.html', 1599789357, '127.0.0.1');
INSERT INTO `dj_log` VALUES (39, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789358, '127.0.0.1');
INSERT INTO `dj_log` VALUES (40, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789359, '127.0.0.1');
INSERT INTO `dj_log` VALUES (41, 1, 'nsh', '名称列表 - {\"content\":\"456df|fasd|dfa\\r\\n456df|fasd|dfa2\"}', '/admin.php/name_search/add_all.html', 1599789370, '127.0.0.1');
INSERT INTO `dj_log` VALUES (42, 1, 'nsh', '名称列表 - {\"content\":\"456df|fasd|dfa\\r\\n456df|fasd|dfa2\"}', '/admin.php/name_search/add_all.html', 1599789723, '127.0.0.1');
INSERT INTO `dj_log` VALUES (43, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789725, '127.0.0.1');
INSERT INTO `dj_log` VALUES (44, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789732, '127.0.0.1');
INSERT INTO `dj_log` VALUES (45, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789733, '127.0.0.1');
INSERT INTO `dj_log` VALUES (46, 1, 'nsh', '名称列表 - {\"content\":\"fd|dfad|fads\\r\\nfd|dfad|fads156\"}', '/admin.php/name_search/add_all.html', 1599789743, '127.0.0.1');
INSERT INTO `dj_log` VALUES (47, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789744, '127.0.0.1');
INSERT INTO `dj_log` VALUES (48, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789746, '127.0.0.1');
INSERT INTO `dj_log` VALUES (49, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789746, '127.0.0.1');
INSERT INTO `dj_log` VALUES (50, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789747, '127.0.0.1');
INSERT INTO `dj_log` VALUES (51, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789747, '127.0.0.1');
INSERT INTO `dj_log` VALUES (52, 1, 'nsh', '名称列表 - {\"content\":\"fd|dfad|fadsfd|dfad|fadsfd|dfad|fads\"}', '/admin.php/name_search/add_all.html', 1599789750, '127.0.0.1');
INSERT INTO `dj_log` VALUES (53, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789751, '127.0.0.1');
INSERT INTO `dj_log` VALUES (54, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789784, '127.0.0.1');
INSERT INTO `dj_log` VALUES (55, 1, 'nsh', '名称列表 - {\"content\":\"fd|dfad|fads\\r\\nfd|dfad|fads\\r\\nfd|dfad|fads14565\"}', '/admin.php/name_search/add_all.html', 1599789791, '127.0.0.1');
INSERT INTO `dj_log` VALUES (56, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789792, '127.0.0.1');
INSERT INTO `dj_log` VALUES (57, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789805, '127.0.0.1');
INSERT INTO `dj_log` VALUES (58, 1, 'nsh', '名称列表 - {\"content\":\"fd|dfad|fads\"}', '/admin.php/name_search/add_all.html', 1599789807, '127.0.0.1');
INSERT INTO `dj_log` VALUES (59, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789809, '127.0.0.1');
INSERT INTO `dj_log` VALUES (60, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789811, '127.0.0.1');
INSERT INTO `dj_log` VALUES (61, 1, 'nsh', '名称列表 - {\"content\":\"fd|dfad|fads\"}', '/admin.php/name_search/add_all.html', 1599789813, '127.0.0.1');
INSERT INTO `dj_log` VALUES (62, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789815, '127.0.0.1');
INSERT INTO `dj_log` VALUES (63, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789839, '127.0.0.1');
INSERT INTO `dj_log` VALUES (64, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789840, '127.0.0.1');
INSERT INTO `dj_log` VALUES (65, 1, 'nsh', '名称列表 - {\"content\":\"add_all|dfad\\\\|ddfa\"}', '/admin.php/name_search/add_all.html', 1599789847, '127.0.0.1');
INSERT INTO `dj_log` VALUES (66, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789848, '127.0.0.1');
INSERT INTO `dj_log` VALUES (67, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789893, '127.0.0.1');
INSERT INTO `dj_log` VALUES (68, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789894, '127.0.0.1');
INSERT INTO `dj_log` VALUES (69, 1, 'nsh', '名称列表 - {\"content\":\"fsd|fsdaf|dfasdf\\r\\nfsd|fsdaf|dfasdf145fsd|fsdaf|dfasdf\\r\\nfsd|fsdaf|dfasdf\"}', '/admin.php/name_search/add_all.html', 1599789907, '127.0.0.1');
INSERT INTO `dj_log` VALUES (70, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789907, '127.0.0.1');
INSERT INTO `dj_log` VALUES (71, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789934, '127.0.0.1');
INSERT INTO `dj_log` VALUES (72, 1, 'nsh', '名称列表 - {\"content\":\"凯西|订单的|多发点\\r\\n打|各大|多发点\"}', '/admin.php/name_search/add_all.html', 1599789946, '127.0.0.1');
INSERT INTO `dj_log` VALUES (73, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789947, '127.0.0.1');
INSERT INTO `dj_log` VALUES (74, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789955, '127.0.0.1');
INSERT INTO `dj_log` VALUES (75, 1, 'nsh', '名称列表 - {\"content\":\"凯西|订单的|29\\r\\n打大声道|各大|28\"}', '/admin.php/name_search/add_all.html', 1599789966, '127.0.0.1');
INSERT INTO `dj_log` VALUES (76, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789967, '127.0.0.1');
INSERT INTO `dj_log` VALUES (77, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599789971, '127.0.0.1');
INSERT INTO `dj_log` VALUES (78, 1, 'nsh', '名称列表 - {\"content\":\"凯西|订单的|多发点\\r\\n凯西|各大|多发点\"}', '/admin.php/name_search/add_all.html', 1599789977, '127.0.0.1');
INSERT INTO `dj_log` VALUES (79, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599789978, '127.0.0.1');
INSERT INTO `dj_log` VALUES (80, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790057, '127.0.0.1');
INSERT INTO `dj_log` VALUES (81, 1, 'nsh', ' - {\"items\":[\"26\",\"25\",\"24\",\"23\",\"22\",\"21\",\"20\",\"19\",\"18\",\"17\",\"16\",\"15\",\"14\",\"13\",\"12\",\"11\",\"10\",\"9\",\"7\",\"6\",\"1\"]}', '/admin.php/name_search/delte.html', 1599790061, '127.0.0.1');
INSERT INTO `dj_log` VALUES (82, 1, 'nsh', ' - {\"items\":[\"26\",\"25\",\"24\",\"23\",\"22\",\"21\",\"20\",\"19\",\"18\",\"17\",\"16\",\"15\",\"14\",\"13\",\"12\",\"11\",\"10\",\"9\",\"7\",\"6\",\"1\"]}', '/admin.php/name_search/delte.html', 1599790067, '127.0.0.1');
INSERT INTO `dj_log` VALUES (83, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790084, '127.0.0.1');
INSERT INTO `dj_log` VALUES (84, 1, 'nsh', '删除 - {\"items\":[\"26\",\"25\",\"24\",\"23\",\"22\",\"21\",\"20\",\"19\",\"18\",\"17\",\"16\",\"15\",\"14\",\"13\",\"12\",\"11\",\"10\",\"9\",\"7\",\"6\",\"1\"]}', '/admin.php/name_search/delete.html', 1599790088, '127.0.0.1');
INSERT INTO `dj_log` VALUES (85, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790089, '127.0.0.1');
INSERT INTO `dj_log` VALUES (86, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599790091, '127.0.0.1');
INSERT INTO `dj_log` VALUES (87, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599790105, '127.0.0.1');
INSERT INTO `dj_log` VALUES (88, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790106, '127.0.0.1');
INSERT INTO `dj_log` VALUES (89, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790107, '127.0.0.1');
INSERT INTO `dj_log` VALUES (90, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790115, '127.0.0.1');
INSERT INTO `dj_log` VALUES (91, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790116, '127.0.0.1');
INSERT INTO `dj_log` VALUES (92, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599790120, '127.0.0.1');
INSERT INTO `dj_log` VALUES (93, 1, 'nsh', '名称列表 - {\"content\":\"今晚|开始|20\\r\\n明晚|开始|30\"}', '/admin.php/name_search/add_all.html', 1599790136, '127.0.0.1');
INSERT INTO `dj_log` VALUES (94, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790138, '127.0.0.1');
INSERT INTO `dj_log` VALUES (95, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790138, '127.0.0.1');
INSERT INTO `dj_log` VALUES (96, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790139, '127.0.0.1');
INSERT INTO `dj_log` VALUES (97, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599790141, '127.0.0.1');
INSERT INTO `dj_log` VALUES (98, 1, 'nsh', '名称列表 - {\"content\":\"今晚|开始|20\\r\\n今晚|开始22|202\\r\\n今晚|开始22|204\\r\\n今晚|开始22|205\"}', '/admin.php/name_search/add_all.html', 1599790158, '127.0.0.1');
INSERT INTO `dj_log` VALUES (99, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790159, '127.0.0.1');
INSERT INTO `dj_log` VALUES (100, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790160, '127.0.0.1');
INSERT INTO `dj_log` VALUES (101, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/31.html', 1599790164, '127.0.0.1');
INSERT INTO `dj_log` VALUES (102, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790165, '127.0.0.1');
INSERT INTO `dj_log` VALUES (103, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790175, '127.0.0.1');
INSERT INTO `dj_log` VALUES (104, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599790176, '127.0.0.1');
INSERT INTO `dj_log` VALUES (105, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599790177, '127.0.0.1');
INSERT INTO `dj_log` VALUES (106, 1, 'nsh', '修改角色组 - []', '/admin.php/group/edit/id/3.html', 1599790177, '127.0.0.1');
INSERT INTO `dj_log` VALUES (107, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599790180, '127.0.0.1');
INSERT INTO `dj_log` VALUES (108, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599790180, '127.0.0.1');
INSERT INTO `dj_log` VALUES (109, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599790181, '127.0.0.1');
INSERT INTO `dj_log` VALUES (110, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790185, '127.0.0.1');
INSERT INTO `dj_log` VALUES (111, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790185, '127.0.0.1');
INSERT INTO `dj_log` VALUES (112, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790489, '127.0.0.1');
INSERT INTO `dj_log` VALUES (113, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599790490, '127.0.0.1');
INSERT INTO `dj_log` VALUES (114, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599791559, '127.0.0.1');
INSERT INTO `dj_log` VALUES (115, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599791561, '127.0.0.1');
INSERT INTO `dj_log` VALUES (116, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599791562, '127.0.0.1');
INSERT INTO `dj_log` VALUES (117, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599791562, '127.0.0.1');
INSERT INTO `dj_log` VALUES (118, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599791563, '127.0.0.1');
INSERT INTO `dj_log` VALUES (119, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599791563, '127.0.0.1');
INSERT INTO `dj_log` VALUES (120, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599791565, '127.0.0.1');
INSERT INTO `dj_log` VALUES (121, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599791569, '127.0.0.1');
INSERT INTO `dj_log` VALUES (122, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599791574, '127.0.0.1');
INSERT INTO `dj_log` VALUES (123, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599791575, '127.0.0.1');
INSERT INTO `dj_log` VALUES (124, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599791576, '127.0.0.1');
INSERT INTO `dj_log` VALUES (125, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599791576, '127.0.0.1');
INSERT INTO `dj_log` VALUES (126, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599791578, '127.0.0.1');
INSERT INTO `dj_log` VALUES (127, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791580, '127.0.0.1');
INSERT INTO `dj_log` VALUES (128, 1, 'nsh', '修改密码 - []', '/admin.php/admin/edit/id/1.html', 1599791581, '127.0.0.1');
INSERT INTO `dj_log` VALUES (129, 1, 'nsh', '修改密码 - []', '/admin.php/admin/edit/id/1.html', 1599791582, '127.0.0.1');
INSERT INTO `dj_log` VALUES (130, 1, 'nsh', '修改密码 - {\"field\":\"admin_username\",\"value\":\"nsh\",\"unique\":\"true\"}', '/admin.php/admin/edit/id/1.html', 1599791601, '127.0.0.1');
INSERT INTO `dj_log` VALUES (131, 1, 'nsh', '修改密码 - {\"group_id\":\"1\",\"admin_username\":\"nsh\",\"admin_sex\":\"1\",\"admin_nickname\":\"测试1\",\"admin_surename\":\"测试1\",\"admin_image\":\"20749\",\"admin_qq\":\"165456\",\"admin_email\":\"2165418@qq.com\",\"admin_mobile\":\"16548951545\"}', '/admin.php/admin/edit/id/1.html', 1599791601, '127.0.0.1');
INSERT INTO `dj_log` VALUES (132, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791602, '127.0.0.1');
INSERT INTO `dj_log` VALUES (133, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791604, '127.0.0.1');
INSERT INTO `dj_log` VALUES (134, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791604, '127.0.0.1');
INSERT INTO `dj_log` VALUES (135, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791605, '127.0.0.1');
INSERT INTO `dj_log` VALUES (136, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791605, '127.0.0.1');
INSERT INTO `dj_log` VALUES (137, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791606, '127.0.0.1');
INSERT INTO `dj_log` VALUES (138, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791606, '127.0.0.1');
INSERT INTO `dj_log` VALUES (139, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791607, '127.0.0.1');
INSERT INTO `dj_log` VALUES (140, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791607, '127.0.0.1');
INSERT INTO `dj_log` VALUES (141, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791607, '127.0.0.1');
INSERT INTO `dj_log` VALUES (142, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791607, '127.0.0.1');
INSERT INTO `dj_log` VALUES (143, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791608, '127.0.0.1');
INSERT INTO `dj_log` VALUES (144, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791618, '127.0.0.1');
INSERT INTO `dj_log` VALUES (145, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791619, '127.0.0.1');
INSERT INTO `dj_log` VALUES (146, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791619, '127.0.0.1');
INSERT INTO `dj_log` VALUES (147, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599791621, '127.0.0.1');
INSERT INTO `dj_log` VALUES (148, 1, 'nsh', '管理员列表 - []', '/admin.php/admin/index.html', 1599792282, '127.0.0.1');
INSERT INTO `dj_log` VALUES (149, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794447, '127.0.0.1');
INSERT INTO `dj_log` VALUES (150, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/32.html', 1599794450, '127.0.0.1');
INSERT INTO `dj_log` VALUES (151, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794451, '127.0.0.1');
INSERT INTO `dj_log` VALUES (152, 1, 'nsh', '删除 - {\"items\":[\"29\"]}', '/admin.php/name_search/delete.html', 1599794456, '127.0.0.1');
INSERT INTO `dj_log` VALUES (153, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794457, '127.0.0.1');
INSERT INTO `dj_log` VALUES (154, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794458, '127.0.0.1');
INSERT INTO `dj_log` VALUES (155, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794459, '127.0.0.1');
INSERT INTO `dj_log` VALUES (156, 1, 'nsh', '删除 - {\"items\":[\"30\"]}', '/admin.php/name_search/delete.html', 1599794461, '127.0.0.1');
INSERT INTO `dj_log` VALUES (157, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794462, '127.0.0.1');
INSERT INTO `dj_log` VALUES (158, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794464, '127.0.0.1');
INSERT INTO `dj_log` VALUES (159, 1, 'nsh', '名称列表 - {\"content\":\"测试|游戏|99\\r\\n腾讯|游戏|199\\r\\n阿里|益智299\"}', '/admin.php/name_search/add_all.html', 1599794500, '127.0.0.1');
INSERT INTO `dj_log` VALUES (160, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794501, '127.0.0.1');
INSERT INTO `dj_log` VALUES (161, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794502, '127.0.0.1');
INSERT INTO `dj_log` VALUES (162, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/35.html', 1599794512, '127.0.0.1');
INSERT INTO `dj_log` VALUES (163, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794514, '127.0.0.1');
INSERT INTO `dj_log` VALUES (164, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794514, '127.0.0.1');
INSERT INTO `dj_log` VALUES (165, 1, 'nsh', '名称列表 - {\"content\":\"阿里|益智|299\"}', '/admin.php/name_search/add_all.html', 1599794529, '127.0.0.1');
INSERT INTO `dj_log` VALUES (166, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794531, '127.0.0.1');
INSERT INTO `dj_log` VALUES (167, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794533, '127.0.0.1');
INSERT INTO `dj_log` VALUES (168, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794534, '127.0.0.1');
INSERT INTO `dj_log` VALUES (169, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794534, '127.0.0.1');
INSERT INTO `dj_log` VALUES (170, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794534, '127.0.0.1');
INSERT INTO `dj_log` VALUES (171, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794534, '127.0.0.1');
INSERT INTO `dj_log` VALUES (172, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794550, '127.0.0.1');
INSERT INTO `dj_log` VALUES (173, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794551, '127.0.0.1');
INSERT INTO `dj_log` VALUES (174, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794551, '127.0.0.1');
INSERT INTO `dj_log` VALUES (175, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794556, '127.0.0.1');
INSERT INTO `dj_log` VALUES (176, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794813, '127.0.0.1');
INSERT INTO `dj_log` VALUES (177, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794814, '127.0.0.1');
INSERT INTO `dj_log` VALUES (178, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794814, '127.0.0.1');
INSERT INTO `dj_log` VALUES (179, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794814, '127.0.0.1');
INSERT INTO `dj_log` VALUES (180, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794864, '127.0.0.1');
INSERT INTO `dj_log` VALUES (181, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794864, '127.0.0.1');
INSERT INTO `dj_log` VALUES (182, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794864, '127.0.0.1');
INSERT INTO `dj_log` VALUES (183, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794865, '127.0.0.1');
INSERT INTO `dj_log` VALUES (184, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794865, '127.0.0.1');
INSERT INTO `dj_log` VALUES (185, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794865, '127.0.0.1');
INSERT INTO `dj_log` VALUES (186, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794869, '127.0.0.1');
INSERT INTO `dj_log` VALUES (187, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794872, '127.0.0.1');
INSERT INTO `dj_log` VALUES (188, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794873, '127.0.0.1');
INSERT INTO `dj_log` VALUES (189, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794873, '127.0.0.1');
INSERT INTO `dj_log` VALUES (190, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599794873, '127.0.0.1');
INSERT INTO `dj_log` VALUES (191, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794877, '127.0.0.1');
INSERT INTO `dj_log` VALUES (192, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599794877, '127.0.0.1');
INSERT INTO `dj_log` VALUES (193, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795695, '127.0.0.1');
INSERT INTO `dj_log` VALUES (194, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795696, '127.0.0.1');
INSERT INTO `dj_log` VALUES (195, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795696, '127.0.0.1');
INSERT INTO `dj_log` VALUES (196, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795696, '127.0.0.1');
INSERT INTO `dj_log` VALUES (197, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795697, '127.0.0.1');
INSERT INTO `dj_log` VALUES (198, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795697, '127.0.0.1');
INSERT INTO `dj_log` VALUES (199, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795697, '127.0.0.1');
INSERT INTO `dj_log` VALUES (200, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795699, '127.0.0.1');
INSERT INTO `dj_log` VALUES (201, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795699, '127.0.0.1');
INSERT INTO `dj_log` VALUES (202, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599795702, '127.0.0.1');
INSERT INTO `dj_log` VALUES (203, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795703, '127.0.0.1');
INSERT INTO `dj_log` VALUES (204, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795703, '127.0.0.1');
INSERT INTO `dj_log` VALUES (205, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795703, '127.0.0.1');
INSERT INTO `dj_log` VALUES (206, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795703, '127.0.0.1');
INSERT INTO `dj_log` VALUES (207, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795958, '127.0.0.1');
INSERT INTO `dj_log` VALUES (208, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795959, '127.0.0.1');
INSERT INTO `dj_log` VALUES (209, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795959, '127.0.0.1');
INSERT INTO `dj_log` VALUES (210, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795959, '127.0.0.1');
INSERT INTO `dj_log` VALUES (211, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599795959, '127.0.0.1');
INSERT INTO `dj_log` VALUES (212, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599795961, '127.0.0.1');
INSERT INTO `dj_log` VALUES (213, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599795962, '127.0.0.1');
INSERT INTO `dj_log` VALUES (214, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599796006, '127.0.0.1');
INSERT INTO `dj_log` VALUES (215, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599796007, '127.0.0.1');
INSERT INTO `dj_log` VALUES (216, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796008, '127.0.0.1');
INSERT INTO `dj_log` VALUES (217, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796008, '127.0.0.1');
INSERT INTO `dj_log` VALUES (218, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599796015, '127.0.0.1');
INSERT INTO `dj_log` VALUES (219, 1, 'nsh', '名称列表 - {\"content\":\"云骑士|游戏|19999\\r\\n云骑士|游戏111|19999\"}', '/admin.php/name_search/add_all.html', 1599796058, '127.0.0.1');
INSERT INTO `dj_log` VALUES (220, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796060, '127.0.0.1');
INSERT INTO `dj_log` VALUES (221, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796061, '127.0.0.1');
INSERT INTO `dj_log` VALUES (222, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599796066, '127.0.0.1');
INSERT INTO `dj_log` VALUES (223, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796076, '127.0.0.1');
INSERT INTO `dj_log` VALUES (224, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796077, '127.0.0.1');
INSERT INTO `dj_log` VALUES (225, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796255, '127.0.0.1');
INSERT INTO `dj_log` VALUES (226, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/38.html', 1599796257, '127.0.0.1');
INSERT INTO `dj_log` VALUES (227, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796258, '127.0.0.1');
INSERT INTO `dj_log` VALUES (228, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599796261, '127.0.0.1');
INSERT INTO `dj_log` VALUES (229, 1, 'nsh', '名称列表 - {\"content\":\"阿里|开心麻花|299\\r\\n阿里游戏|开心麻花|299\\r\\n阿里游戏乐趣|开心麻花|299\\r\\n腾讯|开心麻花|299\"}', '/admin.php/name_search/add_all.html', 1599796293, '127.0.0.1');
INSERT INTO `dj_log` VALUES (230, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796294, '127.0.0.1');
INSERT INTO `dj_log` VALUES (231, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796296, '127.0.0.1');
INSERT INTO `dj_log` VALUES (232, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796297, '127.0.0.1');
INSERT INTO `dj_log` VALUES (233, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599796323, '127.0.0.1');
INSERT INTO `dj_log` VALUES (234, 1, 'nsh', '名称列表 - {\"content\":\"阿里|开心|222\\r\\n阿里游戏|开心|222\\r\\n阿里游戏|开心麻花|222\\r\\n腾讯|开心麻花|222\"}', '/admin.php/name_search/add_all.html', 1599796352, '127.0.0.1');
INSERT INTO `dj_log` VALUES (235, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796353, '127.0.0.1');
INSERT INTO `dj_log` VALUES (236, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796354, '127.0.0.1');
INSERT INTO `dj_log` VALUES (237, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796355, '127.0.0.1');
INSERT INTO `dj_log` VALUES (238, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796355, '127.0.0.1');
INSERT INTO `dj_log` VALUES (239, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796355, '127.0.0.1');
INSERT INTO `dj_log` VALUES (240, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796356, '127.0.0.1');
INSERT INTO `dj_log` VALUES (241, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/add_all.html', 1599796358, '127.0.0.1');
INSERT INTO `dj_log` VALUES (242, 1, 'nsh', '名称列表 - {\"content\":\"阿里|开心|222\\r\\n阿里游戏|开心|222\\r\\n阿里游戏合作|开心麻花|222\\r\\n腾讯|开心麻花|222\"}', '/admin.php/name_search/add_all.html', 1599796367, '127.0.0.1');
INSERT INTO `dj_log` VALUES (243, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796368, '127.0.0.1');
INSERT INTO `dj_log` VALUES (244, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796368, '127.0.0.1');
INSERT INTO `dj_log` VALUES (245, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796369, '127.0.0.1');
INSERT INTO `dj_log` VALUES (246, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796369, '127.0.0.1');
INSERT INTO `dj_log` VALUES (247, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796369, '127.0.0.1');
INSERT INTO `dj_log` VALUES (248, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796369, '127.0.0.1');
INSERT INTO `dj_log` VALUES (249, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796369, '127.0.0.1');
INSERT INTO `dj_log` VALUES (250, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796370, '127.0.0.1');
INSERT INTO `dj_log` VALUES (251, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796370, '127.0.0.1');
INSERT INTO `dj_log` VALUES (252, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796370, '127.0.0.1');
INSERT INTO `dj_log` VALUES (253, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796371, '127.0.0.1');
INSERT INTO `dj_log` VALUES (254, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796371, '127.0.0.1');
INSERT INTO `dj_log` VALUES (255, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796371, '127.0.0.1');
INSERT INTO `dj_log` VALUES (256, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/28.html', 1599796374, '127.0.0.1');
INSERT INTO `dj_log` VALUES (257, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796375, '127.0.0.1');
INSERT INTO `dj_log` VALUES (258, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796375, '127.0.0.1');
INSERT INTO `dj_log` VALUES (259, 1, 'nsh', '删除 - []', '/admin.php/name_search/delete/id/27.html', 1599796377, '127.0.0.1');
INSERT INTO `dj_log` VALUES (260, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796378, '127.0.0.1');
INSERT INTO `dj_log` VALUES (261, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796378, '127.0.0.1');
INSERT INTO `dj_log` VALUES (262, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796378, '127.0.0.1');
INSERT INTO `dj_log` VALUES (263, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796378, '127.0.0.1');
INSERT INTO `dj_log` VALUES (264, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796378, '127.0.0.1');
INSERT INTO `dj_log` VALUES (265, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796379, '127.0.0.1');
INSERT INTO `dj_log` VALUES (266, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796379, '127.0.0.1');
INSERT INTO `dj_log` VALUES (267, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796379, '127.0.0.1');
INSERT INTO `dj_log` VALUES (268, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796379, '127.0.0.1');
INSERT INTO `dj_log` VALUES (269, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (270, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (271, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (272, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (273, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (274, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (275, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796380, '127.0.0.1');
INSERT INTO `dj_log` VALUES (276, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796381, '127.0.0.1');
INSERT INTO `dj_log` VALUES (277, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796381, '127.0.0.1');
INSERT INTO `dj_log` VALUES (278, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796381, '127.0.0.1');
INSERT INTO `dj_log` VALUES (279, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796381, '127.0.0.1');
INSERT INTO `dj_log` VALUES (280, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796381, '127.0.0.1');
INSERT INTO `dj_log` VALUES (281, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796381, '127.0.0.1');
INSERT INTO `dj_log` VALUES (282, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (283, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (284, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (285, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (286, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (287, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (288, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796382, '127.0.0.1');
INSERT INTO `dj_log` VALUES (289, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796383, '127.0.0.1');
INSERT INTO `dj_log` VALUES (290, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796383, '127.0.0.1');
INSERT INTO `dj_log` VALUES (291, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796494, '127.0.0.1');
INSERT INTO `dj_log` VALUES (292, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796495, '127.0.0.1');
INSERT INTO `dj_log` VALUES (293, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796495, '127.0.0.1');
INSERT INTO `dj_log` VALUES (294, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796495, '127.0.0.1');
INSERT INTO `dj_log` VALUES (295, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599796496, '127.0.0.1');
INSERT INTO `dj_log` VALUES (296, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796496, '127.0.0.1');
INSERT INTO `dj_log` VALUES (297, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796497, '127.0.0.1');
INSERT INTO `dj_log` VALUES (298, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796497, '127.0.0.1');
INSERT INTO `dj_log` VALUES (299, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796498, '127.0.0.1');
INSERT INTO `dj_log` VALUES (300, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599796498, '127.0.0.1');
INSERT INTO `dj_log` VALUES (301, 1, 'nsh', '缓存管理 - []', '/admin.php/system/cache.html', 1599796499, '127.0.0.1');
INSERT INTO `dj_log` VALUES (302, 1, 'nsh', '角色组管理 - []', '/admin.php/group/index.html', 1599796499, '127.0.0.1');
INSERT INTO `dj_log` VALUES (303, 1, 'nsh', '名称列表 - []', '/admin.php/name_search/index.html', 1599796499, '127.0.0.1');
INSERT INTO `dj_log` VALUES (304, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796500, '127.0.0.1');
INSERT INTO `dj_log` VALUES (305, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796500, '127.0.0.1');
INSERT INTO `dj_log` VALUES (306, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796500, '127.0.0.1');
INSERT INTO `dj_log` VALUES (307, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796501, '127.0.0.1');
INSERT INTO `dj_log` VALUES (308, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796501, '127.0.0.1');
INSERT INTO `dj_log` VALUES (309, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796501, '127.0.0.1');
INSERT INTO `dj_log` VALUES (310, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796502, '127.0.0.1');
INSERT INTO `dj_log` VALUES (311, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796530, '127.0.0.1');
INSERT INTO `dj_log` VALUES (312, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796531, '127.0.0.1');
INSERT INTO `dj_log` VALUES (313, 1, 'nsh', '进入首页', '/admin.php/index/index.html', 1599796725, '127.0.0.1');

-- ----------------------------
-- Table structure for dj_login
-- ----------------------------
DROP TABLE IF EXISTS `dj_login`;
CREATE TABLE `dj_login`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `object_id` int(11) NULL DEFAULT 0 COMMENT '用户ID或者管理员ID',
  `object_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  `login_type` tinyint(1) NULL DEFAULT 1 COMMENT '类型 1管理员 2用户 3代理',
  `login_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'IP地址',
  `login_address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '地址',
  `login_addtime` int(10) NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '登录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of dj_login
-- ----------------------------
INSERT INTO `dj_login` VALUES (1, 1, 'kk_nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1575019013);
INSERT INTO `dj_login` VALUES (2, 1, 'kk_nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1575541940);
INSERT INTO `dj_login` VALUES (3, 1, 'kk_nsh', 1, '120.39.69.219', '福建省厦门市 电信', 1575863690);
INSERT INTO `dj_login` VALUES (4, 1, 'nsh', 1, '120.39.69.219', '福建省厦门市 电信', 1575863738);
INSERT INTO `dj_login` VALUES (5, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1582854173);
INSERT INTO `dj_login` VALUES (6, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1583650819);
INSERT INTO `dj_login` VALUES (7, 1, 'nsh', 1, '120.36.228.107', '福建省厦门市 电信', 1583682728);
INSERT INTO `dj_login` VALUES (8, 1, 'nsh', 1, '117.30.55.198', '福建省厦门市 电信', 1583756564);
INSERT INTO `dj_login` VALUES (9, 1, 'nsh', 1, '120.41.164.213', '福建省 电信(CDMA全省共用出口)', 1583761585);
INSERT INTO `dj_login` VALUES (10, 1, 'nsh', 1, '120.41.164.213', '福建省 电信(CDMA全省共用出口)', 1583763522);
INSERT INTO `dj_login` VALUES (11, 1, 'nsh', 1, '120.41.164.213', '福建省 电信(CDMA全省共用出口)', 1583765139);
INSERT INTO `dj_login` VALUES (12, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1583765249);
INSERT INTO `dj_login` VALUES (13, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585142879);
INSERT INTO `dj_login` VALUES (14, 1, 'nsh', 1, '59.57.192.103', '福建省厦门市 电信', 1585153914);
INSERT INTO `dj_login` VALUES (15, 1, 'nsh', 1, '59.57.192.103', '福建省厦门市 电信', 1585156063);
INSERT INTO `dj_login` VALUES (16, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585232884);
INSERT INTO `dj_login` VALUES (17, 1, 'nsh', 1, '59.57.192.103', '福建省厦门市 电信', 1585324156);
INSERT INTO `dj_login` VALUES (18, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585325891);
INSERT INTO `dj_login` VALUES (19, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585368565);
INSERT INTO `dj_login` VALUES (20, 1, 'nsh', 1, '59.57.192.103', '福建省厦门市 电信', 1585374008);
INSERT INTO `dj_login` VALUES (21, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585379028);
INSERT INTO `dj_login` VALUES (22, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585380396);
INSERT INTO `dj_login` VALUES (23, 1, 'nsh', 1, '59.57.192.103', '福建省厦门市 电信', 1585380411);
INSERT INTO `dj_login` VALUES (24, 1, 'nsh', 1, '59.57.192.103', '福建省厦门市 电信', 1585490329);
INSERT INTO `dj_login` VALUES (25, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585492314);
INSERT INTO `dj_login` VALUES (26, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585584759);
INSERT INTO `dj_login` VALUES (27, 1, 'nsh', 1, '59.57.170.247', '福建省厦门市 电信', 1585655633);
INSERT INTO `dj_login` VALUES (28, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1585832934);
INSERT INTO `dj_login` VALUES (29, 1, 'nsh', 1, '112.49.76.180', '福建省 移动', 1586006211);
INSERT INTO `dj_login` VALUES (30, 1, 'nsh', 1, '27.154.215.102', '福建省厦门市 电信', 1586231718);
INSERT INTO `dj_login` VALUES (31, 1, 'nsh', 1, '120.41.166.15', '福建省 电信(CDMA全省共用出口)', 1586267151);
INSERT INTO `dj_login` VALUES (32, 1, 'nsh', 1, '27.154.101.249', '福建省厦门市 电信', 1586510941);
INSERT INTO `dj_login` VALUES (33, 1, 'nsh', 1, '110.87.71.91', '福建省厦门市 电信', 1586601689);
INSERT INTO `dj_login` VALUES (34, 1, 'nsh', 1, '117.28.98.160', '福建省泉州市 电信', 1586746538);
INSERT INTO `dj_login` VALUES (35, 1, 'nsh', 1, '27.154.215.214', '福建省厦门市 电信', 1587371605);
INSERT INTO `dj_login` VALUES (36, 1, 'nsh', 1, '27.152.139.13', '福建省泉州市 电信', 1589350473);
INSERT INTO `dj_login` VALUES (37, 1, 'nsh', 1, '120.36.253.77', '福建省厦门市 电信', 1591281068);
INSERT INTO `dj_login` VALUES (38, 1, 'nsh', 1, '120.36.248.144', '福建省厦门市 电信', 1592838992);
INSERT INTO `dj_login` VALUES (39, 1, 'nsh', 1, '117.28.98.98', '福建省泉州市 电信', 1594346693);
INSERT INTO `dj_login` VALUES (40, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1598927196);
INSERT INTO `dj_login` VALUES (41, 3, 'hbj', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1599030547);
INSERT INTO `dj_login` VALUES (42, 3, 'hbj', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1599040382);
INSERT INTO `dj_login` VALUES (43, 3, 'hbj', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1599040640);
INSERT INTO `dj_login` VALUES (44, 2, 'test', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1599040645);
INSERT INTO `dj_login` VALUES (45, 2, 'test', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1599093572);
INSERT INTO `dj_login` VALUES (46, 1, 'nsh', 1, '127.0.0.1', 'IANA 保留地址用于本地回送', 1599787646);

-- ----------------------------
-- Table structure for dj_name_search
-- ----------------------------
DROP TABLE IF EXISTS `dj_name_search`;
CREATE TABLE `dj_name_search`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '名称',
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '价格',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 41 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '名称搜索表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dj_name_search
-- ----------------------------
INSERT INTO `dj_name_search` VALUES (40, '阿里游戏合作', '开心麻花', 222.00);
INSERT INTO `dj_name_search` VALUES (39, '阿里游戏', '开心', 222.00);
INSERT INTO `dj_name_search` VALUES (37, '云骑士', '游戏', 19999.00);
INSERT INTO `dj_name_search` VALUES (34, '腾讯', '游戏', 199.00);
INSERT INTO `dj_name_search` VALUES (36, '阿里', '益智', 299.00);
INSERT INTO `dj_name_search` VALUES (33, '测试', '游戏', 99.00);

-- ----------------------------
-- Table structure for dj_system
-- ----------------------------
DROP TABLE IF EXISTS `dj_system`;
CREATE TABLE `dj_system`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型',
  `identy` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `attvalue` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统配置表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of dj_system
-- ----------------------------
INSERT INTO `dj_system` VALUES (22, 'base', 'WEB_TITLE', '骑士电竞');
INSERT INTO `dj_system` VALUES (23, 'base', 'WEB_ICON', '4');
INSERT INTO `dj_system` VALUES (24, 'base', 'WEB_COPYRIGHT', 'Copyright © 2004 - 2020 yqsdj.Com Co. All Rights Reserved 梅州云骑士网络科技有限公司 版权所有');
INSERT INTO `dj_system` VALUES (25, 'base', 'WEB_ADDRESS', '');
INSERT INTO `dj_system` VALUES (37, 'base', 'WEB_DESCRIPTION', '骑士电竞是一个专注服务电竞爱好者的游戏综合门户网站，提供最新最热的各类热门电子竞技游戏赛事报道、玩法攻略、电竞资讯等。');
INSERT INTO `dj_system` VALUES (38, 'base', 'WEB_KEYWORDS', '骑士电竞是一个专注服务电竞爱好者的游戏综合门户网站，提供最新最热的各类热门电子竞技游戏赛事报道、玩法攻略、电竞资讯等。');
INSERT INTO `dj_system` VALUES (39, 'base', 'WEB_EMAIL', '骑士电竞');
INSERT INTO `dj_system` VALUES (40, 'base', 'WEB_LOGO', '15');
INSERT INTO `dj_system` VALUES (41, 'base', 'WEB_COPYRIGHT_INFO', '骑士电竞是一个专注服务电竞爱好者的游戏综合门户网站，提供最新最热的各类热门电子竞技游戏赛事报道、玩法攻略、电竞资讯等。');
INSERT INTO `dj_system` VALUES (42, 'ad_index', 'INDEX_DJSC_YX_URL', 'http://qsdj.io/index.php/index/index.html');
INSERT INTO `dj_system` VALUES (43, 'ad_index', 'INDEX_DJSC_YX_IMAGE', '21');
INSERT INTO `dj_system` VALUES (44, 'ad_index', 'INDEX_ZDJS_DB_URL', 'http://qsdj.io/index.php/index/index.html');
INSERT INTO `dj_system` VALUES (45, 'ad_index', 'INDEX_ZDJS_DB_IMAGE', '30');
INSERT INTO `dj_system` VALUES (46, 'ad_yxsp', 'YXSP_DYG_URL', 'https://www.yunqishi.net/upload/mp4/202007/7-30.mp4');
INSERT INTO `dj_system` VALUES (47, 'ad_djws', 'DJWS_DYG_URL', 'http://qsdj.io/index.php/game_fun/index.html');
INSERT INTO `dj_system` VALUES (48, 'ad_djws', 'DJWS_DYG_IMAGE', '31');

SET FOREIGN_KEY_CHECKS = 1;
