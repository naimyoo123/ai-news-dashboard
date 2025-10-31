<template>
  <div class="p-8">
    <h1 class="text-3xl font-bold mb-4">📰 AI News Dashboard</h1>
    <p class="text-gray-600 mb-6">Stay updated with the latest AI & tech headlines.</p>

    <div v-if="articles.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="(article, index) in articles"
        :key="index"
        class="p-4 bg-white shadow rounded-lg hover:shadow-lg transition"
      >
        <img
          v-if="article.urlToImage"
          :src="article.urlToImage"
          alt="news"
          class="rounded mb-3 h-40 w-full object-cover"
        />
        <h2 class="text-lg font-semibold mb-2">{{ article.title }}</h2>
        <p class="text-sm text-gray-600 mb-3">{{ article.source.name }}</p>
        <a
          :href="article.url"
          target="_blank"
          class="text-blue-600 text-sm hover:underline"
        >
          Read more →
        </a>
      </div>
    </div>

    <div v-else class="text-gray-500">No articles found.</div>
  </div>
</template>

<script setup>
defineProps({
  articles: Array
})
</script>

<style scoped>
body {
  background-color: #f9fafb;
}
</style>

