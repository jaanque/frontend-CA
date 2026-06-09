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
        </div></div></div></div></div><footer id="footer" role="contentinfo">
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
                            <?php print caNavLink($this->request, "Gestió de Cookies", "", "", "Cookies", "manage"); ?>
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