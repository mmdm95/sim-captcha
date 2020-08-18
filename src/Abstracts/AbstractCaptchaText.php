<?php

namespace Sim\Captcha\Abstracts;

use Sim\Captcha\Interfaces\ICaptchaLanguage;

abstract class AbstractCaptchaText extends AbstractCaptcha
{
    /**
     * @var int $width
     */
    protected $width = 200;

    /**
     * @var int $height
     */
    protected $height = 50;

    /**
     * @var string|null $font
     */
    protected $font = null;

    /**
     * @var int $font_size
     */
    protected $font_size = 20;

    /**
     * @var array $img_attributes
     */
    protected $img_attributes = [];

    /**
     * @var ICaptchaLanguage $language
     */
    protected $language;

    /**
     * @var bool $has_noise
     */
    protected $has_noise = true;

    /**
     * @var bool $use_english_numbers_to_verify
     */
    protected $use_english_numbers_to_verify = false;

    /**
     * Captcha constructor.
     * @param ICaptchaLanguage $language
     */
    public function __construct(ICaptchaLanguage $language)
    {
        $this->init();
        $this->language = $language;
        $this->setName();
        $this->setFont(dirname(__DIR__) . '/Fonts/Menlo-Regular.ttf');
    }

    /**
     * @param int $width
     * @return static
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $height
     * @return static
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param string $filename
     * @return static
     */
    public function setFont(string $filename)
    {
        $this->font = $filename;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * @param int $size
     * @return static
     */
    public function setFontSize(int $size)
    {
        if ($size > 0 && $size < 200) {
            $this->font_size = $size;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getFontSize(): int
    {
        return $this->font_size;
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function addNoise(bool $answer)
    {
        $this->has_noise = $answer;
        return $this;
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function useEnglishNumbersToVerify(bool $answer)
    {
        $this->use_english_numbers_to_verify = $answer;
        return $this;
    }

    /**
     * @param array $attributes
     * @return static
     */
    public function setImgAttributes(array $attributes)
    {
        foreach ($attributes as $key => $attribute) {
            if (is_string($key) && is_string($attribute) && 'src' != strtolower($key)) {
                $this->img_attributes[$key] = $attribute;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getImgAttributes(): array
    {
        return $this->img_attributes;
    }

    /**
     * @return string
     */
    public function getImgAttributesString(): string
    {
        return $this->imgAttributesToString();
    }

    /**
     * @return string
     */
    protected function imgAttributesToString(): string
    {
        $attributes = '';
        foreach ($this->img_attributes as $name => $attribute) {
            $attributes .= " $name='$attribute' ";
        }

        return trim($attributes);
    }
}