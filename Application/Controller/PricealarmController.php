<?php

namespace ITholics\Oxid\BasicCaptcha\Application\Controller;
use ITholics\Oxid\BasicCaptcha\Application\Shared\Captcha;

class PricealarmController extends PricealarmController_parent {
    use Captcha;

    public function addme() {
        if (!$this->getCaptcha()->passCaptcha(false)) {
            $this->_iPriceAlarmStatus = 2;
            return;
        }
        return parent::addme();
    }
}