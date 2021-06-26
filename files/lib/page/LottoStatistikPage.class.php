<?php

namespace wcf\page;

use wcf\data\lotto\stat\LottoStatList;
use wcf\system\WCF;

/**
 * Shows an lottery statistik page.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Page
 */
class LottoStatistikPage extends AbstractPage {

    /**
     * @inheritDoc
     */
    public $loginRequired = true;

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_LOTTO'];

    /**
     * lottery statistik list
     * @var LottoStatList
     */
    public $statList;

    /**
     * @inheritDoc
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'statList' => $this->statList
        ]);
    }

    /**
     * @inheritDoc
     */
    public function readData() {
        parent::readData();

        $statList = new LottoStatList();
        $statList->readObjects();
        $this->statList = $statList->getObjects();
    }

}