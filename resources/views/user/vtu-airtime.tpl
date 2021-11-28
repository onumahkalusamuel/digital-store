{assign var="active" value="menu"}
{extends file="layouts/user.tpl"}
{block name=title}{$network|upper} VTU Airtime{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="scrollable-scroll-area">
            <div class="" style="text-align: center;">
                <img src="/img/logos/{$network}.png" style="width: 50px;margin:1rem" alt="{$network}" /><br />
                <strong><small>{block name=title}{/block}</small></strong>
            </div>
            <form method="POST" action="{$route->urlFor('vtu-airtime',['network' => $network])}"
                onsubmit="return ajaxPost('vtu-airtime');" id="vtu-airtime">
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="080xxxxxxxx" title="phone number" name="phone">
                    <a class="img-link" href="javascript:chooseBeneficiaries('{$network}');" title="choose beneficiaries">
                        <img src="/img/svg/person.svg" style="width:20px" />
                    </a>
                </div>
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="500" type="number" min="50" title="amount" name="amount">
                </div>
                <div class="inner-container" style="cursor: default; text-align:center">
                    <button class="button" role="submit">Buy Airtime</button>
                    <a href="javascript:window.history.back();" class="button cancel text-decoration-none">Cancel</a>
                </div>
            </form>
        </div>
    </div>
{/block}