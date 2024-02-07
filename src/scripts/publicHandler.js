async function sendRequest(method, data, refresh = false, callback = () => { }) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, "/handler.php", true);
    xhr.send(JSON.stringify(data));
    xhr.onload = () => {
        if (refresh) {
            window.location.reload();
        }
        callback(xhr.responseText);
    }
}