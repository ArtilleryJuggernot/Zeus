function showNotification(message, type) {
    console.log("Entrez notification système")
    var notification = document.getElementById('notification');

    if (type === 'success') {
        notification.classList.add('success');
        notification.textContent = "✅ ";
    } else if (type === 'failure') {
        notification.classList.add('failure');
        notification.textContent = "❎ ";
    }

    notification.textContent += message;

    notification.style.opacity = 1;

    setTimeout(function() {
        notification.style.opacity = 0;
        notification.classList.remove('success', 'failure');
        notification.textContent = "";
    }, 3000);

}
