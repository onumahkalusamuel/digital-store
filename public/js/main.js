function ajaxPost(elementId = 'form') {
    var form = document.getElementById(elementId);
    var formData = JSON.stringify(Object.fromEntries(new FormData(form)));

    // create overlay
    var overlay = document.createElement('div');
    overlay.innerHTML = '<div style="display:table;width:100%;height:100vh;position:fixed;top:0;left:0;text-align:center;background-color:#fff5;z-index:1000"><div style="display:table-cell;vertical-align:middle;padding-bottom:100px"><span style="color:white;background-color:#0064cf;padding:15px;">please wait...</span></div></div>';

    document.body.appendChild(overlay);

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState !== 4) return;
        try {
            var response = JSON.parse(xhr.response);
            alert(response.message);
            if (response.success == true && !!response.redirect) {
                window.location.assign(response.redirect);
            }
        } catch (e) {
            alert('An error occured. Please try again later.')
        }

        document.body.removeChild(overlay);
    }

    xhr.open('POST', form.getAttribute('action'), true);
    xhr.setRequestHeader('content-type', 'application/json');
    xhr.send(formData);
    return false;
}

function checkNumberPrefix(network) {
    var valid = false;
    var destination = event.target.value;
    if (destination.length < 4) return;
    var check = getNumberPrefix(destination);
    prefixes.forEach(function(ele) {
        if (check === ele) {
            valid = true;
        }
    });

    if (!valid) {
        document.querySelector('#phone').setCustomValidity("Please enter a valid "+network+" number.");
    } else {
        document.querySelector('#phone').setCustomValidity("");
    }
}

function getNumberPrefix(destination) {
    return "0" + destination.replace(/\+/, "").replace(/^234/, "").replace(/^0/, "").substr(0, 3)
}

function checkEnteredAmount() {
    var bundleValue = event.target.value;
    if (bundleValue > balance) {
        document.querySelector('#amount')
            .setCustomValidity("Amount above balance (#" + balance + ")");
    } else {
        document.querySelector('#amount').setCustomValidity("");
    }
}

function checkSelectedBundle() {
    var bundleValue = event.target.value;
    if (bundleValue > balance) {
        document.querySelector('#bundle')
            .setCustomValidity("Price of bundle above balance (#" + balance + ")");
    } else {
        document.querySelector('#bundle').setCustomValidity("");
    }
}