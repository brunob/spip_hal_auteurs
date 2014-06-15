<?php
/**
 * Plugin Licence
 * (c) 2007-2013 fanouch
 * Distribue sous licence GPL
 * 
 * @package SPIP\Licences\Pipelines
 */

if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Insertion dans le pipeline editer_contenu_objet (SPIP)
 * 
 * Ajout dans le formulaire d'édition de document du sélecteur de licence
 * 
 * @pipeline editer_contenu_obje
 * @param array $flux 
 * 		Le contexte du pipeline
 * @return array  $flux 
 * 		Le contexte du pipeline complété
 */
function hal_auteurs_editer_contenu_objet($flux){
	if(in_array($flux['args']['type'],array('auteur'))){
		if(preg_match(",<li [^>]*class=[\"']editer editer_bio.*>(.*)<\/li>,Uims",$flux['data'],$regs)){
			$ajouts = recuperer_fond('inclure/saisie_hal_auteurs',$flux['args']['contexte']);
			$flux['data'] = str_replace($regs[0],$regs[0].$ajouts,$flux['data']);
		}
	}
	return $flux;
}

/**
 * Ajoute le champ hal sur la visualisation de l'auteur
 * 
 * @pipeline afficher_contenu_objet
 * @param array $flux 
 * 		Données du pipeline
 * @return array $flux
 *		Données du pipeline modifiées
 */ 
function hal_auteurs_afficher_contenu_objet($flux){
	if($flux['args']['type'] == "auteur"){
		$flux['data'] .= recuperer_fond('inclure/vue_hal_auteur',$flux['args']['contexte']);
	}
	return $flux;
}
?>