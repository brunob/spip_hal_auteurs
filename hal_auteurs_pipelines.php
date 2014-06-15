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
?>