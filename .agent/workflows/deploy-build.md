---
description: Deploy CSS/JS build assets to production hosting
---
# Deploy Build Assets ke Production

## Kapan Dibutuhkan
Jika styling (CSS/JS) tidak muncul di production setelah update.

## Langkah-Langkah

### 1. Build assets di lokal
```bash
npm run build
```

### 2. Commit dan push
```bash
git add .
git commit -m "Build production assets"
git push
```

### 3. Di hosting terminal (HyperCloudHost)
```bash
cd ~/sertiku
git pull origin main
```

### 4. Copy build ke public_html (PENTING!)
// turbo
```bash
rm -rf ~/public_html/build
cp -r ~/sertiku/public/build ~/public_html/
```

### 5. Clear cache
// turbo
```bash
php artisan optimize:clear
```

### 6. Clear Cloudflare cache (jika pakai)
1. Buka Cloudflare Dashboard
2. Caching → Configuration
3. Klik "Purge Everything"

### 7. Hard refresh browser
Ctrl+Shift+R atau buka di Incognito window

## Catatan Penting
- `public_html` adalah web root yang terpisah dari `~/sertiku/`
- File dari `~/sertiku/public/build/` harus di-copy ke `~/public_html/build/`
- Jangan lupa clear Cloudflare cache jika masih load CSS lama
