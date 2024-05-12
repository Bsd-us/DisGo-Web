document.addEventListener('DOMContentLoaded', function() {
    let url = window.location.href;
    let path = url.split('/');
    let lastField = path[path.length - 1];
    let navLis = document.querySelectorAll('#inventory nav li');
    navLis.forEach(function(li) {
        if ((path.length === 5 && li.textContent === "Skins") ||
            (path.length === 6 && li.textContent.toLowerCase() === lastField)) {
            li.classList.add('selected');
        }
    });

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
