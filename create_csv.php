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
include __DIR__ . '/func.inc.php';
$opts = getopt('hf:s:d:i:');
if ((!isset($opts['f'])) || isset($opts['h'])) {
	echo "
         * -h help  (显示帮助信息)
         * -f output filename
         * -i input file path
         * -s server id  (服务器id)
         ** eg: php create_csv.php -i path/to/dump.txt -s 1 -f
		\n";
	exit;
}

if (!isset($opts['f'])) {
    exit("lack of parameter of '-f' ");
}

if (!isset($opts['i'])) {
    exit("lack of parameter of '-i' ");
} else {
    $source_file = $opts['i'];
    if (!file_exists($source_file)) {
        exit("can not found file ->  " . $source_file);
    }
}
//当前的server编号,会记录到mysql中
$server_id = isset($opts['s']) ? intval($opts['d']) : 1;
$delimiter = isset($opts['d']) ? $opts['d'] : '#';
$file = fopen($source_file, "r");
$file_path = __DIR__ . '/csv/'; //csv文件目录
$data_file = fopen($file_path . $opts['f'], 'w+');
while (!feof($file)) {
	$content = trim(fgets($file));
	if (substr($content, 0, 8) == 'database' || $content == '') {
		continue;
	}
	$msg = parse_content($content, $server_id, $delimiter);
	if ($msg) {
	    fputs($data_file, $msg . "\n");
	} else {
		continue;
	}
}
fclose($file);

