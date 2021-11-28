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
            {block name=body}{/block}
        </main>
        <div class="fixed-footer-menu">
            <div class="flex">
                <a class="menu-item {if $active eq 'home'}menu-active{/if}" href="{$route->urlFor('home')}">
                    <img class="" alt="home" src="img/svg/house-door.svg" />
                    <span class="menu-title">Home</span>
                </a>
                <a class="menu-item {if $active eq 'prices'}menu-active{/if}" href="{$route->urlFor('prices')}">
                    <img class="" alt="menu" src="img/svg/list.svg" />
                    <span class="menu-title">Prices</span>
                </a>
                <a class="menu-item {if $active eq 'faqs'}menu-active{/if}"
                    href="{$route->urlFor('page',['page'=>'faqs'])}">
                    <img class="" alt="home" src="img/svg/clock-history.svg" />
                    <span class="menu-title">FAQs</span>
                </a>
                <a class="menu-item {if $active eq 'help'}menu-active{/if}" href="{$route->urlFor('help')}">
                    <img class="" alt="help" src="img/svg/info-lg.svg" />
                    <span class="menu-title">Help</span>
                </a>
                <a class="menu-item {if $active eq 'login'}menu-active{/if}" href="{$route->urlFor('login')}">
                    <img class="" alt="login" src="img/svg/person.svg" />
                    <span class="menu-title">Account</span>
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