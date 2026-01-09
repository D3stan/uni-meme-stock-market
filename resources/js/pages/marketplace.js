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
                img.addEventListener('error', checkAllLoaded); // Procedi anche in caso di errore
            }
        });
    }

    // --- 2. Filter Click Logic (Content -> Skeleton) ---
    filters.forEach(filter => {
        filter.addEventListener('click', (e) => {
            feed.classList.add('hidden');
            skeleton.classList.remove('hidden');
        });
    });

    // --- 3. Infinite Scroll Logic ---
    let currentPage = 1;
    let lastPage = false;
    let isLoading = false;
    const filter = new URLSearchParams(window.location.search).get('filter') || 'all';

    async function loadMoreMemes() {
        if (isLoading || lastPage) return;
        isLoading = true;
        skeleton.classList.remove('hidden');
        try {
            const nextPage = currentPage + 1;
            const url = `/marketplace/ajax?filter=${filter}&page=${nextPage}`;
            const res = await fetch(url);
            if (!res.ok) throw new Error('Errore caricamento');
            const data = await res.json();
            if (data.data && data.data.length > 0) {
                data.data.forEach(meme => {
                    feed.insertAdjacentHTML('beforeend', meme.html);
                });
                currentPage = data.current_page;
                lastPage = data.current_page >= data.last_page;
            } else {
                lastPage = true;
            }
        } catch (e) {
            // Opzionalmente mostra errore
        } finally {
            skeleton.classList.add('hidden');
            isLoading = false;
        }
    }

    // Listener evento scroll per raggiungere la fine della pagina
    window.addEventListener('scroll', () => {
        if (lastPage || isLoading) return;
        const scrollY = window.scrollY || window.pageYOffset;
        const viewport = window.innerHeight;
        const fullHeight = document.body.offsetHeight;
        if (scrollY + viewport > fullHeight - 300) {
            loadMoreMemes();
        }
    });
}
