<?
class PluginSpize_Modulespizeimport_Mapperspizeimport extends Mapper
{

    public function getUserList($prefix)
    {
	$sql = "SELECT 
		u.user_id as 'id',
		u.name,
	    u.login as 'login',
		u.email
		FROM ".$prefix."_users u";
	$aReturn = array();
	if ($aRows = $this->oDb->select($sql)) {
	    foreach ($aRows as $aRow) {
		$aReturn[$aRow['id']] = $aRow;
	    }
	    return $aReturn;
	}
	return false;
    }

    public function getUser($prefix,$uid)
    {
	$sql = "SELECT 
		u.user_id as 'id',
		u.name,
	    u.login as 'login',
		u.email,
		IF(u.sex = 1,'m',IF(u.sex=2,'f','other')) as 'gender',
		u.about as 'desc',
		FROM_UNIXTIME(u.registered_unixtime) as 'registerDate'
		FROM ".$prefix."_users u 
		where u.user_id = ".intval($uid);

	if ($aRows = $this->oDb->select($sql)) {
	    foreach ($aRows as $aRow) {
			$aReturn[$aRow['id']] = $aRow;
	    }
	    return $aReturn;
	}

	return false;
    }

    public function getCat($prefix,$cid) {
    	$sql = "SELECT 
	    			b.blog_id as 'id',
		    		b.blog_title as 'name',
		    		b.blog_description as 'description',
		    		b.blog_latin as 'alias',
		    		u.login as 'login'
    			FROM ".$prefix."_blogs b left outer join ".$prefix."_users u ON u.user_id = b.blog_admin_id where blog_id=".intval($cid);
		if ($aRows = $this->oDb->select($sql)) {
		    foreach ($aRows as $aRow) {
				$aReturn[$aRow['id']] = $aRow;
				$aReturn[$aRow['id']]['alias'] = htmlspecialchars($aReturn[$aRow['id']]['alias']);
				$aReturn[$aRow['id']]['name'] = htmlspecialchars($aReturn[$aRow['id']]['name']);
				$aReturn[$aRow['id']]['description'] = htmlspecialchars($aReturn[$aRow['id']]['description']);				
		    }
		    return $aReturn;
		}

		return false;

    }
    public function getCatList($prefix) {
    	$sql = "SELECT 
    		blog_id as 'id',
    		blog_title as 'name', 
    		blog_latin as 'alias',
    		blog_description as 'description' 
    	FROM ".$prefix."_blogs";
		if ($aRows = $this->oDb->select($sql)) {
		    foreach ($aRows as $aRow) {
				$aReturn[$aRow['id']] = $aRow;
				$aReturn[$aRow['id']]['alias'] = htmlspecialchars($aReturn[$aRow['id']]['alias']);
				$aReturn[$aRow['id']]['name'] = htmlspecialchars($aReturn[$aRow['id']]['name']);
				$aReturn[$aRow['id']]['description'] = htmlspecialchars($aReturn[$aRow['id']]['description']);				
		    }
		    return $aReturn;
		}

		return false;

    }

    public function getTopicCount($prefix) {
    	$sql = "SELECT count(*) as 'count' FROM ".$prefix."_news";
    	if($aRow = $this->oDb->select($sql)) {    		
    		return $aRow[0]['count'];
    	}
    	return 1;
    }

    public function getTopicList($prefix,$page,$pagesize) {
    	$sql = "SELECT 
    		i.news_id as 'id',
    		i.news_title as 'title',
    		i.news_author as 'created_by',
    		i.news_start_text as 'hash' 
    	FROM ".$prefix."_news i 
    	where i.hidden = 0
    	order by id desc";
    	if(isset($page) && isset($pagesize)) {
    		$sql .= ' limit '.intval($page-1)*intval($pagesize).",".intval($pagesize);
//    		print $sql;
//    		die;
    	}
		if ($aRows = $this->oDb->select($sql)) {
		    foreach ($aRows as $aRow) {
				$aReturn[$aRow['id']] = $aRow;
				$aReturn[$aRow['id']]['title'] = htmlspecialchars($aReturn[$aRow['id']]['title']);
		    }
		    $count = $this->getTopicCount($prefix);
		    return array('count'=>$count,'collection'=>$aReturn);
		}

		return false;
    }

    public function getComments($prefix,$cid) {
    	$sql = "
    		SELECT 
    			c.news_comments_id as 'id',
    			u.login as 'login',
    			c.news_comments_pid as 'parent',
    			c.news_comments_user_id as 'userid',
    			c.news_comments_author as 'name',
    			'' as 'email',
    			c.news_comments_author_ip as 'ip',
    			c.news_comments_date as 'date',
    			c.news_comments_text as 'comment'
    		FROM `".$prefix."_news_comments` c
 				left outer join ".$prefix."_users u on c.news_comments_user_id = u.user_id
     			WHERE c.news_comments_news_id = ".intval($cid);
		if ($aRows = $this->oDb->select($sql)) {
		    foreach ($aRows as $aRow) {
				$aReturn[$aRow['parent']][$aRow['id']] = $aRow;
		    }
		    return $aReturn;
		}

		return false;
    }

    public function getTopic($prefix,$tid) {
    	$sql = "SELECT 
					 i.news_id as 'id',
					 i.news_title as 'title',
					 i.news_blog_title as 'cat',
					 i.news_date as 'date',
					 i.news_start_text as 'introtext',
					 concat(i.news_start_text,i.news_end_text) as 'fulltext',
					 '' as 'gallery', 
					 '' as 'video',
					 '' as 'tags',
					 i.news_author as 'created_by'
				FROM ".$prefix."_news i
				where i.news_id = ".intval($tid);
		if ($aRows = $this->oDb->select($sql)) {
		    foreach ($aRows as $aRow) {
				$aReturn[$aRow['id']] = $aRow;
				$aReturn[$aRow['id']]['title'] = htmlspecialchars($aReturn[$aRow['id']]['title']);
		    }
		    return $aReturn;
		}

		return false;
    }

    public function setHiddenTopic($prefix,$iId) {
    	$sql = "UPDATE ".$prefix."_news SET hidden=1 WHERE news_id = ?d ";
		if($this->oDb->query($sql,$iId)){
			return true;
		}
		return false;    	
    }

}
?>