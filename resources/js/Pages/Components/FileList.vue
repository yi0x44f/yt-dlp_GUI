<template>
  <div class="border p-4 rounded">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold mb-2">影片列表</h2>
      <button class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer" @click="clearFiles">Clear All</button>
    </div>
    <ul class="list-disc ml-5">
      <li v-for="file in files" :key="file.name">
        <a :href="file.url" target="_blank" class="text-blue-600 hover:underline">
          {{ file.name }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { usePage, router } from '@inertiajs/vue3'

/* 後端 Inertia::render('Home', ['files' => [...]]) */
const page  = usePage()

const loading = ref(false)
const files = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/filelist')
    files.value = res.data
  } catch (err) {
    console.error('讀取檔案列表失敗:', err)
  }
})


/** 清空影片檔 */
function clearFiles () {
  if (!files.value.length || loading.value) return
  if (!confirm('確定要刪除所有影片檔？')) return

  router.delete('/filelist', {
    // ❗ false ⇒ 刪除成功後整個 Home 重新掛載，files 自動刷新
    preserveState: false,

    onStart:  () => loading.value = true,
    onFinish: () => loading.value = false,
  })
}

</script>
