# 🚀 Quick Setup Commands

## Step-by-Step Setup

### 1. Run Migration

```powershell
php artisan migrate
```

### 2. Add Gemini API Key to .env

```env
GEMINI_API_KEY=your-api-key-from-google
```

Get your key: https://makersuite.google.com/app/apikey

### 3. Clear Cache

```powershell
php artisan config:clear
php artisan cache:clear
```

### 4. Build Assets

```powershell
npm install
npm run dev
```

### 5. Test

-   Login to Kiddify
-   Look for floating chat button on mid-right
-   Click and start chatting!

---

## Production Deployment

```powershell
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Verify Installation

✅ Widget visible after login
✅ Can send/receive messages
✅ Chat history persists
✅ Rate limiting works (20 msg/min)
✅ Mobile responsive

---

## Common Commands

### Clear everything:

```powershell
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Rebuild assets:

```powershell
npm run build
```

### Check logs:

```powershell
Get-Content storage/logs/laravel.log -Tail 50
```
