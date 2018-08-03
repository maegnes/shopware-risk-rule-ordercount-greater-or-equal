<?php

namespace OrderCountGreaterThanRiskRule\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

class CustomRule implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Modules_Admin_Execute_Risk_Rule_sRiskOrderCountGreaterThanRiskRule' => 'onMyCustomRule'
        ];
    }

    /**
     * Checks if the customer has x or more orders in his history
     *
     * @param Enlight_Event_EventArgs $args
     * @return bool
     */
    public function onMyCustomRule(Enlight_Event_EventArgs $args)
    {

        $session = Shopware()->Container()->get('session');
        $value = $args->get('value');

        if ($session->offsetGet('sUserId')) {
            $checkOrder = Shopware()->Db()->fetchAll(
                'SELECT id FROM s_order
                  WHERE status != -1 AND status != 4 AND userID = ?',
                [$session->offsetGet('sUserId')]
            );

            return count($checkOrder) >= $value;
        }

        return false;
    }
}
