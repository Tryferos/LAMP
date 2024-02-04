async function handleToggle(event) {
    event.stopPropagation();
    event.preventDefault();
    const completed = event.currentTarget.dataset.completed;
    showSaveBtn();
    if (completed == 'completed') {
        event.currentTarget.dataset.completed = 'incomplete';
        return;
    }
    event.currentTarget.dataset.completed = 'completed';
}

async function handleSave(cid) {
    const confirmation = window.confirm("Do you want to save your changes?")
    if (!confirmation) return;
    const calendar = document.getElementById("calendar-input");
    const title = document.getElementById("c" + cid);
    const descriptions = document.getElementsByClassName("description-input");
    const titles = document.getElementsByClassName("todo-title-input");
    const checkboxes = document.getElementsByClassName("checkbox-input");
    let todos = [];
    for (let i = 0; i < titles.length; i++) {
        const title = titles[i].value;
        const description = descriptions[i].value;
        const completed = checkboxes[i].dataset.completed == "completed";
        const tid = titles[i].parentElement.parentElement.id;
        todos.push({ title: title, description: description, completed: completed, id: tid, cid: cid });
    }
    const data = {
        title: title.value,
        complete_until: calendar.value,
        id: cid,
        todos: todos,
        action: "save-category"
    }
    await sendRequest("POST", { ...data }, true);
}
function onChange(event) {
    showSaveBtn();
}
function showSaveBtn() {
    const saveBtn = document.getElementById('save-btn');
    saveBtn.style.display = "block";
}

function handleDescription(event, id) {
    if (event.target.tagName == "INPUT") return;
    if (event.target.tagName == "TEXTAREA") return;
    if (event.target.tagName == "TEXTAREA") return;
    let tid; let rid;
    tid = "description-" + id;
    rid = "arrow-" + id;
    const description = document.getElementById(tid);
    const arrow = document.getElementById(rid);
    if (description.style.display == "block") {
        description.style.display = "none";
        arrow.dataset.toggled = 'false';
        return;
    }
    arrow.dataset.toggled = 'true';
    description.style.display = "block"
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