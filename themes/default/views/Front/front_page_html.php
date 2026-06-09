<?php
    // 1. Definimos la ruta física apuntando directamente a tu tema 'default'
    $assets_dir = __CA_BASE_DIR__ . '/themes/default/assets/img';
    $random_image_url = '';

    if (is_dir($assets_dir)) {
        $files = array_diff(scandir($assets_dir), array('.', '..'));
        $images = array();
        
        // Filtramos para asegurarnos de que solo coge imágenes
        foreach($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if(in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'))) {
                $images[] = $file;
            }
        }
        
        // 2. Si hay imágenes, elegimos una al azar y construimos su URL
        if(!empty($images)) {
            $random_filename = $images[array_rand($images)];
            $random_image_url = $this->request->getBaseUrlPath() . '/themes/default/assets/img/' . $random_filename;
        }
    }
?>

<style>
    /* Contenedor principal rompiendo los márgenes para ocupar el 100% del ancho */
    .ahat-hero-section {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background-size: cover;
        background-position: center;
        text-align: center;
        padding: 120px 20px; /* Controla la altura total de la sección (espacio arriba y abajo del recuadro blanco) */
        box-sizing: border-box;
    }

    /* Capa con fondo blanco sólido (sin transparencia) */
    .ahat-hero-content {
        background-color: #ffffff; /* Blanco opaco al 100% */
        max-width: 700px;
        margin: 0 auto;
        padding: 60px 40px;
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); /* Sutil sombra para separarlo del fondo */
    }

    /* Textos */
    .ahat-hero-content h1 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #000;
    }

    .ahat-hero-content p {
        margin-bottom: 30px;
        color: #333;
        font-size: 1.1em;
    }

    /* Botón */
    .btn-principal {
        display: inline-block;
        padding: 10px 20px;
        background-color: #e9ecef;
        color: #000 !important;
        text-decoration: none;
        border: 1px solid #ccc;
        border-radius: 3px;
        transition: background-color 0.2s;
    }

    .btn-principal:hover {
        background-color: #d6d8db;
    }
</style>

<div class="ahat-hero-section" style="<?php if ($random_image_url) print "background-image: url('{$random_image_url}');"; ?>">
    
    <div class="ahat-hero-content">
        <h1>Benvinguts a l'Arxiu</h1>
        <p>Explora el nostre fons documental digitalitzat. Navega pel quadre de classificació per descobrir les nostres col·leccions, carpetes i documents històrics.</p>
        
        <?php 
            $url_arxiu = caNavUrl($this->request, '', 'Arxiu', 'Quadre'); 
        ?>
        <a href="<?php print $url_arxiu; ?>" class="btn-principal">Accedir al Quadre de Classificació</a>
    </div>

</div>