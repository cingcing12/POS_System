<?php

namespace App\Services;

class KhqrGenerator
{
    public static function generate($bakongId, $merchantName, $amount, $currency = 'USD')
    {
        $payload = self::f('00', '01'); 
        $payload .= self::f('01', '12'); 
        $merchantInfo = self::f('00', 'bakong@bakong'); 
        $merchantInfo .= self::f('01', $bakongId);      
        $payload .= self::f('29', $merchantInfo);
        $payload .= self::f('52', '5399'); 
        $currCode = ($currency == 'KHR') ? '116' : '840';
        $payload .= self::f('53', $currCode);
        $payload .= self::f('54', number_format($amount, 2, '.', ''));
        $payload .= self::f('58', 'KH');
        $payload .= self::f('59', substr($merchantName, 0, 25)); 
        $payload .= self::f('60', 'Phnom Penh');
        $payload .= '6304'; 
        $payload .= self::crc16($payload);
        return $payload;
    }

    private static function f($id, $value) {
        return $id . sprintf("%02d", strlen($value)) . $value;
    }

    private static function crc16($data) {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return strtoupper(sprintf("%04x", $crc));
    }
}