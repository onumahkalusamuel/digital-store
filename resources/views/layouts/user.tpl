<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <base href="{$basePath}/" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="{block name=description}{/block}">
    <meta name="keywords" content="{block name=keywords}{/block}">

    <title>{block name=title}Welcome{/block}</title>

    <meta property="og:title" content="">
    <meta property="og:type" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">

    <meta name="theme-color" content="#fafafa">

</head>

<body>
    <div class="global-container container">
        <main>
            {* <p>{block name=title}{/block}</p>
            Balance: {$balances.balance} <br />
            Loyalty Points: {$balances.loyalty_points} *}
            {block name=body}{/block}
        </main>
        <div class="fixed-footer-menu">
            <div class="flex">
                <a class="menu-item {if $active eq 'home'}menu-active{/if}" href="{$route->urlFor('dashboard')}">
                    <img class="" alt="home" src="img/svg/house-door.svg" />
                    <span class="menu-title">Dashboard</span>
                </a>
                <a class="menu-item {if $active eq 'menu'}menu-active{/if}" href="{$route->urlFor('menu')}">
                    <img class="" alt="menu" src="img/svg/list.svg" />
                    <span class="menu-title">Menu</span>
                </a>
                <a class="menu-item {if $active eq 'history'}menu-active{/if}" href="{$route->urlFor('history')}">
                    <img class="" alt="home" src="img/svg/clock-history.svg" />
                    <span class="menu-title">History</span>
                </a>
                <a class="menu-item {if $active eq 'support'}menu-active{/if}" href="{$route->urlFor('support')}">
                    <img class="" alt="support" src="img/svg/info-lg.svg" />
                    <span class="menu-title">Support</span>
                </a>
                <a class="menu-item {if $active eq 'profile'}menu-active{/if}" href="{$route->urlFor('profile')}">
                    <img class="" alt="profile" src="img/svg/person.svg" />
                    <span class="menu-title">Profile</span>
                </a>
            </div>
        </div>
    </div>
    <script src="js/vendor/modernizr-3.11.2.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
    <script>
        window.ga = function() { ga.q.push(arguments) };
        ga.q = [];
        ga.l = +new Date;
        ga('create', 'UA-XXXXX-Y', 'auto');
        ga('set', 'anonymizeIp', true);
        ga('set', 'transport', 'beacon');
        ga('send', 'pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>