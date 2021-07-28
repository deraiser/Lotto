<?php

namespace wcf\page;

use wcf\data\lotto\ticket\LottoTicketList;
use wcf\system\WCF;

/**
 * Shows an lotto page.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Page
 */
class LottoPage extends MultipleLinkPage {

    /**
     * @inheritDoc
     */
    public $itemsPerPage = LOTTOTICKETS_PER_PAGE;

    /**
     * @inheritDoc
     */
    public $loginRequired = true;

    /**
     * @inheritDoc
     */
    public $objectListClassName = LottoTicketList::class;

    /**
     * @inheritDoc
     */
    public $sortField = 'created';

    /**
     * @inheritDoc
     */
    public $sortOrder = 'DESC';

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_LOTTO'];

    /**
     * @inheritDoc
     */
    protected function initObjectList() {
        parent::initObjectList();

        $this->objectList->getConditionBuilder()->add('lotto_ticket.userID = ?', [WCF::getUser()->userID]);
    }

}