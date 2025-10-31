# AI News Insight Dashboard 📰🤖

A portfolio-ready starter project that fetches the latest news for a selected country and produces AI-generated summaries and trend insights. Perfect for showcasing Laravel, Inertia + Vue, and Tailwind skills.

---

## ⚙️ Stack
- **Backend:** Laravel 11  
- **Frontend:** Inertia.js + Vue 3  
- **UI:** TailwindCSS  
- **Database:** MySQL (via Laravel Sail)  
- **API:** NewsAPI (fetch headlines)  
- **AI:** Placeholder service (mocked; replace with OpenAI/HuggingFace)

---

## 🚀 Run locally

```bash
# Start Sail containers
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Build frontend assets
./vendor/bin/sail npm run dev
## ✅ Features
- Fetch top headlines by country
- Store articles and AI insights in the database
- Mock AI summarization (ready to replace with real API)
- Interactive Inertia + Vue dashboard

---