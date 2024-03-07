// Check box finish task

const checkboxes = document.querySelectorAll(".task-checkFinish");
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const form = checkbox.parentElement.parentElement
        console.log(form)
        form.submit();
    });
});
