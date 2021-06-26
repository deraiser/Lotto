<?php

namespace wcf\data\lotto\draw;

use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes lotto draw related actions.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Draw
 * 
 * @method LottoDrawEditor[] getObjects()
 * @method LottoDrawEditor getSingleObject()
 */
class LottoDrawAction extends AbstractDatabaseObjectAction {

    /**
     * @inheritDoc
     */
    protected $className = LottoDrawEditor::class;

}