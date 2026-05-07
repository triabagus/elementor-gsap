# Elementor GSAP styled by Osmo
Contributors: creativetria
Tags: elementor, gsap, loading animation, preloader, page transition, hls player, video player, splittext, scroll reveal, slider
Requires at least: 6.7
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.2.1
Elementor tested up to: 4.0.7
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

### Page-level Animations

1. **Willem Loading Animation** — an Osmo-inspired preloader with a split-text logo wrapping a growing image cover that expands to fill the viewport. Configurable logo text, cover image stack (1 final + 3 flash transitions), top navigation bar, colors, typography per text element, and responsive width per logo half. Default font: PP Neue Montreal.

2. **Crisp Loading Animation** — a slideshow-driven preloader: 5 images move horizontally, one becomes the focal point that scales to fit the viewport. After loading, the slideshow remains interactive with thumbnail navigation. Includes SplitText heading animation, custom logo image (PNG/JPG/SVG with OSMO SVG fallback), parallax inner, and cubic-bezier easing.

3. **Pixelated Page Transition** — a retro pixel-grid dissolve between pages. Internal link clicks trigger a randomly-staggered pixel grid that covers the page before navigation; the destination page fades the grid back out for a smooth entry. Pixel color, grid density (responsive), stagger timing, and per-block duration are all configurable.

### Elementor Widgets

4. **Bunny HLS Player (Basic)** — a custom video player for HLS streaming (`.m3u8`), using Safari's native HLS support and [hls.js](https://github.com/video-dev/hls.js) elsewhere. Designed for [Bunny.net](https://bunny.net/), AWS, or any HLS provider. Supports autoplay (muted + loop with IntersectionObserver), lazy loading modes, aspect ratio behaviors, and full styling control.

5. **Masked Text Reveal** — a scroll-triggered reveal using GSAP SplitText + ScrollTrigger. Lines slide up from below a mask when the element enters the viewport. Configurable split type (Lines / Words / Characters), duration, stagger, easing, ScrollTrigger start position, "Animate Once Only" toggle, plus typography group, alignment, and color. Includes built-in FOUC prevention.

6. **Draggable Infinite Slider** — a click-and-drag infinite slider with momentum throw and snap, plus thumbnail navigation, powered by GSAP Draggable + InertiaPlugin. Repeater for slides (image + caption), active-slide offset mode or center mode, animated "01 / 04" counter, prev/next buttons with corner overlay hover effect, and full per-instance styling.

7. **Button Draw Button** - a draw button with svg line and use the attribute to specific which element should trigger the line drawing animation hover.
## Editor Preview

While editing in Elementor, animations are automatically disabled and components render in a static preview state with an identification badge in the top-right corner. Pixelated Transition is skipped entirely in edit mode so it never covers the canvas. You can immediately tell which page has a loading animation enabled without having to save and refresh.

## 🚀 Performance Notes

* Lazy-enqueued assets — CSS/JS for each component only loads on pages that use it.
* GSAP, hls.js, and helper plugins served from the jsDelivr CDN (versioned and cached).
* GSAP animations are skipped entirely in edit mode.
* CSS uses custom properties (`--egsap-*`) for per-instance theming.
* Per-page / per-widget scoping via `data-egsap-id` prevents style bleed between instances.

## 📦 Installation

1. Upload the `elementor-gsap` folder to `/wp-content/plugins/`.
2. Activate the plugin from the **Plugins** menu in WordPress admin.
3. Make sure the **Elementor** plugin is active (latest stable recommended).
4. **Page-level animations**: open a page in Elementor → click the **gear** icon (Page Settings) → **Style** tab → scroll to the desired section (Willem / Crisp / Pixelated Transition).
5. **Widgets**: in the Elementor widget panel, find the **Elements GSAP** category and drag the desired widget onto a Section/Container.

## 🛠 Quick Start

### Add a Willem preloader to a landing page

1. Edit the page in Elementor.
2. Open Page Settings → Style → Willem Loading Animation.
3. Toggle **Enable**.
4. Customize logo text, cover image, and colors.
5. Click **Update** and preview.

### Add a Bunny HLS video to any layout

1. Drag the **Bunny HLS Player (Basic)** widget into a Section/Container.
2. Paste your `.m3u8` URL into the HLS Source field.
3. Pick a placeholder image.
4. Choose a lazy loading mode (`Meta only` is a good default).
5. Update.

### Trigger the Pixelated Page Transition site-wide

1. Enable the toggle on every page where you want the transition active.
2. (Optional) Use Elementor Theme Builder to apply it via a global template.
3. Add `data-transition-prevent` to any link that should bypass the animation.

## 🧰 Requirements

* WordPress 6.7 or later
* PHP 7.4 or later
* Elementor (latest stable recommended)
* Modern browsers with CSS `:has()` support (Chrome 105+, Firefox 121+, Safari 15.4+, Edge 105+)
* For SVG uploads: Elementor's SVG support enabled, or the **Safe SVG** plugin
* For Bunny HLS Player: video must be hosted as an HLS stream (`.m3u8` playlist)
* For page-level loaders: theme must call `wp_body_open()` (WordPress 5.2+ standard)

## Frequently Asked Questions

= Is this plugin free? =

Yes. It's open source under the GPLv3 license.

= Do I need Elementor Pro? =

No. The plugin works with the free version of Elementor. Some features (like dynamic tags on widgets) become more capable with Elementor Pro, but it's not required.

= Can I use a font other than PP Neue Montreal? =

Yes. PP Neue Montreal is just the fallback default. Use the **Typography** control in any animation section to switch fonts (auto-loaded from Elementor's Google Fonts library) or set a Custom Font via Elementor Pro.

= My loading animation isn't showing on the frontend. =

Check the following:

1. The **Enable** toggle is on in Page Settings → Style.
2. Your theme calls `wp_body_open()` (standard since WordPress 5.2).
3. You clicked **Update** and refreshed the frontend.
4. No conflict with another loading animation plugin.

= How do I enable SVG uploads? =

WordPress blocks SVG by default for security. Enable via:

* Elementor Pro: **Elementor → Settings → General → Enable Unfiltered File Uploads**
* Free plugins: **Safe SVG** (recommended) or **SVG Support**

= Does the Bunny HLS Player support MP4? =

No. This player is designed exclusively for HLS streams (`.m3u8`). For regular MP4 files, use Elementor's built-in Video widget.

= Which browsers does the HLS Player support? =

* Safari (macOS/iOS): native HLS support
* Chrome, Firefox, Edge: via the hls.js library
* IE11: not supported

= Can I run multiple loading animations on one page? =

Technically yes (Willem + Crisp + Pixelated all enabled), but it's not recommended — they will render simultaneously and look chaotic. Pick one per page.

= The Pixelated Transition only runs on pages where it's enabled. How do I make it site-wide? =

Enable the toggle on every destination page, or use **Elementor Theme Builder** to apply it via a global template (Single, Archive, etc.).

= Why doesn't a specific link trigger the transition? =

Pixelated Transition only intercepts links that are: (a) same domain, (b) don't start with `#`, (c) don't have `target="_blank"`, and (d) don't have the `data-transition-prevent` attribute. Add `data-transition-prevent` to skip the transition on a specific link.

= The Draggable Slider isn't draggable, what's wrong? =

Make sure both `gsap-draggable` and `gsap-inertia` are loading. Check the browser console — if you see a warning about InertiaPlugin being required for momentum-based scrolling, InertiaPlugin failed to load (often due to ad-blockers or CDN issues).

= Masked Text Reveal replays every time I scroll. How do I make it animate just once? =

Toggle **Animate Once Only** in the widget's Animation tab.

== Changelog ==

= 1.2.0 =

* Added **Pixelated Page Transition** (page-level transition)
* Added **Masked Text Reveal** widget (scroll-triggered SplitText reveal)
* Added **Draggable Infinite Slider** widget (Draggable + InertiaPlugin)
* Added **Button Draw Underline** widget
* Registered additional GSAP plugins: ScrollTrigger, Draggable, InertiaPlugin

= 1.1.0 =

* Added **Crisp Loading Animation** (slideshow preloader)
* Added **Bunny HLS Player (Basic)** widget
* Migrated loading animations from widget to Page Settings (document-level)
* Editor preview mode with identification badge
* CSS custom properties for per-instance styling
* Typography group control per text element
* Responsive sliders for logo widths (Willem & Crisp)
* Default font: PP Neue Montreal
* Upgraded GSAP to 3.15
* Custom logo image control for Crisp animation

= 1.0.0 =

* Initial release with **Willem Loading Animation** as an Elementor widget

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you’d like to change.

## 🙌 Credits

* [GSAP](https://gsap.com/) by GreenSock — animation engine and plugins (ScrollTrigger, SplitText, Draggable, InertiaPlugin, CustomEase)
* [hls.js](https://github.com/video-dev/hls.js) — HLS playback library
* [Osmo](https://www.osmo.supply/) — design inspiration
* [Bunny.net](https://bunny.net/) — recommended HLS video hosting

## 📄 License

This plugin is released under the GPLv3 license. See [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html) for full terms.
