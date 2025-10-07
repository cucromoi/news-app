# 📰 NewsPH — Simple PHP Web News Scraper

Một web app nhỏ viết bằng **PHP + HTML + CSS + JavaScript**, tự động **lấy tin tức (title, description, image, link)** từ trang [VnExpress.net](https://vnexpress.net/) và hiển thị trên giao diện web hiện đại, responsive.

---

## 🚀 Demo Preview

> Giao diện có header, sidebar, danh sách tin có hình, mô tả ngắn, và footer gọn gàng.

---


## 🧠 Cách hoạt động

### `api.php`
- Dùng **cURL** để tải HTML từ VnExpress (để tránh bị chặn User-Agent).  
- Parse HTML bằng `DOMDocument` + `DOMXPath`.  
- Trích xuất:
- `title` (tiêu đề)
- `description` (mô tả)
- `image` (ảnh minh họa)
- `link` (đường dẫn bài viết)  
- Trả về dữ liệu JSON chuẩn UTF-8.

### `script.js`
- Fetch dữ liệu từ `api.php` bằng `fetch()`.
- Render tin tức lên giao diện (`<ul id="news-list">`).
- Tự động tải khi mở trang, có nút **"Tải tin"** để reload.

### `style.css`
- Giao diện có:
- Header (logo + nav)
- Sidebar (chuyên mục + giới thiệu)
- Content (danh sách tin tức)
- Footer (bản quyền + link)
- Responsive: Sidebar ẩn dưới khi màn nhỏ.

---
