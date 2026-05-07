# Vendor Assets

Library pihak ketiga yang di-bundle lokal supaya:
- Tidak ada round-trip ke CDN saat development
- Versi terkunci (reproducible)
- Bisa di-tune (concat/minify ulang) lewat build pipeline lokal

## Versi

| Library | Versi | Source | File |
|---|---|---|---|
| GSAP core | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/gsap.min.js | `gsap/gsap.min.js` |
| SplitText | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/SplitText.min.js | `gsap/SplitText.min.js` |
| CustomEase | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/CustomEase.min.js | `gsap/CustomEase.min.js` |
| ScrollTrigger | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/ScrollTrigger.min.js | `gsap/ScrollTrigger.min.js` |
| Draggable | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/Draggable.min.js | `gsap/Draggable.min.js` |
| InertiaPlugin | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/InertiaPlugin.min.js | `gsap/InertiaPlugin.min.js` |
| DrawSVGPlugin | 3.15.0 | https://cdn.jsdelivr.net/npm/gsap@3.15/dist/DrawSVGPlugin.min.js | `gsap/DrawSVGPlugin.min.js` |
| hls.js | 1.6.11 | https://cdn.jsdelivr.net/npm/hls.js@1.6.11/dist/hls.min.js | `hls/hls.min.js` |

## Update / Re-download

```bash
cd assets/vendor/gsap
GSAP_VER=3.15
for f in gsap SplitText CustomEase ScrollTrigger Draggable InertiaPlugin DrawSVGPlugin; do
  curl -sSfL -o "${f}.min.js" "https://cdn.jsdelivr.net/npm/gsap@${GSAP_VER}/dist/${f}.min.js"
done

cd ../hls
HLS_VER=1.6.11
curl -sSfL -o hls.min.js "https://cdn.jsdelivr.net/npm/hls.js@${HLS_VER}/dist/hls.min.js"
```

Setelah ganti versi, update juga konstanta di `elementor-gsap.php` (`ELEMENTOR_GSAP_GSAP_VER` & `ELEMENTOR_GSAP_HLS_VER`) dan refresh halaman — auto-purge cache Elementor akan jalan otomatis.

## Filter override

Kalau ingin paksa pakai CDN (mis. di production dengan global CDN sendiri),
filter `elementor_gsap_use_local_vendor` bisa di-set false:

```php
add_filter( 'elementor_gsap_use_local_vendor', '__return_false' );
```
