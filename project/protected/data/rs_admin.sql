
--
-- 表的结构 `rs_admin`
--

CREATE TABLE IF NOT EXISTS `rs_admin` (
  `username` varchar(24) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `tel` varchar(16) DEFAULT NULL COMMENT '电话',
  `email` varchar(32) DEFAULT NULL COMMENT 'email',
  PRIMARY KEY (`username`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `rs_admin` (`username`, `password`, `tel`, `email`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '18228041350', 'jsjscool@163.com');