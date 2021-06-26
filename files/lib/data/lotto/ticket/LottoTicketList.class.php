<?php

namespace wcf\data\lotto\ticket;

use wcf\data\DatabaseObjectList;

/**
 * Represents a list of lotto tickets.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Ticket
 *
 * @method LottoTicket current()
 * @method LottoTicket[] getObjects()
 * @method LottoTicket|null search($objectID)
 * @property LottoTicket[] $objects
 */
class LottoTicketList extends DatabaseObjectList {

    /**
     * @inheritDoc
     */
    public $className = LottoTicket::class;

}