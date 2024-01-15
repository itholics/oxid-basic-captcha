<?php
use ITholics\Oxid\BasicCaptcha\Application\Core\Module;

$sMetadataVersion = '2.1';

$sVersion = Module::VERSION;

$aModule = [
    'id'                      => 'ith_basic_captcha',
    'title'                   => [
        'de'                  => "<div style=\"display:flex; align-items: center;\"><img src=\"../out/modules/ith_basic_captcha/thumb.png\" alt=\"ith\" title=\"ITholics\" style=\"height: 15px; margin-right: 5px;\" /> <span><strong>IT</strong>holics - Basic Captcha - {$sVersion}</span></div>",
        'en'                  => "<div style=\"display:flex; align-items: center;\"><img src=\"../out/modules/ith_basic_captcha/thumb.png\" alt=\"ith\" title=\"ITholics\" style=\"height: 15px; margin-right: 5px;\" /> <span><strong>IT</strong>holics - Basic Captcha - {$sVersion}</span></div>"
    ],
    'description'             => [
        'de'                  => 'Einfaches Text Captcha',
        'en'                  => 'Simple text captcha'
    ],
    'thumbnail'               => 'logo.png',
    'version'                 => $sVersion,
    'author'                  => 'ITholics Dev Team',
    'url'                     => 'https://www.itholics.de',
    'email'                   => 'info@itholics.de',
    'controllers'             => [
        'ith_basic_captcha_generator' => ITholics\Oxid\BasicCaptcha\Application\Controller\ImageGeneratorController::class,
    ],
    'templates'               => [
        'ith_basic_captcha.tpl' => 'ith_modules/basic_captcha/Application/views/tpl/ith_basic_captcha.tpl',
    ],
    'extend'                  => [
        OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class => ITholics\Oxid\BasicCaptcha\Application\Controller\DetailsController::class,
        OxidEsales\Eshop\Application\Controller\ContactController::class        => ITholics\Oxid\BasicCaptcha\Application\Controller\ContactController::class,
        OxidEsales\Eshop\Application\Controller\NewsletterController::class     => ITholics\Oxid\BasicCaptcha\Application\Controller\NewsletterController::class,
        OxidEsales\Eshop\Application\Controller\PriceAlarmController::class     => ITholics\Oxid\BasicCaptcha\Application\Controller\PricealarmController::class,
        OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class     => ITholics\Oxid\BasicCaptcha\Application\Component\Widget\ArticleDetails::class,
    ],
    'blocks'                  => [
        ['template' => 'form/contact.tpl', 'block' => 'captcha_form', 'file' => '/Application/views/blocks/ith_basic_captcha_form.tpl'],
        ['template' => 'form/privatesales/invite.tpl', 'block' => 'captcha_form', 'file' => '/Application/views/blocks/ith_basic_captch_form.tpl'],
        ['template' => 'form/pricealarm.tpl', 'block' => 'captcha_form', 'file' => '/Application/views/blocks/ith_basic_captch_form.tpl'],
       // ['template' => 'form/forgotpwd_email.tpl', 'block' => 'captcha_form', 'file' => '/Application/views/blocks/ith_basic_captch_form.tpl']
    ],
    'events'                  => [
        'onActivate'   => Module::class . '::onActivate',
        'onDeactivate' => Module::class . '::onDeactivate',
    ],
    'settings'                => [
        [
            'group' => 'main',
            'name'  => 'oecaptchakey',
            'type'  => 'str',
            'value' => '',
        ]
    ],
    'smartyPluginDirectories' => []
];
