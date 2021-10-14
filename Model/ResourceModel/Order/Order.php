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
 * Copyright (c) 2021 Adyen BV (https://www.adyen.com/)
 * See LICENSE.txt for license details.
 *
 * Author: Adyen <magento@adyen.com>
 */

namespace Adyen\Payment\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Sales\Model\Order as ModelOrder;

class Order extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sales_order', 'entity_id');
    }

    /**
     * Get all the completed orders that have been updated between now and the passed date
     *
     * @param \DateTime $dateTime
     * @return array|null
     */
    public function getCompletedOrdersUpdatedSince(\DateTime $dateTime): ?array
    {
        $select = $this->getConnection()->select()
            ->from(['order' => $this->getTable('sales_order')])
            ->where('order.updated=?', $dateTime)
            ->where('order.state IN (?)', [
                ModelOrder::STATE_PAYMENT_REVIEW,
                ModelOrder::STATE_COMPLETE,
                ModelOrder::STATE_CLOSED
            ]);

        $result = $this->getConnection()->fetchAll($select);

        return empty($result) ? null : $result;
    }
}