function calculateGridItems(container, minItemWidthEm) {
    let containerWidth = container.getBoundingClientRect().width;
    let containerFontSize = parseFloat(window.getComputedStyle(container).fontSize);
    let minItemWidth = minItemWidthEm * containerFontSize;
    let gap = 0.5 * containerFontSize;

    let nbItems = Math.floor((containerWidth + gap) / (minItemWidth + gap));
    let totalGap = gap * (nbItems - 1);
    let remainingSpace = containerWidth - (nbItems * minItemWidth + totalGap);
    let itemWidth = minItemWidth + (remainingSpace / nbItems);

    return [nbItems, itemWidth];
}

document.addEventListener('DOMContentLoaded', function() {
    let itemListGrid = document.querySelector('#open > div');
    let minItemWidthEm = window.matchMedia('(min-width: 600px)').matches ? 8 : 6;
    let [nbLisPerRow, lisWidth] =  calculateGridItems(itemListGrid, minItemWidthEm);
    let invLis = document.querySelectorAll('#animation ul li');
    invLis.forEach(function(li) {
        // Setting manually calculated width for each li
        li.style.width = `${lisWidth}px`;
    });

    // Adapting animation fade to calculated width, starting by each side by half of lisWidth then a full lisWidth fade towards the middle
    document.querySelector('#animation div').style.background = `radial-gradient(circle, transparent calc(100% - 1.5 * ${lisWidth}px), var(--darker-blue) calc(100% - ${lisWidth}px / 2))`;

    // Animation
    let animationWidth = document.getElementById('animation').getBoundingClientRect().width;
    let animationUl = document.querySelector('#animation ul');
    let animationUlWidth = animationUl.getBoundingClientRect().width;
    setTimeout(function() {
        // The cursor will be in the middle of nothing if nbLisPerRow is pair, moving by half of lisWidth if so
        if (nbLisPerRow % 2 !== 0) {
            animationUl.style.left = `calc(-${animationUlWidth}px + ${animationWidth}px)`;
        } else {
            animationUl.style.left = `calc(-${animationUlWidth}px + ${animationWidth}px + ${lisWidth}px  / 2)`;
        }
    }, 1000);
});
