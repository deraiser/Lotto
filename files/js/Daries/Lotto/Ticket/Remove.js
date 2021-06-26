/**
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @module	Daries/Lotto/Ticket/Remove
 */

define(['Ajax', 'Language', 'Ui/Confirmation'], function (Ajax, Language, UiConfirmation) {
    "use strict";

    function LottoRemove() {
        this.init();
    }

    LottoRemove.prototype = {
        init: function () {
            var button = elBySel('.jsTicketDeleteAll');
            button.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
        },

        _click: function (event) {
            UiConfirmation.show({
                confirm: function () {
                    Ajax.apiOnce({
                        data: {
                            actionName: 'removeAll',
                            className: 'wcf\\data\\lotto\\ticket\\LottoTicketAction'
                        },
                        success: function () {
                            window.location.reload();
                        }
                    });
                },
                message: Language.get('wcf.lotto.ticket.remove.confirmMessage')
            });
        }
    };

    return LottoRemove;
});