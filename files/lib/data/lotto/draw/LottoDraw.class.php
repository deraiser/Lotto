<?php

namespace wcf\data\lotto\draw;

use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * Represents a lotto draw.
 * 
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @package	WoltLabSuite\Core\Data\Lotto\Draw
 *
 * @property-read integer $drawID
 * @property-read string $number1
 * @property-read integer $number2
 * @property-read integer $number3
 * @property-read integer $number4
 * @property-read integer $number5
 * @property-read integer $number6
 * @property-read integer $additionalNumber
 * @property-read integer $drawTime
 * @property-read integer $drawn
 * @property-read integer $jackpot
 */
class LottoDraw extends DatabaseObject {

    protected $winClass;

    /**
     * Return 0 if no play active
     * 
     * @return integer
     */
    public static function checkActiveDraw() {
        $sql = "SELECT  COUNT(*) as count
                FROM    wcf" . WCF_N . "_lotto_draw
                WHERE   drawTime > ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([TIME_NOW]);
        return $statement->fetchSingleColumn();
    }

    /**
     * Return actual play
     * 
     * @return LottoDraw
     */
    public static function getLastDraw() {
        $sql = "SELECT      *
                FROM        wcf" . WCF_N . "_lotto_draw
                ORDER BY    drawID DESC";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        $row = $statement->fetchArray();
        if (!$row) $row = [];

        return new LottoDraw(null, $row);
    }

    /**
     * Return data of the win class
     * 
     * @return array
     */
    public function getWinClassData() {
        if ($this->winClass === null) {
            $this->winClass = [];

            $sql = "SELECT      *
                    FROM        wcf" . WCF_N . "_lotto_draw_winclass
                    WHERE       drawID = ?
                    ORDER BY    winClass ASC";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$this->drawID]);
            while ($row = $statement->fetchArray()) {
                $this->winClass[] = $row;
            }
        }

        return $this->winClass;
    }

}