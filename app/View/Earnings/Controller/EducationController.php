<?php
App::uses('AppController','Controller');
class EducationController extends AppController{

    public $layout='table';
    public $uses=array('InformalCouncelling','User','Prisoner','SocialTheme','NonFormalProgram','NonFormalProgramModule','ModuleStage','SchoolProgram','SubSchoolProgram','SubCategorySchoolProgram','FormalEducation','NonFormalEducation');

	public function index(){

		$councellorsList =array();
		$themelist = array();
		$schoolProgramList =array();
		$subSchoolProgramList= array();
		$subSubSchoolProgramList =array();
		$nonFormalProgramList =array();
		$moduleList =array();
		$moduleStageList =array();
		$prisonersList =array();
        $responsibleOfficerList =array();


		$prison_id = $this->Session->read('Auth.User.prison_id');
        $user_id = $this->Session->read('Auth.User.id');
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
			 $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id' =>$prison_id,
                        'Prisoner.status'=>'Approved',
                        'Prisoner.is_trash'=>0,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
             /* $prisonersListInformalDone =array();
              foreach ($prisonersList as $key => $val) {
                 $prisoner_no = var_dump( $val);
                 $informalDetails = $this->InformalCouncelling->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'InformalCouncelling.councellor_id',
                        'InformalCouncelling.opinion_by_prisoner'
                    
                    ),
                    'conditions'    => array(
                        'InformalCouncelling.prisoner_id' =>$prisoner_no,
                        
                    )
                
                ));

                 if(isset($informalDetails ) && $informalDetails != '' ){
                    array_push($prisonersListInformalDone, $val);
                 }
              }
              var_dump($prisonersListInformalDone);
                 exit;*/

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

               //added by smita(start)
                 $responsibleOfficerList = $this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name'
                    ),
                    'conditions'    => array(
                        
                        'User.is_enable'=>1,
                        'User.is_trash'=>0,
                        'User.prison_id' =>$prison_id
                    ),
                    'order'=>array(
                        'User.id'
                    )
                ));
               //added by smita (end)

			   $subSchoolProgramList = array();

			   $subSubSchoolProgramList = array();
       
       

		$this->set(array(
            'responsibleOfficerList' => $responsibleOfficerList,
            'councellorsList' => $councellorsList,
            'themelist' => $themelist,
            'schoolProgramList'=>$schoolProgramList,
            'subSchoolProgramList'=>$subSchoolProgramList,
            'subSubSchoolProgramList'=>$subSubSchoolProgramList,
            'nonFormalProgramList' => $nonFormalProgramList,
            'moduleList'=>$moduleList,
            'moduleStageList'=>$moduleStageList,
            'prisonersList' => $prisonersList,
            'user_id'=>$user_id,
            
        ));



	}

     public function getModuleList(){
        $id =  $this->params['data']['id'];
            $moduleList = $this->NonFormalProgramModule->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'NonFormalProgramModule.id',
                        'NonFormalProgramModule.name'
                    ),
                    'conditions'    => array(
                        
                        'NonFormalProgramModule.is_enable'=>1,
                        'NonFormalProgramModule.is_trash'=>0,
                        'NonFormalProgramModule.program_id'=>$id,

                    ),
                    'order'=>array(
                        'NonFormalProgramModule.id'
                    )
                ));

               $htm ='<option value="0"> -- select module -- </option>';
            foreach ($moduleList as $key => $value) {
                $htm .= '<option value="'.$key.'">'.$value .' </option>';
            }
            
            echo $htm;
            exit;

    }

    public function getModuleStageList(){
        $id =  $this->params['data']['id'];
            $moduleStageList = $this->ModuleStage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'ModuleStage.id',
                        'ModuleStage.name'
                    ),
                    'conditions'    => array(
                        
                        'ModuleStage.is_enable'=>1,
                        'ModuleStage.is_trash'=>0,
                        'ModuleStage.module_id'=>$id

                    ),
                    'order'=>array(
                        'ModuleStage.id'
                    )
                ));

               $htm ='<option value="0"> -- select module stage -- </option>';
            foreach ($moduleStageList as $key => $value) {
                $htm .= '<option value="'.$key.'">'.$value .' </option>';
            }
            
            echo $htm;
            exit;

    }

    public function getSubSubCategorySchoolprogram(){
        $schoolProgramId =  $this->params['data']['school_program_id'];


               $subSubSchoolProgramList = $this->SubCategorySchoolProgram->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SubCategorySchoolProgram.id',
                        'SubCategorySchoolProgram.name'
                    ),
                    'conditions'    => array(
                        
                        'SubCategorySchoolProgram.is_enable'=>1,
                        'SubCategorySchoolProgram.is_trash'=>0,
                        'SubCategorySchoolProgram.sub_school_program_id'=>$schoolProgramId

                    ),
                    'order'=>array(
                        'SubCategorySchoolProgram.id'
                    )
                ));

               $htm ='<option value="0"> -- select sub program -- </option>';
            foreach ($subSubSchoolProgramList as $key => $value) {
                $htm .= '<option value="'.$key.'">'.$value .' </option>';
            }
            
            echo $htm;
            exit;

    }
         public function getSubCategorySchoolprogram(){

        $schoolProgramId =  $this->params['data']['school_program_id'];

            $subSchoolProgramList = $this->SubSchoolProgram->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SubSchoolProgram.id',
                        'SubSchoolProgram.name'
                    ),
                    'conditions'    => array(
                        
                        'SubSchoolProgram.is_enable'=>1,
                        'SubSchoolProgram.is_trash'=>0,
                        'SubSchoolProgram.school_program_id'=>$schoolProgramId
                    ),
                    'order'=>array(
                        'SubSchoolProgram.id'
                    )
                ));
            $htm ='<option value="0"> -- select sub school program -- </option>';
            foreach ($subSchoolProgramList as $key => $value) {
                $htm .= '<option value="'.$key.'">'.$value .' </option>';
            }
            
            echo $htm;
            exit;
           // echo $schoolProgramId;exit;
         } 
     public function getPrisonerDetail(){
        $this->layout   = 'ajax';


        $prisoner_no = $this->params['data']['prisoner_id'];
        $PrisonerDetail = $this->Prisoner->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.first_name',
                        'Prisoner.last_name'
                    ),
                    'conditions'    => array(
                        'Prisoner.id' =>$prisoner_no,
                        
                    )
                
                ));
        echo $PrisonerDetail['Prisoner']['first_name'] . ' ' . $PrisonerDetail['Prisoner']['last_name'] ;exit;
    }
    public function submitInformalForm(){

        $formSelected = "informal";
        $prisoner_no = $this->params['data']['InformalCouncelling']['prisoner_no'];

        $this->request->data['InformalCouncelling']['prisoner_id']=$prisoner_no;
        //$data['prisoner_id'] = $prisoner_no;

        $informalDetails = $this->InformalCouncelling->find('first',array(
            'recursive'     => -1,
            'fields'        => array(
                'InformalCouncelling.councellor_id',
                'InformalCouncelling.opinion_by_prisoner'

            ),
            'conditions'    => array(
                'InformalCouncelling.prisoner_id' =>$prisoner_no,
                'date(InformalCouncelling.end_date) > ' =>date("Y-m-d", strtotime($this->params['data']['InformalCouncelling']['start_date'])),
                
            )

        ));
        $PrisonerDetail = $this->Prisoner->find('first',array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.id' =>$prisoner_no,
                
            )

        ));
        //$data['prisoner_no'] = $PrisonerDetail['Prisoner']['prisoner_no'];
        $this->request->data['InformalCouncelling']['prisoner_no']=$PrisonerDetail['Prisoner']['prisoner_no'];
        $dateOfCouncelling = date('Y-m-d H:i:s',strtotime($this->request->data['InformalCouncelling']['date_of_councelling'])) ;
        $end_date = date('Y-m-d H:i:s',strtotime($this->request->data['InformalCouncelling']['end_date'])) ;
        $start_date = date('Y-m-d H:i:s',strtotime($this->request->data['InformalCouncelling']['start_date'])) ;
        $this->request->data['InformalCouncelling']['date_of_councelling'] = $dateOfCouncelling;
        $this->request->data['InformalCouncelling']['start_date'] = $start_date;
        $this->request->data['InformalCouncelling']['end_date'] = $end_date;
        
        // if(isset($informalDetails) && count($informalDetails)>0){
        //     // $result = "Informal Councelling Already done";
        //     // $this->Session->write('message_type','error');
        //     // $this->Session->write('message','Informal Councelling Already done !');
        //     echo "PROB";exit;

        // }else{
            //debug($this->request->data);
            if ($this->InformalCouncelling->saveAll($this->request->data['InformalCouncelling'])) {

                if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                {
                    
                    $notification_msg = "Informal Counseling created.";
                    $notifyUser = $this->User->find('list',array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                            'User.is_trash'     => 0,
                            'User.is_enable'     => 1,
                            'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                        )
                    ));
                    // debug($notifyUser);
                    $this->addManyNotification($notifyUser,$notification_msg,"education");
                }
                echo "SUCC";exit;
            // $this->Session->write('message_type','success');
            // $this->Session->write('message','Saved Successfully !');
            // $result = 'Saved Successfully !' ;
            // } else {
            //     echo "FAIL";exit;
            // //debug($this->InformalCouncelling->validationErrors);
            // // $this->Session->write('message_type','error');
            // // $this->Session->write('message','Saving Failed !');
            // // $result = 'Saving Failed !';
            // }
        }
        // echo $result ; exit;
        // echo $formSelected;
        
        exit;
       
    }

    public function submitFormalForm(){
        $formSelected = "formal";

        $prisoner_no = $this->params['data']['FormalEducation']['prisoner_no'];

        $this->request->data['FormalEducation']['prisoner_id']=$prisoner_no;
        //$data['prisoner_id'] = $prisoner_no;

        $informalDetails = $this->InformalCouncelling->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'InformalCouncelling.councellor_id',
                        'InformalCouncelling.opinion_by_prisoner'
                    ),
                    'conditions'    => array(
                        'InformalCouncelling.prisoner_id' =>$prisoner_no,
                        
                    )
                
                ));
        $PrisonerDetail = $this->Prisoner->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no',
                    ),
                    'conditions'    => array(
                        'Prisoner.id' =>$prisoner_no,
                        
                    )
                
                ));
        //$data['prisoner_no'] = $PrisonerDetail['Prisoner']['prisoner_no'];
        $this->request->data['FormalEducation']['prisoner_no']= isset($this->request->data['FormalEducation']['prisoner_no']) && ($this->request->data['FormalEducation']['prisoner_no']) != '' ? ($PrisonerDetail['Prisoner']['prisoner_no']) : '';

        //$PrisonerDetail['Prisoner']['prisoner_no'];

        
        $dateOfCouncelling = date('Y-m-d H:i:s',strtotime($this->request->data['FormalEducation']['doc'])) ;

       
        $this->request->data['FormalEducation']['date_of_councelling'] = $dateOfCouncelling;
        $this->request->data['FormalEducation']['sub_category_school_program_id'] =  $this->request->data['FormalEducation']['sub_sub_school_program_id'];
       
        // if(isset($informalDetails) && count($informalDetails)>0){
            if ($this->FormalEducation->saveAll($this->request->data['FormalEducation'])) {
                // $this->Session->write('message_type','success');
                // $this->Session->write('message','Saved Successfully !');
                // $result = 'Saved Successfully !' ;
                echo "SUCC";exit;
            } else {
                //debug($this->InformalCouncelling->validationErrors);
                // $this->Session->write('message_type','error');
                // $this->Session->write('message','Saving Failed !');
                // $result = 'Saving Failed !';
                echo "FAIL";exit;
            }

        // }else{
            //debug($this->request->data);
            // $result = "Informal Councelling not done";
            // $this->Session->write('message_type','error');
            // $this->Session->write('message','Informal Councelling Already done !');
            // echo "PROB";exit;

            
        // }
       // echo $result ; exit;
            //echo $formSelected;
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Formal Education created.";
                        $notifyUser = $this->User->find('list',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        debug($notifyUser);
                        $this->addManyNotification($notifyUser,$notification_msg,"education");
                        
                        
                    }

        exit;
       
    }

    public function submitNonFormalForm(){
        $formSelected = "nonformal";

        $prisoner_no = $this->params['data']['NonFormalEducation']['prisoner_no'];

        $this->request->data['NonFormalEducation']['prisoner_id']=$prisoner_no;
        //$data['prisoner_id'] = $prisoner_no;

        $informalDetails = $this->InformalCouncelling->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'InformalCouncelling.councellor_id',
                        'InformalCouncelling.opinion_by_prisoner'
                    
                    ),
                    'conditions'    => array(
                        'InformalCouncelling.prisoner_id' =>$prisoner_no,
                        
                    )
                
                ));
        $PrisonerDetail = $this->Prisoner->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no',
                    ),
                    'conditions'    => array(
                        'Prisoner.id' =>$prisoner_no,
                        
                    )
                
                ));
        //$data['prisoner_no'] = $PrisonerDetail['Prisoner']['prisoner_no'];
        if(isset($PrisonerDetail['Prisoner']['prisoner_no'])){
            
        $this->request->data['NonFormalEducation']['prisoner_no']=$PrisonerDetail['Prisoner']['prisoner_no'];
         }
         else{
            $this->request->data['NonFormalEducation']['prisoner_no']="";
         }

        if (isset($this->request->data['NonFormalEducation']['doc'])){

        $dateOfCouncelling = date('Y-m-d H:i:s',strtotime($this->request->data['NonFormalEducation']['doc'])) ;
        }
        else{
           $dateOfCouncelling=""; 
        }

        if (isset($this->request->data['NonFormalEducation']['end_date'])){

         $end_date = date('Y-m-d H:i:s',strtotime($this->request->data['NonFormalEducation']['end_date'])) ;
            }

        else{
            $end_date ="";
        }
            if (isset($this->request->data['NonFormalEducation']['start_date'])){
          $start_date = date('Y-m-d H:i:s',strtotime($this->request->data['NonFormalEducation']['start_date'])) ;
         }
         else{
            $start_date="";
         }
        $this->request->data['NonFormalEducation']['date_of_councelling'] = $dateOfCouncelling;
       

            
            if ($this->NonFormalEducation->saveAll($this->request->data['NonFormalEducation'])) {
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                $result = 'Saved Successfully !' ;
            } else {
                //debug($this->InformalCouncelling->validationErrors);
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
                $result = 'Saving Failed !';
            }

        
       // echo $result ; exit;
           // echo $formSelected;

            if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Non Formal Education created.";
                        $notifyUser = $this->User->find('list',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        debug($notifyUser);
                        $this->addManyNotification($notifyUser,$notification_msg,"education");
                        
                        
                    }
        exit;
 }
    public function getInformalDetails(){
        $this->layout   = 'ajax';

        $prisoner_no = $this->params['data']['prisoner_id'];
        $informalDetails = $this->InformalCouncelling->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'InformalCouncelling.councellor_id',
                        'InformalCouncelling.opinion_by_prisoner'
                    
                    ),
                    'conditions'    => array(
                        'InformalCouncelling.prisoner_id' =>$prisoner_no,
                        
                    )
                
                ));
       
        echo json_encode($informalDetails) ;exit;
    }
	public function dataAjax(){
        //$prisoner_no = $this->params['data']['prisoner_id'];
        $condition      = array();
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("InformalCouncelling.prisoner_no like '%$prisonerNo%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("InformalCouncelling.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['stheme']) && $this->params['data']['Search']['stheme'] != ''){
            $themeId = $this->params['data']['Search']['stheme'];

            $condition += array("InformalCouncelling.theme_id"=>$themeId);
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
           $sprisoner_start_date =  date('Y-m-d H:i:s', strtotime($this->params['data']['Search']['sprisoner_start_date']));

            $condition += array( 'InformalCouncelling.start_date ="'.$sprisoner_start_date.'"');
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
           $sprisoner_end_date =  date('Y-m-d H:i:s', strtotime($this->params['data']['Search']['sprisoner_end_date']));
            $condition += array( 'InformalCouncelling.end_date ="'.$sprisoner_end_date.'"');
        }
		$this->layout   = 'ajax';
        $modelName = 'InformalCouncelling';
        
        
		$this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas,
            'modelName'        => $modelName,
            'datas2'=>$this->params['data']
        ));

	}
	public function formalDataAjax(){
        //echo $prisoner_no;exit;
		$this->layout   = 'ajax';
        $modelName = 'FormalEducation';
       
        $condition      = array('FormalEducation.is_trash'=>0);
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("FormalEducation.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("FormalEducation.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(FormalEducation.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(FormalEducation.end_date) <=' => date('Y-m-d',strtotime($end_date))
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
        ) +$limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

	}

	public function NonFormalDataAjax(){

		$this->layout   = 'ajax';
        $modelName = 'NonFormalEducation';
     /*   $condition = array(
                    $modelName.'.prisoner_id'=>$prisoner_no
                );*/
        $condition      = array('NonFormalEducation.is_trash'=>0);
        
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("NonFormalEducation.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("NonFormalEducation.prisoner_name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(NonFormalEducation.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(NonFormalEducation.end_date) <=' => date('Y-m-d',strtotime($end_date))
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
            'nonFormalDatas'         => $datas,
            'modelName'        => $modelName
        ));

	}
}