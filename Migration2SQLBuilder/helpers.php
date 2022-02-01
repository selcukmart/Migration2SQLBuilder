<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 14:51
 */

function _sizeof_migration2sqlbuilder($data): int
{
    if ((PHP_VERSION_ID > 70300) && is_countable($data)) {
        return count($data);
    }
    return is_array($data) ? count($data) : false;
}

function c_migration2sqlbuilder($v, $return = false)
{
    if ($return) {
        $output = '<pre>';
    } else {
        echo '<pre>';
    }
    if (is_array($v) || is_object($v)) {
        if ($return) {
            $output .= print_r($v, true);
        } else {
            print_r($v);
        }
    } elseif ($return) {
        $output .= $v;
    } elseif (is_bool($v)) {
        var_dump($v);
    } else {
        echo $v;
    }
    if ($return) {
        $output .= '</pre>';
        return $output;
    }

    echo '</pre>';
}