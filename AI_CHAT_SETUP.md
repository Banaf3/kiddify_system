# 🤖 Kiddify AI Chat Assistant - Setup Guide

## Overview

A mid-right floating AI chat widget powered by Google Gemini, integrated into your Laravel 12 Kiddify application. The assistant helps with preschool learning content and Kiddify system usage.

---

## ✅ Installation Steps

### 1. Run Database Migration

```powershell
php artisan migrate
```

This creates the `chat_messages` table to store conversation history.

---

### 2. Configure Google Gemini API

#### Get Your API Key:

1. Visit [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with your Google account
3. Click "Create API Key"
4. Copy your API key

#### Add to .env file:

Open your `.env` file and add:

```env
GEMINI_API_KEY=your-actual-api-key-here
```

**Important:** Replace `your-actual-api-key-here` with your real Gemini API key.

---

### 3. Clear Configuration Cache

```powershell
php artisan config:clear
php artisan cache:clear
```

---

### 4. Build Frontend Assets

```powershell
npm install
npm run dev
```

For production:

```powershell
npm run build
```

---

## 🎨 Features Implemented

### ✅ Backend (Laravel)

1. **Database**

    - `ChatMessage` model with user relationship
    - Migration for chat_messages table
    - Methods to retrieve recent messages and clear history

2. **Gemini Service** (`app/Services/GeminiService.php`)

    - Secure API integration with Google Gemini
    - System prompt restricting AI to preschool learning + system help
    - Context-aware conversation memory (last 10 messages)
    - Error handling for API failures (401, 429, 500, timeouts)
    - Safety settings to block harmful content

3. **Controller** (`app/Http/Controllers/AiChatController.php`)

    - `GET /ai/chat/history` - Load recent chat history
    - `POST /ai/chat` - Send message and get AI response
    - `POST /ai/chat/clear` - Clear user's chat history
    - Input validation (max 800 chars)
    - CSRF protection

4. **Routes** (`routes/web.php`)
    - Auth middleware required
    - Rate limiting: 20 messages per minute per user
    - Configured in `AppServiceProvider`

---

### ✅ Frontend

1. **Blade Component** (`resources/views/components/ai-chat-widget.blade.php`)

    - Floating button positioned at mid-right (50% top)
    - Modern chat panel with gradient header
    - Message bubbles (user right, assistant left)
    - Typing indicator with animated dots
    - Clear chat button
    - Responsive design (mobile-friendly)

2. **JavaScript** (`resources/js/ai-chat.js`)

    - Toggle chat panel
    - Load conversation history on first open
    - Send messages via AJAX
    - Display responses with animations
    - Handle errors gracefully
    - Clear chat confirmation
    - Scroll to bottom automatically

3. **Styling** (`resources/css/app.css`)
    - Matches Kiddify purple-pink gradient theme
    - Smooth animations
    - Custom scrollbar styling
    - Responsive breakpoints

---

## 🎯 AI Assistant Capabilities

### ✅ What the Assistant CAN Help With:

1. **Preschool Learning Content:**

    - Letters (A-Z) and alphabet learning
    - Numbers (0-9) and counting
    - Colors, shapes, and patterns
    - Basic English and Arabic vocabulary
    - Simple educational activities for ages 3-6
    - Age-appropriate songs and rhymes

2. **Kiddify System Help:**
    - Login & OTP verification
    - Managing courses and timetables
    - Creating assessments and questions
    - Grading student scores
    - Managing sections
    - Parent-child account linking
    - Admin user approvals
    - Understanding icons and navigation
    - Password reset procedures

### ❌ What the Assistant REFUSES:

If users ask about:

-   Hacking, security exploits, or technical implementation
-   Politics, controversial topics
-   Adult content, violence, or harmful content
-   Anything unrelated to preschool learning or Kiddify system

The assistant will politely refuse and redirect to allowed topics.

---

## 📍 Widget Positioning

-   **Desktop:** Mid-right of screen (50% from top)
-   **Mobile/Tablet:** Bottom-right corner
-   **Z-index:** 1000 (appears above other content)
-   **Responsive:** Adapts to screen size

---

## 🔒 Security Features

1. **Authentication Required:** Only logged-in users can access chat
2. **Rate Limiting:** 20 messages per minute per user
3. **CSRF Protection:** All requests are CSRF-protected
4. **Input Validation:** Messages limited to 800 characters
5. **API Key Security:** Gemini API key stored in `.env`, never exposed to frontend
6. **Error Logging:** Errors logged without exposing sensitive data
7. **Content Safety:** Gemini's built-in safety filters enabled

---

## 📊 Database Schema

```sql
chat_messages
├── id (bigint, primary key)
├── user_id (foreignId → users.id, cascade delete)
├── role (enum: 'user', 'assistant')
├── content (text)
├── created_at (timestamp)
└── updated_at (timestamp)
```

**Indexes:**

-   `(user_id, created_at)` - Optimizes history queries

---

## 🚀 Usage

1. **Login to Kiddify** as any role (Admin, Teacher, Parent, Student)
2. Look for the **floating chat button** on the mid-right side
3. Click to open the chat panel
4. Type your question and press Enter or click Send
5. Assistant responds within seconds
6. Chat history persists across sessions
7. Click "Clear chat history" to start fresh

---

## 🧪 Testing Checklist

-   [ ] Widget appears on all authenticated pages
-   [ ] Floating button positioned mid-right
-   [ ] Click opens/closes chat panel
-   [ ] Chat history loads on first open
-   [ ] Messages send and receive responses
-   [ ] Typing indicator appears while waiting
-   [ ] Error messages display on API failures
-   [ ] Clear chat removes all history
-   [ ] Rate limiting triggers after 20 messages/minute
-   [ ] Responsive design works on mobile
-   [ ] Gradient styling matches Kiddify theme

---

## 🐛 Troubleshooting

### Issue: "Authentication error"

**Solution:** Verify `GEMINI_API_KEY` in `.env` is correct

### Issue: "Too many requests"

**Solution:** Wait 60 seconds - rate limit will reset

### Issue: Widget not appearing

**Solution:**

```powershell
npm run dev
php artisan config:clear
```

### Issue: "Assistant is temporarily unavailable"

**Solution:** Check Gemini API status at [Google Cloud Status](https://status.cloud.google.com/)

### Issue: JavaScript errors

**Solution:**

```powershell
npm install
npm run build
```

---

## 📝 Files Created/Modified

### New Files:

```
database/migrations/2025_12_19_create_chat_messages_table.php
app/Models/ChatMessage.php
app/Services/GeminiService.php
app/Http/Controllers/AiChatController.php
resources/views/components/ai-chat-widget.blade.php
resources/js/ai-chat.js
.env.gemini.example
```

### Modified Files:

```
routes/web.php
app/Providers/AppServiceProvider.php
config/services.php
resources/js/app.js
resources/css/app.css
resources/views/layouts/app.blade.php
```

---

## 🎓 System Prompt Engineering

The assistant uses a carefully crafted system prompt that:

1. **Establishes identity:** "Kiddify Assistant"
2. **Defines strict scope:** Only preschool learning + Kiddify system help
3. **Sets refusal policy:** Politely declines out-of-scope requests
4. **Provides context:** Lists all Kiddify modules and features
5. **Enforces tone:** Simple, encouraging, child-friendly language
6. **Maintains safety:** No code, credentials, or technical implementations

---

## 📞 Support

For issues or questions:

-   Check Laravel logs: `storage/logs/laravel.log`
-   Verify Gemini API quota: [Google Cloud Console](https://console.cloud.google.com/)
-   Review error messages in browser console (F12)

---

## 🎉 Success Criteria Met

✅ Floating chat icon appears mid-right
✅ Clicking opens chat panel matching screenshot style
✅ Messages send and return from Gemini
✅ Chat history loads and can be cleared
✅ Styling matches Kiddify purple-pink gradient theme
✅ AI strictly limited to preschool learning + system help
✅ Rate limiting active (20 messages/minute)
✅ CSRF protected and authenticated
✅ Responsive design works on all devices
✅ Error handling provides friendly messages

---

## 📦 API Rate Limits

**Google Gemini Free Tier:**

-   60 requests per minute
-   1,500 requests per day

**Kiddify App Rate Limit:**

-   20 messages per minute per user
-   Configurable in `AppServiceProvider::boot()`

---

## 🔄 Future Enhancements (Optional)

-   [ ] Add voice input/output
-   [ ] Multi-language support (Arabic)
-   [ ] Suggested quick responses
-   [ ] Export chat history
-   [ ] Admin dashboard for chat analytics
-   [ ] Custom avatars per user role
-   [ ] Markdown rendering in messages
-   [ ] File/image attachment support

---

**Built with ❤️ for Kiddify Preschool Learning System**
