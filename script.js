const apiUrl = "api.php";
const container = document.getElementById("news-container");
const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");
const pageInfo = document.getElementById("page-info");

let newsList = [];
let filtered = [];
let currentPage = 1;
const perPage = 9;

async function loadNews() {
  try {
    const res = await fetch(apiUrl);
    const data = await res.json();
    newsList = data;
    filtered = [...newsList];
    render();
  } catch (err) {
    container.innerHTML = "<p>Lỗi khi tải dữ liệu!</p>";
  }
}

function render() {
  container.innerHTML = "";
  const start = (currentPage - 1) * perPage;
  const pageItems = filtered.slice(start, start + perPage);

  if (pageItems.length === 0) {
    container.innerHTML = "<p>Không có bài viết nào trong mục này.</p>";
    updatePagination();
    return;
  }

  pageItems.forEach((item) => {
    const card = document.createElement("div");
    card.className = "news-card";
    card.innerHTML = `
      <div class="news-image">
        <img src="${item.image || 'https://via.placeholder.com/350x200?text=No+Image'}" alt="${item.title}">
      </div>
      <div class="news-content">
        <h3>${item.title}</h3>
        <p>${item.description || "Không có mô tả."}</p>
        <a href="${item.link}" target="_blank">Đọc tiếp</a>
      </div>
    `;
    container.appendChild(card);
  });

  updatePagination();
}

function updatePagination() {
  const totalPages = Math.ceil(filtered.length / perPage);
  pageInfo.textContent = `Trang ${currentPage} / ${totalPages || 1}`;
  prevBtn.disabled = currentPage === 1;
  nextBtn.disabled = currentPage === totalPages || totalPages === 0;
}

prevBtn.onclick = () => {
  if (currentPage > 1) {
    currentPage--;
    render();
  }
};

nextBtn.onclick = () => {
  const totalPages = Math.ceil(filtered.length / perPage);
  if (currentPage < totalPages) {
    currentPage++;
    render();
  }
};

// ✅ FIXED CATEGORY FILTER
document.querySelectorAll("nav button").forEach((btn) => {
  btn.addEventListener("click", () => {
    document.querySelectorAll("nav button").forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");

    const cat = btn.dataset.category;
    if (cat === "all") {
      filtered = [...newsList];
    } else {
      const keywordMap = {
        "thoi-su": ["thời sự", "xã hội", "chính trị"],
        "kinh-doanh": ["kinh doanh", "tài chính", "doanh nghiệp", "thị trường"],
        "the-thao": ["thể thao", "bóng đá", "v-league", "world cup"],
        "giai-tri": ["giải trí", "showbiz", "ca sĩ", "phim", "ngôi sao"],
        "cong-nghe": ["công nghệ", "tech", "AI", "khoa học", "điện thoại"]
      };

      const keywords = keywordMap[cat] || [];
      filtered = newsList.filter((n) => {
        const text = (n.title + " " + (n.description || "")).toLowerCase();
        return keywords.some((k) => text.includes(k.toLowerCase()));
      });
    }

    currentPage = 1;
    render();
  });
});

loadNews();
