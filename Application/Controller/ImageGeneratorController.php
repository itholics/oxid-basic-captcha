<?php

namespace ITholics\Oxid\BasicCaptcha\Application\Controller;

use ITholics\Oxid\BasicCaptcha\Application\Core\Captcha;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;

class ImageGeneratorController extends FrontendController
{
    protected $emac;
    protected $imageHeight = 18;
    protected $imageWidth = 80;
    protected $fontSize = 14;

    public function init()
    {
        parent::init();
        $this->emac = Registry::getRequest()->getRequestEscapedParameter('e_mac', null);
        if ($this->emac) {
            $this->emac = $this->decodeEmac($this->emac);
        }
    }

    protected function decodeEmac(string $emac): string
    {
        $decryptor = new \OxidEsales\Eshop\Core\Decryptor();
        $config = Registry::getConfig();

        $key = $config->getConfigParam('oecaptchakey');
        if (empty($key)) {
            $key = Captcha::ENCRYPT_KEY;
        }

        return $decryptor->decrypt($emac, $key);
    }

    public function render()
    {
        parent::render();
        try {
            if (!$this->emac) {
                http_response_code(400);
                exit('');
            }
            $image = $this->generateVerificationImage();
            if (!$image) {
                throw new StandardException('Image generation failed by returning NULL');
            }
            header('Content-type: image/png');
            imagepng($image);
            imagedestroy($image);
            exit;
        } catch (\Throwable $e) {
            Registry::getLogger()->error(sprintf('%s() | %s', __METHOD__, $e->getMessage()), [$e]);
            http_response_code(400);
            exit('');
        }
    }

    protected function generateVerificationImage()
    {
        $image = null;
        switch (true) {
            case function_exists('imagecreatetruecolor'):
                $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
                break;
            case function_exists('imagecreate'):
                $image = imagecreate($this->imageWidth, $this->imageHeight);
                break;
            default:
                return null;
        }
        $textX = ($this->imageWidth - strlen($this->emac) * imagefontwidth($this->fontSize)) / 2;
        $textY = ($this->imageHeight - imagefontheight($this->fontSize)) / 2;

        $colors = [
            'text' => imagecolorallocate($image, 0, 0, 0),
            'shadow1' => imagecolorallocate($image, 200, 200, 200),
            'shadow2' => imagecolorallocate($image, 100, 100, 100),
            'background' => imagecolorallocate($image, 255, 255, 255),
            'border' => imagecolorallocate($image, 0, 0, 0),
        ];

        imagefill($image, 0, 0, $colors['background']);
        imagerectangle($image, 0, 0, $this->imageWidth - 1, $this->imageHeight - 1, $colors['border']);
        imagestring($image, $this->fontSize, $textX + 1, $textY + 0, $this->emac, $colors['shadow2']);
        imagestring($image, $this->fontSize, $textX + 0, $textY + 1, $this->emac, $colors['shadow1']);
        imagestring($image, $this->fontSize, $textX, $textY, $this->emac, $colors['text']);

        return $image;
    }
}
