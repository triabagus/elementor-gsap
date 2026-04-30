=== Elementor GSAP styled by Osmo ===
Contributors: creativetria
Tags: elementor, gsap, loading animation, preloader, hls player, video player, bunny, animation, page transition, splittext
Requires at least: 6.7
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.1.0
Elementor tested up to: 4.0.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Kumpulan widget & ekstensi Elementor bertenaga GSAP bergaya Osmo: page loading animation (Willem & Crisp) dan Bunny HLS video player.

== Description ==

**Elementor GSAP styled by Osmo** menambahkan komponen animasi premium ke Elementor menggunakan [GSAP](https://gsap.com/) (GreenSock Animation Platform) — terinspirasi gaya animasi dari [Osmo](https://www.osmo.supply/).

Plugin ini dirancang untuk pengembang dan desainer web yang ingin menambahkan loading animation berkualitas tinggi dan video HLS player ke project Elementor mereka tanpa perlu menulis kode dari nol. Semua kontrol terintegrasi langsung di antarmuka Elementor — dari toggle, color picker, typography group, sampai responsive slider.

= Komponen yang Tersedia =

**1. Willem Loading Animation** (Page-level Preloader)

Preloader bergaya Osmo dengan logo huruf yang dipisah dua, kotak gambar yang tumbuh dari tengah, lalu meluas memenuhi viewport. Cocok untuk landing page, portfolio, atau intro hero section.

* Toggle on/off di Page Settings → Style
* Custom logo text dengan pemisahan otomatis di tengah
* 4 layer cover image (1 final + 3 flash transition)
* Navigation bar (brand, links repeater, CTA)
* Color customization (header, loader, background)
* Typography group control per element (top logo, bottom logo, brand, nav links, CTA)
* Responsive width control untuk separuh logo
* Default font: PP Neue Montreal (fallback ke sans-serif)

**2. Crisp Loading Animation** (Page-level Preloader)

Loading animation dengan slideshow-style: 5 gambar bergerak horizontal, salah satunya menjadi focal point yang scaling sampai memenuhi viewport. Setelah loading selesai, slideshow tetap aktif dengan thumbnail navigation.

* Toggle on/off di Page Settings → Style
* 5 gambar konfigurabel (dipakai untuk slider, loader, dan thumbnail)
* Custom heading text dengan SplitText animation per kata
* Custom logo image (PNG/JPG/SVG) dengan fallback OSMO SVG
* Responsive logo width control
* Background, text, dan fade edge color via CSS custom properties
* Typography group control untuk heading dan paragraph
* Slideshow interaktif dengan parallax inner & cubic-bezier easing

**3. Bunny HLS Player (Basic)** (Elementor Widget)

Widget custom video player untuk HLS streaming (.m3u8) dengan dukungan native Safari + [hls.js](https://github.com/video-dev/hls.js) untuk browser lain. Cocok untuk hosting video via [Bunny.net](https://bunny.net/), AWS, atau provider HLS lain.

* HLS source via .m3u8 URL (dynamic tag support)
* Placeholder image sebelum video load
* Autoplay mode (auto muted + loop, dikontrol IntersectionObserver)
* Lazy loading: Eager / Meta only / Full lazy
* Aspect ratio: Default 16:10 / Auto from video / Cover container
* Border radius, icon color, overlay color, big button background — fully customizable
* Play/pause toggle, mute control, hover state
* Responsive design dengan breakpoint mobile

= Editor Preview Mode =

Saat editing di Elementor, semua animasi dan loading state otomatis dinonaktifkan dan menampilkan preview statis dengan badge label di pojok kanan atas. Developer dan editor langsung tahu mana page yang punya loading animation aktif tanpa perlu save & refresh.

= Performance & Asset Loading =

* Asset di-enqueue lazy: hanya halaman dengan toggle on yang load CSS/JS terkait
* GSAP & hls.js dari jsDelivr CDN (versioned, cached)
* Editor mode skip animasi — tidak boros saat ngedit
* CSS pakai custom properties (`--egsap-*`) untuk theming per-instance
* Per-page scoping via `data-egsap-id` agar typography & style tidak bocor antar halaman

== Installation ==

1. Upload folder `elementor-gsap` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin via menu **Plugins** di WordPress admin
3. Pastikan plugin **Elementor** sudah aktif (minimal versi terbaru stabil)
4. Untuk Loading Animation: edit halaman dengan Elementor → klik ikon **gear** (Page Settings) → tab **Style** → scroll ke section **Willem Loading Animation** atau **Crisp Loading Animation**
5. Untuk Bunny HLS Player: di panel widget Elementor, cari category **Elements GSAP** → drag widget **Bunny HLS Player (Basic)** ke Section/Container

== Requirements ==

* WordPress 6.7 atau lebih baru
* PHP 7.4 atau lebih baru
* Elementor (versi terbaru stabil disarankan)
* Browser modern dengan dukungan CSS `:has()` (Chrome 105+, Firefox 121+, Safari 15.4+, Edge 105+)
* Untuk SVG upload: enable Elementor SVG support atau install plugin Safe SVG
* Untuk Bunny HLS Player: video harus di-host sebagai HLS stream (.m3u8 playlist)

== Frequently Asked Questions ==

= Apakah plugin ini gratis? =

Ya. Plugin ini open source di bawah lisensi GPLv3.

= Apakah perlu Elementor Pro? =

Tidak wajib. Plugin ini bekerja dengan Elementor versi gratis (Free). Beberapa fitur seperti dynamic tags pada widget HLS Player akan lebih lengkap kalau pakai Elementor Pro.

= Bisa pakai font selain PP Neue Montreal? =

Bisa. PP Neue Montreal hanya default fallback. Pakai control **Typography** di setiap section animasi untuk mengganti font (auto-load dari Google Fonts library Elementor) atau set Custom Font via Elementor Pro.

= Kenapa loading animation tidak muncul di frontend? =

Periksa hal-hal berikut:
1. Pastikan toggle **Enable Loading Animation** sudah aktif di Page Settings → Style
2. Tema yang dipakai harus memanggil action `wp_body_open()` (standar WordPress 5.2+)
3. Sudah klik **Update** dan refresh halaman frontend
4. Tidak ada conflict dengan plugin loading animation lain

= Bagaimana cara enable upload file SVG? =

WordPress memblokir SVG default karena alasan keamanan. Aktifkan via:
* Elementor Pro: **Elementor → Settings → General → Enable Unfiltered File Uploads**
* Plugin gratis: **Safe SVG** (recommended) atau **SVG Support**

= Apakah Bunny HLS Player support format MP4? =

Tidak. Player ini didesain khusus untuk HLS streaming (.m3u8). Untuk MP4 reguler, gunakan widget Video bawaan Elementor.

= Browser apa saja yang didukung HLS Player? =

* Safari (macOS/iOS): native HLS support
* Chrome, Firefox, Edge: via hls.js library
* IE11: tidak didukung

= Bisa load 2 loading animation sekaligus di satu halaman? =

Secara teknis bisa (Willem + Crisp keduanya aktif), tapi tidak disarankan — keduanya akan render bersamaan dan tampilan akan kacau. Pilih salah satu per page.

== Changelog ==

= 1.1.0 =
* Tambah **Crisp Loading Animation** (slideshow preloader)
* Tambah widget **Bunny HLS Player (Basic)**
* Migrasi loading animation dari widget ke Page Settings (document-level)
* Editor preview mode dengan badge identifikasi
* CSS custom properties untuk per-instance styling
* Typography group control per text element
* Responsive slider untuk logo width (Willem & Crisp)
* Default font: PP Neue Montreal
* GSAP upgrade ke 3.15
* Custom logo image control untuk Crisp animation

= 1.0.0 =
* Initial release dengan **Willem Loading Animation** sebagai widget Elementor

== Upgrade Notice ==

= 1.1.0 =
Loading animation tidak lagi widget — sekarang di Page Settings → Style. Migrasi tidak otomatis: kalau page sebelumnya pakai widget Willem, perlu di-set ulang dari Page Settings.

== Credits ==

* [GSAP](https://gsap.com/) by GreenSock — animation engine
* [hls.js](https://github.com/video-dev/hls.js) — HLS playback library
* [Osmo](https://www.osmo.supply/) — design inspiration
* [Bunny.net](https://bunny.net/) — recommended HLS video hosting
