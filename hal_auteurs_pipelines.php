<?php
/**
 * Plugin Hal auteurs
 * (c) 2014 kent1
 * Distribue sous licence GPL
 * 
 * @package SPIP\Hal auteurs\Pipelines
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
		include_spip('inc/autoriser');
		if(autoriser('modifierextra_hal', 'auteur', $flux['args']['contexte']['id_auteur'], '', array(
			'type' => 'auteur',
			'id_objet' => $flux['args']['contexte']['id_auteur'],
			'contexte' => isset($args['contexte']) ? $args['contexte'] : array(),
			'table' => 'spip_auteurs',
			'champ' => 'hal',
		)) && preg_match(",<li [^>]*class=[\"']editer editer_bio.*>(.*)<\/li>,Uims",$flux['data'],$regs)){
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

/**
 * Vérifier la valeur du champ HAL
 * 
 * @pipeline formulaire_verifier
 * @param array $flux 
 * 		Données du pipeline
 * @return array $flux
 *		Données du pipeline modifiées
 */ 
function hal_auteurs_formulaire_verifier($flux){
	if($flux['args']['form'] == "editer_auteur"){
		if(($hal = _request('hal')) && strlen($hal) > 1){
			if(!is_numeric($hal)){
				$hals = explode(',',$hal);
				foreach($hals as $hal){
					if(!is_numeric($hal) || $hal <= 0){
						$flux['hal'] = _T('hal_auteurs:erreur_champ_hal');
						break;
					}
				}
			}
		}
	}
	return $flux;
}

/**
 * Créer et associer un hal à l'auteur lors de la modification champ HAL
 * 
 * @pipeline post_edition
 * @param array $flux 
 * 		Données du pipeline
 * @return array $flux
 *		Données du pipeline modifiées
 */ 
function hal_auteurs_post_edition($flux){
	if($flux['args']['table'] == "spip_auteurs" && isset($flux['data']['hal'])){
		if(isset($flux['data']['hal'])){
			$hals = array();
			if(is_numeric($flux['data']['hal']) && $flux['data']['hal'] > 0)
				$hals[] = $flux['data']['hal'];
			else
				$hals = explode(',',$flux['data']['hal']);
			
			$hals_auteurs = array();
			$hals_test = sql_select('hal.id_hal','spip_hals as hal LEFT JOIN spip_auteurs_liens as lien ON lien.objet="hal" AND lien.id_objet=hal.id_hal','lien.id_auteur='.intval($flux['args']['id_objet']));
			while($hal = sql_fetch($hals_test)){
				$hals_auteurs[$hal['id_hal']] = $hal['id_hal'];
			}
			include_spip('action/editer_hal');
			include_spip('action/editer_liens');
			foreach($hals as $hal){
				if(is_numeric($hal) && $hal > 0){
					$id_hal = sql_fetsel('statut,id_hal','spip_hals','authid = '.intval($hal));
					$set=array('authid'=>$hal,'statut'=>'publie');
					if(!$id_hal){
						$set['titre'] = sql_getfetsel('nom','spip_auteurs','id_auteur='.intval($flux['args']['id_objet']));
						$id_hal = hal_inserer();
						if(isset($hals_auteurs[$hal['id_hal']]))
							unset($hals_auteurs[$hal['id_hal']]);
					}
					else{
						$id_hal = $id_hal['id_hal'];
						if(isset($hals_auteurs[$id_hal]))
							unset($hals_auteurs[$id_hal]);
					}
					$err = hal_modifier($id_hal,$set);
					objet_associer(array('auteur'=>$flux['args']['id_objet']), array('hal'=>$id_hal));
				}
			}
			if(count($hals_auteurs) > 0){
				$set = array('statut' => 'poubelle');
				foreach($hals_auteurs as $id_hal){
					$err = hal_modifier($id_hal,$set);
				}
			}
			
		}
		
	}
	return $flux;
}
?>