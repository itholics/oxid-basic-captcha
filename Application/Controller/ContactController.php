<?php

namespace ITholics\Oxid\BasicCaptcha\Application\Controller;
use ITholics\Oxid\BasicCaptcha\Application\Shared\Captcha;

class ContactController extends ContactController_parent {
    use Captcha;

    public function send() {
        if (!$this->getCaptcha()->passCaptcha()) {
            return false;
        }
        return parent::send();
    }
}