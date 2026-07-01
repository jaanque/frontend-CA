<?php
    // 1. Definimos la ruta física y preparamos variables
    $assets_dir = __CA_BASE_DIR__ . '/themes/default/assets/img';
    $random_image_url = '';

    if (is_dir($assets_dir)) {
        // Obtenemos archivos descartando los directorios raíz
        $files = array_diff(scandir($assets_dir), ['.', '..']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $images = [];
        
        // Filtramos para asegurar que solo cargamos imágenes
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed_extensions)) {
                $images[] = $file;
            }
        }
        
        // 2. Si hay imágenes, elegimos una al azar y construimos la URL
        if (!empty($images)) {
            $random_filename = $images[array_rand($images)];
            $random_image_url = $this->request->getBaseUrlPath() . '/themes/default/assets/img/' . $random_filename;
        }
    }
?>

<style>
    /* Contenedor principal: Uso de transform para evitar scroll horizontal indeseado */
    .ahat-hero-section {
        width: 100vw;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 120px 20px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Capa con fondo blanco sólido */
    .ahat-hero-content {
        background-color: #ffffff;
        max-width: 700px;
        width: 100%;
        padding: 60px 40px;
        border-radius: 8px; /* Curvatura ligeramente más suave y moderna */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); /* Sombra más elegante y dispersa */
        text-align: center;
    }

    /* Textos */
    .ahat-hero-content h1 {
        margin: 0 0 15px 0;
        color: #000000;
        font-size: 2.2rem;
        line-height: 1.2;
    }

    .ahat-hero-content p {
        margin: 0 0 35px 0;
        color: #333333;
        font-size: 1.15rem;
        line-height: 1.6;
    }

    /* Botón */
    .btn-principal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 28px;
        background-color: #e9ecef;
        color: #000000 !important;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #cccccc;
        border-radius: 4px;
        transition: all 0.3s ease; /* Transición más fluida */
    }

    .btn-principal:hover {
        background-color: #d6d8db;
        border-color: #babbbe;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px); /* Ligero efecto de elevación al pasar el ratón */
    }
    
    /* Responsivo para móviles */
    @media (max-width: 768px) {
        .ahat-hero-content {
            padding: 40px 25px;
        }
        .ahat-hero-content h1 {
            font-size: 1.8rem;
        }
        .ahat-hero-content p {
            font-size: 1rem;
        }
    }
</style>

<section class="ahat-hero-section" style="<?php if ($random_image_url) echo "background-image: url('{$random_image_url}');"; ?>">
    
    <div class="ahat-hero-content">
        <h1>Benvinguts a l'Arxiu</h1>
        <p>Explora el nostre fons documental digitalitzat. Navega pel quadre de classificació per descobrir les nostres col·leccions, carpetes i documents històrics.</p>
        
        <?php 
            $url_arxiu = caNavUrl($this->request, '', 'Arxiu', 'Quadre'); 
        ?>
        <a href="<?php echo htmlspecialchars($url_arxiu, ENT_QUOTES, 'UTF-8'); ?>" class="btn-principal">
            Accedir al Quadre de Classificació
        </a>
    </div>

</section>