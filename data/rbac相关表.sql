/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-01-13 18:46:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cp_rbac_assigment
-- ----------------------------
DROP TABLE IF EXISTS `cp_rbac_assigment`;
CREATE TABLE `cp_rbac_assigment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL COMMENT '权限或者角色id',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='权限分配表';

-- ----------------------------
-- Records of cp_rbac_assigment
-- ----------------------------
INSERT INTO `cp_rbac_assigment` VALUES ('37', '46', '21', '21', '1452669556');
INSERT INTO `cp_rbac_assigment` VALUES ('38', '46', '22', '22', '1452669556');
INSERT INTO `cp_rbac_assigment` VALUES ('39', '47', '22', '22', '1452669566');
INSERT INTO `cp_rbac_assigment` VALUES ('51', '0', '21', '7', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('52', '0', '21', '8', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('53', '0', '21', '9', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('54', '0', '21', '10', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('55', '0', '21', '11', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('56', '0', '21', '12', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('57', '0', '21', '13', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('58', '0', '21', '14', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('59', '0', '21', '15', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('60', '0', '21', '16', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('61', '0', '21', '17', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('62', '0', '21', '18', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('63', '0', '21', '19', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('64', '0', '21', '23', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('65', '0', '21', '25', '1452673132');
INSERT INTO `cp_rbac_assigment` VALUES ('66', '0', '22', '7', '1452673137');
INSERT INTO `cp_rbac_assigment` VALUES ('67', '0', '22', '12', '1452673137');
INSERT INTO `cp_rbac_assigment` VALUES ('68', '0', '22', '14', '1452673137');
INSERT INTO `cp_rbac_assigment` VALUES ('69', '0', '22', '15', '1452673137');
INSERT INTO `cp_rbac_assigment` VALUES ('70', '0', '22', '16', '1452673137');
INSERT INTO `cp_rbac_assigment` VALUES ('71', '0', '22', '17', '1452673137');
INSERT INTO `cp_rbac_assigment` VALUES ('72', '0', '22', '25', '1452673137');

-- ----------------------------
-- Table structure for cp_rbac_item
-- ----------------------------
DROP TABLE IF EXISTS `cp_rbac_item`;
CREATE TABLE `cp_rbac_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1/权限 2/角色 3/权限父节点',
  `title` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '在菜单上显示1/true 2/false',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='节点表';

-- ----------------------------
-- Records of cp_rbac_item
-- ----------------------------
INSERT INTO `cp_rbac_item` VALUES ('6', '0', '3', '后台权限管理', '后台权限管理', '0', '1452665739');
INSERT INTO `cp_rbac_item` VALUES ('7', '6', '1', '/admin/rbacmanage/index', '权限分配', '1', '1452665981');
INSERT INTO `cp_rbac_item` VALUES ('8', '6', '1', '/admin/rbacmanage/adduser', '添加用户', '2', '1452666001');
INSERT INTO `cp_rbac_item` VALUES ('9', '6', '1', '/admin/rbacmanage/updateuser', '更新用户', '2', '1452666027');
INSERT INTO `cp_rbac_item` VALUES ('10', '6', '1', '/admin/rbacmanage/deluser', '删除用户', '2', '1452666047');
INSERT INTO `cp_rbac_item` VALUES ('11', '6', '1', '/admin/rbacmanage/userassignrole', '用户绑定角色', '2', '1452666064');
INSERT INTO `cp_rbac_item` VALUES ('12', '6', '1', '/admin/rbacmanage/getuserrole', '用户获取角色', '2', '1452666081');
INSERT INTO `cp_rbac_item` VALUES ('13', '6', '1', '/admin/rbacmanage/roleassignpermission', '角色分配权限', '2', '1452666105');
INSERT INTO `cp_rbac_item` VALUES ('14', '6', '1', '/admin/rbacmanage/rolegetpermissions', '获取角色权限列表', '2', '1452666137');
INSERT INTO `cp_rbac_item` VALUES ('15', '6', '1', '/admin/rbacmanage/itemList', '角色/权限列表', '1', '1452666196');
INSERT INTO `cp_rbac_item` VALUES ('16', '6', '1', '/admin/rbacmanage/userList', '用户列表', '1', '1452666211');
INSERT INTO `cp_rbac_item` VALUES ('17', '6', '1', '/admin/rbacmanage/getItemParentNodes', '获取父节点列表', '2', '1452666233');
INSERT INTO `cp_rbac_item` VALUES ('18', '6', '1', '/admin/rbacmanage/addItem', '添加节点', '2', '1452666245');
INSERT INTO `cp_rbac_item` VALUES ('19', '6', '1', '/admin/rbacmanage/delItem', '删除节点', '2', '1452666278');
INSERT INTO `cp_rbac_item` VALUES ('21', '0', '2', '管理员', '后台管理员，可以分配权限查看日志', '0', '1452666327');
INSERT INTO `cp_rbac_item` VALUES ('22', '0', '2', '访客', '只能查看不能删除操作', '0', '1452666350');
INSERT INTO `cp_rbac_item` VALUES ('23', '6', '1', '/admin/rbacManage/updateItem', '更新节点', '2', '1452667736');
INSERT INTO `cp_rbac_item` VALUES ('24', '0', '3', '后台首页', '后台首页', '0', '1452673080');
INSERT INTO `cp_rbac_item` VALUES ('25', '24', '1', '/admin/index/index', '首页', '2', '1452673119');

-- ----------------------------
-- Table structure for cp_user
-- ----------------------------
DROP TABLE IF EXISTS `cp_user`;
CREATE TABLE `cp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ctime` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of cp_user
-- ----------------------------
INSERT INTO `cp_user` VALUES ('46', 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'admin@qq.com', '283a6c9c2c213c8791f6cf78debe32e6', '1452580176', '0');
INSERT INTO `cp_user` VALUES ('47', 'guest', '827ccb0eea8a706c4c34a16891f84e7b', 'guest@qq.com', '44aa6f37c6e264540a52b6dcd8f3287e', '1452668393', '0');
