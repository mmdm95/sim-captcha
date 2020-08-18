<?php

namespace Sim\Captcha\Utils;

use Sim\Captcha\Interfaces\ICaptchaLanguage;

class CaptchaUtil
{
    const TEXT_NUMBER = 0x1;
    const TEXT_LOWER_CHAR = 0x2;
    const TEXT_UPPER_CHAR = 0x4;
    const TEXT_ALL = CaptchaUtil::TEXT_NUMBER |
    CaptchaUtil::TEXT_LOWER_CHAR |
    CaptchaUtil::TEXT_UPPER_CHAR;

    /**
     * @param $key
     * @param $value
     * @param $time
     */
    public static function setTimesSession($key, $value, $time)
    {
        $arr = [
            'data' => $value,
            'ttl' => time() + $time,
        ];
        ArrayUtil::set($_SESSION, $key, $arr);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function getTimedSession($key)
    {
        if (self::hasTimedSession($key)) {
            $res = ArrayUtil::get($_SESSION, $key);
            if (!is_array($res) || (isset($res['ttl']) && time() > $res['ttl'])) {
                self::removeTimedSession($key);
                return null;
            }
            return $res['data'] ?? $res;
        }
        return null;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function hasTimedSession($key)
    {
        return ArrayUtil::has($_SESSION, $key, false);
    }

    /**
     * @param $key
     */
    public static function removeTimedSession($key)
    {
        ArrayUtil::remove($_SESSION, $key);
    }

    /**
     * @param $timestamp
     * @return bool
     */
    public static function isValidTimestamp($timestamp): bool
    {
        return ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * @param $length
     * @param $type
     * @param ICaptchaLanguage $language
     * @return array
     */
    public static function randomString($length, $type, ICaptchaLanguage $language): array
    {
        $charactersMap = [
            'number' => $language->numbers(),
            'lower' => $language->alphaSmall(),
            'upper' => $language->alphaCaps(),
        ];

        $characters = [];
        $haveType = CaptchaUtil::TEXT_ALL;

        if (($type & CaptchaUtil::TEXT_NUMBER) && count($charactersMap['number'])) {
            $characters[] = $charactersMap['number'];
            $haveType = $haveType ^ CaptchaUtil::TEXT_NUMBER;
        }
        if (($type & CaptchaUtil::TEXT_LOWER_CHAR) && count($charactersMap['lower'])) {
            $characters[] = $charactersMap['lower'];
            $haveType = $haveType ^ CaptchaUtil::TEXT_LOWER_CHAR;
        }
        if (($type & CaptchaUtil::TEXT_UPPER_CHAR) && count($charactersMap['upper'])) {
            $characters[] = $charactersMap['upper'];
            $haveType = $haveType ^ CaptchaUtil::TEXT_UPPER_CHAR;
        }

        if ((CaptchaUtil::TEXT_ALL ^ $haveType) == 0) {
            $characters[] = $charactersMap['number'];
            if (count($charactersMap['lower'])) {
                $characters[] = $charactersMap['lower'];
            }
            if (count($charactersMap['upper'])) {
                $characters[] = $charactersMap['upper'];
            }
        }

        $charactersLength = count($characters);

        $randomString = [];
        for ($i = 0; $i < $length; $i++) {
//            srand(self::make_seed());
            $charactersArr = $characters[rand(0, $charactersLength - 1)];
            $charactersArrLength = count($charactersArr);
            $character = $charactersArr[rand(0, $charactersArrLength - 1)];
            $randomString[] = trim($character);
        }

        return $randomString;
    }

    // seed with microseconds
    private static function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return $sec + $usec * 1000000;
    }
}