<?php

namespace Sim\Captcha\i18n;

use Sim\Captcha\Interfaces\ICaptchaLanguage;

class Arabic implements ICaptchaLanguage
{
    /**
     * {@inheritdoc}
     */
    public function numbers(): array
    {
        return [
            '٠',
            '١',
            '٢',
            '٣',
            '٤',
            '٥',
            '٦',
            '٧',
            '٨',
            '٩',
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
            'ا',
            'ب',
            'ت',
            'ث',
            'ج',
            'ح',
            'خ',
            'د',
            'ذ',
            'ر',
            'ز',
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
            'ك',
            'ل',
            'م',
            'ن',
            'ة',
            'و',
            'ي',
        ];
    }
}