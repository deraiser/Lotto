<?php

namespace wcf\system\user\notification\object;

use wcf\data\DatabaseObjectDecorator;
use wcf\data\lotto\ticket\LottoTicket;
use wcf\system\request\LinkHandler;

/**
 * Represents a lotto notification object.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\User\Notification\Object
 *
 * @method  LottoTicket     getDecoratedObject()
 * @mixin   LottoTicket
 */
class LottoNotificationObject extends DatabaseObjectDecorator implements IUserNotificationObject {

    /**
     * @inheritDoc
     */
    protected static $baseClass = LottoTicket::class;

    /**
     * @inheritDoc
     */
    public function getAuthorID() {
        return $this->userID;
    }

    /**
     * @inheritDoc
     */
    public function getTitle() {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getURL() {
        return LinkHandler::getInstance()->getLink('LottoTicket', [
                    'id' => $this->ticketID
        ]);
    }

}