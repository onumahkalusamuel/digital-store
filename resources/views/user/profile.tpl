{assign var="active" value="profile"}
{extends file="layouts/user.tpl"}
{block name=title}Profile{/block}
{block name=body}
    <div class="top-section inner-container">
        <strong style="padding-bottom: 2rem;">Profile</strong>
        <div class="profile-picture">
        </div>
        <div class="profile-name">Onumah Kalu Samuel</div>
    </div>

    <div class="history-items scrollable-scroll-area">
        <div class="card flex align-items-center">
            <span class="card-avatar" style="background-image: url(img/svg/person.svg);"></span>
            <span class="card-details">
                <span>Change Password</span>
            </span>
        </div>
        <a href="{$route->urlFor('logout')}" class="card flex align-items-center text-decoration-none">
            <span class="card-avatar" style="background-image: url(img/svg/person.svg);"></span>
            <span class="card-details" style="color: red; font-weight:bold">
                <span>Logout</span>
            </span>
        </a>
    </div>
{/block}