{capture assign='headContent'}
    {if $pageNo < $pages}
        <link rel="next" href="{link controller='Lotto'}pageNo={@$pageNo+1}{/link}">
    {/if}
    {if $pageNo > 1}
        <link rel="prev" href="{link controller='Lotto'}{if $pageNo > 2}pageNo={@$pageNo-1}{/if}{/link}">
    {/if}
{/capture}

{if $objects|count}
    {capture assign='contentHeaderNavigation'}
        <li><button class="jsTicketDeleteAll"><span class="icon icon16 fa-trash-o"></span> <span>{lang}wcf.lotto.ticket.remove.all{/lang}</span></button></li>
    {/capture}
    <script data-relocate="true">
        require(['Language', 'Daries/Lotto/Ticket/Remove'], function(Language, LottoRemove) {
            Language.addObject({
                'wcf.lotto.ticket.remove.confirmMessage': '{lang}wcf.lotto.ticket.remove.confirmMessage{/lang}'
            });

            new LottoRemove();
        });
    </script>
{/if}

{include file='header'}

{hascontent}
    <div class="paginationTop">
        {content}
            {pages print=true assign='pagesLinks' controller='Lotto' link="pageNo=%d"}
        {/content}
    </div>
{/hascontent}

{if $objects|count}
    <ol class="section containerList tripleColumned lottoList">
        {foreach from=$objects item=ticket}
            <li class="jsLottoTicketList pointer" data-ticket-id="{@$ticket->ticketID}"  data-link="{link controller='LottoTicket' id=$ticket->ticketID}{/link}">
                <div class="containerHeadline">
                    <h3>{lang ticketID=$ticket->ticketID}wcf.lotto.ticketHeadline{/lang}</h3>
                </div>
                <div class="containerContent">
                    <ul>
                        <li><small>{lang ticketNumber=$ticket->ticketNumber}wcf.lotto.ticketNumber{/lang}</small></li>
                        <li><small>{lang time=$ticket->created costCoins=$ticket->costCoins}wcf.lotto.pay{/lang}</small></li>
                        <li>
                            <small>
                                {if $ticket->drawID}
                                    {if $ticket->winCoins}
                                        <span class="badge green">{lang}wcf.lotto.win.true{/lang}</span>
                                    {else}
                                        <span class="badge red">{lang}wcf.lotto.win.false{/lang}</span>
                                    {/if}
                                {else}
                                    <span class="badge yellow">{lang}wcf.lotto.wait{/lang}</span>
                                {/if}
                            </small>
                        </li>
                    </ul>
                </div>
            </li>
        {/foreach}
    </ol>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
    {hascontent}
        <div class="paginationBottom">
            {content}{@$pagesLinks}{/content}
        </div>
    {/hascontent}

    {hascontent}
        <nav class="contentFooterNavigation">
            <ul>
                {content}{event name='contentFooterNavigation'}{/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

<script data-relocate="true">
    require(['Daries/Lotto/Ticket/Link'], function(LottoTicketLink) {
        LottoTicketLink.init();
    });
</script>

{include file='footer'}