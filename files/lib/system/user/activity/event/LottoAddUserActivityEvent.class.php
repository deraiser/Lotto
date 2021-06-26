<?php

namespace wcf\system\user\activity\event;

use wcf\data\lotto\ticket\LottoTicketList;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * User activity event implementation for responses to lotto add.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\User\Activity\Event
 */
class LottoAddUserActivityEvent extends SingletonFactory implements IUserActivityEvent {

    /**
     * @inheritDoc
     */
    public function prepare(array $events) {
        $objectIDs = [];
        foreach ($events as $event) {
            $objectIDs[] = $event->objectID;
        }

        $ticketList = new LottoTicketList();
        $ticketList->getConditionBuilder()->add("lotto_ticket.ticketID IN (?)", array($objectIDs));
        $ticketList->readObjects();
        $tickets = $ticketList->getObjects();

        foreach ($events as $event) {
            if (isset($tickets[$event->objectID])) {
                $event->setIsAccessible();

                $text = WCF::getLanguage()->get('wcf.user.profile.recentActivity.lottoAdd');
                $event->setTitle($text);
            } else {
                $event->setIsOrphaned();
            }
        }
    }

}