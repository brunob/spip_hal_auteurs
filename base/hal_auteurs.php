<?php
/**
 * Plugin HAL Auteurs
 * (c) 2014 kent1
 * Distribue sous licence GPL
 * 
 * Déclaration du champ hal supplémentaire sur les auteurs
 * 
 * @package SPIP\HAL_Auteurs\Pipelines
 */
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Insertion dans le pipeline declarer_tables_objets_sql (SPIP)
 * 
 * On ajoute le champ hal à la table spip_auteurs
 * 
 * @param array $tables
 * 		Le tableau des objets déclarés
 * @return array $tables
 * 		Le tableau des objets déclarés complété
 */
function hal_auteurs_declarer_tables_objets_sql($tables){
	$tables['spip_auteurs']['field']['hal'] = "text NOT NULL DEFAULT ''";
	$tables['spip_auteurs']['champs_editables'][] = "hal";
	$tables['spip_auteurs']['champs_versionnes'][] = "hal";
	return $tables;
}
