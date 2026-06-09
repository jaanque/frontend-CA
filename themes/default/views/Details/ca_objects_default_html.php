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
?>

<style>
    /* --- CONTENEDOR PRINCIPAL --- */
    .ahat-detail-main {
        padding-top: 30px;
        padding-bottom: 60px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* --- NAVEGACIÓN Y SIDEBARS --- */
    .ahat-back-top-btn {
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 25px;
        transition: color 0.2s;
    }
    .ahat-back-top-btn:hover { color: #0f172a; text-decoration: none; }
    
    .ahat-sidebar-nav {
        display: block;
        text-align: center;
        color: #64748b;
        text-decoration: none;
        padding: 25px 0;
        width: 100%;
        transition: background-color 0.2s, color 0.2s;
    }
    .ahat-sidebar-nav:hover { background-color: #f1f5f9; color: #0f172a; text-decoration: none; }
    .ahat-sidebar-nav .glyphicon { font-size: 16px; display: block; margin-bottom: 6px; }
    .ahat-sidebar-nav .ahat-nav-txt { font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; display: block; line-height: 1.4; font-weight: 700; }

    /* --- COLUMNA MULTIMEDIA (IZQUIERDA) --- */
    .ahat-media-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        margin-bottom: 25px;
        transition: box-shadow 0.3s;
    }
    .ahat-media-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }
    
    /* --- HERRAMIENTAS (COMPARTIR/PDF) --- */
    .ahat-tools-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .ahat-tool-item {
        font-size: 13px;
        font-weight: 500;
    }
    .ahat-tool-item a, .ahat-tool-item span.glyphicon + a { 
        color: #475569 !important; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 6px;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background-color 0.2s, color 0.2s;
    }
    .ahat-tool-item a:hover { background-color: #f1f5f9; color: #0f172a !important; }
    .ahat-tool-item .glyphicon { font-size: 14px; color: #94a3b8; }

    /* --- CABECERA DE TEXTOS (DERECHA) --- */
    .ahat-header-info {
        margin-bottom: 25px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 20px;
    }
    .ahat-title-main {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin: 0 0 8px 0;
        line-height: 1.25;
    }
    .ahat-type-badge {
        display: inline-block;
        font-size: 11px;
        text-transform: uppercase;
        color: #475569;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 4px;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    /* --- FICHA TÉCNICA REFINADA --- */
    .ahat-info-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 35px;
    }
    .ahat-grid-row {
        display: grid;
        grid-template-columns: 140px 1fr;
        align-items: start;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .ahat-grid-row:last-child { border-bottom: none; }
    .ahat-lbl {
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding-top: 2px;
    }
    .ahat-val {
        font-size: 15px;
        color: #1e293b;
        line-height: 1.6;
    }

    /* --- BLOQUES DE RELACIONES EXTERNAS --- */
    .ahat-relations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
        margin-top: 25px;
        border-top: 1px solid #e2e8f0;
        padding-top: 30px;
    }
    .ahat-rel-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        transition: transform 0.2s;
    }
    .ahat-rel-card:hover { transform: translateY(-2px); }
    .ahat-rel-card label {
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 800;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 2px solid #e2e8f0;
    }
    .ahat-rel-card .unit { font-size: 13.5px; color: #334155; line-height: 1.5; }
    .ahat-rel-card .unit l a { color: #0284c7; text-decoration: none; font-weight: 600; }
    .ahat-rel-card .unit l a:hover { text-decoration: underline; }
</style>

<div class="row ahat-detail-main">
    
    <div class='col-xs-12 navTop'>
        <a href="<?php print caNavUrl($this->request, '', 'Arxiu', 'Quadre'); ?>" class="ahat-back-top-btn">
            <span class="glyphicon glyphicon-chevron-left"></span> Tornar a l'Arxiu
        </a>
        {{{nextLink}}}
    </div>
    
    <div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
        <div class="detailNavBgLeft" style="padding: 0; background: transparent; border: none;">
            <a href="<?php print caNavUrl($this->request, '', 'Arxiu', 'Quadre'); ?>" class="ahat-sidebar-nav">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="ahat-nav-txt">Tornar a<br/>l'Arxiu</span>
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
                    
                    <div class="row">
                        <?= caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_object, array("returnAs" => "bsCols", "linkTo" => "basic", "bsColClasses" => "smallpadding col-sm-3 col-md-3 col-xs-4", "primaryOnly" => $this->getVar('representationViewerPrimaryOnly') ? 1 : 0)); ?>
                    </div>
                    
                    <?php if ($vn_comments_enabled | $vn_share_enabled | $vn_pdf_enabled) { ?>              
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
                                    <span class="glyphicon glyphicon-file"></span> <?= caDetailLink($this->request, "PDF", "faDownload", "ca_objects", $vn_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_objects_summary')); ?>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <?php if ($vn_comments_enabled) { ?>
                            <div id='ahatCommentsWrapper' style="display:none; background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:15px; margin-top:10px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                                <?php print $this->getVar("itemComments");?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                </div>
                
                <div class='col-sm-6 col-md-6 col-lg-5'>
                    
                    <div class="ahat-header-info">
                        <h1 class="ahat-title-main">
                            {{{<unit relativeTo="ca_collections" delimiter="<br/>"><l>^ca_collections.preferred_labels.name</l></unit><ifcount min="1" code="ca_collections"> ➔ </ifcount>}}}{{{ca_objects.preferred_labels.name}}}
                        </h1>
                        <span class="ahat-type-badge">
                            {{{<unit>^ca_objects.type_id</unit>}}}
                        </span>
                    </div>
                    
                    <div class="ahat-info-grid">
                        
                        {{{<ifdef code="ca_objects.idno">
                            <div class="ahat-grid-row">
                                <div class="ahat-lbl">Código</div>
                                <div class="ahat-val">^ca_objects.idno</div>
                            </div>
                        </ifdef>}}}
                        
                        {{{<ifdef code="ca_objects.containerID">
                            <div class="ahat-grid-row">
                                <div class="ahat-lbl">Caja / Serie</div>
                                <div class="ahat-val">^ca_objects.containerID</div>
                            </div>
                        </ifdef>}}}            
                        
                        {{{<ifdef code="ca_objects.description">
                            <div class="ahat-grid-row">
                                <div class="ahat-lbl">Descripción</div>
                                <div class="ahat-val trimText">^ca_objects.description</div>
                            </div>
                        </ifdef>}}}
                        
                        {{{<ifdef code="ca_objects.dateSet.setDisplayValue">
                            <div class="ahat-grid-row">
                                <div class="ahat-lbl">Fecha</div>
                                <div class="ahat-val">^ca_objects.dateSet.setDisplayValue</div>
                            </div>
                        </ifdef>}}}
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12 ahat-relations-grid">      
                            
                            {{{<ifcount code="ca_entities" min="1">
                                <div class="ahat-rel-card">
                                    <ifcount code="ca_entities" min="1" max="1"><label>Persona Relacionada</label></ifcount>
                                    <ifcount code="ca_entities" min="2"><label>Personas Relacionadas</label></ifcount>
                                    <div class="unit"><unit relativeTo="ca_entities" delimiter="<br/>"><l>^ca_entities.preferred_labels</l> (^relationship_typename)</unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_occurrences" min="1">
                                <div class="ahat-rel-card">
                                    <ifcount code="ca_occurrences" min="1" max="1"><label>Esdeveniment</label></ifcount>
                                    <ifcount code="ca_occurrences" min="2"><label>Esdeveniments</label></ifcount>
                                    <div class="unit"><unit relativeTo="ca_occurrences" delimiter="<br/>"><l>^ca_occurrences.preferred_labels</l> (^relationship_typename)</unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_places" min="1">
                                <div class="ahat-rel-card">
                                    <ifcount code="ca_places" min="1" max="1"><label>Lloc Relacionat</label></ifcount>
                                    <ifcount code="ca_places" min="2"><label>Llocs Relacionats</label></ifcount>
                                    <div class="unit"><unit relativeTo="ca_places" delimiter="<br/>"><l>^ca_places.preferred_labels</l> (^relationship_typename)</unit></div>
                                </div>
                            </ifcount>}}}
                            
                            {{{<ifcount code="ca_list_items" min="1">
                                <div class="ahat-rel-card">
                                    <ifcount code="ca_list_items" min="1" max="1"><label>Terme Relacionat</label></ifcount>
                                    <ifcount code="ca_list_items" min="2"><label>Termes Relacionats</label></ifcount>
                                    <div class="unit"><unit relativeTo="ca_list_items" delimiter="<br/>"><l>^ca_list_items.preferred_labels.name_plural</l> (^relationship_typename)</unit></div>
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
        <div class="detailNavBgRight" style="background: transparent; border: none;">
            {{{nextLink}}}
        </div>
    </div>
</div>

<script type='text/javascript'>
    jQuery(document).ready(function() {
        $('.trimText').readmore({
          speed: 100,
          maxHeight: 160,
          moreLink: '<a href="#" style="color:#0284c7; font-size:13px; font-weight:600; margin-top:5px; display:inline-block;">Llegir més</a>',
          lessLink: '<a href="#" style="color:#0284c7; font-size:13px; font-weight:600; margin-top:5px; display:inline-block;">Amagar</a>'
        });
    });
</script>