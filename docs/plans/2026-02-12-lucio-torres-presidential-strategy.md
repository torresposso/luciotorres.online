# Design Doc: Lucio Torres 2026 - Ruta Pan con Paz

**Date:** 2026-02-12
**Status:** Validated
**Context:** Presidential Campaign for Lucio Torres (2026).
**Core Proposal:** "Pan con Paz" (Bread with Peace).

---

## 1. Strategic Objective
Transform the current media platform into a high-conversion presidential campaign site. The focus is to position Lucio Torres as a serious candidate whose investigative journalism background provides the diagnostic foundation for his "Pan con Paz" government proposal.

## 2. Visual Identity & Style
- **System:** Swiss Style / Minimalism.
- **Typography:** Newsreader (Headings) + Roboto (Body).
- **Colors:** Primary Red (`#DC2626`), Secondary Red (`#EF4444`), CTA Blue (`#1E40AF`), Text (`#450A0A`), Background (`#FEF2F2`).
- **Semantic UI:** daisyUI (v5) for layout and interaction components.

## 3. Core Feature: "La Ruta 2026"
An interactive, scroll-driven timeline that narrates the transition from investigative journalism to presidential leadership.

### Components (daisyUI + Alpine.js)

#### A. Interactive Timeline (`.timeline`)
- **Semantic Structure:** Vertical timeline connecting past investigations to future solutions.
- **Alpine.js Logic:** Tracks scroll progress (`activeStep`) using `x-intersect`.
- **Interactions:**
  - Highlights timeline path and icons as the user scrolls.
  - Triggering animations for stats.

#### B. Evidence Stats (`.stats`)
- **Semantic Structure:** Data cards showing metrics of corruption uncovered vs. targets of "Pan con Paz".
- **Alpine.js Logic:** Number counter animation on visibility.
- **Detail:** Each stat includes a "Source" popover (`.dropdown` or `.popover`) for transparency.

#### C. Manifesto Pillars (`.collapse`)
- **Semantic Structure:** Clean, accordion-style blocks for "Justicia Social", "Economía", and "Paz".
- **Interaction:** One-at-a-time expansion managed by Alpine state.

#### D. Conversion Join (`.join`)
- **Semantic Structure:** Unified input/button group for email/WhatsApp capture.
- **Goal:** Recruitment for "Frente por la Vida" volunteers and voters.

## 4. Technical Architecture
- **Framework:** Astro (Static Site Generation).
- **Interactivity:** Alpine.js (Lightweight state management).
- **Styling:** Tailwind CSS + daisyUI.
- **Hydration:** Use `client:idle` for Alpine.js components to ensure instant content visibility.

## 5. Success Criteria
- **Performance:** Lighthouse score > 90 on mobile.
- **Engagement:** Increased time on page through interactive scrollytelling.
- **Conversion:** High click-through rate on the "Pan con Paz" manifesto download.

---

## 6. Implementation Phases
1. **Infrastructure:** Setup daisyUI themes and Tailwind configuration.
2. **Layout:** Build the "Route 2026" timeline structure.
3. **Interactivity:** Implement Alpine.js scroll tracking and animations.
4. **Content:** Migrate manifesto content into Astro Content Collections.
5. **Final Polish:** Accessibility audit and mobile responsiveness check.
