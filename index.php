<?php

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

// baca pilihan saat ini dari URL 
$current = [
    'badan'   => $defaults['badan'],
    'baju'    => $_GET['baju'] ?? $defaults['baju'],
    'mulut'   => $_GET['mulut'] ?? $defaults['mulut'],
    'mata'    => $_GET['mata'] ?? $defaults['mata'],
    'kepala'  => $_GET['kepala'] ?? $defaults['kepala'],
    'bgColor' => $_GET['bgColor'] ?? $defaults['bgColor']
];

// mastiin pilihan
foreach ($parts_config as $category => $max) {
    $current[$category] = min(max(1, (int)($current[$category])), $max);
}
$current['bgColor'] = htmlspecialchars($current['bgColor']);


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
    <title>Chararecipt - Avatar Maker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="header-circles-container">
            <div class="avatar-circle top-left" style="background-color: #f7e0e7;"></div>
            <div class="avatar-circle bottom-left" style="background-color: #faebd7;"></div>
            <div class="avatar-circle top-right" style="background-color: #cce0f5;"></div>
            <div class="avatar-circle bottom-right" style="background-color: #fce8d5;"></div>
        </div>

        <div class="header-content">
            <h1>CHARARECIPT</h1>
            <p>Temukan jati diri dan pilih karakter yang paling mencerminkan gayamu!</p>
        </div>
    </header>

    <main class="avatar-creator">
        
        <div class="avatar-preview-wrapper">
            
            <div id="avatar-frame">
                <div id="avatar-layers">
                    <div id="avatar-background" style="background-color: <?php echo $current['bgColor']; ?>;"></div> 
                    
                    <img id="badan-part" class="avatar-layer" style="z-index: 10;" 
                        src="assets/badan/Badan.png" alt="Badan">
                    
                    <img id="baju-part" class="avatar-layer" style="z-index: 15;" 
                        src="assets/baju/Baju <?php echo $current['baju'];?>.png" alt="Baju">
                    
                    <img id="mata-part" class="avatar-layer" style="z-index: 25;" 
                        src="assets/mata/Mata <?php echo $current['mata'];?>.png" alt="Mata">
                    
                    <img id="mulut-part" class="avatar-layer" style="z-index: 30;" 
                        src="assets/mulut/Mulut <?php echo $current['mulut'];?>.png" alt="Mulut">
                    
                    <img id="kepala-part" class="avatar-layer" style="z-index: 35;" 
                        src="assets/kepala/Kepala <?php echo $current['kepala'];?>.png" alt="Kepala">
                        
                    <img id="display-frame" class="avatar-layer" style="z-index: 99;"  
                         onerror="this.style.display='none'">
                </div>
            </div>
            
            <div class="color-picker-section">
                <span class="color-text">Anda dapat mengganti warna latar belakang:</span>
                <form method="GET" action="index.php" id="color-form">
                    <?php 
                    foreach ($current as $key => $value) {
                        if ($key !== 'bgColor') {
                            echo "<input type='hidden' name='{$key}' value='{$value}'>";
                        }
                    }
                    ?>
                    <input type="color" name="bgColor" id="bg-color-picker" 
                           value="<?php echo $current['bgColor']; ?>" 
                           onchange="document.getElementById('color-form').submit();">
                </form>
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
            
            <button id="download-btn"; background-color: #f0f0f0;">
                Download avatar (.png)
            </button>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>Design by: **Aida Rosyid** | Code by: **Key Riella**</p>
        </div>
    </footer>

</body>
</html>