<?php

namespace wcf\data\lotto\draw;

use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\system\cache\builder\LottoNextDrawCacheBuilder;

/**
 * Provides functions to edit lotto draws.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Draw
 * 
 * @method static LottoDraw create(array $parameters = [])
 * @method LottoDraw getDecoratedObject()
 * @mixin LottoDraw
 */
class LottoDrawEditor extends DatabaseObjectEditor implements IEditableCachedObject {

    /**
     * @inheritDoc
     */
    protected static $baseClass = LottoDraw::class;

    /**
     * @inheritDoc
     */
    public static function resetCache() {
        LottoNextDrawCacheBuilder::getInstance()->reset();
    }

}