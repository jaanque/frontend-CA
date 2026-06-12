<?php
/* ----------------------------------------------------------------------
 * themes/default/views/bundles/ca_objects_default_html.php
 * ----------------------------------------------------------------------
 */
 
    $t_object =             $this->getVar("item");
    $va_comments =          $this->getVar("comments");
    $va_tags =              $this->getVar("tags_array");
    $vn_comments_enabled =  $this->getVar("commentsEnabled");
    $vn_share_enabled =     $this->getVar("shareEnabled");
    $vn_pdf_enabled =       $this->getVar("pdfEnabled");
    $vn_id =                $t_object->get('ca_objects.object_id');

    // --- EXTRACCIÓN FORZADA DE METADATOS VÍA PHP ---
    $vs_idno        = $t_object->get('ca_objects.idno');
    
    // Caja / Serie (Múltiples opciones por si acaso)
    $vs_container   = $t_object->get('ca_objects.containerID');
    if(!$vs_container) { $vs_container = $t_object->get('ca_objects.caja'); }
    if(!$vs_container) { $vs_container = $t_object->get('ca_objects.serie'); }
    
    // Fecha (Cazamos el campo 'dates' exacto de tu captura de pantalla)
    $vs_date        = $t_object->get('ca_objects.dates');
    if(!$vs_date) { $vs_date = $t_object->get('ca_objects.date'); }
    if(!$vs_date) { $vs_date = $t_object->get('ca_objects.dateSet'); }
    
    // Descripción
    $vs_description = $t_object->get('ca_objects.description');
    if(!$vs_description) { $vs_description = $t_object->get('ca_objects.caption'); }
    if(!$vs_description) { $vs_description = $t_object->get('ca_objects.text'); }
?>

<style>
    /* --- CONTENEDOR PRINCIPAL --- */
    .ahat-detail-main {
        padding-top: 10px;
        padding-bottom: 60px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        background-color: #f8f9fa; /* Gris casi blanco, muy neutro */
    }

    /* --- NAVEGACIÓN Y SIDEBARS --- */
    .navTop {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 40px; 
    }

    /* 1. Botón superior (Estilo Píldora Minimalista) */
    .ahat-back-top-btn {
        background-color: #ffffff;
        color: #111111;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        border-radius: 40px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .ahat-back-top-btn:hover { 
        background-color: #f1f3f5;
        color: #0284c7; 
        border-color: #ccc;
        text-decoration: none; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .ahat-back-top-btn .glyphicon { font-size: 14px; }

    /* 2. Botones laterales */
    .detailNavBgLeft {
        padding-top: 0 !important; 
        padding-left: 30px !important; 
        display: flex;
        justify-content: flex-start; 
        align-items: flex-start;
    }

    .detailNavBgRight {
        padding-top: 0 !important; 
        padding-right: 30px !important; 
        display: flex;
        justify-content: flex-end; 
        align-items: flex-start;
    }

    .ahat-sidebar-nav {
        display: flex;
        flex-direction: column; 
        align-items: center;
        justify-content: center;
        background-color: #ffffff !important;
        text-decoration: none;
        
        min-width: 75px !important;
        min-height: 75px !important;
        box-sizing: border-box !important;
        border-radius: 16px !important; 
        
        border: 1px solid #e0e0e0; 
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); 
        transition: all 0.3s ease; 
        
        padding: 10px 5px;
        margin-top: 0px; 
    }

    .ahat-sidebar-nav .glyphicon { 
        font-size: 20px; 
        margin: 0 0 5px 0 !important; 
        color: #555555 !important; 
        display: block;
        transition: color 0.3s ease;
    }
    
    .ahat-sidebar-nav .ahat-nav-txt { 
        display: block !important; 
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #555555;
        letter-spacing: 0.5px;
        text-align: center;
        transition: color 0.3s ease;
    }

    .ahat-sidebar-nav:hover { 
        background-color: #f1f3f5 !important; 
        border-color: #ccc; 
        text-decoration: none; 
        transform: translateY(-3px); 
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12); 
    }
    
    .ahat-sidebar-nav:hover .glyphicon,
    .ahat-sidebar-nav:hover .ahat-nav-txt {
        color: #0284c7 !important; 
    }

    /* --- COLUMNA MULTIMEDIA (IZQUIERDA) --- */
    .ahat-media-card {
        background: #ffffff;
        border: none;
        border-radius: 20px; 
        padding: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 25px;
    }
    
    /* --- HERRAMIENTAS (COMPARTIR/PDF) --- */
    .ahat-tools-panel {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        padding: 12px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 25px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }
    .ahat-tool-item a, .ahat-tool-item span.glyphicon + a { 
        color: #111111 !important; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px;
        padding: 10px 16px;
        border-radius: 30px; 
        font-size: 13px;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    .ahat-tool-item a:hover { 
        background-color: #f1f3f5; 
    }

    /* --- CABECERA DE TEXTOS (DERECHA) --- */
    .ahat-header-info {
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
    }
    .ahat-title-main {
        font-size: 34px;
        font-weight: 800;
        color: #111111;
        letter-spacing: -0.5px;
        margin: 0 0 12px 0;
        line-height: 1.2;
    }
    .ahat-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        text-transform: uppercase;
        color: #111111;
        background: #f1f3f5; 
        padding: 6px 14px;
        border-radius: 30px;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    /* --- TITULOS DE SECCIÓN --- */
    .ahat-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ahat-section-title span.glyphicon { color: #111111; font-size: 18px; }

    /* --- FICHA TÉCNICA REFINADA --- */
    .ahat-info-grid {
        display: flex;
        flex-direction: column;
        gap: 0;
        background: #ffffff;
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        margin-bottom: 40px;
    }
    .ahat-grid-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        align-items: start;
        padding: 20px;
        border-bottom: 1px solid #f1f3f5;
    }
    .ahat-grid-row:last-child { border-bottom: none; }
    
    .ahat-lbl {
        font-size: 13px;
        color: #767676; 
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ahat-lbl .glyphicon { font-size: 12px; color: #111111; }
    .ahat-val {
        font-size: 15px;
        color: #111111;
        line-height: 1.6;
    }

    /* --- BLOQUES DE RELACIONES EXTERNAS --- */
    .ahat-relations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
        margin-top: 15px;
    }
    .ahat-rel-card {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        transition: background-color 0.2s ease;
    }
    .ahat-rel-card:hover { 
        background-color: #f8f9fa; 
    }
    .ahat-rel-card label {
        font-size: 12px;
        text-transform: uppercase;
        color: #767676;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f3f5;
    }
    .ahat-rel-card label .glyphicon { color: #111111; }
    .ahat-rel-card .unit { font-size: 15px; color: #111111; line-height: 1.6; }
    .ahat-rel-card .unit l a { color: #111111; text-decoration: underline; font-weight: 600; display: block; }
    .ahat-rel-card .unit l a:hover { color: #767676; }

    /* Botones Readmore generados por JS */
    .rm-btn-read {
        display: inline-flex;
        align-items: center;
        background: #f1f3f5;
        color: #111111 !important;
        padding: 8px 16px;
        border-radius: 30px;
        margin-top: 12px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .rm-btn-read:hover { background: #e9e9e9; text-decoration: none !important; }
</style>

<div class="row ahat-detail-main">
    
    <div class='col-xs-12 navTop'>
        <a href="<?php print caNavUrl($this->request, '', 'Arxiu', 'Quadre'); ?>" class="ahat-back-top-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Tornar a l'Arxiu
        </a>
        <div>{{{nextLink}}}</div>
    </div>
    
    <div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
        <div class="detailNavBgLeft" style="padding: 0; background: transparent; border: none;">
            <a href="<?php print caNavUrl($this->request, '', 'Arxiu', 'Quadre'); ?>" class="ahat-sidebar-nav" title="Tornar a l'Arxiu">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="ahat-nav-txt">Tornar</span>
            </a>
        </div>
    </div>
    
    <div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>
        <div class="container-fluid" style="padding: 0;">
            <div class="row">
                
                <div class='col-sm-6 col-md-6 col-lg-5 col-lg-offset-1'>
                    
                    <div class="ahat-media-card">
                        {{{representationViewer}}}
                    </div>
                    
                    <div id="detailAnnotations"></div>
                    
                    <div class="row" style="margin-top:15px;">
                        <?= caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_object, array("returnAs" => "bsCols", "linkTo" => "basic", "bsColClasses" => "smallpadding col-sm-3 col-md-3 col-xs-4", "primaryOnly" => $this->getVar('representationViewerPrimaryOnly') ? 1 : 0)); ?>
                    </div>
                    
                    <?php if ($vn_comments_enabled || $vn_share_enabled || $vn_pdf_enabled) { ?>              
                        <div class="ahat-tools-panel">
                            <?php if ($vn_comments_enabled) { ?>
                                <div class="ahat-tool-item">
                                    <a href='#' onclick='jQuery("#ahatCommentsWrapper").slideToggle(); return false;'>
                                        <span class="glyphicon glyphicon-comment"></span> Comentaris (<?php print sizeof($va_comments) + sizeof($va_tags); ?>)
                                    </a>
                                </div>
                            <?php } ?>
                            
                            <?php if ($vn_share_enabled) { ?>
                                <div class="ahat-tool-item">
                                    <span class="glyphicon glyphicon-share-alt"></span> <?php print $this->getVar("shareLink"); ?>
                                </div>
                            <?php } ?>
                            
                            <?php if ($vn_pdf_enabled) { ?>
                                <div class="ahat-tool-item">
                                    <?= caDetailLink($this->request, "<span class='glyphicon glyphicon-save-file'></span> Descarregar PDF", "faDownload", "ca_objects", $vn_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_objects_summary')); ?>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <?php if ($vn_comments_enabled) { ?>
                            <div id='ahatCommentsWrapper' style="display:none; background:#fff; border:none; border-radius:16px; padding:20px; margin-top:15px; box-shadow: 0 4px 14px rgba(0,0,0,0.05);">
                                <?php print $this->getVar("itemComments");?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                </div>
                
                <div class='col-sm-6 col-md-6 col-lg-5'>
                    
                    <div class="ahat-header-info">
                        <span class="ahat-type-badge">
                            <span class="glyphicon glyphicon-tag"></span> {{{<unit>^ca_objects.type_id</unit>}}}
                        </span>
                        <h1 class="ahat-title-main" style="margin-top: 15px;">
                            {{{<unit relativeTo="ca_collections" delimiter="<br/>"><l>^ca_collections.preferred_labels.name</l></unit><ifcount min="1" code="ca_collections"> <span style='color:#767676;'>/</span> </ifcount>}}}{{{ca_objects.preferred_labels.name}}}
                        </h1>
                    </div>
                    
                    <h3 class="ahat-section-title"><span class="glyphicon glyphicon-list-alt"></span> Fitxa Tècnica</h3>
                    <div class="ahat-info-grid">
                        
                        <div class="ahat-grid-row">
                            <div class="ahat-lbl">Código</div>
                            <div class="ahat-val" style="font-family: monospace; font-size: 16px; font-weight:600;">
                                <?= $vs_idno ? $vs_idno : "—"; ?>
                            </div>
                        </div>
                        
                        <div class="ahat-grid-row">
                            <div class="ahat-lbl"><span class="glyphicon glyphicon-folder-open"></span> Caja / Serie</div>
                            <div class="ahat-val">
                                <?= $vs_container ? $vs_container : "—"; ?>
                            </div>
                        </div>            
                        
                        <div class="ahat-grid-row">
                            <div class="ahat-lbl"><span class="glyphicon glyphicon-calendar"></span> Fecha</div>
                            <div class="ahat-val">
                                <?= $vs_date ? $vs_date : "—"; ?>
                            </div>
                        </div>

                        <div class="ahat-grid-row" style="grid-template-columns: 1fr; gap:8px;">
                            <div class="ahat-lbl"><span class="glyphicon glyphicon-align-left"></span> Descripción</div>
                            <div class="ahat-val trimText">
                                <?= $vs_description ? $vs_description : "No hay descripción disponible."; ?>
                            </div>
                        </div>
                        
                    </div>
                    
                    {{{<ifcount code="ca_entities" min="1"><h3 class="ahat-section-title" style="margin-top:35px;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}
                    {{{<ifcount code="ca_occurrences" min="1"><h3 class="ahat-section-title" style="margin-top:35px; display:none;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}
                    {{{<ifcount code="ca_places" min="1"><h3 class="ahat-section-title" style="margin-top:35px; display:none;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}
                    {{{<ifcount code="ca_list_items" min="1"><h3 class="ahat-section-title" style="margin-top:35px; display:none;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}

                    <div class="row">
                        <div class="col-sm-12 ahat-relations-grid">      
                            
                            {{{<ifcount code="ca_entities" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-user"></span> 
                                        <ifcount code="ca_entities" min="1" max="1">Persona Relacionada</ifcount>
                                        <ifcount code="ca_entities" min="2">Personas Relacionadas</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_entities" delimiter="<br/>"><l>^ca_entities.preferred_labels</l> <span style="font-size:12px; color:#767676;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_occurrences" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-time"></span>
                                        <ifcount code="ca_occurrences" min="1" max="1">Esdeveniment</ifcount>
                                        <ifcount code="ca_occurrences" min="2">Esdeveniments</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_occurrences" delimiter="<br/>"><l>^ca_occurrences.preferred_labels</l> <span style="font-size:12px; color:#767676;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_places" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-map-marker"></span>
                                        <ifcount code="ca_places" min="1" max="1">Lloc Relacionat</ifcount>
                                        <ifcount code="ca_places" min="2">Llocs Relacionats</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_places" delimiter="<br/>"><l>^ca_places.preferred_labels</l> <span style="font-size:12px; color:#767676;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_list_items" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-tags"></span>
                                        <ifcount code="ca_list_items" min="1" max="1">Terme Relacionat</ifcount>
                                        <ifcount code="ca_list_items" min="2">Termes Relacionats</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_list_items" delimiter="<br/>"><l>^ca_list_items.preferred_labels.name_plural</l> <span style="font-size:12px; color:#767676;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                        </div>
                        
                        <div class="col-sm-12" style="margin-top: 30px;">
                            {{{map}}}
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
    
    <div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
        <div class="detailNavBgRight" style="background: transparent; border: none;">
            {{{nextLink}}}
        </div>
    </div>
</div>

<script type='text/javascript'>
    jQuery(document).ready(function() {
        $('.trimText').readmore({
          speed: 150,
          maxHeight: 140,
          moreLink: '<a href="#" class="rm-btn-read">Llegir més</a>',
          lessLink: '<a href="#" class="rm-btn-read">Amagar</a>'
        });
    });
</script>