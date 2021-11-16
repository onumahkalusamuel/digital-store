{assign var="active" value="payments"}
{extends file="layouts/user.tpl"}
{block name=title}Payments{/block}
{block name=body}
    <form method="POST" action="{$route->urlFor('payments')}" onsubmit="return ajaxPost('payments');" id="payments">
        <div class="">
            <div class="">
                <label class="" for="amount">
                    Amount<span>&nbsp;*</span>
                </label>
            </div>
            <div class="">
                <input name="amount" type="text" class="" id="amount" required>
            </div>
        </div>
        <div class="">
            <button class="">Fund Wallet</button>
        </div>
    </form>

    <h3>Bank Deposit</h3>
    <p>Make payment to the following account and send details to support for account crediting.</p>
    <p><strong>Bank:</strong> {$account_details.bank_name}</p>
    <p><strong>Account Number:</strong> {$account_details.account_number}</p>
    <p><strong>Account Name:</strong> {$account_details.account_name}</p>
    <h3>Previous Transactions</h3>
    list of previous transactions.
{/block}