<?php
/**
 *                       ######
 *                       ######
 * ############    ####( ######  #####. ######  ############   ############
 * #############  #####( ######  #####. ######  #############  #############
 *        ######  #####( ######  #####. ######  #####  ######  #####  ######
 * ###### ######  #####( ######  #####. ######  #####  #####   #####  ######
 * ###### ######  #####( ######  #####. ######  #####          #####  ######
 * #############  #############  #############  #############  #####  ######
 *  ############   ############  #############   ############  #####  ######
 *                                      ######
 *                               #############
 *                               ############
 *
 * Adyen Payment module (https://www.adyen.com/)
 *
 * Copyright (c) 2021 Adyen NV (https://www.adyen.com/)
 * See LICENSE.txt for license details.
 *
 * Author: Adyen <magento@adyen.com>
 */

namespace Adyen\Payment\Cron;

use Adyen\Payment\Helper\StateData;
use Adyen\Payment\Logger\AdyenLogger;
use Adyen\Payment\Model\ResourceModel\Order\Order;

class ClearAdyenStateData
{
    const TIME_RANGE = '-10 minutes';
    /**
     * @var AdyenLogger
     */
    private $adyenLogger;

    /**
     * @var Order
     */
    private $orderResourceModel;

    /**
     * @var StateData
     */
    private $stateDataHelper;

    /**
     * @param AdyenLogger $adyenLogger
     * @param Order $orderResourceModel
     * @param StateData $stateDataHelper
     */
    public function __construct(
        AdyenLogger $adyenLogger,
        Order $orderResourceModel,
        StateData $stateDataHelper
    ) {
        $this->adyenLogger = $adyenLogger;
        $this->orderResourceModel = $orderResourceModel;
        $this->stateDataHelper = $stateDataHelper;
    }

    public function execute()
    {
        $timeStart = new \DateTime();
        $timeStart->modify(self::TIME_RANGE);
        $orders = $this->orderResourceModel->getCompletedOrdersUpdatedSince($timeStart);
        foreach ($orders as $order) {
            $this->stateDataHelper->CleanQuoteStateData($order['quoteId'], $order['adyen_resulturl_event_code']);
        }
    }
}
