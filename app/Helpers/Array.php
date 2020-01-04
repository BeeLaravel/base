<?php
// ## Array
// 层次数组 => 将一维数组按照父子关系转换为多维数组
function level_array($data, $parent_id=0, $children='children', $parent_id_field='parent_id', $id_field='id') {
    $return_data = [];

    foreach ( $data as $key => $value ) {
        if ( $value->$parent_id_field==$parent_id ) {
            $value->$children = level_array($data, $value->$id_field, $children, $parent_id_field, $id_field);
            // $value[$children] = level_array($data, $value[$id_field], $children, $parent_id_field, $id_field);
            $return_data[] = $value;
        }
    }

    return $return_data;
}
// 扁平数组 => 将多维数组转换为一维数组
function plain_array($data, $level=0, $char="\t", $field='title', $children='children', $id_field='id') {
    $return_data = [];

    foreach ( $data as $key => $value ) {
        $return_data[$value[$id_field]] = str_repeat($char, $level).$value[$field];
        $return_data += plain_array($value[$children], $level+1, $char, $field, $children, $id_field);
    }

    return $return_data;
}
function json_implode($data, $separator=",") {
    return implode($separator, json_decode($data, true));
}