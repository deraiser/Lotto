/**
 * @author	Marco Daries
 * @copyright	2018-2020 Daries.info
 * @license	Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0) <https://creativecommons.org/licenses/by-nd/4.0/>
 * @module	Daries/Lotto/Ticket/Add
 */

define(['Dictionary', 'Language', 'Ui/Dialog'], function (Dictionary, Language, UiDialog) {
    "use strict";

    function TicketAdd(jCoins, ticketPrice, additionalFieldPrice) {
        this._jCoins = jCoins;
        this._ticketPrice = ticketPrice;
        this._additionalFieldPrice = additionalFieldPrice;

        this._disabledTickets = [];
        this._ticket = new Dictionary();

        this.init();
    }
    TicketAdd.prototype = {
        init: function () {
            this._setDisableTickets();

            var blocks = elBySelAll('.jsLottoBlock');
            for (var i = 0, length = blocks.length; i < length; i++) {
                var block = blocks[i];
                var blockID = ~~elData(block, 'block-id');

                if (this._disabledTickets.indexOf(blockID) !== -1) {
                    block.classList.add('fieldDisabled');
                    block.removeAttribute('title');
                } else {
                    block.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));

                    this._ticket.set(blockID, new Dictionary());
                }
            }

            var buttons = elBySelAll('.jsLottoTicketFieldShuffle');
            for (var i = 0, length = buttons.length; i < length; i++) {
                var button = buttons[i];
                button.addEventListener(WCF_CLICK_EVENT, this._shuffleField.bind(this));
            }

            elBySel('.jsLottoTicketFieldRemove').addEventListener(WCF_CLICK_EVENT, this._removeAll.bind(this));
            elBySel('.jsLottoSubmit').addEventListener(WCF_CLICK_EVENT, this._submit.bind(this, false));
            elBySel('.jsLottoSubmitNext').addEventListener(WCF_CLICK_EVENT, this._submit.bind(this, true));

            this._checkShuffleButtonField();
        },
        _addField: function (number, field) {
            var ticketBlock = this._ticket.get(this._activBlock);

            if (ticketBlock.has(number)) {
                ticketBlock.delete(number);
                field.classList.remove('selectedField');
            } else {
                if (this._freeCount !== 0) {
                    ticketBlock.set(number, true);
                    field.classList.add('selectedField');
                }
            }
        },
        _checkShuffleButtonField: function () {
            var activField = 0;
            this._ticket.forEach(function (block) {
                var blockObj = block.toObject();
                var keys = Object.keys(blockObj);
                if (keys.length === 6)
                    activField++;
            });

            var countDisabledFields = this._disabledTickets.length;
            var freeFields = 12 - countDisabledFields - activField;

            var buttons = elBySelAll('.jsLottoTicketFieldShuffle');
            for (var i = 0, length = buttons.length; i < length; i++) {
                var button = buttons[i];
                var shuffleCount = ~~elData(button, 'shuffle-id');

                if (freeFields < shuffleCount) {
                    button.disabled = true;
                } else {
                    button.disabled = false;
                }
            }
        },
        _click: function (event) {
            var block = event.currentTarget;
            var blockID = elData(block, 'block-id');

            this._activBlock = blockID;

            UiDialog.open(this);
            UiDialog.setTitle(this, Language.get('wcf.lotto.block.title') + blockID);
        },
        _clickField: function (event) {
            var field = event.currentTarget;
            var number = ~~elData(field, 'number');

            this._addField(number, field);
            this._updateBlockInfoCount();
            this._submitDialogButton();
        },
        _dialogSetup: function () {
            return {
                id: 'lottoBlockField',
                options: {
                    onClose: (function () {
                        this._updateTicket();
                    }).bind(this),
                    onSetup: (function (content) {
                        elBySel('button[data-type="submit"]', content).addEventListener(WCF_CLICK_EVENT, this._submitDialog.bind(this));
                        elBySel('button[data-type="reset"]', content).addEventListener(WCF_CLICK_EVENT, this._remove.bind(this));
                        elBySel('button[data-type="shuffle"]', content).addEventListener(WCF_CLICK_EVENT, this._shuffleBlockField.bind(this));

                        var fields = elBySelAll('.jsLottoBlockFields', content);
                        for (var i = 0, length = fields.length; i < length; i++) {
                            var field = fields[i];

                            field.addEventListener(WCF_CLICK_EVENT, this._clickField.bind(this));
                        }
                    }).bind(this),
                    onShow: (function (content) {
                        this._updateBlockField();
                        this._updateBlockInfoCount();
                        this._submitDialogButton();
                    }).bind(this)
                }
            };
        },
        _remove: function () {
            this._ticket.set(this._activBlock, new Dictionary());
            this._updateBlockField();
            this._updateBlockInfoCount();
            this._submitDialogButton();
        },
        _removeAll: function () {
            var newTicket = new Dictionary();
            this._ticket.forEach(function (block, blockID) {
                newTicket.set(blockID, new Dictionary());
            });

            this._ticket = newTicket;

            this._updateTicket();
        },
        _setDisableTickets: function () {
            if (this._jCoins < this._ticketPrice) {
                for (var i = 1; i <= 12; i++) {
                    this._disabledTickets.push(i);
                }
            } else {
                var jCoins = this._jCoins - this._ticketPrice;
                var count = jCoins / this._additionalFieldPrice;
                count = Math.floor(count);

                for (var i = 12; count + 1 < i; i--) {
                    if (i !== 1) {
                        this._disabledTickets.push(i);
                    }
                }
            }
        },
        _shuffle: function (blockID) {
            this._ticket.set(blockID, new Dictionary());
            var ticketBlock = this._ticket.get(blockID);

            var count = 0;
            while (count < 6) {
                var newNumber = Math.floor((Math.random() * 49) + 1);

                if (!ticketBlock.has(newNumber)) {
                    ticketBlock.set(newNumber, true);
                    count++;
                }
            }
        },
        _shuffleField: function (event) {
            var button = event.currentTarget;
            var shuffleCount = ~~elData(button, 'shuffle-id');

            var count = 0;
            this._ticket.forEach((function (block, blockID) {
                var blockObj = block.toObject();
                var keys = Object.keys(blockObj);
                if (keys.length !== 6 && count < shuffleCount) {
                    this._shuffle(blockID);
                    count++;
                }
            }).bind(this));

            this._updateTicket();
        },
        _shuffleBlockField: function () {
            this._shuffle(this._activBlock);
            this._updateBlockField();
            this._updateBlockInfoCount();
            this._submitDialogButton();
        },
        _submit: function (next, event) {
            var input;

            this._ticket.forEach(function (block, blockID) {
                var blockObj = block.toObject();
                var keys = Object.keys(blockObj);

                if (keys.length === 6) {
                    block.forEach(function (value, number) {
                        input = elCreate('input');
                        input.type = 'hidden';
                        input.name = 'tickets[' + blockID + '][]';
                        input.value = number;

                        event.currentTarget.appendChild(input);
                    });
                }
            });

            if (next) {
                input = elCreate('input');
                input.type = 'hidden';
                input.name = 'nextTicket';
                input.value = 1;

                event.currentTarget.appendChild(input);
            }
        },
        _submitDialog: function () {
            this._updateTicket();
            UiDialog.close(this);
        },
        _submitDialogButton: function () {
            var disabled = true;
            if (this._freeCount === 0)
                disabled = false;

            var content = UiDialog.getDialog(this).content;
            elBySel('button[data-type="submit"]', content).disabled = disabled;
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
        },
        _updateBlockInfoCount: function () {
            var ticketBlock = this._ticket.get(this._activBlock);
            var newCount = 6;
            ticketBlock.forEach(function () {
                newCount--;
            });

            this._freeCount = newCount;

            var content = UiDialog.getDialog(this).content;
            elBySel('.jsLottoBlockInfoCount span', content).innerHTML = newCount;
        },
        _updateTicket: function () {
            var fields = elBySelAll('.lottoFields');
            for (var i = 0, length = fields.length; i < length; i++) {
                var field = fields[i];
                var blockID = ~~elData(field, 'block-id');
                var number = ~~elData(field, 'number');

                if (this._ticket.has(blockID)) {
                    var ticketBlock = this._ticket.get(blockID);
                    if (ticketBlock.has(number)) {
                        if (!field.classList.contains('selectedField')) {
                            field.classList.add('selectedField');
                        }
                    } else {
                        field.classList.remove('selectedField');
                    }
                }
            }

            this._checkShuffleButtonField();
        }
    };
    return TicketAdd;
});