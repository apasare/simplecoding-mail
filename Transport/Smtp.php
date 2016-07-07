<?php

namespace SimpleCoding\Mail\Transport;

use Magento\Store\Model\ScopeInterface;

class Smtp extends \Zend_Mail_Transport_Smtp implements \Magento\Framework\Mail\TransportInterface
{
    /**
     * Mail smtp host
     */
    const XML_PATH_MAIL_SMTP_HOST = 'simplecoding_mail/smtp/host';

    /**
     * Mail smtp port
     */
    const XML_PATH_MAIL_SMTP_PORT = 'simplecoding_mail/smtp/port';

    /**
     * Mail smtp auth
     */
    const XML_PATH_MAIL_SMTP_AUTH = 'simplecoding_mail/smtp/auth';

    /**
     * Mail smtp username
     */
    const XML_PATH_MAIL_SMTP_USERNAME = 'simplecoding_mail/smtp/username';

    /**
     * Mail smtp password
     */
    const XML_PATH_MAIL_SMTP_PASSWORD = 'simplecoding_mail/smtp/password';

    /**
     * Mail smtp ssl
     */
    const XML_PATH_MAIL_SMTP_SSL = 'simplecoding_mail/smtp/ssl';

    /**
     * @var \Magento\Framework\Mail\MessageInterface
     */
    protected $_message;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @param \Magento\Framework\Mail\MessageInterface $message
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @throws \InvalidArgumentException
     */
    public function __construct(
        \Magento\Framework\Mail\MessageInterface $message,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        if (!$message instanceof \Zend_Mail) {
            throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
        }

        $this->_message = $message;
        $this->_scopeConfig = $scopeConfig;
        $this->_date = $date;

        parent::__construct(
            $this->getHostname(),
            $this->getConfig()
        );
    }

    /**
     * @return string $hostname
     */
    protected function getHostname()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_HOST,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array $config
     */
    protected function getConfig()
    {
        $config = [];
        $config['port'] = $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_PORT,
            ScopeInterface::SCOPE_STORE
        );
        $config['auth'] = $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_AUTH,
            ScopeInterface::SCOPE_STORE
        );
        $config['username'] = $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_USERNAME,
            ScopeInterface::SCOPE_STORE
        );
        $config['password'] = $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_PASSWORD,
            ScopeInterface::SCOPE_STORE
        );
        $config['password'] = $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_PASSWORD,
            ScopeInterface::SCOPE_STORE
        );

        $ssl = $this->_scopeConfig->getValue(
            self::XML_PATH_MAIL_SMTP_SSL,
            ScopeInterface::SCOPE_STORE
        );
        if ($ssl) {
            $config['ssl'] = $ssl;
        }

        return $config;
    }

    /**
     * @return void
     */
    protected function prepareMessage()
    {
        // set 'Date:' header to get a better spam score
        // http://wiki.apache.org/spamassassin/Rules/MISSING_DATE
        if (null === $this->_message->getDate()) {
            $this->_message->setDate($this->_date->gmtTimestamp());
        }
    }

    /**
     * Send a mail using this transport
     *
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMessage()
    {
        try {
            $this->prepareMessage();
            parent::send($this->_message);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }
}
