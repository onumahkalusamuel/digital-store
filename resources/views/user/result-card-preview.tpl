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
            <div class="card" style="text-align: center;">
                <strong>PIN:</strong>
                79876545565545455
            </div>
            <div class="inner-container" style="cursor: default; text-align:center">
                <button class="button" role="submit">Download as PDF</button>
            </div>
        </div>
    </div>
{/block}