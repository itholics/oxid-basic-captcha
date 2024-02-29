<?php

declare(strict_types=1);

namespace ITholics\Oxid\BasicCaptcha\Application\Core;

interface RendererInterface {
    public function render(string $emac, string $decryptKey, int $imageWidth = 80, int $imageHeight = 18, int $fontSize = 14): void;
}