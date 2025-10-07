// script.js
// ----------------------------
// Lấy tin từ api.php và hiển thị
// ----------------------------

async function loadNews() {
  const list = document.getElementById("news-list");
  list.innerHTML = "<li>Đang tải tin...</li>";

  try {
    const res = await fetch("api.php");
    const data = await res.json();

    if (data.error) {
      list.innerHTML = `<li>${data.error}</li>`;
      return;
    }

    list.innerHTML = "";
    data.forEach(item => {
      const li = document.createElement("li");
      li.innerHTML = `
        ${item.image ? `<img src="${item.image}" alt="">` : ""}
        <h3><a href="${item.link}" target="_blank">${item.title}</a></h3>
        <p>${item.description}</p>
      `;
      list.appendChild(li);
    });
  } catch (e) {
    list.innerHTML = "<li>Lỗi khi tải dữ liệu!</li>";
    console.error(e);
  }
}

// Tự động tải khi mở trang
window.addEventListener("DOMContentLoaded", loadNews);
document.getElementById("load-btn").addEventListener("click", loadNews);
