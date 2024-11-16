<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

/* Aws functions */
function get_aws_s3_config() {
    $aws_s3_config = [
        'region' => settings()->offload->region ?: 'us-east-1',
        'version' => 'latest',
        'credentials' => [
            'key' => settings()->offload->access_key,
            'secret' => settings()->offload->secret_access_key,
        ],
        'http' => [
            'defaultOptions' => [
                'verify' => true,
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
                ]
            ]
        ],
    ];

    if(settings()->offload->provider != 'aws-s3') {
        $aws_s3_config['endpoint'] = settings()->offload->endpoint_url;
    }

    return $aws_s3_config;
}

/* Generate chart data for based on the date key and each of keys inside */
function get_chart_data(Array $main_array) {

    $results = [];

    foreach($main_array as $date_label => $data) {

        foreach($data as $label_key => $label_value) {

            if(!isset($results[$label_key])) {
                $results[$label_key] = [];
            }

            $results[$label_key][] = $label_value;

        }

    }

    foreach($results as $key => $value) {
        $results[$key] = '["' . implode('", "', $value) . '"]';
    }

    $results['labels'] = '["' . implode('", "', array_keys($main_array)) . '"]';

    $results['is_empty'] = count($results) > 1 ? false : true;

    return $results;
}

function get_gravatar($email, $size = 80, $d = 'identicon', $rating = 'g') {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(mb_strtolower(trim($email ?? '')));
    $url .= "?s=$size&d=$d&r=$rating";

    return $url;
}

function get_favicon_url_from_domain($domain) {
    return sprintf('https://external-content.duckduckgo.com/ip3/%s.ico', $domain);
}

/* Helper to output proper and nice numbers */
function nr($number, $decimals = 0, $display_decimals_if_zero = true, $extra = false) {

    if($extra) {
        $formatted_number = $number;
        $touched = false;

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('B', $extra)))) {

            if($number > 999999999) {
                $formatted_number = number_format($number / 1000000000, $decimals, l('global.number.decimal_point'), l('global.number.thousands_separator')) . 'B';

                $touched = true;
            }

        }

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('M', $extra)))) {

            if($number > 999999) {
                $formatted_number = number_format($number / 1000000, $decimals, l('global.number.decimal_point'), l('global.number.thousands_separator')) . 'M';

                $touched = true;
            }

        }

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('K', $extra)))) {

            if($number > 999) {
                $formatted_number = number_format($number / 1000, $decimals, l('global.number.decimal_point'), l('global.number.thousands_separator')) . 'K';

                $touched = true;
            }

        }

        if($decimals > 0) {
            $dotzero = '.' . str_repeat('0', $decimals);
            $formatted_number = str_replace($dotzero, '', $formatted_number);
        }

        return $formatted_number;
    }

    if($number == 0) {
        return 0;
    }

    if(!$display_decimals_if_zero && $decimals > 0) {
        $decimals = floor($number) != $number ? $decimals : 0;
    }

    return number_format($number, $decimals, l('global.number.decimal_point'), l('global.number.thousands_separator'));
}

function get_ip() {
    if(isset($GLOBALS['ip'])) {
        return $GLOBALS['ip'];
    }

    if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {

        if(mb_strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            $GLOBALS['ip'] = trim(reset($ips));
        } else {
            $GLOBALS['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

    } else if(array_key_exists('REMOTE_ADDR', $_SERVER)) {
        $GLOBALS['ip'] = $_SERVER['REMOTE_ADDR'];
    } else if(array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        $GLOBALS['ip'] = $_SERVER['HTTP_CLIENT_IP'];
    }

    return $GLOBALS['ip'];
}

function get_device_type($user_agent) {
    $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
    $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';

    if(preg_match_all($mobile_regex, $user_agent)) {
        return 'mobile';
    } else {

        if(preg_match_all($tablet_regex, $user_agent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }

    }
}

function process_export_json($array_of_objects, $type = '', $type_array = [], $file_name = 'data') {

    if(isset($_GET['export']) && $_GET['export'] == 'json') {
        //ALTUMCODE:DEMO if(DEMO) exit('This command is blocked on the demo.');

        if(\Altum\Title::get()) $file_name = \Altum\Title::get();
        header('Content-Disposition: attachment; filename="' . $file_name . '.json";');
        header('Content-Type: application/json; charset=UTF-8');

        $json = json_exporter($array_of_objects, $type, $type_array);

        die($json);
    }

}

function json_exporter($array_of_objects, $type = 'basic', $type_array = []) {

    foreach($array_of_objects as $object) {

        foreach($object as $key => $value) {

            if(($type == 'exclude' && in_array($key, $type_array)) || ($type == 'include' && !in_array($key, $type_array))) {
                unset($object->{$key});
            }

        }

    }

    return json_encode($array_of_objects);
}

function process_export_csv($array, $type = '', $type_array = [], $file_name = 'data') {

    if(isset($_GET['export']) && $_GET['export'] == 'csv') {
        //ALTUMCODE:DEMO if(DEMO) exit('This command is blocked on the demo.');

        if(\Altum\Title::get()) $file_name = \Altum\Title::get();
        header('Content-Disposition: attachment; filename="' . $file_name . '.csv";');
        header('Content-Type: application/csv; charset=UTF-8');

        $csv = csv_exporter($array, $type, $type_array);

        die($csv);
    }

}

function csv_exporter($array, $type = 'basic', $type_array = []) {

    $result = '';

    /* Export the header */
    $headers = [];
    foreach(array_keys((array) reset($array)) as $value) {
        /* Check if not excluded */
        if(($type == 'exclude' && !in_array($value, $type_array)) || ($type == 'include' && in_array($value, $type_array)) || $type == 'basic') {
            $headers[] = '"' . $value . '"';
        }
    }

    $result .= implode(',', $headers);

    /* Data */
    foreach($array as $row) {
        $result .= "\n";

        $row_array = [];

        foreach($row as $key => $value) {
            /* Check if not excluded */
            if(($type == 'exclude' && !in_array($key, $type_array)) || ($type == 'include' && in_array($key, $type_array)) || $type == 'basic') {
                $row_array[] = '"' . addslashes($value ?? '') . '"';
            }
        }

        $result .= implode(',', $row_array);
    }

    return $result;
}

function csv_link_exporter($csv) {
    return 'data:application/csv;charset=utf-8,' . urlencode($csv);
}

function get_continents_array() {
    return [
        'AF' => '🌍 Africa',
        'AN' => '🧊 Antarctica',
        'AS' => '🌏 Asia',
        'EU' => '🌍 Europe',
        'NA' => '🌎 North America',
        'OC' => '🌊 Oceania',
        'SA' => '🌎 South America',
    ];
}

function get_continents_no_emoji_array() {
    return [
        'AF' => 'Africa',
        'AN' => 'Antarctica',
        'AS' => 'Asia',
        'EU' => 'Europe',
        'NA' => 'North America',
        'OC' => 'Oceania',
        'SA' => 'South America',
    ];
}

function get_continent_from_continent_code($code) {
    return get_continents_array()[mb_strtoupper($code ?? '')] ?? $code;
}

function get_countries_no_emoji_array() {
    return [
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BQ' => 'Bonaire, Sint Eustatius and Saba',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic of the Congo',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Curacao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea, Democratic People\'s Republic of',
        'KR' => 'Korea, Republic of',
        'XK' => 'Kosovo',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia, the Former Yugoslav Republic of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States of',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'CS' => 'Serbia and Montenegro',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SX' => 'Sint Maarten',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic of',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.s.',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    ];
}

function get_countries_array() {
    return [
        'AF' => '🇦🇫 Afghanistan',
        'AX' => '🇦🇽 Aland Islands',
        'AL' => '🇦🇱 Albania',
        'DZ' => '🇩🇿 Algeria',
        'AS' => '🇦🇸 American Samoa',
        'AD' => '🇦🇩 Andorra',
        'AO' => '🇦🇴 Angola',
        'AI' => '🇦🇮 Anguilla',
        'AQ' => '🇦🇶 Antarctica',
        'AG' => '🇦🇬 Antigua and Barbuda',
        'AR' => '🇦🇷 Argentina',
        'AM' => '🇦🇲 Armenia',
        'AW' => '🇦🇼 Aruba',
        'AU' => '🇦🇺 Australia',
        'AT' => '🇦🇹 Austria',
        'AZ' => '🇦🇿 Azerbaijan',
        'BS' => '🇧🇸 Bahamas',
        'BH' => '🇧🇭 Bahrain',
        'BD' => '🇧🇩 Bangladesh',
        'BB' => '🇧🇧 Barbados',
        'BY' => '🇧🇾 Belarus',
        'BE' => '🇧🇪 Belgium',
        'BZ' => '🇧🇿 Belize',
        'BJ' => '🇧🇯 Benin',
        'BM' => '🇧🇲 Bermuda',
        'BT' => '🇧🇹 Bhutan',
        'BO' => '🇧🇴 Bolivia',
        'BQ' => '🇧🇶 Bonaire, Sint Eustatius and Saba',
        'BA' => '🇧🇦 Bosnia and Herzegovina',
        'BW' => '🇧🇼 Botswana',
        'BV' => '🇧🇻 Bouvet Island',
        'BR' => '🇧🇷 Brazil',
        'IO' => '🇮🇴 British Indian Ocean Territory',
        'BN' => '🇧🇳 Brunei Darussalam',
        'BG' => '🇧🇬 Bulgaria',
        'BF' => '🇧🇫 Burkina Faso',
        'BI' => '🇧🇮 Burundi',
        'KH' => '🇰🇭 Cambodia',
        'CM' => '🇨🇲 Cameroon',
        'CA' => '🇨🇦 Canada',
        'CV' => '🇨🇻 Cape Verde',
        'KY' => '🇰🇾 Cayman Islands',
        'CF' => '🇨🇫 Central African Republic',
        'TD' => '🇹🇩 Chad',
        'CL' => '🇨🇱 Chile',
        'CN' => '🇨🇳 China',
        'CX' => '🇨🇽 Christmas Island',
        'CC' => '🇨🇨 Cocos (Keeling) Islands',
        'CO' => '🇨🇴 Colombia',
        'KM' => '🇰🇲 Comoros',
        'CG' => '🇨🇬 Congo',
        'CD' => '🇨🇩 Congo, Democratic Republic of the Congo',
        'CK' => '🇨🇰 Cook Islands',
        'CR' => '🇨🇷 Costa Rica',
        'CI' => '🇨🇮 Cote D\'Ivoire',
        'HR' => '🇭🇷 Croatia',
        'CU' => '🇨🇺 Cuba',
        'CW' => '🇨🇼 Curacao',
        'CY' => '🇨🇾 Cyprus',
        'CZ' => '🇨🇿 Czech Republic',
        'DK' => '🇩🇰 Denmark',
        'DJ' => '🇩🇯 Djibouti',
        'DM' => '🇩🇲 Dominica',
        'DO' => '🇩🇴 Dominican Republic',
        'EC' => '🇪🇨 Ecuador',
        'EG' => '🇪🇬 Egypt',
        'SV' => '🇸🇻 El Salvador',
        'GQ' => '🇬🇶 Equatorial Guinea',
        'ER' => '🇪🇷 Eritrea',
        'EE' => '🇪🇪 Estonia',
        'ET' => '🇪🇹 Ethiopia',
        'FK' => '🇫🇰 Falkland Islands (Malvinas)',
        'FO' => '🇫🇴 Faroe Islands',
        'FJ' => '🇫🇯 Fiji',
        'FI' => '🇫🇮 Finland',
        'FR' => '🇫🇷 France',
        'GF' => '🇬🇫 French Guiana',
        'PF' => '🇵🇫 French Polynesia',
        'TF' => '🇹🇫 French Southern Territories',
        'GA' => '🇬🇦 Gabon',
        'GM' => '🇬🇲 Gambia',
        'GE' => '🇬🇪 Georgia',
        'DE' => '🇩🇪 Germany',
        'GH' => '🇬🇭 Ghana',
        'GI' => '🇬🇮 Gibraltar',
        'GR' => '🇬🇷 Greece',
        'GL' => '🇬🇱 Greenland',
        'GD' => '🇬🇩 Grenada',
        'GP' => '🇬🇵 Guadeloupe',
        'GU' => '🇬🇺 Guam',
        'GT' => '🇬🇹 Guatemala',
        'GG' => '🇬🇬 Guernsey',
        'GN' => '🇬🇳 Guinea',
        'GW' => '🇬🇼 Guinea-Bissau',
        'GY' => '🇬🇾 Guyana',
        'HT' => '🇭🇹 Haiti',
        'HM' => '🇭🇲 Heard Island and Mcdonald Islands',
        'VA' => '🇻🇦 Holy See (Vatican City State)',
        'HN' => '🇭🇳 Honduras',
        'HK' => '🇭🇰 Hong Kong',
        'HU' => '🇭🇺 Hungary',
        'IS' => '🇮🇸 Iceland',
        'IN' => '🇮🇳 India',
        'ID' => '🇮🇩 Indonesia',
        'IR' => '🇮🇷 Iran, Islamic Republic of',
        'IQ' => '🇮🇶 Iraq',
        'IE' => '🇮🇪 Ireland',
        'IM' => '🇮🇲 Isle of Man',
        'IL' => '🇮🇱 Israel',
        'IT' => '🇮🇹 Italy',
        'JM' => '🇯🇲 Jamaica',
        'JP' => '🇯🇵 Japan',
        'JE' => '🇯🇪 Jersey',
        'JO' => '🇯🇴 Jordan',
        'KZ' => '🇰🇿 Kazakhstan',
        'KE' => '🇰🇪 Kenya',
        'KI' => '🇰🇮 Kiribati',
        'KP' => '🇰🇵 Korea, Democratic People\'s Republic of',
        'KR' => '🇰🇷 Korea, Republic of',
        'XK' => '🇽🇰 Kosovo',
        'KW' => '🇰🇼 Kuwait',
        'KG' => '🇰🇬 Kyrgyzstan',
        'LA' => '🇱🇦 Lao People\'s Democratic Republic',
        'LV' => '🇱🇻 Latvia',
        'LB' => '🇱🇧 Lebanon',
        'LS' => '🇱🇸 Lesotho',
        'LR' => '🇱🇷 Liberia',
        'LY' => '🇱🇾 Libyan Arab Jamahiriya',
        'LI' => '🇱🇮 Liechtenstein',
        'LT' => '🇱🇹 Lithuania',
        'LU' => '🇱🇺 Luxembourg',
        'MO' => '🇲🇴 Macao',
        'MK' => '🇲🇰 Macedonia, the Former Yugoslav Republic of',
        'MG' => '🇲🇬 Madagascar',
        'MW' => '🇲🇼 Malawi',
        'MY' => '🇲🇾 Malaysia',
        'MV' => '🇲🇻 Maldives',
        'ML' => '🇲🇱 Mali',
        'MT' => '🇲🇹 Malta',
        'MH' => '🇲🇭 Marshall Islands',
        'MQ' => '🇲🇶 Martinique',
        'MR' => '🇲🇷 Mauritania',
        'MU' => '🇲🇺 Mauritius',
        'YT' => '🇾🇹 Mayotte',
        'MX' => '🇲🇽 Mexico',
        'FM' => '🇫🇲 Micronesia, Federated States of',
        'MD' => '🇲🇩 Moldova, Republic of',
        'MC' => '🇲🇨 Monaco',
        'MN' => '🇲🇳 Mongolia',
        'ME' => '🇲🇪 Montenegro',
        'MS' => '🇲🇸 Montserrat',
        'MA' => '🇲🇦 Morocco',
        'MZ' => '🇲🇿 Mozambique',
        'MM' => '🇲🇲 Myanmar',
        'NA' => '🇳🇦 Namibia',
        'NR' => '🇳🇷 Nauru',
        'NP' => '🇳🇵 Nepal',
        'NL' => '🇳🇱 Netherlands',
        'AN' => '🇦🇳 Netherlands Antilles',
        'NC' => '🇳🇨 New Caledonia',
        'NZ' => '🇳🇿 New Zealand',
        'NI' => '🇳🇮 Nicaragua',
        'NE' => '🇳🇪 Niger',
        'NG' => '🇳🇬 Nigeria',
        'NU' => '🇳🇺 Niue',
        'NF' => '🇳🇫 Norfolk Island',
        'MP' => '🇲🇵 Northern Mariana Islands',
        'NO' => '🇳🇴 Norway',
        'OM' => '🇴🇲 Oman',
        'PK' => '🇵🇰 Pakistan',
        'PW' => '🇵🇼 Palau',
        'PS' => '🇵🇸 Palestinian Territory, Occupied',
        'PA' => '🇵🇦 Panama',
        'PG' => '🇵🇬 Papua New Guinea',
        'PY' => '🇵🇾 Paraguay',
        'PE' => '🇵🇪 Peru',
        'PH' => '🇵🇭 Philippines',
        'PN' => '🇵🇳 Pitcairn',
        'PL' => '🇵🇱 Poland',
        'PT' => '🇵🇹 Portugal',
        'PR' => '🇵🇷 Puerto Rico',
        'QA' => '🇶🇦 Qatar',
        'RE' => '🇷🇪 Reunion',
        'RO' => '🇷🇴 Romania',
        'RU' => '🇷🇺 Russian Federation',
        'RW' => '🇷🇼 Rwanda',
        'BL' => '🇧🇱 Saint Barthelemy',
        'SH' => '🇸🇭 Saint Helena',
        'KN' => '🇰🇳 Saint Kitts and Nevis',
        'LC' => '🇱🇨 Saint Lucia',
        'MF' => '🇲🇫 Saint Martin',
        'PM' => '🇵🇲 Saint Pierre and Miquelon',
        'VC' => '🇻🇨 Saint Vincent and the Grenadines',
        'WS' => '🇼🇸 Samoa',
        'SM' => '🇸🇲 San Marino',
        'ST' => '🇸🇹 Sao Tome and Principe',
        'SA' => '🇸🇦 Saudi Arabia',
        'SN' => '🇸🇳 Senegal',
        'RS' => '🇷🇸 Serbia',
        'CS' => '🇨🇸 Serbia and Montenegro',
        'SC' => '🇸🇨 Seychelles',
        'SL' => '🇸🇱 Sierra Leone',
        'SG' => '🇸🇬 Singapore',
        'SX' => '🇸🇽 Sint Maarten',
        'SK' => '🇸🇰 Slovakia',
        'SI' => '🇸🇮 Slovenia',
        'SB' => '🇸🇧 Solomon Islands',
        'SO' => '🇸🇴 Somalia',
        'ZA' => '🇿🇦 South Africa',
        'GS' => '🇬🇸 South Georgia and the South Sandwich Islands',
        'SS' => '🇸🇸 South Sudan',
        'ES' => '🇪🇸 Spain',
        'LK' => '🇱🇰 Sri Lanka',
        'SD' => '🇸🇩 Sudan',
        'SR' => '🇸🇷 Suriname',
        'SJ' => '🇸🇯 Svalbard and Jan Mayen',
        'SZ' => '🇸🇿 Swaziland',
        'SE' => '🇸🇪 Sweden',
        'CH' => '🇨🇭 Switzerland',
        'SY' => '🇸🇾 Syrian Arab Republic',
        'TW' => '🇹🇼 Taiwan, Province of China',
        'TJ' => '🇹🇯 Tajikistan',
        'TZ' => '🇹🇿 Tanzania, United Republic of',
        'TH' => '🇹🇭 Thailand',
        'TL' => '🇹🇱 Timor-Leste',
        'TG' => '🇹🇬 Togo',
        'TK' => '🇹🇰 Tokelau',
        'TO' => '🇹🇴 Tonga',
        'TT' => '🇹🇹 Trinidad and Tobago',
        'TN' => '🇹🇳 Tunisia',
        'TR' => '🇹🇷 Turkey',
        'TM' => '🇹🇲 Turkmenistan',
        'TC' => '🇹🇨 Turks and Caicos Islands',
        'TV' => '🇹🇻 Tuvalu',
        'UG' => '🇺🇬 Uganda',
        'UA' => '🇺🇦 Ukraine',
        'AE' => '🇦🇪 United Arab Emirates',
        'GB' => '🇬🇧 United Kingdom',
        'US' => '🇺🇸 United States',
        'UM' => '🇺🇲 United States Minor Outlying Islands',
        'UY' => '🇺🇾 Uruguay',
        'UZ' => '🇺🇿 Uzbekistan',
        'VU' => '🇻🇺 Vanuatu',
        'VE' => '🇻🇪 Venezuela',
        'VN' => '🇻🇳 Viet Nam',
        'VG' => '🇻🇬 Virgin Islands, British',
        'VI' => '🇻🇮 Virgin Islands, U.s.',
        'WF' => '🇼🇫 Wallis and Futuna',
        'EH' => '🇪🇭 Western Sahara',
        'YE' => '🇾🇪 Yemen',
        'ZM' => '🇿🇲 Zambia',
        'ZW' => '🇿🇼 Zimbabwe',
    ];
}

function get_country_from_country_code($code) {
    $code = mb_strtoupper($code ?? '');
    return get_countries_no_emoji_array()[$code] ?? $code;
}

function get_locale_languages_array() {
    return [
        'ab' => 'Abkhazian',
        'aa' => 'Afar',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'sq' => 'Albanian',
        'am' => 'Amharic',
        'ar' => 'Arabic',
        'an' => 'Aragonese',
        'hy' => 'Armenian',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ae' => 'Avestan',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'bm' => 'Bambara',
        'ba' => 'Bashkir',
        'eu' => 'Basque',
        'be' => 'Belarusian',
        'bn' => 'Bengali',
        'bh' => 'Bihari languages',
        'bi' => 'Bislama',
        'bs' => 'Bosnian',
        'br' => 'Breton',
        'bg' => 'Bulgarian',
        'my' => 'Burmese',
        'ca' => 'Catalan, Valencian',
        'km' => 'Central Khmer',
        'ch' => 'Chamorro',
        'ce' => 'Chechen',
        'ny' => 'Chichewa, Chewa, Nyanja',
        'zh' => 'Chinese',
        'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
        'cv' => 'Chuvash',
        'kw' => 'Cornish',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'dv' => 'Divehi, Dhivehi, Maldivian',
        'nl' => 'Dutch, Flemish',
        'dz' => 'Dzongkha',
        'en' => 'English',
        'eo' => 'Esperanto',
        'et' => 'Estonian',
        'ee' => 'Ewe',
        'fo' => 'Faroese',
        'fj' => 'Fijian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'ff' => 'Fulah',
        'gd' => 'Gaelic, Scottish Gaelic',
        'gl' => 'Galician',
        'lg' => 'Ganda',
        'ka' => 'Georgian',
        'de' => 'German',
        'ki' => 'Gikuyu, Kikuyu',
        'el' => 'Greek (Modern)',
        'kl' => 'Greenlandic, Kalaallisut',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ht' => 'Haitian, Haitian Creole',
        'ha' => 'Hausa',
        'he' => 'Hebrew',
        'hz' => 'Herero',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hu' => 'Hungarian',
        'is' => 'Icelandic',
        'io' => 'Ido',
        'ig' => 'Igbo',
        'id' => 'Indonesian',
        'ia' => 'Interlingua (International Auxiliary Language Association)',
        'ie' => 'Interlingue',
        'iu' => 'Inuktitut',
        'ik' => 'Inupiaq',
        'ga' => 'Irish',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'jv' => 'Javanese',
        'kn' => 'Kannada',
        'kr' => 'Kanuri',
        'ks' => 'Kashmiri',
        'kk' => 'Kazakh',
        'rw' => 'Kinyarwanda',
        'kv' => 'Komi',
        'kg' => 'Kongo',
        'ko' => 'Korean',
        'kj' => 'Kwanyama, Kuanyama',
        'ku' => 'Kurdish',
        'ky' => 'Kyrgyz',
        'lo' => 'Lao',
        'la' => 'Latin',
        'lv' => 'Latvian',
        'lb' => 'Letzeburgesch, Luxembourgish',
        'li' => 'Limburgish, Limburgan, Limburger',
        'ln' => 'Lingala',
        'lt' => 'Lithuanian',
        'lu' => 'Luba-Katanga',
        'mk' => 'Macedonian',
        'mg' => 'Malagasy',
        'ms' => 'Malay',
        'ml' => 'Malayalam',
        'mt' => 'Maltese',
        'gv' => 'Manx',
        'mi' => 'Maori',
        'mr' => 'Marathi',
        'mh' => 'Marshallese',
        'ro' => 'Moldovan, Moldavian, Romanian',
        'mn' => 'Mongolian',
        'na' => 'Nauru',
        'nv' => 'Navajo, Navaho',
        'nd' => 'Northern Ndebele',
        'ng' => 'Ndonga',
        'ne' => 'Nepali',
        'se' => 'Northern Sami',
        'no' => 'Norwegian',
        'nb' => 'Norwegian Bokmål',
        'nn' => 'Norwegian Nynorsk',
        'ii' => 'Nuosu, Sichuan Yi',
        'oc' => 'Occitan (post 1500)',
        'oj' => 'Ojibwa',
        'or' => 'Oriya',
        'om' => 'Oromo',
        'os' => 'Ossetian, Ossetic',
        'pi' => 'Pali',
        'pa' => 'Panjabi, Punjabi',
        'ps' => 'Pashto, Pushto',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Romansh',
        'rn' => 'Rundi',
        'ru' => 'Russian',
        'sm' => 'Samoan',
        'sg' => 'Sango',
        'sa' => 'Sanskrit',
        'sc' => 'Sardinian',
        'sr' => 'Serbian',
        'sn' => 'Shona',
        'sd' => 'Sindhi',
        'si' => 'Sinhala, Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'so' => 'Somali',
        'st' => 'Sotho, Southern',
        'nr' => 'South Ndebele',
        'es' => 'Spanish, Castilian',
        'su' => 'Sundanese',
        'sw' => 'Swahili',
        'ss' => 'Swati',
        'sv' => 'Swedish',
        'tl' => 'Tagalog',
        'ty' => 'Tahitian',
        'tg' => 'Tajik',
        'ta' => 'Tamil',
        'tt' => 'Tatar',
        'te' => 'Telugu',
        'th' => 'Thai',
        'bo' => 'Tibetan',
        'ti' => 'Tigrinya',
        'to' => 'Tonga (Tonga Islands)',
        'ts' => 'Tsonga',
        'tn' => 'Tswana',
        'tr' => 'Turkish',
        'tk' => 'Turkmen',
        'tw' => 'Twi',
        'ug' => 'Uighur, Uyghur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'Volap_k',
        'wa' => 'Walloon',
        'cy' => 'Welsh',
        'fy' => 'Western Frisian',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang, Chuang',
        'zu' => 'Zulu'
    ];
}

function get_language_from_locale($locale) {
    $languages = get_locale_languages_array();

    if(!isset($languages[$locale])) {
        return $locale;
    } else {
        return $languages[$locale];
    }
}

/* Dump & die */
function dd($string = null) {
    var_dump($string);
    die();
}

/* Output in debug.log file */
function dil($string = null) {
    ob_start();

    print_r($string);

    $content = ob_get_clean();

    error_log($content);
}

/* Quick include with parameters */
function include_view($view_path, $data = []) {

    $data = (object) $data;

    ob_start();

    require $view_path;

    return ob_get_clean();
}

function get_max_upload() {
    return min(convert_php_size_to_mb(ini_get('upload_max_filesize')), convert_php_size_to_mb(ini_get('post_max_size')));
}

function convert_php_size_to_mb($string) {
    $suffix = mb_strtoupper(mb_substr($string, -1));

    if(!in_array($suffix, ['P','T','G','M','K'])){
        return (int) $string;
    }

    $value = mb_substr($string, 0, -1);

    switch($suffix) {
        case 'P':
            $value *= 1000 * 1000 * 100;
            break;
        case 'T':
            $value *= 1000 * 1000;
            break;
        case 'G':
            $value *= 1000;
            break;
        case 'M':
            /* :) */
            break;
        case 'K':
            $value = $value / 1000;
            break;
    }

    return (float) $value;
}

function get_formatted_bytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1000));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1000, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function get_percentage_between_two_numbers($number, $total) {
    if($total == 0) {
        return 0;
    }

    return ($number / $total) * 100;
}

function get_percentage_change($first_number, $second_number) {
    $first_number = (int) $first_number;
    $second_number = (int) $second_number;

    if($first_number == $second_number) {
        return 0;
    }

    if($first_number < 1) {
        $first_number = 0;
    }

    if($first_number == 0) {
        return 100;
    }

    $difference = (($second_number - $first_number) / $first_number) * 100;

    return $difference;
}

function hex_to_rgb($hex) {
    preg_match("/^#{0,1}([0-9a-f]{1,6})$/i",$hex,$match);
    if(!isset($match[1])) {
        return false;
    }

    if(mb_strlen($match[1]) == 6) {
        list($r, $g, $b) = [$match[1][0].$match[1][1],$match[1][2].$match[1][3],$match[1][4].$match[1][5]];
    }
    elseif(mb_strlen($match[1]) == 3) {
        list($r, $g, $b) = [$match[1][0].$match[1][0],$match[1][1].$match[1][1],$match[1][2].$match[1][2]];
    }
    else if(mb_strlen($match[1]) == 2) {
        list($r, $g, $b) = [$match[1][0].$match[1][1],$match[1][0].$match[1][1],$match[1][0].$match[1][1]];
    }
    else if(mb_strlen($match[1]) == 1) {
        list($r, $g, $b) = [$match[1].$match[1],$match[1].$match[1],$match[1].$match[1]];
    }
    else {
        return false;
    }

    $color = [];
    $color['r'] = hexdec($r);
    $color['g'] = hexdec($g);
    $color['b'] = hexdec($b);

    return $color;
}

function process_and_get_redirect_params() {
    $redirect = null;

    if(isset($_GET['redirect'])) {
        $redirect = query_clean($_GET['redirect']);
        $_SESSION['redirect'] = $redirect;
    }

    return $redirect ?? $_SESSION['redirect'] ?? null;
}

function os_name_to_os_key($os_name) {
    $os = [
        'Windows' => 'windows',
        'Android' => 'android',
        'iOS' => 'ios',
        'OS X' => 'apple',
        'Linux' => 'linux',
        'Ubuntu' => 'ubuntu',
        'Chrome OS' => 'chromeos',
        'KaiOS' => 'kaios',
    ];

    return $os[$os_name] ?? 'unknown';
}

function display_response_time($number) {
    if($number > 1000) {
        return nr($number / 1000, 2) . ' ' . l('global.date.short_seconds');
    } else {
        return nr($number, 3) . ' ' . l('global.date.short_milliseconds');
    }
}

function browser_name_to_browser_key($browser_name) {
    $browsers = [
        'Chrome' => 'chrome',
        'Firefox' => 'firefox',
        'Firefox Mobile' => 'firefox',
        'Edge' => 'edge',
        'Brave' => 'brave',
        'Safari' => 'safari',
        'Opera' => 'opera',
        'Opera Mini' => 'opera',
        'Opera Mobile' => 'opera',
        'Opera Touch' => 'operatouch',
        'Yandex Browser' => 'yandex',
    ];

    return $browsers[$browser_name] ?? 'unknown';
}

function get_random_line_from_text($text) {
    $array = preg_split('/\r\n|\r|\n/', $text ?? '');
    return $array[array_rand($array)];
}

function get_plan_feature_limit_info($used, $total, $should_display = true) {
    if(!$should_display) return null;

    $percentage_used = $total == -1 || $total == 0 ? 0 : ($used / $total * 100);
    $percentage_remaining = $total == -1 ? l('global.unlimited') : nr(100-$percentage_used) . '%';

    return sprintf(l('global.info_message.plan_feature_limit_info'), '<strong>' . nr($used) . '</strong>', '<strong>' . ($total == -1 ? l('global.unlimited') : nr($total)) . '</strong>', '<strong>' . $percentage_remaining . '</strong>');
}

function replace_space_with_plus($string) {
    return str_replace(' ', '+', $string);
}

function convert_editorjs_json_to_html($json) {
    $object = json_decode($json);

    $html = '';
    foreach($object->blocks as $block) {
        switch ($block->type) {
            case 'paragraph':
                $html .= '<p>' . $block->data->text . '</p>';
                break;

            case 'header':
                $html .= '<h' . $block->data->level . '>' . $block->data->text . '</h' . $block->data->level . '>';
                break;

            case 'raw':
                $html .= $block->data->html;
                break;

            case 'list':
                $lsType = ($block->data->style == 'ordered') ? 'ol' : 'ul';
                $html .= '<' . $lsType . '>';
                foreach($block->data->items as $item) {
                    $html .= '<li>' . $item . '</li>';
                }
                $html .= '</' . $lsType . '>';
                break;

            case 'code':
                $html .= '<code>' . e($block->data->code) . '</code>';
                break;

            case 'image':
                $html .= '<div><img class="img-fluid" src="' . $block->data->url . '" alt="' . $block->data->caption . '" /></div>';
                break;

            case 'delimiter':
                $html .= '<hr class="border-gray-100 my-4" />';
                break;

            case 'embed':
                $html .= '
                <div class="embed-responsive embed-responsive-16by9 link-iframe-round">
                    <iframe class="embed-responsive-item" scrolling="no" frameborder="no" src="' . $block->data->embed . '"></iframe>
                </div>
                ';

                if(!empty($block->data->caption)) {
                    $html .= '<p class="small text-muted">' . $block->data->caption . '</p>';
                }
                break;

            default:
                break;
        }
    }

    return $html;
}

function remove_directory_and_contents($dir) {
    if(is_dir($dir)) {
        $objects = scandir($dir);
        foreach($objects as $object) {
            if($object != "." && $object != "..") {
                if(filetype($dir."/".$object) == "dir")
                    remove_directory_and_contents($dir . "/" . $object);
                else unlink   ($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function get_convert_tz_sql($column, $new_timezone, $old_timezone = null) {
    $tz_difference = \Altum\Date::get_timezone_difference($old_timezone ?? \Altum\Date::$default_timezone, $new_timezone);

    return $tz_difference != '+00:00' ? "CONVERT_TZ({$column}, '+00:00', '{$tz_difference}')" : $column;
}
