// Ganti layer avatar
document.querySelectorAll(".slider-items").forEach(group => {
    const target = group.getAttribute("data-target");
    const layer = document.getElementById("layer-" + target);

    group.querySelectorAll(".slider-item").forEach((item, index) => {
        item.addEventListener("click", () => {

            // Hapus active
            group.querySelectorAll(".slider-item").forEach(i => i.classList.remove("active"));
            item.classList.add("active");

            // Perbarui gambar
            const imgSrc = item.querySelector("img").src;
            layer.src = imgSrc;
        });
    });
});

// Ubah background avatar
document.getElementById("bgColorPicker").addEventListener("input", e => {
    document.getElementById("avatar-background").style.backgroundColor = e.target.value;
});

// Download PNG avatar
document.getElementById("download-btn").addEventListener("click", () => {
    html2canvas(document.getElementById("avatar-frame")).then(canvas => {
        const link = document.createElement("a");
        link.download = "my_avatar.png";
        link.href = canvas.toDataURL();
        link.click();
    });
});

// Load html2canvas
const script = document.createElement("script");
script.src = "https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js";
document.body.appendChild(script);
