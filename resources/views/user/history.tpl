{assign var="active" value="history"}
{extends file="layouts/user.tpl"}
{block name=title}History{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="top-section">
            <strong>History</strong>
        </div>
        <div class="history-items scrollable-scroll-area">
            {foreach from=$history.data item=item}
                <div class="card flex align-items-center">
                    <span class="card-avatar" style="background-image: url(img/logos/{$item.service_provider}.png);"></span>
                    <span class="card-details">
                        <span>{$item.destination}</span><br />
                        <small>{$item.description} - {$item.status}</small>
                    </span>
                    <span class="card-info">
                        <span>&#8358;{$item.amount|@number_format:"2"}</span><br />
                        <small>{$item.created_at}</small></span>
                </div>
            {/foreach}
        </div>
    </div>
{/block}