# Elementor GSAP
- Contributors: creativetria
- Tags: gsap, elementor, elementor-plugin, elementor-addons, elementor-widgets
- WordPress tested up to: 7.0.1
- Elementor tested up to: 4.1.5
- License: GPLv3
- License URI: https://www.gnu.org/licenses/gpl-3.0.html

Premium Osmo-style loading animations, page transitions, and interactive widgets for Elementor — powered by GSAP.

## Overview

**Elementor GSAP styled by Osmo** brings premium-grade animations to the Elementor page builder. Inspired by the design language of [Osmo](https://www.osmo.supply/) and powered by [GSAP](https://gsap.com/) (GreenSock Animation Platform), this plugin gives designers and developers a curated set of preloaders, page transitions, and animated widgets — all configurable directly from the Elementor panel.

## Why use this plugin?

* **No code required** — every animation parameter (color, font, duration, easing, breakpoint) is exposed as an Elementor control.
* **Production-ready** — ships with editor preview mode, asset lazy-loading, FOUC prevention, and per-instance scoping.
* **Performance-conscious** — assets only load on pages that actually use them.
* **Multi-instance safe** — drop multiple sliders or text reveals on the same page without conflicts.
* **Developer-friendly** — clean BEM-style class names, CSS custom properties, and semantic markup.

## ✨ What's included

The plugin ships **4 page-level animations** (configured via Page Settings) and **16 Elementor widgets** (grouped into 8 sub-categories under the "Elements GSAP" section).

### Loaders

1. **Willem Loading Animation** — an Osmo-inspired preloader with a split-text logo wrapping a growing image cover that expands to fill the viewport (default font: PP Neue Montreal).

2. **Crisp Loading Animation** — a slideshow-driven preloader where 5 horizontal images move and one scales up as the focal point, staying interactive with thumbnail navigation after loading.

3. **Pixelated Page Transition** — a retro pixel-grid dissolve between pages: link clicks trigger a randomly-staggered grid that covers the page before navigation, then fades back out on entry.

4. **Welcoming Words Loader** — a minimalist multilingual greeting preloader that cycles through a comma-separated word list (`Hello, Bonjour, स्वागत हे, Ciao, Olá`) with an up/down mask transition, then fades out to reveal the page.

### Elementor Widgets

1. **Bunny HLS Player (Basic)** — a custom video player for HLS streaming (`.m3u8`) using Safari native HLS and [hls.js](https://github.com/video-dev/hls.js) elsewhere; supports autoplay-in-view (IntersectionObserver), lazy loading, and aspect ratio modes.

2. **Masked Text Reveal** — a scroll-triggered reveal (SplitText + ScrollTrigger) where lines slide up from below a mask when the element enters the viewport, with configurable split type (Lines/Words/Characters).

3. **Draggable Infinite Slider** — a click-and-drag infinite slider with momentum throw + snap, thumbnail navigation, and an animated "01 / 04" counter, powered by Draggable + InertiaPlugin.

4. **Button Draw Underline** — a button with an animated SVG underline that draws in on hover (DrawSVGPlugin), with 6 hand-drawn variants or random cycle.

5. **Button Character Stagger** — an Osmo-style button where each character slides up on hover with staggered delay, paired with an inset background that shrinks on hover (pure CSS transitions).

6. **Looping Words with Selector** — a vertical word carousel where the visible word is highlighted by an animated corner-edge "selector" that snaps to the word's width.

7. **Image Scroll** — a fixed-size frame that reveals a tall or wide image by scrolling its content on hover or via mouse-tracking; ideal for long screenshots, full-page mockups, and panoramas (pure CSS).

8. **Side Navigation Wipe** — a fixed trigger button that opens a right-side drawer menu with a staggered three-panel wipe effect (GSAP + CustomEase), with menu items and socials repeaters.

9. **Pixelated Image Reveal** — an image card that transitions from a default to an active image via a randomly-staggered pixel-grid reveal on hover or click.

10. **Fixed Underlay Navigation** — a fixed trigger button that slides the entire page content left to reveal a navigation menu sitting *underneath*, with distinct open/close motion via GSAP's `easeReverse` technique.

11. **Sticky Steps (Basic)** — a two-column layout where a sticky image column stays in view while text steps scroll past, swapping visuals based on which step is closest to viewport center (GSAP ScrollTrigger).

12. **Logo Wall Cycle** — a client/partner logo grid where a visible subset continuously cycles through a larger pool via smooth `yPercent + autoAlpha` crossfade, with responsive columns × visible-count per breakpoint.

13. **Sticky Features** — a two-column pinned-scroll section where the left image column stays fixed while ScrollTrigger scrubs through features, swapping images via `clip-path` reveal and cross-fading text with a progress bar underneath.

14. **Expanding Bottom Navigation** — a floating pill nav fixed at the bottom (or top) that morphs into a full menu panel on toggle, with staggered item reveal (GSAP + CustomEase `osmo` preset).

15. **Radial Cards Slider (GSAP)** — cards arranged on a rotating wheel that you can drag with inertia + snap or navigate via Prev/Next + dots (Draggable + InertiaPlugin + CustomEase `radial` easing).

16. **Step-by-step Timeline** — a vertical timeline where a fill line scrubs down as you scroll, toggling each step to `active` / `current` states with animated marker colors and content opacity (GSAP ScrollTrigger, respects `prefers-reduced-motion`).

## 📦 Installation

1. Upload the `elementor-gsap` folder to `/wp-content/plugins/`.
2. Activate the plugin from the **Plugins** menu in WordPress admin.
3. Make sure the **Elementor** plugin is active (latest stable recommended).
4. **Page-level animations**: open a page in Elementor → click the **gear** icon (Page Settings) → **Style** tab → scroll to the desired section (Willem / Crisp / Pixelated Transition / Welcoming Words Loader).
5. **Widgets**: in the Elementor widget panel, find the **Elements GSAP** category and drag the desired widget onto a Section/Container.

## Frequently Asked Questions

= Is this plugin free? =

Yes. It's open source under the GPLv3 license.

= Do I need Elementor Pro? =

No. The plugin works with the free version of Elementor. Some features (like dynamic tags on widgets) become more capable with Elementor Pro, but it's not required.

= Can I use a font other than PP Neue Montreal? =

Yes. PP Neue Montreal is just the fallback default. Use the **Typography** control in any animation section to switch fonts (auto-loaded from Elementor's Google Fonts library) or set a Custom Font via Elementor Pro.

= How do I enable SVG uploads? =

WordPress blocks SVG by default for security. Enable via:

* Elementor: **Elementor → Settings → General → Enable Unfiltered File Uploads**
* Free plugins: **Safe SVG** (recommended) or **SVG Support**

= Does the Bunny HLS Player support MP4? =

No. This player is designed exclusively for HLS streams (`.m3u8`). For regular MP4 files, use Elementor's built-in Video widget.

= Which browsers does the HLS Player support? =

* Safari (macOS/iOS): native HLS support
* Chrome, Firefox, Edge: via the hls.js library
* IE11: not supported

= Can I run multiple loading animations or transition on one page? =

Not recommended — they will render simultaneously and look chaotic. Pick one per page.

== Changelog ==

= 1.0.0 =

* Initial release with **Elementor GSAP** as an Elementor.

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you’d like to change.

## 🙌 Credits

* [GSAP](https://gsap.com/) by GreenSock — animation engine and plugins (ScrollTrigger, SplitText, Draggable, InertiaPlugin, CustomEase)
* [hls.js](https://github.com/video-dev/hls.js) — HLS playback library
* [Osmo](https://www.osmo.supply/) — design inspiration
* [Bunny.net](https://bunny.net/) — recommended HLS video hosting

## 📄 License

This plugin is released under the GPLv3 license. See [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html) for full terms.
