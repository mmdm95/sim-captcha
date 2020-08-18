<?php

use Sim\Captcha\Captcha;
use Sim\Captcha\CaptchaFactory;
use Sim\Captcha\i18n\Arabic;
use Sim\Captcha\i18n\Persian;

include_once '../../vendor/autoload.php';

/**
 * @var Captcha $captcha
 */
$captcha = CaptchaFactory::instance();
//$captcha = CaptchaFactory::instance(CaptchaFactory::CAPTCHA, new Persian());
//$captcha->setFont(CaptchaFactory::FONT_IRAN_SANS);
//$captcha = CaptchaFactory::instance(CaptchaFactory::CAPTCHA, new Arabic());
//$captcha->setFont(CaptchaFactory::FONT_LATEEF)->setFontSize(25);
http_response_code(200);
echo json_encode($captcha->generate());
exit;
