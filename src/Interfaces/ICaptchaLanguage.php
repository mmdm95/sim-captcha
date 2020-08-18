<?php

namespace Sim\Captcha\Interfaces;

interface ICaptchaLanguage
{
    /**
     * Return numbers of language
     *
     * Note: If there is not any number on that language,
     * return empty array
     *
     * @return array
     */
    public function numbers(): array;

    /**
     * Return small alpha of language
     *
     * Note: If there is not any small alpha on that language,
     * return empty array
     *
     * @return array
     */
    public function alphaSmall(): array;

    /**
     * Return capital alpha of language
     *
     * Note: If there is not any capital alpha on that language,
     * return empty array
     *
     * @return array
     */
    public function alphaCaps(): array;
}