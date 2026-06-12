# Design

## Palette & Semantic Tokens (Relative Color Syntax)

We define only two source hexadecimal / OKLCH brand seeds:
- **Midnight Seed**: `oklch(20.31% 0.031 269.4)`
- **Orange Seed**: `oklch(66.06% 0.199 38.6)`

All other semantic color values for DaisyUI v5 are derived dynamically using **modern CSS Relative Color Syntax** (`oklch(from var(--seed) l c h)`), varying only the Lightness (`l`) channel to maintain absolute hue and chroma harmony:

### Derived Base Scale (from Midnight Seed):
- **base-100** (Page BG): `oklch(from var(--brand-midnight) 99% c h)` (Lightness bumped to 99% for a clean off-white)
- **base-200** (Secondary BG): `oklch(from var(--brand-midnight) 97.5% c h)` (Lightness at 97.5%)
- **base-300** (Borders/Dividers): `oklch(from var(--brand-midnight) 90% c h)` (Lightness at 90%)
- **base-content** (Ink/Text): `oklch(from var(--brand-midnight) 12% c h)` (Lightness dropped to 12% for deep contrast)

### Derived Brand Acents:
- **primary** (Midnight): `var(--brand-midnight)`
- **primary-content**: `oklch(100% 0 0)`
- **secondary** (Orange): `var(--brand-orange)`
- **secondary-content**: `oklch(100% 0 0)`
- **accent** (Orange Light/Hover): `var(--brand-orange-light)`
- **accent-content**: `var(--brand-midnight)`
- **brand-orange-accessible**: `oklch(from var(--brand-orange) 58% c h)` (Lightness reduced to 58% to meet WCAG AA contrast on light backgrounds)
- **brand-orange-light**: `oklch(from var(--brand-orange) 72% c h)` (Lightness raised to 72% for hover states)

## Typography

- **display**: "Poppins", sans-serif (Display headers)
- **sans**: "Inter", sans-serif (Body copy)
