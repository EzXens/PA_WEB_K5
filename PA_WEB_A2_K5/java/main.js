function openModal() {
  var modal = document.getElementById('imagePopup');
  modal.style.display = 'block';
}

function closeModal() {
  var modal = document.getElementById('imagePopup');
  modal.style.display = 'none';
} 

function addData() {
  alert('Data added!'); 
  closeModal(); 
}


function changeBg() {
  const gambar = [
    'url("./assets/waifu-snow.png")',
    'url("./assets/ai_art.jpg")',
    'url("./assets/artfull.jpg")',
    'url("./assets/modern.jpg")',
    'url("./assets/realistic.jpg")',
    'url("./assets/visual_art.jpg")',
  ];

  const banners = document.getElementsByClassName('banner');

  for (let i = 0; i < banners.length; i++) {
    const bg = gambar[Math.floor(Math.random() * gambar.length)];

    // Hapus class 'bg-image' dari semua elemen 'banner'
    for (let j = 0; j < banners.length; j++) {
      banners[j].classList.remove('bg-image');
    }

    // Tambahkan class 'bg-image' ke elemen yang sedang diubah
    banners[i].classList.add('bg-image');

    // Atur background image dengan linear gradient dan URL gambar
    banners[i].style.backgroundImage = bg;
  }
}

// Panggil fungsi changeBg setelah halaman dimuat
window.addEventListener('load', changeBg);

// Panggil fungsi changeBg setiap 5 detik setelah itu
setInterval(changeBg, 5000);


// Fungsi untuk mengatur tombol "like"
function likeImage(event) {
  // Menghentikan penyebaran klik untuk menghindari memicu deskripsi gambar
  event.stopPropagation();
  // Tambahkan logika untuk mengatur tombol "like" di sini
  // Misalnya, Anda dapat menambahkan kode untuk mengganti warna tombol "like"
}
const navbar = document.querySelector
('.navbar','.toggle');

// ketika hamburger menu di klik
document.querySelector('#menu').
onclick = () => {
    navbar.classList.toggle('active');
};

// menghilangkan nav jika klik hamburger menu diluar side bar
const menu = document.querySelector('#menu');

document.addEventListener('click',function(e) {
    if(!menu.contains(e.target) && !navbar.contains(e.target)){
     navbar.classList.remove('active');
    }
});



const checkbox = document.getElementById("checkbox");

checkbox.addEventListener("change", () => {
    document.body.classList.toggle("dark");
});

function opendetail(gambarId) {
  var detail = document.getElementById('item_gambar_' + gambarId);
  detail.style.display = 'block';
}

function closedetail(gambarId) {
  var detail = document.getElementById('item_gambar_' + gambarId);
  detail.style.display = 'none';
}


