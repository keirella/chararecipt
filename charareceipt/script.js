// script.js

// untuk memperbarui semua link slider 
function updateSliderLinks() {
    const colorPicker = document.getElementById('bg-color-picker');
    if (!colorPicker) return;

    const bgColor = colorPicker.value.substring(1); 
    const sliderLinks = document.querySelectorAll('.slider-item');
    
    sliderLinks.forEach(link => {
        let href = link.getAttribute('href');
        
        href = href.replace(/&?bgColor=[^&]*/, '');
        
        if (href.includes('?')) {
            link.href = href + `&bgColor=%23${bgColor}`; 
        } else {
            link.href = href + `?bgColor=%23${bgColor}`;
        }
    });
}


document.querySelectorAll(".slider-items").forEach(group => {
    const category = group.getAttribute("data-category"); 
    const layer = document.getElementById(category + "-part"); 
});

// ubah background avatar dan update link
document.addEventListener("DOMContentLoaded", () => {
    const colorPicker = document.getElementById('bg-color-picker');
    const avatarBackground = document.getElementById('avatar-background');
    
    updateSliderLinks(); 

    if (colorPicker && avatarBackground) {
        
        colorPicker.addEventListener('input', () => {
            avatarBackground.style.backgroundColor = colorPicker.value;
            updateSliderLinks(); 
        });
        
        avatarBackground.style.backgroundColor = colorPicker.value;
    }
});


// download PNG avatar 
document.getElementById("download-btn").addEventListener("click", () => {
    const avatarFrame = document.getElementById("avatar-frame");
    
    const avatarWidth = avatarFrame.offsetWidth;
    const avatarHeight = avatarFrame.offsetHeight;
    
    // 1. Render Avatar yang sudah dibuat ke kanvas
    html2canvas(avatarFrame, {
        width: avatarWidth,
        height: avatarHeight,
        scale: 2
    }).then(avatarCanvas => {
        
        // 2. Load gambar FRAME/STRUK
        const frameImage = new Image();
        frameImage.src = "assets/Frame.png"; 
        
        frameImage.onload = () => {
            
            // Dimensi Penuh dari Frame.png
            const finalWidth = frameImage.width;
            const finalHeight = frameImage.height;
            
            const finalCanvas = document.createElement('canvas');
            finalCanvas.width = finalWidth;
            finalCanvas.height = finalHeight;
            const ctx = finalCanvas.getContext('2d');
            
            // 3. Gambar FRAME/STRUK (Frame.png)
            ctx.drawImage(frameImage, 0, 0, finalWidth, finalHeight);
            
            // 4. Hitung posisi dan ukuran AVATAR
            const frameX = finalWidth * 0.235; 
            const frameY = finalHeight * 0.265; 
            const frameSize = finalWidth * 0.53; 

            // 5. Gambar AVATAR di atas Frame
            ctx.drawImage(avatarCanvas, frameX, frameY, frameSize, frameSize);
            
            // 6. Download Kanvas Akhir
            const link = document.createElement("a");
            link.download = "chararecipt_result.png";
            link.href = finalCanvas.toDataURL("image/png");
            link.click();
            
            // 7. Reset sesi setelah download
            setTimeout(() => {
                window.location.href = "index.php?reset_session=1";
            }, 100); 
        };
        
        frameImage.onerror = () => {
            alert("Gagal memuat file frame/struk. Pastikan 'Frame.png' ada di direktori yang sama.");
        };
    });
});

// load html2canvas
const script = document.createElement("script");
script.src = "https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js";
document.body.appendChild(script);
