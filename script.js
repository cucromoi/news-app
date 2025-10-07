// script.js - hiển thị tin, phân trang, lazy-load ảnh

const newsList = document.getElementById("news-list");
const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");
const buttons = document.querySelectorAll("#categories button");

let currentCategory = "trang-chu";
let currentPage = 1;
let itemsPerPage = 9;
let newsData = [];

// Gọi API lấy dữ liệu
async function loadNews(category = currentCategory) {
  newsList.innerHTML = "<p>⏳ Đang tải...</p>";
  try {
    const res = await fetch(`../api.php?category=${category}`);
    const data = await res.json();
    if (data.error) throw new Error(data.error);
    newsData = data;
    currentPage = 1;
    renderPage();
  } catch (err) {
    newsList.innerHTML = `<p style="color:red">Lỗi khi tải dữ liệu!</p>`;
  }
}

// Render 9 bài / trang
function renderPage() {
  newsList.innerHTML = "";
  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const pageItems = newsData.slice(start, end);

  pageItems.forEach(item => {
    const card = document.createElement("div");
    card.className = "card";
    card.innerHTML = `
      <img src="${item.image}" loading="lazy" alt="ảnh">
      <div class="card-content">
        <h3><a href="${item.link}" target="_blank">${item.title}</a></h3>
        <p>${item.description}</p>
      </div>`;
    newsList.appendChild(card);
  });

  prevBtn.disabled = currentPage === 1;
  nextBtn.disabled = end >= newsData.length;
}

buttons.forEach(btn => {
  btn.addEventListener("click", () => {
    buttons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    currentCategory = btn.dataset.category;
    loadNews(currentCategory);
  });
});

prevBtn.onclick = () => {
  if (currentPage > 1) {
    currentPage--;
    renderPage();
  }
};

nextBtn.onclick = () => {
  if ((currentPage * itemsPerPage) < newsData.length) {
    currentPage++;
    renderPage();
  }
};

// Auto load khi mở trang
document.addEventListener("DOMContentLoaded", () => loadNews());
