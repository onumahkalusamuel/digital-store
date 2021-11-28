{extends file="layouts/public.tpl"}
{block name=title}Register{/block}
{block name=description}Register now to start making money selling digital products, airtime, data and more...{/block}
{block name=keywords}register,new,account,login,data,vtu,airtime{/block}

{block name=body}
    <div class="scrollable-container">
        <div class="scrollable-scroll-area" style="max-height: 100vh;">
            <div class="" style="text-align: center;padding-top:2rem">
                <a href="{$route->urlFor('home')}"><img src="/icon.png" style="margin:1.5rem;" width="40px" /> <br /></a>
                <strong>REGISTER</strong>
            </div>
            <form method="POST" action="{$route->urlFor('register')}" onsubmit="return ajaxPost('register');" id="register">
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="Full Name" title="Full Name" name="fullname" required>
                </div>
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="Email" title="Email" name="email" required>
                </div>
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="Password" title="Password" name="password" required>
                </div>
                <div class="inner-container" style="cursor: default; text-align:center">
                    <button class="button" role="submit">Register</button>
                </div>
            </form>

            <div class="inner-container text-center">
                <div>
                    Have an account?
                    <strong>
                        <a href="{$route->urlFor('login')}">Log in here</a>
                    </strong>
                </div>
            </div>
        </div>
    </div>
{/block}