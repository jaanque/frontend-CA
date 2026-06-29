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

    <div class="ahat-tree-container">
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
                
                // Identificar si es un directorio principal (Nivel 0) para separarlo visualmente
                $classe_arrel = ($nivell == 0) ? 'ahat-directori-arrel' : '';

                // Elemento sin contenido o privado
                if (($num_documents == 0 && !$has_children) || $es_privada) {
                    $icona = $es_privada ? "glyphicon-lock" : "glyphicon-folder-close";
                    $motiu = $es_privada ? "Privat" : "Buida";
                    $color = $es_privada ? "ahat-color-privat" : "ahat-color-buit";

                    echo '
                    <div class="ahat-tree-row ahat-disabled '.$classe_arrel.'">
                        <span class="ahat-caret-placeholder"></span>
                        <span class="glyphicon '.$icona.' ahat-icon '.$color.'"></span> 
                        <span class="ahat-label">'.$vs_collection_name.'</span>
                        <span class="ahat-tag">'.$motiu.'</span>
                    </div>';
                } 
                // Carpeta con contenido
                else {
                    $badge_docs = $num_documents > 0 ? '<span class="ahat-tag">'.$num_documents.' docs</span>' : '';

                    echo '
                    <details class="ahat-tree-node '.$classe_arrel.'">
                        <summary class="ahat-tree-row">
                            <span class="glyphicon glyphicon-triangle-right ahat-caret"></span>
                            <span class="glyphicon glyphicon-folder-close ahat-icon ahat-color-carpeta ahat-icon-tancada"></span>
                            <span class="glyphicon glyphicon-folder-open ahat-icon ahat-color-carpeta ahat-icon-oberta"></span>
                            <span class="ahat-label font-weight-bold">'.$vs_collection_name.'</span>
                            '.$badge_docs.'
                        </summary>
                        
                        <div class="ahat-tree-branch">';
                        
                        // SUBCARPETES
                        if ($has_children) {
                            foreach($tree[$vn_collection_id] as $child_id) {
                                $renderCarpeta($child_id, $nivell + 1);
                            }
                        }

                        // DOCUMENTS
                        if ($num_documents > 0) {
                            foreach($va_objects as $va_obj) {
                                $vn_object_id = $va_obj['object_id'];
                                $t_object = new ca_objects($vn_object_id);
                                $vs_title = $t_object->get('ca_objects.preferred_labels');
                                $vs_link = caNavUrl($request, '', 'Detail', 'objects', array($vn_object_id));
                                
                                echo '
                                <a href="'.$vs_link.'" class="ahat-tree-row ahat-doc">
                                    <span class="ahat-caret-placeholder"></span>
                                    <span class="glyphicon glyphicon-file ahat-icon ahat-color-doc"></span>
                                    <span class="ahat-label">'.$vs_title.'</span>
                                </a>';
                            }
                        }
                            
                    echo '
                        </div>
                    </details>';
                }
            };

            if(count($roots) > 0) {
                echo '<div class="ahat-root-wrapper">';
                foreach($roots as $root_id) {
                    $renderCarpeta($root_id, 0);
                }
                echo '</div>';
            } else {
                echo '<p class="text-muted">No hi ha directoris públics.</p>';
            }
        ?>
    </div>
</div>

<style>
    /* Estructura general */
    .ahat-arxiu-wrapper { 
        max-width: 900px; 
        margin: 20px auto; 
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        font-size: 14px;
        color: #333;
    }
    
    .ahat-arxiu-header h1 { 
        font-size: 22px; 
        border-bottom: 2px solid #ddd; 
        padding-bottom: 8px; 
        margin-bottom: 15px; 
    }

    .ahat-root-wrapper {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background: #fff;
    }

    /* --- SEPARACIÓN DE DIRECTORIOS PRINCIPALES --- */
    .ahat-directori-arrel {
        border-bottom: 1px solid #eaeaea; /* Línea separadora clara */
        background-color: #fafafa; /* Fondo ligeramente distinto para la raíz */
    }
    
    .ahat-directori-arrel:last-child {
        border-bottom: none; /* Quitamos la línea al último elemento */
    }

    /* Cada fila (carpeta o documento) */
    .ahat-tree-row {
        display: flex;
        align-items: center;
        padding: 6px 10px; /* Un poco más de respiro vertical */
        cursor: pointer;
        text-decoration: none !important;
        color: inherit;
        user-select: none;
        transition: background-color 0.15s ease;
    }

    /* Hover */
    .ahat-tree-row:hover {
        background-color: #eaf1f8;
    }

    /* Enlaces de documentos */
    a.ahat-tree-row {
        color: #2c3e50;
        background-color: #fff; /* Las sub-ramas tienen fondo blanco */
        border-bottom: 1px solid #f7f7f7; /* Micro-línea entre documentos */
    }
    a.ahat-tree-row:last-child {
        border-bottom: none;
    }
    a.ahat-tree-row:hover {
        color: #0056b3;
    }

    /* Ocultar la flecha nativa de <details> */
    details > summary::-webkit-details-marker { display: none; }
    details > summary { list-style: none; }

    /* Cambio de iconos al abrir/cerrar */
    .ahat-tree-node .ahat-icon-oberta { display: none; }
    .ahat-tree-node[open] > summary .ahat-icon-oberta { display: inline-block; }
    .ahat-tree-node[open] > summary .ahat-icon-tancada { display: none; }

    /* Rotación de la pequeña flecha (caret) */
    .ahat-caret {
        font-size: 10px;
        width: 18px;
        color: #888;
        transition: transform 0.1s ease;
        display: inline-block;
        text-align: center;
    }
    .ahat-tree-node[open] > summary .ahat-caret {
        transform: rotate(90deg);
    }

    /* Espaciador para alineación */
    .ahat-caret-placeholder {
        width: 18px;
        display: inline-block;
    }

    /* Iconos */
    .ahat-icon {
        margin-right: 8px;
        font-size: 15px;
    }
    .ahat-color-carpeta { color: #dcb143; } 
    .ahat-color-doc { color: #8a959e; }     
    .ahat-color-privat { color: #e74c3c; }  
    .ahat-color-buit { color: #bdc3c7; }    

    /* Textos y etiquetas */
    .ahat-label {
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .font-weight-bold {
        font-weight: 600;
        color: #222;
    }

    .ahat-disabled {
        color: #888;
    }

    /* Contador */
    .ahat-tag {
        font-size: 11px;
        background: #e4e8ec;
        color: #555;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 10px;
        white-space: nowrap;
    }

    /* La rama del árbol (Subcarpetas y documentos) */
    .ahat-tree-branch {
        margin-left: 22px; 
        padding-left: 8px;
        border-left: 1px dotted #bbb; /* Línea de guía visual punteada */
        margin-top: 0;
        margin-bottom: 8px;
        background-color: #fff; /* Asegura que el interior del árbol sea blanco */
    }
</style>