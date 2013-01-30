<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Copyright © 2012 1099511627776@mail.ru
 *
 * --------------------------------------------------------
 *
 *   Contact e-mail: 1099511627776@mail.ru
 *
  ---------------------------------------------------------
*/

class PluginSpize_ModuleLstopic extends Module
{
    protected $oMapper;

	public function Init(){
		$this->oMapper = Engine::GetMapper(__CLASS__, 'lstopic');
	}                       	

	public function getTopicByK2Id($k2_id){

		if($iId = $this->oMapper->getTopicByK2Id($k2_id)){
			return $this->Topic_getTopicById($iId);
		}
		return false;
	}

	public function setTopicK2Id($oTopic,$k2id){
		return $this->oMapper->setTopicK2Id($oTopic->getId(),$k2id);
	}

}

?>