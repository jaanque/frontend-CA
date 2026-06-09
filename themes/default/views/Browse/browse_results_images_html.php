<?php
/* ----------------------------------------------------------------------
 * views/Browse/browse_results_images_html.php : 
 * ----------------------------------------------------------------------
 */
 
    $qr_res             = $this->getVar('result');
    $va_facets          = $this->getVar('facets');
    $va_criteria        = $this->getVar('criteria');
    $vs_browse_key      = $this->getVar('key');
    $va_access_values   = $this->getVar('access_values');
    $vn_hits_per_block  = (int)$this->getVar('hits_per_block');
    $vn_start           = (int)$this->getVar('start');
    $vn_row_id          = (int)$this->getVar('row_id');
    $vb_row_id_loaded   = false;
    if(!$vn_row_id){
        $vb_row_id_loaded = true;
    }
    
    $va_views           = $this->getVar('views');
    $vs_current_view    = $this->getVar('view');
    $va_view_icons      = $this->getVar('viewIcons');
    $vs_current_sort    = $this->getVar('sort');
    
    $t_instance         = $this->getVar('t_instance');
    $vs_table           = $this->getVar('table');
    $vs_pk              = $this->getVar('primaryKey');
    $o_config = $this->getVar("config");    
    
    $va_options         = $this->getVar('options');
    $vs_extended_info_template = caGetOption('extendedInformationTemplate', $va_options, null);

    $vb_ajax            = (bool)$this->request->isAjax();
    $va_add_to_set_link_info = caGetAddToSetInfo($this->request);
    
    $vb_refine = false;
    if(is_array($va_facets) && sizeof($va_facets)){
        $vb_refine = true;
    }

    if ($vn_start < $qr_res->numHits()) {
        $vn_c = 0;
        $vn_results_output = 0;
        $qr_res->seek($vn_start);
        
        // Contenidor principal de la llista (Només primera càrrega)
        if ($vn_start == 0) {
            print "<div class='doc-list-container'>";
        }

        $t_list_item = new ca_list_items();
        while($qr_res->nextHit()) {
            if($vn_c == $vn_hits_per_block){
                if($vb_row_id_loaded){ break; } else { $vn_c = 0; }
            }
            $vn_id = $qr_res->get("{$vs_table}.{$vs_pk}");
            if($vn_id == $vn_row_id){ $vb_row_id_loaded = true; }
            
            $vs_cache_key = md5($vs_table.$vn_id."list_simple".$vb_refine);
            if(($o_config->get("cache_timeout") > 0) && ExternalCache::contains($vs_cache_key,'browse_result')){
                print ExternalCache::fetch($vs_cache_key, 'browse_result');
            }else{
            
                // 1. Obtenim el text del títol
                $vs_label_text = $qr_res->get("{$vs_table}.preferred_labels.name");
                
                // 2. Deixem que el sistema de CA generi l'enllaç sencer amb la classe CSS correcta! Això evita qualsevol error de rutes.
                $vs_title_link = caDetailLink($this->request, $vs_label_text, 'doc-title', $vs_table, $vn_id);
                $vs_btn_link = caDetailLink($this->request, 'Obrir document', 'btn-open', $vs_table, $vn_id);
            
                $vs_add_to_set_link = "";
                if(($vs_table == 'ca_objects') && is_array($va_add_to_set_link_info) && sizeof($va_add_to_set_link_info)){
                    $vs_add_to_set_link = "<a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', $va_add_to_set_link_info["controller"], 'addItemForm', array($vs_pk => $vn_id))."\"); return false;' class='btn-add-set' title='".$va_add_to_set_link_info["link_text"]."'>+</a>";
                }
            
                $vs_expanded_info = $qr_res->getWithTemplate($vs_extended_info_template);

                // ESTRUCTURA UX NETA
                $vs_result_output = "
                <div class='doc-item' id='row{$vn_id}'>
                    <div class='doc-content'>
                        {$vs_title_link}
                        <div class='doc-description'>{$vs_expanded_info}</div>
                    </div>
                    
                    <div class='doc-actions'>
                        {$vs_btn_link}
                        {$vs_add_to_set_link}
                    </div>
                </div>";

                ExternalCache::save($vs_cache_key, $vs_result_output, 'browse_result', $o_config->get("cache_timeout"));
                print $vs_result_output;
            }               
            $vn_c++;
            $vn_results_output++;
        }
        
        if (($vn_start + $vn_results_output) >= $qr_res->numHits()) {
             print "</div>";
        }
        
        print "<div style='clear:both'></div>".caNavLink($this->request, _t('Mostrar més resultats'), 'btn-load-more', '*', '*', '*', array('s' => $vn_start + $vn_results_output, 'key' => $vs_browse_key, 'view' => $vs_current_view, 'sort' => $vs_current_sort, '_advanced' => $this->getVar('is_advanced') ? 1  : 0));
    }
?>

<style>
    /* Tipografia base neta per a tot el bloc */
    .doc-list-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px 0;
    }

    /* Targeta de document (Fila) */
    .doc-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #ffffff;
        padding: 20px 24px;
        margin-bottom: 12px;
        border: 1px solid #e0e4e8;
        border-radius: 8px;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    /* Efecte Hover suau */
    .doc-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Contingut Esquerra */
    .doc-content {
        flex: 1;
        padding-right: 20px;
    }

    /* Estils aplicats a l'enllaç generat pel sistema */
    a.doc-title {
        display: block;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        text-decoration: none;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    a.doc-title:hover {
        color: #b21117; 
    }

    .doc-description {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
    }

    /* Botons (Dreta) */
    .doc-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Estils aplicats al botó generat pel sistema */
    a.btn-open {
        background-color: #f3f4f6;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
        white-space: nowrap;
    }

    a.btn-open:hover {
        background-color: #b21117;
        color: #ffffff;
        text-decoration: none;
    }

    .btn-add-set {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: #f3f4f6;
        color: #6b7280;
        border-radius: 6px;
        text-decoration: none;
        font-size: 18px;
        font-weight: bold;
    }

    .btn-add-set:hover {
        background-color: #e5e7eb;
        color: #111827;
        text-decoration: none;
    }

    /* Paginació inferior */
    .btn-load-more {
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 30px auto;
        text-align: center;
        background-color: #ffffff;
        color: #374151;
        border: 1px solid #cbd5e1;
        padding: 12px 24px;
        font-size: 15px;
        font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-load-more:hover {
        background-color: #f8fafc;
        text-decoration: none;
    }

    /* Adaptació a mòbils */
    @media (max-width: 768px) {
        .doc-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .doc-content {
            padding-right: 0;
            margin-bottom: 16px;
        }
        .doc-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>