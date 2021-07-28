<?php

namespace wcf\system\user\notification\event;

use wcf\system\request\LinkHandler;

/**
 * Notification event for win a user in lotto. 
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\User\Notification\Object\Type
 */
class LottoWinNotificationEvent extends AbstractUserNotificationEvent {

    /**
     * @inheritDoc
     */
    public function getEmailMessage($notificationType = 'instant') {
        return $this->getLanguage()->getDynamicVariable('wcf.user.notification.lottoWin.mail', [
                    'lottoTicket' => $this->userNotificationObject
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getLink() {
        return LinkHandler::getInstance()->getLink('LottoTicket', [
                    'id' => $this->userNotificationObject->ticketID
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getMessage() {
        return $this->getLanguage()->getDynamicVariable('wcf.user.notification.lottoWin.message', [
                    'lottoTicket' => $this->userNotificationObject
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle() {
        return $this->getLanguage()->get('wcf.user.notification.lottoWin.title');
    }

}