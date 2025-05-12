# YouTube Video Downloader Web

這是一個基於 Laravel + Inertia.js + Vue 3 的影片下載工具，讓使用者可以輸入 YouTube 影片網址，自訂格式與時間區段，下載影片或音訊檔案。

## 🚀 功能特色（請補上你的功能，我會幫你擴寫）

- 支援影片或音訊下載（MP4 / MP3）
- 可指定下載區段（例如從 00:01:30 開始到 00:03:00）
- 顯示檔案清單與下載狀態
- ...

## 📦 安裝需求

本專案依賴 `ffmpeg` 與 `yt-dlp` 指令工具，請先確保系統中已正確安裝，並可從 CLI 呼叫這兩個指令。

### 1. 安裝 `ffmpeg`

#### macOS（使用 Homebrew）：
```bash
brew install ffmpeg
