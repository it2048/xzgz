
--
-- 表的结构 `xz_tips`
--

CREATE TABLE IF NOT EXISTS `xz_tips` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(128) NOT NULL COMMENT '标题',
  `tag` varchar(64) DEFAULT NULL COMMENT '标签',
  `stime` int(11) NOT NULL COMMENT '开始时间',
  `endtime` int(11) NOT NULL COMMENT '结束时间',
  `img` varchar(128) DEFAULT NULL COMMENT '图片',
  `zone_list` varchar(256) NOT NULL COMMENT '区',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1,2小贴士，攻略',
  `content` text NOT NULL COMMENT '内容',
  `user` varchar(64) NOT NULL COMMENT '添加人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='景区小贴士' AUTO_INCREMENT=1 ;