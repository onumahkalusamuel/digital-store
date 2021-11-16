{assign var="active" value="dashboard"}
{extends file="layouts/user.tpl"}
{block name=title}Dashboard{/block}
{block name=body}
    <div>
        <h4>What do you want to buy?</h4>
        <ul>
            <li>
                VTU Data
                <ul>
                    <li>
                        <a href="{$route->urlFor('vtu-data', ['network' => 'mtn'])}">
                            MTN VTU Data
                        </a>
                    </li>
                    <li>
                        <a href="{$route->urlFor('vtu-data', ['network' => 'airtel'])}">
                            Airtel VTU Data
                        </a>
                    </li>
                    <li>
                        <a href="{$route->urlFor('vtu-data', ['network' => 'ninemobile'])}">
                            9Mobile VTU Data
                        </a>
                    </li>
                    <li>
                        <a href="{$route->urlFor('vtu-data', ['network' => 'glo'])}">
                            Glo VTU Data
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                SME Data
                <ul>
                    <li>
                        <a href="{$route->urlFor('sme-data', ['network' => 'mtn'])}">
                            MTN SME Data
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                VTU Airtime
                <ul>
                    <li><a href="{$route->urlFor('vtu-airtime', ['network' => 'mtn'])}">MTN VTU</a></li>
                    <li><a href="{$route->urlFor('vtu-airtime', ['network' => 'airtel'])}">Airtel VTU</a></li>
                    <li><a href="{$route->urlFor('vtu-airtime', ['network' => 'ninemobile'])}">9Mobile VTU</a></li>
                    <li><a href="{$route->urlFor('vtu-airtime', ['network' => 'glo'])}">Glo VTU</a></li>
                </ul>
            </li>
            <li>
                ShareWithMe Airtime
                <ul>
                    <li><a href="{$route->urlFor('sns-airtime', ['network' => 'mtn'])}">MTN ShareNSell</a></li>
                </ul>
            </li>
            <li>
                Result Cards
                <ul>
                    <li><a href="{$route->urlFor('result-card', ['examination' => 'waec'])}">WAEC Card</a></li>
                    <li><a href="{$route->urlFor('result-card', ['examination' => 'neco'])}">NECO Card</a></li>
                    <li><a href="{$route->urlFor('result-card', ['examination' => 'nabteb'])}">NABTEB Card</a></li>
                </ul>
            </li>
        </ul>
    </div>
{/block}