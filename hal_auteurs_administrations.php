<?php
/**
 * Plugin HAL Auteurs
 *
 * (c) 2014 kent1
 * Distribue sous licence GPL
 *
 * Modification des tables
 * 
 * @package SPIP\HAL_Auteurs\Administration
 */

if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Installation ou mise à jour du plugin
 * 
 * Ajoute un champ hal sur la table spip_auteurs
 * 
 * @param string $nom_meta_base_version
 * 		Le nom de la meta d'installation
 * @param float $version_cible
 * 		La version vers laquelle installer
 * @return void
 */
function hal_auteurs_upgrade($nom_meta_base_version,$version_cible){

	$maj = array();
	
	$maj['create'] = array(
		array('maj_tables',array('spip_auteurs'))
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Désinstallation du plugin
 * 
 * On supprime : 
 * -* La meta d'installation
 * 
 * @param string $nom_meta_base_version
 * 		Le nom de la meta d'installation
 * @return void
 */
function hal_auteurs_vider_tables($nom_meta_base_version) {
	effacer_meta($nom_meta_base_version);
}

?>