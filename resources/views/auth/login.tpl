{assign var="active" value="login"}
{extends file="layouts/public.tpl"}
{block name=title}Login{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="scrollable-scroll-area" style="max-height: 100vh;">
            <div class="" style="text-align: center;padding-top:2rem">
                <a href="{$route->urlFor('home')}"><img src="/icon.png" style="margin:1.5rem;" width="40px" /> <br /></a>
                <strong>LOGIN</strong>
            </div>
            <form method="POST" action="{$route->urlFor('login')}" onsubmit="return ajaxPost('login', true);" id="login">
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="phone or email" title="phone or email" name="phone_email"
                        required>
                </div>
                <div class="card flex align-items-center">
                    <input class="input card-details" placeholder="password" type="password" title="password"
                        name="password" required>
                </div>
                <div class="inner-container" style="cursor: default; text-align:center">
                    <button class="button" role="submit">Login</button>
                </div>
            </form>

            <div class="inner-container text-center">
                <div>
                    Forgot password?
                    <strong>
                        <a href="{$route->urlFor('reset-password')}">Reset here</a>
                    </strong>
                </div>
                <div>
                    No account?
                    <strong>
                        <a href="{$route->urlFor('register')}">Signup here</a>
                    </strong>
                </div>
            </div>
        </div>
    </div>
{/block}