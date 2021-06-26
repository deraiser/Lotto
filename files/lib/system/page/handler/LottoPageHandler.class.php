<?php

namespace wcf\system\page\handler;

use wcf\system\WCF;

/**
 * Page handler implementation for the page showing the lotto.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Page\Handler
 */
class LottoPageHandler extends AbstractMenuPageHandler {
    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * @inheritDoc
     */
    public function isVisible($objectID = null) {
        return MODULE_LOTTO && WCF::getUser()->userID;
    }

}