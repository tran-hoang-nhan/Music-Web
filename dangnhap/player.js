document.addEventListener('DOMContentLoaded', () => {
    const audioPlayer = document.getElementById('audio-player');
    const circle = document.getElementById('circle');
    const volumeSlider = document.getElementById('volume-slider');
    const volumeBtn = document.getElementById('volume-btn');
    //Ẩn hiện info 
    const infoDiv = document.querySelector(".info");
    const ordersDiv = document.querySelector(".orders");
    const toggleInfoBtn = document.querySelector(".toggle-info-btn");
    const toggleOrdersBtn = document.querySelector(".toggle-orders-btn");
    // Toggle hiển thị info và xoay icon
    toggleInfoBtn.addEventListener("click", () => {
        infoDiv.classList.toggle("hidden");
        const icon = toggleInfoBtn.querySelector("i"); // Tìm icon bên trong nút
        icon.style.transform = icon.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)'; 
    });

    // Toggle hiển thị orders và xoay icon
    toggleOrdersBtn.addEventListener("click", () => {
        ordersDiv.classList.toggle("hidden");
        const icon = toggleOrdersBtn.querySelector("i"); // Tìm icon bên trong nút
        icon.style.transform = icon.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)'; 
    });
    // Nếu trình phát đã có file nhạc thì tự động phát
    const savedVolume = localStorage.getItem('volume');
    if (savedVolume) {
        audioPlayer.volume = savedVolume;
        volumeSlider.value = savedVolume;
    }
    if (audioPlayer.src) {
        audioPlayer.play();
    }
    audioPlayer.addEventListener('play', () => {
        circle.classList.add('playing');  // Kích hoạt quay vòng
    });

    // Khi bài hát dừng
    audioPlayer.addEventListener('pause', () => {
        circle.classList.remove('playing');  // Dừng quay vòng
    });

    // Khi bài hát kết thúc
    audioPlayer.addEventListener('ended', () => {
        handleSongChange('next');
        circle.classList.remove('playing');  // Dừng quay vòng khi kết thúc bài hát
    });
    // Xử lý nút next và previous
    document.getElementById('next-song').addEventListener('click', () => handleSongChange('next'));
    document.getElementById('prev-song').addEventListener('click', () => handleSongChange('previous'));
    // Xử lý âm lượng
    volumeBtn.addEventListener('click', () => {
        audioPlayer.muted = !audioPlayer.muted; // Chuyển đổi trạng thái mute
        volumeBtn.innerHTML = audioPlayer.muted ? '<i class="fa fa-volume-mute"></i>' : '<i class="fa fa-volume-up"></i>';
    });
    volumeSlider.addEventListener('input', () => {
        audioPlayer.volume = volumeSlider.value;
        localStorage.setItem('volume', volumeSlider.value); // Lưu âm lượng vào localStorage
    });
});

// Gửi yêu cầu chuyển bài hát (next/previous) đến server
function handleSongChange(action) {
    fetch('save_songs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=${action}`,
    })
        .then(() => location.reload())
        .catch(console.error);
}
function searchSongs() {
    const input = document.getElementById('search-input');
    const filter = input.value.toLowerCase(); // Lấy giá trị tìm kiếm và chuyển về chữ thường
    const table = document.querySelector('.playlist table tbody');
    const rows = table.getElementsByTagName('tr'); // Lấy tất cả các hàng trong bảng
    
    for (let i = 0; i < rows.length; i++) {
        const columns = rows[i].getElementsByTagName('td'); // Lấy các cột trong mỗi hàng
        if (columns.length > 0) {
            const songName = columns[1].textContent.toLowerCase(); // Tên bài hát (cột thứ 2)
            const authorName = columns[2].textContent.toLowerCase(); // Tác giả (cột thứ 3)
            
            // Kiểm tra từ khóa trong tên bài hát hoặc tên tác giả
            if (songName.includes(filter) || authorName.includes(filter)) {
                rows[i].style.display = ''; // Hiển thị hàng nếu khớp
            } else {
                rows[i].style.display = 'none'; // Ẩn hàng nếu không khớp
            }
        }
    }
}
function updatePrice() {
    const price = document.querySelector("select[name='loai']").selectedOptions[0].dataset.price;
    const loai = document.querySelector("select[name='loai']").selectedOptions[0].textContent;  // Lấy giá trị hiển thị
    document.getElementById("gia").value = price;
    document.getElementById("loai_display").value = loai;
}
