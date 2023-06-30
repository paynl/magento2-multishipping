<?php

namespace Paynl\Multishipping\Observer;

use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Multishipping\Model\Checkout\Type\Multishipping\State;

class PaySuccessRedirect implements ObserverInterface
{
     /**
     * @var State
     */
    private $state;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param ResponseFactory $responseFactory
     * @param State $state
     */
    public function __construct(ResponseFactory $responseFactory, State $state)
    {
        $this->responseFactory = $responseFactory;
        $this->state = $state;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (count($observer->getData('order_ids')) === 1) {
            return;
        }

        $this->state->setCompleteStep(State::STEP_OVERVIEW);
        $this->state->setActiveStep(State::STEP_SUCCESS);

        $successUrl = '/multishipping/checkout/success?utm_nooverride=1';
        $response = $this->responseFactory->create();
        $response->setRedirect($successUrl);
        $response->sendResponse();

        exit;
    }
}
