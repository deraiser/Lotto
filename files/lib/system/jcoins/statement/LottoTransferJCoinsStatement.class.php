<?php

namespace wcf\system\jcoins\statement;

use wcf\data\jcoins\statement\JCoinsStatementAction;
use wcf\data\user\UserAction;
use wcf\system\exception\UserInputException;

/**
 * The lotto transfer JCoins statement.
 *
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\System\Jcoins\Statement
 */
class LottoTransferJCoinsStatement extends DefaultJCoinsStatement {

    /**
     * Object type for lotto pay
     */
    const OBJECT_TYPE = 'info.daries.lotto.transfer';

    /**
     * @inheritdoc
     */
    public function calculateAmount() {
        if (isset($this->parameters['amount'])) {
            $amount = $this->parameters['amount'];
            if ($this->parameters['type'] == 'buy') $amount = $amount * -1;
            return $amount;
        }

        // no amount given
        return 0;
    }

    /**
     * @inheritdoc
     */
    protected function saveDatabase() {
        $parameters = $this->getParameters();

        if (isset($parameters['time'])) {
            $time = $parameters['time'];
            unset($parameters['time']);
        } else {
            $time = false;
        }

        unset($parameters['userID']);

        $data = [
            'objectTypeID' => $this->getObjectType()->getObjectID(),
            'objectID' => $this->objectID,
            'amount' => $this->calculateAmount(),
            'additionalData' => serialize($parameters),
            'userID' => $this->parameters['userID'],
            'time' => $time ?: TIME_NOW
        ];

        if ($this->parameters['userID'] && $this->calculateAmount()) {
            $action = new JCoinsStatementAction([], 'create', [
                'data' => $data
            ]);
            $returnValues = $action->executeAction();
            $this->returnValuesLastObject = $returnValues['returnValues'];

            // update coins for the user 
            $userAction = new UserAction([$this->parameters['userID']], 'update', [
                'counters' => [
                    'jCoinsAmount' => $this->calculateAmount()
                ]
            ]);
            $userAction->executeAction();
        }
    }

    /**
     * @inheritdoc
     */
    public function validateParameters() {
        parent::validateParameters();

        if (empty($this->parameters['amount'])) {
            throw new UserInputException('amount');
        }
    }

}