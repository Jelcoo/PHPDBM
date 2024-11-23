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
