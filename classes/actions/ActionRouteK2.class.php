<?php
/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Events for liveStreet 1.0.1
 *   Copyright © 2012 1099511627776@mail.ru
 *
 * --------------------------------------------------------
 *
 *   Contact e-mail: 1099511627776@mail.ru
 *
  ---------------------------------------------------------
*/


class PluginK2import_ActionRouteK2 extends ActionPlugin_Inherit_ActionError {

	protected function EventError() {
		$url = $this->getParam(0);
		$fullurl = $_SERVER['REQUEST_URI'];
		if(preg_match('/\/item\/(\d+)-.*?/i',$fullurl,$matches)){
			$k2id = $matches[1];
			if(!($oTopic = $this->Plugink2import_lstopic_getTopicByK2Id($k2id))){
				$this->PluginK2import_k2import_addPost($k2id);
				$this->PluginK2import_k2import_addComments($k2id);
				if($oTopic = $this->Plugink2import_lstopic_getTopicByK2Id($k2id)){
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: '.$oTopic->getUrl());				
					dump("I'm routing to:".$oTopic->getUrl());
					return;
				} else {
					parent::EventError();
				}
			} else {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: '.$oTopic->getUrl());
				dump("I'm routing to:".$oTopic->getUrl());
				return;
			}
		};
		parent::EventError();
	}
}

?>
