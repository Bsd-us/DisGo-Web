function updateGetParam(field, value) {
    const url = new URL(window.location.href);
    if (value == 'default' || value == '') {
        url.searchParams.delete(field);
    } else {
        url.searchParams.set(field, value);
    }
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Event listeners for hover divs for items details
    let invLis = document.querySelectorAll('.itemListGrid ul li');
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

        // Detecting if li is at the start of a new row (reversing hover div so it doesn't go off screen)
        if (li.previousElementSibling &&
            li.previousElementSibling.getBoundingClientRect().top < li.getBoundingClientRect().top)
        {
            li.classList.add('firstOfRow');
        }
    });
    invLis[0].classList.add('firstOfRow');

    // Set figure width to match whatever grid auto-fill li width was chosen
    let lisWidth = invLis[0].getBoundingClientRect().width + 'px';
    let invFigures = document.querySelectorAll('.itemListGrid ul figure');
    invFigures.forEach(function(figure) {
        figure.style.width = lisWidth;
    });
});
