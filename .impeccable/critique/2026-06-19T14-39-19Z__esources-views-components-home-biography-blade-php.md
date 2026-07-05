---
target: biography section (home)
total_score: 35
p0_count: 0
p1_count: 2
p2_count: 3
p3_count: 1
timestamp: 2026-06-19T14-39-19Z
slug: esources-views-components-home-biography-blade-php
---
## Design Health Score

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 4 | Section self-evident — first-person voice + photo + career data |
| 2 | Match System / Real World | 4 | Real media outlets, specific biographical details |
| 3 | User Control and Freedom | 4 | Static display, no traps |
| 4 | Consistency and Standards | 4 | Typography + tokens match brand system |
| 5 | Error Prevention | 3 | Image lacks width/height — CLS risk |
| 6 | Recognition Rather Than Recall | 4 | Everything on-screen, no hidden state |
| 7 | Flexibility and Efficiency | 3 | Single CTA path, no alternate depth |
| 8 | Aesthetic and Minimalist Design | 2 | Over-decorated: 2 background blurs + corner shape + image gradient + border + shadow + animation stagger |
| 9 | Error Recovery | 4 | Nothing can break |
| 10 | Help and Documentation | 3 | CTA ambiguous for non-Spanish readers |
| **Total** | | **35/40** | **Good** |

## Anti-Patterns Verdict

**Does this look AI-generated?** No. The media badges (specific real outlets), first-person voice, and the 46 stat anchoring a genuine biographical detail show human editorial judgment. One near-miss: the gradient overlay on the photo flirts with trend territory, but applied to an image (not text) it passes.

**Deterministic scan:** 0 findings (clean). No issues flagged by the automated detector.

## Overall Impression

This is a solid, credible biography section that communicates "real journalist with real credentials." The number stat and media badges are its strongest assets. But the decorative density undermines the brand's "Solemnidad Disruptiva" promise — too many soft/playful treatments (blurs, animations, rounded corners) for a page that should feel presidential and urgent. The main tension: the "46" stat wins the visual hierarchy battle against the heading, so the user processes age before mission.

## What's Working

1. **Media badges** — Specific outlet names as pills are a credibility shortcut that works. Grounds the page in real journalism.
2. **Typography scale** — Poppins black at 3xl–5xl with text-balance is confident and editorial.
3. **First-person heading** — "He dedicado mi vida a acompañar a la gente" is brave and on-brand; disarms the usual detachment of "About" sections.

## Priority Issues

### [P1] CTA link contrast reversed
`text-secondary` (74.5% lightness) on `bg-base-200` (97.5% lightness) has borderline contrast (~3:1). The hover state (`brand-orange-accessible` at 58%) is MORE readable than the default. The default should be the accessible version; hover should go lighter/brighter.

### [P1] "46" stat overpowers the heading
The number occupies the visual center of gravity at 4xl/5xl + black weight + brand orange, but the heading ("He dedicado mi vida a acompañar a la gente") carries the section's message. Reduce the stat's weight (drop to 2xl/3xl) or restructure so the heading sits above it.

### [P2] Over-decoration
Two blurred background circles + decorative corner shape + image gradient overlay + border + shadow + scroll-reveal delay. Each is reasonable alone; together they create noise that undermines "solemn" brand. Remove the corner shape, consolidate to one blur, or simplify the image frame.

### [P2] Image missing dimensions
No width/height attributes on `<img>`. On slow connections or if the upload fails, the layout collapses. Add explicit aspect ratio or dimensions with a `bg-base-300` placeholder fallback.

### [P2] CTA touch target on mobile
`py-1` (4px) yields ~24px total height. WCAG 2.2 2.5.8 requires 24px minimum. Increase to `py-3` or `py-4` for mobile tap safety.

### [P3] Caption contrast
`text-xs` (0.75rem) italic at 60% opacity on 97.5% lightness background may fail WCAG AA for normal text. The caption adds little value over the photo itself.

## Persona Red Flags

**Jordan (First-Timer)**: Understands "this is about a person" in 3s. The photo saves it. But non-Spanish speakers miss the "biography" signal — no icon or label says "About."

**Riley (Stress Tester)**: Image without dimensions = CLS. If IntersectionObserver fails, `.reveal` elements stay invisible forever (no timeout fallback). The `-top-2 -right-2` decorative element breaks on RTL layouts.

**Casey (Mobile User)**: CTA touch target too small. Media badges at 0.65rem are nearly untappable. "46" at 4xl on a 375px phone occupies ~30% viewport.

## Minor Observations

- Wikipedia link has the same reversed contrast pattern (default less accessible than hover).
- `&rarr;` on CTA may be janky without GPU compositing — consider `translate3d` or `will-change`.
- Photo is WhatsApp-compressed; on large screens artifacts will show.
- HTML entities (`&ntilde;`, `&oacute;`) instead of raw UTF-8 — renders fine but inconsistent.

## Questions to Consider

1. What if the heading was the entry point instead of the number? The story leads with "service" but the eye leads with "age."
2. What does "Solemnidad Disruptiva" look like in a photo frame?
3. Is "46 años" the right stat, or would years of journalism / investigations published / court cases won be more compelling?
