<?php

namespace wcf\system\user\notification\object\type;

use wcf\data\lotto\ticket\LottoTicket;
use wcf\data\lotto\ticket\LottoTicketList;
use wcf\system\user\notification\object\LottoNotificationObject;

/**
 * Represents a lotto notification object type.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\User\Notification\Object\Type
 */
class LottoNotificationObjectType extends AbstractUserNotificationObjectType {

    /**
     * @inheritDoc
     */
    protected static $decoratorClassName = LottoNotificationObject::class;

    /**
     * @inheritDoc
     */
    protected static $objectClassName = LottoTicket::class;

    /**
     * @inheritDoc
     */
    protected static $objectListClassName = LottoTicketList::class;

}