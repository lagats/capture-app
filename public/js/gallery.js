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
                // alt: 'Wedding Photo', // Adjust based on your data or add logic for dynamic alt text
                // title: 'Wedding Photo', // Adjust based on your data or add logic for dynamic title
                decoding: 'async',
                loading: 'lazy'
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


