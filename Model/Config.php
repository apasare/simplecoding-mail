<?php

namespace SimpleCoding\Mail\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const DEFAULT_PATH_PATTERN = 'simplecoding_mail/smtp/%s';

    const KEY_HOST = 'host';

    const KEY_PORT = 'port';

    const KEY_AUTH = 'auth';

    const KEY_USERNAME = 'username';

    const KEY_PASSWORD = 'password';

    const KEY_SSL = 'ssl';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param string $pathPattern
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $pathPattern = self::DEFAULT_PATH_PATTERN
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->pathPattern = $pathPattern;
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|null $storeId
     *
     * @return mixed
     */
    public function getValue($field, $storeId = null)
    {
        if ($this->pathPattern === null) {
            return null;
        }

        return $this->scopeConfig->getValue(
            sprintf($this->pathPattern, $field),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve smtp host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->getValue(self::KEY_HOST);
    }

    /**
     * Retrieve smtp port
     *
     * @return string
     */
    public function getPort()
    {
        return $this->getValue(self::KEY_PORT);
    }

    /**
     * Retrieve smtp authentication method
     *
     * @return string
     */
    public function getAuthMethod()
    {
        return $this->getValue(self::KEY_AUTH);
    }

    /**
     * Retrieve smtp username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getValue(self::KEY_USERNAME);
    }

    /**
     * Retrieve smtp password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getValue(self::KEY_PASSWORD);
    }

    /**
     * Retrieve smtp ssl method
     *
     * @return string
     */
    public function getSslMethod()
    {
        return $this->getValue(self::KEY_SSL);
    }
}
