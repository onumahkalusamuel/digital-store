{assign var="active" value="menu"}
{extends file="layouts/user.tpl"}
{block name=title}{$examination|upper} Result Card{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="scrollable-scroll-area">
            <div class="" style="text-align: center;">
                <img src="/img/logos/{$examination}.png" style="width: 50px;margin:1rem" alt="{$examination}" /><br />
                <strong><small>{block name=title}{/block}</small></strong>
            </div>
            <form method="POST" action="{$route->urlFor('result-card',['examination' => $examination])}"
                onsubmit="return ajaxPost('result-card');" id="result-card">
                <div class="card" style="text-align: center;">
                    <small>
                        <strong>NOTE:</strong>
                        You are about to purchase <strong>{block name=title}{/block}</strong>.
                        The details will be sent to your email, and also displayed here.
                        You can download it as pdf after purchase.
                    </small>
                </div>
                <div class="card flex align-items-center">
                    <label class="">
                        <input type="checkbox" required name="confirmation" />
                        <small>I confirm my purchase of <strong>{block name=title}{/block}</strong></small>
                    </label>
                </div>
                <div class="inner-container" style="cursor: default; text-align:center">
                    <button class="button" role="submit">Buy Result Card</button>
                    <a href="javascript:window.history.back();" class="button cancel text-decoration-none">Cancel</a>
                </div>
            </form>
        </div>
    </div>
{/block}