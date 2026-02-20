# Asset Compilation Guide

## Quick Start

### For Development (Hot Module Replacement - Recommended)
1. Open **TWO** terminal windows
2. Terminal 1: Run Laravel server
   ```bash
   php artisan serve
   ```
3. Terminal 2: Run Vite dev server
   ```bash
   npm run dev
   ```
4. Access your app at `http://127.0.0.1:8000`

**OR** use the batch script:
```bash
start-dev.bat
```

### For Production (Build Once)
```bash
npm run build
php artisan serve
```

## Troubleshooting

### CSS/JS Not Loading?

1. **Check if assets are built:**
   ```bash
   npm run build
   ```

2. **Clear Laravel caches:**
   ```bash
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   ```

3. **In Development Mode:**
   - Make sure `npm run dev` is running in a separate terminal
   - Check that Vite dev server is accessible at `http://localhost:5173`

4. **Check Browser Console:**
   - Open browser DevTools (F12)
   - Check Console for errors
   - Check Network tab to see if assets are loading

5. **Verify .env settings:**
   ```
   APP_ENV=local (for development)
   APP_DEBUG=true
   ```

## Asset Files Location

- **Source:** `resources/css/app.css` and `resources/js/app.js`
- **Built:** `public/build/assets/`
- **Manifest:** `public/build/manifest.json`

## Notes

- In **development mode** (`APP_ENV=local`), you MUST run `npm run dev`
- In **production mode**, run `npm run build` once before deploying
- The Vite dev server provides Hot Module Replacement (HMR) for instant updates











