{extends file="layouts/public.tpl"}
{block name=title}Reset Update{/block}
{block name=body}
    <div class="card card-bordered">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Reset Update</h4>
                </div>
            </div>

            {if $data.message === 'success'}
                {if !empty($data.message)}
                    <div class="alert alert-success text-center text-uppercase" style="font-weight: bolder;">
                        {$data.message}
                    </div>
                {/if}

                {if empty($data.hide_form)}

                    <form method="POST" action="" class="form-validate is-alter">

                        <input type="hidden" name="csrf" value="{$data.csrf}" />

                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">New Password<span class="text-danger">
                                        &nbsp;*</span></label>
                            </div>
                            <div class="form-control-wrap">
                                <a href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" name="newPassword" class="form-control form-control-lg" id="password"
                                    placeholder="Enter your passcode" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-lg btn-primary btn-block">Reset Password</button>
                        </div>
                    </form>
                {/if}
            {else}
                {if !empty($data.message)}
                    <div class="alert alert-danger text-center text-uppercase" style="font-weight: bolder;">
                        {$data.message}
                    </div>
                {/if}
            {/if}
        </div>
    </div>
{/block}

{include file="public/footer.tpl"}