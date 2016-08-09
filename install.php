<?php

class Informations_Site_theme_Install
{
    
    public static function install()
    {
        
        add_option( 'site-infos-nom', 'Nom Agence' ) ;
        add_option( 'site-infos-adresse', 'Adresse' ) ;
        add_option( 'site-infos-ville', 'Ville' ) ;
        add_option( 'site-infos-code-postal', 'Code Postal' ) ;
        add_option( 'site-infos-pays', 'France' ) ;
        add_option( 'site-infos-contact-email-1', 'Email' ) ;
        add_option( 'site-infos-contact-email-2', '' ) ;
        add_option( 'site-infos-contact-tel-1', 'Tel' ) ;
        add_option( 'site-infos-contact-tel-2', '' ) ;
        add_option( 'site-infos-mum-departement', '0' ) ;
        add_option( 'site-infos-adresse-2', '' ) ;
        //Mention legal
        add_option( 'site-infos-mention-legal-responsable', '' ) ;
        add_option( 'site-infos-mention-legal-raison_social', '' ) ;
        add_option( 'site-infos-mention-legal-siege_social', '' ) ;
        add_option( 'site-infos-mention-legal-rcs', '' ) ;
        add_option( 'site-infos-mention-legal-forme_sociale', '' ) ;
        add_option( 'site-infos-mention-legal-carte_professionelle', '' ) ;
        add_option( 'site-infos-mention-legal-prefecture_delivrance_carte', '' ) ;
        add_option( 'site-infos-mention-legal-capital', '' ) ;
        add_option( 'site-infos-mention-legal-caisse_garantie_financiere', '' ) ;
        
        global $wpdb;
        
        $wpdb->query("
            CREATE TABLE {$wpdb->prefix}agences (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(250) NOT NULL,
                `adresse` varchar(250) DEFAULT NULL,
                `ville` varchar(250) DEFAULT NULL,
                `code_postal` varchar(250) DEFAULT NULL,
                `numero_departement` int(5) DEFAULT NULL,
                `pays` varchar(80) DEFAULT NULL,
                `email_1` varchar(100) DEFAULT NULL,
                `email_2` varchar(100) DEFAULT NULL,
                `telephone_1` varchar(20) DEFAULT NULL,
                `telephone_2` varchar(20) DEFAULT NULL,
                `responsable` varchar(250) DEFAULT NULL,
                `raison_social` varchar(250) DEFAULT NULL,
                `siege_social` varchar(250) DEFAULT NULL,
                `rcs` varchar(250) DEFAULT NULL,
                `forme_sociale` varchar(250) DEFAULT NULL,
                `carte_professionelle` varchar(250) DEFAULT NULL,
                `prefecture_delivrance_carte` varchar(250) DEFAULT NULL,
                `capital` varchar(250) DEFAULT NULL,
                `caisse_garantie_financiere` varchar(250) DEFAULT NULL,
                `is_principal` smallint(1) NOT NULL DEFAULT '0',
                `parent_id` int(11) NOT NULL DEFAULT '0',
                `is_active` smallint(1) NOT NULL DEFAULT '1',
                `dateAdd` datetime DEFAULT NULL,
                `dateUp` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
              
              
         ");
        
        //Insertion Agence par defaut
        $wpdb->query("
                        INSERT INTO {$wpdb->prefix}agences
                            SET
                            nom = 'Nom Agence',
                            adresse = 'Adresse',
                            ville = 'Ville',
                            code_postal = '111',
                            numero_departement = 0,
                            pays = 'France',
                            email_1 = 'Email',
                            telephone_1 = 'Tel',
                            is_principal = 1,
                            is_active = 1,
                            dateAdd = NOW(),
                            dateUp = NOW()
                    ");
    }

}

?>
