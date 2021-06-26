{if !"LOTTO_BRANDING_FREE"|defined}
    {if $templateName == 'lotto' || $templateName == 'lottoStatistik' || $templateName == 'lottoTicket' || $templateName == 'lottoTicketAdd'}
        <div class="copyright">{lang}wcf.lotto.copyright{/lang}</div>
    {/if}
{/if}