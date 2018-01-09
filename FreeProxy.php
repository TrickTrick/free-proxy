<?php

namespace TrickTrick\freeProxy;

use phpQuery;

class FreeProxy
{
    protected static $_url = 'https://free-proxy-list.net';
    protected static $_ips;
    protected static $_counter = 0;
    protected static $_country = null;

    public static function getIps()
    {

        $body = file_get_contents(self::$_url);
        $document = phpQuery::newDocumentHTML($body);
        $arTr = pq($document)->find('tr');
        $res = array();
        foreach ($arTr as $k => $tr) {
            if(!$k) continue;
            $ip = pq($tr)->find('td:eq(0)')->html();
            $port = pq($tr)->find('td:eq(1)')->html();
            $country = pq($tr)->find('td:eq(2)')->html();
            if (self::$_country && $country != self::$_country) continue;
            if ($ip && $port)
                $res[] = [
                    'ip' => $ip,
                    'port' => $port,
                ];
        }
        self::$_ips = $res;
        return $res;
    }

    public static function getIp($needNewIp = false)
    {
        if (!self::$_ips) self::getIps();

        if ($needNewIp && isset(self::$_ips[self::$_counter + 1])) {
            self::$_counter++;
        }
        return self::$_ips[self::$_counter]['ip'] . ':' . self::$_ips[self::$_counter]['port'];
    }
}
