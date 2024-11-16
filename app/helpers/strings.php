<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function input_clean($string, $max_characters = null) {
    $wrapper_function = $max_characters ? function($string) use ($max_characters) { return mb_substr($string, 0, $max_characters); } : fn($string) => $string;
    return $wrapper_function(trim(strip_tags(filter_var_filter_string_polyfill($string ?? ''))));
}

function query_clean($string, $max_characters = null) {
    return mysql_escape_stringg(input_clean($string, $max_characters));
}

function array_query_clean($array) {
    return array_map('query_clean', $array);
}

function mysql_escape_stringg($unescaped_string) {
    $replacementMap = [
        "\0" => "\\0",
        "\n" => "\\n",
        "\r" => "\\r",
        "\t" => "\\t",
        chr(26) => "\\Z",
        chr(8) => "\\b",
        '"' => '\"',
        "'" => "\'",
        '\\' => '\\\\'
    ];

    return \strtr($unescaped_string, $replacementMap);
}

function filter_var_filter_string_polyfill($string) {
    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
}

function string_truncate($string, $maxchar, $ending = '..') {
    $length = mb_strlen($string);
    if($length > $maxchar) {
        $cutsize = -($length-$maxchar);
        $string  = mb_substr($string, 0, $cutsize);
        $string  = $string . $ending;
    }
    return $string;
}

function string_filter_alphanumeric($string) {

    $string = preg_replace('/[^a-zA-Z0-9\s]+/', '', $string);

    $string = preg_replace('/\s+/', ' ', $string);

    return $string;
}

function string_generate($length) {
    $characters = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
    $content = '';

    for($i = 1; $i <= $length; $i++) {
        $content .= $characters[array_rand($characters, 1)];
    }

    return $content;
}

function string_starts_with($needle, $haystack) {
    return mb_substr($haystack, 0, mb_strlen($needle)) === $needle;
}

function string_ends_with($needle, $haystack) {
    return mb_substr($haystack, -mb_strlen($needle)) === $needle;
}

function string_estimate_reading_time($string) {
    $total_words = str_word_count(strip_tags($string));

    /* 200 is the total amount of words read per minute */
    $minutes = floor($total_words / 200);
    $seconds = floor($total_words / 200 / (200 / 60));

    return (object) [
        'minutes' => $minutes,
        'seconds' => $seconds
    ];
}

function process_spintax($string) {
    return preg_replace_callback('/\{[^{}]*\|[^{}]*\}/', function ($match) {
        $content = substr($match[0], 1, -1);
        $words = explode('|', $content);
        return $words[array_rand($words)];
    }, $string);
}
