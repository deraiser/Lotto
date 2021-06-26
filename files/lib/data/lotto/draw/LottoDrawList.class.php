<?php

namespace wcf\data\lotto\draw;

use wcf\data\DatabaseObjectList;

/**
 * Represents a list of ticket draws.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Draw
 *
 * @method LottoDraw current()
 * @method LottoDraw[] getObjects()
 * @method LottoDraw|null search($objectID)
 * @property LottoDraw[] $objects
 */
class LottoDrawList extends DatabaseObjectList {
    
    /**
     * @inheritDoc
     */
    public $className = LottoDraw::class;

}