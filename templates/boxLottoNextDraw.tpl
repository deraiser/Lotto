<div class="boxLottoNextDraw">
    <div class="lottoCounter">
        <div class="lottoCounterDay"><p></p><small>{lang}wcf.lotto.day{/lang}</small></div>
        <div class="lottoCounterHour"><p></p><small>{lang}wcf.lotto.hour{/lang}</small></div>
        <div class="lottoCounterMinute"><p></p><small>{lang}wcf.lotto.minute{/lang}</small></div>
        <div class="lottoCounterSecond"><p></p><small>{lang}wcf.lotto.second{/lang}</small></div>
    </div>
    <div class="nextDraw">{@$drawTime|time}</div>
</div>

<script data-relocate="true">
    require(['Daries/Lotto/Counter'], function(LottoCounter) {
        new LottoCounter({$drawTime - TIME_NOW});
    });
</script>