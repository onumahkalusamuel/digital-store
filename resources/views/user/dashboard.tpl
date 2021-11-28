{assign var="active" value="home"}
{extends file="layouts/user.tpl"}
{block name=title}Home{/block}
{block name=body}
    <div class="inner-container top-section" style="height: 100%;box-sizing: border-box;">
        <h3 style="padding:0;">Welcome, {$user.fullname|capitalize}</h3>
        <div style="margin-bottom:1.5rem">
            <div>balance</div>
            <div class="balance">&#8358;{$user.balance|@number_format:"2"}</div>
        </div>

        <div class="flex justify-space-between">
            <p style="padding:0;margin:0">Quick links</p>
            <a class="white link" href="{$route->urlFor('menu')}">more >></a>
        </div>
        <div class="flex flex-wrap">
            <a class="favorite-item" href="{$route->urlFor('vtu-data', ['network' => 'mtn'])}">
                <span class="menu-title">MTN SME Data</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('vtu-airtime', ['network' => 'airtel'])}">
                <span class="menu-title">Airtel VTU Airtime</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('vtu-airtime', ['network' => 'airtel'])}">
                <span class="menu-title">Airtel Airtime</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('vtu-data', ['network' => 'glo'])}">
                <span class="menu-title">Glo Data</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('vtu-airtime', ['network' => 'glo'])}">
                <span class="menu-title">Glo VTU Airtime</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('vtu-airtime', ['network' => 'ninemobile'])}">
                <span class="menu-title">9Mobile Airtime</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('vtu-data', ['network' => 'ninemobile'])}">
                <span class="menu-title">9Mobile Data</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('sns-airtime', ['network' => 'mtn'])}">
                <span class="menu-title">MTN ShareNSell</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('result-card', ['examination' => 'waec'])}">
                <span class="menu-title">WAEC Result Card</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('result-card', ['examination' => 'neco'])}">
                <span class="menu-title">NECO Result Card</span>
            </a>
            <a class="favorite-item" href="{$route->urlFor('result-card', ['examination' => 'nabteb'])}">
                <span class="menu-title">NABTEB Result Card</span>
            </a>
        </div>
    </div>
{/block}