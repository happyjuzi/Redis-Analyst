# Redis-Analyst
Analyse the rdb file of redis, so show you the keys  statistics

##功能描述：
    分析redis的rdb文件，将分析结果（键名，类型，空间大小，元素数量等）导入到mysql,通过分析结果查找瓶颈问题。

##依赖环境：
1.    安装[redis-rdb-tools](https://github.com/happyjuzi/Redis-Analyst/wiki/tutorial)

2.  表结构
     
        CREATE TABLE `juzi_redis` (

        `db` tinyint(1) NOT NULL COMMENT 'database',
    
        `type` tinyint(1) NOT NULL COMMENT '0-string,1-hash,2-list,3-set,4-sortedset',
    
        `key` char(50) NOT NULL DEFAULT '' COMMENT '键名',
    
        `key_prefix` char(30) NOT NULL DEFAULT '' COMMENT '键名前缀，用于分组',
    
        `key_suffix` char(30) NOT NULL DEFAULT '' COMMENT '键名后缀',
    
        `size_in_bytes` int(11) NOT NULL COMMENT '空间大小，单位是byte',
    
        `encoding` tinyint(1) NOT NULL COMMENT '0-raw,1-int,2-linkedlist,3-hashtable,4-intset,5-skiplist,6-ziplist,7-string',
        `num_elements` int(11) NOT NULL COMMENT '元素数量',
    
        `len_largest_element` int(11) NOT NULL COMMENT '最大元素的长度',
    
        `server_id` tinyint(1) NOT NULL COMMENT '主机标识',
    
        KEY `prefix` (`key_prefix`)
    
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

##步骤：

1.   rdb -c memory ~/Downloads/dump02.rdb.bak | php ~/html/Redis-Analyst/timing_csv.php -d "#" -f yuancj.csv

2.   php ~/html/Redis-Analyst/load_data.php -f yuancj.csv -d 2016


