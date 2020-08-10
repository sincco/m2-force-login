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
namespace Sincco\ForceLogin\Observer;

use Magento\Framework\Event\ObserverInterface;

class ForceCustomerLoginObserver implements ObserverInterface
{

    private $scopeConfig;

    private $customerSession;

    private $customerUrl;

    private $context;

    private $contextHttp;


    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $contextHttp,
        \Magento\Customer\Model\Url $customerUrl
    ) {
        $this->scopeConfig     = $scopeConfig;
        $this->context         = $context;
        $this->customerSession = $customerSession;
        $this->customerUrl     = $customerUrl;
        $this->contextHttp     = $contextHttp;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event_name = $observer->getEventName();

        $forced_login_status = $this->scopeConfig->getValue(
            'forcelogin/parameters/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $forced_login_access = $this->scopeConfig->getValue(
            'forcelogin/parameters/access_to_website',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($forced_login_status) {
            $module_name     = $this->context->getRequest()->getModuleName();
            $controller_name = $this->context->getRequest()->getControllerName();
            $action_name     = $this->context->getRequest()->getActionName();

            $isLoggedIn = $this->contextHttp->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

            if ($isLoggedIn || $module_name === 'api') {
                return $this;
            }

            if ($controller_name === 'account' && $forced_login_access === '1') {
                return $this;
            }

            if ($forced_login_access === '0' && $controller_name === 'account'
                && ($action_name === 'login' || $action_name === 'loginPost'
                || $action_name === 'forgotpassword'
                || $action_name === 'createpassword'                )
            ) {
                return $this;
            }

            $customer_login_url = $this->customerUrl->getLoginUrl();
            $this->context->getResponse()->setRedirect($customer_login_url);
        }

        return $this;
    }
}
