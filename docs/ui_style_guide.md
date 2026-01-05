# UI Style Guide & Design System

This document outlines the technical implementation of the AlmaStreet "Modern Fintech" aesthetic, ensuring consistency across the application using semantic tokens and Tailwind CSS.

## 1. Color System (Semantic Palette)

We avoid hardcoded hex values in components. Instead, we use semantic roles defined in `resources/css/app.css`.

### Brand Identity
*   **Primary (`--color-brand`)**: #10b981 (Emerald 500). Used for "Buy", "Profit", "Success", and primary CTAs.
*   **Danger (`--color-brand-danger`)**: #ef4444 (Red 500). Used for "Sell", "Loss", "Error", and destructive actions.

### Dark Mode Surface System
To create depth without relying solely on flat colors, we use a layered surface system:
*   **Background (`--color-surface-50`)**: #0f172a (Slate 900). The deepest layer, used for the main app background.
*   **Card (`--color-surface-100`)**: #1e293b (Slate 800). Used for cards, modals, and elevated sections.
*   **Highlight (`--color-surface-200`)**: #334155 (Slate 700). Used for borders, hover states, and secondary interactives.

---

## 2. Implementation (`resources/css/app.css`)

The following configuration is applied via Tailwind v4 `@theme`:

```css
@theme {
    /* Semantic Colors */
    --color-brand: #10b981;
    --color-brand-dark: #064e3b;
    --color-brand-light: #34d399;

    --color-brand-success: #10b981;
    --color-brand-danger: #ef4444;

    /* Surface Layers */
    --color-surface-50: #0f172a;
    --color-surface-100: #1e293b;
    --color-surface-200: #334155;
    
    /* Text Hierarchy */
    --color-text-main: #f8fafc;  /* Slate 50 */
    --color-text-muted: #94a3b8; /* Slate 400 */
}

@layer components {
    /* Reusable Utility Classes */
    
    /* Financial text */
    .price-positive { @apply text-brand font-bold font-mono; }
    .price-negative { @apply text-brand-danger font-bold font-mono; }
    
    /* Semantic Badges */
    .badge-positive { 
        @apply bg-brand/10 text-brand border border-brand/20 px-2 py-0.5 rounded text-xs font-bold uppercase; 
    }
    .badge-negative { 
        @apply bg-brand-danger/10 text-brand-danger border border-brand-danger/20 px-2 py-0.5 rounded text-xs font-bold uppercase; 
    }

    /* Structural Components */
    .card-base { 
        @apply bg-surface-100 border border-surface-200 rounded-2xl shadow-lg overflow-hidden;
    }
    
    .input-base {
        @apply bg-surface-50 border-surface-200 text-text-main focus:ring-brand focus:border-brand rounded-xl block w-full p-3 transition-colors;
    }
}
```

---

## 3. Component Usage Examples

### A. Primary Buttons (Actions)

Use `bg-brand` for primary "Go" actions and `bg-brand-danger` for "Stop/Sell" actions.

```html
<!-- Buy Action -->
<button class="bg-brand hover:bg-brand-light text-surface-50 font-bold py-3 px-6 rounded-xl transition-colors shadow-lg shadow-brand/20">
    COMPRA ORA
</button>

<!-- Sell Action -->
<button class="bg-brand-danger hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-red-500/20">
    VENDI TUTTO
</button>
```

### B. Meme Card (Layered Depth)

Use `bg-surface-100` for the card body to lift it from the `bg-surface-50` page background.

```html
<div class="card-base">
    <div class="relative">
        <img src="{{ $image }}" class="w-full aspect-video object-cover" />
        <div class="absolute top-2 right-2 badge-positive">
            NEW
        </div>
    </div>
    
    <div class="p-4">
        <h3 class="text-text-main text-lg font-bold">{{ $title }}</h3>
        <p class="text-text-muted text-xs uppercase tracking-wider mb-3">{{ $ticker }}</p>
        
        <div class="flex justify-between items-center pt-3 border-t border-surface-200">
            <!-- Price uses brand color for emphasis -->
            <span class="text-brand font-mono text-xl font-bold">{{ $price }} CFU</span>
            
            <!-- Semantic percentage badge -->
            <span class="badge-positive">
                +12.5%
            </span>
        </div>
    </div>
</div>
```

### C. Financial Ticker (Text Utilities)

Use `text-brand` and `text-brand-danger` for trend indicators.

```html
<div class="bg-surface-50 border-b border-surface-200 py-2">
    <div class="flex gap-8 overflow-hidden">
        <div class="flex items-center gap-2">
            <span class="text-text-muted text-xs font-bold uppercase">Trending:</span>
            <span class="text-text-main font-bold">$PIKA</span>
            <span class="price-positive">+5.2%</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-text-main font-bold">$DOGE</span>
            <span class="price-negative">-2.1%</span>
        </div>
    </div>
</div>
```

### D. Input Fields (Forms)

Use `input-base` for consistent styling of form elements.

```html
<div>
    <label class="block mb-2 text-sm font-medium text-text-muted uppercase">Quantità</label>
    <input type="number" class="input-base text-center text-2xl font-mono font-bold" placeholder="0">
    <p class="mt-1 text-xs text-text-muted">Saldo disponibile: 1,250.00 CFU</p>
</div>
```
