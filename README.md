# 📰 News PHP Web App (VNExpress RSS)

Trang web đọc tin tức được xây dựng bằng **PHP, HTML, CSS và JavaScript**,  
tự động lấy dữ liệu từ **RSS Feed của VNExpress** để hiển thị tin mới nhất theo từng chuyên mục.

---

## 💡 Mô tả

- Ứng dụng hiển thị danh sách tin tức từ VNExpress thông qua RSS.  
- Có phân loại các chuyên mục: Trang chủ, Thế giới, Kinh doanh, Giải trí, Thể thao, Pháp luật, Khoa học.  
- Hỗ trợ phân trang (3x3 bài viết mỗi trang).  
- Hiển thị ảnh, tiêu đề, mô tả ngắn và liên kết gốc đến bài viết.  
- Giao diện responsive, tối ưu cho cả desktop và mobile.  
- Không cần cơ sở dữ liệu, hoạt động hoàn toàn qua API RSS.

---

## 🧠 Công nghệ sử dụng

- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Backend:** PHP 8.x (SimpleXML + cURL)
- **Nguồn dữ liệu:** [VNExpress RSS](https://vnexpress.net/rss)
- **Layout:** Grid 3x3, hiển thị từng trang 9 bài viết

---

## 🧩 Chức năng chính

| Chức năng | Mô tả |
|------------|--------|
| 📰 Hiển thị tin tức | Lấy dữ liệu mới nhất từ RSS và render ra giao diện |
| 🧭 Chọn chuyên mục | Người dùng có thể chọn danh mục để lọc tin |
| 📄 Phân trang | Hiển thị tối đa 9 bài mỗi trang, có nút chuyển trang |
| 📱 Responsive | Giao diện tự co giãn phù hợp mọi thiết bị |
| ⚙️ Không cần DB | Hoạt động hoàn toàn dựa trên RSS, không cần MySQL |

---
