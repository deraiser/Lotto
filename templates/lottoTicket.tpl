{capture assign='contentTitle'}{$__wcf->getActivePage()->getTitle()}{/capture}
{capture assign='contentDescription'}{lang ticketNumber=$ticket->ticketNumber}wcf.lotto.ticketNumber{/lang}{/capture}


{capture assign='sidebarLeft'}
    {hascontent}
        <section id="lottoDrawList" class="box lottoDrawList">
            <h2 class="boxTitle">{lang}wcf.lotto.ticket.draw{/lang}</h2>

            <div class="boxContent">
                <dl class="plain dataList">
                    {content}
                        {foreach from=$draws key=number item=draw}
                            {assign var="number" value=$number+1}

                            <dt>{$number}. {lang}wcf.lotto.ticket.number{/lang}</dt>
                            <dd>{$draw}</dd>
                        {/foreach}
                    {/content}

                    <dt>{lang}wcf.lotto.ticket.additionalNumber{/lang}</dt>
                    <dd>{$additionalNumber}</dd>
                </dl>
            </div>
        </section>
    {/hascontent}

    {hascontent}
        <section id="lottoWinClassList" class="box lottoWinClassList">
            <h2 class="boxTitle">{lang}wcf.lotto.winClass{/lang}</h2>

            <div class="boxContent">
                <dl class="plain dataList">
                    {content}
                        {foreach from=$winClassData item=winClass}
                            <dt>{$winClass.winClass}. {lang}wcf.lotto.ticket.winClass{/lang}</dt>
                            <dd>({$winClass.counts}x) {$winClass.pro} {lang}wcf.jcoins.title{/lang}</dd>
                        {/foreach}
                    {/content}
                </dl>
            </div>
        </section>
    {/hascontent}
{/capture}

{include file='header'}

{if !$ticket->drawID}
    
{/if}
{if $ticket->drawID}
    {if $ticket->winCoins}
        <p class="success">{lang}wcf.lotto.win{/lang}</p>
    {else}
        <p class="error">{lang}wcf.lotto.notWin{/lang}</p>
    {/if}
{else}
    <p class="warning">{lang}wcf.lotto.notPlay{/lang}</p>
{/if}

<div class="section">
    <div id="lottoContainer" class="jsOnly">
        <div id="lottoTicket">
            <div class="lottoHeader"></div>
            <div class="lottoTicketField">
                {section name=blockID loop=12}
                    {assign var="blockID" value=$blockID+1}
                    <div class="lottoField{if $fields[$blockID]|isset} jsLottoBlock{/if}{if !$fields[$blockID]|isset} fieldDisabled{/if}" data-block-id="{$blockID}">
                        <div class="lottoFieldBGNumber"><span>{$blockID}</span></div>
                        <div class="lottoFieldBlock">
                            {section name="numberID" loop=49}
                                {assign var="numberID" value=$numberID+1}
                                <div class="lottoFields{if $fields[$blockID]|isset && $numberID|in_array:$fields[$blockID]} selectedField{if $numberID|in_array:$draws} selectedDrawField{/if}{/if}" data-block-id="{$blockID}" data-number="{$numberID}"></div>
                            {/section}
                        </div>
                    </div>
                {/section}
            </div>
        </div>
    </div>
</div>

<div id="lottoBlockField" class="jsOnly" style="display: none">
    <div class="lottoBlockField">
        <div class="lottoBlock">
            <div class="lottoBlockField jsLottoBlockField">
                {section name=numberID loop=49}
                    {assign var="numberID" value=$numberID+1}
                    <div class="lottoBlockFields jsLottoBlockFields" data-number="{$numberID}"><span>{$numberID}</span></div>
                {/section}
            </div>
        </div>
    </div>
</div>

{if $previousTicket || $nextTicket}
    <div class="section ticketNavigation">
        <nav>
            <ul>
                {if $previousTicket}
                    <li class="previousTicketButton">
                        <a href="{$previousTicket->getLink()}" rel="prev">
                            <div>
                                <span class="ticketNavigationName">{lang}wcf.lotto.previousTicket{/lang}</span>
                                <span class="ticketNavigationTitle">{lang ticketNumber=$previousTicket->ticketNumber}wcf.lotto.ticketNumber{/lang}</span>
                            </div>
                        </a>
                    </li>
                {/if}

                {if $nextTicket}
                    <li class="nextTicketButton">
                        <a href="{$nextTicket->getLink()}" rel="next">
                            <div>
                                <span class="ticketNavigationName">{lang}wcf.lotto.nextTicket{/lang}</span>
                                <span class="ticketNavigationTitle">{lang ticketNumber=$nextTicket->ticketNumber}wcf.lotto.ticketNumber{/lang}</span>
                            </div>
                        </a>
                    </li>
                {/if}
            </ul>
        </nav>
    </div>
{/if}

<script data-relocate="true">
    require(['Language', 'Daries/Lotto/Ticket'], function(Language, LottoTicket) {
        Language.addObject({
            'wcf.lotto.block.title': '{lang}wcf.lotto.block.title{/lang}'
        });

        var fields = {
            {foreach from=$fields key=__blockID item=field}
                {@$__blockID}: [
                    {foreach from=$field item=number}
                        {@$number},
                    {/foreach}
                ],
            {/foreach}
        };

        new LottoTicket(fields);
    });
</script>

{include file='footer'}