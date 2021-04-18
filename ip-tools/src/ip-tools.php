<?php

/**
 * IP Query Tool
 *
 * @author palemoky@gmail.com
 * @time   2021-04-11
 */

// When IPv6 is not supported, the get_file_contents() query IP will have a warning
error_reporting(E_ERROR | E_PARSE);

const IPV4_QUERY_URL = 'https://api-ipv4.ip.sb/ip';
const IPV6_QUERY_URL = 'https://api-ipv6.ip.sb/ip';
const GEO_IP_QUERY_URL = 'https://api.ip.sb/geoip/';

const OUTPUT_FORMAT_HEADER = '{"items":[';
const OUTPUT_FORMAT_BODY = '{"title":"%s","subtitle":"%s","text":{"copy":"%s"},"icon":{"path":"%s"}}';
const OUTPUT_FORMAT_FOOT = ']}';

/**
 * 获取 IP 的地理位置信息
 * @param string $sIp ip
 * @return string
 */
$getGeoIp = function ($sIp) {
    $sResp = file_get_contents(GEO_IP_QUERY_URL . trim($sIp));
    if (false !== $sResp) {
        $oResp = json_decode($sResp);
        return sprintf(
            OUTPUT_FORMAT_BODY,
            $oResp->ip,
            "$oResp->organization | $oResp->country $oResp->region $oResp->city",
            "$oResp->organization | $oResp->country $oResp->region $oResp->city",
            "$oResp->country_code.png"
        );
    } else {
        return sprintf(OUTPUT_FORMAT_BODY, '网络错误，请稍后重试', '', '', '');
    }
};

/**
 * 没有输入 IP 地址时，默认展示本地 IP 与公网 IP
 */
if (empty($argv[1])) {
    // 获取本地 IP
    $sLocalIp = shell_exec("ifconfig | grep 'inet ' | awk '{print $2}'");
    $aLocalIp = array_filter(array_unique(explode(PHP_EOL, $sLocalIp)));

    $sItems = '';
    foreach ($aLocalIp as $sIp) {
        $sItems .= ',' . sprintf(OUTPUT_FORMAT_BODY, $sIp, 'Private address', $sIp, 'default.png');
    }
    $sItems = ltrim($sItems, ',');

    // 获取公网 IP
    $sPublicIpv4 = file_get_contents(IPV4_QUERY_URL);
    $sPublicIpv6 = file_get_contents(IPV6_QUERY_URL);

    if (false !== $sPublicIpv4) {
        $sItems .= ',' . $getGeoIp($sPublicIpv4);
    }

    if (false !== $sPublicIpv6) {
        $sItems .= ',' . $getGeoIp($sPublicIpv6);
    }

    echo OUTPUT_FORMAT_HEADER . $sItems . OUTPUT_FORMAT_FOOT;
} else {
    // 查询本机指定网卡 IP
    if (false !== strpos($argv[1], 'en')) {
        $sEnIp = shell_exec("ifconfig $argv[1] | grep 'inet ' | awk '{print $2}'");
        echo sprintf(
            OUTPUT_FORMAT_HEADER . OUTPUT_FORMAT_BODY . OUTPUT_FORMAT_FOOT,
            trim($sEnIp), $argv[1], trim($sEnIp), 'default.png'
        );
        exit;
    } elseif (false === filter_var($argv[1], FILTER_VALIDATE_IP)) {
        echo sprintf(
            OUTPUT_FORMAT_HEADER . OUTPUT_FORMAT_BODY . OUTPUT_FORMAT_FOOT,
            'Invalid IP address', 'Please check and retype', '', ''
        );
        exit;
    } elseif (false === filter_var($argv[1], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
        // 私有地址不经查询直接返回结果
        echo sprintf(
            OUTPUT_FORMAT_HEADER . OUTPUT_FORMAT_BODY . OUTPUT_FORMAT_FOOT,
            $argv[1], 'Private address', '', 'default.png'
        );
        exit;
    }

    // 查询 IP 信息
    echo OUTPUT_FORMAT_HEADER . $getGeoIp($argv[1]) . OUTPUT_FORMAT_FOOT;
}

