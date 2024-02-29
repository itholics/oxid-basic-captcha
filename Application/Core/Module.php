<?php

namespace ITholics\Oxid\BasicCaptcha\Application\Core;

use ITholics\Oxid\BasicCaptcha\Application\Shared\Connection;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class Module
{
    use Connection;

    public const ID = 'ith_basic_captcha';
    public const VERSION = '0.1.9';

    protected static ?self $__instance = null;

    public static function getInstance(): static
    {
        return static::$__instance ?? (static::$__instance = oxNew(static::class));
    }

    public static function onActivate(): void
    {
        static::getInstance()->activate();
    }

    public static function onDeactivate(): void
    {
        static::getInstance()->deactivate();
    }

    public function createTable(): void
    {
        $this->getDb()->executeStatement('
            CREATE TABLE IF NOT EXISTS `oecaptcha` ('.
            "`OXID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Captcha id',".
            "`OXHASH` char(32) NOT NULL default '' COMMENT 'Hash',".
            "`OXTIME` int(11) NOT NULL COMMENT 'Validation time',".
            "`OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',".
            'PRIMARY KEY (`OXID`), '.
            'KEY `OXID` (`OXID`,`OXHASH`), '.
            'KEY `OXTIME` (`OXTIME`) '.
            ") ENGINE=MEMORY AUTO_INCREMENT=1 COMMENT 'If session is not available, this is where captcha information is stored';
        ");
    }

    public function dropTable(): void
    {
        $this->getDb()->executeStatement('DROP TABLE IF EXISTS `oecaptcha`;');
    }

    public function activate(): void
    {
        $this->createTable();
    }

    public function deactivate(): void
    {
        $this->dropTable();
    }

    public function getDecryptKey(): string {
        /** @var ModuleSettingServiceInterface $moduleSetting */
        $moduleSetting = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingServiceInterface::class);
        return trim($moduleSetting->getString('oecaptchakey', 'ith_basic_captcha')->toString()) ?: Captcha::ENCRYPT_KEY;
    }

    public function useFast(): bool {
        /** @var ModuleSettingServiceInterface $moduleSetting */
        $moduleSetting = ContainerFactory::getInstance()->getContainer()->get(ModuleSettingServiceInterface::class);
        return $moduleSetting->getBoolean('fastUrl', 'ith_basic_captcha');
    }
}
