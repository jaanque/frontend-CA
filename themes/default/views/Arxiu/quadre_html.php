<?php
/** ---------------------------------------------------------------------
 * themes/default/views/Arxiu/quadre_html.php : Acordió Jeràrquic (Tree-view)
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
                    $icona_motiu = $es_privada ? "glyphicon-lock" : "glyphicon-ban-circle";
                    $motiu = $es_privada ? "Privat" : "Buida";
                    echo '
                    <div class="'.$classe_contenidor.' ahat-carpeta-buida">
                        <div class="ahat-info-esquerra">
                            <span class="glyphicon glyphicon-folder-close ahat-icona-carpeta"></span> 
                            <span>'.$vs_collection_name.'</span>
                        </div>
                        <div class="ahat-info-dreta">
                            <span class="ahat-badge"><span class="glyphicon '.$icona_motiu.'"></span> '.$motiu.'</span>
                        </div>
                    </div>';
                } 
                else {
                    echo '
                    <details class="'.$classe_contenidor.'">
                        <summary class="ahat-carpeta-capcalera">
                            <div class="ahat-info-esquerra">
                                <span class="glyphicon glyphicon-folder-open ahat-icona-carpeta"></span> 
                                <span>'.$vs_collection_name.'</span>
                            </div>
                            <div class="ahat-fletxa"><span class="glyphicon glyphicon-chevron-down"></span></div>
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

                        // DOCUMENTS (Llistat amb icona real)
                        if ($num_documents > 0) {
                            echo '<ul class="ahat-doc-llista">';
                            foreach($va_objects as $va_obj) {
                                $vn_object_id = $va_obj['object_id'];
                                $t_object = new ca_objects($vn_object_id);
                                $vs_title = $t_object->get('ca_objects.preferred_labels');
                                
                                $vs_link = caNavUrl($request, '', 'Detail', 'objects', array($vn_object_id));
                                
                                echo '
                                <li class="ahat-doc-item">
                                    <a href="'.$vs_link.'">
                                        <span class="glyphicon glyphicon-picture ahat-icona-doc"></span> 
                                        '.$vs_title.'
                                    </a>
                                </li>';
                            }
                            echo '</ul>';
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
    /* Estructura general */
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
        gap: 15px; 
    }
    
    /* --- NIVELL 0 (La carpeta principal parece una caja solida) --- */
    .ahat-carpeta-arrel { 
        background: #fff; 
        border: 1px solid #dcdcdc; 
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* --- SUBCARPETAS (Efecto árbol jerárquico, sin cajas pesadas) --- */
    .ahat-subcarpetes-wrapper {
        display: flex; 
        flex-direction: column; 
        gap: 8px; 
        margin: 10px 0 10px 15px;
        padding-left: 15px;
        border-left: 2px solid #eaeaea; /* Línea guía visual */
    }
    
    .ahat-subcarpeta { 
        background: transparent; 
        border: none; 
    }
    
    /* Le damos borde solo a la cabecera de la subcarpeta para que resalte ligeramente */
    .ahat-subcarpeta > .ahat-carpeta-capcalera {
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        background: #fafafa;
    }

    /* --- CAPÇALERES --- */
    .ahat-carpeta-capcalera { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 12px 15px; 
        cursor: pointer; 
        list-style: none; 
        user-select: none; 
        transition: background-color 0.2s;
    }
    
    .ahat-carpeta-capcalera::-webkit-details-marker { display: none; }
    
    .ahat-carpeta-capcalera:hover { 
        background: #f1f1f1 !important; 
    }

    .ahat-carpeta-buida { 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        padding: 12px 15px; 
        background: #fdfdfd; 
        color: #888; 
    }
    
    /* Borde inferior solo para carpetas principales abiertas */
    .ahat-carpeta-arrel[open] > .ahat-carpeta-capcalera { 
        border-bottom: 1px solid #eee; 
    }

    .ahat-info-esquerra { 
        display: flex; 
        align-items: center; 
        font-weight: 500;
        color: #333;
    }
    
    /* Estilos para los iconos */
    .ahat-icona-carpeta {
        color: #6c757d;
        margin-right: 12px;
        font-size: 1.2em;
    }

    .ahat-icona-doc {
        color: #adb5bd;
        margin-right: 10px;
    }

    .ahat-badge { 
        font-size: 0.85em; 
        background: #f5f5f5; 
        padding: 4px 10px; 
        border-radius: 20px; 
        color: #777; 
        border: 1px solid #e0e0e0;
    }
    
    .ahat-fletxa { 
        color: #aaa; 
        font-size: 0.8em; 
        transition: transform 0.2s ease; 
    }
    
    details[open] > .ahat-carpeta-capcalera > .ahat-fletxa { 
        transform: rotate(180deg); 
    }

    /* --- CONTINGUT INTERIOR --- */
    .ahat-carpeta-contingut { 
        padding: 10px 15px; 
    }

    /* --- LLISTAT DE DOCUMENTS --- */
    .ahat-doc-llista { 
        list-style-type: none;
        padding-left: 15px;
        margin: 5px 0 5px 15px;
        display: flex;
        flex-direction: column;
        border-left: 2px solid #eaeaea; /* Línea guía visual para documentos también */
    }

    .ahat-doc-item a { 
        display: block;
        padding: 10px 14px;
        color: #444;
        text-decoration: none;
        border-bottom: 1px solid #f0f0f0; /* Separador sutil, no caja */
        transition: all 0.2s ease;
    }
    
    /* Quitar el borde inferior del último documento para que quede más limpio */
    .ahat-doc-item:last-child a {
        border-bottom: none;
    }

    .ahat-doc-item a:hover { 
        background-color: #f9f9f9;
        color: #000;
    }
    
    .ahat-doc-item a:hover .ahat-icona-doc {
        color: #555;
    }
</style>