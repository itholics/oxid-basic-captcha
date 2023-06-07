<?php

namespace ITholics\Oxid\BasicCaptcha\Application\Controller;
use ITholics\Oxid\BasicCaptcha\Application\Shared\Captcha;


class ForgotPasswordController extends ForgotPasswordController_parent {
    use Captcha;

    public function forgotpassword() {
        if (!$this->getCaptcha()->passCaptcha()) {
            return false;
        }
        return parent::forgotpassword();
    }
}