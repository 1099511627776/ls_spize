<?php

class PluginSpize_ModuleLstopic_MapperLstopic extends Mapper
{

    public function getTopicByK2Id($k2_id)
    {
		$sql = "SELECT topic_id FROM ".Config::Get('db.table.topic')." WHERE spz_id = ?d";
		if ($aRows = $this->oDb->select($sql,$k2_id)) {
		    foreach ($aRows as $aRow) {
				return $aRow['topic_id'];
		    }
		    return $aReturn;
		}
		return false;
	}

	public function setTopicK2Id($topic_id,$k2id) {
		$sql = "UPDATE ".Config::Get('db.table.topic')." SET spz_id = ?d WHERE topic_id = ?d";
		if($this->oDb->query($sql,$k2id,$topic_id)){
			return true;
		}
		return false;
	}

}
?>