#!/usr/bin/php
<?php
/*
 * 1. 部署脚本：
 *  离线文件监控：rdb -c memory ~/Desktop/dump02.rdb > redis2.csv &&  php命令 代码路径/redis_script/create_csv.php -f  redis2.csv -s 1
 *   && php命令 代码路径/redis_script/csv/rdb2csv.csv
 *
 * 3. 帮助信息
 * -h help  (显示帮助信息)
 * -f file model (离线读取nginx日志时有效)
 * -s server id  (服务器id)
 */
include __DIR__ . 'func.inc.php';
$opts = getopt('hf:s:');
if ((!isset($opts['f'])) || isset($opts['h'])) {
	echo "
         * -h help  (显示帮助信息)
         * -f file model (离线读取redis日志文件)
         * -s server id  (服务器id)
         ** eg: php create_csv.php -l path/to/dump.txt -s 1 -f
		\n";
	exit;
}
$server_id = 1; //当前的server编号,会记录到mysql中
if (isset($opts['s'])) {
	$server_id = intval($opts['s']);
}

if (isset($opts['f'])) {
	$file = fopen($opts['f'], "r");
	$fname = pathinfo("/testweb/test.txt",PATHINFO_BASENAME); //f模式的指定日期的csv文件名
	$file_path = __DIR__ . '/csv/'; //csv文件目录
	$data_file = fopen($file_path . $fname, 'w+');
	while (!feof($file)) {
		$content = trim(fgets($file));
		if (substr($content, 0, 8) == 'database' || $content == '') {
			continue;
		}
		$msg = parse_content($content, $server_id);
		if ($msg) {
		    fputs($data_file, $msg . "\n");
		} else {
			continue;
		}
	}
	fclose($file);
}

