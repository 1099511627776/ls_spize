<?php

class PluginSpize_ActionAdmin extends ActionPlugin
{

    protected $oUserCurrent = null;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
	$this->oUserCurrent = $this->User_GetUserCurrent();
	if (!$this->oUserCurrent OR !$this->oUserCurrent->isAdministrator()) {
	    return Router::Action('error');
	}
	$this->SetDefaultEvent('admin');
    }

    /**
     * Регистрируем необходимые евенты
     *
     */
    protected function RegisterEvent()
    {
		$this->AddEvent('admin', 'EventAdmin');
		$this->AddEvent('users', 'EventUsers');
		$this->AddEvent('categories', 'EventCategories');
		$this->AddEvent('comments', 'EventComments');
		$this->AddEventPreg('/^posts$/i','/^(page(\d+))?$/i','EventPosts');
		$this->AddEventPreg('/^posts$/i','/^(item(\d+))?$/i', 'EventPosts');
		$this->AddEventPreg('/^posts$/i','/^(delitem(\d+))?$/i', 'EventPosts');
		$this->AddEventPreg('/^posts$/i','/^(comment(\d+))?$/i', 'EventPosts');
    }

    private function getUsers(){
    	return $this->Plugink2import_k2import_GetUsers();
    }
    private function addUser($uid) {
    	return $this->Plugink2import_k2import_addUser($uid);
    }

	private function getCats(){
    	return $this->PluginSpize_Modulespizeimport_GetCats();
    }
    private function addCat($cid) {
    	return $this->PluginSpize_Modulespizeimport_addCat($cid);
    }

   	private function getPosts($page=null,$pagesize=null){
    	return $this->PluginSpize_Modulespizeimport_GetPosts(null,$page,$pagesize);
    }
    private function setHiddenTopic($tid){
    	return $this->PluginSpize_Modulespizeimport_setHiddenTopic($tid);
    }
    private function addPost($tid) {
    	return $this->PluginSpize_Modulespizeimport_addPost($tid);
    }
    private function addComments($cid) {
    	return $this->PluginSpize_Modulespizeimport_addComments($cid);
    }

    protected function EventAdmin()
    {
		$this->SetTemplateAction('admin');
    }

    protected function EventUsers()
    {
        $params = $this->getParams();
		$this->Viewer_Assign('sTemplateWebPathPlugin',Plugin::GetTemplateWebPath(get_class($this)));
		if ($params) {
	        $uId = $params[0];        	
	        $this->Viewer_SetResponseAjax('json');
	        $status = $this->addUser($uId);
	        $this->Viewer_AssignAjax('id',$uId);
	        $this->Viewer_AssignAjax('status',$status);
        } else {
        	$users = $this->PluginSpize_Modulespizeimport_ImportUsers();
			$this->Viewer_Assign('aUsers',$this->getUsers());			
        }
    }

    protected function EventCategories()
    {
       	set_time_limit(0);
        $params = $this->getParams();
		$this->Viewer_Assign('sTemplateWebPathPlugin',Plugin::GetTemplateWebPath(get_class($this)));
		if ($params) {
	        $cId = $params[0];        	
	        $this->Viewer_SetResponseAjax('json');
	        $status = $this->addCat($cId);
	        $this->Viewer_AssignAjax('id',$cId);
	        $this->Viewer_AssignAjax('status',$status);
        } else {
        	$users = $this->getCats();
        	foreach($users as $cid => $row){
        		$this->addCat($cid);
        	}
			$this->Viewer_Assign('aCats',$this->getCats());			
        }
    }

    protected function EventPosts()
    {
        if ($this->GetParamEventMatch(0,0)){
	        $tid = preg_match("/^(item(\d+))?$/i",$this->GetParamEventMatch(0,0)) ? $this->GetParamEventMatch(0,2) : null;
			$iPage = preg_match("/^(page(\d+))?$/i",$this->GetParamEventMatch(0,0)) ? $this->GetParamEventMatch(0,2) : 1;
			$cid = preg_match("/^(comment(\d+))?$/i",$this->GetParamEventMatch(0,0)) ? $this->GetParamEventMatch(0,2) : null;
	        $dtid = preg_match("/^(delitem(\d+))?$/i",$this->GetParamEventMatch(0,0)) ? $this->GetParamEventMatch(0,2) : null;
        } else {
        	$tid = null;
        	$cid = null;
        	$dtid = null;
        	$iPage = 1;
        }
		$this->Viewer_Assign('sTemplateWebPathPlugin',Plugin::GetTemplateWebPath(get_class($this)));
		if ($tid) {
	        $this->Viewer_SetResponseAjax('json');
	        $status = $this->addPost($tid);
	        //print "tid: {$tid} status: {$status}";
	        $this->Viewer_AssignAjax('id',$tid);
	        $this->Viewer_AssignAjax('status',$status);
        } elseif ($cid){
	        $this->Viewer_SetResponseAjax('json');
	        $status = $this->addComments($cid);
	        $this->Viewer_AssignAjax('cid',$cid);
	        $this->Viewer_AssignAjax('status',$status);        	
        } elseif($dtid){
	        $this->Viewer_SetResponseAjax('json');
	        $status = $this->setHiddenTopic($dtid);
	        $this->Viewer_AssignAjax('id',$tid);
	        $this->Viewer_AssignAjax('status',$status);
        } else {
        	$pts = $this->getPosts($iPage,20);
        	$count = $pts['count'];
        	$posts = $pts['collection'];
			$aPaging=$this->Viewer_MakePaging($count,$iPage,20,Config::Get('pagination.pages.count'),Router::GetPath('spize/posts'));
			$this->Viewer_Assign('aPaging',$aPaging);
			$this->Viewer_Assign('aPosts',$posts);			
        }
    }

    protected function EventComments()
    {
		$this->SetTemplateAction('comments');
    }


    public function EventShutdown()
    {
		/*$this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);*/
    }

}

?>
