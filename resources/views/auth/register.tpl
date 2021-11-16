{extends file="layouts/public.tpl"}
{block name=title}Register{/block}
{block name=description}Register now to start making money selling digital products, airtime, data and more...{/block}
{block name=keywords}register,new,account,login,data,vtu,airtime{/block}
{block name=body}
    <h4 class="">Register</h4>
    <p>Fill the form below to get started immediately.</p>
    <form action="{$route->urlFor('register')}" autocomplete="off" method="POST" id="registerForm" class=""
        onsubmit="return ajaxPost('registerForm');">
        <div class="">
            <label class="" for="fullname">
                Full Name<span class="text-danger">&nbsp;*</span>
            </label>
            <div>
                <input type="text" id="fullname" name="fullname" class="" required>
            </div>
        </div>
        <div class="">
            <label class="" for="phone">
                Phone<span class="text-danger">&nbsp;*</span>
            </label>
            <div class="">
                <input type="text" id="phone" name="phone" class="" autocomplete="off" required>
            </div>
        </div>
        <div class="">
            <label class="" for="email">
                Email Address<span class="text-danger"> &nbsp;*</span>
            </label>
            <div>
                <input type="email" id="email" name="email" class="" autocomplete="off" required>
            </div>
        </div>
        <div class="">
            <button class="" type="submit">Register</button>
        </div>
    </form>
    <div class="">
        Already have an account?
        <strong>
            <a href="{$route->urlFor('login')}">Sign in instead</a>
        </strong>
    </div>

{/block}