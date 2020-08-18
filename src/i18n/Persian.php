<?php

namespace Sim\Captcha\i18n;

use Sim\Captcha\Interfaces\ICaptchaLanguage;

class Persian implements ICaptchaLanguage
{
    /**
     * {@inheritdoc}
     */
    public function numbers(): array
    {
        return [
            '۰',
            '۱',
            '۲',
            '۳',
            '۴',
            '۵',
            '۶',
            '۷',
            '۸',
            '۹',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function alphaSmall(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function alphaCaps(): array
    {
        return [
            'آ',
            'ب',
            'پ',
            'ت',
            'ث',
            'ج',
            'چ',
            'ح',
            'خ',
            'د',
            'ذ',
            'ر',
            'ز',
            'ژ',
            'س',
            'ش',
            'ص',
            'ض',
            'ط',
            'ظ',
            'ع',
            'غ',
            'ف',
            'ق',
            'ک',
            'گ',
            'ل',
            'م',
            'ن',
            'و',
            'ه',
            'ی',
        ];
    }
}