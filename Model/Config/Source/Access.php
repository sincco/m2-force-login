<?php
/**
 * # NOTICE OF LICENSE
 * This work is licensed under a ***Creative Commons Attribution-NonCommercial-
 * NoDerivs 3.0 Unported License*** http://creativecommons.org/licenses/by-nc-nd/3.0
 *
 * ## Authors
 *
 * Iván Miranda @deivanmiranda
 */
namespace Sincco\ForceLogin\Model\Config\Source;

class Access implements \Magento\Framework\Option\ArrayInterface
{


    /**
     * @return array
     */
    public function toOptionArray()
    {
        return
        [
            [
            'value' => 1,
            'label' => __('Via Login and Register'),
            ],                
            [
            'value' => 0,
            'label' => __('Via Login'),
            ],
        ];
    }
}
