<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900">AI News Insight Dashboard</h1>
      </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
      <div class="mb-8">
        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
          Select Country
        </label>
        <div class="flex gap-4">
          <select v-model="selectedCountry" @change="fetchNews" class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Choose a country...</option>
            <option v-for="country in countries" :key="country.code" :value="country.code">{{ country.name }}</option>
          </select>
          <button @click="fetchNews(true)" :disabled="loading || !selectedCountry" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
            {{ loading ? 'Loading...' : 'Refresh' }}
          </button>
        </div>
      </div>
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
        <p class="mt-4 text-gray-600">Fetching and analyzing news...</p>
      </div>
      <div v-if="error" class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
        <p class="text-red-800">{{ error }}</p>
      </div>
      <div v-if="insight" class="space-y-6">
        <div v-if="cached" class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
          <p class="text-yellow-800">Showing cached data from {{ formatDate(insight.generated_at) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">📰 AI Summary</h2>
          <p class="text-gray-700 leading-relaxed">{{ insight.summary }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">📊 Category Trends</h2>
          <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div v-for="(count, category) in insight.category_trends" :key="category" class="text-center p-4 bg-gray-50 rounded-lg">
              <div class="text-2xl font-bold text-blue-600">{{ count }}</div>
              <div class="text-sm text-gray-600 capitalize">{{ category }}</div>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">💬 AI Analysis</h2>
          <p class="text-gray-700 leading-relaxed">{{ insight.analysis }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Latest Headlines</h2>
          <div class="space-y-4">
            <div v-for="article in articles" :key="article.id" class="border-b border-gray-200 pb-4 last:border-b-0">
              <h3 class="font-medium text-gray-900">{{ article.title }}</h3>
              <p class="text-sm text-gray-600 mt-1">{{ article.description }}</p>
              <div class="flex justify-between items-center mt-2">
                <span class="text-xs text-gray-500">{{ article.source }} • {{ formatDate(article.published_at) }}</span>
                <a :href="article.url" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">Read more →</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ countries: Array, recentInsights: Array })
const selectedCountry = ref('')
const loading = ref(false)
const error = ref('')
const insight = ref(null)
const articles = ref([])
const cached = ref(false)

const fetchNews = async (forceRefresh = false) => {
  if (!selectedCountry.value) return
  loading.value = true; error.value = ''
  try {
    const response = await fetch('/api/news', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ country: selectedCountry.value, force_refresh: forceRefresh })
    })
    const data = await response.json()
    if (!response.ok) throw new Error(data.error || 'Failed to fetch news')
    insight.value = data.insight; articles.value = data.articles; cached.value = data.cached
  } catch (err) { error.value = err.message } finally { loading.value = false }
}

const loadRecentInsight = (recentInsight) => {
  selectedCountry.value = recentInsight.country_code
  insight.value = recentInsight
  articles.value = recentInsight.articles || []
  cached.value = true
}

const formatDate = (dateString) => new Date(dateString).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' })

onMounted(() => { if (props.countries.length > 0) selectedCountry.value = props.countries[0].code })
</script>
