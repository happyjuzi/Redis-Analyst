# Redis-Analyst
Analyse the rdb file of redis, so show you the keys  statistics

功能描述：
    分析redis的rdb文件，将分析结果（键名，类型，空间大小，元素数量等）导入到mysql

依赖环境：
1. 安装rdb工具

2.  表结构
CREATE TABLE `juzi_redis` (
  `db` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `key` char(50) NOT NULL COMMENT 'lookup',
  `key_prefix` char(30) NOT NULL COMMENT 'lookup',
  `key_suffix` char(30) NOT NULL COMMENT 'lookup',
  `size_in_bytes` int(11) NOT NULL,
  `encoding` tinyint(1) NOT NULL,
  `num_elements` int(11) NOT NULL,
  `len_largest_element` int(11) NOT NULL,
  `server_id` tinyint(1) NOT NULL,
  KEY `prefix` (`key_prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

步骤：

1. rdb -c memory ~/Downloads/dump02.rdb.bak | php ~/html/Redis-Analyst/timing_csv.php -d "#" -f yuancj.csv

2. php ~/html/Redis-Analyst/load_data.php -f yuancj.csv -d 2016


