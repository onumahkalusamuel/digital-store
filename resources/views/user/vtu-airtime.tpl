{assign var="active" value="vtu-airtime"}
{extends file="layouts/user.tpl"}
{block name=title}{$network|upper} VTU Airtime{/block}
{block name=body}
    <form method="POST" action="{$route->urlFor('vtu-airtime',['network' => $network])}"
        onsubmit="return ajaxPost('vtu-airtime');" id="vtu-airtime">
        <div class="">
            <div class="">
                <label class="" for="phone">
                    Phone<span>&nbsp;*</span> <button>beneficiaries</button>
                </label>
            </div>
            <div class="">
                <input onchange="checkNumberPrefix('{$network}');" name="phone" type="text" class="" id="phone" required>
            </div>
        </div>
        <div class="">
            <div class="">
                <label class="" for="amount">
                    Amount<span>&nbsp;*</span>
                </label>
            </div>
            <div class="">
                <input name="amount" class="" id="amount" onkeyup="checkEnteredAmount();" type="number" min="50" required>
            </div>
        </div>
        <div class="">
            <button class="">Buy Airtime</button>
        </div>
    </form>
    <script>
        var prefixes = "{$prefixes}".split(',');
        var balance = {$balances.balance};
    </script>
{/block}