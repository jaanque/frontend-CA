<?php
# --------------------------------------------------------------------------------------------
#               Pawtucket2: Cataloguing system for CollectiveAccess
#               Version 2.0 - Configuration File
# --------------------------------------------------------------------------------------------

# ========================================================================
# 1. CONFIGURACIÓN DE RUTAS Y URLs
# ========================================================================
if (!defined("__CA_SITE_HOSTNAME__")) {
    # CORRECCIÓN: Se añade el puerto 8080 para que la navegación interna funcione
    define("__CA_SITE_HOSTNAME__", "81.0.58.152:8080");
}
if (!defined("__CA_URL_ROOT__")) {
    define("__CA_URL_ROOT__", "");
}
if (!defined("__CA_MEDIA_URL_ROOT__")) {
    # CORRECTO: Apuntando al puerto 3510 del backend para leer las imágenes
    define("__CA_MEDIA_URL_ROOT__", "/media/collectiveaccess"); 
}

# ========================================================================
# 2. CONFIGURACIÓN DE BASE DE DATOS
# ========================================================================
if (!defined("__CA_DB_HOST__")) {
    define("__CA_DB_HOST__", '81.0.58.152');
}
if (!defined("__CA_DB_PORT__")) {
    define("__CA_DB_PORT__", '3306');
}
if (!defined("__CA_DB_USER__")) {
    define("__CA_DB_USER__", 'admin');
}
if (!defined("__CA_DB_PASSWORD__")) {
    define("__CA_DB_PASSWORD__", 'ZBI!jjeu44826#HDE25');
}
if (!defined("__CA_DB_DATABASE__")) {
    define("__CA_DB_DATABASE__", 'collectiveaccess');
}

# ========================================================================
# 3. INFORMACIÓN GENERAL DE LA APLICACIÓN
# ========================================================================
if (!defined("__CA_APP_DISPLAY_NAME__")) {
    define("__CA_APP_DISPLAY_NAME__", "My First CollectiveAccess System");
}
if (!defined("__CA_LOG_DIR__")) {
    define("__CA_LOG_DIR__", __DIR__."/app/log");
}
if (!defined("__CA_ADMIN_EMAIL__")) {
    define("__CA_ADMIN_EMAIL__", 'info@put-your-domain-here.com');
}

# Configuración de zona horaria
date_default_timezone_set('America/New_York');

# ========================================================================
# 4. CONFIGURACIONES ADICIONALES DEL SISTEMA
# ========================================================================
if (!defined("__CA_QUEUE_ENABLED__")) {
    define("__CA_QUEUE_ENABLED__", 0);
}
if (!defined("__CA_DEFAULT_LOCALE__")) {
    define("__CA_DEFAULT_LOCALE__", "en_US");
}
define("__CA_USE_CLEAN_URLS__", 0);

if (!defined("__CA_APP_NAME__")) {
    define("__CA_APP_NAME__", "collectiveaccess");
}
if (!defined("__CA_GOOGLE_MAPS_KEY__")) {
    define("__CA_GOOGLE_MAPS_KEY__", "");
}
if (!defined("__CA_GOOGLE_RECAPTCHA_KEY__")) {
     define("__CA_GOOGLE_RECAPTCHA_KEY__", "");
}
if (!defined("__CA_GOOGLE_RECAPTCHA_SECRET_KEY__")) {
     define("__CA_GOOGLE_RECAPTCHA_SECRET_KEY__", "");
}

$_CA_THEMES_BY_DEVICE = [
    '_default_'     => 'default'
];

# ========================================================================
# 5. CACHÉ, SEGURIDAD Y DEPURACIÓN
# ========================================================================
if (!defined('__CA_CACHE_BACKEND__')) { 
    define('__CA_CACHE_BACKEND__', 'file');
}
if (!defined("__CA_DB_USE_SSL__")) {
    define("__CA_DB_USE_SSL__", false);
}
if (!defined("__CA_DB_SSL_VERIFY_CERT__")) {
    define("__CA_DB_SSL_VERIFY_CERT__", true);
}
if (!defined("__CA_DB_SSL_KEY__")) {
    define("__CA_DB_SSL_KEY__", null);
}
if (!defined("__CA_DB_SSL_CERTIFICATE__")) {
    define("__CA_DB_SSL_CERTIFICATE__", null);
}
if (!defined("__CA_DB_SSL_CA_CERTIFICATE__")) {
    define("__CA_DB_SSL_CA_CERTIFICATE__", null);
}
if (!defined("__CA_DB_SSL_CA_PATH__")) {
    define("__CA_DB_SSL_CA_PATH__", null);
}
if (!defined('__CA_ALLOW_INSTALLER_TO_OVERWRITE_EXISTING_INSTALLS__')) {
    define('__CA_ALLOW_INSTALLER_TO_OVERWRITE_EXISTING_INSTALLS__', false);
}
if (!defined('__CA_STACKTRACE_ON_EXCEPTION__')) {
    define('__CA_STACKTRACE_ON_EXCEPTION__', false);
}

# Cargar configuraciones finales del sistema
require(__DIR__."/app/helpers/post-setup.php");