<?php

namespace wcf\form;

use wcf\data\lotto\draw\LottoDraw;
use wcf\data\lotto\draw\LottoDrawAction;
use wcf\data\lotto\ticket\LottoTicketAction;
use wcf\data\lotto\ticket\LottoTicketList;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\HeaderUtil;

/**
 * Shows the lotto ticket add form.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Form
 */
class LottoTicketAddForm extends AbstractForm {

    /**
     * @inheritDoc
     */
    public $loginRequired = true;

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_LOTTO', 'MODULE_JCOINS'];

    /**
     * ticket
     * @var array
     */
    public $tickets = [];

    /**
     * create a new ticket number
     * 
     * @return string new ticket number
     */
    protected function createTicketNumber() {
        $newTicketNumber = '';

        while (strlen($newTicketNumber) < 10) {
            $rnd = rand(0, 9);
            $newTicketNumber .= $rnd;
        }

        $lottoTicketsList = new LottoTicketList();
        $lottoTicketsList->getConditionBuilder()->add('ticketNumber = ?', [$newTicketNumber]);
        $count = $lottoTicketsList->countObjects();

        if ($count) {
            $newTicketNumber = $this->createTicketNumber();
        }

        return $newTicketNumber;
    }

    /**
     * @inheritDoc
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if (isset($_POST['tickets']) && is_array($_POST['tickets'])) $this->tickets = ArrayUtil::toIntegerArray($_POST['tickets']);
    }

    /**
     * @inheritDoc
     */
    public function save() {
        $coins = 0;
        if (count($this->tickets) == 1) {
            $coins = JCOINS_LOTTOADD;
        } else {
            $coins = ((count($this->tickets) - 1) * JCOINS_LOTTOADDADDITIONALFIELD) + JCOINS_LOTTOADD;
        }

        $data = [
            'userID' => WCF::getUser()->userID,
            'ticketNumber' => $this->createTicketNumber(),
            'created' => TIME_NOW,
            'costCoins' => $coins
        ];

        $this->objectAction = new LottoTicketAction([], 'create', [
            'data' => $data,
            'tickets' => $this->tickets
        ]);
        $this->objectAction->executeAction();

        $this->updateUserCoins($coins);

        $draw = LottoDraw::getLastDraw();
        $newJackpot = floor($draw->jackpot + ($coins * LOTTO_TICKET_TO_JACKPOT / 100));
        $objectAction = new LottoDrawAction([$draw->drawID], 'update', ['data' => [
                'jackpot' => $newJackpot
        ]]);
        $objectAction->executeAction();

        $this->saved();
        
        if (isset($_POST['nextTicket'])) {
             HeaderUtil::redirect(LinkHandler::getInstance()->getLink('LottoTicketAdd'));
        } else {
            HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Lotto'));
        }
        exit;
    }

    protected function updateUserCoins($coins) {
        UserJCoinsStatementHandler::getInstance()->create('info.daries.lotto.transfer', $this->objectAction->getReturnValues()['returnValues'], [
            'fields' => count($this->tickets),
            'amount' => $coins,
            'type' => 'buy',
            'userID' => WCF::getUser()->userID
        ]);
    }

    /**
     * @inheritDoc
     */
    public function validate() {
        parent::validate();

        if (empty($this->tickets)) {
            throw new UserInputException('tickets');
        }
    }

}