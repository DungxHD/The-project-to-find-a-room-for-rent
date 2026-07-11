// =========================================================
// LUONG MO PHONG: Cau hoi -> Lua chon -> Phan tich -> Video -> Cau hoi tiep theo
// =========================================================

const clockEl = document.getElementById("clock");
const mainStage = document.getElementById("main-stage");
const chatBox = document.getElementById("chat-box");
const bubbleQuestion = document.getElementById("bubble-question");
const choicesEl = document.getElementById("choices");
const analysisBox = document.getElementById("analysis-box");
const routeLine = document.getElementById("route-line");
const fadeOverlay = document.getElementById("fade-overlay");
const videoOverlay = document.getElementById("video-overlay");
const videoPlayer = document.getElementById("video-player");
const endScreen = document.getElementById("end-screen");
const btnRestart = document.getElementById("btn-restart");

// ---- Dieu khien video (play/pause, tua, quay lai, bo qua) ----
const btnPlayPause = document.getElementById("btn-play-pause");
const iconPlay = document.getElementById("icon-play");
const iconPause = document.getElementById("icon-pause");
const videoSeek = document.getElementById("video-seek");
const videoTime = document.getElementById("video-time");
const btnBackAnalysis = document.getElementById("btn-back-analysis");
const btnSkipNext = document.getElementById("btn-skip-next");
const btnResumeVideo = document.getElementById("btn-resume-video");

// ---- Danh gia phong tro (man hinh ket thuc) ----
const roomReview = document.getElementById("room-review");
const reviewName = document.getElementById("review-name");
const reviewGrid = document.getElementById("review-grid");
const reviewComment = document.getElementById("review-comment");

// >>> SUA O DAY: doi ten bien pendingNextStt -> pendingNextId
let pendingNextId = null;

// Luu lai du lieu phan tich gan nhat de co the quay lai xem
let lastAnalysisData = null;

// Co dang keo thanh tua hay khong (tranh xung dot voi timeupdate)
let isSeeking = false;

// ---------------------------------------------------------
// 1) TAI CAU HOI THEO id_ai_hoi (khong con dung stt de dieu huong)
// ---------------------------------------------------------
// >>> SUA O DAY: doi tham so ham tu "stt" thanh "id"
async function loadQuestion(id) {
  try {
    // >>> SUA O DAY: doi query string stt=${stt} -> id=${id}
    const res = await fetch(`api.php?action=get_question&id=${id}`);

    if (!res.ok) {
      throw new Error(`Server trả về lỗi HTTP ${res.status}`);
    }

    const data = await res.json();

    if (data.error) {
      throw new Error(data.error);
    }

    if (!data.found) {
      showEndScreen();
      return;
    }
    renderQuestion(data);
  } catch (err) {
    showError(err.message || "Không thể tải câu hỏi.");
  }
}

function showError(message) {
  choicesEl.innerHTML = "";
  bubbleQuestion.textContent =
    message +
    " — Kiểm tra: (1) trang phải được mở qua server PHP (vd: http://localhost/songthu/), " +
    'KHÔNG phải mở trực tiếp file index.php; (2) database "mo_phong_songthu" đã được tạo ' +
    "từ file sql/schema.sql; (3) thông tin kết nối trong db.php đã đúng.";
  analysisBox.classList.add("hidden");
  chatBox.classList.remove("hidden");
  requestAnimationFrame(() => chatBox.classList.add("show"));
}

function renderQuestion(data) {
  clockEl.textContent = data.thoi_gian;
  bubbleQuestion.textContent = data.noi_dung_hoi;

  choicesEl.innerHTML = "";
  data.lua_chon.forEach((choice) => {
    const btn = document.createElement("button");
    btn.className = "btn-choice";
    btn.textContent = choice;
    btn.addEventListener("click", () => onChoose(data.id_ai_hoi, choice));
    choicesEl.appendChild(btn);
  });

  analysisBox.classList.add("hidden");
  chatBox.classList.remove("hidden");
  requestAnimationFrame(() => chatBox.classList.add("show"));
}

// ---------------------------------------------------------
// 2) NGUOI DUNG CHON XONG -> GOI API LAY PHAN TICH
// ---------------------------------------------------------
async function onChoose(id_ai_hoi, lua_chon) {
  chatBox.classList.remove("show");
  setTimeout(() => chatBox.classList.add("hidden"), 400);

  const formData = new FormData();
  formData.append("action", "submit_choice");
  formData.append("id_ai_hoi", id_ai_hoi);
  formData.append("lua_chon", lua_chon);

  try {
    const res = await fetch("api.php", { method: "POST", body: formData });
    const data = await res.json();
    if (!data.found) return;

    setTimeout(() => renderAnalysis(data), 450);
  } catch (err) {
    console.error(err);
  }
}

// ---------------------------------------------------------
// 3) HIEN THI PHAN TICH THEO KIEU LO TRINH (route timeline)
// ---------------------------------------------------------
function renderAnalysis(data) {
  // >>> SUA O DAY: doi data.next_stt -> data.next_id (khop voi api.php da sua)
  pendingNextId = data.next_id;

  // Luu lai de co the quay ve xem lai tu video
  lastAnalysisData = data;
  btnResumeVideo.classList.add("hidden");

  const lines = data.mo_ta.split("\n").filter((l) => l.trim() !== "");

  routeLine.innerHTML =
    lines
      .map((line) => `<div class="route-step">${escapeHtml(line)}</div>`)
      .join("") +
    `<div class="route-step route-final">${escapeHtml(data.diem_cuoi)}</div>`;

  analysisBox.classList.remove("hidden", "fading-out");

  const steps = routeLine.querySelectorAll(".route-step");
  steps.forEach((step, i) => {
    setTimeout(() => step.classList.add("visible"), i * 650);
  });

  const totalRevealTime = steps.length * 650 + 1600;
  setTimeout(() => fadeToBlackThenPlay(data), totalRevealTime);
}

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

// ---------------------------------------------------------
// 4) MO DEN (fade to black) RIO CHUYEN SANG VIDEO
// ---------------------------------------------------------
function fadeToBlackThenPlay(data) {
  analysisBox.classList.add("fading-out");
  fadeOverlay.classList.add("active");

  setTimeout(() => {
    analysisBox.classList.add("hidden");
    playVideo(data);
  }, 950);
}

function playVideo(data) {
  if (!data.video) {
    // Khong co video -> bo qua, sang cau hoi tiep theo luon
    fadeOverlay.classList.remove("active");
    goToNextStage();
    return;
  }

  videoPlayer.src = data.video;
  videoSeek.value = 0;
  videoTime.textContent = "0:00 / 0:00";
  videoOverlay.classList.add("active");

  videoPlayer.play().catch(() => {
    // Neu trinh duyet chan autoplay, cho nguoi dung bam de phat
  });

  setTimeout(() => fadeOverlay.classList.remove("active"), 300);
}

// ---------------------------------------------------------
// 4b) CAC NUT DIEU KHIEN VIDEO: PLAY/PAUSE - TUA - QUAY LAI - BO QUA
// ---------------------------------------------------------
function formatTime(sec) {
  if (!isFinite(sec) || sec < 0) return "0:00";
  const m = Math.floor(sec / 60);
  const s = Math.floor(sec % 60)
    .toString()
    .padStart(2, "0");
  return `${m}:${s}`;
}

// Nut Play / Pause
btnPlayPause.addEventListener("click", () => {
  if (videoPlayer.paused) {
    videoPlayer.play().catch(() => {});
  } else {
    videoPlayer.pause();
  }
});

videoPlayer.addEventListener("play", () => {
  iconPlay.style.display = "none";
  iconPause.style.display = "inline";
});

videoPlayer.addEventListener("pause", () => {
  iconPlay.style.display = "inline";
  iconPause.style.display = "none";
});

// Cap nhat thanh tua + thoi gian khi video dang chay
videoPlayer.addEventListener("timeupdate", () => {
  if (!isSeeking && videoPlayer.duration) {
    videoSeek.value = (videoPlayer.currentTime / videoPlayer.duration) * 100;
  }
  videoTime.textContent = `${formatTime(videoPlayer.currentTime)} / ${formatTime(videoPlayer.duration)}`;
});

// Nut tua: keo de xem truoc, tha ra moi nhay toi vi tri do
videoSeek.addEventListener("input", () => {
  isSeeking = true;
  if (videoPlayer.duration) {
    videoTime.textContent = `${formatTime((videoSeek.value / 100) * videoPlayer.duration)} / ${formatTime(videoPlayer.duration)}`;
  }
});

videoSeek.addEventListener("change", () => {
  if (videoPlayer.duration) {
    videoPlayer.currentTime = (videoSeek.value / 100) * videoPlayer.duration;
  }
  isSeeking = false;
});

// Nut quay lai buoc phan tich truoc do
btnBackAnalysis.addEventListener("click", () => {
  videoPlayer.pause();
  videoOverlay.classList.remove("active");
  if (lastAnalysisData) {
    showAnalysisAgain();
  }
});

function showAnalysisAgain() {
  analysisBox.classList.remove("hidden", "fading-out");
  chatBox.classList.add("hidden");
  const steps = routeLine.querySelectorAll(".route-step");
  steps.forEach((step) => step.classList.add("visible"));
  btnResumeVideo.classList.remove("hidden");
}

// Nut "Tiep tuc xem video" (xuat hien sau khi bam Quay lai phan tich)
btnResumeVideo.addEventListener("click", () => {
  btnResumeVideo.classList.add("hidden");
  analysisBox.classList.add("hidden");
  videoOverlay.classList.add("active");
  videoPlayer.play().catch(() => {});
});

// Nut bo qua video -> chuyen thang sang cau hoi AI hoi tiep theo
btnSkipNext.addEventListener("click", () => {
  videoPlayer.pause();
  videoOverlay.classList.remove("active");
  videoPlayer.removeAttribute("src");
  videoPlayer.load();
  goToNextStage();
});

videoPlayer.addEventListener("ended", () => {
  videoOverlay.classList.remove("active");
  videoPlayer.removeAttribute("src");
  videoPlayer.load();
  goToNextStage();
});

// Neu file video loi / chua duoc them vao, van cho tiep tuc mo phong
videoPlayer.addEventListener("error", () => {
  if (videoOverlay.classList.contains("active")) {
    videoOverlay.classList.remove("active");
    goToNextStage();
  }
});

function goToNextStage() {
  // >>> SUA O DAY: doi pendingNextStt -> pendingNextId
  if (pendingNextId) {
    loadQuestion(pendingNextId);
  } else {
    showEndScreen();
  }
}

// ---------------------------------------------------------
// 5) MAN HINH KET THUC
// ---------------------------------------------------------
function showEndScreen() {
  mainStage.classList.add("hidden");
  endScreen.classList.remove("hidden");
  loadRoomReview();
}

// Goi API lay danh gia phong tro (gia phong, gia nuoc, tien coc, tien ich...)
// va hien thi len man hinh ket thuc
async function loadRoomReview() {
  try {
    const res = await fetch("api.php?action=get_danh_gia");
    const data = await res.json();

    if (!data.found) {
      roomReview.classList.add("hidden");
      return;
    }

    reviewName.textContent = data.ten_phong_tro || "Đánh giá phòng trọ";

    reviewGrid.innerHTML = `
      <div class="review-item">
        <div class="review-label">Giá phòng / tháng</div>
        <div class="review-value">${formatVND(data.gia_phong)}</div>
      </div>
      <div class="review-item">
        <div class="review-label">Giá điện / kWh</div>
        <div class="review-value">${formatVND(data.gia_dien)}</div>
      </div>
      <div class="review-item">
        <div class="review-label">Giá nước</div>
        <div class="review-value">${formatVND(data.gia_nuoc)}</div>
      </div>
      <div class="review-item">
        <div class="review-label">Tiền cọc</div>
        <div class="review-value">${formatVND(data.tien_coc)}</div>
      </div>
      <div class="review-item review-item-wide">
        <div class="review-label">Tiện ích khác</div>
        <div class="review-value review-value-small">${escapeHtml(data.tien_ich_khac || "Không có")}</div>
      </div>
    `;

    reviewComment.textContent = data.danh_gia_chung
      ? `"${data.danh_gia_chung}"`
      : "";
    roomReview.classList.remove("hidden");
  } catch (err) {
    console.error(err);
    roomReview.classList.add("hidden");
  }
}

function formatVND(n) {
  if (n === null || n === undefined) return "—";
  return Number(n).toLocaleString("vi-VN") + " đ";
}

btnRestart.addEventListener("click", () => {
  endScreen.classList.add("hidden");
  mainStage.classList.remove("hidden");
  // >>> SUA O DAY: doi pendingNextStt -> pendingNextId
  pendingNextId = null;
  lastAnalysisData = null;
  roomReview.classList.add("hidden");
  loadQuestion(1); // id_ai_hoi = 1 la cau hoi dau tien
});

// ---------------------------------------------------------
// KHOI DONG
// ---------------------------------------------------------
loadQuestion(1); // id_ai_hoi = 1 la cau hoi dau tien
