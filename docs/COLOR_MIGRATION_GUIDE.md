# Color Migration Guide - AlmaStreet Semantic Color System

## Overview
We are migrating from hardcoded Tailwind color classes (e.g., `bg-blue-600`, `text-green-500`) to a semantic color system defined in `resources/css/app.css`. This ensures consistency, simplifies theming, and aligns with our Modern Fintech design aesthetic.

---

## Semantic Color Tokens

### Brand Colors (Primary Actions)
- **`brand`** (#10b981 - Emerald): Buy actions, profit, success, primary CTAs
- **`brand-light`** (#34d399): Hover states for brand
- **`brand-dark`** (#064e3b): Dark variant for brand

### Danger Colors (Destructive Actions)
- **`brand-danger`** (#ef4444 - Red): Sell actions, loss, errors, destructive actions
- **`brand-danger-dark`** (#991b1b): Dark variant for danger

### Accent Colors (Informational)
- **`brand-accent`** (#3b82f6 - Blue): Info badges, neutral stats, non-critical highlights

### Surface/Background Layers
- **`surface-50`** (#0f172a - Slate 900): Deepest layer, main app background
- **`surface-100`** (#1e293b - Slate 800): Cards, modals, elevated sections
- **`surface-200`** (#334155 - Slate 700): Borders, hover states, secondary interactives

### Text Hierarchy
- **`text-main`** (#f8fafc - Slate 50): Primary text, headings
- **`text-muted`** (#94a3b8 - Slate 400): Secondary text, labels, timestamps

---

## Migration Rules

### ✅ DO Use Semantic Colors For:
1. **Financial Indicators**: Profits/Buy → `brand`, Losses/Sell → `brand-danger`
2. **Primary Buttons**: CTAs → `bg-brand hover:bg-brand-light`
3. **Danger Buttons**: Destructive actions → `bg-brand-danger hover:bg-brand-danger-dark`
4. **Info Elements**: Stats, badges → `brand-accent`
5. **Backgrounds**: Cards → `bg-surface-100`, Page → `bg-surface-50`
6. **Borders**: `border-surface-200`
7. **Text**: Primary → `text-main`, Secondary → `text-muted`

### ❌ DON'T Use Hardcoded Colors Like:
- ❌ `bg-blue-600`, `text-green-500`, `border-red-400`
- ❌ `bg-emerald-500`, `text-slate-400`
- ❌ Direct hex values in Tailwind classes

### 🎨 Use Utility Classes (When Available):
Prefer these pre-built classes from `@layer components` in `app.css`:
- `.price-positive` - For positive price changes
- `.price-negative` - For negative price changes
- `.badge-positive` - Green badge for gains/new/active
- `.badge-negative` - Red badge for losses/down
- `.badge-info` - Blue badge for neutral info
- `.btn-primary` - Primary CTA button
- `.btn-danger` - Destructive action button
- `.card-base` - Standard card styling
- `.input-base` - Form input styling

---

## Migration Examples

### Example 1: Button Migration
**Before:**
```html
<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
    Buy Now
</button>
```

**After:**
```html
<button class="bg-brand hover:bg-brand-light text-text-main px-4 py-2 rounded-xl">
    Buy Now
</button>
```
Or use the utility class:
```html
<button class="btn-primary">Buy Now</button>
```

### Example 2: Badge Migration
**Before:**
```html
<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
    +5.2%
</span>
```

**After:**
```html
<span class="badge-positive">+5.2%</span>
```

### Example 3: Card Migration
**Before:**
```html
<div class="bg-slate-800 border border-slate-700 rounded-lg p-4">
    Content
</div>
```

**After:**
```html
<div class="card-base p-4">
    Content
</div>
```
Or manually:
```html
<div class="bg-surface-100 border border-surface-200 rounded-2xl p-4">
    Content
</div>
```

### Example 4: JavaScript Dynamic Classes
**Before:**
```javascript
const changeClass = isProfit 
    ? 'text-green-500' 
    : 'text-red-500';
```

**After:**
```javascript
const changeClass = isProfit 
    ? 'text-brand' 
    : 'text-brand-danger';
```

---

## Files Already Migrated ✅

### UI Components (11 files)
- ✅ `resources/views/components/ui/badge-change.blade.php`
- ✅ `resources/views/components/ui/action-bar.blade.php`
- ✅ `resources/views/components/navigation/navigation-bar.blade.php`
- ✅ `resources/views/components/ui/stat-card.blade.php`
- ✅ `resources/views/components/ui/modal.blade.php`
- ✅ `resources/views/components/ui/toast.blade.php`
- ✅ `resources/views/components/ui/trend-card.blade.php`
- ✅ `resources/views/components/ui/empty-state.blade.php`
- ✅ `resources/views/components/ui/confirmation-modal.blade.php`
- ✅ `resources/views/components/ui/table.blade.php`
- ✅ `resources/views/components/ui/options-card.blade.php`

### Trading Components (5 files)
- ✅ `resources/views/components/trading/price-header.blade.php`
- ✅ `resources/views/components/trading/slippage-modal.blade.php`
- ✅ `resources/views/components/trading/stats-section.blade.php`
- ✅ `resources/views/components/trading/order-modal.blade.php`
- ✅ `resources/views/components/trading/chart-toggle.blade.php`

### Profile Components (4 files)
- ✅ `resources/views/components/profile/stats-grid.blade.php`
- ✅ `resources/views/components/profile/settings-button.blade.php`
- ✅ `resources/views/components/profile/password-modal.blade.php`
- ✅ `resources/views/components/profile/menu-options.blade.php`

### Form Components (2 files)
- ✅ `resources/views/components/forms/button.blade.php`
- ✅ `resources/views/components/forms/input.blade.php`

### JavaScript Files (6 files)
- ✅ `resources/js/pages/trading.js`
- ✅ `resources/js/services/NotificationService.js`
- ✅ `resources/js/components/OrderModal.js`
- ✅ `resources/js/components/Chart.js`
- ✅ `resources/js/pages/otp-verification.js`
- ✅ `resources/js/pages/settings.js`

### Pages (2 files)
- ✅ `resources/views/memes/create.blade.php`
- ✅ `resources/views/welcome.blade.php` (landing page)

### Admin Pages (3 files)
- ✅ `resources/views/admin/notifications.blade.php`
- ✅ `resources/views/admin/ledger.blade.php`
- ✅ `resources/views/admin/events.blade.php`

---

## Files Still Needing Migration 🔧

Run this search to find remaining hardcoded colors:
```bash
# Search for common hardcoded color patterns
grep -r "bg-blue-\|text-green-\|border-red-\|bg-emerald-\|text-slate-\|bg-gray-" resources/views --include="*.blade.php"
grep -r "bg-blue-\|text-green-\|border-red-\|bg-emerald-\|text-slate-\|bg-gray-" resources/js --include="*.js"
```

### Known Areas to Check:
1. **Admin Dashboard Pages**
   - `resources/views/admin/dashboard.blade.php`
   - `resources/views/admin/memes/*.blade.php`
   - `resources/views/admin/users/*.blade.php`
   
2. **Meme Pages**
   - `resources/views/memes/index.blade.php`
   - `resources/views/memes/show.blade.php`
   - `resources/views/memes/edit.blade.php`

3. **User Portfolio/Profile Pages**
   - `resources/views/portfolio/*.blade.php`
   - `resources/views/profile/*.blade.php`

4. **Auth Pages**
   - `resources/views/auth/*.blade.php`

5. **Remaining JavaScript**
   - `resources/js/components/*.js` (any components not yet migrated)
   - `resources/js/pages/*.js` (any pages not yet migrated)

---

## Testing Checklist

After migrating a component, verify:

1. ✅ **Visual Consistency**: Colors match the design system
2. ✅ **Hover States**: Interactive elements have proper hover feedback
3. ✅ **Focus States**: Focus rings use `focus:ring-brand` or `focus:ring-brand-danger`
4. ✅ **Dark Mode**: All colors work in dark mode (our default)
5. ✅ **Financial Context**: Green = positive/buy, Red = negative/sell
6. ✅ **No Blue CTAs**: Blue (`brand-accent`) only for info, not primary actions
7. ✅ **Border Radius**: Buttons use `rounded-xl`, cards use `rounded-2xl`

---

## Common Patterns

### Financial Change Display
```html
<!-- Profit/Gain -->
<span class="price-positive">+$123.45</span>
<span class="badge-positive">+5.2%</span>

<!-- Loss/Down -->
<span class="price-negative">-$67.89</span>
<span class="badge-negative">-2.1%</span>
```

### Button Group
```html
<!-- Primary + Secondary -->
<div class="flex gap-3">
    <button class="btn-primary">Confirm</button>
    <button class="bg-surface-200 hover:bg-surface-200/80 text-text-main px-5 py-2.5 rounded-xl">
        Cancel
    </button>
</div>

<!-- Buy + Sell -->
<div class="flex gap-3">
    <button class="bg-brand hover:bg-brand-light text-text-main px-5 py-2.5 rounded-xl">
        Buy
    </button>
    <button class="btn-danger">Sell</button>
</div>
```

### Info Card with Status
```html
<div class="card-base p-4">
    <div class="flex items-center justify-between">
        <h3 class="text-text-main font-bold">Meme Title</h3>
        <span class="badge-info">Active</span>
    </div>
    <p class="text-text-muted mt-2">Description text</p>
</div>
```

---

## Quick Reference: Color Mapping

| Old (Hardcoded) | New (Semantic) | Context |
|----------------|----------------|---------|
| `bg-blue-600` | `bg-brand` | Primary buttons (if action is "buy/confirm") |
| `bg-red-600` | `bg-brand-danger` | Danger buttons (sell/delete) |
| `bg-green-500` | `bg-brand` | Success indicators, profits |
| `text-green-400` | `text-brand` | Positive price changes |
| `text-red-400` | `text-brand-danger` | Negative price changes |
| `bg-slate-800` | `bg-surface-100` | Cards, modals |
| `bg-slate-900` | `bg-surface-50` | Page background |
| `border-slate-700` | `border-surface-200` | Borders |
| `text-white` | `text-text-main` | Primary text |
| `text-slate-400` | `text-text-muted` | Secondary text |
| `bg-blue-100 text-blue-800` | `badge-info` | Info badges |

---

## Need Help?

1. **Review existing migrated files** for patterns
2. **Check `resources/css/app.css`** for available utility classes
3. **Refer to `docs/ui_style_guide.md`** for design principles
4. **Ask in #frontend channel** if unsure about color semantics

---

## Commit Message Template

```
feat(ui): migrate [component-name] to semantic color system

- Replace hardcoded Tailwind colors with semantic tokens
- Update [specific elements] to use brand/brand-danger/brand-accent
- Apply utility classes where available
- Test hover/focus states

Related: COLOR_MIGRATION
```

---

**Last Updated**: January 5, 2026  
**Status**: Phase 1 Complete (33 files migrated), Phase 2 In Progress
