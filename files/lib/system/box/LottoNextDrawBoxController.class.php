<?php

namespace wcf\system\box;

use wcf\system\cache\builder\LottoNextDrawCacheBuilder;
use wcf\system\WCF;

/**
 * Box that shows the lotto next draw.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Box
 */
class LottoNextDrawBoxController extends AbstractBoxController {

    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['sidebarLeft', 'sidebarRight'];

    /**
     * @inheritDoc
     */
    protected function loadContent() {
        $drawTime = LottoNextDrawCacheBuilder::getInstance()->getData([], 'drawTime');

        if ($drawTime) {
            $this->content = WCF::getTPL()->fetch('boxLottoNextDraw', 'wcf', ['drawTime' => $drawTime], true);
        }
    }

}