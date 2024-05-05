// public/js/file_manager.js

document.addEventListener("DOMContentLoaded", function() {
    var folders = document.querySelectorAll(".folder-label");

    folders.forEach(function(folder) {
        folder.addEventListener("click", function() {
            var subfolders = this.nextElementSibling;
            if (subfolders.style.display === "none" || subfolders.style.display === "") {
                subfolders.style.display = "block";
            } else {
                subfolders.style.display = "none";
            }
        });
    });
});
