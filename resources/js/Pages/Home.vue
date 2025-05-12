<template>
    <!-- ✅ 成功訊息 -->
    <div
        v-if="successMessage"
        class="mt-4 p-3 text-green-800 bg-green-100 rounded border border-green-300"
    >
        {{ successMessage }}
    </div>

    <!-- ✅ 錯誤訊息 -->
    <div
        v-if="errorMessage"
        class="mt-4 p-3 text-red-700 bg-red-100 rounded border border-red-300"
    >
        {{ errorMessage }}
    </div>
    <div class="flex flex-col gap-4 p-10">
        <!-- 主畫面 -->
        <div class="lg:flex items-center gap-4">
            <div class="lg:w-9/12">
                <Download />
            </div>
            <div class="lg:w-3/12">
                <FileList />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

import Download from "./Components/Download.vue";
import FileList from "./Components/FileList.vue";

/**
 * $page.props 來自 HandleInertiaRequests share()
 */
const page = usePage();

const successMessage = computed(() => page.props.flash?.message || null);

/**
 * 如果你用 withErrors(['message' => 'xxx'])：
 *   errors.message 會是一條字串
 * 如果你用 validate() 失敗：
 *   errors.bag 會是一個 {欄位: [錯誤...] } 的物件
 *   下面程式會抓第一個欄位的第一條錯誤來顯示
 */
const errorMessage = computed(() => {
    if (page.props.errors?.message) {
        return page.props.errors.message;
    }

    const bag = page.props.errors?.bag;
    if (bag && Object.keys(bag).length > 0) {
        const firstField = Object.keys(bag)[0];
        return bag[firstField]?.[0] ?? null;
    }
    return null;
});
</script>
