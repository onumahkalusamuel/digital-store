{assign var="active" value="payments"}
{extends file="layouts/user.tpl"}
{block name=title}Payments{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="top-section inner-container">
            <div>balance</div>
            <div class="balance">&#8358;{$user.balance|@number_format:"2"}</div>
            <div class="flex justify-space-around quick-menu">
                <a class="menu-item" href="javascript:showModal('instant-deposit');">
                    <img alt="payments" src="img/svg/menu-up-white.svg" />
                    <span class="menu-title">Instant Deposit</span>
                </a>
                <div id="instant-deposit" class="modal">
                    <div class="global-container container">
                        <div class="inner-container" style="padding-top:2rem">
                            <button class="pointer badge" onclick="hideModal()">x</button>
                            <h3 align="center">Instant Deposit</h3>
                            <hr />
                            <p>Fill in the form below to initiate the transaction.</p>

                            <form method="POST" action="{$route->urlFor('payments')}"
                                onsubmit="return ajaxPost('payments');" id="payments">
                                <div class="card flex align-items-center">
                                    <input class="input card-details" placeholder="enter amount. e.g. 5000" type="number"
                                        min="50" title="amount" name="amount" required>
                                </div>
                                <div class="inner-container" style="cursor: default; text-align:center">
                                    <button class="button" role="submit">Fund Wallet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <a class="menu-item" href="javascript:showModal('bank-deposit');">
                    <img alt="payments" src="img/svg/person-white.svg" />
                    <span class="menu-title">Bank Deposit</span>
                </a>
                <div id="bank-deposit" class="modal">
                    <div class="global-container container">

                        <div class="inner-container" style="padding-top:2rem">
                            <button class="pointer badge" onclick="hideModal()">x</button>
                            <h3 align="center">Bank Deposit</h3>
                            <hr />
                            <p>Make payment to the following account and send details to support for account crediting. Make
                                sure to state the bank and amount paid.</p>
                            <p><strong>Bank:</strong> {$account_details.bank_name}</p>
                            <p><strong>Account Number:</strong> {$account_details.account_number}</p>
                            <p><strong>Account Name:</strong> {$account_details.account_name}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .background-completed {
                background-color: darkgreen;
            }

            .background-pending {
                background-color: orange;
            }

            .background-failed {
                background-color: darkred;
            }
        </style>
        <div class="scrollable-scroll-area" style="max-height: 54vh;">
            {foreach from=$payments.data item=item}
                <div class="card flex align-items-center {$item.status}">
                    <span class="card-avatar background-{$item.status}"></span>
                    <span class="card-details">
                        <span>{$item.transaction_id}</span><br />
                        <small>
                            {if $item.payment_link ne ''}
                                <a href="{$item.payment_link}" target="_blank">click here to continue payment</a>
                            {else}
                                {$item.service_id} - {$item.status}
                            {/if}
                        </small>
                    </span>
                    <span class="card-info">
                        <span>&#8358;{$item.amount|@number_format:"2"}</span><br />
                        <small>{$item.created_at}</small></span>
                </div>
            {/foreach}
        </div>
    </div>
{/block}