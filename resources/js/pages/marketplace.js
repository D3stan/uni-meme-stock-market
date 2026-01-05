export function initMarketplace() {
    const page = document.querySelector('[data-page="marketplace"]');
    if (!page) return;

    const feed = document.getElementById('meme-feed');
    const skeleton = document.getElementById('meme-feed-skeleton');
    const filters = document.querySelectorAll('a[href*="filter="]');

    if (!feed || !skeleton) return;

    // --- 1. Initial Load Logic (Skeleton -> Content) ---
    
    // Minimum time to show skeleton (in ms) to prevent flickering on fast loads
    const MIN_SKELETON_TIME = 500; 
    const startTime = Date.now();

    const showContent = () => {
        const elapsedTime = Date.now() - startTime;
        const remainingTime = Math.max(0, MIN_SKELETON_TIME - elapsedTime);

        setTimeout(() => {
            skeleton.classList.add('hidden');
            feed.classList.remove('hidden');
        }, remainingTime);
    };

    // Check if images are loaded
    const images = feed.querySelectorAll('img');
    let imagesLoaded = 0;
    const totalImages = images.length;

    if (totalImages === 0) {
        showContent();
    } else {
        const checkAllLoaded = () => {
            imagesLoaded++;
            if (imagesLoaded >= totalImages) {
                showContent();
            }
        };

        images.forEach(img => {
            if (img.complete) {
                checkAllLoaded();
            } else {
                img.addEventListener('load', checkAllLoaded);
                img.addEventListener('error', checkAllLoaded); // Proceed even on error
            }
        });
    }

    // --- 2. Filter Click Logic (Content -> Skeleton) ---

    filters.forEach(filter => {
        filter.addEventListener('click', (e) => {
            // Visual toggle to show something is happening before navigation
            feed.classList.add('hidden');
            skeleton.classList.remove('hidden');
            
            // Allow default navigation to proceed
        });
    });
}
