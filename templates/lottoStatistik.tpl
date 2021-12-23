{include file='header'}

<ol class="section containerList doubleColumned lottoStatList">
    {foreach from=$statList item=stat}
        <li>
            <div class="containerHeadline">
                <h3>{lang}wcf.lotto.stats.stat{@$stat->statID}{/lang}</h3>
            </div>
            <div class="containerContent">
                {section name=numberID loop=49}
                    {assign var="numberID" value=$numberID+1}
                    {assign var=__number value=number|concat:$numberID}
                    <div class="lottoStatField">
                        <h1>{$numberID}</h1>
                        <p>{$stat->$__number|shortUnit}</p>
                    </div>
                {/section}
            </div>
        </li>
    {/foreach}
</ol>

{include file='footer'}