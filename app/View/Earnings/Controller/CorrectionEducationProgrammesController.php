<?php
App::uses('AppController','Controller');
class CorrectionEducationProgrammesController extends AppController{
    public $layout='table';
	public $uses=array('User','Prisoner','SocialTheme','SchoolProgram','SubSchoolProgram','SubCategorySchoolProgram','FormalEducation','NonFormalProgram','NonFormalEducation','NonFormalProgramModule','ModuleStage');

	public function index(){
		
	}

	public function formalEducation(){
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
		
        if(isset($this->data['FormalEducationDelete']['id']) && (int)$this->data['FormalEducationDelete']['id'] != 0){
            if($this->FormalEducation->exists($this->data['FormalEducationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->FormalEducation->updateAll(array('FormalEducation.is_trash' => 1), array('FormalEducation.id'  => $this->data['FormalEducationDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'formalEducation'));
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
    public function addFormalEducation(){
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
        $schoolProgramList = $this->SchoolProgram->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SchoolProgram.id',
                        'SchoolProgram.name'
                    ),
                    'conditions'    => array(
                        
                        'SchoolProgram.is_enable'=>1,
                        'SchoolProgram.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SchoolProgram.id'
                    )
                ));


               $subSchoolProgramList = array();

               $subSubSchoolProgramList = array();
        if(isset($this->request->data['FormalEducationEdit']['id'])){
            $this->request->data=$this->FormalEducation->findById($this->data["FormalEducationEdit"]["id"]);
            
            $subSchoolProgramList = $this->SubSchoolProgram->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SubSchoolProgram.id',
                        'SubSchoolProgram.name'
                    ),
                    'conditions'    => array(
                        'SubSchoolProgram.school_program_id'=>$this->request->data['FormalEducation']['school_program_id'],
                        'SubSchoolProgram.is_enable'=>1,
                        'SubSchoolProgram.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SubSchoolProgram.id'
                    )
                ));
            $subSubSchoolProgramList = $this->SubCategorySchoolProgram->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SubCategorySchoolProgram.id',
                        'SubCategorySchoolProgram.name'
                    ),
                    'conditions'    => array(
                        'SubCategorySchoolProgram.sub_school_program_id'=>$this->request->data['FormalEducation']['sub_school_program_id'],
                        'SubCategorySchoolProgram.is_enable'=>1,
                        'SubCategorySchoolProgram.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SubCategorySchoolProgram.id'
                    )
                ));
            //SocialTheme

            
            $this->request->data['FormalEducation']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['FormalEducation']['date_of_enrolment']));
            $this->request->data['FormalEducation']['start_date'] =date('d-m-Y',strtotime($this->request->data['FormalEducation']['start_date']));
            $this->request->data['FormalEducation']['end_date'] =date('d-m-Y',strtotime($this->request->data['FormalEducation']['end_date']));

        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'schoolProgramList'=>$schoolProgramList,
            'subSchoolProgramList'=>$subSchoolProgramList,
            'subSubSchoolProgramList'=>$subSubSchoolProgramList,
            'councellorsList' => $councellorsList,
            
        ));
    }
	
	public function saveFormalEducation(){
		$this->layout   = 'ajax';

		$this->request->data['FormalEducation']['prisoner_id']=$this->request->data['FormalEducation']['prisoner_no'];
		$this->request->data['FormalEducation']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['FormalEducation']['start_date']));
		$this->request->data['FormalEducation']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['FormalEducation']['date_of_enrolment']));
		$this->request->data['FormalEducation']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['FormalEducation']['end_date']));

		$this->FormalEducation->saveAll($this->request->data);
		echo "Success";
		exit;
	}


	public function nonFormalEducation(){
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
		if(isset($this->data['NonFormalEducationDelete']['id']) && (int)$this->data['NonFormalEducationDelete']['id'] != 0){
            if($this->NonFormalEducation->exists($this->data['NonFormalEducationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->NonFormalEducation->updateAll(array('NonFormalEducation.is_trash' => 1), array('NonFormalEducation.id'  => $this->data['NonFormalEducationDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'nonFormalEducation'));
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

    public function addNonFormalEducation(){
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');

        $includedList = $this->NonFormalEducation->find('list',array(
                    "fields"    => array(
                        "NonFormalEducation.prisoner_id"
                    ),
                    'conditions'    => array(
                        'NonFormalEducation.is_trash' => 0,
                        'NonFormalEducation.discontinued' => 0,
                    ),
                    
                ));
        $condition=array(

                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,

                    );
        if(count($includedList) >0){
        $condition += array(
                "Prisoner.id NOT IN (".implode(",", $includedList).")",
            );
    }
       $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => $condition,
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
                        
                        'SocialTheme.is_enable'=>1,
                        'SocialTheme.is_trash'=>0,
                    ),
                    'order'=>array(
                        'SocialTheme.id'
                    )
                ));
        $nonFormalProgramList = $this->NonFormalProgram->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'NonFormalProgram.id',
                        'NonFormalProgram.name'
                    ),
                    'conditions'    => array(
                        
                        'NonFormalProgram.is_enable'=>1,
                        'NonFormalProgram.is_trash'=>0,
                    ),
                    'order'=>array(
                        'NonFormalProgram.id'
                    )
                ));
            $moduleList = array();
            $moduleStageList = array();
            if(isset($this->request->data['NonFormalEducationEdit']['id'])){
            $this->request->data=$this->NonFormalEducation->findById($this->data["NonFormalEducationEdit"]["id"]);
            //SocialTheme
            $moduleList = $this->NonFormalProgramModule->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'NonFormalProgramModule.id',
                        'NonFormalProgramModule.name'
                    ),
                    'conditions'    => array(
                        'NonFormalProgramModule.program_id'=>$this->request->data['NonFormalEducation']['non_formal_program_id'],
                        'NonFormalProgramModule.is_enable'=>1,
                        'NonFormalProgramModule.is_trash'=>0,
                    ),
                    'order'=>array(
                        'NonFormalProgramModule.id'
                    )
                ));
            $moduleStageList = $this->ModuleStage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'ModuleStage.id',
                        'ModuleStage.name'
                    ),
                    'conditions'    => array(
                        'ModuleStage.module_id'=>$this->request->data['NonFormalEducation']['module_id'],
                        'ModuleStage.is_enable'=>1,
                        'ModuleStage.is_trash'=>0,
                    ),
                    'order'=>array(
                        'ModuleStage.id'
                    )
                ));

            $this->request->data['NonFormalEducation']['date_of_enrolment'] =date('d-m-Y',strtotime($this->request->data['NonFormalEducation']['date_of_enrolment']));
            $this->request->data['NonFormalEducation']['start_date'] =date('d-m-Y',strtotime($this->request->data['NonFormalEducation']['start_date']));
            $this->request->data['NonFormalEducation']['end_date'] =date('d-m-Y',strtotime($this->request->data['NonFormalEducation']['end_date']));
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'themelist' => $themelist,
            'councellorsList' => $councellorsList,
            'nonFormalProgramList' => $nonFormalProgramList,
            'moduleList'=>$moduleList,
            'moduleStageList'=>$moduleStageList,
        ));
    }

	public function saveNonFormalEducation(){
		$this->layout   = 'ajax';

		$this->request->data['NonFormalEducation']['prisoner_id']=$this->request->data['NonFormalEducation']['prisoner_no'];
		$this->request->data['NonFormalEducation']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['NonFormalEducation']['start_date']));
		$this->request->data['NonFormalEducation']['date_of_enrolment'] = date('Y-m-d H:m:s',strtotime($this->request->data['NonFormalEducation']['date_of_enrolment']));
		$this->request->data['NonFormalEducation']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['NonFormalEducation']['end_date']));

		$this->NonFormalEducation->saveAll($this->request->data);
		echo "Success";
		exit;
	}

}