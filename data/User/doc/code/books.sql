-- Adminer 3.7.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+08:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `book_node`;
CREATE TABLE `book_node` (
  `id` int(15) NOT NULL COMMENT '图书id',
  `pid` int(15) NOT NULL COMMENT '所属分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书信息表';


DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `flag` int(1) DEFAULT '1' COMMENT '是否删除标记',
  `book_name` char(200) NOT NULL COMMENT '名称',
  `book_auther` char(200) NOT NULL COMMENT '作者',
  `book_cover` char(200) NOT NULL COMMENT '缩略图',
  `book_intro` varchar(1000) NOT NULL COMMENT '内容简介',
  `write_time` int(15) NOT NULL COMMENT '写作时间',
  `crate_time` int(15) NOT NULL COMMENT '添加时间',
  `file_size` int(15) DEFAULT '0' COMMENT '文件大小',
  `file_url` char(200) NOT NULL COMMENT '文件地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书信息表';

INSERT INTO `books` (`id`, `flag`, `book_name`, `book_auther`, `book_cover`, `book_intro`, `write_time`, `crate_time`, `file_size`, `file_url`) VALUES
(65,	0,	'送你一把远飞的伞',	'王慧艳',	'http://img3.douban.com/mpic/s7637544.jpg',	'不能对数组名进行直接复制与比较。示例2中，若想把数组a的内容复制给数组b，不能用语句 b = a ，否则将产生编译错误。应该用标准库函数strcpy进行复制。同理，比较b和a的内容是否相同，不能',	3,	2,	2,	'static/upload/2013-07/591b0.epub'),
(73,	1,	'远行的鸟群小说集',	'淘米网络',	'http://img3.douban.com/lpic/s26320285.jpg',	'宇宙大冒险圣者逆袭1瑞尔斯的考验',	0,	1374133822,	0,	'static/upload/2013-08/268f9.epub'),
(67,	1,	'多点银行',	'老臣',	'http://img3.douban.com/mpic/s2731621.jpg',	'不能对数组名进行直接复制与比较。示例2中，若想把数组a的内容复制给数组b，不能用语句 b = a ，否则将产生编译错误。应该用标准库函数strcpy进行复制。同理，比较b和a的内容是否相同，不能',	3,	2,	2,	'static/upload/2013-08/7bf23.epub'),
(68,	1,	'第一部：葵花公主和黑寡妇',	'老臣',	'http://img3.douban.com/mpic/s7028310.jpg',	'不能对数组名进行直接复制与比较。示例2中，若想把数组a的内容复制给数组b，不能用语句 b = a ，否则将产生编译错误。应该用标准库函数strcpy进行复制。同理，比较b和a的内容是否相同，不能',	3,	2,	2,	'static/upload/2013-08/5806c.epub'),
(69,	1,	'第二部：葵花公主与草原白狼（第六稿）',	'王慧艳',	'http://img3.douban.com/mpic/s8470520.jpg',	'不能对数组名进行直接复制与比较。示例2中，若想把数组a的内容复制给数组b，不能用语句 b = a ，否则将产生编译错误。应该用标准库函数strcpy进行复制。同理，比较b和a的内容是否相同，不能',	3,	2,	2,	'static/upload/2013-08/7a3c9.epub'),
(70,	0,	'宠物前前',	'老臣',	'http://img3.douban.com/mpic/s7028311.jpg',	'不能对数组名进行直接复制与比较。示例2中，若想把数组a的内容复制给数组b，不能用语句 b = a ，否则将产生编译错误。应该用标准库函数strcpy进行复制。同理，比较b和a的内容是否相同，不能',	3,	2,	2,	'static/upload/2013-08/805c6.epub'),
(71,	1,	'插嘴大王',	'王慧艳',	'http://img3.douban.com/mpic/s8470527.jpg',	'不能对数组名进行直接复制与比较。示例2中，若想把数组a的内容复制给数组b，不能用语句 b = a ，否则将产生编译错误。应该用标准库函数strcpy进行复制。同理，比较b和a的内容是否相同，不能',	3,	2,	2,	'static/upload/2013-08/ec856.epub');

DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pid` int(15) DEFAULT '0' COMMENT '父结点id',
  `name` char(100) NOT NULL COMMENT '结点名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='节点表';


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL COMMENT '用户名',
  `password` char(50) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `user` (`id`, `name`, `password`) VALUES
(1,	'admin',	'21232f297a57a5a743894a0e4a801fc3');

-- 2013-08-01 17:22:06
