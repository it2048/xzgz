
--
-- 表的结构 `xz_scenic`
--

CREATE TABLE IF NOT EXISTS `xz_scenic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(128) NOT NULL COMMENT '标题',
  `desc` text COMMENT '概要描述',
  `content` text NOT NULL COMMENT '文档内容',
  `top` int(11) NOT NULL COMMENT '置顶',
  `mp3` varchar(128) DEFAULT NULL COMMENT '语言',
  `atime` int(11) NOT NULL COMMENT '添加时间',
  `ptime` int(11) DEFAULT NULL COMMENT '游玩时间',
  `img` text COMMENT '图片多张',
  `add` varchar(128) DEFAULT NULL COMMENT '地址信息',
  `x` varchar(32) DEFAULT NULL COMMENT 'x坐标',
  `y` varchar(32) DEFAULT NULL COMMENT 'y坐标',
  `zone` varchar(32) NOT NULL COMMENT '哪个县',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='旅游地点表' AUTO_INCREMENT=1 ;