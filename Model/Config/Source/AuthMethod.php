<?php

namespace SimpleCoding\Mail\Model\Config\Source;

class AuthMethod implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('None')],
            ['value' => 'plain', 'label' => 'PLAIN'],
            ['value' => 'login', 'label' => 'LOGIN'],
            ['value' => 'crammd5', 'label' => 'CRAM-MD5'],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}
