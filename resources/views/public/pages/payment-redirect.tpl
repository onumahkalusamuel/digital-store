{assign var="active" value=""}
{extends file="layouts/public.tpl"}

{block name=body}
    <style>
        .success {
            color: darkgreen
        }

        .error {
            color: darkred
        }
    </style>
    <div class="inner-container">
        <h3 align="center">Payment Status</h3>
        <hr />
        <div style="text-align: center;" class="{if $success}success{else}error{/if}">
            <strong>{$message}</strong>
        </div>
    </div>
{/block}