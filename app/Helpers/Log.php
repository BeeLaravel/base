<?php
// ## Log 日志

// 页面输出
function log_page($data) {
    if ( is_array($data) || is_object($data) ) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo $data;
    }
}
// 文件输出
function log_file($data, $desc="", $file="") {
    // if ( empty($file) ) $file = date('YmdH') . '.log';
    if ( empty($file) ) $file = 'beesoft.log';
    $file = storage_path('/logs/' . $file);

    if ( is_array($data) || is_object($data) ) {
        error_log($desc . (!empty($desc) ? ":" : "") . var_export($data, 1) . "\n", 3, $file);
    } else {
        error_log($desc . (!empty($desc) ? ":" : "") . $data . "\n", 3, $file);
    }
}