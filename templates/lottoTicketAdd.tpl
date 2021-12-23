{capture assign='sidebarLeft'}
    <section id="lottoCoinsList" class="box lottoCoinsList">
        <h2 class="boxTitle">{lang}wcf.lotto.jCoins{/lang}</h2>

        <div class="boxContent">
            <dl class="plain dataList">
                <dt>{lang}wcf.lotto.jCoins.lottoAdd{/lang}</dt>
                <dd>{JCOINS_LOTTOADD} {lang}wcf.jcoins.title{/lang}</dd>

                <dt>{lang}wcf.lotto.jCoins.lottoAddAdditionalField{/lang}</dt>
                <dd>{JCOINS_LOTTOADDADDITIONALFIELD} {lang}wcf.jcoins.title{/lang}</dd>
            </dl>
        </div>
    </section>
{/capture}

{include file='header'}

{if LOTTO_WARNING}
    <p class="info">{lang}wcf.lotto.info{/lang}</p>
{/if}

{if ($__wcf->user->jCoinsAmount - JCOINS_LOTTOADD) <= 0}
    <p class="error">{lang}wcf.lotto.error.coins{/lang}</p>
{else}
    <div class="section">
        <div id="lottoContainer" class="jsOnly">
            <div id="lottoTicket">
                <div class="lottoHeader"></div>
                <div class="lottoTicketField">
                    {section name=blockID loop=12}
                        {assign var="blockID" value=$blockID+1}
                        <div class="lottoField jsLottoBlock" title="{lang blockID=$blockID}wcf.lotto.lottoField{/lang}" data-block-id="{$blockID}">
                            <div class="lottoFieldBGNumber"><span>{$blockID}</span></div>
                            <div class="lottoFieldBlock">
                                {section name="numberID" loop=49}
                                    {assign var="numberID" value=$numberID+1}
                                    <div class="lottoFields" data-block-id="{$blockID}" data-number="{$numberID}"></div>
                                {/section}
                            </div>
                        </div>
                    {/section}
                </div>
                <div class="lottoTicketFieldOption jsOnly">
                    <div class="lottoTicketFieldShuffle">
                        <span>{lang}wcf.lotto.randomFields{/lang}</span>
                        <ul class="buttonList">
                            <li><button class="small jsLottoTicketFieldShuffle" data-shuffle-id="1">+1</button></li>
                            <li><button class="small jsLottoTicketFieldShuffle" data-shuffle-id="3">+3</button></li>
                            <li><button class="small jsLottoTicketFieldShuffle" data-shuffle-id="5">+5</button></li>
                            <li><button class="small jsLottoTicketFieldShuffle" data-shuffle-id="12">+12</button></li>
                        </ul>
                    </div>
                    <div class="lottoTicketFieldRemove">
                        <span class="icon icon24 fa-trash jsTooltip jsLottoTicketFieldRemove pointer" title="{lang}wcf.lotto.button.allFieldRemove{/lang}"></span>
                    </div>
                </div>
                <div class="lottoFooter"></div>
            </div>
        </div>

        <form method="post" action="{link controller='LottoTicketAdd'}{/link}">
            <div class="formSubmit">
                <button class="buttonPrimary jsLottoSubmit">{lang}wcf.lotto.button.submit{/lang}</button>
                <button class="button jsLottoSubmitNext">{lang}wcf.lotto.button.submit.next{/lang}</button>
                {@SECURITY_TOKEN_INPUT_TAG}
            </div>
        </form>
    </div>

    <div id="lottoBlockField" class="jsOnly" style="display: none">
        <div class="lottoBlockField">
            <div class="lottoBlockInfoCount jsLottoBlockInfoCount">{lang}wcf.lotto.block.count{/lang}</div>

            <div class="lottoBlock">
                <div class="lottoBlockField jsLottoBlockField">
                    {section name=numberID loop=49}
                        {assign var="numberID" value=$numberID+1}
                        <div class="lottoBlockFields jsLottoBlockFields" data-number="{$numberID}"><span>{$numberID}</span></div>
                    {/section}
                </div>
            </div>
        </div>

        <div class="formSubmit">
            <button class="buttonPrimary" data-type="submit">{lang}wcf.global.button.submit{/lang}</button>
            <button class="button" data-type="shuffle">{lang}wcf.lotto.button.fieldShuffle{/lang}</button>
            <button class="button" data-type="reset">{lang}wcf.global.button.reset{/lang}</button>
        </div>
    </div>

    <script data-relocate="true">
        require(['Language', 'Daries/Lotto/Ticket/Add'], function(Language, LottoTicketAdd) {
            Language.addObject({
                'wcf.lotto.block.title': '{lang}wcf.lotto.block.title{/lang}'
            });

            new LottoTicketAdd({$__wcf->user->jCoinsAmount}, {JCOINS_LOTTOADD}, {JCOINS_LOTTOADDADDITIONALFIELD});
        });
    </script>
{/if}

{include file='footer'}