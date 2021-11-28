{assign var="active" value="menu"}
{extends file="layouts/user.tpl"}
{block name=title}Menu{/block}
{block name=body}
    <div class="scrollable-container">
        {include file="user/components/balance-partial.tpl"}
        <div class="scrollable-scroll-area" style="max-height: 54vh;">
            <a href="javascript:toggleListMenu('menu-1')" class="card flex align-items-center text-decoration-none">
                <span class="card-details">
                    <div>VTU Data</div>
                    <small>Direct data purchase from network providers.</small>
                </span>
                <span class="card-info">
                    <img src="img/svg/arrow-down-circle.svg" />
                </span>
            </a>
            <div class="menu-content" id="menu-1">
                <a class="menu-link" style="background-image: url(/img/logos/mtn.png);"
                    href="{$route->urlFor('vtu-data', ['network' => 'mtn'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/airtel.png);"
                    href="{$route->urlFor('vtu-data', ['network' => 'airtel'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/ninemobile.png);"
                    href="{$route->urlFor('vtu-data', ['network' => 'ninemobile'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/glo.png);"
                    href="{$route->urlFor('vtu-data', ['network' => 'glo'])}">
                </a>
            </div>
            <a href="javascript:toggleListMenu('menu-2')" class="card flex align-items-center text-decoration-none"
                title="Small and medium enterprise cheap data plans.">
                <span class="card-details">
                    <div>SME Data</div>
                    <small>SME cheap data plans (aka datashare)</small>
                </span>
                <span class="card-info">
                    <img src="img/svg/arrow-down-circle.svg" />
                </span>
            </a>
            <div class="menu-content" id="menu-2">
                <a class="menu-link" style="background-image: url(/img/logos/mtn.png);"
                    href="{$route->urlFor('sme-data', ['network' => 'mtn'])}">
                </a>
            </div>
            <a href="javascript:toggleListMenu('menu-3')" class="card flex align-items-center text-decoration-none">
                <span class="card-details">
                    <div>VTU Airtime</div>
                    <small>Airtime recharge for all networks</small>
                </span>
                <span class="card-info">
                    <img src="img/svg/arrow-down-circle.svg" />
                </span>
            </a>
            <div class="menu-content" id="menu-3">
                <a class="menu-link" style="background-image: url(/img/logos/mtn.png);"
                    href="{$route->urlFor('vtu-airtime', ['network' => 'mtn'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/airtel.png);"
                    href="{$route->urlFor('vtu-airtime', ['network' => 'airtel'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/ninemobile.png);"
                    href="{$route->urlFor('vtu-airtime', ['network' => 'ninemobile'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/glo.png);"
                    href="{$route->urlFor('vtu-airtime', ['network' => 'glo'])}">
                </a>
            </div>
            <a href="javascript:toggleListMenu('menu-4')" class="card flex align-items-center text-decoration-none"
                title="Airtime sharing. e.g. MTN ShareNSell, Airtel Me2U, etc">
                <span class="card-details">
                    <div>Airtime Share</div>
                    <small>Airtime sharing. e.g. MTN ShareNSell, Airtel Me2U, etc</small>
                </span>
                <span class="card-info"><img src="img/svg/arrow-down-circle.svg" /></span>
            </a>
            <div class="menu-content" id="menu-4">
                <a class="menu-link" style="background-image: url(/img/logos/mtn.png);"
                    href="{$route->urlFor('sns-airtime', ['network' => 'mtn'])}">
                </a>
            </div>
            <a href="javascript:toggleListMenu('menu-5')" class="card flex align-items-center text-decoration-none"
                title="Result scratch card. WAEC, NECO, NABTEB, etc.">
                <span class="card-details">
                    <div>Result Scratch Cards</div>
                    <small>Result scratch card. WAEC, NECO, etc.</small>
                </span>
                <span class="card-info"><img src="img/svg/arrow-down-circle.svg" /></span>
            </a>
            <div class="menu-content" id="menu-5">
                <a class="menu-link" style="background-image: url(/img/logos/waec.png);"
                    href="{$route->urlFor('result-card', ['examination' => 'waec'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/neco.png);"
                    href="{$route->urlFor('result-card', ['examination' => 'neco'])}">
                </a>
                <a class="menu-link" style="background-image: url(/img/logos/nabteb.png);"
                    href="{$route->urlFor('result-card', ['examination' => 'nabteb'])}">
                </a>
            </div>
        </div>
    </div>
{/block}