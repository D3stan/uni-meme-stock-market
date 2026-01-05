/**
 * Admin Moderation Management
 */

function openModerationModal(button) {
    // Get data from button attributes
    const id = button.dataset.id;
    const ticker = button.dataset.ticker;
    const title = button.dataset.title;
    const image = button.dataset.image;
    const textAlt = button.dataset.textAlt;
    const creator = button.dataset.creator;
    const creatorId = button.dataset.creatorId;
    const creatorAvatar = button.dataset.creatorAvatar;
    const price = parseFloat(button.dataset.price);
    const status = button.dataset.status;

    // Update card elements
    const card = document.getElementById('moderation-card');
    if (!card) return;
    
    // Update avatar (first img in card)
    const avatarImg = card.querySelector('.w-8.h-8.rounded-full');
    if (avatarImg) {
        avatarImg.src = creatorAvatar;
        avatarImg.alt = creator;
    }
    
    // Update title and ticker
    const titleEl = card.querySelector('h3.text-sm');
    if (titleEl) titleEl.textContent = title;
    
    const tickerEl = card.querySelector('.text-xs.text-text-muted');
    if (tickerEl) tickerEl.textContent = '$' + ticker;
    
    // Update status badge
    const statusBadge = card.querySelector('.px-2.py-1.text-xs.font-semibold.rounded-md.border');
    if (statusBadge) {
        statusBadge.className = 'px-2 py-1 text-xs font-semibold rounded-md border ' + getStatusClasses(status);
        statusBadge.textContent = getStatusText(status);
    }
    
    // Update meme image (the big image, not the avatar)
    const memeImg = card.querySelector('a.block img');
    if (memeImg) {
        // Handle storage path format: data/{userId}/{image}
        const imageSrc = image.startsWith('http') || image.startsWith('/storage/') 
            ? image 
            : `/storage/data/${creatorId}/${image}`;
        memeImg.src = imageSrc;
        memeImg.alt = textAlt;
    }
    
    // Update price
    const priceEl = card.querySelector('.text-2xl.font-bold.font-mono');
    if (priceEl) priceEl.textContent = price.toFixed(2);

    // Update text alternative
    const textAltEl = document.getElementById('meme-alt-text');
    if (textAltEl) {
        textAltEl.textContent = textAlt || 'Nessuna alternativa testuale disponibile';
    }

    // Set form actions
    document.getElementById('approveForm').action = `/admin/moderation/${id}/approve`;
    document.getElementById('rejectForm').action = `/admin/moderation/${id}/reject`;

    // Show modal
    showModal('moderationModal');
}

function getStatusClasses(status) {
    const classes = {
        'pending': 'bg-brand-accent/10 text-brand-accent border-brand-accent/20',
        'approved': 'bg-brand/10 text-brand border-brand/20',
        'suspended': 'bg-brand-danger/10 text-brand-danger border-brand-danger/20',
    };
    return classes[status] || 'bg-surface-200/30 text-text-muted border-surface-200';
}

function getStatusText(status) {
    const texts = {
        'pending': 'In Attesa',
        'approved': 'Approvato',
        'suspended': 'Rifiutato',
    };
    return texts[status] || status;
}

// Event delegation for view buttons
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.view-meme-btn');
        if (button) {
            openModerationModal(button);
        }
    });
});
