<?php
/**
 * 写入日志文件
 */
function log_result($msg, $prefix = '') {
	$file_name = $prefix . date('Y-m-d') . '.log';
	error_log("执行日期：" . strftime("%Y-%m-%d %H:%M:%S", time()) . " " . $msg . "\n", 3, dirname(__file__) . '/logs/' . $file_name);
}

/**
 * cvs文件是否存在，不存在就创建，并写入字段名
 */
function check_mkdir_file($file_path, $file_name) {
	$file_path . $file_name;
	if (!file_exists($file_path . $file_name)) {
		mkdir($file_path . $file_name);
		$filed = 'ip,hour,minute,second,access_time,request_method,version,url,response_code,response_bytes,response_time,server_id' . "\n";
		error_log($filed, 3, $file_path . $file_name);
	}
}

function parse_content($content, $server_id = 1) {
    $content_arr = explode(',', $content);
    $key = trim($content_arr[2], '"');
    $key_arr = get_key($key);
    $new_arr['db'] = $content_arr[0];
    $new_arr['type'] = get_type($content_arr[1]);
    $new_arr['key'] = $key;
    $new_arr['key_prefix'] = $key_arr[0];
    $new_arr['key_suffix'] = $key_arr[1];
    $new_arr['size'] = $content_arr[3];
    $new_arr['encoding'] = get_encoding($content_arr[4]);
    $new_arr['elements'] = $content_arr[5];
    $new_arr['len_largest_element'] = $content_arr[6];
    $new_arr['server_id'] = $server_id;
    $str = join('","', array_values($new_arr));
    return '"' . $str . '"';
}

function get_encoding($content) {
    $str = '';
    switch ($content) {
        case 'raw':
            $str = '0';
            break;
        case 'int':
            $str = '1';
            break;
        case 'linkedlist':
            $str = '2';
            break;
        case 'hashtable':
            $str = '3';
            break;
        case 'intset':
            $str = '4';
            break;
        case 'skiplist':
            $str = '5';
            break;
        case 'ziplist':
            $str = '6';
            break;
        case 'string':
            $str = '7';
            break;
        default:
            $str = $content;
            break;
    }
    return $str;
}

function get_type($content) {
    $str = '';
    switch ($content) {
        case 'string':
            $str = '0';
            break;
        case 'hash':
            $str = '1';
            break;
        case 'list':
            $str = '2';
            break;
        case 'set':
            $str = '3';
            break;
        case 'sortedset':
            $str = '4';
            break;
        default:
            $str = $content;
            break;
    }
    return $str;
}

function get_key($content, $delimiter= '#') {
    return explode($delimiter, $content);
}

function test() {
    return 1;
}
