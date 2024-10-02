// -------------------
// Grid Layout
// -------------------

(function(){

    /**
     * Set appropriate spanning to any masonry item
     *
     * Get different properties we already set for the masonry, calculate 
     * height or spanning for any cell of the masonry grid based on its 
     * content-wrapper's height, the (row) gap of the grid, and the size 
     * of the implicit row tracks.
     *
     * @param item Object A brick/tile/cell inside the masonry
     */
    function resizeMasonryItem(item){
        /* Get the grid object, its row-gap, and the size of its implicit rows */
        var grid = document.getElementsByClassName('masonry')[0],
            rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap')),
            rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows'));
    
        /*
        * Spanning for any brick = S
        * Grid's row-gap = G
        * Size of grid's implicitly create row-track = R
        * Height of item content = H
        * Net height of the item = H1 = H + G
        * Net height of the implicit row-track = T = G + R
        * S = H1 / T
        */
        var rowSpan = Math.ceil((item.querySelector('.masonry-content').getBoundingClientRect().height+rowGap)/(rowHeight+rowGap));
    
        /* Set the spanning as calculated above (S) */
        item.style.gridRowEnd = 'span '+rowSpan;
    
        /* Make the images take all the available space in the cell/item */
        item.querySelector('.masonry-content').style.height = rowSpan * 10 + "px";
    }
    
    /**
     * Apply spanning to all the masonry items
     *
     * Loop through all the items and apply the spanning to them using 
     * `resizeMasonryItem()` function.
     *
     * @uses resizeMasonryItem
     */
    function resizeAllMasonryItems(){
        // Get all item class objects in one list
        var allItems = document.getElementsByClassName('masonry-item');
    
        /*
        * Loop through the above list and execute the spanning function to
        * each list-item (i.e. each masonry item)
        */
        for(var i=0;i>allItems.length;i++){
            resizeMasonryItem(allItems[i]);
        }
    }
    
    /**
     * Resize the items when all the images inside the masonry grid 
     * finish loading. This will ensure that all the content inside our
     * masonry items is visible.
     *
     * @uses ImagesLoaded
     * @uses resizeMasonryItem
     */
    function waitForImages() {
        var allItems = document.getElementsByClassName('masonry-item');
        for(var i=0;i<allItems.length;i++){
            imagesLoaded( allItems[i], function(instance) {
                var item = instance.elements[0];
                resizeMasonryItem(item);
            } );
        }
    }
    
    /* Resize all the grid items on the load and resize events */
    var masonryEvents = ['load', 'resize'];
    masonryEvents.forEach( function(event) {
        window.addEventListener(event, resizeAllMasonryItems);
    } );
    
    /* Do a resize once more when all the images finish loading */
    // waitForImages();

    // Make functions avaliable in global scope
    window.captureApp = {
        resizeAllMasonryItems,
        waitForImages
    };

})();



// -------------------
// Load images
// -------------------

(function(){

    const imageContainer = document.getElementById('image-container');
    const loader = document.getElementById('loader');

    let ready = false;
    let imagesLoaded = 0;
    let totalImages = 0;
    let photosArray = [];
    let currentPage = 0;
    let itemsPerPage = 12;

    // Your custom API endpoint
    const apiUrl = 'https://wedding.lagats.com/capture/media';

    function updateAPIUrlWithNewPage() {
        currentPage++;
        return `${apiUrl}?page=${currentPage}&count=${itemsPerPage}`;
    }

    function imageLoaded() {
        imagesLoaded++;
        if (imagesLoaded === totalImages) {
            ready = true;
            loader.hidden = true;

            // rebind lightbox
            if (typeof refreshFsLightbox === "function") { 
                // referesh 'fslightbox'
                refreshFsLightbox();
            }

            // update grid
            window.captureApp.resizeAllMasonryItems();
            window.captureApp.waitForImages();

            // check if there is still room on page
            checkScrollPosition();
        }
    }

    function setAttributes(element, attributes) {
    for (const key in attributes) {
        element.setAttribute(key, attributes[key]);
    }
    }

    function displayPhotos() {
        imagesLoaded = 0;
        totalImages = photosArray.length;
        photosArray.forEach((photo) => {
            const item = document.createElement('a');
            // Modify href based on your API response structure (replace with appropriate field)
            setAttributes(item, {
                class: 'masonry-item',
                'data-fslightbox': '',
                href: photo.size.original, // Assuming "original" holds the full image URL
                target: '_blank',
            });
            const img = document.createElement('img');
            // Modify src based on your API response structure (replace with appropriate field)
            setAttributes(img, {
                class: 'masonry-content',
                src: photo.size.thumb, // Assuming "thumb" holds the thumbnail URL
                alt: 'Wedding Photo', // Adjust based on your data or add logic for dynamic alt text
                title: 'Wedding Photo', // Adjust based on your data or add logic for dynamic title
                decoding: 'async'
            });
            // Check when each is finished loading
            img.addEventListener('load', imageLoaded);
            item.appendChild(img);
            imageContainer.appendChild(item);
        });
    }

    async function getPhotos() {
        try {
            const res = await fetch(updateAPIUrlWithNewPage());
            photosArray = await res.json();
            displayPhotos();
        } catch (err) {
            console.log(err);
        }
    }

    function checkScrollPosition() {
        if (
            window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000 &&
            ready
        ) {
            ready = false;
            getPhotos();
        }
    }

    // Check to see if scrolling near bottom of page; load more photos
    window.addEventListener('scroll', checkScrollPosition);

    // On Load
    getPhotos();

})();


