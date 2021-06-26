<?php

namespace wcf\page;

use wcf\data\lotto\ticket\LottoTicket;
use wcf\data\lotto\ticket\LottoTicketList;
use wcf\system\cache\runtime\LottoDrawRuntimeCache;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Shows an lotto ticket page.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Page
 */
class LottoTicketPage extends AbstractPage {

    /**
     * additional number of current ticket draw
     *
     * @var integer
     */
    public $additionalNumber = 0;

    /**
     * list of draws of current ticket draw
     *
     * @var integer[]
     */
    public $draws = [];

    /**
     * list of fields of current ticket
     *
     * @var integer[][]
     */
    public $fields = [];

    /**
     * @inheritDoc
     */
    public $loginRequired = true;

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_LOTTO'];

    /**
     * next ticket
     * @var LottoTicket
     */
    public $nextTicket;

    /**
     * previous ticket
     * @var LottoTicket
     */
    public $previousTicket;

    /**
     * ticket object
     * @var LottoTicket
     */
    public $ticket;

    /**
     * ticket id
     * @var integer
     */
    public $ticketID = 0;

    /**
     * win class data
     *
     * @var array
     */
    public $winClassData = [];

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'additionalNumber' => $this->additionalNumber,
            'draws' => $this->draws,
            'fields' => $this->fields,
            'nextTicket' => $this->nextTicket,
            'previousTicket' => $this->previousTicket,
            'ticket' => $this->ticket,
            'ticketID' => $this->ticketID,
            'winClassData' => $this->winClassData
        ]);
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $fieldDatas = $this->ticket->getFields();
        foreach ($fieldDatas as $fieldData) {
            $this->fields[$fieldData['fieldID']][] = $fieldData['number1'];
            $this->fields[$fieldData['fieldID']][] = $fieldData['number2'];
            $this->fields[$fieldData['fieldID']][] = $fieldData['number3'];
            $this->fields[$fieldData['fieldID']][] = $fieldData['number4'];
            $this->fields[$fieldData['fieldID']][] = $fieldData['number5'];
            $this->fields[$fieldData['fieldID']][] = $fieldData['number6'];
        }

        if ($this->ticket->drawID) {
            $draw = LottoDrawRuntimeCache::getInstance()->getObject($this->ticket->drawID);
            if ($draw !== null) {
                $this->additionalNumber = $draw->additionalNumber;
                $this->winClassData = $draw->getWinClassData();

                $this->draws = [
                    $draw->number1,
                    $draw->number2,
                    $draw->number3,
                    $draw->number4,
                    $draw->number5,
                    $draw->number6
                ];
            }
        }

        // get next ticket
        $ticketList = new LottoTicketList();
        $ticketList->getConditionBuilder()->add('lotto_ticket.created > ?', [$this->ticket->created]);
        $ticketList->getConditionBuilder()->add('lotto_ticket.userID = ?', [WCF::getUser()->userID]);
        $ticketList->sqlOrderBy = 'lotto_ticket.created ASC';
        $ticketList->sqlLimit = 1;
        $ticketList->readObjects();
        foreach ($ticketList as $ticket) $this->nextTicket = $ticket;

        // get previous $ticket
        $ticketList = new LottoTicketList();
        $ticketList->getConditionBuilder()->add('lotto_ticket.created < ?', [$this->ticket->created]);
        $ticketList->getConditionBuilder()->add('lotto_ticket.userID = ?', [WCF::getUser()->userID]);
        $ticketList->sqlOrderBy = 'lotto_ticket.created DESC';
        $ticketList->sqlLimit = 1;
        $ticketList->readObjects();
        foreach ($ticketList as $ticket) $this->previousTicket = $ticket;
    }

    /**
     * @inheritDoc
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->ticketID = intval($_REQUEST['id']);

        $this->ticket = new LottoTicket($this->ticketID);
        if (!$this->ticket->ticketID || !$this->ticket->userID == WCF::getUser()->userID) {
            throw new IllegalLinkException();
        }
    }

}