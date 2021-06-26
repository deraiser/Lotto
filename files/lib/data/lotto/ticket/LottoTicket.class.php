<?php

namespace wcf\data\lotto\ticket;

use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Represents a lotto ticket.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Ticket
 *
 * @property-read integer $ticketID
 * @property-read integer|null $userID
 * @property-read string $ticketNumber
 * @property-read integer $created
 * @property-read integer $costCoins
 * @property-read integer $winCoins
 * @property-read integer|null $drawID
 */
class LottoTicket extends DatabaseObject implements ILinkableObject {

    /**
     * list of fields
     * @var string[] 
     */
    public $fields;

    /**
     * return a list of current ticket fields
     * 
     * @return string[]
     */
    public function getFields() {
        if ($this->fields === null) {
            $this->fields = [];

            $sql = "SELECT  *
                    FROM    wcf" . WCF_N . "_lotto_ticket_field
                    WHERE   ticketID = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$this->ticketID]);
            while ($row = $statement->fetchArray()) {
                $this->fields[] = $row;
            }
        }

        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function getLink() {
        return LinkHandler::getInstance()->getLink('LottoTicket', [
                    'id' => $this->ticketID,
                    'forceFrontend' => true
        ]);
    }

    /**
     * return a sort list of current ticket fields
     * 
     * @return string[]
     */
    public function getSortFields() {
        $fields = $this->getFields();

        $newFields = [];
        foreach ($fields as $field) {
            $numbers = [
                $field['number1'],
                $field['number2'],
                $field['number3'],
                $field['number4'],
                $field['number5'],
                $field['number6']
            ];

            sort($numbers, SORT_NUMERIC);

            $newFields[] = array_merge($field, [
                'number1' => $numbers[0],
                'number2' => $numbers[1],
                'number3' => $numbers[2],
                'number4' => $numbers[3],
                'number5' => $numbers[4],
                'number6' => $numbers[5]
            ]);
        }

        return $newFields;
    }

}