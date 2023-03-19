<?php
namespace Sesame\Other;

class Utility {
    /**
     * タイムスタンプ(ms) を DateTime型に変換する
     *
     * @param float $timeStamp
     * @return \DateTime
     */
    public static function datetimeByTimestamp(float $timeStamp): \DateTime {
        $timestamp = (float) $timeStamp / 1000;
        $datetime = new \DateTime();
        $datetime->setTimeZone(new \DateTimeZone('Asia/Tokyo'));
        $datetime->setTimestamp($timestamp);
        $date = $datetime->format('Y-m-d H:i:s');
        return $datetime;
    }
}
