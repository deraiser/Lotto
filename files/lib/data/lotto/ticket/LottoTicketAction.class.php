<?php

namespace wcf\data\lotto\ticket;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\WCF;

/**
 * Executes lotto ticket related actions.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Ticket
 * 
 * @method LottoTicketEditor[] getObjects()
 * @method LottoTicketEditor getSingleObject()
 */
class LottoTicketAction extends AbstractDatabaseObjectAction {

    /**
     * @inheritDoc
     */
    protected $className = LottoTicketEditor::class;

    /**
     * @inheritDoc
     * 
     * @return	LottoTicket
     */
    public function create() {
        /** @var LottoTicket $ticket */
        $ticket = parent::create();
        $ticketEditor = new LottoTicketEditor($ticket);

        $ticketEditor->insertFields($this->parameters['tickets']);

        UserActivityEventHandler::getInstance()->fireEvent('info.daries.lotto.recentActivityEvent.lottoAdd', $ticket->ticketID);

        return $ticket;
    }

    public function delete() {
        if (empty($this->objects)) {
            $this->readObjects();
        }

        $ticketIDs = [];
        foreach ($this->getObjects() as $ticket) {
            $ticket->delete();

            $ticketIDs[] = $ticket->ticketID;
        }

        if (!empty($ticketIDs)) {
            // remove notifications
            UserNotificationHandler::getInstance()->removeNotifications('info.daries.lotto.notification', $ticketIDs);
        }
    }

    /**
     * All the lottery tickets played by the user will be deleted.
     */
    public function removeAll() {
        $ticketList = new LottoTicketList();
        $ticketList->getConditionBuilder()->add('lotto_ticket.userID = ?', [WCF::getUser()->userID]);
        $ticketList->getConditionBuilder()->add('lotto_ticket.drawID IS NOT NULL');
        $ticketList->readObjectIDs();
        $ticketIDs = $ticketList->getObjectIDs();

        if (!empty($ticketIDs)) {
            $ticketAction = new LottoTicketAction($ticketIDs, 'delete');
            $ticketAction->executeAction();
        }
    }

    /**
     * Validates the "removeAll" action.
     */
    public function validateRemoveAll() {
        
    }

}