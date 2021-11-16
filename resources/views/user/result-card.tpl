{assign var="active" value="sme-data"}
{extends file="layouts/user.tpl"}
{block name=title}{$examination|upper} Result Card{/block}
{block name=body}
    <form method="POST" action="{$route->urlFor('result-card',['examination' => $examination])}"
        onsubmit="return ajaxPost('result-card');" id="result-card">
        <div class="">
            <div class="">
                <strong>
                    NOTE: You are about to purchase {$examination|upper} scratch card.
                    The details will be sent to your email, and also displayed here.
                    You can also download it as pdf.
                </strong>
            </div>
            <div class="">
                <label class="">
                    <input type="checkbox" required name="confirmation" />
                    I confirm that I want to purchase {$examination|upper} Result Card
                </label>
            </div>
        </div>
        <div class="">
            <button class="">Buy Result Card</button>
        </div>
    </form>
    <script>
        var prefixes = "{$prefixes}".split(',');
        var balance = {$balances.balance};
    </script>
{/block}