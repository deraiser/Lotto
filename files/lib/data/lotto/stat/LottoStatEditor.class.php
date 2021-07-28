<?php

namespace wcf\data\lotto\stat;

use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit lotto stats.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Stat
 * 
 * @method static LottoStat create(array $parameters = [])
 * @method LottoStat getDecoratedObject()
 * @mixin LottoStat
 */
class LottoStatEditor extends DatabaseObjectEditor {

    /**
     * @inheritDoc
     */
    protected static $baseClass = LottoStat::class;

}