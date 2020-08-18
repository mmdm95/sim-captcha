<?php

use Sim\Captcha\CaptchaSimpleMath;
use Sim\Captcha\CaptchaFactory;
use Sim\Captcha\i18n\Persian;

include_once '../../vendor/autoload.php';

/**
 * @var CaptchaSimpleMath $captcha
 */
$captcha = CaptchaFactory::instance(CaptchaFactory::CAPTCHA_SIMPLE_MATH);
//$captcha = CaptchaFactory::instance(CaptchaFactory::CAPTCHA_SIMPLE_MATH, new Persian());
//$captcha->setFont(CaptchaFactory::FONT_IRAN_SANS);
$captcha->setName('page-name');

// submit form to check verify
if(isset($_POST['submit'])) {
    if($captcha->verify($_POST['captcha'])) {
        echo 'captcha is valid';
    } else {
        echo 'captcha is NOT valid!! Please try again';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Captcha</title>
</head>
<body>
<form action="captcha-text.php" method="post" style="text-align: center;">
    <h3>
        Please enter result of following equation in input:
    </h3>
    <br>
    <?= $captcha->setImgAttributes([
        'style' => 'display: inline-block; margin-bottom: 20px;'
    ])->generate(); ?>
    <br>
    <input type="text" id="captcha" name="captcha" placeholder="enter code here">
    <button type="submit" name="submit">
        Submit
    </button>
</form>
</body>
</html>
