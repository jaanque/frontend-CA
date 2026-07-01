<?php
/** ---------------------------------------------------------------------
 * themes/default/views/Arxiu/quadre_html.php : Acordió Jeràrquic (Tree-view)
 * UI/UX Mejorada
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
                
                // Identificar si es un directorio principal (Nivel 0)
                $classe_arrel = ($nivell == 0) ? 'ahat-directori-arrel' : '';

                // Elemento sin contenido o privado
                if (($num_documents == 0 && !$has_children) || $es_privada) {
                    $icona = $es_privada ? "glyphicon-lock" : "glyphicon-folder-close";
                    $motiu = $es_privada ? "Privat" : "Buida";
                    $color = $es_privada ? "ahat-color-privat" : "ahat-color-buit";
                    $badge_class = $es_privada ? "ahat-badge-danger" : "ahat-badge-muted";

                    echo '
                    <div class="ahat-tree-row ahat-disabled '.$classe_arrel.'">
                        <span class="ahat-caret-placeholder"></span>
                        <span class="glyphicon '.$icona.' ahat-icon '.$color.'"></span> 
                        <span class="ahat-label">'.$vs_collection_name.'</span>
                        <span class="ahat-tag '.$badge_class.'">'.$motiu.'</span>
                    </div>';
                } 
                // Carpeta con contenido
                else {
                    $badge_docs = $num_documents > 0 ? '<span class="ahat-tag ahat-badge-info">'.$num_documents.' docs</span>' : '';

                    echo '
                    <details class="ahat-tree-node '.$classe_arrel.'">
                        <summary class="ahat-tree-row">
                            <span class="glyphicon glyphicon-chevron-right ahat-caret"></span>
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
                echo '<div class="ahat-empty-state"><span class="glyphicon glyphicon-inbox"></span><p>No hi ha directoris públics.</p></div>';
            }
        ?>
    </div>
</div>

<style>
    :root {
        --text-main: #2c3e50;
        --text-muted: #7f8c8d;
        --border-color: #eaeaea;
        --bg-main: #ffffff;
        --bg-hover: #f4f6f8;
        --bg-root: #fdfdfd;
        --color-folder: #f39c12;
        --color-doc: #95a5a6;
        --color-private: #e74c3c;
        --color-empty: #bdc3c7;
        --transition-speed: 0.2s;
    }

    /* Estructura general */
    .ahat-arxiu-wrapper { 
        max-width: 900px; 
        margin: 30px auto; 
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        font-size: 15px;
        color: var(--text-main);
        background: var(--bg-main);
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 24px;
        border: 1px solid var(--border-color);
    }
    
    .ahat-arxiu-header h1 { 
        font-size: 24px; 
        font-weight: 600;
        border-bottom: 2px solid var(--border-color); 
        padding-bottom: 12px; 
        margin-top: 0;
        margin-bottom: 20px; 
        color: #1a252f;
    }

    .ahat-root-wrapper {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    /* --- SEPARACIÓN DE DIRECTORIOS PRINCIPALES --- */
    .ahat-directori-arrel {
        border-bottom: 1px solid var(--border-color); 
        background-color: var(--bg-root); 
    }
    .ahat-directori-arrel:last-child {
        border-bottom: none;
    }

    /* Cada fila (carpeta o documento) */
    .ahat-tree-row {
        display: flex;
        align-items: center;
        padding: 8px 12px; 
        cursor: pointer;
        text-decoration: none !important;
        color: inherit;
        user-select: none;
        transition: background-color var(--transition-speed) ease, padding-left var(--transition-speed) ease;
        border-radius: 4px; /* Suave en el interior */
        margin: 2px 4px;
    }

    /* Hover & Focus */
    .ahat-tree-row:hover, .ahat-tree-row:focus-visible {
        background-color: var(--bg-hover);
        outline: none;
    }

    /* Enlaces de documentos */
    a.ahat-tree-row {
        color: var(--text-main);
        background-color: transparent;
    }
    a.ahat-tree-row:hover {
        color: #2980b9;
        background-color: #ebf5fa;
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
        font-size: 11px;
        width: 20px;
        color: #a6b0b3;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-block;
        text-align: center;
    }
    .ahat-tree-node[open] > summary .ahat-caret {
        transform: rotate(90deg);
        color: var(--text-main);
    }

    /* Espaciador para alineación */
    .ahat-caret-placeholder {
        width: 20px;
        display: inline-block;
        flex-shrink: 0;
    }

    /* Iconos */
    .ahat-icon {
        margin-right: 10px;
        font-size: 16px;
        flex-shrink: 0;
    }
    .ahat-color-carpeta { color: var(--color-folder); } 
    .ahat-color-doc { color: var(--color-doc); }    
    .ahat-color-privat { color: var(--color-private); }  
    .ahat-color-buit { color: var(--color-empty); }    

    /* Textos y etiquetas */
    .ahat-label {
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 10px;
    }

    .font-weight-bold {
        font-weight: 600;
        color: #34495e;
    }

    .ahat-disabled {
        color: var(--text-muted);
    }

    /* Contadores y Badges (Estilo Píldora) */
    .ahat-tag {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
        margin-left: auto; /* Empuja el badge a la derecha */
        white-space: nowrap;
        flex-shrink: 0;
    }
    .ahat-badge-info { background: #e1f0fa; color: #2980b9; }
    .ahat-badge-danger { background: #fdeaea; color: #e74c3c; }
    .ahat-badge-muted { background: #f0f3f4; color: #7f8c8d; }

    /* La rama del árbol (Subcarpetas y documentos) */
    .ahat-tree-branch {
        margin-left: 22px; 
        padding-left: 6px;
        border-left: 1px solid #dfe6e9; /* Línea sólida sutil en lugar de punteada */
        margin-top: 2px;
        margin-bottom: 6px;
        /* Animación suave de aparición */
        animation: ahat-fade-in 0.3s ease-in-out;
    }

    @keyframes ahat-fade-in {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Estado vacío si no hay colecciones */
    .ahat-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    .ahat-empty-state .glyphicon {
        font-size: 32px;
        color: var(--color-empty);
        margin-bottom: 10px;
    }
</style>