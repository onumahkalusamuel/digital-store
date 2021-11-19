{assign var="active" value="menu"}
{extends file="layouts/user.tpl"}
{block name=title}Menu{/block}
{block name=body}
    <div class="top-section inner-container">
        <div>available balance</div>
        <div class="balance">N{"600644.54"|@number_format:"2"}</div>
        <div class="loyalty_points">LP: {"4000"|@number_format}</div>
        <div class="flex .justify-space-around quick-menu">
            <a class="menu-item" href="{$route->urlFor('payments')}">
                <img alt="payments" src="img/svg/house-door.svg" />
                <span class="menu-title">Payments</span>
            </a>
            <a class="menu-item" href="{$route->urlFor('payments')}">
                <img alt="payments" src="img/svg/person.svg" />
                <span class="menu-title">Beneficiaries</span>
            </a>
            <a class="menu-item" href="{$route->urlFor('history')}">
                <img alt="payments" src="img/svg/clock-history.svg" />
                <span class="menu-title">Transactions</span>
            </a>
        </div>
    </div>

    <div class="menu-items">
        <div class="list-menu-title" data-target="menu-1">
            <span>VTU Data</span>
            <span><img src="img/svg/arrow-down-circle.svg" /></span>
        </div>
        <div class="menu-content" id="menu-1">
            <a href="{$route->urlFor('vtu-data', ['network' => 'mtn'])}">
                MTN
            </a>
            <a href="{$route->urlFor('vtu-data', ['network' => 'airtel'])}">
                Airtel
            </a>
            <a href="{$route->urlFor('vtu-data', ['network' => 'ninemobile'])}">
                9Mobile
            </a>
            <a href="{$route->urlFor('vtu-data', ['network' => 'glo'])}">
                Glo
            </a>
        </div>
        <div class="list-menu-title" data-target="menu-2">
            <span>SME Data</span>
            <span><img src="img/svg/arrow-down-circle.svg" /></span>
        </div>
        <div class="menu-content" id="menu-2">
            <a href="{$route->urlFor('sme-data', ['network' => 'mtn'])}">
                MTN
            </a>
        </div>
        <div class="list-menu-title" data-target="menu-3">
            <span>VTU Airtime</span>
            <span><img src="img/svg/arrow-down-circle.svg" /></span>
        </div>
        <div class="menu-content" id="menu-3">
            <a href="{$route->urlFor('vtu-airtime', ['network' => 'mtn'])}">
                MTN
            </a>
            <a href="{$route->urlFor('vtu-airtime', ['network' => 'airtel'])}">
                Airtel
            </a>
            <a href="{$route->urlFor('vtu-airtime', ['network' => 'ninemobile'])}">
                9Mobile
            </a>
            <a href="{$route->urlFor('vtu-airtime', ['network' => 'glo'])}">
                Glo
            </a>
        </div>
        <div class="list-menu-title" data-target="menu-4">
            <span>Airtime Share</span>
            <span><img src="img/svg/arrow-down-circle.svg" /></span>
        </div>
        <div class="menu-content" id="menu-4">
            <a href="{$route->urlFor('sns-airtime', ['network' => 'mtn'])}">
                MTN ShareNSell
            </a>
        </div>
        <div class="list-menu-title" data-target="menu-5">
            <span>Result Cards</span>
            <span><img src="img/svg/arrow-down-circle.svg" /></span>
        </div>
        <div class="menu-content" id="menu-5">
            <a href="{$route->urlFor('result-card', ['examination' => 'waec'])}">
                WAEC
            </a>
            <a href="{$route->urlFor('result-card', ['examination' => 'neco'])}">
                NECO
            </a>
            <a href="{$route->urlFor('result-card', ['examination' => 'nabteb'])}">
                NABTEB
            </a>
        </div>
    </div>

    <script>
        document.querySelectorAll(".list-menu-title").forEach(function(list) {
            list.addEventListener("click", toggleListMenu);
        });

        function toggleListMenu() {
            var targetElementId = event.target.getAttribute("data-target");
            var clickTarget = document.querySelector("#" + targetElementId);
            if (clickTarget.style.display == "block") clickTarget.style.display = "none";
            else clickTarget.style.display = "block";
        }
    </script>
{/block}