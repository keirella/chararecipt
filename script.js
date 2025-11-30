// pembaruan bagian avatar (untuk mengatasi reload/scroll-up)
function updateSession(part, value) {
    fetch('update_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ part: part, value: value })
    })
    .then(response => response.json())
    .catch(error => console.error('Error updating session:', error));
}

// pembaruan warna latar belakang (untuk mengatasi reload/scroll-up)
function updateBgColorSession(color) {
    fetch('update_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ bgColor: color })
    })
    .then(response => response.json())
    .catch(error => console.error('Error updating background color session:', error));
}


document.addEventListener("DOMContentLoaded", () => {
    const colorPicker = document.getElementById('bg-color-picker');
    const avatarBackground = document.getElementById('avatar-background');

    if (colorPicker && avatarBackground) {
        
        // atur warna awal dari PHP
        avatarBackground.style.backgroundColor = colorPicker.value;

        colorPicker.addEventListener('input', (event) => {
            const newColor = event.target.value;
            avatarBackground.style.backgroundColor = newColor;
            updateBgColorSession(newColor); 
        });
    }

    // klik slider
    document.querySelectorAll('.slider-item').forEach(item => {
        item.addEventListener('click', function(event) {
            
            const category = this.getAttribute('data-category');
            const value = this.getAttribute('data-value');
            
            const layerImg = document.getElementById(category + "-part");
            const categoryNameCapitalized = category.charAt(0).toUpperCase() + category.slice(1);
            
            layerImg.src = `assets/${category}/${categoryNameCapitalized} ${value}.png`;

            const container = this.closest('.slider-items');
            container.querySelector('.slider-item.active')?.classList.remove('active');
            this.classList.add('active');

            updateSession(category, value);
        });
    });

});

// download PNG avatar 
document.getElementById("download-btn").addEventListener("click", () => {
    const avatarFrame = document.getElementById("avatar-frame");
    
    const avatarWidth = avatarFrame.offsetWidth;
    const avatarHeight = avatarFrame.offsetHeight;
    
    // render Avatar yang sudah dibuat ke kanvas
    html2canvas(avatarFrame, {
        width: avatarWidth,
        height: avatarHeight,
        scale: 2
    }).then(avatarCanvas => {
        
        // load gambar FRAME/STRUK
        const frameImage = new Image();
        frameImage.src = "assets/Frame.png"; 
        
        frameImage.onload = () => {
            
            const finalWidth = 580; 
            const finalHeight = 1300; 
            
            const finalCanvas = document.createElement('canvas');
            finalCanvas.width = finalWidth;
            finalCanvas.height = finalHeight;
            const ctx = finalCanvas.getContext('2d');
            
            const avatarTargetWidth = 452; 
            const avatarTargetHeight = 592; 
            
            const frameX = (finalWidth - avatarTargetWidth) / 2; 
            const frameY = 290; 
            
            ctx.drawImage(avatarCanvas, frameX, frameY, avatarTargetWidth, avatarTargetHeight);
            
            ctx.drawImage(frameImage, 0, 0, finalWidth, finalHeight);
            
            // download Kanvas Akhir
            const link = document.createElement("a");
            link.download = "charareceipt.png"; 
            link.href = finalCanvas.toDataURL("image/png"); 
            link.click();

             setTimeout(() => {
                 window.location.href = "index.php";
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
