{assign var="active" value="menu"}
{extends file="layouts/user.tpl"}
{block name=title}{$network|upper} VTU Data{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="scrollable-scroll-area">
            <div class="" style="text-align: center;">
                <img src="/img/logos/{$network}.png" style="width: 50px;margin:1rem" alt="{$network}" /><br />
                <strong><small>{block name=title}{/block}</small></strong>
            </div>
            <form method="POST" action="{$route->urlFor('vtu-data',['network' => $network])}"
                onsubmit="return ajaxPost('vtu-data');" id="vtu-data">
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="080xxxxxxxx" title="phone number" name="phone">
                    <a class="img-link" href="javascript:chooseBeneficiaries('{$network}');" title="choose beneficiaries">
                        <img src="/img/svg/person.svg" style="width:20px" />
                    </a>
                </div>
                <div class="card flex align-items-center">
                    <select name="bundle" class="input card-details" id="bundle">
                        <option value="0">--Select Bundle--</option>
                        {foreach from=$priceList item=item}
                            <option value="{$item.amount}">{$item.data} - #{$item.amount}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="inner-container" style="cursor: default; text-align:center">
                    <button class="button" role="submit">Buy Data</button>
                    <a href="javascript:window.history.back();" class="button cancel text-decoration-none">Cancel</a>
                </div>
            </form>
        </div>
    </div>
{/block}