<?php
/** ---------------------------------------------------------------------
 * themes/default/views/Arxiu/quadre_html.php : Acordió Sencill i Nadiu
 * ----------------------------------------------------------------------
 */
?>

<div class="ahat-arxiu-wrapper">
    
    <div class="ahat-arxiu-header">
        <h1>Quadre de Classificació</h1>
    </div>

    <div class="ahat-carpetes-container">
        <?php
            $o_db = new Db();
            $qr_collections = $o_db->query("SELECT collection_id FROM ca_collections WHERE deleted = 0");

            $all_ids = [];
            $tree = [];
            $es_filla = []; 

            while($qr_collections->nextRow()) {
                $id = $qr_collections->get('collection_id');
                $all_ids[] = $id;
                
                $t_col = new ca_collections($id);
                $relacionades = $t_col->getRelatedItems('ca_collections');
                
                if(is_array($relacionades)) {
                    foreach($relacionades as $rel) {
                        $rel_id = $rel['collection_id'];
                        if (!isset($es_filla[$id])) { 
                            $tree[$id][] = $rel_id; 
                            $es_filla[$rel_id] = true; 
                        }
                    }
                }
            }

            $roots = [];
            foreach($all_ids as $id) {
                if(!isset($es_filla[$id])) {
                    $roots[] = $id;
                }
            }

            $request = $this->request;
            $impreses = []; 
            
            $renderCarpeta = function($vn_collection_id, $nivell) use (&$renderCarpeta, &$tree, &$impreses, $request) {
                if(in_array($vn_collection_id, $impreses)) return; 
                $impreses[] = $vn_collection_id;
                
                $t_collection = new ca_collections($vn_collection_id);
                $vs_collection_name = $t_collection->get('ca_collections.preferred_labels');
                
                $va_objects = $t_collection->getRelatedItems('ca_objects');
                $num_documents = (is_array($va_objects)) ? count($va_objects) : 0;
                
                $vn_access = $t_collection->get('ca_collections.access');
                $es_privada = ($vn_access == 0);
                
                $has_children = isset($tree[$vn_collection_id]) && count($tree[$vn_collection_id]) > 0;
                
                $classe_contenidor = ($nivell == 0) ? 'ahat-carpeta-arrel' : 'ahat-subcarpeta';
                
                if (($num_documents == 0 && !$has_children) || $es_privada) {
                    $motiu = $es_privada ? "Privat" : "Buida";
                    echo '
                    <div class="'.$classe_contenidor.' ahat-carpeta-buida">
                        <div class="ahat-info-esquerra">
                            📁 <span>'.$vs_collection_name.'</span>
                        </div>
                        <div class="ahat-info-dreta">
                            <span class="ahat-badge">🚫 '.$motiu.'</span>
                        </div>
                    </div>';
                } 
                else {
                    echo '
                    <details class="'.$classe_contenidor.'">
                        <summary class="ahat-carpeta-capcalera">
                            <div class="ahat-info-esquerra">
                                📂 <span>'.$vs_collection_name.'</span>
                            </div>
                            <div class="ahat-fletxa">▼</div>
                        </summary>
                        
                        <div class="ahat-carpeta-contingut">';
                        
                        // SUBCARPETES
                        if ($has_children) {
                            echo '<div class="ahat-subcarpetes-wrapper">';
                            foreach($tree[$vn_collection_id] as $child_id) {
                                $renderCarpeta($child_id, $nivell + 1);
                            }
                            echo '</div>';
                        }

                        // IMATGES
                        if ($num_documents > 0) {
                            echo '<div class="ahat-graella">';
                            foreach($va_objects as $va_obj) {
                                $vn_object_id = $va_obj['object_id'];
                                $t_object = new ca_objects($vn_object_id);
                                $vs_title = $t_object->get('ca_objects.preferred_labels');
                                $vs_image_tag = $t_object->get('ca_object_representations.media.small'); 
                                
                                $vs_link = caNavUrl($request, '', 'Detail', 'objects', array($vn_object_id));
                                
                                echo '
                                <a href="'.$vs_link.'" class="ahat-doc-targeta">
                                    <div class="ahat-doc-imatge">';
                                    if($vs_image_tag) { echo $vs_image_tag; } 
                                    else { echo '<div class="no-img">📄 Sense imatge</div>'; }
                                echo '
                                    </div>
                                    <div class="ahat-doc-titol">'.$vs_title.'</div>
                                </a>';
                            }
                            echo '</div>';
                        }
                            
                    echo '
                        </div>
                    </details>';
                }
            };

            if(count($roots) > 0) {
                foreach($roots as $root_id) {
                    $renderCarpeta($root_id, 0);
                }
            } else {
                echo '<p>No hi ha directoris públics.</p>';
            }
        ?>
    </div>
</div>

<style>
    /* Estructura general sin forzar fuentes */
    .ahat-arxiu-wrapper { 
        max-width: 1000px; 
        margin: 40px auto; 
    }
    
    .ahat-arxiu-header h1 { 
        border-bottom: 1px solid #ccc; 
        padding-bottom: 10px; 
        margin-bottom: 25px; 
    }

    .ahat-carpetes-container { 
        display: flex; 
        flex-direction: column; 
        gap: 10px; 
    }
    
    /* --- NIVELL 0 y SUBCARPETAS (Bordes simples) --- */
    .ahat-carpeta-arrel { 
        background: #fff; 
        border: 1px solid #ccc; 
    }
    
    .ahat-subcarpetes-wrapper {
        display: flex; 
        flex-direction: column; 
        gap: 5px; 
        margin-bottom: 15px;
    }
    
    .ahat-subcarpeta { 
        background: #fff; 
        border: 1px solid #e0e0e0; 
        margin-left: 20px; /* Tabulación normal */
    }

    /* --- CAPÇALERES (Simples y nativas) --- */
    .ahat-carpeta-capcalera { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 12px 15px; 
        cursor: pointer; 
        list-style: none; 
        user-select: none; 
    }
    
    .ahat-carpeta-capcalera::-webkit-details-marker { display: none; }
    
    .ahat-carpeta-capcalera:hover { 
        background: #f5f5f5; 
    }

    .ahat-carpeta-buida { 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        padding: 12px 15px; 
        background: #fafafa; 
        color: #888; 
        border-color: #eee;
    }

    .ahat-info-esquerra { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }
    
    .ahat-badge { 
        font-size: 0.85em; /* Tamaño relativo al texto estándar */
        background: #eee; 
        padding: 2px 8px; 
        border-radius: 4px; 
        color: #555; 
    }
    
    .ahat-fletxa { 
        color: #888; 
        font-size: 0.8em; 
        transition: transform 0.2s ease; 
    }
    
    details[open] > .ahat-carpeta-capcalera > .ahat-fletxa { 
        transform: rotate(180deg); 
    }
    
    details[open] > .ahat-carpeta-capcalera { 
        border-bottom: 1px solid #eee; 
        background: #fdfdfd; 
    }

    /* --- CONTINGUT INTERIOR --- */
    .ahat-carpeta-contingut { 
        padding: 15px; 
    }

    .ahat-graella { 
        display: flex; 
        flex-wrap: wrap; 
        gap: 15px; 
    }

    /* --- TARGETES DE DOCUMENTS (Estilo plano) --- */
    .ahat-doc-targeta { 
        width: 130px; 
        background: #fff;
        border: 1px solid #ddd; 
        text-decoration: none; 
        color: inherit; /* Hereda el color de enlace por defecto */
        display: block;
    }
    
    .ahat-doc-targeta:hover { 
        border-color: #999; /* Oscurece la línea sin sombras ni movimientos */
    }
    
    .ahat-doc-imatge { 
        height: 120px; 
        background: #f5f5f5; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        overflow: hidden; 
        border-bottom: 1px solid #eee;
    }
    
    .ahat-doc-imatge img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
    }
    
    .no-img { 
        color: #aaa; 
        font-size: 0.85em; 
    }
    
    .ahat-doc-titol { 
        padding: 8px; 
        line-height: 1.3; 
    }
</style>