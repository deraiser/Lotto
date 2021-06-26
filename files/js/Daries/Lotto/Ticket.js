/**
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @module	Daries/Lotto/Ticket
 */

define(['Dictionary', 'Language', 'Ui/Dialog'], function (Dictionary, Language, UiDialog) {
    "use strict";

    function Ticket(fields) {
        this._ticket = new Dictionary();

        this.init(fields);
    }
    Ticket.prototype = {
        init: function (fields) {
            var blocks = elBySelAll('.jsLottoBlock');
            for (var i = 0, length = blocks.length; i < length; i++) {
                var block = blocks[i];
                var blockID = ~~elData(block, 'block-id');

                block.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));

                this._ticket.set(blockID, new Dictionary());
                
                var ticketBlock = this._ticket.get(blockID);
                fields[blockID].forEach(function(number) {
                    ticketBlock.set(number, true);
                });
            }
        },
        _click: function (event) {
            var block = event.currentTarget;
            var blockID = elData(block, 'block-id');

            this._activBlock = blockID;

            UiDialog.open(this);
            UiDialog.setTitle(this, Language.get('wcf.lotto.block.title') + blockID);
        },
        _dialogSetup: function () {
            return {
                id: 'lottoBlockField',
                options: {
                    onShow: (function (content) {
                        this._updateBlockField();
                    }).bind(this)
                }
            };
        },
        _updateBlockField: function () {
            var content = UiDialog.getDialog(this).content;
            var ticketBlock = this._ticket.get(this._activBlock);

            var fields = elBySelAll('.jsLottoBlockFields', content);
            for (var i = 0, length = fields.length; i < length; i++) {
                var field = fields[i];
                var number = ~~elData(field, 'number');

                if (ticketBlock.has(number)) {
                    if (!field.classList.contains('selectedField')) {
                        field.classList.add('selectedField');
                    }
                } else {
                    field.classList.remove('selectedField');
                }
            }
        }
    };
    return Ticket;
});