<?php

namespace wcf\system\cache\builder;

use wcf\data\lotto\draw\LottoDraw;

/**
 * Caches the time and jackpot of the next draw.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Cache\Builder
 */
class LottoNextDrawCacheBuilder extends AbstractCacheBuilder {

    /**
     * @inheritDoc
     */
    protected function rebuild(array $parameters) {
        $data = [
            'drawTime' => 0,
            'jackpot' => 0
        ];

        $draw = LottoDraw::getLastDraw();
        if ($draw->drawID) {
            $data = [
                'drawTime' => $draw->drawTime,
                'jackpot' => $draw->jackpot
            ];
        }

        return $data;
    }

}