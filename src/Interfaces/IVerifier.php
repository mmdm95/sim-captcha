<?php

namespace Sim\Captcha\Interfaces;

interface IVerifier
{
    /**
     * @param $input
     * @return mixed
     */
    public function verify($input);
}