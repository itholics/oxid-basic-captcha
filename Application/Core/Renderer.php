<?php

declare(strict_types=1);

namespace ITholics\Oxid\BasicCaptcha\Application\Core;

use InvalidArgumentException;
use OxidEsales\Eshop\Core\Decryptor;
use RuntimeException;

class Renderer implements RendererInterface
{
    private static $instance;

    public static function getInstance(): static
    {
        return static::$instance ??= oxNew(self::class);
    }

    public function render(string $emac, string $decryptKey, int $imageWidth = 80, int $imageHeight = 18, int $fontSize = 14): void
    {
        $emac = trim($emac);
        if (!$emac) {
            throw new InvalidArgumentException(sprintf("%s() > 'Empty emac'", __METHOD__));
        }
        if (!$decryptKey) {
            throw new InvalidArgumentException(sprintf("%s() > 'Empty decryptKey'", __METHOD__));
        }
        $emac  = $this->decodeEmac($emac, $decryptKey);
        $image = $this->generateVerificationImage($emac, $imageWidth, $imageHeight, $fontSize);
        if (!$image) {
            throw new RuntimeException(sprintf("%s() > 'Image generation failed by returning NULL'", __METHOD__));
        }
        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    protected function decodeEmac(string $emac, string $decryptKey): string
    {
        $decryptor = new Decryptor();

        return $decryptor->decrypt($emac, $decryptKey);
    }

    protected function generateVerificationImage(string $emac, int $imageWidth = 80, int $imageHeight = 18, int $fontSize = 14)
    {
        $image = null;

        switch (true) {
            case function_exists('imagecreatetruecolor'):
                $image = imagecreatetruecolor($imageWidth, $imageHeight);

                break;

            case function_exists('imagecreate'):
                $image = imagecreate($imageWidth, $imageHeight);

                break;

            default:
                return null;
        }
        $textX = (int) (($imageWidth - strlen($emac) * imagefontwidth($fontSize)) / 2);
        $textY = (int) (($imageHeight - imagefontheight($fontSize)) / 2);

        $colors = [
            'text'       => imagecolorallocate($image, 0, 0, 0),
            'shadow1'    => imagecolorallocate($image, 200, 200, 200),
            'shadow2'    => imagecolorallocate($image, 100, 100, 100),
            'background' => imagecolorallocate($image, 255, 255, 255),
            'border'     => imagecolorallocate($image, 0, 0, 0),
        ];

        imagefill($image, 0, 0, $colors['background']);
        imagerectangle($image, 0, 0, $imageWidth - 1, $imageHeight - 1, $colors['border']);
        imagestring($image, $fontSize, $textX + 1, $textY + 0, $emac, $colors['shadow2']);
        imagestring($image, $fontSize, $textX + 0, $textY + 1, $emac, $colors['shadow1']);
        imagestring($image, $fontSize, $textX, $textY, $emac, $colors['text']);

        return $image;
    }
}
