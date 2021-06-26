<?php

namespace wcf\system\box;

use wcf\system\WCF;

/**
 * Box that shows the lotto ticket add button.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Box
 */
class LottoTicketAddBoxController extends AbstractBoxController {

    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['sidebarLeft', 'sidebarRight'];

    /**
     * @inheritDoc
     */
    protected function loadContent() {
        if (WCF::getUser()->userID && MODULE_LOTTO) {
            $jCoins = WCF::getUser()->jCoinsAmount;
            if (($jCoins - JCOINS_LOTTOADD) < 0) return;

            $this->content = WCF::getTPL()->fetch('boxLottoTicketAdd', 'wcf', [], true);
        }
    }

}