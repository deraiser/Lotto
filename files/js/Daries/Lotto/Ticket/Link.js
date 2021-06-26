/**
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @module	Daries/Lotto/Ticket/Link
 */

define([], function () {
    "use strict";
    /**
     * @exports Daries/Lotto/Ticket/Link
     */
    return {
        init: function () {
            var overviews = elBySelAll('.jsLottoTicketList');
            for (var i = 0, length = overviews.length; i < length; i++) {
                overviews[i].addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
            }
        },

        _click: function (event) {
            event.preventDefault();

            window.location = elData(event.currentTarget, 'link');
        }
    }
});