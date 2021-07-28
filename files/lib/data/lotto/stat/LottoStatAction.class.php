<?php

namespace wcf\data\lotto\stat;

use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes lotto stat related actions.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Stat
 * 
 * @method LottoStatEditor[] getObjects()
 * @method LottoStatEditor getSingleObject()
 */
class LottoStatAction extends AbstractDatabaseObjectAction {

    /**
     * @inheritDoc
     */
    protected $className = LottoStatEditor::class;

}