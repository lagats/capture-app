// -------------------
// Load images
// -------------------

(function(){

    // Create capture object if it doesn't exist
    window.capture = window.capture || {};
    const capture = window.capture || {};

    // elements
    const imageContainer = document.getElementById('image-container');
    const loader = document.getElementById('loader');

    // vars
    let ready = false;
    let imagesLoaded = 0;
    let totalImages = 0;
    let photosArray = [];
    let currentPage = 0;
    let itemsPerPage = 12;
    let isMyCaptures = document.body.classList.contains('gallery--my_captures');

    // Your custom API endpoint
    const apiUrl = './media';

    function updateAPIUrlWithNewPage() {
        currentPage++;
        let query = `${apiUrl}?page=${currentPage}&count=${itemsPerPage}`;
        if(isMyCaptures){
            query += '&my_captures=true';
        }
        return query;
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
        photosArray.forEach((photo, index) => {
            const photoId = ((currentPage - 1) * itemsPerPage) + index + 1;
            // setup div
            const div = document.createElement('div');
            setAttributes(div, {
                class: 'masonry-item',
            });
            // Setup checkbox 
            const checkbox = document.createElement('input');
            setAttributes(checkbox, {
                type: 'checkbox',
                id: `checkbox-${photoId}`,
                class: 'masonry-checkbox',
                value: photo.name,
            });
            checkbox.addEventListener('change', function() {
                const event = new Event('checkboxChange');
                      event.target = this;
                window.dispatchEvent(event);
            });
            // Setup checkbox label
            const label = document.createElement('label');
            setAttributes(label, {
                for: `checkbox-${photoId}`,
                class: 'masonry-checkbox-label',
            });
            // Modify href based on your API response structure (replace with appropriate field)
            const item = document.createElement('a');
            setAttributes(item, {
                class: 'masonry-link',
                'data-fslightbox': '',
                href: photo.size.original, // Assuming "original" holds the full image URL
                target: '_blank',
            });
            // Modify src based on your API response structure (replace with appropriate field)
            const img = document.createElement('img');
            setAttributes(img, {
                class: 'masonry-content',
                src: photo.size.thumb, // Assuming "thumb" holds the thumbnail URL
                // alt: 'Wedding Photo', // Adjust based on your data or add logic for dynamic alt text
                // title: 'Wedding Photo', // Adjust based on your data or add logic for dynamic title
                decoding: 'async',
                loading: 'lazy',
            });
            // Check when each is finished loading
            item.appendChild(img);
            div.appendChild(checkbox);
            div.appendChild(label);
            div.appendChild(item);
            imageContainer.appendChild(div);
            img.addEventListener('load', imageLoaded);
        });
    }

    async function getPhotos() {
        try {
            const res = await fetch(updateAPIUrlWithNewPage());
            photosArray = await res.json();
            if(photosArray.length > 0) {
                displayPhotos();
            }
            if(photosArray.length === 0) {
                // stop loader if no images
                loader.hidden = true;
                if(currentPage === 1) {
                    const galleryStatus = document.querySelector('#galleryStatus');
                    galleryStatus.textContent = 'No images';
                    galleryStatus.classList.add('visible');
                    galleryStatus.classList.add('center');
                }
                return;
            }
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


