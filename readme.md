# Elementor GSAP
Contributors: creativetria
Tags: gsap, elementor, elementor-plugin, elementor-addons, elementor-widgets
WordPress tested up to: 7.0.1
Elementor tested up to: 4.1.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Premium Osmo-style loading animations, page transitions, and interactive widgets for Elementor — powered by GSAP.

## Description

**Elementor GSAP styled by Osmo** brings premium-grade animations to the Elementor page builder. Inspired by the design language of [Osmo](https://www.osmo.supply/) and powered by [GSAP](https://gsap.com/) (GreenSock Animation Platform), this plugin gives designers and developers a curated set of preloaders, page transitions, and animated widgets — all configurable directly from the Elementor panel.

##  📸 Demo

![Demo GIF](https://your-demo-link.com/demo.gif)  
[Live Site](https://your-live-site.com)

## Why use this plugin?

* **No code required** — every animation parameter (color, font, duration, easing, breakpoint) is exposed as an Elementor control.
* **Production-ready** — ships with editor preview mode, asset lazy-loading, FOUC prevention, and per-instance scoping.
* **Performance-conscious** — assets only load on pages that actually use them.
* **Multi-instance safe** — drop multiple sliders or text reveals on the same page without conflicts.
* **Developer-friendly** — clean BEM-style class names, CSS custom properties, and semantic markup.

## ✨ What's included

The plugin ships **3 page-level animations** (configured via Page Settings) and **3 Elementor widgets** (in the "Elements GSAP" category).

### Loaders

1. **Willem Loading Animation** — an Osmo-inspired preloader with a split-text logo wrapping a growing image cover that expands to fill the viewport. Configurable logo text, cover image stack (1 final + 3 flash transitions), top navigation bar, colors, typography per text element, and responsive width per logo half. Default font: PP Neue Montreal.

2. **Crisp Loading Animation** — a slideshow-driven preloader: 5 images move horizontally, one becomes the focal point that scales to fit the viewport. After loading, the slideshow remains interactive with thumbnail navigation. Includes SplitText heading animation, custom logo image (PNG/JPG/SVG with OSMO SVG fallback), parallax inner, and cubic-bezier easing.

3. **Pixelated Page Transition** — a retro pixel-grid dissolve between pages. Internal link clicks trigger a randomly-staggered pixel grid that covers the page before navigation; the destination page fades the grid back out for a smooth entry. Pixel color, grid density (responsive), stagger timing, and per-block duration are all configurable.

### Elementor Widgets

4. **Bunny HLS Player (Basic)** — a custom video player for HLS streaming (`.m3u8`), using Safari's native HLS support and [hls.js](https://github.com/video-dev/hls.js) elsewhere. Designed for [Bunny.net](https://bunny.net/), AWS, or any HLS provider. Supports autoplay (muted + loop with IntersectionObserver), lazy loading modes, aspect ratio behaviors, and full styling control.

5. **Masked Text Reveal** — a scroll-triggered reveal using GSAP SplitText + ScrollTrigger. Lines slide up from below a mask when the element enters the viewport. Configurable split type (Lines / Words / Characters), duration, stagger, easing, ScrollTrigger start position, "Animate Once Only" toggle, plus typography group, alignment, and color. Includes built-in FOUC prevention.

6. **Draggable Infinite Slider** — a click-and-drag infinite slider with momentum throw and snap, plus thumbnail navigation, powered by GSAP Draggable + InertiaPlugin. Repeater for slides (image + caption), active-slide offset mode or center mode, animated "01 / 04" counter, prev/next buttons with corner overlay hover effect, and full per-instance styling.

7. **Button Draw Underline** — a button with an animated SVG underline that draws in on hover (DrawSVGPlugin). Choose between 6 hand-drawn underline variants or random cycle; duration, easing, color, and thickness configurable.

8. **Button Character Stagger** — an Osmo-style button where each character slides up on hover with a staggered delay, paired with an inset background that shrinks on hover. Pure CSS transitions (no GSAP needed); stagger offset, duration, easing, character travel distance, hover inset, padding, max-width, radius, typography, and colors all configurable.

9. **Looping Words with Selector** — a vertical word carousel where the visible word is highlighted by an animated corner-edge "selector" that snaps to the word's width. Powered by GSAP. Repeater for the word list, configurable word/selector durations, easing (Elastic / Bounce / Expo presets), fade gradient color, edge color/thickness/length, and selector height.

10. **Image Scroll** — a fixed-size frame that reveals a tall/wide image by scrolling its content on hover or via mouse-tracking. Useful for long screenshots, full-page mockups, before/after panoramas. Direction (vertical / horizontal), trigger (hover / mouse-track), reverse, duration, easing, frame height, max-width, border radius, border, shadow, and optional centered overlay icon — all controllable. Pure CSS transitions; no GSAP dependency.

11. **Side Navigation Wipe** — a fixed-position trigger button that opens a right-side drawer menu with a staggered, three-panel wipe effect (Osmo-style). Powered by GSAP + CustomEase. Menu items repeater (label + eyebrow + link), socials repeater, Escape key to close, ARIA-expanded sync, configurable trigger position, panel colors (3 layers), overlay color, menu width/radius, and typography per text role. Editor preview renders menu inline so content stays editable.

12. **Fixed Underlay Navigation** — a fixed-position trigger button where the entire page content slides left to reveal a navigation menu that was sitting *underneath* the whole time (vs. Side Navigation Wipe which slides a drawer over the content). Powered by GSAP + CustomEase with the "easeReverse" technique from the GSAP 3.15 announcement, so the open and close motions feel distinct, and mid-animation interruptions stay responsive without snapping. Includes logo (default SVG / image upload / none), Menu/Close toggle with animated label flip + bars rotating into an X, large menu items repeater with current-item highlight, separate Socials and Quick Links columns at the bottom, decorative overlay borders + corner cutouts framing the menu edge, animated bottom-border scaleX, Escape/overlay-click to close, ARIA-expanded sync, and an auto-wrap fallback for the main content wrapper (or a custom CSS selector via the "Main Wrapper Selector" control). Fully configurable via CSS custom properties exposed as Elementor controls.

## 📦 Installation

1. Upload the `elementor-gsap` folder to `/wp-content/plugins/`.
2. Activate the plugin from the **Plugins** menu in WordPress admin.
3. Make sure the **Elementor** plugin is active (latest stable recommended).
4. **Page-level animations**: open a page in Elementor → click the **gear** icon (Page Settings) → **Style** tab → scroll to the desired section (Willem / Crisp / Pixelated Transition).
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

= Can I run multiple loading animations on one page? =

Technically yes (Willem + Crisp + Pixelated all enabled), but it's not recommended — they will render simultaneously and look chaotic. Pick one per page.

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
