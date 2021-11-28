{assign var="active" value="home"}
{extends file="layouts/public.tpl"}

{block name=body}
    <div class="inner-container">
        <h3 align="center">Quickly buy your airtime, data, or waec and neco scratch cards now,
            or <a href="{$route->urlFor('register')}">become a vendor</a> to earn big rewards!!!
            <a href="{$route->urlFor('prices')}">
                Compare prices here.
            </a>
        </h3>
        <hr />
        <form id="quick-buy" method="POST" onsubmit="return submitQuickBuy();" action="{$route->urlFor('api-quick-buy')}">
            <div class="" style="width:100%; text-align:center">
                <a class="button cancel badge danger text-decoration-none" href="javascript:resetForm()" id="d-close"
                    style="display: none;">X</a>
                <span class="badge" id="d-product" style="display: none;"></span>
                <span class="badge" id="d-network" style="display: none;"></span>
            </div>
            <div id="products">
                <h3 style="text-align:center">What do you want to buy?</h3>
                <div class="">
                    <div class="card p-none">
                        <a class="product text-decoration-none" href="javascript:chooseProduct('sme-data')"
                            title="Small and medium enterprise cheap data plans.">
                            <div>SME Data</div>
                            <small>SME cheap data plans (aka datashare)</small>
                        </a>
                    </div>
                    <div class="card p-none">
                        <a class="product text-decoration-none" href="javascript:chooseProduct('vtu-data')"
                            title="Direct data purchase from network providers.">
                            <div>VTU Data</div>
                            <small>Direct data purchase from network providers.</small>
                        </a>
                    </div>
                    <div class="card p-none">
                        <a class="product text-decoration-none" href="javascript:chooseProduct('vtu-airtime')"
                            title="Airtime recharge for all networks">
                            <div>Airtime</div>
                            <small>Airtime recharge for all networks</small>
                        </a>
                    </div>
                    <div class="card p-none">
                        <a class="product text-decoration-none" href="javascript:chooseProduct('result-card')"
                            title="Result scratch card. WAEC, NECO, NABTEB, etc.">
                            <div>Result Card</div>
                            <small>Result scratch card. WAEC, NECO, etc.</small>
                        </a>
                    </div>
                </div>
            </div>
            <div style="display:none" id="networks-examinations">
                <div id="networks" style="display: block;">
                    <h3 style="text-align:center">Select Network</h3>
                    <div id="sme-data-only" style="display: none;" class="flex justify-space-evenly">
                        <a class="menu-link" style="background-image: url(/img/logos/mtn.png);"
                            href="javascript:chooseNetwork('mtn')">
                        </a>
                    </div>
                    <div id="vtu-data-airtime" style="display: none;" class="flex justify-space-evenly">
                        <a class="menu-link" style="background-image: url(/img/logos/mtn.png);"
                            href="javascript:chooseNetwork('mtn')">
                        </a>
                        <a class="menu-link" style="background-image: url(/img/logos/airtel.png);"
                            href="javascript:chooseNetwork('airtel')">
                        </a>
                        <a class="menu-link" style="background-image: url(/img/logos/ninemobile.png);"
                            href="javascript:chooseNetwork('ninemobile')">
                        </a>
                        <a class="menu-link" style="background-image: url(/img/logos/glo.png);"
                            href="javascript:chooseNetwork('glo')">
                        </a>
                    </div>
                </div>
                <div id="examinations" style="display: block;">
                    <h3 style="text-align:center">Select Examination</h3>
                    <div id="result-card" class="flex justify-space-evenly">
                        <a class="menu-link" style="background-image: url(/img/logos/waec.png);"
                            href="javascript:chooseExamination('waec')">
                        </a>
                        <a class="menu-link" style="background-image: url(/img/logos/neco.png);"
                            href="javascript:chooseExamination('neco')">
                        </a>
                        <a class="menu-link" style="background-image: url(/img/logos/nabteb.png);"
                            href="javascript:chooseExamination('nabteb')">
                        </a>
                    </div>
                </div>
            </div>
            <div style="display:none" id="final-details">
                <h3 style="text-align:center">Final Step</h3>
                <div id="plans" class="card flex align-items-center">
                    <select id="plan" class="card-details input" name="plan"></select>
                </div>
                <div id="quantity" class="card flex align-items-center">
                    <input name="quantity" class="card-details input" type="number" min="1" placeholder="quantity" />
                </div>
                <div id="amount" class="card flex align-items-center">
                    <input name="amount" class="card-details input" placeholder="amount" />
                </div>
                <div id="phone" class="card flex align-items-center">
                    <input name="phone" class="card-details input" placeholder="080xxxxxxxx" />
                </div>
                <div id="email" class="card flex align-items-center">
                    <input name="email" class="card-details input" placeholder="email@example.com" />
                </div>
                <div id="button" style="text-align: center;">
                    <button role="bottom" class="button">Buy Now</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        var selectedProduct = "";
        var selectedNetwork = "";
        var selectedExamination = "";

        var dProduct = document.getElementById("d-product");
        var dNetwork = document.getElementById("d-network");
        var dClose = document.getElementById("d-close");
        var products = document.getElementById("products");
        var networksExaminations = document.getElementById("networks-examinations");
        var networks = document.getElementById("networks");
        var examinations = document.getElementById("examinations");
        var networkSmeDataOnly = document.getElementById("sme-data-only");
        var networkVtuDataAirtime = document.getElementById("vtu-data-airtime");
        var finalDetails = document.getElementById("final-details");
        var quantity = document.getElementById("quantity");
        var phone = document.getElementById("phone");
        var email = document.getElementById("email");
        var plans = document.getElementById("plans");
        var plan = document.getElementById("plan");

        function chooseProduct(product) {
            selectedProduct = product;
            dProduct.innerHTML = selectedProduct;
            dProduct.style.display = 'inline';
            dClose.style.display = 'inline';
            products.style.display = 'none';
            switch (selectedProduct) {
                case "vtu-data":
                case "vtu-airtime": {
                    networksExaminations.style.display = 'block';
                    networks.style.display = 'block';
                    networkVtuDataAirtime.style.display = 'flex';
                    networkSmeDataOnly.style.display = 'none';
                    phone.style.display = 'flex';
                    examinations.style.display = 'none';
                    quantity.style.display = 'none';
                    email.style.display = 'none';
                    if (selectedProduct == "vtu-airtime") {
                        plans.style.display = 'none';
                    }
                    break;
                }

                case "sme-data": {
                    networksExaminations.style.display = 'block';
                    networks.style.display = 'block';
                    networkVtuDataAirtime.style.display = 'none';
                    networkSmeDataOnly.style.display = 'flex';
                    phone.style.display = 'flex';
                    examinations.style.display = 'none';
                    quantity.style.display = 'none';
                    email.style.display = 'none';
                    break;
                }
                case "result-card": {
                    networksExaminations.style.display = 'block';
                    examinations.style.display = 'block';
                    quantity.style.display = 'flex';
                    amount.style.display = 'none';
                    email.style.display = 'flex';
                    phone.style.display = 'none';
                    networks.style.display = 'none';
                    break;
                }
                default: {
                    return false;
                    break;
                }
            }
        }

        function chooseNetwork(network) {
            selectedNetwork = network;
            dNetwork.innerHTML = selectedNetwork;
            dNetwork.style.display = 'inline';
            dClose.style.display = 'inline';
            networksExaminations.style.display = 'none';
            if (selectedProduct == "vtu-airtime") {
                amount.style.display = 'flex';
                phone.style.display = 'flex'
                finalDetails.style.display = "block";
                return;
            } else {
                amount.style.display = 'none';
            }

            // create overlay
            var overlay = document.createElement('div');
            overlay.innerHTML =
                '<div style="display:table;width:100%;height:100vh;position:fixed;top:0;left:0;text-align:center;background-color:#fff5;z-index:1000"><div style="display:table-cell;vertical-align:middle;padding-bottom:100px"><span style="color:white;background-color:#0064cf;padding:15px;">please wait...</span></div></div>';

            document.body.appendChild(overlay);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;
                try {
                    var response = JSON.parse(xhr.response);
                    plan.innerHTML = "";
                    if (response.length) {
                        var html = "";
                        response.forEach(function(resp) {
                            var option = document.createElement("option");
                            option.value = resp.amount;
                            option.innerHTML = resp.data + " at #" + resp.amount;
                            plan.appendChild(option);
                        });

                        finalDetails.style.display = "block";
                        plans.style.display = "flex";
                    } else {
                        alert("no plans available");
                    }
                } catch (e) {
                    alert('Unable to retrieve plans at the moment.')
                }
                document.body.removeChild(overlay);
            }

            var url = "";
            if (selectedProduct == "sme-data") {
                url = "/api/price-list/sme-data/" + selectedNetwork;
            } else if (selectedProduct == "vtu-data") {
                url = "/api/price-list/vtu-data/" + selectedNetwork;
            }

            xhr.open('GET', url);
            xhr.send();

        }

        function chooseExamination(examination) {
            selectedExamination = examination;
            dNetwork.innerHTML = selectedExamination;
            dNetwork.style.display = 'inline';
            networksExaminations.style.display = 'none';

            finalDetails.style.display = "block";
            plans.style.display = "none";
        }

        function resetForm() {
            plans.style.display = 'none';
            finalDetails.style.display = 'none';
            networksExaminations.style.display = 'none';
            products.style.display = 'block';
            dProduct.style.display = 'none';
            dNetwork.style.display = 'none';
            dClose.style.display = 'none';

        }

        function submitQuickBuy() {

            if (!confirm("Request will be submitted now. Continue?")) return false;

            var form = document.getElementById("quick-buy");
            var formData = new FormData(form);
            formData.append("product", selectedProduct);
            formData.append("network", selectedNetwork);
            formData.append("examination", selectedExamination);
            // create overlay
            var overlay = document.createElement('div');
            overlay.innerHTML =
                '<div style="display:table;width:100%;height:100vh;position:fixed;top:0;left:0;text-align:center;background-color:#fff5;z-index:1000"><div style="display:table-cell;vertical-align:middle;padding-bottom:100px"><span style="color:white;background-color:#0064cf;padding:15px;">please wait...</span></div></div>';

            document.body.appendChild(overlay);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;
                try {
                    var response = JSON.parse(xhr.response);
                    if (response.success == false) alert(response.message);
                    if (response.success == true && !!response.payment_link) {
                        window.open(response.payment_link);
                    }
                } catch (e) {
                    alert('An error occured. Please try again later.')
                }
                document.body.removeChild(overlay);
            }

            xhr.open('POST', form.getAttribute("action"), true);
            xhr.setRequestHeader('content-type', 'application/json');
            xhr.send(JSON.stringify(Object.fromEntries(formData)));

            return false;
        }
    </script>
{/block}