<?php
App::uses('AppController','Controller');
class SocialRehabiliationProgrammeController extends AppController{
    public $layout='table';
	public $uses=array('User','Prisoner','SocialTheme','SocialisationProgram','CounsellingAndGuidance','SpecificCaseTreatment','LivelihoodSkillsTraining','BehaviourLifeSkillTraining','SpiritualMoralRehabilation','FormalEducation','NonFormalEducation','Aftercare','CouncelingSession');

	public function index(){
		
	}

	public function socialisationProgrammes(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

		$prison_id = $this->Session->read('Auth.User.prison_id');
		
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
		
        
	if(isset($this->data['SocialisationProgramDelete']['id']) && (int)$this->data['SocialisationProgramDelete']['id'] != 0){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

            if($this->SocialisationProgram->exists($this->data['SocialisationProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialisationProgram->updateAll(array('SocialisationProgram.is_trash' => 1), array('SocialisationProgram.id'  => $this->data['SocialisationProgramDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'socialisationProgrammes'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }

		$this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
	}

    public function addSocialisationProgrammes(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        //debug($this->request->data);exit;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(

                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,

                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id

                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'socialisation',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));
        $councellorsList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.prison_id' =>$prison_id,
                        'User.designation_id' => 14,
                        'User.is_enable'=>1,
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));
        //debug($prison_id);
        if(isset($this->request->data['SocialisationProgramEdit']['id'])){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
            $this->request->data=$this->SocialisationProgram->findById($this->data["SocialisationProgramEdit"]["id"]);
            //SocialTheme

            $themes = explode(',',$this->request->data['SocialisationProgram']['themes']);
            $themeArray =array();
            foreach ($themes as $themeId) {
                if($themeId !=''){
                    array_push($themeArray, $themeId);
                }
            }
            $this->request->data['SocialisationProgram']['themes'] = $themeArray;

            $this->request->data['SocialisationProgram']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['SocialisationProgram']['date_of_enrolment']));
            $this->request->data['SocialisationProgram']['start_date'] =date('d-m-Y',strtotime($this->request->data['SocialisationProgram']['start_date']));
            $this->request->data['SocialisationProgram']['end_date'] =date('d-m-Y',strtotime($this->request->data['SocialisationProgram']['end_date']));
        }

        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList' => $councellorsList,
            
        ));
    }
	public function saveSocialisationProgram(){
		$this->layout   = 'ajax';
		$themes='';
		foreach ($this->request->data['SocialisationProgram']['themes'] as $key => $value) {
			//echo $value;
			$themes .= $value.',';
		}
		$this->request->data['SocialisationProgram']['themes'] = $themes;
		$this->request->data['SocialisationProgram']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SocialisationProgram']['start_date']));
		$this->request->data['SocialisationProgram']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['SocialisationProgram']['date_of_enrolment']));
		$this->request->data['SocialisationProgram']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SocialisationProgram']['end_date']));

		$this->SocialisationProgram->saveAll($this->request->data);

        $this->Session->write('message_type','success');
        $this->Session->write('message','Saved Successfully !');
		echo "Success";
		exit;
	}

    
    public function socialisationProgramAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'SocialisationProgram';
       
        $condition      = array("SocialisationProgram.is_trash"=>0);
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(SocialisationProgram.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(SocialisationProgram.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }

        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        )+$limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

    }
    public function discontinueSocialisation(){
        $this->layout   = 'ajax';
        $modelName = 'SocialisationProgram';
         //debug($this->request->data);exit;
        $id = $this->request->data['id'];
        if($this->SocialisationProgram->exists($this->data['id'])){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();     
                $curnt_date = date('Y-m-d');   
                if($this->SocialisationProgram->updateAll(array('SocialisationProgram.discontinued' => 1,'SocialisationProgram.discontinue_date'=>"'".$curnt_date ."'"), array('SocialisationProgram.id'  => $this->data['id']))){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Discontinued Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }
    public function continueSocialisation(){
        $this->layout   = 'ajax';
        $modelName = 'SocialisationProgram';
         //debug($this->request->data);exit;
        $id = $this->request->data['id'];
        if($this->SocialisationProgram->exists($this->data['id'])){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialisationProgram->updateAll(array('SocialisationProgram.discontinued' => 0,'SocialisationProgram.discontinue_date'=>NUll), array('SocialisationProgram.id'  => $this->data['id']))){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','continued Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }
    public function discontinueItem(){
        $this->layout   = 'ajax';
        $modelName = $this->request->data['model'];
         //debug($this->request->data);exit;
        $id = $this->request->data['id'];
        if($this->$modelName->exists($this->data['id'])){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();
                $curnt_date = date('Y-m-d');                 
                if($this->$modelName->updateAll(array($modelName.'.discontinued' => 1,'discontinue_date'=>"'".$curnt_date ."'"), array($modelName.'.id'  => $this->data['id']))){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Discontinued Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }
    public function finalSave(){
        $this->layout   = 'ajax';
        $modelName = $this->request->data['model'];
         //debug($this->request->data);exit;
        $id = $this->request->data['id'];
        if($this->$modelName->exists($this->data['id'])){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();
                $curnt_date = date('Y-m-d');                 
                if($this->$modelName->updateAll(array($modelName.'.final_save' => 1), array($modelName.'.id'  => $this->data['id']))){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Final Saved Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }
    public function continueItem(){
        $this->layout   = 'ajax';
         //debug($this->request->data);exit;
        $id = $this->request->data['id'];
        $modelName = $this->request->data['model'];

        if($this->$modelName->exists($this->data['id'])){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->$modelName->updateAll(array($modelName.'.discontinued' => 0,'discontinue_date'=>NULL), array($modelName.'.id'  => $this->data['id']))){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','continued Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }

    public function submitHeadRemark(){
        $this->layout   = 'ajax';
        //$modelName = $this->request->data['model'];
         //debug($this->request->data);exit;
        $id = 0;
         if(isset($this->request->data['SocialisationProgram']['id'])){
            $modelName = 'SocialisationProgram';
            $id= $this->request->data['SocialisationProgram']['id'];
         }
         if(isset($this->request->data['CounsellingAndGuidance']['id'])){
            $modelName = 'CounsellingAndGuidance';
            $id= $this->request->data['CounsellingAndGuidance']['id'];
         }
         if(isset($this->request->data['SpiritualMoralRehabilation']['id'])){
            $modelName = 'SpiritualMoralRehabilation';
            $id= $this->request->data['SpiritualMoralRehabilation']['id'];
         }
         if(isset($this->request->data['BehaviourLifeSkillTraining']['id'])){
            $modelName = 'BehaviourLifeSkillTraining';
            $id= $this->request->data['BehaviourLifeSkillTraining']['id'];
         }
         if(isset($this->request->data['LivelihoodSkillsTraining']['id'])){
            $modelName = 'LivelihoodSkillsTraining';
            $id= $this->request->data['LivelihoodSkillsTraining']['id'];
         }
         if(isset($this->request->data['SpecificCaseTreatment']['id'])){
            $modelName = 'SpecificCaseTreatment';
            $id= $this->request->data['SpecificCaseTreatment']['id'];
         }
         if(isset($this->request->data['FormalEducation']['id'])){
            $modelName = 'FormalEducation';
            $id= $this->request->data['FormalEducation']['id'];
         }
         if(isset($this->request->data['NonFormalEducation']['id'])){
            $modelName = 'NonFormalEducation';
            $id= $this->request->data['NonFormalEducation']['id'];
         }
         if(isset($this->request->data['Aftercare']['id'])){
            $modelName = 'Aftercare';
            $id= $this->request->data['Aftercare']['id'];
         }
         
         
        if($this->$modelName->exists($id)){
                         
                  if($modelName = 'CounsellingAndGuidance'){
                      $db = ConnectionManager::getDataSource('default');
                        $db->begin();            
                        //$id= $this->request->data['CounsellingAndGuidance']['id'];

                        if($this->CouncelingSession->updateAll(array('CouncelingSession.head_remark' =>"'". $this->request->data['CounsellingAndGuidance']['head_remark'] . "'"), array('CouncelingSession.counceling_id'  => $id,'CouncelingSession.session'=>$this->request->data['CounsellingAndGuidance']['session']))){
                            $result ="success";

                        }else{
                            $db->rollback();
                            $result ="failed";
                            
                        }
                  }       
                if($this->$modelName->saveAll($this->request->data)){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Updated Successfully !');
                    $result ="success";
                }else{
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }

	public function counsellingAndGuidance(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
		$prison_id = $this->Session->read('Auth.User.prison_id');
		$today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,

                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $sessionList =array(
            '1'=>'Session 1',
            '2'=>'Session 2',
            '3'=>'Session 3',
            '4'=>'Session 4',
            '5'=>'Session 5',
            '6'=>'Session 6',
            '7'=>'Session 7',
            '8'=>'Session 8',
            '9'=>'Session 9',
            '10'=>'Session 10',

        );
         $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'counceling',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));
		if(isset($this->data['CounsellingAndGuidanceDelete']['id']) && (int)$this->data['CounsellingAndGuidanceDelete']['id'] != 0){
            if($this->CounsellingAndGuidance->exists($this->data['CounsellingAndGuidanceDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->CounsellingAndGuidance->updateAll(array('CounsellingAndGuidance.is_trash' => 1), array('CounsellingAndGuidance.id'  => $this->data['CounsellingAndGuidanceDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'counsellingAndGuidance'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }

		$this->set(array(
            
            'prisonersList' => $prisonersList,
            'sessionList'=>$sessionList,
            'themelist'=>$themelist
            
        ));
	}

public function changeSession(){
            $this->layout   = 'ajax';
         //debug($this->request->data);exit;
        $id = $this->request->data['CounsellingAndGuidance']['id'];
        $session =  $this->request->data['CounsellingAndGuidance']['session'];
        if($this->CounsellingAndGuidance->exists($id)){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();     
                $curnt_date = date('Y-m-d H:m:s');  

                $themes ='';
                   foreach ($this->request->data['CounsellingAndGuidance']['theme'] as $key => $value) {
                        $themes .= $value.',';
                    }
                $fields=array(
                    'CounsellingAndGuidance.session' => "'".$session."'",
                    'CounsellingAndGuidance.session_'.$session => "'".$curnt_date."'",
                    'CounsellingAndGuidance.theme'=>"'".$themes . "'",
                    'CounsellingAndGuidance.start_date'=>"'".date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['start_date']))."'",
                    'CounsellingAndGuidance.end_date'=>"'".date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['end_date']))."'",
                    'CounsellingAndGuidance.prisoners_input' => "'".$this->request->data['CounsellingAndGuidance']['prisoners_input']."'",
                    'CounsellingAndGuidance.discontinued' => 0,
                    'CounsellingAndGuidance.head_remark' => NUll,




                );
            
                if($this->CounsellingAndGuidance->updateAll($fields,array('CounsellingAndGuidance.id'  => $id))){

                    $counceling_session = array();
        
        $counceling_session['CouncelingSession']['session'] = $session;
        $counceling_session['CouncelingSession']['counceling_id'] = $id;
        $counceling_session['CouncelingSession']['start_date']=date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['start_date']));
        $counceling_session['CouncelingSession']['end_date']= date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['end_date']));
        $counceling_session['CouncelingSession']['theme']=$themes;
        $counceling_session['CouncelingSession']['prisoners_input']= $this->request->data['CounsellingAndGuidance']['prisoners_input'];
          $this->CouncelingSession->saveAll($counceling_session);

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Session Changed Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }

        public function addCounsellingAndGuidance(){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id

                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $councellorsList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.prison_id' =>$prison_id,
                        'User.designation_id' => 14,
                        'User.is_enable'=>1,
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));
        $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'counceling',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));

        $sessionList =array(
            '1'=>'Session 1',
            '2'=>'Session 2',
            '3'=>'Session 3',
            '4'=>'Session 4',
            '5'=>'Session 5',
            '6'=>'Session 6',
            '7'=>'Session 7',
            '8'=>'Session 8',
            '9'=>'Session 9',
            '10'=>'Session 10',

        );
        if(isset($this->request->data['CounsellingAndGuidanceEdit']['id'])){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
            $this->request->data=$this->CounsellingAndGuidance->findById($this->data["CounsellingAndGuidanceEdit"]["id"]);
            //SocialTheme

            $themes = explode(',',$this->request->data['CounsellingAndGuidance']['theme']);
            $themeArray =array();
            foreach ($themes as $themeId) {
                if($themeId !=''){
                    array_push($themeArray, $themeId);
                }
            }
            $this->request->data['CounsellingAndGuidance']['theme'] = $themeArray;
            $this->request->data['CounsellingAndGuidance']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['CounsellingAndGuidance']['date_of_enrolment']));
            $this->request->data['CounsellingAndGuidance']['start_date'] =date('d-m-Y',strtotime($this->request->data['CounsellingAndGuidance']['start_date']));
            $this->request->data['CounsellingAndGuidance']['end_date'] =date('d-m-Y',strtotime($this->request->data['CounsellingAndGuidance']['end_date']));
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList' => $councellorsList,
            'sessionList'=>$sessionList
        ));
    }

	public function saveCounsellingAndGuidance(){

		$this->layout   = 'ajax';
		$themes='';
        $session_selected = $this->request->data['CounsellingAndGuidance']['session'];
        $curnt_date =  date('Y-m-d H:m:s');

        $this->request->data['CounsellingAndGuidance']['session_'.$session_selected] = $curnt_date;
		foreach ($this->request->data['CounsellingAndGuidance']['theme'] as $key => $value) {
			//echo $value;
			$themes .= $value.',';
		}
		$this->request->data['CounsellingAndGuidance']['theme'] = $themes;
		$this->request->data['CounsellingAndGuidance']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['start_date']));
		$this->request->data['CounsellingAndGuidance']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['date_of_enrolment']));
		$this->request->data['CounsellingAndGuidance']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['end_date']));

		$this->CounsellingAndGuidance->saveAll($this->request->data);

        $counceling_session = array();
        
        $counceling_session['CouncelingSession']['session'] = $session_selected;
        $counceling_session['CouncelingSession']['counceling_id'] = $this->CounsellingAndGuidance->id;
        $counceling_session['CouncelingSession']['start_date']=date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['start_date']));
        $counceling_session['CouncelingSession']['end_date']= date('Y-m-d H:m:s',strtotime($this->request->data['CounsellingAndGuidance']['end_date']));
        $counceling_session['CouncelingSession']['theme']=$themes;
        $counceling_session['CouncelingSession']['prisoners_input']= $this->request->data['CounsellingAndGuidance']['prisoners_input'];
          $this->CouncelingSession->saveAll($counceling_session);
		echo "Success";
		exit;
	}

    
    public function counsellingAndGuidanceAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'CounsellingAndGuidance';
       
        $condition      = array("CounsellingAndGuidance.is_trash"=>0);

        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("CounsellingAndGuidance.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("CounsellingAndGuidance.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(CounsellingAndGuidance.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(CounsellingAndGuidance.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }
         $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'counceling',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        )+$limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
            'themelist' =>$themelist
        ));

    }
	public function spiritualAndMoralRehabiliation(){
		
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

		$prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        if(isset($this->data['SpiritualMoralRehabilationDelete']['id']) && (int)$this->data['SpiritualMoralRehabilationDelete']['id'] != 0){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
            $moduleId = $this->getModuleId("social_rehabilitation");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');

            //echo $moduleId;exit;
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }
            if($this->SpiritualMoralRehabilation->exists($this->data['SpiritualMoralRehabilationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SpiritualMoralRehabilation->updateAll(array('SpiritualMoralRehabilation.is_trash' => 1), array('SpiritualMoralRehabilation.id'  => $this->data['SpiritualMoralRehabilationDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'spiritualAndMoralRehabiliation'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }

        $this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
	}

    public function addSpiritualAndMoralRehabiliation(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id

                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $councellorsList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.prison_id' =>$prison_id,
                        'User.designation_id' => 14,
                        'User.is_enable'=>1,
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));
        $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'spiritual',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));
        if(isset($this->request->data['SpiritualMoralRehabilationEdit']['id'])){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
            $moduleId = $this->getModuleId("social_rehabilitation");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

            //echo $moduleId;exit;
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }
            $this->request->data=$this->SpiritualMoralRehabilation->findById($this->data["SpiritualMoralRehabilationEdit"]["id"]);
            //SocialTheme

            $themes = explode(',',$this->request->data['SpiritualMoralRehabilation']['themes']);
            $themeArray =array();
            foreach ($themes as $themeId) {
                if($themeId !=''){
                    array_push($themeArray, $themeId);
                }
            }
            
            $this->request->data['SpiritualMoralRehabilation']['themes'] = $themeArray;
            $this->request->data['SpiritualMoralRehabilation']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['SpiritualMoralRehabilation']['date_of_enrolment']));
            $this->request->data['SpiritualMoralRehabilation']['start_date'] =date('d-m-Y',strtotime($this->request->data['SpiritualMoralRehabilation']['start_date']));
            $this->request->data['SpiritualMoralRehabilation']['end_date'] =date('d-m-Y',strtotime($this->request->data['SpiritualMoralRehabilation']['end_date']));
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList' => $councellorsList,
        ));
    }



public function SaveSpiritualMoralRehabilation(){
        $this->layout   = 'ajax';
        $themes='';
        foreach ($this->request->data['SpiritualMoralRehabilation']['themes'] as $key => $value) {
            //echo $value;
            $themes .= $value.',';
        }
        $this->request->data['SpiritualMoralRehabilation']['themes'] = $themes;
        $this->request->data['SpiritualMoralRehabilation']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SpiritualMoralRehabilation']['start_date']));
        $this->request->data['SpiritualMoralRehabilation']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['SpiritualMoralRehabilation']['date_of_enrolment']));
        $this->request->data['SpiritualMoralRehabilation']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SpiritualMoralRehabilation']['end_date']));

        $this->SpiritualMoralRehabilation->saveAll($this->request->data);
        echo "Success";
        exit;
    }

    
    public function spiritualAndMoralRehabilitaionAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'SpiritualMoralRehabilation';
       
        $condition      = array("SpiritualMoralRehabilation.is_trash"=>0);

        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("SpiritualMoralRehabilation.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("SpiritualMoralRehabilation.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(SpiritualMoralRehabilation.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(SpiritualMoralRehabilation.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        ) + $limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

    }

	public function  behaviourLifeSkillTrainings(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

		$prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
      if(isset($this->data['BehaviourLifeSkillTrainingDelete']['id']) && (int)$this->data['BehaviourLifeSkillTrainingDelete']['id'] != 0){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
            if($this->BehaviourLifeSkillTraining->exists($this->data['BehaviourLifeSkillTrainingDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->BehaviourLifeSkillTraining->updateAll(array('BehaviourLifeSkillTraining.is_trash' => 1), array('BehaviourLifeSkillTraining.id'  => $this->data['BehaviourLifeSkillTrainingDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'behaviourLifeSkillTrainings'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }

        $this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
	}

public function  addBehaviourLifeSkillTrainings(){
        
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id

                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $councellorsList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.prison_id' =>$prison_id,
                        'User.designation_id' => 14,
                        'User.is_enable'=>1,
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));
        $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'behaviour',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));
        if(isset($this->request->data['BehaviourLifeSkillTrainingEdit']['id'])){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
            $moduleId = $this->getModuleId("social_rehabilitation");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

            //echo $moduleId;exit;
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }
            $this->request->data=$this->BehaviourLifeSkillTraining->findById($this->data["BehaviourLifeSkillTrainingEdit"]["id"]);
            //SocialTheme

            $themes = explode(',',$this->request->data['BehaviourLifeSkillTraining']['themes']);
            $themeArray =array();
            foreach ($themes as $themeId) {
                if($themeId !=''){
                    array_push($themeArray, $themeId);
                }
            }
            $this->request->data['BehaviourLifeSkillTraining']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['BehaviourLifeSkillTraining']['date_of_enrolment']));
            $this->request->data['BehaviourLifeSkillTraining']['start_date'] =date('d-m-Y',strtotime($this->request->data['BehaviourLifeSkillTraining']['start_date']));
            $this->request->data['BehaviourLifeSkillTraining']['end_date'] =date('d-m-Y',strtotime($this->request->data['BehaviourLifeSkillTraining']['end_date']));
            $this->request->data['BehaviourLifeSkillTraining']['themes'] = $themeArray;

        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList' => $councellorsList,
        ));
    }


public function SaveBehaviourLifeSkillTraining(){
        $this->layout   = 'ajax';
        $themes='';
        foreach ($this->request->data['BehaviourLifeSkillTraining']['themes'] as $key => $value) {
            //echo $value;
            $themes .= $value.',';
        }
        $this->request->data['BehaviourLifeSkillTraining']['themes'] = $themes;
        $this->request->data['BehaviourLifeSkillTraining']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['BehaviourLifeSkillTraining']['start_date']));
        $this->request->data['BehaviourLifeSkillTraining']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['BehaviourLifeSkillTraining']['date_of_enrolment']));
        $this->request->data['BehaviourLifeSkillTraining']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['BehaviourLifeSkillTraining']['end_date']));

        $this->BehaviourLifeSkillTraining->saveAll($this->request->data);
        echo "Success";
        exit;
    }

    
    public function behaviouLifeSkillTrainingAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'BehaviourLifeSkillTraining';

        $condition      = array("BehaviourLifeSkillTraining.is_trash"=>0);

        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("BehaviourLifeSkillTraining.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("BehaviourLifeSkillTraining.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(BehaviourLifeSkillTraining.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(BehaviourLifeSkillTraining.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }


        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        ) + $limit;
        //debug($this->paginate);exit;

        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

    }
	public function  livelihoodSkillsTraining(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
		$prison_id = $this->Session->read('Auth.User.prison_id');
		$today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
       
        if(isset($this->data['LivelihoodSkillsTrainingDelete']['id']) && (int)$this->data['LivelihoodSkillsTrainingDelete']['id'] != 0){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
            $moduleId = $this->getModuleId("social_rehabilitation");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');

            //echo $moduleId;exit;
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }
            if($this->LivelihoodSkillsTraining->exists($this->data['LivelihoodSkillsTrainingDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->LivelihoodSkillsTraining->updateAll(array('LivelihoodSkillsTraining.is_trash' => 1), array('LivelihoodSkillsTraining.id'  => $this->data['LivelihoodSkillsTrainingDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'livelihoodSkillsTraining'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }
		$this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
	}

    public function  addLivelihoodSkillsTraining(){
        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $councellorsList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.prison_id' =>$prison_id,
                        'User.designation_id' => 14,
                        'User.is_enable'=>1,
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));

        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id

                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'livelihood',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));
        if(isset($this->request->data['LivelihoodSkillsTrainingEdit']['id'])){
            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
            $moduleId = $this->getModuleId("social_rehabilitation");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

            //echo $moduleId;exit;
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }
            $this->request->data=$this->LivelihoodSkillsTraining->findById($this->data["LivelihoodSkillsTrainingEdit"]["id"]);
            //SocialTheme

            $themes = explode(',',$this->request->data['LivelihoodSkillsTraining']['themes']);
            $themeArray =array();
            foreach ($themes as $themeId) {
                if($themeId !=''){
                    array_push($themeArray, $themeId);
                }
            }

            $this->request->data['LivelihoodSkillsTraining']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['LivelihoodSkillsTraining']['date_of_enrolment']));
            $this->request->data['LivelihoodSkillsTraining']['start_date'] =date('d-m-Y',strtotime($this->request->data['LivelihoodSkillsTraining']['start_date']));
            $this->request->data['LivelihoodSkillsTraining']['end_date'] =date('d-m-Y',strtotime($this->request->data['LivelihoodSkillsTraining']['end_date']));
            $this->request->data['LivelihoodSkillsTraining']['themes'] = $themeArray;
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList'=>$councellorsList
            
        ));
    }
    public function saveLivelihoodSkillsTraining(){
        $this->layout   = 'ajax';
        $themes='';
        foreach ($this->request->data['LivelihoodSkillsTraining']['themes'] as $key => $value) {
            //echo $value;
            $themes .= $value.',';
        }
        $this->request->data['LivelihoodSkillsTraining']['themes'] = $themes;
        $this->request->data['LivelihoodSkillsTraining']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['LivelihoodSkillsTraining']['start_date']));
        $this->request->data['LivelihoodSkillsTraining']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['LivelihoodSkillsTraining']['date_of_enrolment']));
        $this->request->data['LivelihoodSkillsTraining']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['LivelihoodSkillsTraining']['end_date']));

        $this->LivelihoodSkillsTraining->saveAll($this->request->data);
        echo "Success";
        exit;
    }

    
    public function livelihoodSkillsTrainingAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'LivelihoodSkillsTraining';
       
        $condition      = array("LivelihoodSkillsTraining.is_trash"=>0);

        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("LivelihoodSkillsTraining.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("LivelihoodSkillsTraining.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(LivelihoodSkillsTraining.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(LivelihoodSkillsTraining.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }


        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        )+$limit;

        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

    }
	public function  specificCaseTreatment(){

        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

		$prison_id = $this->Session->read('Auth.User.prison_id');
		$today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
		if(isset($this->data['SpecificCaseTreatmentDelete']['id']) && (int)$this->data['SpecificCaseTreatmentDelete']['id'] != 0){


                $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
                $moduleId = $this->getModuleId("social_rehabilitation");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');

                //echo $moduleId;exit;
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
            if($this->SpecificCaseTreatment->exists($this->data['SpecificCaseTreatmentDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SpecificCaseTreatment->updateAll(array('SpecificCaseTreatment.is_trash' => 1), array('SpecificCaseTreatment.id'  => $this->data['SpecificCaseTreatmentDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'spiritualAndMoralRehabiliation'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }

		$this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
	}

    public function  addSpecificCaseTreatment(){

        $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id
                        
                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $themelist = $this->SocialTheme->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SocialTheme.id',
                        'SocialTheme.name'
                    ),
                    'conditions'    => array(
                        'SocialTheme.type'=>'specificcase',
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));

        $councellorsList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.prison_id' =>$prison_id,
                        'User.designation_id' => 14,
                        'User.is_enable'=>1,
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));
        if(isset($this->request->data['SpecificCaseTreatmentEdit']['id'])){

            $menuId = $this->getMenuId("/SocialRehabiliationProgramme/socialisationProgrammes");
            $moduleId = $this->getModuleId("social_rehabilitation");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

            //echo $moduleId;exit;
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }

            $this->request->data=$this->SpecificCaseTreatment->findById($this->data["SpecificCaseTreatmentEdit"]["id"]);
            //SocialTheme

            $themes = explode(',',$this->request->data['SpecificCaseTreatment']['themes']);
            $themeArray =array();
            foreach ($themes as $themeId) {
                if($themeId !=''){
                    array_push($themeArray, $themeId);
                }
            }
            
            $this->request->data['SpecificCaseTreatment']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['SpecificCaseTreatment']['date_of_enrolment']));
            $this->request->data['SpecificCaseTreatment']['start_date'] =date('d-m-Y',strtotime($this->request->data['SpecificCaseTreatment']['start_date']));
            $this->request->data['SpecificCaseTreatment']['end_date'] =date('d-m-Y',strtotime($this->request->data['SpecificCaseTreatment']['end_date']));
            $this->request->data['SpecificCaseTreatment']['themes'] = $themeArray;
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList'=>$councellorsList
            
        ));
    }
	
	function saveSpecificCaseTreatment(){
		$this->layout   = 'ajax';
		$themes='';
		foreach ($this->request->data['SpecificCaseTreatment']['themes'] as $key => $value) {
			//echo $value;
			$themes .= $value.',';
		}
		$this->request->data['SpecificCaseTreatment']['themes'] = $themes;
		$this->request->data['SpecificCaseTreatment']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SpecificCaseTreatment']['start_date']));
		$this->request->data['SpecificCaseTreatment']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['SpecificCaseTreatment']['date_of_enrolment']));
		$this->request->data['SpecificCaseTreatment']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SpecificCaseTreatment']['end_date']));

		$this->SpecificCaseTreatment->saveAll($this->request->data);
		echo "Success";
		exit;
	}


    public function specificCaseTreatmentAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'SpecificCaseTreatment';
       
        $condition      = array("SpecificCaseTreatment.is_trash"=>0);

        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("SpecificCaseTreatment.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("SpecificCaseTreatment.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(SpecificCaseTreatment.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(SpecificCaseTreatment.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }


        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        )+$limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

    }
    

}	