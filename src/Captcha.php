<?php

namespace Sim\Captcha;

use Sim\Captcha\Abstracts\AbstractCaptchaText;
use Sim\Captcha\Exceptions\CaptchaException;
use Sim\Captcha\i18n\English;
use Sim\Captcha\Interfaces\ICaptchaException;
use Sim\Captcha\Utils\CaptchaUtil;
use Sim\Captcha\Utils\NumberConverterUtil;

class Captcha extends AbstractCaptchaText
{
    /**
     * @var int $length
     */
    protected $length = 6;

    /**
     * @var int $difficulty
     */
    protected $difficulty = CaptchaFactory::DIFFICULTY_NORMAL;

    /**
     * @var array $img_attributes
     */
    protected $img_attributes = [];

    /**
     * @param int $length
     * @return Captcha
     */
    public function setLength(int $length): Captcha
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $difficulty
     * @return Captcha
     */
    public function setDifficulty(int $difficulty): Captcha
    {
        if (in_array($difficulty, [CaptchaFactory::DIFFICULTY_EASY, CaptchaFactory::DIFFICULTY_NORMAL, CaptchaFactory::DIFFICULTY_HARD])) {
            $this->difficulty = $difficulty;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * @param string|null $code
     * @return string
     * @throws CaptchaException
     */
    public function generate(string $code = null)
    {
        $base64Code = $this->generateBase64Code($code);
        return "<img src='data:image/png;base64," . $base64Code . "' {$this->getImgAttributesString()}>";
    }

    /**
     * @param string|null $code
     * @return string
     * @throws CaptchaException
     */
    public function generateBase64Code(string $code = null)
    {
        if (empty($this->font) || !file_exists($this->font)) {
            throw new CaptchaException("Font does'n specified!");
        }
        if (empty($code)) {
            $code = $this->getRandomArrString();
        }

        // Begin output buffering
        ob_start();

        $width = $this->getWidth();
        $height = $this->getHeight();

        $image = imagecreatetruecolor($width, $height);
        imageantialias($image, true);

        $colors = [];
        $red = rand(125, 175);
        $green = rand(125, 175);
        $blue = rand(125, 175);
        for ($i = 0; $i < 5; $i++) {
            $colors[] = imagecolorallocate($image, $red - 20 * $i, $green - 20 * $i, $blue - 20 * $i);
        }

        if ($this->has_noise) {
            for ($w = 1; $w <= $width; $w++) {
                for ($h = 1; $h <= $height; $h++) {
                    if (mt_rand(1, 100) >= 65) {
                        ImageSetPixel($image, $w, $h, $colors[rand(1, 4)]);
                    }
                }
            }
        }

        imagefill($image, 0, 0, $colors[0]);

        for ($i = 0;
             $i < 10;
             $i++) {
            imagesetthickness($image, rand(2, 10));
            $line_color = $colors[rand(1, 4)];
            imagerectangle($image, rand(-10, 10), rand(-10, 10), rand(-10, $width + 20), rand(40, $height + 20), $line_color);
        }

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        $textColors = [$black, $white];

        $string_length = count($code);
        $captcha_string = $code;

        $this->setSession($captcha_string);

        for ($i = 0; $i < $string_length; $i++) {
            $initial = 15;
            $letter_space = ($width - $initial) / $string_length;

            imagettftext($image, $this->getFontSize(), rand(-15, 15), $initial + $i * $letter_space, rand(25, $height - 15), $textColors[rand(0, 1)], $this->getFont(), $captcha_string[$i]);
        }

//        ob_clean();
//        header('Cache-Control: no-cache');
//        header('Content-Type: image/png');

        imagepng($image);
        // and finally retrieve the byte stream
        $rawImageBytes = ob_get_clean();
        imagedestroy($image);

        return base64_encode($rawImageBytes);
    }

    /**
     * @param string $input
     * @return bool
     * @throws ICaptchaException
     */
    public function verify($input): bool
    {
        $value = $this->getSession();
        if (is_null($value) || !is_array($value)) {
            return false;
        }
        if (!is_string($input)) {
            throw new CaptchaException("Verify phrase is not valid! Please specify a string value");
        }

        if (empty($input)) return false;

        $value = (string)implode('', $value);
        if (CaptchaFactory::DIFFICULTY_NORMAL == $this->difficulty) {
            $value = mb_strtolower($value);
            $input = mb_strtolower($input);
        }

        if ($this->use_english_numbers_to_verify) {
            $converter = new NumberConverterUtil($this->language, new English());
            $value = $converter->convert($value);
            $input = $converter->convert($input);
        }

        return $input === (string)$value;
    }

    /**
     * @return array
     */
    protected function getRandomArrString(): array
    {
        switch ($this->difficulty) {
            case CaptchaFactory::DIFFICULTY_EASY:
                $difficulty = CaptchaUtil::TEXT_NUMBER;
                break;
            case CaptchaFactory::DIFFICULTY_HARD:
                $difficulty = CaptchaUtil::TEXT_NUMBER | CaptchaUtil::TEXT_UPPER_CHAR | CaptchaUtil::TEXT_LOWER_CHAR;
                break;
            case CaptchaFactory::DIFFICULTY_NORMAL:
            default:
                $difficulty = CaptchaUtil::TEXT_NUMBER | CaptchaUtil::TEXT_UPPER_CHAR;
                break;
        }

        $code = CaptchaUtil::randomString($this->getLength(), $difficulty, $this->language);

        return $code;
    }
}