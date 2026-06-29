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
    
    // Caja / Serie
    $vs_container   = $t_object->get('ca_objects.containerID');
    if(!$vs_container) { $vs_container = $t_object->get('ca_objects.caja'); }
    if(!$vs_container) { $vs_container = $t_object->get('ca_objects.serie'); }
    
    // Fecha
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
        padding-top: 15px;
        padding-bottom: 40px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        background-color: #ffffff;
        color: #333;
    }

    /* --- NAVEGACIÓN Y SIDEBARS --- */
    .navTop {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px 20px 20px;
        border-bottom: 1px solid #eaeaea;
        margin-bottom: 20px;
    }

    /* Botón superior minimalista */
    .ahat-back-top-btn {
        background-color: #f8f9fa;
        color: #444;
        font-weight: 500;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #dcdcdc;
        transition: all 0.2s ease;
    }

    .ahat-back-top-btn:hover { 
        background-color: #e9ecef;
        color: #0056b3; 
        border-color: #c0c0c0;
        text-decoration: none; 
    }

    .ahat-back-top-btn .glyphicon { font-size: 11px; }

    /* Botones laterales simplificados */
    .detailNavBgLeft, .detailNavBgRight {
        padding: 0 !important;
        display: flex;
        align-items: flex-start;
    }

    .ahat-sidebar-nav {
        display: flex;
        flex-direction: column; 
        align-items: center;
        justify-content: center;
        background-color: #fdfdfd !important;
        text-decoration: none;
        width: 50px !important;
        height: 60px !important;
        border-radius: 4px !important; 
        border: 1px solid #e0e0e0; 
        transition: all 0.15s ease; 
        margin-top: 0px; 
    }

    .ahat-sidebar-nav .glyphicon { 
        font-size: 16px; 
        color: #777 !important; 
        margin-bottom: 3px !important;
    }
    
    .ahat-sidebar-nav .ahat-nav-txt { 
        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        color: #777;
    }

    .ahat-sidebar-nav:hover { 
        background-color: #f0f4f8 !important; 
        border-color: #bcd1e0; 
    }
    .ahat-sidebar-nav:hover .glyphicon, .ahat-sidebar-nav:hover .ahat-nav-txt {
        color: #0056b3 !important; 
    }

    /* --- COLUMNA MULTIMEDIA (IZQUIERDA) --- */
    .ahat-media-card {
        background: #fafafa;
        border: 1px solid #e2e2e2;
        border-radius: 4px; 
        padding: 10px;
        margin-bottom: 15px;
    }
    
    /* --- HERRAMIENTAS (COMPARTIR/PDF) --- */
    .ahat-tools-panel {
        background: #f8f9fa;
        border: 1px solid #e2e2e2;
        border-radius: 4px;
        padding: 8px 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    .ahat-tool-item a, .ahat-tool-item span.glyphicon + a { 
        color: #444 !important; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 6px;
        padding: 4px 10px;
        border-radius: 3px; 
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
    }
    .ahat-tool-item a:hover { 
        background-color: #fff; 
        border-color: #dcdcdc;
    }

    /* --- CABECERA DE TEXTOS (DERECHA) --- */
    .ahat-header-info {
        margin-bottom: 20px;
    }
    .ahat-title-main {
        font-size: 24px;
        font-weight: 600;
        color: #222;
        margin: 5px 0 10px 0;
        line-height: 1.3;
    }
    .ahat-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        text-transform: uppercase;
        color: #555;
        background: #eee; 
        padding: 3px 8px;
        border-radius: 3px;
        font-weight: 600;
    }

    /* --- TITULOS DE SECCIÓN --- */
    .ahat-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        border-bottom: 2px solid #eaeaea;
        padding-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ahat-section-title span.glyphicon { color: #777; font-size: 14px; }

    /* --- FICHA TÉCNICA (Estilo explorador) --- */
    .ahat-info-grid {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #e2e2e2;
        border-radius: 4px;
        margin-bottom: 25px;
    }
    .ahat-grid-row {
        display: flex;
        padding: 8px 12px;
        border-bottom: 1px dotted #ccc;
    }
    .ahat-grid-row:last-child { border-bottom: none; }
    
    .ahat-lbl {
        width: 130px;
        flex-shrink: 0;
        font-size: 12px;
        color: #666; 
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .ahat-lbl .glyphicon { font-size: 11px; color: #888; }
    
    .ahat-val {
        font-size: 13px;
        color: #222;
        line-height: 1.5;
        flex-grow: 1;
    }

    .ahat-val-desc {
        background: #fafafa;
        padding: 10px;
        border-radius: 3px;
        border: 1px solid #f0f0f0;
        margin-top: 4px;
    }

    /* --- BLOQUES DE RELACIONES EXTERNAS --- */
    .ahat-relations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 10px;
    }
    .ahat-rel-card {
        background: #fff;
        border: 1px solid #e2e2e2;
        border-radius: 4px;
        padding: 12px;
    }
    .ahat-rel-card label {
        font-size: 11px;
        text-transform: uppercase;
        color: #777;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px solid #f0f0f0;
    }
    .ahat-rel-card label .glyphicon { color: #888; }
    .ahat-rel-card .unit { font-size: 13px; color: #333; line-height: 1.5; }
    .ahat-rel-card .unit l a { color: #0056b3; text-decoration: none; display: block; }
    .ahat-rel-card .unit l a:hover { text-decoration: underline; }

    /* Botones Readmore generados por JS */
    .rm-btn-read {
        display: inline-block;
        background: #f0f0f0;
        color: #333 !important;
        padding: 4px 10px;
        border-radius: 3px;
        margin-top: 8px;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        border: 1px solid #ddd;
        text-decoration: none;
    }
    .rm-btn-read:hover { background: #e4e4e4; border-color: #ccc; }
</style>

<div class="row ahat-detail-main">
    
    <div class='col-xs-12 navTop'>
        <a href="<?php print caNavUrl($this->request, '', 'Arxiu', 'Quadre'); ?>" class="ahat-back-top-btn">
            <span class="glyphicon glyphicon-arrow-left"></span> Quadre de Classificació
        </a>
        <div class="ahat-next-link-wrapper">{{{nextLink}}}</div>
    </div>
    
    <div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
        <div class="detailNavBgLeft">
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
                    
                    <div class="row" style="margin-top:10px;">
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
                                    <?= caDetailLink($this->request, "<span class='glyphicon glyphicon-save-file'></span> PDF", "faDownload", "ca_objects", $vn_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_objects_summary')); ?>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <?php if ($vn_comments_enabled) { ?>
                            <div id='ahatCommentsWrapper' style="display:none; background:#fafafa; border:1px solid #e2e2e2; border-radius:4px; padding:15px; margin-top:10px;">
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
                        <h1 class="ahat-title-main">
                            {{{<unit relativeTo="ca_collections" delimiter="<span style='color:#ccc; margin:0 5px;'>/</span>"><l>^ca_collections.preferred_labels.name</l></unit><ifcount min="1" code="ca_collections"> <span style='color:#ccc; margin:0 5px;'>/</span> </ifcount>}}}{{{ca_objects.preferred_labels.name}}}
                        </h1>
                    </div>
                    
                    <h3 class="ahat-section-title"><span class="glyphicon glyphicon-list-alt"></span> Fitxa Tècnica</h3>
                    <div class="ahat-info-grid">
                        
                        <div class="ahat-grid-row">
                            <div class="ahat-lbl">Codi</div>
                            <div class="ahat-val" style="font-family: monospace; font-size: 13px;">
                                <?= $vs_idno ? $vs_idno : "—"; ?>
                            </div>
                        </div>
                        
                        <div class="ahat-grid-row">
                            <div class="ahat-lbl"><span class="glyphicon glyphicon-folder-open"></span> Unitat</div>
                            <div class="ahat-val">
                                <?= $vs_container ? $vs_container : "—"; ?>
                            </div>
                        </div>            
                        
                        <div class="ahat-grid-row">
                            <div class="ahat-lbl"><span class="glyphicon glyphicon-calendar"></span> Data</div>
                            <div class="ahat-val">
                                <?= $vs_date ? $vs_date : "—"; ?>
                            </div>
                        </div>

                        <div class="ahat-grid-row" style="flex-direction: column;">
                            <div class="ahat-lbl" style="width: 100%; margin-bottom: 5px;"><span class="glyphicon glyphicon-align-left"></span> Descripció</div>
                            <div class="ahat-val ahat-val-desc trimText">
                                <?= $vs_description ? $vs_description : "<span style='color:#888; font-style:italic;'>Sense descripció</span>"; ?>
                            </div>
                        </div>
                        
                    </div>
                    
                    {{{<ifcount code="ca_entities" min="1"><h3 class="ahat-section-title" style="margin-top:30px;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}
                    {{{<ifcount code="ca_occurrences" min="1"><h3 class="ahat-section-title" style="margin-top:30px; display:none;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}
                    {{{<ifcount code="ca_places" min="1"><h3 class="ahat-section-title" style="margin-top:30px; display:none;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}
                    {{{<ifcount code="ca_list_items" min="1"><h3 class="ahat-section-title" style="margin-top:30px; display:none;"><span class="glyphicon glyphicon-link"></span> Relacions</h3></ifcount>}}}

                    <div class="row">
                        <div class="col-sm-12 ahat-relations-grid">      
                            
                            {{{<ifcount code="ca_entities" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-user"></span> 
                                        <ifcount code="ca_entities" min="1" max="1">Persona</ifcount>
                                        <ifcount code="ca_entities" min="2">Persones</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_entities" delimiter="<br/>"><l>^ca_entities.preferred_labels</l> <span style="font-size:11px; color:#888;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_occurrences" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-time"></span>
                                        <ifcount code="ca_occurrences" min="1" max="1">Esdeveniment</ifcount>
                                        <ifcount code="ca_occurrences" min="2">Esdeveniments</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_occurrences" delimiter="<br/>"><l>^ca_occurrences.preferred_labels</l> <span style="font-size:11px; color:#888;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_places" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-map-marker"></span>
                                        <ifcount code="ca_places" min="1" max="1">Lloc</ifcount>
                                        <ifcount code="ca_places" min="2">Llocs</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_places" delimiter="<br/>"><l>^ca_places.preferred_labels</l> <span style="font-size:11px; color:#888;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_list_items" min="1">
                                <div class="ahat-rel-card">
                                    <label><span class="glyphicon glyphicon-tags"></span>
                                        <ifcount code="ca_list_items" min="1" max="1">Terme</ifcount>
                                        <ifcount code="ca_list_items" min="2">Termes</ifcount>
                                    </label>
                                    <div class="unit"><unit relativeTo="ca_list_items" delimiter="<br/>"><l>^ca_list_items.preferred_labels.name_plural</l> <span style="font-size:11px; color:#888;">(^relationship_typename)</span></unit></div>
                                </div>
                            </ifcount>}}}
                            
                        </div>
                        
                        <div class="col-sm-12" style="margin-top: 20px;">
                            {{{map}}}
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
    
    <div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
        <div class="detailNavBgRight">
            {{{nextLink}}}
        </div>
    </div>
</div>

<script type='text/javascript'>
    jQuery(document).ready(function() {
        $('.trimText').readmore({
          speed: 150,
          maxHeight: 120,
          moreLink: '<a href="#" class="rm-btn-read">Llegir més</a>',
          lessLink: '<a href="#" class="rm-btn-read">Amagar</a>'
        });
    });
</script>