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
