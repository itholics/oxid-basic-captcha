<?php

use ITholics\Oxid\BasicCaptcha\Application\Core\Module;
use ITholics\Oxid\BasicCaptcha\Application\Core\Renderer;
use ITholics\Oxid\BasicCaptcha\Application\Core\RendererInterface;
use OxidEsales\Eshop\Core\Registry;

// require dirname(__DIR__, 4) . '/bootstrap.php';

foreach (['/bootstrap.php', '/source/bootstrap.php'] as $s) {
    $s = dirname(__DIR__, 4) . $s;

    try {
        if (file_exists($s)) {
            require $s;

            break;
        }
    } catch (Throwable) {
    }
}

class Handler
{
    public function __construct(
        protected RendererInterface $renderer,
        protected string $emac,
        protected string $decryptKey,
        protected int $imageWidth = 80,
        protected int $imageHeight = 18,
        protected int $fontSize = 16
    ) {}

    public function render(): never
    {
        try {
            $this->renderer->render($this->emac, $this->decryptKey, $this->imageWidth, $this->imageHeight, $this->fontSize);

            exit;
        } catch (Throwable $e) {
            Registry::getLogger()->error(sprintf('%s() | %s', __METHOD__, $e->getMessage()), [$e]);
            http_response_code(400);

            exit('');
        }
    }

}

$handler = new Handler(
    Renderer::getInstance(),
    Registry::getRequest()->getRequestEscapedParameter('e_mac', ""),
    Module::getInstance()->getDecryptKey()
);
$handler->render();
