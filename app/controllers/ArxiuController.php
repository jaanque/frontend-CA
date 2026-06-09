<?php
// Hem tret la part de "/core" de la ruta
require_once(__CA_LIB_DIR__.'/Controller/ActionController.php');

class ArxiuController extends ActionController {
    
    # Aquesta funció crea la ruta /Arxiu/Quadre
    public function Quadre() {
        // Li diem a Pawtucket quin arxiu ha de carregar
        $this->render('Arxiu/quadre_html.php');
    }
}