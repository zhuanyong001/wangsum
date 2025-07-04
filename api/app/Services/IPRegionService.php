<?php

namespace App\Services;

use Ritaswc\ZxIPAddress\IPv4Tool;
use Ritaswc\ZxIPAddress\IPv6Tool;

class IPRegionService
{
    public static function getRegion($ip)
    {
        if (strpos($ip, '.') !== false) {
            try {
                $ress = IPv4Tool::query($ip);
                if ($ress && isset($ress['disp'])) {
                    return $ress['disp'];
                } else {
                    $dbFile = base_path("storage/app") . "/ip/ip2region.db";
                    $method     = 'btreeSearch';
                    $ip2regionObj = new \Ip2Region($dbFile);
                    $ip_info = $ip2regionObj->{$method}($ip);
                    if (isset($ip_info['region'])) {
                        return $ip_info['region'];
                    }
                    return "";
                }
            } catch (\Exception $ex) {
                $dbFile = base_path("storage/app") . "/ip/ip2region.db";
                $method     = 'btreeSearch';
                $ip2regionObj = new \Ip2Region($dbFile);
                $ip_info = $ip2regionObj->{$method}($ip);
                if (isset($ip_info['region'])) {
                    return $ip_info['region'];
                }
                return "";
            }
        } else {
            try {
                $ress = IPv6Tool::query($ip);
                if ($ress && isset($ress['disp'])) {
                    return $ress['disp'];
                } else {
                    return "";
                }
            } catch (\Exception $ex) {
                return "";
            }
        }
    }
}
