{extends file="layouts/public.tpl"}
{block name=title}Reset Password{/block}
{block name=body}

    <h4 class="nk-block-title mt-5">Reset Password</h4>
    <p>Enter your email below to reset your password.</p>

    <form id="reset-password" action="{$route->urlFor('reset-password')}" method="POST" class="form-validate is-alter mb-5"
        onsubmit="return ajaxPost('reset-password');">
        <div class="">
            <div class="">
                <label class="" for="email">Email</label>
            </div>
            <div class="">
                <input name="email" type="text" class="" id="email" placeholder="Enter your email address" required>
            </div>
        </div>
        <div class="">
            <button class="">Send Reset Link</button>
        </div>
    </form>
    <div class="">
        <a href="{{$route->urlFor('login')}}"><strong>Return to login</strong></a>
    </div>
    </div>
    </div>
{/block}