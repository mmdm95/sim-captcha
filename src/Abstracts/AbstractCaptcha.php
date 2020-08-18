<?php

namespace Sim\Captcha\Abstracts;

use Sim\Captcha\Interfaces\ICaptcha;
use Sim\Captcha\Utils\CaptchaUtil;

abstract class AbstractCaptcha implements ICaptcha
{
    /**
     * @var string $default_name
     */
    protected $default_name = 'captcha';

    /**
     * @var string $captcha_session_name
     */
    protected $captcha_session_name = '__captcha_sess_simplicity_';

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var int $expiration 600s = 10min
     */
    protected $expiration = 600;

    /**
     * {@inheritdoc}
     */
    public function setName(string $name = null)
    {
        $this->name = $name ?: $this->default_name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $expire_time
     * @return static
     */
    public function setExpiration(int $expire_time)
    {
        $this->expiration = $expire_time < PHP_INT_MAX ? ($expire_time > ~PHP_INT_MAX ? $expire_time : $this->expiration) : $this->expiration;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Initialize needed functionality
     */
    protected function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION[$this->captcha_session_name])) {
            $_SESSION[$this->captcha_session_name] = [];
        }
    }

    /**
     * @param $value
     * @return static
     */
    protected function setSession($value)
    {
        CaptchaUtil::setTimesSession($this->_dotConcatenation($this->captcha_session_name, $this->getName()), $value, $this->getExpiration());
        return $this;
    }

    /**
     * @return mixed|null
     */
    protected function getSession()
    {
        return CaptchaUtil::getTimedSession($this->_dotConcatenation($this->captcha_session_name, $this->getName()));
    }

    /**
     * @return static
     */
    protected function removeSession()
    {
        CaptchaUtil::removeTimedSession($this->_dotConcatenation($this->captcha_session_name, $this->getName()));
        return $this;
    }

    /**
     * @param $str1
     * @param $str2
     * @return string
     */
    private function _dotConcatenation($str1, $str2)
    {
        return $str1 . '.' . $str2;
    }
}