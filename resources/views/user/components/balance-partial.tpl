<div class="top-section inner-container">
    <div>balance</div>
    <div class="balance">&#8358;{$user.balance|@number_format:"2"}</div>
    <div class="flex justify-space-around quick-menu">
        <a class="menu-item" href="{$route->urlFor('payments')}">
            <img alt="payments" src="img/svg/menu-up-white.svg" />
            <span class="menu-title">Payments</span>
        </a>
        <a class="menu-item" href="javascript:alert('Beneficiaries coming soon...');">
            <img alt="payments" src="img/svg/person-white.svg" />
            <span class="menu-title">Beneficiaries</span>
        </a>
        <a class="menu-item" href="{$route->urlFor('history')}">
            <img alt="payments" src="img/svg/clock-history-white.svg" />
            <span class="menu-title">History</span>
        </a>
    </div>
</div>