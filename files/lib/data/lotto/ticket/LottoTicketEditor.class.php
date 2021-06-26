<?php

namespace wcf\data\lotto\ticket;

use wcf\data\DatabaseObjectEditor;
use wcf\data\lotto\stat\LottoStat;
use wcf\data\lotto\stat\LottoStatAction;
use wcf\system\WCF;

/**
 * Provides functions to edit lotto tickets.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Ticket
 * 
 * @method static LottoTicket create(array $parameters = [])
 * @method LottoTicket getDecoratedObject()
 * @mixin LottoTicket
 */
class LottoTicketEditor extends DatabaseObjectEditor {

    /**
     * @inheritDoc
     */
    protected static $baseClass = LottoTicket::class;

    /**
     * Inserts ticket fields in the current ticket.
     * 
     * @param array $tickets
     */
    public function insertFields(array $tickets) {
        $sql = "INSERT INTO wcf" . WCF_N . "_lotto_ticket_field
                            (ticketID, fieldID, number1, number2, number3, number4, number5, number6)
                VALUES      (?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = WCF::getDB()->prepareStatement($sql);

        $counters = [];
        foreach ($tickets as $fieldID => $fields) {
            $statement->execute([$this->ticketID, $fieldID, $fields[0], $fields[1], $fields[2], $fields[3], $fields[4], $fields[5]]);

            foreach ($fields as $number) {
                $key = "number$number";

                if (!isset($counters[$key])) $counters[$key] = 0;
                $counters[$key] ++;
            }
        }

        if (!empty($counters)) {
            $statAction = new LottoStatAction([LottoStat::LOTTO_STAT_VOTED], 'update', ['counters' => $counters]);
            $statAction->executeAction();
        }
    }

}