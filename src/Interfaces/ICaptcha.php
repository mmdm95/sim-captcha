<?php

namespace Sim\Captcha\Interfaces;

interface ICaptcha extends IGenerator, IVerifier
{
    /**
     * @param string|null $name
     * @return static
     */
    public function setName(string $name = null);

    /**
     * @return string
     */
    public function getName();
}