<?php

/*

Plugin Name: Aki'leads - Informations du Site

Description: Permettant l'enregistrement des informations concernant le site comme : Nom, Adresse, Ville, Pays, Code postal, Contact, Numero Téléphone

Version: 1.0

Author: Aki'leads

License: GPL2

*/

class Informations_Site_theme_Plugin

{

    public function __construct()

    {
        include_once plugin_dir_path( __FILE__ ).'/install.php';
        include_once plugin_dir_path( __FILE__ ).'/uninstall.php';
        
        register_activation_hook(__FILE__, array('Informations_Site_theme_Install', 'install'));
        register_deactivation_hook(__FILE__, array('Informations_Site_theme_Uninstall', 'uninstall'));
        register_uninstall_hook(__FILE__, array('Informations_Site_theme_Uninstall', 'uninstall'));
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('init', array($this, 'register_script'));
        if(is_admin()){
            add_action('admin_init', array($this, 'register_settings'));
            
        }
        add_shortcode( 'site-infos', array($this, 'site_infos_output') );
    }
    
    /**
     * Appel aux elements de style
     */
    public function register_script() {
        
        if(isset($_GET['page']) && $_GET['page'] == 'information-site-theme'){

            wp_register_style( 'jquery-ui-css', plugins_url('/css/jquery-ui.css' , __FILE__));
            wp_enqueue_style( 'jquery-ui-css');
            
            wp_register_style( 'style', plugins_url('/css/style.css' , __FILE__));
            wp_enqueue_style( 'style');
            
            wp_register_style( 'jquery.fancybox-css', plugins_url('/css/jquery.fancybox.css' , __FILE__));
            wp_enqueue_style( 'jquery.fancybox-css');
            
            wp_register_script( 'jquery-1.12.4', plugins_url('/js/jquery-1.12.4.js' , __FILE__));
            wp_enqueue_script( 'jquery-1.12.4');
            
            wp_register_script( 'jquery-ui-js', plugins_url('/js/jquery-ui.js' , __FILE__));
            wp_enqueue_script( 'jquery-ui-js');
            
            wp_register_script( 'jquery.fancybox-js', plugins_url('/js/jquery.fancybox.js' , __FILE__));
            wp_enqueue_script( 'jquery.fancybox-js');
            
            wp_register_script( 'add', plugins_url('/js/add.js' , __FILE__));
            wp_enqueue_script( 'add');
        }
       
    }
    
    /**
     * Configuration des shortcodes pour les information du site
     * @param type $_tAttributs
     * @return type
     */
    public function site_infos_output( $_tAttributs ) {
        $_tAttributs = shortcode_atts( array(
            'infos' => false
        ), $_tAttributs );
        
        $zValue = get_option('site-infos-nom') ;
        $zKey = $_tAttributs['infos'] ;
        if(!empty(get_option($zKey))){
            $zValue = get_option($zKey) ;
        }
        
        return $zValue ;
    }
    
    public function add_admin_menu()
        {

            add_menu_page('Informations Site theme', 'Informations Site theme plugin', 'manage_options', 'information-site-theme', array($this, 'menu_html'));

        }
    public function menu_html()
        {

            echo '<h1>Configuration du plugin Informations Site </h1>';
            
            //Enregistrement
            if(isset($_POST['btn-agence-save'])){
                
                self::globaleEnregistementAgence($_POST) ;
                //echo '<pre>' ;print_r($_POST) ;
                
            }
            //Supression Agence
            if(isset($_POST['action']) && $_POST['action'] == 'delete_agence'){
                
                $iAgenceID = $_POST['id-agence'] ;
                
                self::deleteAgence($iAgenceID) ;
                
                exit() ;
            }
            $zMonUrl = admin_url( 'admin.php?page=information-site-theme' );
            //Recuperation des agences
            $toAgences = self::getAgenceRequete(array()) ;
            ?>
            <a href="#pop-add-agence" class="add-btn-url">AJouter un agence <span class="icon icon-plus"></span></a>
            <div class="clear-both"></div>
            <form method="post" action="<?php echo $zMonUrl ;?>" id="formSiteInfos">
                <div id="accordion">
                    <?php foreach($toAgences as  $oAgence) : ;?>
                    <?php
                        $iIsPrincipale = $oAgence->is_principal ;
                        //Modification informations option
                        $zSiegeSociale = trim($oAgence->siege_social) ;
                        if($iIsPrincipale == 1){
                            update_option("site-infos-nom", $oAgence->nom);
                            update_option("site-infos-adresse", $oAgence->adresse);
                            update_option("site-infos-ville", $oAgence->ville);
                            update_option("site-infos-code-postal", $oAgence->code_postal);
                            update_option("site-infos-pays", $oAgence->pays);
                            update_option("site-infos-contact-email-1", $oAgence->email_1);
                            update_option("site-infos-contact-email-2", $oAgence->email_2);
                            update_option("site-infos-contact-tel-1", $oAgence->telephone_1);
                            update_option("site-infos-contact-tel-2", $oAgence->telephone_2);
                            update_option("site-infos-mum-departement", $oAgence->numero_departement);
                            update_option("site-infos-adresse-2", '');
                            
                            
                            if(empty($zSiegeSociale)){
                                $zSiegeSociale = $oAgence->adresse.' '.$oAgence->ville.' '.$oAgence->code_postal.' '.$oAgence->pays ;
                            }
                            //Mention legal
                            update_option( 'site-infos-mention-legal-responsable', !empty($oAgence->responsable) ? $oAgence->responsable : '--' ) ;
                            update_option( 'site-infos-mention-legal-raison_social', !empty($oAgence->raison_social) ? $oAgence->raison_social : '--') ;
                            update_option( 'site-infos-mention-legal-siege_social', $zSiegeSociale ) ;
                            update_option( 'site-infos-mention-legal-rcs', !empty($oAgence->rcs) ? $oAgence->rcs : '--') ;
                            update_option( 'site-infos-mention-legal-forme_sociale', !empty($oAgence->forme_sociale) ? $oAgence->forme_sociale : '--' ) ;
                            update_option( 'site-infos-mention-legal-carte_professionelle', !empty($oAgence->carte_professionelle) ? $oAgence->carte_professionelle : '--' ) ;
                            update_option( 'site-infos-mention-legal-prefecture_delivrance_carte', !empty($oAgence->prefecture_delivrance_carte) ? $oAgence->prefecture_delivrance_carte : '--' ) ;
                            update_option( 'site-infos-mention-legal-capital', !empty($oAgence->capital) ? $oAgence->capital : '--' ) ;
                            update_option( 'site-infos-mention-legal-caisse_garantie_financiere', !empty($oAgence->caisse_garantie_financiere) ? $oAgence->caisse_garantie_financiere : '--' ) ;
                        }
                        
                        $zPrincipale = ($iIsPrincipale == 1 ? ' (Agence Principale)' : '') ;
                    ?>
                    <h3>
                        <strong><?php echo $oAgence->nom ;?></strong><?php echo $zPrincipale ;?>
                        
                        <?php echo (empty($zPrincipale) ? '<span num="'.$oAgence->id.'" ajax-url="'.$zMonUrl.'" class="btn-action-suppr icon-minus">X</span>' : '' ) ;?>
                    </h3>
                    <div>
                        <input type="hidden" name="id[]" value="<?php echo $oAgence->id ;?>">
                        <input type="hidden" name="parent_id[]" value="<?php echo $oAgence->parent_id ;?>">
                        <input type="hidden" name="is_active[]" value="1">
                        <input type="hidden" id="check-value-<?php echo $oAgence->id ;?>" name="is_principal[]" value="<?php echo $oAgence->is_principal ;?>">
                        <div class="clear-both"></div>
                        <label class="radio label-radio" style="float:left;">Agence Principale :</label>
                        <label class="radio" style="float:left;">
                            <input class="check-is-principale" num="<?php echo $oAgence->id ;?>" id="check-<?php echo $oAgence->id ;?>" type="checkbox" value="<?php echo $oAgence->is_principal ;?>" <?php echo ($oAgence->is_principal == 1 ? 'checked' : '') ; ?>/> 
                        </label>
                        <div class="clear-both"></div>
                        <div class="tabs">
                            <ul>
                                <li><a href="#info-principale">Information General</a></li>
                                <li><a href="#mention-legal">Mentions Légales</a></li>
                            </ul>
                            <div id="info-principale">
                                <div>
                                    <p>
                                        <label>Nom de l'agence : </label>
                                        <input type="text" name="nom[]" value="<?php echo $oAgence->nom ; ?>" title="Nom de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Adresse: </label>
                                        <input type="text" name="adresse[]" value="<?php echo $oAgence->adresse ; ?>" title="Adresse 1 de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Ville : </label>
                                        <input type="text" name="ville[]" value="<?php echo $oAgence->ville ; ?>" title="Ville de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Code postal : </label>
                                        <input type="text" name="code_postal[]" value="<?php echo $oAgence->code_postal ; ?>" title="Code postal de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Numéro du département : </label>
                                        <input type="text" name="numero_departement[]" value="<?php echo $oAgence->numero_departement ; ?>" title="numéro du département de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Pays : </label>
                                        <input type="text" name="pays[]" value="<?php echo $oAgence->pays ; ?>" title="Pays de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Email 1 : </label>
                                        <input type="text" name="email_1[]" value="<?php echo $oAgence->email_1 ; ?>" title="Contact Email 1 de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Email 2 : </label>
                                        <input type="text" name="email_2[]" value="<?php echo $oAgence->email_2 ; ?>" title="Contact Email 2 de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Numéro téléphone 1 : </label>
                                        <input type="text" name="telephone_1[]" value="<?php echo $oAgence->telephone_1 ; ?>" title="Contact Téléphone 1 de l'agence"/>
                                    </p>
                                    <p>
                                        <label>Numéro téléphone 2 : </label>
                                        <input type="text" name="telephone_2[]" value="<?php echo $oAgence->telephone_2 ; ?>" title="Contact Téléphone 2 de l'agence"/>
                                    </p>
                                </div>
                            </div>
                            <div id="mention-legal">
                                <div>
                                    <p>
                                        <label>Responsable de la publication : </label>
                                        <input type="text" name="responsable[]" value="<?php echo $oAgence->responsable ; ?>" title="Responsable de la publication"/>
                                    </p>
                                    <p>
                                        <label>Raison sociale : </label>
                                        <input type="text" name="raison_social[]" value="<?php echo $oAgence->raison_social ; ?>" title="Raison sociale"/>
                                    </p>
                                    <p>
                                        <label>Siège social : </label>
                                        <input type="text" name="siege_social[]" value="<?php echo $zSiegeSociale ; ?>" title="Siège social"/>
                                    </p>
                                    <p>
                                        <label>RCS : </label>
                                        <input type="text" name="rcs[]" value="<?php echo $oAgence->rcs ; ?>" title="RCS"/>
                                    </p>
                                    <p>
                                        <label>Forme sociale : </label>
                                        <input type="text" name="forme_sociale[]" value="<?php echo $oAgence->forme_sociale ; ?>" title="Forme sociale"/>
                                    </p>
                                    <p>
                                        <label>Carte professionnelle : </label>
                                        <input type="text" name="carte_professionelle[]" value="<?php echo $oAgence->carte_professionelle ; ?>" title="Carte professionnelle"/>
                                    </p>
                                    <p>
                                        <label>Préfecture de délivrance de la carte professionnelle : </label>
                                        <input type="text" name="prefecture_delivrance_carte[]" value="<?php echo $oAgence->prefecture_delivrance_carte ; ?>" title="Préfecture de délivrance de la carte professionnelle"/>
                                    </p>
                                    <p>
                                        <label>Capital : </label>
                                        <input type="text" name="capital[]" value="<?php echo $oAgence->capital ; ?>" title="Capital"/>
                                    </p>
                                    <p>
                                        <label>Caisse garantie financière : </label>
                                        <input type="text" name="caisse_garantie_financiere[]" value="<?php echo $oAgence->caisse_garantie_financiere ; ?>" title="Caisse garantie financière"/>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <?php endforeach; ?>
                    
                </div>
                <?php settings_fields('site_infos_theme_settings') ?>
                
                <div style="clear: both;"></div>
                <?php submit_button('Enregistrer', 'primary', 'btn-agence-save'); ?>
            </form>
            <div class="pop-hide" style="display:none;">
                <div class="pop-in" id="pop-add-agence">
                    <form method="post" action="<?php echo $zMonUrl ;?>" id="formSiteInfos">
                            
                            <h3><strong>Nouvel agence</strong></h3>
                            <div>
                                <input type="hidden" name="id[]" value="">
                                <input type="hidden" name="parent_id[]" value="0">
                                <input type="hidden" name="is_principal[]" value="0">
                                <input type="hidden" name="is_active[]" value="1">
                                <!--div class="clear-both"></div>
                                <label class="radio label-radio" style="float:left;">Actif :</label>
                                <label class="radio" style="float:left;">
                                    <input type="radio" name="is_active[]" value="1" checked="checked"/> Oui  
                                </label>
                                <label class="radio" style="float:left;">
                                    <input type="radio" name="is_active[]" value="0" /> Non 
                                </label-->
                                <div class="clear-both"></div>
                                <div class="tabs">
                                    <ul>
                                        <li><a href="#info-principale">Information General</a></li>
                                        <li><a href="#mention-legal">Mentions Légales</a></li>
                                    </ul>
                                    <div id="info-principale">
                                        <div>
                                            <p>
                                                <label>Nom de l'agence : </label>
                                                <input type="text" name="nom[]" value="" title="Nom de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Adresse: </label>
                                                <input type="text" name="adresse[]" value="" title="Adresse 1 de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Ville : </label>
                                                <input type="text" name="ville[]" value="" title="Ville de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Code postal : </label>
                                                <input type="text" name="code_postal[]" value="" title="Code postal de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Numéro du département : </label>
                                                <input type="text" name="numero_departement[]" value="" title="numéro du département de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Pays : </label>
                                                <input type="text" name="pays[]" value="" title="Pays de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Email 1 : </label>
                                                <input type="text" name="email_1[]" value="" title="Contact Email 1 de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Email 2 : </label>
                                                <input type="text" name="email_2[]" value="" title="Contact Email 2 de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Numéro téléphone 1 : </label>
                                                <input type="text" name="telephone_1[]" value="" title="Contact Téléphone 1 de l'agence"/>
                                            </p>
                                            <p>
                                                <label>Numéro téléphone 2 : </label>
                                                <input type="text" name="telephone_2[]" value="" title="Contact Téléphone 2 de l'agence"/>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="mention-legal">
                                        <div>
                                            <p>
                                                <label>Responsable de la publication : </label>
                                                <input type="text" name="responsable[]" value="" title="Responsable de la publication"/>
                                            </p>
                                            <p>
                                                <label>Raison sociale : </label>
                                                <input type="text" name="raison_social[]" value="" title="Raison sociale"/>
                                            </p>
                                            <p>
                                                <label>Siège social : </label>
                                                <input type="text" name="siege_social[]" value="" title="Siège social"/>
                                            </p>
                                            <p>
                                                <label>RCS : </label>
                                                <input type="text" name="rcs[]" value="" title="RCS"/>
                                            </p>
                                            <p>
                                                <label>Forme sociale : </label>
                                                <input type="text" name="forme_sociale[]" value="" title="Forme sociale"/>
                                            </p>
                                            <p>
                                                <label>Carte professionnelle : </label>
                                                <input type="text" name="carte_professionelle[]" value="" title="Carte professionnelle"/>
                                            </p>
                                            <p>
                                                <label>Préfecture de délivrance de la carte professionnelle : </label>
                                                <input type="text" name="prefecture_delivrance_carte[]" value="" title="Préfecture de délivrance de la carte professionnelle"/>
                                            </p>
                                            <p>
                                                <label>Capital : </label>
                                                <input type="text" name="capital[]" value="" title="Capital"/>
                                            </p>
                                            <p>
                                                <label>Caisse garantie financière : </label>
                                                <input type="text" name="caisse_garantie_financiere[]" value="" title="Caisse garantie financière"/>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php settings_fields('site_infos_theme_settings') ?>

                        <div style="clear: both;"></div>
                        <?php submit_button('Enregistrer', 'primary', 'btn-agence-save'); ?>
                    </form>
                </div>
            </div>
            <!-- 1- Message d'alerte delete agence-->
            <div id="confirm-popup-footer-delete-agence" title="Confirmation" style="display: none ;"> 

              <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Voulez-vous supprimer cet agence ?</p>
            </div>
            <!-- 2- Message d'alerte demande-->
            <div id="confirm-popup-footer-choice-agence-principale" title="Confirmation" style="display: none ;"> 

              <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Vous devez choisir un agence principale ! ?</p>
            </div>
           
        <?php
        }
    public function register_settings()
    {
        

    }
    
    /**
     * Requete general de selection
     * @global type $wpdb
     * @param type $_toParams
     * @return type
     */
    public static function getAgenceRequete($_toParams){
        
        global $wpdb;
        
        $zSQL = "SELECT * FROM ".$wpdb->prefix."agences " ;
        $zSQL .= "WHERE 1 = 1 " ;
        $zSQL .= (array_key_exists('is_principal', $_toParams)) ? " AND is_principal = '" . trim($_toParams['is_principal']) . "' " : "" ;
        $zSQL .= (array_key_exists('is_active', $_toParams)) ? " AND is_active = '" . trim($_toParams['is_active']) . "' " : "" ;
        
        return $wpdb->get_results( $zSQL ) ;
        
    }
    
    /**
     * Recuperation du nombre d'Agence
     * @global type $wpdb
     * @param type $_toParams
     * @return type
     */
    public static function countAgence($_toParams){
        
        global $wpdb;
        
        $zSQL = "SELECT COUNT(*) as count FROM ".$wpdb->prefix."agences " ;
        $zSQL .= "WHERE 1 = 1 " ;
        $zSQL .= (array_key_exists('is_principal', $_toParams)) ? " AND is_principal = '" . trim($_toParams['is_principal']) . "' " : "" ;
        $zSQL .= (array_key_exists('is_active', $_toParams)) ? " AND is_active = '" . trim($_toParams['is_active']) . "' " : "" ;
        
        return $wpdb->get_row( $zSQL ) ;
    }

    /**
     * Enregistrement d'un nouvel / mis à jour Agence
     * @param type $_toParams
     * @return boolean
     */
    public static function globaleEnregistementAgence($_toParams){
        
        $tiIDs = $_toParams['id'] ;
        foreach ($tiIDs as $iKey => $iID){
            
            $toSaveAgenceParams['id'] = $iID ;
            $toSaveAgenceParams['nom'] = $_toParams['nom'][$iKey] ;
            $toSaveAgenceParams['adresse'] = $_toParams['adresse'][$iKey] ;
            $toSaveAgenceParams['ville'] = $_toParams['ville'][$iKey] ;
            $toSaveAgenceParams['code_postal'] = $_toParams['code_postal'][$iKey] ;
            $toSaveAgenceParams['numero_departement'] = $_toParams['numero_departement'][$iKey] ;
            $toSaveAgenceParams['pays'] = $_toParams['pays'][$iKey] ;
            $toSaveAgenceParams['email_1'] = $_toParams['email_1'][$iKey] ;
            $toSaveAgenceParams['email_2'] = $_toParams['email_2'][$iKey] ;
            $toSaveAgenceParams['telephone_1'] = $_toParams['telephone_1'][$iKey] ;
            $toSaveAgenceParams['telephone_2'] = $_toParams['telephone_2'][$iKey] ;
            $toSaveAgenceParams['responsable'] = $_toParams['responsable'][$iKey] ;
            $toSaveAgenceParams['raison_social'] = $_toParams['raison_social'][$iKey] ;
            $toSaveAgenceParams['siege_social'] = $_toParams['siege_social'][$iKey] ;
            $toSaveAgenceParams['rcs'] = $_toParams['rcs'][$iKey] ;
            $toSaveAgenceParams['forme_sociale'] = $_toParams['forme_sociale'][$iKey] ;
            $toSaveAgenceParams['carte_professionelle'] = $_toParams['carte_professionelle'][$iKey] ;
            $toSaveAgenceParams['prefecture_delivrance_carte'] = $_toParams['prefecture_delivrance_carte'][$iKey] ;
            $toSaveAgenceParams['capital'] = $_toParams['capital'][$iKey] ;
            $toSaveAgenceParams['caisse_garantie_financiere'] = $_toParams['caisse_garantie_financiere'][$iKey] ;
            $toSaveAgenceParams['is_principal'] = isset($_toParams['is_principal'][$iKey]) ? $_toParams['is_principal'][$iKey] : 2 ;
            $toSaveAgenceParams['parent_id'] = $_toParams['parent_id'][$iKey] ;
            $toSaveAgenceParams['is_active'] = isset($_toParams['is_active'][$iKey]) ? 1 : 0 ;
            
            if(!empty($iID)){
              //MIS à Jour  
                
              self::updateAgence($toSaveAgenceParams) ;
            }else{
               //Nouvel enregistrement 
               self::addAgence($toSaveAgenceParams) ;
            }
        }
        
        return true ;
    }
    
    /**
     * Mettre tous les agences en Non Agence principale
     * @global type $wpdb
     * @return type
     */
    public static function resetAgencePricipale(){
        
        global $wpdb;
        
        $zSQL = "UPDATE ".$wpdb->prefix."agences " ;
        $zSQL .= "SET" ;
        $zSQL .= " is_principal = 0" ;
        
        return $wpdb->query( $zSQL ) ;
    }
    
    /**
     * Get Agence By ID
     * @global type $wpdb
     * @param type $_iIdAgence
     * @return type
     */
    public static function getAgenceById($_iIdAgence){
        
        global $wpdb;
        
        $zSQL = "SELECT * FROM ".$wpdb->prefix."agences WHERE id = " . (int)$_iIdAgence ;
        
        return $wpdb->get_row( $zSQL ) ;
    }

    /**
     * Suppression Agence
     * @global type $wpdb
     * @param type $_iIdAgence
     */
    public static function deleteAgence($_iIdAgence){
        
        global $wpdb;
        
        $zSql = "DELETE FROM " . $wpdb->prefix ."agences WHERE id = " . (int)$_iIdAgence ;
        
        $wpdb->query( $zSql ) ;
    }

    /**
     * Enregistrement nouvel agence
     * @global type $wpdb
     * @param type $_toParams
     * @return type
     */
    public static function addAgence($_toParams){
        
        global $wpdb;
        
        $zSQL = "INSERT INTO ".$wpdb->prefix."agences " ;
        $zSQL .= "SET" ;
        $zSQL .= " nom = '".$_toParams['nom']."'," ;
        $zSQL .= " adresse = '".$_toParams['adresse']."'," ;
        $zSQL .= " ville = '".$_toParams['ville']."'," ;
        $zSQL .= " code_postal = '".$_toParams['code_postal']."'," ;
        $zSQL .= " numero_departement = '".$_toParams['numero_departement']."'," ;
        $zSQL .= " pays = '".$_toParams['pays']."'," ;
        $zSQL .= " email_1 = '".$_toParams['email_1']."'," ;
        $zSQL .= " email_2 = '".$_toParams['email_2']."'," ;
        $zSQL .= " telephone_1 = '".$_toParams['telephone_1']."'," ;
        $zSQL .= " telephone_2 = '".$_toParams['telephone_2']."'," ;
        $zSQL .= " responsable = '".$_toParams['responsable']."'," ;
        $zSQL .= " raison_social = '".$_toParams['raison_social']."'," ;
        $zSQL .= " siege_social = '".$_toParams['siege_social']."'," ;
        $zSQL .= " rcs = '".$_toParams['rcs']."'," ;
        $zSQL .= " forme_sociale = '".$_toParams['forme_sociale']."'," ;
        $zSQL .= " carte_professionelle = '".$_toParams['carte_professionelle']."'," ;
        $zSQL .= " prefecture_delivrance_carte = '".$_toParams['prefecture_delivrance_carte']."'," ;
        $zSQL .= " capital = '".$_toParams['capital']."'," ;
        $zSQL .= " caisse_garantie_financiere = '".$_toParams['caisse_garantie_financiere']."'," ;
        $zSQL .= " is_principal = '".$_toParams['is_principal']."'," ;
        $zSQL .= " parent_id = '".$_toParams['parent_id']."'," ;
        $zSQL .= " is_active = '".$_toParams['is_active']."'," ;
        $zSQL .= " dateAdd = NOW()," ;
        $zSQL .= " dateUp = NOW()" ;
        
        return $wpdb->query( $zSQL ) ;
    }
    
    /**
     * Enregistrement mis à jour agence
     * @global type $wpdb
     * @param type $_toParams
     * @return type
     */
    public static function updateAgence($_toParams){
        
        global $wpdb;
        
        $zSQL = "UPDATE ".$wpdb->prefix."agences " ;
        $zSQL .= "SET" ;
        $zSQL .= " nom = '".$_toParams['nom']."'," ;
        $zSQL .= " adresse = '".$_toParams['adresse']."'," ;
        $zSQL .= " ville = '".$_toParams['ville']."'," ;
        $zSQL .= " code_postal = '".$_toParams['code_postal']."'," ;
        $zSQL .= " numero_departement = '".$_toParams['numero_departement']."'," ;
        $zSQL .= " pays = '".$_toParams['pays']."'," ;
        $zSQL .= " email_1 = '".$_toParams['email_1']."'," ;
        $zSQL .= " email_2 = '".$_toParams['email_2']."'," ;
        $zSQL .= " telephone_1 = '".$_toParams['telephone_1']."'," ;
        $zSQL .= " telephone_2 = '".$_toParams['telephone_2']."'," ;
        $zSQL .= " responsable = '".$_toParams['responsable']."'," ;
        $zSQL .= " raison_social = '".$_toParams['raison_social']."'," ;
        $zSQL .= " siege_social = '".$_toParams['siege_social']."'," ;
        $zSQL .= " rcs = '".$_toParams['rcs']."'," ;
        $zSQL .= " forme_sociale = '".$_toParams['forme_sociale']."'," ;
        $zSQL .= " carte_professionelle = '".$_toParams['carte_professionelle']."'," ;
        $zSQL .= " prefecture_delivrance_carte = '".$_toParams['prefecture_delivrance_carte']."'," ;
        $zSQL .= " capital = '".$_toParams['capital']."'," ;
        $zSQL .= " caisse_garantie_financiere = '".$_toParams['caisse_garantie_financiere']."'," ;
        $zSQL .= " is_principal = ".$_toParams['is_principal']."," ;
        $zSQL .= " parent_id = ".$_toParams['parent_id']."," ;
        $zSQL .= " is_active = ".$_toParams['is_active']."," ;
        $zSQL .= " dateUp = NOW()" ;
        $zSQL .= " WHERE id = ".$_toParams['id']."" ;
        
        return $wpdb->query( $zSQL ) ;
    }
}

new Informations_Site_theme_Plugin() ;

?>
