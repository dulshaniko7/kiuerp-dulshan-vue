<?php
namespace Modules\Admin\Services;

class Location
{
    public static $ipApiKey = "";

    public static function getClientIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
        {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if($ip == "::1" || $ip == "127.0.0.1")
        {
            $ip = "123.231.122.200";
        }

        return $ip;
    }

    public static function getGeoData($ip)
    {
        $data = array();
        $ip_info = Location::getIpApiDataPremium($ip);

        if(!(is_array($ip_info) && isset($ip_info["isp"])))
        {
            $ip_info = Location::getIpApiDataFree($ip);

            if(is_array($ip_info) && isset($ip_info["isp"]))
            {
                $data = $ip_info;
            }
        }
        else
        {
            $data = $ip_info;
        }

        return $data;
    }

    public static function getIpApiDataPremium($ip)
    {
        //return @json_decode(@file_get_contents("http://pro.ip-api.com/json/".$ip."?key=".Location::$ipApiKey."&lang=fr"), true);

        return @json_decode(@file_get_contents("http://ip-api.com/json/".$ip), true);
    }

    public static function getIpApiDataFree($ip)
    {
        return @json_decode(@file_get_contents("http://ip-api.com/json/".$ip), true);
    }
}
