<?php

namespace Sim\Captcha\Utils;

use Sim\Captcha\i18n\English;
use Sim\Captcha\Interfaces\ICaptchaLanguage;

class NumberConverterUtil
{
    /**
     * @var array $from_numbers
     */
    protected $from_numbers = [];

    /**
     * @var array $to_numbers
     */
    protected $to_numbers = [];

    /**
     * @var array $from_alpha_small
     */
    protected $from_alpha_small = [];

    /**
     * @var array $to_alpha_small
     */
    protected $to_alpha_small = [];

    /**
     * @var array $from_alpha_capital
     */
    protected $from_alpha_capital = [];

    /**
     * @var array $to_alpha_capital
     */
    protected $to_alpha_capital = [];

    /**
     * NumberConverterUtil constructor.
     * @param ICaptchaLanguage $from
     * @param ICaptchaLanguage|null $to
     */
    public function __construct(ICaptchaLanguage $from, ICaptchaLanguage $to = null)
    {
        $this->from_numbers = $from->numbers();
        $this->from_alpha_small = $from->alphaSmall();
        $this->from_alpha_capital = $from->alphaCaps();
        if (is_null($to)) {
            $to = new English();
        }
        $this->to_numbers = $to->numbers();
        $this->to_alpha_small = $to->alphaSmall();
        $this->to_alpha_capital = $to->alphaCaps();
    }

    /**
     * @param array|string $str
     * @return array|mixed
     */
    public function convert($str)
    {
        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = $this->convert($str[$k]);
            }
            return $newArr;
        }

        if (is_string($str)) {
            $str = str_replace($this->from_numbers, $this->to_numbers, $str);
            $str = str_replace($this->from_alpha_small, $this->to_alpha_small, $str);
            $str = str_replace($this->from_alpha_capital, $this->to_alpha_capital, $str);
        }

        return $str;
    }
}