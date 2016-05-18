<?php
include_once __DIR__ . '/func.inc.php';
$opts = getopt('f:d:s:');

$file_path = __DIR__ . '/csv/'; //csv文件目录
$data_file = fopen($file_path . $opts['f'], 'w+');
if (!isset($opts['f'])) {
    exit('lack of parameter of the -f');
}
$server_id = isset($opts['s']) ? intval($opts['s']) : 1;
$delimiter = isset($opts['d']) ? $opts['d'] : '#';
while (1) {
	$line = trim(fgets(STDIN));
	if (substr($line, 0, 8) == 'database' || $line == '') {
		continue;
	}
	$msg = parse_content($line, $server_id, $delimiter);
	if ($msg) {
	    fputs($data_file, $msg . "\n");
	} else {
		continue;
	}
}