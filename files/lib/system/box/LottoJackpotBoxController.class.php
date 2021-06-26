<?php

namespace wcf\system\box;

use wcf\system\cache\builder\LottoNextDrawCacheBuilder;
use wcf\system\WCF;

/**
 * Box that shows the lotto jackpot.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Box
 */
class LottoJackpotBoxController extends AbstractBoxController {

    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['sidebarLeft', 'sidebarRight'];

    /**
     * @inheritDoc
     */
    protected function loadContent() {
        WCF::getTPL()->assign('jackpot', LottoNextDrawCacheBuilder::getInstance()->getData([], 'jackpot'));

        $this->content = WCF::getTPL()->fetch('boxLottoJackpot', 'wcf', [], true);
    }

}