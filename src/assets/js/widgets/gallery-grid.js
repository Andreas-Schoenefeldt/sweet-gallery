import Widgets from 'js-widget-hooks';

const heightPartInViewPortToShow = 2/3;

const isInViewport = function (elem) {
    const bounding = elem.getBoundingClientRect();

    return (
        bounding.top >= 0
        && bounding.left >= 0

            // 2/3 of the thing in the viewport are enough
        && bounding.bottom - (bounding.height * heightPartInViewPortToShow) <= (window.innerHeight || document.documentElement.clientHeight)
        // && bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
};


Widgets.register('gallery-grid', function (el) {
    let tiles = [...el.querySelectorAll('.swg-gallery__preview')];
    let running = false;

    console.log(tiles);

    const scrollListener = function (e) {
        if (!running) {
            running = true;
            window.setTimeout(checkVisibility, 10);
        }
    };

    const checkVisibility = function () {
        running = true;
        let processedTiles = [];
        tiles.forEach(function (tile, index) {
            if (isInViewport(tile)) {

                // add a bit ov variability
                window.setTimeout(function () {
                    tile.classList.add('visible');
                }, Math.random() * 500);

                // forget about this tile
                processedTiles.push(index);
            }
        });

        processedTiles.reverse().forEach(function (index) {
            tiles.splice(index, 1);
        });

        if (!tiles.length) {
            window.removeEventListener('scroll', scrollListener);
            console.log('removed event listener');
        }

        running = false;
    };

    checkVisibility();

    console.log(tiles);

    if (tiles.length) {
        window.addEventListener('scroll', scrollListener);
    }
});