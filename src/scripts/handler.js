async function handleToggle(ev) {
    const id = ev.parentElement.parentElement.id;
    await sendRequest("POST", { id: id, action: 'toggleTodo' }, true);
}

async function handleDetails(ev) {
    const id = ev.id;
    const details = await sendRequest("POST", { id: id, action: 'toggleDescription' }, false, (data) => {
        console.log(data)
    });
}


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