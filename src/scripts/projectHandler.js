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

function handleFilterTitleClick(event) {
    const target = event.currentTarget;
    const input = target.childNodes.item(2);
    if (event.target.tagName == "INPUT") {
        return;
    }
    input.click();

}

function handleBack() {
    window.location.href = "/index.php";
}

function handleFilterChange(event) {
    const target = event.target;
    const filter = target.dataset.filter.toLowerCase();
    const filters = document.getElementsByClassName('filter-item');
    let isReset = false;
    for (let i = 0; i < filters.length; i++) {
        const input = filters[i].childNodes.item(2);
        if (filters[i].dataset.filter.toLowerCase() == filter) {
            if (!input.checked) {
                isReset = true;
                filters.item(0).childNodes.item(2).checked = true;
            }
            continue;
        }
        input.checked = false;
    }
    const tasks = document.getElementsByClassName('task');
    for (let i = 0; i < tasks.length; i++) {
        if (filter == 'all' || isReset) {
            tasks[i].style.display = "flex";
            continue;
        }
        if (isReset) continue;
        if (filter == 'completed') {
            if (tasks[i].dataset.completed == 'completed') {
                tasks[i].style.display = "flex";
                continue;
            }
        }
        if (filter == 'pending') {
            if (tasks[i].dataset.completed != 'completed') {
                tasks[i].style.display = "flex";
                continue;
            }
        }
        tasks[i].style.display = "none";
    }
}

async function handleCancelChanges() {
    const confirmation = window.confirm("Do you want to cancel your changes?");
    if (!confirmation) return;
    window.location.reload();
}

async function handleSaveChanges(cid) {
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
        if (title.length < 4) {
            alert(`Todo title '${title}' need to have at least 4 characters.`);
            return;
        }
        const description = descriptions[i].value;
        const completed = checkboxes[i].dataset.completed == "completed";
        const tid = titles[i].parentElement.parentElement.id;
        todos.push({ title: title, description: description, completed: completed, id: tid, cid: cid });
    }
    if (title.value.length < 4) {
        alert("Project title need to have at least 4 characters.");
        return;
    }
    const data = {
        title: title.value,
        complete_until: calendar.value,
        id: cid,
        todos: todos,
        action: "save-category"
    }
    await sendRequest("POST", { ...data }, false, (response) => {
        if (!response) {
            window.location.reload();
            return;
        }
        const res = JSON.parse(response);
        if (res.error) {
            alert(res.error);
        }
        window.location.reload();
    });
}
function onChange(event) {
    showSaveBtn();
}
function showSaveBtn() {
    const saveBtn = document.getElementById('save-btn');
    saveBtn.style.display = "flex";
    const createTodoBtn = document.getElementById('add-btn');
    createTodoBtn.style.display = "none";
}

function handleCreateTodo(cid) {
    sendRequest("POST", { action: "create-todo", cid: cid }, false, (response) => {
        console.log(response)
        if (response.indexOf("Error") != -1) {
            alert("You need to fill out the title before creating a new todo");
            return;
        }
        window.location.reload();
    });
}

function handleDeleteTodo(event) {
    const id = event.currentTarget.parentElement.id;
    const confirmation = window.confirm("Do you want to delete this todo?");
    if (!confirmation) return;

    sendRequest("POST", { action: "delete-todo", id: id }, true);
}

function handleDescription(event, id) {
    if (event.target.tagName == "INPUT") return;
    if (event.target.tagName == "TEXTAREA") return;
    if (event.target.tagName == "TEXTAREA") return;
    const description = document.getElementById("description-" + id);
    const arrow = document.getElementById("arrow-" + id);
    const deleteBtn = document.getElementById("delete-" + id);
    if (description.style.display == "block") {
        description.style.display = "none";
        deleteBtn.style.display = "none";
        arrow.dataset.toggled = 'false';
        return;
    }
    arrow.dataset.toggled = 'true';
    deleteBtn.style.display = "block";
    description.style.display = "block"
}

