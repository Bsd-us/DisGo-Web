document.addEventListener('DOMContentLoaded', function() {
    // Highlight navigation item based on current page
    let url = window.location.href;
    let path = url.split('/');
    let lastField = path[path.length - 1];
    let navLis = document.querySelectorAll('#user nav li');
    navLis.forEach(function(li) {
        // If no target inventory was specified (one / split shorter), skins is default
        if ((path.length === 5 && li.textContent === "Skins") ||
            (path.length === 6 && lastField.startsWith(li.textContent.toLowerCase()))) {
            li.classList.add('selected');
        }
    });
});
