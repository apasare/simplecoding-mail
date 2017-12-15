<?php

namespace SimpleCoding\Mail\Transport;

class Smtp extends \Zend_Mail_Transport_Smtp implements \Magento\Framework\Mail\TransportInterface
{
    /**
     * @var \Magento\Framework\Mail\MessageInterface
     */
    protected $_message;

    /**
     * @var \SimpleCoding\Mail\Model\Config
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @param \Magento\Framework\Mail\MessageInterface $message
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \SimpleCoding\Mail\Model\Config $scopeConfig
     * @throws \InvalidArgumentException
     */
    public function __construct(
        \Magento\Framework\Mail\MessageInterface $message,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \SimpleCoding\Mail\Model\Config $scopeConfig
    ) {
        if (!$message instanceof \Zend_Mail) {
            throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
        }

        $this->_message = $message;
        $this->_date = $date;
        $this->_config = $scopeConfig;

        parent::__construct(
            $this->_config->getHost(),
            $this->getConfig()
        );
    }

    /**
     * @return array $config
     */
    protected function getConfig()
    {
        $config = [
            'port' => $this->_config->getPort(),
            'auth' => $this->_config->getAuthMethod(),
            'username' => $this->_config->getUsername(),
            'password' => $this->_config->getPassword(),
        ];

        $ssl = $this->_config->getSslMethod();
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
