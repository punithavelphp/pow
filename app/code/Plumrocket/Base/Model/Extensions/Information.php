<?php
/**
 * @package     Plumrocket_Base
 * @copyright   Copyright (c) 2020 Plumrocket Inc. (https://plumrocket.com)
 * @license     https://plumrocket.com/license/  End-user License Agreement
 */

declare(strict_types=1);

namespace Plumrocket\Base\Model\Extensions;

use Plumrocket\Base\Api\Data\ExtensionInformationInterface;
use Plumrocket\Base\Api\GetModuleVersionInterface;
use Plumrocket\Base\Api\ModuleInformationInterface;

/**
 * @since 2.3.0
 * @deprecated since 2.5.0 - it is better to use "pr_extensions.xml" than this class with constants
 */
class Information implements ModuleInformationInterface
{
    const IS_SERVICE = true;
    const NAME = 'Base';
    const WIKI = '';
    const CONFIG_SECTION = 'plumbase';

    /**
     * Path to config that define if module enabled,
     * for modules without configs left this constant empty
     */
    const IS_ENABLED_FIELD_CONFIG_PATH = '';
    const MODULE_NAME = 'Base';
    const VENDOR_NAME = 'Plumrocket';

    /**
     * @var \Plumrocket\Base\Api\GetModuleVersionInterface
     */
    private $getModuleVersion;

    /**
     * ModuleInformation constructor.
     *
     * @param \Plumrocket\Base\Api\GetModuleVersionInterface $getModuleVersion
     */
    public function __construct(GetModuleVersionInterface $getModuleVersion)
    {
        $this->getModuleVersion = $getModuleVersion;
    }

    /**
     * @inheritDoc
     */
    public function isService(): bool
    {
        return static::IS_SERVICE;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getOfficialName(): string
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getWikiLink(): string
    {
        return static::WIKI;
    }

    /**
     * @inheritDoc
     */
    public function getConfigSection(): string
    {
        return static::CONFIG_SECTION;
    }

    /**
     * @inheritDoc
     */
    public function getIsEnabledFieldConfigPath(): string
    {
        return static::IS_ENABLED_FIELD_CONFIG_PATH;
    }

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return static::MODULE_NAME;
    }

    /**
     * @inheritDoc
     */
    public function getVendorAndModuleName(): string
    {
        return static::VENDOR_NAME . '_' . $this->getModuleName();
    }

    /**
     * @inheritDoc
     */
    public function getInstalledVersion(): string
    {
        return $this->getModuleVersion->execute($this->getVendorAndModuleName());
    }

    public function setIsService(bool $isService): ExtensionInformationInterface
    {
        return $this;
    }

    public function setTitle(string $title): ExtensionInformationInterface
    {
        return $this;
    }

    public function setConfigSection(string $configSection): ExtensionInformationInterface
    {
        return $this;
    }

    public function setWikiLink(string $wikiLink): ExtensionInformationInterface
    {
        return $this;
    }

    public function setModuleName(string $moduleName): ExtensionInformationInterface
    {
        return $this;
    }

    public function setIsEnabledFieldConfigPath(string $configPath): ExtensionInformationInterface
    {
        return $this;
    }
}
