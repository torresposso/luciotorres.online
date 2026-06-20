{{--
  Wayuu-inspired geometric motif (kasalewo'u — "eye of the wayuu").
  The Wayuu are the indigenous people of La Guajira, Colombia.
  Their kanasta (woven bags) feature geometric diamonds and lines.
  Used as a brand stamp/decorative element to add Caribbean
  authenticity without kitsch. Color is inherited via currentColor.
--}}
@props([
  'class' => 'w-12 h-12',
])

<svg
  {{ $attributes->merge(['class' => $class]) }}
  viewBox="0 0 100 100"
  xmlns="http://www.w3.org/2000/svg"
  fill="none"
  stroke="currentColor"
  stroke-width="1.5"
  aria-hidden="true"
  focusable="false"
>
  {{-- Outer diamond (kantyu'u) --}}
  <polygon points="50,6 94,50 50,94 6,50" />
  {{-- Inner diamond --}}
  <polygon points="50,26 74,50 50,74 26,50" />
  {{-- Center dot (the "eye") --}}
  <circle cx="50" cy="50" r="4" fill="currentColor" stroke="none" />
  {{-- Cross lines --}}
  <line x1="6" y1="50" x2="94" y2="50" stroke-width="0.5" />
  <line x1="50" y1="6" x2="50" y2="94" stroke-width="0.5" />
</svg>
