<?php
session_start();

// Default di frame
$defaults = [
    'badan' => 1,
    'baju' => 1,
    'mulut' => 1,
    'mata' => 1,
    'kepala' => 1,
    'bgColor' => '#ffffff'
];

// buat part bagian
$parts_config = [
    'badan' => 1,
    'baju' => 6,
    'mulut' => 6,
    'mata' => 6,
    'kepala' => 6
];

// buat reset
if (isset($_GET['reset_session'])) {
    unset($_SESSION['avatar']);
    header("Location: index.php");
    exit;
}

// baca pilihan saat ini dari session atau default
$current = $_SESSION['avatar'] ?? $defaults;

if(!empty($_GET)) {
    foreach ($defaults as $key => $value) {
        if (isset($_GET[$key])) {
            $current[$key] = $_GET[$key];
        }
    }
    
    if (isset($_GET['bgColor'])) {
         $current['bgColor'] = $_GET['bgColor'];
    } else if (!isset($_GET['bgColor']) && isset($_SESSION['avatar']['bgColor'])) {
         $current['bgColor'] = $_SESSION['avatar']['bgColor'];
    }

    $_SESSION['avatar'] = $current;

    header("Location: index.php");
    exit;
}

$_SESSION['avatar'] = $current;

// mastiin pilihan
foreach ($parts_config as $category => $max) {
    $current[$category] = min(max(1, (int)($current[$category])), $max);
}

// tombol slider
function generate_slider_items($category, $max, $current_parts) {
    $output = '';
    
    $CategoryName = ucfirst($category);
    
    for ($i = 1; $i <= $max; $i++) {
        $query_parts = $current_parts;
        $query_parts[$category] = $i;
        
        $query_string = http_build_query($query_parts);
        $active_class = ($current_parts[$category] == $i) ? 'active' : '';
        
        $fileName = "{$CategoryName} {$i}.png";
        $filePath = "assets/{$category}/" . rawurlencode($fileName);

        $output .= "<a href='index.php?{$query_string}' class='slider-item {$active_class}'>";
        $output .= "<img src='{$filePath}' alt='{$CategoryName} {$i}'>";
        $output .= "</a>";
    }
    return $output;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charareceipt - Avatar Maker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="header-circles-container">
            <img src="assets/avatar/ava1.png" class="avatar-circle top-left">
            <img src="assets/avatar/ava2.png" class="avatar-circle bottom-left">
            <img src="assets/avatar/ava3.png" class="avatar-circle top-right">
            <img src="assets/avatar/ava4.png" class="avatar-circle bottom-right">
        </div>

        <div class="header-content">
            <h1>CHARARECEIPT</h1>
            <p>Temukan jati diri dan pilih karakter yang paling mencerminkan gayamu!</p>
        </div>
    </header>

    <main class="avatar-creator">
        
        <div class="avatar-preview-wrapper">
            
            <div id="avatar-frame">
                <div id="avatar-layers">
                    <div id="avatar-background"></div> 
                    
                    <img id="badan-part" class="avatar-layer" style="z-index: 10;" 
                        src="assets/badan/Badan 2.png" alt="Badan">
                    
                    <img id="baju-part" class="avatar-layer" style="z-index: 15;" 
                        src="assets/baju/Baju <?php echo $current['baju'];?>.png" alt="Baju">
                    
                    <img id="mata-part" class="avatar-layer" style="z-index: 25;" 
                        src="assets/mata/Mata <?php echo $current['mata'];?>.png" alt="Mata">
                    
                    <img id="mulut-part" class="avatar-layer" style="z-index: 30;" 
                        src="assets/mulut/Mulut <?php echo $current['mulut'];?>.png" alt="Mulut">
                    
                    <img id="kepala-part" class="avatar-layer" style="z-index: 35;" 
                        src="assets/kepala/Kepala <?php echo $current['kepala'];?>.png" alt="Kepala">
                        
                    </div>
            </div>
            
            <div class="color-picker-section">
                <span class="color-text">Anda dapat mengganti warna latar belakang:</span>
                
                <input type="color" id="bg-color-picker" value="<?php echo $current['bgColor'] ?? '#ffffff'; ?>">
            </div>
        </div>

        <div class="customization-controls">

            <div class="slider-group"><h3>Baju</h3><div class="slider-items-container">
                <div class="slider-items" id="baju-slider" data-category="baju">
                    <?php echo generate_slider_items('baju', $parts_config['baju'], $current); ?>
                </div>
            </div></div>

            <div class="slider-group"><h3>Mulut</h3><div class="slider-items-container">
                <div class="slider-items" id="mulut-slider" data-category="mulut">
                    <?php echo generate_slider_items('mulut', $parts_config['mulut'], $current); ?>
                </div>
            </div></div>
            
            <div class="slider-group"><h3>Mata</h3><div class="slider-items-container">
                <div class="slider-items" id="mata-slider" data-category="mata">
                    <?php echo generate_slider_items('mata', $parts_config['mata'], $current); ?>
                </div>
            </div></div>

            <div class="slider-group"><h3>Kepala</h3><div class="slider-items-container">
                <div class="slider-items" id="kepala-slider" data-category="kepala">
                    <?php echo generate_slider_items('kepala', $parts_config['kepala'], $current); ?>
                </div>
            </div></div>
            
            <button id="download-btn"; background-color: #f0f0f0;>
                Download avatar (.png)
            </button>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>Design by: Aida Rosyid | Code by: Ryuki Riella</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
