{assign var="active" value="vtu-data"}
{extends file="layouts/user.tpl"}
{block name=title}{$network|upper} VTU Data{/block}
{block name=body}
    <form method="POST" action="{$route->urlFor('vtu-data',['network' => $network])}"
        onsubmit="return ajaxPost('vtu-data');" id="vtu-data">
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
                <label class="" for="bundle">
                    Data Bundle<span>&nbsp;*</span>
                </label>
            </div>
            <div class="">
                <select name="bundle" class="" id="bundle" onchange="checkSelectedBundle();">
                    <option value="0">--Select Bundle--</option>
                    {foreach from=$priceList item=item}
                        <option value="{$item.amount}">{$item.data} - #{$item.amount}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="">
            <button class="">Buy Data</button>
        </div>
    </form>
    <script>
        var prefixes = "{$prefixes}".split(',');
        var balance = {$balances.balance};
    </script>
{/block}