<?php

namespace wcf\system\cache\runtime;

use wcf\data\lotto\draw\LottoDraw;
use wcf\data\lotto\draw\LottoDrawList;

/**
 * Runtime cache implementation for lotto draws.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Cache\Runtime
 * 
 * @method LottoDraw[] getCachedObjects()
 * @method LottoDraw getObject($objectID)
 * @method LottoDraw[] getObjects(array $objectIDs)
 */
class LottoDrawRuntimeCache extends AbstractRuntimeCache {

    /**
     * @inheritDoc
     */
    protected $listClassName = LottoDrawList::class;

}