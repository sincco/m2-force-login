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
namespace Sincco\ForceLogin\Model\Overrides;

class Registration extends \Magento\Customer\Model\Registration
{

    private $scopeConfig;


    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isAllowed()
    {
        $forced_login_status = $this->scopeConfig->getValue(
            'forcelogin/parameters/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $forced_login_access = $this->scopeConfig->getValue(
            'forcelogin/parameters/access_to_website',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($forced_login_access == '0' && $forced_login_status == '1') {
            return false;
        }

        return true;
    }
}
