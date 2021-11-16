{extends file="layouts/public.tpl"}
{block name=title}Login{/block}
{block name=body}
    <h4 class="">Login</h4>
    <p>Enter your login details to access your account.</p>

    <form id="login" action="{$route->urlFor('login')}" method="POST" class="" onsubmit="return ajaxPost('login');">
        <div class="">
            <div class="">
                <label class="" for="phone-email">
                    Phone or Email<span>&nbsp;*</span>
                </label>
            </div>
            <div class="">
                <input name="phone_email" type="text" class="" id="phone-email" required>
            </div>
        </div>
        <div class="">
            <div class="">
                <label class="" for="password">
                    Password<span class="text-danger"> &nbsp;*</span>
                </label>
            </div>
            <div class="">
                <input name="password" type="password" class="" id="password" required>
            </div>
        </div>
        <div class="">
            <button class="">Login</button>
        </div>
    </form>
    <div class="">
        <div>
            Forgot your password?
            <strong>
                <a href="{$route->urlFor('reset-password')}">Click here to reset</a>
            </strong>
        </div>
        <div>
            No account?
            <strong>
                <a href="{$route->urlFor('register')}">Signup here</a>
            </strong>
        </div>
    </div>

{/block}