<style>
    /* Contenedor básico centrado */
    .ahat-hero-section {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        text-align: center;
    }

    /* Márgenes normales para el título y el texto */
    .ahat-hero-section h1 {
        margin-bottom: 15px;
    }

    .ahat-hero-section p {
        margin-bottom: 30px;
    }

    /* Botón estándar, gris claro, sin estridencias */
    .btn-principal {
        display: inline-block;
        padding: 8px 16px;
        background-color: #e9ecef;
        color: #000 !important;
        text-decoration: none;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .btn-principal:hover {
        background-color: #d6d8db;
    }
</style>

<div class="ahat-hero-section">
    <h1>Benvinguts a l'Arxiu</h1>
    <p>Explora el nostre fons documental digitalitzat. Navega pel quadre de classificació per descobrir les nostres col·leccions, carpetes i documents històrics.</p>
    
    <?php 
        $url_arxiu = caNavUrl($this->request, '', 'Arxiu', 'Quadre'); 
    ?>
    <a href="<?php print $url_arxiu; ?>" class="btn-principal">Accedir al Quadre de Classificació</a>
</div>