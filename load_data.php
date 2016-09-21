<?php
/**
 * Created by PhpStorm.
 * User: Sorci
 * Date: 15/9/7
 * Time: 上午11:22
 */
$opts = getopt('hf:d:'); //f文件目录，d待导入日志的日期
if (!isset($opts['f']) || isset($opts['h'])) {
	echo "
         * -h help  (显示帮助信息)
         * -f file model (离线读取nginx日志文件)
         * -d file date  (log文件日期)
         ** eg: php load_data.php -f path/to/file.csv -d 20150817
		\n";
	exit;
}
require_once __DIR__ . '/func.inc.php';
$config = require_once __DIR__ . '/config.php';
$mysql_table = 'juzi_redis_' . date('Ymd');
if (isset($opts['d'])) {
	$mysql_table = 'juzi_redis_' . date('Ymd', strtotime($opts['d']));
}
$file_path = __DIR__ . '/csv/'; //csv文件目录
$file_name = $file_path . $opts['f']; //csv文件名
$conn = mysql_connect($config['DB_HOST'] . ':' . $config['DB_PORT'], $config['DB_USER'], $config['DB_PWD']);
if ($conn) {
	//选择数据库
	mysql_select_db($config['DB_NAME'], $conn);
	//是否存在表
	$exists_table_sql = "create table if not exists `$mysql_table` like `juzi_redis`";
	mysql_query($exists_table_sql);
	//导入数据
	$sql = "LOAD DATA LOCAL INFILE '" . $file_name . "' INTO TABLE `" . $mysql_table . "` CHARACTER SET utf8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' (`db`,`type`,`key`,`key_prefix`,`key_suffix`,`size_in_bytes`,`encoding`,`num_elements`,`len_largest_element`,`server_id`)";
	if (mysql_query($sql)) {
		log_result("加载" . $file_name . "文件成功。");
	} else {
		log_result("加载" . $file_name . "失败了。");
		echo $sql;
	}
	mysql_close($conn);
} else {
	echo 'Mysql could not connection.' . "\n";
}
