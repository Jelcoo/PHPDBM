function setUrlQuery(...data) {
    const urlParams = new URLSearchParams(window.location.search);
    for (const d of data) {
        urlParams.set(d[0], d[1]);
    }
    window.location.search = urlParams.toString();
}

function getUrlQuery(key) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(key);
}

function removeUrlQuery(keys) {
    const urlParams = new URLSearchParams(window.location.search);
    for (const key of keys) {
        urlParams.delete(key);
    }

    window.location.search = urlParams.toString();
}

// Source: https://gist.github.com/ionurboz/51b505ee3281cd713747b4a84d69f434
function debounce(fn, delay) {
    var timer = null;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            fn.apply(context, args);
        }, delay);
    };
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function handleResponse(response) {
    const statusBanner = document.querySelector('.alert');

    try {
        const data = JSON.parse(response);

        if (data.type === 'redirect') {
            statusBanner.classList = 'alert alert-success';
            
            setTimeout(() => {
                window.location.href = data.message;
            }, 2000);
            return;
        } else {
            const statusClass = data.type === 'success' ? 'alert-success'
            : data.type === 'warning' ? 'alert-warning'
            : 'alert-danger';
            statusBanner.classList = 'alert ' + statusClass;
            statusBanner.textContent = data.message;
        }
    } catch (e) {
        statusBanner.classList = 'alert alert-danger';
        statusBanner.textContent = response;
        return;
    }
}
