function handleAddProject() {
    sendRequest('POST', { action: 'create-category' }, false, (response) => {
        const category = JSON.parse(response);
        if (category == null || category == undefined || category == "") {
            alert("Please finish creating the current project before creating a new one.");
            return;
        }
        window.location.href = `./project.php?id=${category.id}`;
    });
}