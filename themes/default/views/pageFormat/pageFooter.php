<?php
/* ----------------------------------------------------------------------
 * views/pageFormat/pageFooter.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2015-2025 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
?>
        <div style="clear:both; height:1px;"></div>
        </div></div></div></div></div>

        <style>
            #footer {
                background-color: #ffffff; /* Fondo blanco limpio */
                border-top: 1px solid #e9ecef; /* Línea de división ultra sutil */
                padding: 40px 0;
                margin-top: 50px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            }
            
            #footer .container {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            #footer .list-inline {
                margin: 0;
                padding: 0;
                display: inline-flex;
                gap: 12px; /* Espaciado moderno entre elementos */
            }

            #footer .list-inline li {
                padding: 0 !important;
                margin: 0 !important;
            }

            #footer .list-inline a {
                color: #767676; /* Gris neutro de texto secundario tipo Pinterest */
                font-weight: 600;
                font-size: 14px;
                text-decoration: none !important;
                padding: 10px 20px;
                border-radius: 30px; /* Botón tipo píldora */
                transition: all 0.2s ease;
                display: inline-block;
            }

            /* Efecto Hover suave al pasar el ratón */
            #footer .list-inline a:hover {
                background-color: #f1f3f5; /* Fondo gris claro suave */
                color: #111111; /* Texto casi negro */
            }
        </style>

        <footer id="footer" role="contentinfo">
            <div class="container text-center">
                <ul class="list-inline">
                    <li>
                        <a href="<?php print caNavUrl($this->request, '', 'Front', 'Index'); ?>">Inici</a>
                    </li>
                    <li>
                        <a href="<?php print caNavUrl($this->request, '', 'Arxiu', 'Quadre'); ?>">Arxiu</a>
                    </li>
                    <?php if (CookieOptionsManager::cookieManagerEnabled()): ?>
                        <li>
                            <?php 
                                // Capturamos el enlace nativo de cookies para aplicarle el estilo limpio
                                $cookieLink = caNavLink($this->request, "Gestió de Cookies", "", "", "Cookies", "manage");
                                // Le inyectamos la clase si es necesario, aunque al heredar de la lista ya toma el estilo
                                print $cookieLink; 
                            ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </footer>

        <?php print TooltipManager::getLoadHTML(); ?>
        
        <div id="caMediaPanel" role="complementary"> 
            <div id="caMediaPanelContentArea"></div>
        </div>
        
        <script type="text/javascript">
            var caMediaPanel;
            jQuery(document).ready(function() {
                if (caUI.initPanel) {
                    caMediaPanel = caUI.initPanel({ 
                        panelID: 'caMediaPanel',
                        panelContentID: 'caMediaPanelContentArea',
                        onCloseCallback: function(data) {
                            if (data && data.url) {
                                window.location = data.url;
                            }
                        },
                        exposeBackgroundColor: '#FFFFFF',
                        exposeBackgroundOpacity: 0.7,
                        panelTransitionSpeed: 400,
                        allowMobileSafariZooming: true,
                        mobileSafariViewportTagID: '_msafari_viewport',
                        closeButtonSelector: '.close'
                    });
                }
            });
        </script>
        
        <?php print $this->render("Cookies/banner_html.php"); ?>
    </body>
</html>