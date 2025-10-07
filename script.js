const API_BASE = "../api.php";
let currentCategory = "Trang chủ";
let currentPage = 1;
const perPage = 9;

async function loadNews(category = "Trang chủ") {
  currentCategory = category;
  currentPage = 1;

  document.querySelector("#news").innerHTML = "<p>Đang tải...</p>";
  try {
    const res = await fetch(`${API_BASE}?category=${encodeURIComponent(category)}`);
    const data = await res.json();
    if (data.error) throw new Error(data.error);

    renderNews(data.news);
    renderPagination(data.news);
  } catch (e) {
    document.querySelector("#news").innerHTML = `<p>Lỗi: ${e.message}</p>`;
  }
}

function renderNews(news) {
  const start = (currentPage - 1) * perPage;
  const paged = news.slice(start, start + perPage);

  document.querySelector("#news").innerHTML = paged
    .map(
      (item) => `
      <div class="news-card">
        <img src="${item.image}" alt="Ảnh" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
        <div class="news-content">
          <h3>${item.title}</h3>
          <p>${item.description.substring(0, 150)}...</p>
          <a href="${item.link}" target="_blank">Đọc thêm</a>
        </div>
      </div>
    `
    )
    .join("");
}

function renderPagination(news) {
  const totalPages = Math.ceil(news.length / perPage);
  const pagination = document.querySelector("#pagination");
  pagination.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.className = i === currentPage ? "active" : "";
    btn.onclick = () => {
      currentPage = i;
      renderNews(news);
      renderPagination(news);
    };
    pagination.appendChild(btn);
  }
}

document.querySelectorAll(".category-btn").forEach((btn) => {
  btn.addEventListener("click", () => loadNews(btn.textContent));
});

loadNews();
