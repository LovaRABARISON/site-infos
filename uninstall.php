<?php

class Informations_Site_theme_Uninstall
{
    
    public static function uninstall()
    {
        //Parametre par defaut
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}agences;");
    }

}

?>
