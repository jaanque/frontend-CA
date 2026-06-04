<?php
/** ---------------------------------------------------------------------
 * themes/default/Front/front_page_html : Pàgina principal del lloc
 * ----------------------------------------------------------------------
 */
        // 1. Carrega el carrusel d'imatges superior
        print $this->render("Front/featured_set_slideshow_html.php");
?>

    <div class="row" style="margin-top: 30px;">
        <div class="col-sm-12">
            <h2 style="border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;">Arxiu Històric</h2>

            <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; border: 1px solid #e1e1e1; margin-bottom: 40px; text-align: center;">
                <h3 style="margin-top: 0;">Explora el Quadre de Classificació</h3>
                <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
                    Navegueu per l'estructura jeràrquica de fons, seccions i sèries per localitzar documents específics.
                </p>
                <a href="<?php print __CA_URL_ROOT__; ?>/index.php/Browse/objects/facet/cuadro_clasificacion_facet"
                   style="display: inline-block; background-color: #0056b3; color: #fff; padding: 12px 24px; font-size: 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                   📁 Obrir l'Explorador de Jerarquies
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h2 style="border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;">Galeries Digitals</h2>

            <div class="arxiu-digital-grid">
<?php
                // Carrega les galeries (ara s'adaptaran automàticament a la quadrícula)
                print $this->render("Front/gallery_set_links_html.php");
?>
            </div>
        </div>
    </div>

    <style>
        .arxiu-digital-grid {
            display: grid;
            /* Crea columnes automàtiques que no baixin de 200px d'amplada */
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
            align-items: stretch;
            margin-bottom: 50px;
        }

        /* Forcem els elements de Pawtucket a comportar-se com a targetes */
        .arxiu-digital-grid > div {
            width: 100% !important;
            float: none !important;
            padding: 15px;
            background: #fff;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Efecte en passar el cursor per sobre de les galeries */
        .arxiu-digital-grid > div:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        /* Ajust de les miniatures */
        .arxiu-digital-grid img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>