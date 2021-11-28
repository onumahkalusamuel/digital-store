{assign var="active" value="faqs"}
{extends file="layouts/public.tpl"}
{block name=title}FAQs{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="top-section">
            <strong>FAQs</strong>
        </div>
        <div class="scrollable-scroll-area" style="max-height: 100vh;">
            <a href="javascript:toggleListMenu('menu-1')" class="card flex align-items-center text-decoration-none">
                <span class="card-details"><small>How much money can I make selling vtu airtime?</small></span>
                <span class="card-info">
                    <img src="img/svg/arrow-down-circle.svg" />
                </span>
            </a>
            <div class="menu-content" id="menu-1">
                As much as #500,000
            </div>
        </div>
    </div>
{/block}