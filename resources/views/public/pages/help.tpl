{assign var="active" value="help"}
{extends file="layouts/public.tpl"}
{block name=title}Help{/block}
{block name=body}
    <div class="scrollable-container">
        <div class="top-section">
            <strong>Help</strong>
        </div>
        <div class="card-items scrollable-scroll-area">
            <a href="https://wa.me/{$whatsapp}" class="card flex align-items-center text-decoration-none">
                <span class="card-avatar" style="background-image: url(img/logos/whatsapp.png);"></span>
                <span class="card-details">
                    <span>{$whatsapp}</span>
                </span>
            </a>
            <a href="https://fb.me/{$facebook}" class="card flex align-items-center text-decoration-none">
                <span class="card-avatar" style="background-image: url(img/logos/whatsapp.png);"></span>
                <span class="card-details">
                    <span>{$facebook}</span><br />
                </span>
            </a>
            <a href="https://instagram.com/{$instagram}" class="card flex align-items-center text-decoration-none">
                <span class="card-avatar" style="background-image: url(img/logos/instagram.png);"></span>
                <span class="card-details">
                    <span>{$instagram}</span><br />
                </span>
            </a>
            <a href="tel:{$phone}" class="card flex align-items-center text-decoration-none">
                <span class="card-avatar" style="background-image: url(img/logos/phone.png);"></span>
                <span class="card-details">
                    <span>{$phone}</span>
                </span>
            </a>
            <a href="mailto:{$email}" class="card flex align-items-center text-decoration-none">
                <span class="card-avatar" style="background-image: url(img/logos/email.png);"></span>
                <span class="card-details">
                    <span>{$email}</span><br />
                </span>
            </a>
        </div>
    </div>
{/block}