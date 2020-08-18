<?php

use Sim\Captcha\Captcha;
use Sim\Captcha\CaptchaFactory;
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

// submit form to check verify
if (isset($_POST['submit'])) {
    if ($captcha->verify($_POST['captcha'])) {
//    if ($captcha->useEnglishNumbersToVerify(true)->verify($_POST['captcha'])) {
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
<form action="index.php" method="post" style="text-align: center;">
    <h3>
        Please enter following characters in input:
    </h3>
    <br>
    <div id="captchaContainer">
    </div>
    <br>
    <button type="button" id="newCaptcha">
        new captcha
    </button>
    <input type="text" id="captcha" name="captcha" placeholder="enter code here">
    <button type="submit" name="submit">
        Submit
    </button>
</form>

<script>
    (function () {
        /**
         * @see https://stackoverflow.com/questions/9899372/pure-javascript-equivalent-of-jquerys-ready-how-to-call-a-function-when-t
         * @param fn
         */
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === "complete" || document.readyState === "interactive") {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        function addEvent(element, type, callback, bubble) { // 1
            if (document.addEventListener) { // 2
                return element.addEventListener(type, callback, bubble || false); // 3
            }
            return element.attachEvent('on' + type, callback); // 4
        }

        function getCaptcha(callback) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (typeof callback === typeof function () {
                    }) {
                        var response;
                        try {
                            response = JSON.parse(xhr.responseText);
                        } catch {
                            console.log(xhr.responseText);
                            response = '';
                        }
                        callback.apply(this, [response]);
                    }
                }
            };
            xhr.open('GET', 'captcha.php');
            xhr.send()
        }

        docReady(function () {
            // DOM is loaded and ready for manipulation here
            var captcha_container,
                captcha_btn;

            captcha_container = document.getElementById('captchaContainer');
            captcha_btn = document.getElementById('newCaptcha');

            function setCaptcha() {
                getCaptcha(function (newImage) {
                    captcha_container.innerHTML = newImage;
                });
            }

            setCaptcha();

            addEvent(captcha_btn, 'click', function () {
                setCaptcha();
            });
        });
    })();
</script>
</body>
</html>
