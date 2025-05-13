# YouTube Video Downloader Web

![demo](https://raw.githubusercontent.com/yi0x44f/yt-dlp_GUI/refs/heads/main/image.png)

這是一個 YouTube 影片下載工具。  
使用者可以輸入影片網址，自訂格式、畫質、時間區段，自動下載影片或音訊。

---

## 🚀 功能特色

- 🎞 支援影片與音訊下載（MP4 / MP3）
- 📺 支援畫質選擇（例：1080p、720p）
- ⏱ 可指定下載區段（如從 `00:01:30` 到 `00:03:00`）
- 📂 顯示檔案清單，可點擊下載

---

## 📦 安裝與啟動

### 1️⃣ 安裝前端資源

```bash
npm install
npm run build
```

## 2️⃣ 啟動服務（使用 Docker Compose）
```
docker compose up --build
```
✅ 系統會自動建構 Laravel 環境並啟用 yt-dlp 與 ffmpeg
🌐 開啟瀏覽器並造訪 http://localhost 即可使用

🧱 技術架構
Backend: Laravel 12 + Docker
Frontend: Inertia.js + Vue 3 + Tailwind CSS
CLI Tools: yt-dlp + ffmpeg


