<?php

namespace Sim\Captcha;

use Sim\Captcha\Abstracts\AbstractCaptchaText;
use Sim\Captcha\Exceptions\CaptchaTextException;
use Sim\Captcha\i18n\English;
use Sim\Captcha\Interfaces\ICaptchaLanguage;
use Sim\Captcha\Utils\CaptchaUtil;
use Sim\Captcha\Utils\NumberConverterUtil;

class CaptchaSimpleMath extends AbstractCaptchaText
{
    /**
     * @var array $operands
     */
    protected $operands = [];

    /**
     * @var array $sum_operands
     */
    protected $sum_operands = ['+', '-'];

    /**
     * @var array $mul_operands
     */
    protected $mul_operands = ['×'];

    /**
     * @var string $operand
     */
    protected $operand;

    /**
     * @var array $numbers_alpha
     */
    protected $numbers_alpha = [];

    /**
     * @var ICaptchaLanguage $en_language
     */
    protected $en_language;

    /**
     * @var int $numbers_count
     */
    protected $numbers_count = 2;

    /**
     * @var array $generated_numbers
     */
    protected $generated_numbers = [];

    /**
     * CaptchaText constructor.
     * @param ICaptchaLanguage $language
     */
    public function __construct(ICaptchaLanguage $language)
    {
        parent::__construct($language);
        $this->en_language = new English();
        $this->operands = $this->sum_operands;
    }

    /**
     * @param int $count
     * @return CaptchaSimpleMath
     */
    public function setNumbersCount(int $count): CaptchaSimpleMath
    {
        if ($count >= 2 && $count <= 5) {
            $this->numbers_count = $count;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getNumbersCount(): int
    {
        return $this->numbers_count;
    }

    /**
     * @return CaptchaSimpleMath
     */
    public function useMultiplyOperands(): CaptchaSimpleMath
    {
        $this->operands = $this->mul_operands;
        return $this;
    }

    /**
     * @return string
     * @throws CaptchaTextException
     */
    public function generate()
    {
        $base64Code = $this->generateBase64Code();
        return "<img src='data:image/png;base64," . $base64Code . "' {$this->getImgAttributesString()}>";
    }

    /**
     * @return string
     * @throws CaptchaTextException
     */
    public function generateBase64Code()
    {
        if (empty($this->font) || !file_exists($this->font)) {
            throw new CaptchaTextException("Font does'n specified!");
        }
        if (empty($code)) {
            $code = $this->generateNumbersAlphaOperands();
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

        for ($i = 0; $i < 10; $i++) {
            imagesetthickness($image, rand(2, 10));
            $line_color = $colors[rand(1, 4)];
            imagerectangle($image, rand(-10, 10), rand(-10, 10), rand(-10, $width + 20), rand(40, $height + 20), $line_color);
        }

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        $textColors = [$black, $white];

        $string_length = count($code);
        $captcha_string = $code;

        $result = $this->getSummaryResult($code);

        $this->setSession($result);

        for ($i = 0; $i < $string_length; $i++) {
            $initial = 15;
            $letter_space = ($width - $initial) / $string_length;
            $x = $initial + $i * $letter_space;
            $fontSize = $this->getFontSize();
            if (in_array($captcha_string[$i], array_merge($this->sum_operands, $this->mul_operands))) {
                $fontSize += 5;
            }

            imagettftext($image, $fontSize, rand(-15, 15), $x, rand(25, $height - 15), $textColors[rand(0, 1)], $this->getFont(), $captcha_string[$i]);
        }

        imagepng($image);
        // and finally retrieve the byte stream
        $rawImageBytes = ob_get_clean();
        imagedestroy($image);

        return base64_encode($rawImageBytes);
    }

    /**
     * {@inheritdoc}
     * @throws CaptchaTextException
     */
    public function verify($input)
    {
        $value = $this->getSession();
        if (is_null($value) || !is_numeric($value)) {
            return false;
        }
        if (!is_string($input)) {
            throw new CaptchaTextException("Verify phrase is not valid! Please specify a string value");
        }

        if ($this->use_english_numbers_to_verify) {
            $converter = new NumberConverterUtil($this->language, $this->en_language);
            $value = $converter->convert($value);
            $input = $converter->convert($input);
        }

        return (string)$input === (string)$value;
    }

    /**
     * @param array $summary
     * @return int
     */
    protected function getSummaryResult(array $summary): int
    {
        $result = 0;
        $len = count($summary);

        for ($i = 0; $i < $len; $i++) {
            if (0 == $i) {
                $result = (int)$this->generated_numbers[$i / 2];
            } elseif (in_array($summary[$i], $this->operands)) {
                if (isset($summary[$i + 1])) {
                    switch ($summary[$i]) {
                        case '+':
                            $result += (int)$this->generated_numbers[($i + 1) / 2];
                            $i++;
                            break;
                        case '-':
                            $result -= (int)$this->generated_numbers[($i + 1) / 2];
                            $i++;
                            break;
                        case '×':
                            $result *= (int)$this->generated_numbers[($i + 1) / 2];
                            $i++;
                            break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function generateNumbersAlphaOperands(): array
    {
        $numbersAlpha = $this->mapNumberToNumbersAlpha($this->getRandomNumber());
        $numsCount = count($numbersAlpha);
        $operands = $this->getRandomOperand($numsCount - 1);
        $operandsCount = count($operands);

        $summary = [];
        $summary[] = array_shift($numbersAlpha);
        for ($i = 0; $i < $operandsCount; $i++) {
            $summary[] = array_shift($operands);
            $summary[] = array_shift($numbersAlpha);
        }

        return $summary;
    }

    /**
     * @return array
     */
    protected function getRandomNumber(): array
    {
        $this->generated_numbers = CaptchaUtil::randomString($this->getNumbersCount(), CaptchaUtil::TEXT_NUMBER, $this->en_language);
        return $this->generated_numbers;
    }

    /**
     * @param int $count
     * @return array
     */
    protected function getRandomOperand(int $count): array
    {
        $operands = [];
        for ($i = 0; $i < $count; $i++) {
            $rndNum = rand(0, count($this->operands) - 1);
            $operands[] = $this->operands[$rndNum];
        }
        return $operands;
    }

    /**
     * @param array $numbers
     * @return array
     */
    protected function mapNumberToNumbersAlpha(array $numbers): array
    {
        $enNumbers = array_keys($this->en_language->numbers());
        $numKeys = [];
        foreach ($numbers as $number) {
            $res = array_search($number, $enNumbers);
            if (false !== $res) {
                $numKeys[] = $res;
            }
        }

        $numbersAlpha = $this->language->numbers();
        $numsAlpha = [];
        foreach ($numKeys as $numKey) {
            $numsAlpha[] = $numbersAlpha[$numKey];
        }

        return $numsAlpha;
    }
}