<?php

namespace wcf\data\lotto\stat;

use wcf\data\DatabaseObjectList;

/**
 * Represents a list of ticket stats.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Stat
 *
 * @method LottoStat current()
 * @method LottoStat[] getObjects()
 * @method LottoStat|null search($objectID)
 * @property LottoStat[] $objects
 */
class LottoStatList extends DatabaseObjectList {

    /**
     * @inheritDoc
     */
    public $className = LottoStat::class;

}