<?php
namespace ITholics\Oxid\BasicCaptcha\Application\Shared;

trait Captcha {
    protected $captcha;

    public function getCaptcha(): \ITholics\Oxid\BasicCaptcha\Application\Core\Captcha {
        if (!$this->captcha) {
            $this->captcha = \ITholics\Oxid\BasicCaptcha\Application\Core\Captcha::getInstance();
        }
        return $this->captcha;
    }
}