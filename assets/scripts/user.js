function updateSort(sortValue) {
    const url = new URL(window.location.href);
    if (sortValue === 'default') {
        url.searchParams.delete('sort');
    } else {
        url.searchParams.set('sort', sortValue);
    }
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Highlight navigation item based on current page
    let url = window.location.href;
    let path = url.split('/');
    let lastField = path[path.length - 1];
    let navLis = document.querySelectorAll('#inventory nav li');
    navLis.forEach(function(li) {
        // If no target inventory was specified (one / split shorter), skins is default
        if ((path.length === 5 && li.textContent === "Skins") ||
            (path.length === 6 && lastField.startsWith(li.textContent.toLowerCase()))) {
            li.classList.add('selected');
        }
    });

    // Event listeners for hover divs for items details
    let invLis = document.querySelectorAll('#inventory > ul li');
    invLis.forEach(function(li) {
        let timer;
        let subDiv = li.getElementsByTagName('div')[0];

        li.addEventListener('mouseenter', function() {
            timer = setTimeout(function() {
                subDiv.classList.remove('retractedDiv');
                setTimeout(function() {
                    subDiv.classList.add('extendedDiv');
                }, 100);
            }, 500);
        });
        li.addEventListener('mouseleave', function() {
            clearTimeout(timer);
            subDiv.classList.remove('extendedDiv');
            subDiv.classList.add('retractedDiv');
        });
    });
});
