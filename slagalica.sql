/*
Navicat MySQL Data Transfer

Source Server         : ubuntu server 2
Source Server Version : 50717
Source Host           : 192.168.254.2:3306
Source Database       : slagalica

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-10-24 23:34:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `games`
-- ----------------------------
DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `users_id1` int(11) DEFAULT NULL,
  `users_id2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of games
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(13) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `msisdn` (`msisdn`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '381631109252');
INSERT INTO `users` VALUES ('2', '381631109253');
INSERT INTO `users` VALUES ('5', '381631109254');
INSERT INTO `users` VALUES ('8', '381631109255');
INSERT INTO `users` VALUES ('14', '381631109256');
INSERT INTO `users` VALUES ('18', '381631109258');
INSERT INTO `users` VALUES ('44', '381631109259');
