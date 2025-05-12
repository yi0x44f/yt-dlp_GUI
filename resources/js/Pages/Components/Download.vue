<template>
        <div class="flex justify-center w-full">
            <div class="sm:w-1/2 w-3/4">
                <div class="flex justify-center">
                    <img src="/public/Youtube.png" class="w-1/2" alt="" />
                </div>
                <form
                    id="downloadForm"
                    class="flex flex-col gap-2 my-5"
                    @submit.prevent="submit"
                >
                    <!-- 影片網址 -->
                    <label for="url" class="text-sm font-medium"
                        >Youtube Video URL</label
                    >
                    <input
                        type="url"
                        id="url"
                        name="url"
                        placeholder="https://youtu.be/xxxxxxxxxxx"
                        required
                        v-model="form.url"
                        class="input input-bordered"
                    />

                    <!-- 檔案格式 -->
                    <label for="format" class="text-sm font-medium"
                        >檔案格式</label
                    >
                    <select
                        id="format"
                        name="format"
                        required
                        v-model="form.format"
                        class="select select-bordered"
                    >
                        <option value="mp4">mp4 (影片)</option>
                        <option value="mp3">mp3 (音訊)</option>
                    </select>

                    <!-- 畫質 v-if format == mp4 -->
                    <label
                        v-if="form.format === 'mp4'"
                        for="resolution"
                        class="text-sm font-medium"
                    >
                        畫質</label
                    >
                    <select
                        v-if="form.format === 'mp4'"
                        id="resolution"
                        name="resolution"
                        required
                        v-model="form.resolution"
                        class="select select-bordered"
                    >
                        <option value="1080">1080p</option>
                        <option value="720">720p</option>
                        <option value="480">480p</option>
                        <option value="360">360p</option>
                        <option value="best">Best</option>
                    </select>

                    <!-- 下載開始時間 -->
                    <label for="start" class="text-sm font-medium">
                        Start Time <small>(HH:MM:SS，可留空)</small>
                    </label>
                    <input
                        type="text"
                        id="start"
                        name="start"
                        pattern="^([0-9]{2}):([0-5][0-9]):([0-5][0-9])$"
                        placeholder="00:01:30"
                        v-model="form.start_time"
                        class="input input-bordered"
                    />

                    <!-- 下載結束時間 -->
                    <label for="end" class="text-sm font-medium">
                        End Time <small>(HH:MM:SS，可留空)</small>
                    </label>
                    <input
                        type="text"
                        id="end"
                        name="end"
                        pattern="^([0-9]{2}):([0-5][0-9]):([0-5][0-9])$"
                        placeholder="00:03:00"
                        v-model="form.end_time"
                        class="input input-bordered"
                    />

                    <!-- 3️⃣ 按鈕：class 與 :disabled 綁 canSubmit -->
                    <button
                        type="submit"
                        class="btn rounded-4xl p-2 ring-1 transition"
                        :class="
                            canSubmit
                                ? 'bg-blue-500 ring-blue-500 text-white  cursor-pointer'
                                : 'bg-gray-300 ring-gray-400 text-gray-500 cursor-not-allowed'
                        "
                        :disabled="!canSubmit"
                    >
                        送出下載任務
                    </button>
                </form>
                <!-- ===== 表單結束 ===== -->
                <div v-if="form.processing">Please wait...</div>

            </div>
        </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, watch } from "vue";

const form = useForm({
    url: "",
    format: "mp4",
    resolution: "1080",
    start_time: "",
    end_time: "",
    processing: false,
});
/* ---------- 工具函式：把 0 ~ 6 位數字 → hh:mm:ss ---------- */

function normalizeTime(val) {
    // 只留數字 → 補到 6 碼 → 取最後 6 碼
    const digits = (val ?? "")
        .toString()
        .replace(/\D/g, "")
        .padStart(6, "0")
        .slice(-6);
    if (digits.length !== 6) return val; // 還沒湊滿，先不改
    const hh = digits.slice(0, 2);
    const mm = digits.slice(2, 4);
    const ss = digits.slice(4, 6);
    return `${hh}:${mm}:${ss}`;
}

/* ---------- 監聽 start_time ---------- */
watch(
    () => form.start_time,
    (newVal) => {
        const fmt = normalizeTime(newVal);
        if (fmt !== newVal) form.start_time = fmt; // 只在格式化後不一樣時回寫
    }
);

/* ---------- 監聽 end_time ---------- */
watch(
    () => form.end_time,
    (newVal) => {
        const fmt = normalizeTime(newVal);
        if (fmt !== newVal) form.end_time = fmt;
    }
);

/* ---------------- 1️⃣  計算「可送出」狀態 ---------------- */
const canSubmit = computed(() => {
    const urlPattern = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i;

    return (
        !form.processing &&
        typeof form.url === "string" &&
        form.url.trim() !== "" &&
        urlPattern.test(form.url.trim()) &&
        ["mp4", "mp3"].includes(form.format) &&
        // start_time < end_time
        (form.start_time === "" ||
            form.end_time === "" ||
            form.start_time < form.end_time)
    );
});

/* ---------------- 2️⃣  送出 ---------------- */
function submit() {
    if (!canSubmit.value) return;

    form.submit("post", "/", {
        preserveState: false,
        preserveScroll: true,
    });
}
</script>

<style scoped></style>
