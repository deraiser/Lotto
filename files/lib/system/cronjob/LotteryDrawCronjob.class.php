<?php

namespace wcf\system\cronjob;

use wcf\data\cronjob\Cronjob;
use wcf\data\lotto\draw\LottoDraw;
use wcf\data\lotto\draw\LottoDrawAction;
use wcf\data\lotto\stat\LottoStat;
use wcf\data\lotto\stat\LottoStatAction;
use wcf\data\lotto\ticket\LottoTicket;
use wcf\data\lotto\ticket\LottoTicketAction;
use wcf\data\lotto\ticket\LottoTicketList;
use wcf\system\cache\builder\LottoNextDrawCacheBuilder;
use wcf\system\event\EventHandler;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\user\notification\object\LottoNotificationObject;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\WCF;
use wcf\util\MathUtil;

/**
 * Cronjob for a lotto draw.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Cronjob
 */
class LotteryDrawCronjob extends AbstractCronjob {

    /**
     * additional number of currently draw
     * @var integer
     */
    protected $additionalNumber = 0;

    /**
     * current draw object
     * @var LottoDraw
     */
    public $draw;

    /**
     * number of currently draw
     * @var integer[]
     */
    protected $drawNumbers = [];

    /**
     * list fields from lotto tickets
     * @var array
     */
    protected $fields = [];

    /**
     * list with current and new jackpot
     * @var integer
     */
    protected $jackpot = 0;

    /**
     * list of lose tickets
     * @var integer[]
     */
    protected $loseTickets = [];

    /**
     * list of lotto tickets
     * @var LottoTicket[]
     */
    protected $tickets = [];

    /**
     * list of winclass with the tickets of the won
     * @var array
     */
    protected $winClass = [
        1 => [],
        2 => [],
        3 => [],
        4 => [],
        5 => [],
        6 => [],
        7 => [],
        8 => [],
        9 => []
    ];

    /**
     * create the next draw
     */
    protected function createNextDraw() {
        if (LOTTO_JACKPOT_RESET) $this->jackpot = LOTTO_NEW_JACKPOT;
        
        $objectAction = new LottoDrawAction([], 'create', ['data' => [
                'drawTime' => strtotime('next ' . LOTTO_PLAY_DATE . ' ' . TIMEZONE) + LOTTO_PLAY_TIME * 3600,
                'jackpot' => $this->jackpot
        ]]);
        $objectAction->executeAction();
    }

    /**
     * All tickets will be checked to see if they have won
     */
    protected function draw() {
        foreach ($this->tickets as $ticket) {
            if (!isset($this->fields[$ticket->ticketID])) continue;

            $additional = false;
            $ticketLastNumber = intval(substr($ticket->ticketNumber, -1, 1));
            if ($ticketLastNumber === $this->additionalNumber) $additional = true;

            $hit = [];
            $winTicket = false;
            foreach ($this->fields[$ticket->ticketID] as $fieldID => $fieldData) {
                foreach ($fieldData as $number) {
                    if (in_array($number, $this->drawNumbers)) {
                        $hit[$fieldID][] = $number;
                    }
                }

                if (isset($hit[$fieldID]) && (count($hit[$fieldID]) >= 2 && count($hit[$fieldID]) < 6)) {
                    $ticketField = $ticket->ticketID . '.' . $fieldID;
                    switch (count($hit[$fieldID])) {
                        case 2:
                            if ($additional) {
                                $this->winClass[9][] = $ticketField;

                                $winTicket = true;
                            }
                            break;
                        case 3:
                            if ($additional) $this->winClass[7][] = $ticketField;
                            else $this->winClass[8][] = $ticketField;

                            $winTicket = true;
                            break;
                        case 4:
                            if ($additional) $this->winClass[5][] = $ticketField;
                            else $this->winClass[6][] = $ticketField;

                            $winTicket = true;
                            break;
                        case 5:
                            if ($additional) $this->winClass[3][] = $ticketField;
                            else $this->winClass[4][] = $ticketField;

                            $winTicket = true;
                            break;
                        case 6:
                            if ($additional) $this->winClass[1][] = $ticketField;
                            else $this->winClass[2][] = $ticketField;

                            $winTicket = true;
                            break;
                    }
                }
            }

            if (!$winTicket) {
                $this->loseTickets[$ticket->ticketID] = $ticket->userID;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function execute(Cronjob $cronjob) {
        parent::execute($cronjob);

        if (!MODULE_LOTTO || !MODULE_JCOINS) return;
        if (!LOTTO_DRAW_ACTIVATE) return;

        if (LottoDraw::checkActiveDraw()) return;

        $this->draw = LottoDraw::getLastDraw();
        if (!$this->draw->drawID) {
            $this->createNextDraw();
            return;
        }

        EventHandler::getInstance()->fireAction($this, 'before');

        $this->loadTickets();
        $this->newDraw();
        $this->draw();
        $this->matchs();
        $this->updateStat();

        $objectAction = new LottoDrawAction([$this->draw->drawID], 'update', ['data' => [
                'drawn' => 1
        ]]);
        $objectAction->executeAction();

        $objectAction = new LottoTicketAction($this->tickets, 'update', ['data' => [
                'drawID' => $this->draw->drawID
        ]]);
        $objectAction->executeAction();

        $this->createNextDraw();

        $this->resetCache();

        EventHandler::getInstance()->fireAction($this, 'after');
    }

    /**
     * All tickets are loaded and the list with the field
     */
    protected function loadTickets() {
        $ticketList = new LottoTicketList();
        $ticketList->getConditionBuilder()->add('lotto_ticket.drawID IS NULL');
        $ticketList->readObjects();
        $this->tickets = $ticketList->getObjects();

        foreach ($this->tickets as $ticket) {
            foreach ($ticket->getFields() as $field) {
                $this->fields[$field['ticketID']][$field['fieldID']][] = $field['number1'];
                $this->fields[$field['ticketID']][$field['fieldID']][] = $field['number2'];
                $this->fields[$field['ticketID']][$field['fieldID']][] = $field['number3'];
                $this->fields[$field['ticketID']][$field['fieldID']][] = $field['number4'];
                $this->fields[$field['ticketID']][$field['fieldID']][] = $field['number5'];
                $this->fields[$field['ticketID']][$field['fieldID']][] = $field['number6'];
            }
        }
    }

    /**
     * Win classes, notify winning users and pay out winnings
     */
    protected function matchs() {
        $messageUser = [];
        $winCoins = $this->readWinCoins();

        $sql = "UPDATE  wcf" . WCF_N . "_lotto_ticket_field
                SET     winCoins = ?
                WHERE   ticketID = ?
                    AND fieldID = ?";
        $updateLottoField = WCF::getDB()->prepareStatement($sql);

        $sql = "INSERT INTO     wcf" . WCF_N . "_lotto_draw_winclass
                                (drawID, winClass, pro, counts)
                VALUES          (?, ?, ?, ?)";
        $insertDrawWinClass = WCF::getDB()->prepareStatement($sql);

        for ($i = 1; $i <= 9; $i++) {
            $pro = $winCoins[$i];

            if (count($this->winClass[$i]) > 0) {
                if ($i != 9) $pro = floor($winCoins[$i] / count($this->winClass[$i]));

                foreach ($this->winClass[$i] as $value) {
                    $ex = explode(".", $value);
                    $ticketID = $ex[0];
                    $fieldID = $ex[1];

                    $userID = $this->tickets[$ticketID]->userID;

                    if (!isset($messageUser[$userID][$ticketID]['coins'])) $messageUser[$userID][$ticketID]['coins'] = 0;
                    $messageUser[$userID][$ticketID]['coins'] += $pro;

                    $updateLottoField->execute([$pro, $ticketID, $fieldID]);
                }
            }

            $insertDrawWinClass->execute([$this->draw->drawID, $i, $pro, count($this->winClass[$i])]);
        }

        if (!empty($messageUser)) {
            $coins = 0;
            foreach ($messageUser as $userID => $tickets) {
                foreach ($tickets as $ticketID => $value) {
                    $objectAction = new LottoTicketAction([$ticketID], 'update', ['data' => [
                            'winCoins' => $value['coins']
                    ]]);
                    $objectAction->executeAction();

                    if ($value['coins'] != 0) {
                        UserJCoinsStatementHandler::getInstance()->create('info.daries.lotto.transfer', new LottoTicket($ticketID), [
                            'amount' => $value['coins'],
                            'type' => 'win',
                            'userID' => $userID
                        ]);
                    }

                    $coins += $value['coins'];
                    UserNotificationHandler::getInstance()->fireEvent('lottoWin', 'info.daries.lotto.notification', new LottoNotificationObject(new LottoTicket($ticketID)), [$userID]);
                }
            }

            $newJackpot = $this->jackpot - $coins;
            $this->jackpot = $newJackpot;
        }

        if (!empty($this->loseTickets)) {
            foreach ($this->loseTickets as $ticketID => $userID) {
                UserNotificationHandler::getInstance()->fireEvent('lottoLose', 'info.daries.lotto.notification', new LottoNotificationObject(new LottoTicket($ticketID)), [$userID]);
            }
        }
    }

    /**
     * creates a new lottery draw
     */
    protected function newDraw() {
        while (count($this->drawNumbers) < 6) {
            $newNumber = MathUtil::getRandomValue(1, 49);

            if (!in_array($newNumber, $this->drawNumbers)) {
                $this->drawNumbers[] = $newNumber;
            }
        }

        sort($this->drawNumbers, SORT_NUMERIC);

        $this->additionalNumber = MathUtil::getRandomValue(0, 9);

        $objectAction = new LottoDrawAction([$this->draw->drawID], 'update', ['data' => [
                'number1' => $this->drawNumbers[0],
                'number2' => $this->drawNumbers[1],
                'number3' => $this->drawNumbers[2],
                'number4' => $this->drawNumbers[3],
                'number5' => $this->drawNumbers[4],
                'number6' => $this->drawNumbers[5],
                'additionalNumber' => $this->additionalNumber
        ]]);
        $objectAction->executeAction();
    }

    /**
     * Create winning classes jcoins
     * 
     * @return	array
     */
    protected function readWinCoins() {
        $winCoins = [];
        $jackpot = $this->jackpot = intval($this->draw->jackpot);

        $winCoins[9] = LOTTO_WINCLASS9;
        $jackpot -= ($winCoins[9] * count($this->winClass[9]));

        $winCoins[1] = floor($jackpot * floatval(LOTTO_WINCLASS1) / 100);
        $jackpot -= $winCoins[1];

        $winCoins[2] = floor($jackpot * floatval(LOTTO_WINCLASS2) / 100);
        $winCoins[3] = floor($jackpot * floatval(LOTTO_WINCLASS3) / 100);
        $winCoins[4] = floor($jackpot * floatval(LOTTO_WINCLASS4) / 100);
        $winCoins[5] = floor($jackpot * floatval(LOTTO_WINCLASS5) / 100);
        $winCoins[6] = floor($jackpot * floatval(LOTTO_WINCLASS6) / 100);
        $winCoins[7] = floor($jackpot * floatval(LOTTO_WINCLASS7) / 100);
        $winCoins[8] = floor($jackpot * floatval(LOTTO_WINCLASS8) / 100);

        return $winCoins;
    }

    /**
     * reset cache
     */
    protected function resetCache() {
        LottoNextDrawCacheBuilder::getInstance()->reset();
    }

    /**
     * update stat
     */
    protected function updateStat() {
        $counters = [];
        foreach ($this->drawNumbers as $draw) {
            $key = "number$draw";
            $counters[$key] = 1;
        }

        $statAction = new LottoStatAction([LottoStat::LOTTO_STAT_DRAW], 'update', ['counters' => $counters]);
        $statAction->executeAction();
    }

}