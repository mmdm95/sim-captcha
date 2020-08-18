<?php

namespace Sim\Captcha;

use Sim\Captcha\i18n\English;
use Sim\Captcha\Interfaces\ICaptchaLanguage;

class CaptchaFactory
{
    const CAPTCHA = 1;
    const CAPTCHA_SIMPLE_MATH = 2;

    const DIFFICULTY_EASY = 1;
    const DIFFICULTY_NORMAL = 2;
    const DIFFICULTY_HARD = 3;

    const FONT_IRAN_SANS = __DIR__ . DIRECTORY_SEPARATOR . 'Fonts' . DIRECTORY_SEPARATOR . 'IRANSansWeb.ttf';
    const FONT_MENLO = __DIR__ . DIRECTORY_SEPARATOR . 'Fonts' . DIRECTORY_SEPARATOR . 'Menlo-Regular.ttf';
    const FONT_LATEEF = __DIR__ . DIRECTORY_SEPARATOR . 'Fonts' . DIRECTORY_SEPARATOR . 'Lateef-Regular.ttf';

    /**
     * @param int $type
     * @param ICaptchaLanguage|null $language
     * @return Captcha|CaptchaSimpleMath
     */
    public static function instance(int $type = CaptchaFactory::CAPTCHA, ICaptchaLanguage $language = null)
    {
        if (is_null($language)) {
            $language = new English();
        }

        switch ($type) {
            case self::CAPTCHA_SIMPLE_MATH:
                return new CaptchaSimpleMath($language);
            case self::CAPTCHA:
            default:
                return new Captcha($language);
        }
    }
}