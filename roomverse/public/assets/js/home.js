document.addEventListener("DOMContentLoaded", function () {
  initHeroCarousel();
  initSearchForm();
});

/* ---------------------------------------------------------------- */
/* Hero banner carousel                                               */
/* ---------------------------------------------------------------- */
function initHeroCarousel() {
  const banner = document.getElementById("heroBanner");
  if (!banner) return;

  const slides = [...banner.querySelectorAll(".hero-slide")];
  const dots = [...banner.querySelectorAll(".hero-dot")];

  const prevBtn = banner.querySelector(".hero-arrow-left");
  const nextBtn = banner.querySelector(".hero-arrow-right");

  if (slides.length === 0) return;

  let current = 0;
  let autoSlide;

  function showSlide(index) {
    current = (index + slides.length) % slides.length;

    slides.forEach((slide, i) => {
      slide.classList.toggle("is-active", i === current);
    });

    dots.forEach((dot, i) => {
      dot.classList.toggle("is-active", i === current);
    });
  }

  function nextSlide() {
    showSlide(current + 1);
  }

  function prevSlide() {
    showSlide(current - 1);
  }

  function startAutoSlide() {
    stopAutoSlide();

    autoSlide = setInterval(() => {
      nextSlide();
    }, 3000);
  }

  function stopAutoSlide() {
    clearInterval(autoSlide);
  }

  nextBtn?.addEventListener("click", () => {
    nextSlide();
    startAutoSlide();
  });

  prevBtn?.addEventListener("click", () => {
    prevSlide();
    startAutoSlide();
  });

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      showSlide(index);
      startAutoSlide();
    });
  });

  banner.addEventListener("mouseenter", stopAutoSlide);
  banner.addEventListener("mouseleave", startAutoSlide);

  showSlide(0);
  startAutoSlide();
}

/* ---------------------------------------------------------------- */
/* Form tìm kiếm -> gọi api/rooms/search.php -> render lại 4 phòng    */
/* ---------------------------------------------------------------- */
function initSearchForm() {
  const form = document.getElementById("searchForm");
  const grid = document.getElementById("roomGrid");
  const useLocationBtn = document.getElementById("useMyLocationBtn");
  if (!form || !grid) return;

  let userCoords = null;

  useLocationBtn &&
    useLocationBtn.addEventListener("click", function () {
      if (!navigator.geolocation) {
        alert("Trình duyệt của bạn không hỗ trợ định vị.");
        return;
      }
      useLocationBtn.textContent = "Đang lấy vị trí...";
      navigator.geolocation.getCurrentPosition(
        function (pos) {
          userCoords = { lat: pos.coords.latitude, lng: pos.coords.longitude };
          useLocationBtn.textContent = "Đã dùng vị trí của bạn ✓";
          runSearch();
        },
        function () {
          alert("Không lấy được vị trí. RoomVerse sẽ dùng khu vực bạn đã chọn.");
          useLocationBtn.textContent = "Dùng vị trí của tôi";
        },
      );
    });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    runSearch();
  });

  function runSearch() {
    const formData = new FormData(form);
    const params = new URLSearchParams();

    const doiTuong = formData.get("doi_tuong_id");
    if (doiTuong) params.set("doi_tuong_id", doiTuong);

    const giaKhoang = formData.get("gia_khoang");
    if (giaKhoang) {
      const [min, max] = giaKhoang.split("-");
      params.set("gia_min", min);
      params.set("gia_max", max);
    }

    const khoangCach = formData.get("khoang_cach_km");
    if (khoangCach) params.set("khoang_cach_km", khoangCach);

    if (userCoords) {
      params.set("user_lat", userCoords.lat);
      params.set("user_lng", userCoords.lng);
    } else {
      const khuVuc = formData.get("khu_vuc_id");
      if (khuVuc) params.set("khu_vuc_id", khuVuc);
    }

    setLoading(true);

    fetch("api/rooms/search.php?" + params.toString())
      .then((res) => res.json())
      .then((json) => {
        if (json.success) {
          renderRooms(json.data);
        } else {
          grid.innerHTML =
            '<p class="no-results">' +
            (json.message || "Có lỗi xảy ra, vui lòng thử lại.") +
            "</p>";
        }
      })
      .catch(() => {
        grid.innerHTML =
          '<p class="no-results">Không thể kết nối máy chủ, vui lòng thử lại.</p>';
      })
      .finally(() => setLoading(false));
  }

  function setLoading(isLoading) {
    grid.style.opacity = isLoading ? "0.5" : "1";
  }

  function renderRooms(rooms) {
    if (!rooms.length) {
      grid.innerHTML =
        '<p class="no-results">Không tìm thấy phòng trọ phù hợp.</p>';
      return;
    }
    grid.innerHTML = rooms.map(roomCardHtml).join("");
  }

  function roomCardHtml(room) {
    const isNew =
      room.created_at &&
      new Date(room.created_at) >=
        new Date(Date.now() - 7 * 24 * 60 * 60 * 1000);
    const image = room.anh_dai_dien || "assets/images/rooms/placeholder.jpg";
    const price = Number(room.gia).toLocaleString("vi-VN");
    const distanceHtml =
      room.khoang_cach !== null && room.khoang_cach !== undefined
        ? `<span class="room-distance">📶 ${Number(room.khoang_cach).toFixed(1)} km</span>`
        : "";
    const ratingHtml = room.diem_trung_binh
      ? `<span class="room-rating">⭐ ${room.diem_trung_binh} (${room.so_luot_danh_gia})</span>`
      : `<span class="room-rating room-rating-empty">Chưa có đánh giá</span>`;
    const address = room.dia_chi || room.ten_dia_diem || "";

    return `
        <article class="room-card" data-id="${room.id}">
            <div class="room-card-media">
                ${isNew ? '<span class="badge badge-new">Mới</span>' : ""}
                <button class="room-fav-btn" aria-label="Yêu thích">♡</button>
                <img src="${escapeHtml(image)}" alt="${escapeHtml(room.ten_phong)}" loading="lazy">
            </div>
            <div class="room-card-body">
                <h3 class="room-title">${escapeHtml(room.ten_phong)}</h3>
                <p class="room-address">📍 ${escapeHtml(address)}</p>
                <p class="room-price">${price} đ/tháng</p>
                <div class="room-meta">${distanceHtml}${ratingHtml}</div>
                <div class="room-actions">
                    <a class="btn btn-primary btn-sm" href="index.php?page=vr&id=${room.id}">Xem 360 VR</a>
                    <a class="btn btn-outline btn-sm" href="index.php?page=detail&id=${room.id}">Xem chi tiết</a>
                </div>
            </div>
        </article>`;
  }

  function escapeHtml(str) {
    const div = document.createElement("div");
    div.textContent = str ?? "";
    return div.innerHTML;
  }
}
