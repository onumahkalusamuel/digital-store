{assign var="active" value="prices"}
{extends file="layouts/public.tpl"}
{block name=title}Prices{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="top-section">
            <strong>Prices</strong>
        </div>
        <div class="scrollable-scroll-area" style="max-height: 100vh;">
            <div class="card">
                <span class="card-details">
                    <table border="1" width="100%" cellspacing=0>
                        <thead>
                            <tr>
                                <th rowspan="2">Item</th>
                                <th colspan="2">Prices (&#8358;)</th>
                            </tr>
                            <tr>
                                <th>QuickBuy *</th>
                                <th>Vendor</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$prices item=item}
                                <tr>
                                    <td>{$item.description}</td>
                                    <td class="text-center">{$item.price|@number_format}</td>
                                    <td class="text-center">{$item.price|@number_format}</td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </span>
            </div>
            <div class="inner-container">
                <small>
                    * All quickbuy purchases come with transaction charges. Register an account and fund your wallet to
                    carry out transactions without extra charges.</small>
            </div>
        </div>
    </div>
{/block}