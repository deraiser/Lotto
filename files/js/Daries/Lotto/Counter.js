/**
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @module	Daries/Lotto/Counter
 */

define(['Dom/Util','WoltLabSuite/Core/Timer/Repeating'], function (DomUtil, Repeating) {
    "use strict";

    function Counter(nextDraw) {
        this._nextDraw = nextDraw;

        this.init();
    }
    Counter.prototype = {
        component: function (x, v) {
            return Math.floor(x / v);
        },
        init: function () {
            this._counter();

            this._repeating = new Repeating(this._counter.bind(this), 999);
        },
        _counter: function () {
            this._nextDraw--;

            var days = this.component(this._nextDraw, (60 * 60 * 24));
            var hours = this.component(this._nextDraw, (60 * 60)) % 24;
            var minutes = this.component(this._nextDraw, 60) % 60;
            var seconds = this.component(this._nextDraw, 1) % 60;
            
            if (this._nextDraw < 1) {
                this._repeating.stop();
            }
            
            var counter = elBySel('.lottoCounter');
            DomUtil.setInnerHtml(elBySel('.lottoCounterDay > p', counter), days);
            DomUtil.setInnerHtml(elBySel('.lottoCounterHour > p', counter), hours);
            DomUtil.setInnerHtml(elBySel('.lottoCounterMinute > p', counter), minutes);
            DomUtil.setInnerHtml(elBySel('.lottoCounterSecond > p', counter), seconds);
        }
    };
    return Counter;
});