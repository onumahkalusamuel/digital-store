{assign var="active" value="home"}
{extends file="layouts/user.tpl"}
{block name=title}Home{/block}
{block name=body}
    <div class="inner-container top-section">
        <div class="flex justify-space-between">
            <h3 style="padding:0;">Good morning, Kalu</h3>
            <span>..logo</span>
        </div>
        <div class="flex justify-space-between">
            <p style="padding:0;margin:0">My favorites</p>
            <a class="white link" href="{$route->urlFor('menu')}">More Options >></a>
        </div>
        <div class="flex flex-wrap">
            <a class="favorite-item">
                <img alt="mtn-sns" src="img/svg/house-door.svg" />
                <span class="menu-title">MTN ShareNSell</span>
            </a>
            <a class="favorite-item">
                <img alt="mtn-sme" src="img/svg/house-door.svg" />
                <span class="menu-title">MTN SME Data</span>
            </a>
            <a class="favorite-item">
                <img alt="airtel-vtu" src="img/svg/house-door.svg" />
                <span class="menu-title">Airtel Airtime</span>
            </a>
            <a class="favorite-item">
                <img alt="glo-vtu" src="img/svg/house-door.svg" />
                <span class="menu-title">Glo Airtime</span>
            </a>
            <a class="favorite-item">
                <img alt="9mobile-vtu" src="img/svg/house-door.svg" />
                <span class="menu-title">9Mobile Airtime</span>
            </a>
            <a class="favorite-item">
                <img alt="exam-waec-card" src="img/svg/house-door.svg" />
                <span class="menu-title">WAEC Result Card</span>
            </a>
            <a class="favorite-item">
                <img alt="exam-neco-card" src="img/svg/house-door.svg" />
                <span class="menu-title">NECO Result Card</span>
            </a>
        </div>
    </div>
{/block}