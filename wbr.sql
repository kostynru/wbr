/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50614
Source Host           : localhost:3306
Source Database       : wbr

Target Server Type    : MYSQL
Target Server Version : 50614
File Encoding         : 65001

Date: 2014-08-21 21:56:30
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `friends`
-- ----------------------------
DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `uid` int(20) NOT NULL,
  `fid` int(20) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of friends
-- ----------------------------
INSERT INTO `friends` VALUES ('1', '3', '1');
INSERT INTO `friends` VALUES ('1', '2', '0');
INSERT INTO `friends` VALUES ('1', '18', '1');

-- ----------------------------
-- Table structure for `ims`
-- ----------------------------
DROP TABLE IF EXISTS `ims`;
CREATE TABLE `ims` (
  `mid` int(60) NOT NULL AUTO_INCREMENT,
  `text` varchar(500) NOT NULL,
  `sid` int(20) NOT NULL,
  `uid` int(20) NOT NULL,
  `time` int(15) NOT NULL,
  `checked` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims
-- ----------------------------
INSERT INTO `ims` VALUES ('5', 'Hello, dude', '3', '1', '1404050244', '1');
INSERT INTO `ims` VALUES ('6', 'Hi, man', '3', '2', '1403758635', '1');
INSERT INTO `ims` VALUES ('8', 'Yoooo, dude', '2', '3', '1404060000', '1');
INSERT INTO `ims` VALUES ('9', 'Hi, mate!', '1', '3', '1404357628', '1');
INSERT INTO `ims` VALUES ('11', 'Hello, my darling friend', '1', '3', '1404359487', '1');
INSERT INTO `ims` VALUES ('38', 'fefawfjnakfan', '2', '1', '0', '1');
INSERT INTO `ims` VALUES ('39', 'Hello, guy!', '1', '2', '1406703497', '1');
INSERT INTO `ims` VALUES ('41', 'ezszvzsv', '2', '1', '1406703497', '1');
INSERT INTO `ims` VALUES ('42', 'segsegsg', '2', '1', '1406703498', '1');
INSERT INTO `ims` VALUES ('43', 'gksenskjvnse', '2', '1', '1406703499', '1');
INSERT INTO `ims` VALUES ('44', 'fsfesfgs', '2', '1', '1406727967', '1');
INSERT INTO `ims` VALUES ('45', 'Yo, man!', '1', '3', '1407065117', '1');

-- ----------------------------
-- Table structure for `online`
-- ----------------------------
DROP TABLE IF EXISTS `online`;
CREATE TABLE `online` (
  `id` int(10) NOT NULL,
  `time` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of online
-- ----------------------------
INSERT INTO `online` VALUES ('0', '1407262838');
INSERT INTO `online` VALUES ('1', '1408632616');
INSERT INTO `online` VALUES ('2', '1407330696');
INSERT INTO `online` VALUES ('3', '1407330696');
INSERT INTO `online` VALUES ('19', '1407396427');

-- ----------------------------
-- Table structure for `profiles`
-- ----------------------------
DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `second_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `about` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of profiles
-- ----------------------------
INSERT INTO `profiles` VALUES ('1', 'admin', 'd5eb0736dc752a8c35014fd572f3659f', 'Kostya', 'Shelko', 'kostynru1997@gmail.com', 'His highnest - admin', 'Europe/Moscow', '1');
INSERT INTO `profiles` VALUES ('2', 'fake_user', '81dc9bdb52d04dc20036dbd8313ed055', 'Michael', 'Stenley', 'weber@automattic.com', 'Fake user', 'Europe/Moscow', '1');
INSERT INTO `profiles` VALUES ('3', 'parara', '81dc9bdb52d04dc20036dbd8313ed055', 'Kolins', 'Marvin', 't@toni.org', 'Fake user too', 'Europe/Moscow', '1');
INSERT INTO `profiles` VALUES ('4', '6415f50843c9dcee599c9aa54daa0383', 'c55b58bc0cdd0d3288f37da821ff6c1f', 'Kostya', 'Shelkoun', 'kostynru@jijal.com', null, 'Asia/Novosibirsk', '0');
INSERT INTO `profiles` VALUES ('7', '9774804db6cbe00d036311caa374c111', 'b957dc3f850436863ef1924219f9f0b9', 'Kostya', 'Shelkon', 'kojfa@yma.com', null, 'Asia/Novosibirsk', '0');
INSERT INTO `profiles` VALUES ('18', 'b37b6500acdeed3bd486f363f009724a', '984dc389320ef42eee828885ae8ed2d5', 'Kostya', 'Shelkotyan', '123g@gsb.com', null, 'Europe/London', '0');
INSERT INTO `profiles` VALUES ('19', '92ce91dab65f1b61b3c20b6eb5b95ac9', 'c55b58bc0cdd0d3288f37da821ff6c1f', 'Kostya', 'Shelkonyanidze', 'kostynru@ymail.com', null, 'Europe/Moscow', '0');

-- ----------------------------
-- Table structure for `register_hash`
-- ----------------------------
DROP TABLE IF EXISTS `register_hash`;
CREATE TABLE `register_hash` (
  `id` int(30) NOT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of register_hash
-- ----------------------------

-- ----------------------------
-- Table structure for `sessions`
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(6) NOT NULL,
  `expire` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('32402978426d25c7f870d456b8e4cb66', '1', '1411217525');

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `uid` int(6) NOT NULL DEFAULT '0',
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wall_everybody` int(1) DEFAULT '1',
  `messages_everybody` int(1) DEFAULT '1',
  `wall_enabled` int(1) DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('1', '9a79c90db4729b6a63ffcbd93b16374d', '0', '1', '0');
INSERT INTO `settings` VALUES ('2', '9a79c90db4729b6a63ffcbd93b16374d', '0', '1', '1');

-- ----------------------------
-- Table structure for `userdata`
-- ----------------------------
DROP TABLE IF EXISTS `userdata`;
CREATE TABLE `userdata` (
  `uid` int(20) NOT NULL,
  `birth` int(30) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `gender` int(1) NOT NULL,
  `skype` varchar(60) DEFAULT NULL,
  `twitter` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userdata
-- ----------------------------
INSERT INTO `userdata` VALUES ('0', '857163600', null, '0', null, null);
INSERT INTO `userdata` VALUES ('1', '854571600', 'Omsk', '1', 'kostynru', 'supremepowerme');
INSERT INTO `userdata` VALUES ('2', '854582400', 'San Farancisco', '0', null, null);
INSERT INTO `userdata` VALUES ('3', '854582400', 'San Paulo', '1', null, null);
INSERT INTO `userdata` VALUES ('7', '854571600', null, '0', null, null);
INSERT INTO `userdata` VALUES ('18', '857163600', 'Omsk', '0', null, null);
INSERT INTO `userdata` VALUES ('19', '857163600', 'Moscow', '0', null, null);

-- ----------------------------
-- Table structure for `wall`
-- ----------------------------
DROP TABLE IF EXISTS `wall`;
CREATE TABLE `wall` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uid` int(20) NOT NULL,
  `content` varchar(500) NOT NULL,
  `time` int(32) NOT NULL,
  `img` varchar(50) DEFAULT '',
  `likes` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wall
-- ----------------------------
INSERT INTO `wall` VALUES ('4', '1', 'SG0sIG5pY2Ugd29yaw==', '1404197344', '', '0');
INSERT INTO `wall` VALUES ('9', '3', 'SG0sIG5pY2Ugd29yaw==', '1404197344', null, '1');
INSERT INTO `wall` VALUES ('10', '1', 'TmljZSB3b3JrLCBtYXRlcw==', '1404314396', null, '0');
INSERT INTO `wall` VALUES ('11', '1', 'V2VsY29tZSE=', '1405237415', null, '0');
INSERT INTO `wall` VALUES ('12', '1', 'UHJlcGFyZSB5b3JzZWxmIF5e', '1405237433', null, '0');
INSERT INTO `wall` VALUES ('13', '1', 'TmljZSBXb3JrLCBtYXRl', '1405237458', null, '0');
INSERT INTO `wall` VALUES ('15', '1', 'JUQwJTlGJUQxJTgwJUQwJUI4JUQwJUIyJUQwJUI1JUQxJTgyJTIwJTVFJTVF', '1405238084', null, '0');
INSERT INTO `wall` VALUES ('16', '1', 'SG9sYSE=', '1405238407', null, '0');
INSERT INTO `wall` VALUES ('17', '1', 'SG9sYSE=', '1405238444', null, '0');
INSERT INTO `wall` VALUES ('18', '1', 'SG9sYSEh', '1405238534', null, '0');
INSERT INTO `wall` VALUES ('19', '1', 'UXVlJTIwdGFsJTNG', '1405238683', null, '0');
INSERT INTO `wall` VALUES ('20', '1', 'SVQlMjBXT1JLUyEh', '1405238728', null, '0');
INSERT INTO `wall` VALUES ('21', '1', 'SGlp', '1405246448', null, '0');
INSERT INTO `wall` VALUES ('22', '1', 'SGVsbG8lMkMlMjBteSUyMGZyaWVuZA==', '1405246536', null, '0');
INSERT INTO `wall` VALUES ('23', '1', 'SGV5JTIwaGV5', '1407065674', null, '0');
INSERT INTO `wall` VALUES ('24', '1', 'MTIz', '1407246230', null, '0');
INSERT INTO `wall` VALUES ('25', '1', 'aGVsbG8=', '1408025616', '', '0');
INSERT INTO `wall` VALUES ('26', '1', 'aGVsbG8=', '1408025860', '46520fd270b082d1bfda25db82b087a3.jpg', '0');
INSERT INTO `wall` VALUES ('28', '1', 'WW8=', '1408030144', '', '1');
INSERT INTO `wall` VALUES ('29', '2', 'TmlnZ2E=', '1408030151', '', '1');

-- ----------------------------
-- Table structure for `wall_attch`
-- ----------------------------
DROP TABLE IF EXISTS `wall_attch`;
CREATE TABLE `wall_attch` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `img_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`,`img_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wall_attch
-- ----------------------------
INSERT INTO `wall_attch` VALUES ('1', 'be5a106d20d0c6c2bb81156873705e3b.jpg', '0');

-- ----------------------------
-- Table structure for `wall_likes`
-- ----------------------------
DROP TABLE IF EXISTS `wall_likes`;
CREATE TABLE `wall_likes` (
  `pid` int(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wall_likes
-- ----------------------------
INSERT INTO `wall_likes` VALUES ('9', '1');
INSERT INTO `wall_likes` VALUES ('29', '1');
INSERT INTO `wall_likes` VALUES ('28', '1');

-- ----------------------------
-- Table structure for `wall_preferences`
-- ----------------------------
DROP TABLE IF EXISTS `wall_preferences`;
CREATE TABLE `wall_preferences` (
  `uid` int(10) NOT NULL,
  `enabled` int(1) DEFAULT '1',
  `show_not_friends` int(1) DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wall_preferences
-- ----------------------------
INSERT INTO `wall_preferences` VALUES ('1', '1', '1');
INSERT INTO `wall_preferences` VALUES ('2', '1', '1');
INSERT INTO `wall_preferences` VALUES ('3', '1', '1');
