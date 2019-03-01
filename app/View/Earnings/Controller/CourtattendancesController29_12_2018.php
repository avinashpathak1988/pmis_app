<?php
App::uses('AppController', 'Controller');
class CourtattendancesController  extends AppController {
	public $layout='table';
    public $uses=array('Prisoner', 'Courtattendance', 'Court', 'Magisterial','Offence','Courtlevel','PrisonerSentence','ApprovalProcess','Gatepass','PresidingJudge','CauseList','EscortTeam','ReturnFromCourt','PrisonerOffence');
    public function courtscheduleList()
    {
    	$this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'Courtattendance', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of court attendence list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Courtattendance list of prisoner are pending for review.";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array( 
                                "user_id"   => $notifyUser['User']['id'],
                                "content"   => $notification_msg,
                                "url_link"   => "courtattendances/courtscheduleList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Courtattendance list of prisoner are pending for approve";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array(
                                "user_id"   => $notifyUser['User']['id'],
                                "content"   => $notification_msg,
                                "url_link"  => "courtattendances/courtscheduleList",                    
                            ));
                        }
                    }
                    //notification on approval of Disciplinary proceeding list --END--

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Approved"){
                            $reference_id = '';
                            foreach ($this->request->data['ApprovalProcess'] as $key => $value) {
                                $reference_id = $value['fid'];
                            }
                            $gatepassArr = array(
                                "prisoner_id"   => 1,
                                "user_id"       => 1,
                                "gatepass_type" => 'Court Attendance',
                                "model_name"    => 'Courtattendance',
                                "reference_id"  => $reference_id,
                            );
                            //$this->createGatepass($gatepassArr);
                            $this->Session->write('message','Approved Successfully !');}
                        
                    }
                    else{
                        $this->Session->write('message','Saved Successfully !');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        }
    	$prisonerListData = $this->Courtattendance->find('list', array(
                "joins" => array(
                    array(
                        "table" => "prisoners",
                        "alias" => "Prisoner",
                        "type" => "left",
                        "conditions" => array(
                            "Courtattendance.prisoner_id = Prisoner.id"
                        ),
                    ),
                ),
				'fields'		=> array(
					'Prisoner.id',
					'Prisoner.prisoner_no',
				),
				'conditions'	=> array(
					'Prisoner.prison_id'        => $this->Auth->user('prison_id')
				),
			));
        // if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        //     {
        //         $sttusListData =array("Draft"=>"Draft","Saved"=>"Final Save");
                
        //     }
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        //     {
        //         $sttusListData =array("Saved"=>"Final Save","Review-Rejected"=>"Review-Rejected");
                
        //     }
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        //     {
        //         $sttusListData =array("Reviewed"=>"Reviewed");
                
        //     }
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        // $statusList = array('Approved'=>'Approved', 'Approve-Rejected'=>'Approve-Rejected'); 
        // if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        // {
        //     $statusList += array('Draft'=>'Draft');     
        //     $default_status = 'Draft';
        // } 
        // if(($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')))
        // {
        //     $statusList += array('Reviewed'=>'Reviewed', 'Review-Rejected'=>'Review-Rejected', 'Saved'=>'Saved'); 
        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        //     {
        //         $default_status = 'Saved';
        //     }
        // }
        // if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        // {
        //    $statusList += array('Reviewed'=>'Reviewed'); 
        //     $default_status = 'Reviewed';
        // }
    	$this->set(array(
					'prisonerListData'					=> $prisonerListData,
                    'sttusListData'=>$statusList,
                    'default_status'    => $default_status
				));
    }
    
    public function courtsscheduleListAjax(){
    	$this->layout 			= 'ajax';
    	$prisoner_id 	= '';
        $status = '';
    	$condition 				= array(
            'Courtattendance.is_trash'      => 0,
    		'Courtattendance.prison_id'		=> $this->Session->read('Auth.User.prison_id'),            
    	);
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Courtattendance.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Courtattendance.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Courtattendance.status !='=>'Draft');
                $condition      += array('Courtattendance.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Courtattendance.status !='=>'Draft');
                $condition      += array('Courtattendance.status !='=>'Saved');
                $condition      += array('Courtattendance.status !='=>'Review-Rejected');
                $condition      += array('Courtattendance.status'=>'Reviewed');
            }   
        }
    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
    		$prisoner_id = $this->params['named']['prisoner_id'];
    		$condition += array(
    			'Courtattendance.prisoner_id'	=> $prisoner_id,
    		);
    	}
		    	
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    	
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'Courtattendance.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('Courtattendance');
    	$this->set(array(
    		'datas'						=> $datas,
    		'prisoner_id'					=> $prisoner_id,
            'status'=>$status
    	));
    }

    public function getOffenceName($offence_id){
        $offence_idarr=explode(",",$offence_id);
        $offence_name='';
        foreach($offence_idarr as $values){
        $datas=$this->Offence->find('first',array(
                'conditions'=>array(
                'Offence.id'=>$values,
                ),
                'fields'        => array(
                
                'Offence.name',
              ),
            ));
                    $offence_name .=$datas['Offence']['name'].',';
        
      }
      $offence_name=rtrim($offence_name,",");
      return $offence_name;
    }
    public function courtsscheduleListAjaxpdf(){
        $this->layout           = 'ajax';
        $prisoner_id    = '';
        $condition              = array(
            'Courtattendance.is_trash'      => 0,
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Courtattendance.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Courtattendance.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Courtattendance.status !='=>'Draft');
                $condition      += array('Courtattendance.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Courtattendance.status !='=>'Draft');
                $condition      += array('Courtattendance.status !='=>'Saved');
                $condition      += array('Courtattendance.status !='=>'Review-Rejected');
                $condition      += array('Courtattendance.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Courtattendance.prisoner_id'   => $prisoner_id,
            );
        }
                
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='export_xls';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Courtattendance.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        $this->set(array(
            'datas'                     => $datas,
            'prisoner_id'                   => $prisoner_id,
        ));
    }
	public function index($uuid) {
		$this->set('funcall',$this);
       // debug($this->request->data); exit;

		if($uuid){
			/*
			 *Query for validate uuid of priosners
			 */
			$prisonerData = $this->Prisoner->find('first', array(
				'recursive'		=> -1,
				'conditions'	=> array(
					'Prisoner.uuid'		=> $uuid,
				),
			));
            $this->loadModel('Magisterial');
            /*$magisterialList=$this->Magisterial->find('list',array(
                  'conditions'=>array(
                    'Magisterial.is_enable'=>1,
                    'Magisterial.is_trash'=>0,
                  ),
                  'order'=>array(
                    'Magisterial.name'
                  )
            ));*/
            
            $magisterialList=$this->Courtlevel->find('list',array(
                  'conditions'=>array(
                    'Courtlevel.is_enable'=>1,
                    'Courtlevel.is_trash'=>0,
                  ),
                  'order'=>array(
                    'Courtlevel.name'
                  )
            ));
            

            $this->loadModel('CauseList');
            $causeData = $this->CauseList->find('all',array(
                "recursive" => -1,
                "joins" => array(
                    array(
                        "table" => "courtattendances",
                        "alias" => "Courtattendance",
                        "type" => "left",
                        "conditions" => array(
                            "Courtattendance.cause_list_id = CauseList.id"
                        ),
                    ),
                ),
                'conditions'=>array("OR"=>array("Courtattendance.status IS NULL",
                    "Courtattendance.status IN ('Review-Rejected','Approve-Rejected')"),
                    'CauseList.is_trash'=>0,
                    'CauseList.prisoner_id'=>$prisonerData['Prisoner']['id'],
                ),
            ));
            

            $causeList = array();
            if(isset($causeData) && is_array($causeData) && count($causeData)>0){
                foreach ($causeData as $key => $value) {
                    $causeList[$value['CauseList']['id']] = $value['CauseList']['high_court_case_no']."(".date("d-m-Y",strtotime($value['CauseList']['session_date'])).")";
                }
            }
            
            $caseTypeList =array(
                '1'=>'Capital Case',
                '2'=>'Petty Case'
            );
            $remarksList =array(
                'Mention'=>'Mention',
                'Commited'=>'Commited',
                'Hearing'=>'Hearing',
                'Acuatal or Prema Facie' => 'Acuatal or Prema Facie',
                'Defence'=>'Defence',
                'Acuatal and convicted'=>'Acuatal and convicted',
                'Sentence and Appeal'=>'Sentence and Appeal',

            );
            $caseStatusList =array(
                'Mention'=>'Mention',
                'Commitment'=>'Commitment',
                'Hearing'=>'Hearing',
                'Ruling'=>'Ruling',
                'Defence'=>'Defence',
                'Judgement'=>'Judgement',
                'Sentence'=>'Sentence'
            );
             $this->loadModel('PrisonerSentence');
                $PrisonerCaseFile=$this->PrisonerSentence->find('list',array(
                     'fields'=>array(
                        'PrisonerSentence.id',
                        'PrisonerSentence.case_file_no'
                    ),
                      'conditions'=>array(
                        //'PrisonerCaseFile.is_enable'=>1,
                        'PrisonerSentence.is_trash'=>0,
                       // 'PrisonerCaseFile.prisoner_id'=>$prisoner_id
                      ),
                      'order'=>array(
                        'PrisonerSentence.id' => 'ASC'
                      )
                ));


             
                //debug($Prsionersentenacelist); exit;
			if(isset($prisonerData['Prisoner']['id']) && (int)$prisonerData['Prisoner']['id'] != 0){
				$courtList 		= array();
				$prisoner_id 	= $prisonerData['Prisoner']['id'];
                /*
                 *Code for add the cause list records
                */                  
                if(isset($this->data['CauseList']) && is_array($this->data['CauseList']) && count($this->data['CauseList']) >0){
                   // debug($this->data); exit;
                    //attendance_date
                    if(isset($this->request->data['CauseList']['session_date']) && $this->request->data['CauseList']['session_date'] != ''){
                        $this->request->data['CauseList']['session_date'] = date('Y-m-d', strtotime($this->request->data['CauseList']['session_date']));
                    }
                    if(isset($this->request->data['CauseList']['next_date']) && $this->request->data['CauseList']['next_date'] != ''){
                        $this->request->data['CauseList']['next_date'] = date('Y-m-d', strtotime($this->request->data['CauseList']['next_date']));
                    }
                    if(isset($this->data['CauseList']['uuid']) && $this->data['CauseList']['uuid'] == ''){
                        $uuidArr = $this->CauseList->query("select uuid() as code");
                        $this->request->data['CauseList']['uuid']         = $uuidArr[0][0]['code'];
                    }
                    $this->request->data['CauseList']['prisoner_id']  = $prisoner_id;
                    $this->request->data['CauseList']['prison_id'] = $this->Auth->user('prison_id');   
                    if($this->CauseList->save($this->request->data)){
                        $this->Session->write('message_type','success');
                        if($this->request->data['CauseList']['id']==""){
                            $this->Session->write('message','Saved Successfully !');    
                        }
                        else{
                            $this->Session->write('message','Updated Successfully !');
                        }
                        $this->redirect('/courtattendances/index/'.$uuid.'#causeList');
                    }else{
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }
                /*
                 *Code for edit the cause list records
                */              
                if(isset($this->data['CauseListEdit']['id']) && (int)$this->data['CauseListEdit']['id'] != 0){
                    if($this->CauseList->exists($this->data['CauseListEdit']['id'])){
                        $this->data = $this->CauseList->findById($this->data['CauseListEdit']['id']);
                    }
                }
                /*
                 *Code for delete the cause list records
                 */ 
                if(isset($this->data['CauseListDelete']['id']) && (int)$this->data['CauseListDelete']['id'] != 0){
                    if($this->CauseList->exists($this->data['CauseListDelete']['id'])){
                        $this->CauseList->id = $this->data['CauseListDelete']['id'];
                        if($this->CauseList->saveField('is_trash',1)){
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Deleted Successfully !');
                        }else{
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Delete Failed !');
                        }
                        $this->redirect('/courtattendances/index/'.$uuid.'#causeList');                      
                    }
                }

				/*
				 *Code for add the court attendance records
				*/					
				if(isset($this->data['Courtattendance']) && is_array($this->data['Courtattendance']) && count($this->data['Courtattendance']) >0){
					$offence_id='';
                    // code by partha
                    if(isset($this->request->data['Courtattendance']['offence_id']) && $this->request->data['Courtattendance']['offence_id']!= '')
                    {
                        foreach ($this->request->data['Courtattendance']['offence_id'] as $value) {
                        $offence_id .=$value.',';
                        }
                    }
					
					$offence_id=rtrim($offence_id,",");
					$this->request->data['Courtattendance']['offence_id']=$offence_id;
                    //attendance_date
					if(isset($this->request->data['Courtattendance']['attendance_date']) && $this->request->data['Courtattendance']['attendance_date'] != ''){
                        // $date = $this->request->data['Courtattendance']['attendance_date'];
                        // $res = explode("-", $date);
                        // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                        // echo $changedDate; // prints 2014-10-24
						$this->request->data['Courtattendance']['attendance_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['attendance_date']));
					}
                    if(isset($this->request->data['Courtattendance']['production_warrent_date']) && $this->request->data['Courtattendance']['production_warrent_date'] != ''){
                        // $date = $this->request->data['Courtattendance']['attendance_date'];
                        // $res = explode("-", $date);
                        // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                        // echo $changedDate; // prints 2014-10-24
                        $this->request->data['Courtattendance']['production_warrent_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['production_warrent_date']));
                    }
                    if(isset($this->request->data['Courtattendance']['court_date']) && $this->request->data['Courtattendance']['court_date'] != ''){
                       
                        $this->request->data['Courtattendance']['court_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['court_date']));
                    }
                     if(isset($this->request->data['Courtattendance']['cause_date']) && $this->request->data['Courtattendance']['cause_date'] != ''){
                       
                        $this->request->data['Courtattendance']['cause_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['cause_date']));
                    }
					if(isset($this->data['Courtattendance']['uuid']) && $this->data['Courtattendance']['uuid'] == ''){
						$uuidArr = $this->Courtattendance->query("select uuid() as code");
						$this->request->data['Courtattendance']['uuid'] 		= $uuidArr[0][0]['code'];
					}
                    $this->request->data['Courtattendance']['prisoner_id']  = $prisoner_id;  
					$this->request->data['Courtattendance']['prison_id'] 	= $this->Auth->user('prison_id');
                   // debug($this->data);	exit;
                    //debug($this->validationErrors); exit; 
                   // debug($this->Session->read()); exit; 

                    $this->Courtattendance->recursive=-1;					
					if($this->Courtattendance->save($this->data)){
	                    $this->Session->write('message_type','success');
                        if($this->request->data['Courtattendance']['id']==""){
                            $this->Session->write('message','Saved Successfully !');    
                        }
                       

	                    else{
                            $this->Session->write('message','Updated Successfully !');
                        }
	                    $this->redirect('/courtattendances/index/'.$uuid);
					}else{
		                $this->Session->write('message_type','error');
		                $this->Session->write('message','Saving Failed !');
					}
				}
				/*
				 *Code for edit the court attendance records
				*/			
            	
		        if(isset($this->data['CourtattendanceEdit']['id']) && (int)$this->data['CourtattendanceEdit']['id'] != 0){

		            if($this->Courtattendance->exists($this->data['CourtattendanceEdit']['id'])){
		                $this->data = $this->Courtattendance->findById($this->data['CourtattendanceEdit']['id']);

		            }
		        }

		        /*
		         *Code for delete the court attendance records
		         */	
		        if(isset($this->data['CourtattendanceDelete']['id']) && (int)$this->data['CourtattendanceDelete']['id'] != 0){
		            if($this->Courtattendance->exists($this->data['CourtattendanceDelete']['id'])){
	                    $this->Courtattendance->id = $this->data['CourtattendanceDelete']['id'];
	                    if($this->Courtattendance->saveField('is_trash',1)){
							$this->Session->write('message_type','success');
		                    $this->Session->write('message','Deleted Successfully !');
	                    }else{
							$this->Session->write('message_type','error');
		                    $this->Session->write('message','Delete Failed !');
	                    }
	                    $this->redirect('/courtattendances/index/'.$uuid."#produceToCourt");		                
		            }
		        }	

				/*
				 *Query for get the Magistrail area list
				 */
				
				$prissentence=$this->PrisonerSentence->find('first', array(
					'recursive'		=> -1,
                    'conditions'    => array(
                        'PrisonerSentence.prisoner_id'  => $prisonerData['Prisoner']['id'],
                        // 'Offence.is_enable'      => 1,
                        // 'Offence.is_trash'       => 0,
                    ),
				));
                // debug($prissentence);
                ///isset($prissentence["PrisonerSentence"]["offence"]) && $prissentence["PrisonerSentence"]["offence"]!=''
                $this->loadModel('PrisonerOffence');
                $prisonerOffenceList = $this->PrisonerOffence->find("list", array(
                    "conditions"    => array(
                        "PrisonerOffence.prisoner_id"=> $prisonerData['Prisoner']['id'],
                        // "PrisonerOffence.is_enable"=> 1,
                        // "PrisonerOffence.is_trash"=> 0,
                        // "PrisonerOffence.status"=> "Approved",
                    ),
                    "fields"    => array(
                        "PrisonerOffence.offence",
                        "PrisonerOffence.offence",
                    ),
                ));
                // print_r($prisonerOffenceList);
                if(isset($prisonerOffenceList) && is_array($prisonerOffenceList) && count($prisonerOffenceList)>0){
                    $offenceList=$this->Offence->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Offence.id',
                            'Offence.name',
                        ),
                        'conditions'    => array(
                            'Offence.id IN ('.implode(",", $prisonerOffenceList).')',
                            // 'Offence.is_enable'      => 1,
                            // 'Offence.is_trash'       => 0,
                        ),
                        'order'         => array(
                            'Offence.name',
                        ),
                    ));
                }else{
                    $offenceList=array();
                }
				
				$magestrilareaList = $this->Magisterial->find('list', array(
					'recursive'		=> -1,
					'fields'		=> array(
						'Magisterial.id',
						'Magisterial.name',
					),
					'conditions'	=> array(
						'Magisterial.is_enable'		=> 1,
						'Magisterial.is_trash'		=> 0,
					),
					'order'			=> array(
						'Magisterial.name',
					),
				));
				/*
				 *Query for get the court List
				 */
				if(isset($this->data['Courtattendance']['magisterial_id']) && (int)$this->data['Courtattendance']['magisterial_id'] != 0){
					$courtList = $this->Court->find('list', array(
						'recursive'		=> -1,
						'fields'		=> array(
							'Court.id',
							'Court.name',
						),
						'conditions'	=> array(
							'Court.is_enable'		=> 1,
							'Court.is_trash'		=> 0,
							'Court.magisterial_id'	=> $this->data['Courtattendance']['magisterial_id'],
						),
						'order'			=> array(
							'Court.name'
						),
					));
				}

                /* return from court*/
            
                //debug($this->request->data['ReturnFromCourt']);exit;    
                if(isset($this->data['ReturnFromCourt']) && is_array($this->data['ReturnFromCourt']) && count($this->data['ReturnFromCourt']) >0){
                    if(isset($this->request->data['ReturnFromCourt']['session_date']) && $this->request->data['ReturnFromCourt']['session_date'] != ''){
                        $this->request->data['ReturnFromCourt']['session_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['session_date']));
                    }
                    if(isset($this->request->data['ReturnFromCourt']['commitment_date']) && $this->request->data['ReturnFromCourt']['commitment_date'] != ''){
                        $this->request->data['ReturnFromCourt']['commitment_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['commitment_date']));
                    }
                    if(isset($this->request->data['ReturnFromCourt']['conviction_date']) && $this->request->data['ReturnFromCourt']['conviction_date'] != ''){
                        $this->request->data['ReturnFromCourt']['conviction_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['conviction_date']));
                    }
                    if(isset($this->request->data['ReturnFromCourt']['aquited_date']) && $this->request->data['ReturnFromCourt']['aquited_date'] != ''){
                        $this->request->data['ReturnFromCourt']['aquited_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['aquited_date']));
                    }
                    //$this->data['ReturnFromCourt']['uuid']=$uuid; 
                    $this->request->data['ReturnFromCourt']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                    if($this->ReturnFromCourt->saveAll($this->request->data)){
                        $this->Session->write('message_type','success');
                        if($this->request->data['ReturnFromCourt']['id']==""){
                            $this->Session->write('message','Saved Successfully !');    
                        }
                        else{
                            $this->Session->write('message','Updated Successfully !');
                        }
                        $this->redirect('/courtattendances/index/'.$uuid);
                    }else{
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }


                                if(isset($this->data['ReturnFromCourtEdit']['id']) && (int)$this->data['ReturnFromCourtEdit']['id'] != 0){
                    if($this->ReturnFromCourt->exists($this->data['ReturnFromCourtEdit']['id'])){
                        $this->data = $this->ReturnFromCourt->findById($this->data['ReturnFromCourtEdit']['id']);
                    }
                }
                if(isset($this->data['ReturnFromCourtDelete']['id']) && (int)$this->data['ReturnFromCourtDelete']['id'] != 0){
                    if($this->ReturnFromCourt->exists($this->data['ReturnFromCourtDelete']['id'])){
                        $this->ReturnFromCourt->id = $this->data['ReturnFromCourtDelete']['id'];
                        if($this->ReturnFromCourt->saveField('is_trash',1)){
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Deleted Successfully !');
                        }else{
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Delete Failed !');
                        }
                        $this->redirect('/courtattendances/index/'.$uuid.'#returnFromCourt');                      
                    }
                }
                $mentalcaseList=array("Yes"=>"Convicted","No"=>"Un-Convicted");
            /*return from court ends*/
				
				$this->set(array(
					'uuid'					=> $uuid,
					'courtList'				=> $courtList,
					'magestrilareaList'		=> $magestrilareaList,
					'offenceList'           => $offenceList,
					'prisoner_id'           => $prisonerData['Prisoner']['id'],
                    'magisterialList'       => $magisterialList,
                    'causeList'             => $causeList,
                    'caseTypeList'          => $caseTypeList,
                    'caseStatusList'        => $caseStatusList,
                    'case_file_no'      => $PrisonerCaseFile,
                    'offenceIdList'      => $offenceIdList,
                    'remarksList'           => $remarksList,
                    'mentalcaseList'       => $mentalcaseList
				));
			}else{
				return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
			}
		}else{
			return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
		}
    }
     public function showCount($id=''){
        $this->autoRender = false;
        if(isset($id) && (int)$id != 0){
           $condition = array();
            $this->loadModel('PrisonerSentence');
            $insertedRecord = $this->PrisonerSentence->find("list",array(
                "conditions"    => array(
                    "PrisonerSentence.case_id"   => $id,
                ),
                "fields"    => array(
                    "PrisonerSentence.offence_id"
                ),
            ));
            //debug($insertedRecord); exit;
            if(isset($insertedRecord) && count($insertedRecord)>0){
                $condition = array("PrisonerOffence.id NOT IN (".implode(",", $insertedRecord).")");
            }
            $offenceList = $this->PrisonerOffence->find('all', array(
                //'recursive'     => -1,
                'conditions'=> array('PrisonerOffence.prisoner_case_file_id' => $id)+$condition,
                'fields'        => array(
                    'PrisonerOffence.id,PrisonerOffence.offence_no',
                ),
            ));
            //debug($countyList);
            if(is_array($offenceList) && count($offenceList)>0){
                echo '<option value="">-- Select Offence --</option>';
                foreach($offenceList as $offenceKey=>$offenceVal){
                    echo '<option value="'.$offenceVal['PrisonerOffence']['id'].'">'.$offenceVal['PrisonerOffence']['offence_no'].'</option>';
                }
            }else{
                //echo "hwsafgcsh";
                echo '<option value="">-- Select Offence --</option>';
            }
        }else{
            echo '<option value="">-- Select Offence --</option>';
        }
    } 
     function getReturnFromCourt()
    {
        $this->autoRender = false;
        $offence = ''; $result = '';
        $offence_id = $this->request->data['offence_id'];
        $prisoner_id = $this->request->data['prisoner_id'];
        //get offence id
        $offenceData = $this->PrisonerOffence->find('first', array(
            'recursive'     => -1,
            'conditions'    => array(
                'PrisonerOffence.id'   => $offence_id
            )
        ));
        if(isset($offenceData) && !empty($offenceData) && count($offenceData) > 0)
        {
            $offence = $offenceData['PrisonerOffence']['offence'];
        }
        if($offence != '')
        {
            $returnFromCourtData = $this->ReturnFromCourt->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    //'ReturnFromCourt.case_status'   => 'Sentence',
                    //'ReturnFromCourt.is_trash'      => 0,
                    'ReturnFromCourt.prisoner_id'   => $prisoner_id,
                    'ReturnFromCourt.offence_id'    => $offence
                ),
                'order'         => array(
                    'ReturnFromCourt.id' => 'DESC'
                )
            ));
        }
        if(isset($returnFromCourtData[0]['ReturnFromCourt']))
        {
            $resultdata['commitment_date'] = '';
            $resultdata['conviction_date'] = '';
            //echo '<pre>'; print_r($returnFromCourtData);  exit;
            if(isset($returnFromCourtData[0]['ReturnFromCourt']['commitment_date']) && ($returnFromCourtData[0]['ReturnFromCourt']['commitment_date'] != '0000-00-00'))
            {
                $resultdata['commitment_date'] = date('d-m-Y', strtotime($returnFromCourtData[0]['ReturnFromCourt']['commitment_date']));
            }
            if(isset($returnFromCourtData[0]['ReturnFromCourt']['conviction_date']) && ($returnFromCourtData[0]['ReturnFromCourt']['conviction_date'] != '0000-00-00'))
            {
                $resultdata['conviction_date'] = date('d-m-Y', strtotime($returnFromCourtData[0]['ReturnFromCourt']['conviction_date']));
            }
            // if(isset($returnFromCourtData[0]['ReturnFromCourt']['commitment_date']) && ($returnFromCourtData[0]['ReturnFromCourt']['commitment_date'] != '0000-00-00'))
            // {
            //     $resultdata['commitment_date'] = date('d-m-Y', strtotime($returnFromCourtData[0]['ReturnFromCourt']['commitment_date']));
            // }
            $result = json_encode($resultdata);
        }
        echo $result; exit;
    }
    public function fetchCaseTypeAjax(){
        $this->layout           = 'ajax';
        
        $caseType = '' ;
        $caseFileNumber= '' ;
        if(isset($this->request->data['uuid']) && $this->request->data['uuid'] != ''){
            $uuid = $this->request->data['uuid'];

            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
            $prisoner_id    = $prisonerData['Prisoner']['id'];
            $this->loadModel('PrisonerAdmission');
            $this->loadModel('PrisonerOffence');
            $prisonerSentenceData = $this->PrisonerAdmission->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerAdmission.prisoner_id'     => $prisoner_id,
                        
                    ),
                ));
            if(isset($prisonerSentenceData['PrisonerAdmission']['id'])){
                    $offence_category_id = $this->PrisonerOffence->field("offence_category_id",array("PrisonerOffence.prisoner_id"=>$prisoner_id));
                    $caseType = $offence_category_id;
                    $caseFileNumber = $prisonerSentenceData['PrisonerAdmission']['case_file_no'];

                }
            /*if(isset($this->request->data['offence_id']) && $this->request->data['offence_id'] != ''){
                $offence_id = $this->request->data['offence_id'];
                 //$this->loadModel('ReturnFromCourt');
                $prisonerOffenceData = $this->PrisonerOffence->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerOffence.uuid'     => $this->request->data['uuid'],
                        //'0'=>"ReturnFromCourt.uuid LIKE '%$uuid%'"
                        'PrisonerOffence.offence'     => $offence_id,
                        //'ReturnFromCourt.case_status' => 'Commitment',
                    ),
                ));

                if(isset($prisonerOffenceData['PrisonerOffence']['id'])){
                    $caseType = $prisonerOffenceData['PrisonerOffence']['offence_category_id'];
                }
            
            }*/

        }
        

         echo $caseFileNumber . ',' .$caseType;
        exit;

    }
    public function fetchCommitmentDateAjax(){
        $this->layout           = 'ajax';
        $convictedDate = '' ;
        if(isset($this->request->data['uuid']) && $this->request->data['uuid'] != ''){
            $uuid = $this->request->data['uuid'];
            if(isset($this->request->data['offence_id']) && $this->request->data['offence_id'] != ''){
                $offence_id = $this->request->data['offence_id'];
                 //$this->loadModel('ReturnFromCourt');
                $courtReturnData = $this->ReturnFromCourt->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'ReturnFromCourt.uuid'     => $this->request->data['uuid'],
                        //'0'=>"ReturnFromCourt.uuid LIKE '%$uuid%'"
                        'ReturnFromCourt.offence_id'     => $offence_id,
                        'ReturnFromCourt.case_status' => 'Commitment',
                    ),
                ));

                if(isset($courtReturnData['ReturnFromCourt']['id'])){
                    $convictedDate = $courtReturnData['ReturnFromCourt']['commitment_date'];
                }
            
            }

        }

        
        echo $convictedDate;
        exit;

    }
    
    public function courtattendanceindexAjaxpdf(){
        $this->layout           = 'ajax';
        $production_warrent_no  = '';
        $attendance_date        = '';
        $attendance_time        = '';
        $magisterial_id         = '';
        $court_id               = '';
        $case_no                = '';
        $uuid                   = '';
        $condition              = array(
            'Courtattendance.is_trash'      => 0,
        );
        if(isset($this->params['named']['production_warrent_no']) && $this->params['named']['production_warrent_no'] != ''){
            $production_warrent_no = $this->params['named']['production_warrent_no'];
            $condition += array(
                0 => "Courtattendance.production_warrent_no LIKE '%$production_warrent_no%'"
            );
        }
        if(isset($this->params['named']['attendance_date']) && $this->params['named']['attendance_date'] != ''){
            $attendance_date = $this->params['named']['attendance_date'];
            $condition += array(
                'Courtattendance.attendance_date'   => date('Y-m-d', strtotime($attendance_date)),
            );          
        }
        if(isset($this->params['named']['attendance_time']) && $this->params['named']['attendance_time'] != ''){
            $attendance_time = $this->params['named']['attendance_time'];
            $condition += array(
                'Courtattendance.attendance_time'   => $attendance_time,
            );          
        }  
        if(isset($this->params['named']['magisterial_id']) && $this->params['named']['magisterial_id'] != ''){
            $magisterial_id = $this->params['named']['magisterial_id'];
            $condition += array(
                'Courtattendance.magisterial_id'    => $magisterial_id,
            );              
        }  
        if(isset($this->params['named']['court_id']) && $this->params['named']['court_id'] != ''){
            $court_id = $this->params['named']['court_id'];
            $condition += array(
                'Courtattendance.court_id'  => $court_id,
            );          
        }
        if(isset($this->params['named']['case_no']) && $this->params['named']['case_no'] != ''){
            $case_no = $this->params['named']['case_no'];
            $condition += array(
                1 => "Courtattendance.case_no LIKE '%$case_no%'"
            );          
        }
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
            $prisoner_id    = $prisonerData['Prisoner']['id'];
            $condition += array(
                'Courtattendance.prisoner_id'  => $prisoner_id,
            ); 
        }       
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Courtattendance.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
            'case_no'                   => $case_no,
            'court_id'                  => $court_id,
            'magisterial_id'            => $magisterial_id,
            'attendance_time'           => $attendance_time,
            'attendance_date'           => $attendance_date,
            'production_warrent_no'     => $production_warrent_no,
        ));
    }
    public function indexAjax(){
    	$this->layout 			= 'ajax';
    	$production_warrent_no 	= '';
    	$attendance_date 		= '';
    	$attendance_time 		= '';
    	$magisterial_id 		= '';
    	$court_id 				= '';
    	$case_no				= '';
    	$uuid 					= '';
    	$condition 				= array(
    		'Courtattendance.is_trash'		=> 0,
            'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id')
    	);
    	if(isset($this->params['named']['production_warrent_no']) && $this->params['named']['production_warrent_no'] != ''){
    		$production_warrent_no = $this->params['named']['production_warrent_no'];
    		$condition += array(
    			0 => "Courtattendance.production_warrent_no LIKE '%$production_warrent_no%'"
    		);
    	}
		if(isset($this->params['named']['attendance_date']) && $this->params['named']['attendance_date'] != ''){
    		$attendance_date = $this->params['named']['attendance_date'];
    		$condition += array(
    			'Courtattendance.attendance_date'	=> date('Y-m-d', strtotime($attendance_date)),
    		);    		
    	}
		if(isset($this->params['named']['attendance_time']) && $this->params['named']['attendance_time'] != ''){
    		$attendance_time = $this->params['named']['attendance_time'];
    		$condition += array(
    			'Courtattendance.attendance_time'	=> $attendance_time,
    		);     		
    	}  
		if(isset($this->params['named']['magisterial_id']) && $this->params['named']['magisterial_id'] != ''){
    		$magisterial_id = $this->params['named']['magisterial_id'];
    		$condition += array(
    			'Courtattendance.magisterial_id'	=> $magisterial_id,
    		);      		
    	}  
		if(isset($this->params['named']['court_id']) && $this->params['named']['court_id'] != ''){
    		$court_id = $this->params['named']['court_id'];
    		$condition += array(
    			'Courtattendance.court_id'	=> $court_id,
    		);    		
    	}
		if(isset($this->params['named']['case_no']) && $this->params['named']['case_no'] != ''){
    		$case_no = $this->params['named']['case_no'];
    		$condition += array(
    			1 => "Courtattendance.case_no LIKE '%$case_no%'"
    		);    		
    	}
		if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
    		$uuid = $this->params['named']['uuid'];
            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
            $prisoner_id    = $prisonerData['Prisoner']['id'];
            $condition += array(
                'Courtattendance.prisoner_id'  => $prisoner_id,
            ); 
    	}    	

        /*
         * Code for add the update status on court releaes
         */                  
        if(isset($this->data['Courtattendance']) && is_array($this->data['Courtattendance']) && count($this->data['Courtattendance']) >0){
            
            $db = ConnectionManager::getDataSource('default');
            $db->begin();                   
            if($this->Courtattendance->saveAll($this->data)){
                $refId = 0;
                $action = 'Add';
                if(isset($this->request->data['Courtattendance']['id']) && (int)$this->request->data['Courtattendance']['id'] != 0)
                {
                    $refId = $this->request->data['Courtattendance']['id'];
                    $action = 'Edit';
                }
                //save audit log 
                if($this->auditLog('Courtattendance', 'update status', $refId, $action, json_encode($this->data)))
                {
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Update Successfully !');
                    $this->redirect('/courtattendances/index/'.$uuid.'#produceToCourt');
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }


		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    	
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'Courtattendance.id'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('Courtattendance');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas,
    		'case_no'					=> $case_no,
    		'court_id'					=> $court_id,
    		'magisterial_id'			=> $magisterial_id,
    		'attendance_time'			=> $attendance_time,
    		'attendance_date'			=> $attendance_date,
    		'production_warrent_no'		=> $production_warrent_no,
    	));     	      	    	    	    	
    }

    public function indexCauseAjax(){
        $this->layout           = 'ajax';
        $production_warrent_no  = '';
        $attendance_date        = '';
        $attendance_time        = '';
        $magisterial_id         = '';
        $court_id               = '';
        $case_no                = '';
        $uuid                   = '';
        $condition              = array(
            'CauseList.is_trash'      => 0,
            'CauseList.prison_id'     => $this->Session->read('Auth.User.prison_id')
        );
        
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
            $prisoner_id    = $prisonerData['Prisoner']['id'];
            $condition += array(
                'CauseList.prisoner_id'  => $prisoner_id,
            ); 
        }       
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','couse_list_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','couse_list_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'CauseList.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('CauseList');
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
        ));                                         
    }

    public function indexCourtReturnAjax(){
        $this->layout           = 'ajax';
        
        $uuid                   = '';
        $condition              = array(
            'ReturnFromCourt.is_trash'      => 0,
        );
        //debug($this->params);exit;
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
            $condition += array(
                'ReturnFromCourt.uuid' => $uuid,
            );
            
        }       
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','couse_list_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','couse_list_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'ReturnFromCourt.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('ReturnFromCourt');
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
        ));                                         
    }
    
    public function getCourtByMagisterial(){
    	$this->autoRender = false;
    	if(isset($this->data['magisterial_id']) && (int)$this->data['magisterial_id'] != 0){
    		$courtList = $this->Court->find('list', array(
    			'recursive'		=> -1,
    			'fields'		=> array(
    				'Court.id',
    				'Court.name',
    			),
    			'conditions'	=> array(
    				'Court.magisterial_id'	=> $this->data['magisterial_id']
    			),
    			'order'			=> array(
    				'Court.name',
    			),
    		));
    		if(is_array($courtList) && count($courtList)>0){
    			echo '<option value="">--Select Court--</option>';
    			foreach($courtList as $key=>$val){
    				echo '<option value="'.$key.'">'.$val.'</option>';
    			}
    		}else{
    			echo '<option value="">--Select Court--</option>';
    		}
    	}else{
    		echo '<option value="">--Select Court--</option>';
    	}
    }
    
    public function getCourtByCourtLevel(){
        $this->autoRender = false;
        if(isset($this->data['courtlevel_id']) && (int)$this->data['courtlevel_id'] != 0){
            $courtList = $this->Court->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Court.id',
                    'Court.name',
                ),
                'conditions'    => array(
                    'Court.courtlevel_id'  => $this->data['courtlevel_id']
                ),
                'order'         => array(
                    'Court.name',
                ),
            ));
            if(is_array($courtList) && count($courtList)>0){
                echo '<option value="">--Select Court--</option>';
                foreach($courtList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Court--</option>';
            }
        }else{
            echo '<option value="">--Select Court--</option>';
        }
    }
    public function getJudgeByCourt(){
        $this->autoRender = false;
        if(isset($this->data['court_id']) && (int)$this->data['court_id'] != 0){
            $judgeList = $this->PresidingJudge->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PresidingJudge.id',
                    'PresidingJudge.name',
                ),
                'conditions'    => array(
                    'PresidingJudge.court_id'  => $this->data['court_id']
                ),
                'order'         => array(
                    'PresidingJudge.name',
                ),
            ));
            if(is_array($judgeList) && count($judgeList)>0){
                echo '<option value="">--Select Judge--</option>';
                foreach($judgeList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Judge--</option>';
            }
        }else{
            echo '<option value="">--Select Judge--</option>';
        }
    }
   public function getRemarkByCaseStatus(){
        $this->autoRender = false;
        if(isset($this->data['case_status'])){
                $case_status=$this->data['case_status'];
                if($case_status == 'Mention'){
                    echo '<option value="Mention">Mention</option>';
                }else if($case_status == 'Commitment'){
                    echo '<option value="Commited">Commited</option>';
                }
                else if($case_status == 'Hearing'){
                    echo '<option value="Hearing">Hearing</option>';
                }else if($case_status == 'Ruling'){
                    echo '<option value="Acuatal">Acuatal</option>';
                    echo '<option value="Prema Facie">Prema Facie</option>';
                }else if($case_status == 'Defence'){
                    echo '<option value="Defence">Defence</option>';
                    
                }else if($case_status == 'Judgement'){
                    echo '<option value="Acuatal">Acuatal</option>';
                    echo '<option value="Convicted">Convicted</option>';
                    
                }else if($case_status == 'Sentence'){
                    echo '<option value="Sentence">Sentence</option>';
                    echo '<option value="Appeal">Appeal</option>';
                }else{
                    echo '<option value="">--Select Remark--</option>';

                }
        }else{
            echo '<option value="">--Select Remark--</option>';
        }
    }
    public function getCourtlvl(){
    	$this->autoRender = false;
    	if(isset($this->data['court_id']) && (int)$this->data['court_id'] != 0){
    		$courtList = $this->Court->find('first', array(
    			'recursive'		=> -1,
    			'conditions'	=> array(
    				'Court.id'	=> $this->data['court_id']
    			),
    		));
    		$courtLevlList = $this->Courtlevel->find('first', array(
    			'recursive'		=> -1,
    			'conditions'	=> array(
    				'Courtlevel.id'	=> $courtList["Court"]["courtlevel_id"]
    			),
    			
    		));
    		if(is_array($courtLevlList) && count($courtLevlList)>0){
    			
    				echo $courtLevlList["Courtlevel"]["name"];
    			
    		}else{
    			echo '';
    		}
    	}else{
    		echo '';
    	}
    }

    public function courtscheduleGatepassList()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['Gatepass']) && count($this->request->data['Gatepass']) > 0)
            {

                $items = $this->request->data['Gatepass'];
                $gatepassDetails = array();
                foreach ($items as $key => $value) {
                    if(!is_array($value)){
                        $gatepassDetails[$key] = $value;
                    }                   
                }
                $status = $this->setGatepass($items, 'Courtattendance',$gatepassDetails);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Gatepass generated Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect('courtscheduleGatepassList');
            }
        }
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
            ));
        $magestrilareaList = $this->Magisterial->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Magisterial.id',
                        'Magisterial.name',
                    ),
                    'conditions'    => array(
                        'Magisterial.is_enable'     => 1,
                        'Magisterial.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Magisterial.name',
                    ),
                ));
      
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        
        $this->set(array(
                    'prisonerListData'                  => $prisonerListData,
                    'sttusListData'=>$statusList,
                    'default_status'    => $default_status,
                    'magestrilareaList' => $magestrilareaList,
                ));
    }
    
    public function courtsscheduleGatepassListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        
        $status = '';

        $teamList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.prison_id'    => $this->Auth->user('prison_id'),
                'EscortTeam.escort_type'  => "Court",
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));


        $condition              = array(
            'Courtattendance.is_trash'      => 0,
            'Courtattendance.status'      => 'Approved',
            'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id'),
        );
        // debug($this->params['named']);
        if(isset($this->params['named']['magisterial_id']) && $this->params['named']['magisterial_id'] != ''){
            $magisterial_id = $this->params['named']['magisterial_id'];
            $condition += array(
                'Courtattendance.magisterial_id'   => $magisterial_id,
            );
        }

        if(isset($this->params['named']['court_id']) && $this->params['named']['court_id'] != ''){
            $court_id = $this->params['named']['court_id'];
            $condition += array(
                'Courtattendance.court_id'   => $court_id,
            );
        }

        if(isset($this->params['named']['attendance_date']) && $this->params['named']['attendance_date'] != ''){
            $attendance_date = $this->params['named']['attendance_date'];
            $condition += array(
                'date(Courtattendance.attendance_date)'   => date("Y-m-d", strtotime($attendance_date)),
            );
        }
                
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_gatepasslist_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_gatepasslist_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_gatepasslist_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Courtattendance.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status,
            'teamList'          => $teamList,
        ));
    }

    public function setGatepass($items, $model,$gatepassDetails)
    {
        $result = 0;
        if(count($items) > 0)
        {
            $prison_id = $this->Session->read('Auth.User.prison_id');
            $login_user_id = $this->Session->read('Auth.User.id');
            $i = 0;
            $data = array();
            $recordCount = $this->Gatepass->find("count", array(
                "conditions"    => array(
                    "Gatepass.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                ),
            ));
            $notificationPrisoner = array();
            foreach($items as $item){
                if(is_array($item) && count($item)>0){
                    // $recordCount++;
                    $data[$i]['Gatepass']           = $gatepassDetails;
                    $data[$i]['Gatepass']['gp_date']    = date("Y-m-d", strtotime($gatepassDetails['gp_date']));
                    $data[$i]['Gatepass']['gp_no']  = "GP-".str_pad($this->Session->read('Auth.User.prison_id'),3,"0",STR_PAD_LEFT)."-".str_pad($recordCount,5,"0",STR_PAD_LEFT);
                    $uuidArr = $this->Gatepass->query("select uuid() as code");
                    $data[$i]['Gatepass']['uuid']       = $uuidArr[0][0]['code'];
                    
                    $data[$i]['Gatepass']['prison_id']  = $prison_id;
                    $data[$i]['Gatepass']['model_name'] = $model;
                    $data[$i]['Gatepass']['user_id']    = $login_user_id;
                    $data[$i]['Gatepass']['reference_id'] = $item['fid'];                   
                    $data[$i]['Gatepass']['gatepass_type'] = 'Court Attendance';        
                    $gatepassData = $this->Courtattendance->findById($item['fid']);           
                    $data[$i]['Gatepass']['prisoner_id'] = $gatepassData['Courtattendance']['prisoner_id'];
                    $notificationPrisoner[] = $gatepassData['Courtattendance']['prisoner_id'];
                }                
                $i++;
            }
            if(count($data) > 0)
            {
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->Gatepass->saveMany($data))
                {
                    if($this->auditLog('Gatepass', 'gatepass_generation', 0, 'Add', json_encode($data)))
                    {
                        $userList = $this->User->find("list", array(
                            "conditions"    => array(
                                "User.usertype_id"  => Configure::read('GATEKEEPER_USERTYPE'),
                                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                            )
                        ));
                        $prisonerName = array();
                        if(isset($notificationPrisoner) && is_array($notificationPrisoner) && count($notificationPrisoner)>0){
                            foreach ($notificationPrisoner as $notificationPrisonerkey => $notificationPrisonervalue) {
                                $prisonerName[] = $this->getPrisonerName($notificationPrisonervalue);
                            }
                        }
                        if(isset($userList) && is_array($userList) && count($userList)>0 && count($prisonerName)>0){
                            $this->addManyNotification($userList,"Gatepass generated for the prisoner(s) ".implode(", ", $prisonerName),"Gatepasses/gatepassList");
                        }
                        $db->commit();
                        $result = 1;
                    }
                    else 
                    {
                        $db->rollback();
                        $result = 0;
                    }
                }
                else 
                {
                    $db->rollback();
                    $result = 0;
                }
            }
        }
        return $result;
    }

    public function checkCauseListUsed($id){
        return $this->Courtattendance->find("count", array(
            "conditions"    => array(
                "Courtattendance.cause_list_id" => $id,
            ),
        ));
    }

    public function getCauseListDetails(){
        $this->layout = 'ajax';

        $data = $this->CauseList->findById($this->data['id']);
        $this->set(array(
            'data'             => $data,
        ));
    }

    public function courtsTrackList()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
            ));
        $magestrilareaList = $this->Magisterial->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Magisterial.id',
                        'Magisterial.name',
                    ),
                    'conditions'    => array(
                        'Magisterial.is_enable'     => 1,
                        'Magisterial.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Magisterial.name',
                    ),
                ));

        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }

        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));

        $this->set(array(
                    'prisonerListData'                  => $prisonerListData,
                    'magestrilareaList' => $magestrilareaList,
                ));
    }
    
    public function courtsTrackListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $prisoner_name='';
        $sprisoner_no='';
        $status = '';
        $condition              = array(
            'Courtattendance.is_trash'      => 0,
            'Courtattendance.status'      => 'Approved',
        );
        $prison_id      = '';
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
         //debug($this->params['named']);
        if(isset($this->params['named']['magisterial_id']) && $this->params['named']['magisterial_id'] != ''){
            $magisterial_id = $this->params['named']['magisterial_id'];
            $condition += array(
                'Courtattendance.magisterial_id'   => $magisterial_id,
            );
        }

        if(isset($this->params['named']['court_id']) && $this->params['named']['court_id'] != ''){
            $court_id = $this->params['named']['court_id'];
            $condition += array(
                'Courtattendance.court_id'   => $court_id,
            );
        }

        if(isset($this->params['named']['attendance_date']) && $this->params['named']['attendance_date'] != ''){
            $attendance_date = $this->params['named']['attendance_date'];
            $condition += array(
                'date(CauseList.session_date)'   => date("Y-m-d", strtotime($attendance_date)),
            );
        }
        if(isset($this->params['named']['sprisoner_no']) && $this->params['named']['sprisoner_no'] != ''){
            $sprisoner_no = $this->params['named']['sprisoner_no'];
            $condition += array(
                'Prisoner.prisoner_no like "%'.$sprisoner_no.'%"'
            );
        }
        if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prisoner_name = $this->params['named']['prisoner_name'];
            $prisoner_name = str_replace(' ','',$prisoner_name);
            $condition += array(2 => "CONCAT(Prisoner.first_name,  Prisoner.middle_name, Prisoner.last_name) LIKE '%$prisoner_name%'");
            // $condition += array(
            //     'Prisoner.fullname like "%'.$prisoner_name.'%"'
            // );
        }
                
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_tracklist_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_tracklist_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_tracklist_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.prisoner_no'  => 'ASC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status,
            'prison_id'         => $prison_id,
            'sprisoner_no'      => $sprisoner_no,
            'prisoner_name'     => $prisoner_name

        ));
    }

    public function getEscort($id){
        $data = $this->EscortTeam->findById($id);
        $memberData = array();
        if(isset($data['EscortTeam']['members']) && $data['EscortTeam']['members']!=''){
            foreach (explode(",", $data['EscortTeam']['members']) as $key => $value) {
                $memberData[] = $this->getName($value,"User","name");
            }
            return $data['EscortTeam']['name']."(".implode(",", $memberData).")";
        }
    }

    public function courtsLoadReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
            ));
        $magestrilareaList = $this->Magisterial->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Magisterial.id',
                        'Magisterial.name',
                    ),
                    'conditions'    => array(
                        'Magisterial.is_enable'     => 1,
                        'Magisterial.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Magisterial.name',
                    ),
                ));
        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }

        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        
        $this->set(array(
            'prisonerListData'                  => $prisonerListData,
            'magestrilareaList' => $magestrilareaList,
            'prisonList'    => $prisonList,
        ));
    }
    
    public function courtsLoadReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition              = array(
            'Courtattendance.is_trash'      => 0,
            'Courtattendance.judgment'      => 'Draft',
            'Courtattendance.status'      => 'Approved',
        );

        $prison_id      = '';
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Courtattendance.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Courtattendance.prison_id' => $prison_id );
            }
        }
        // debug($this->params['named']);
        if(isset($this->params['named']['magisterial_id']) && $this->params['named']['magisterial_id'] != ''){
            $magisterial_id = $this->params['named']['magisterial_id'];
            $condition += array(
                'Courtattendance.magisterial_id'   => $magisterial_id,
            );
        }

        if(isset($this->params['named']['court_id']) && $this->params['named']['court_id'] != ''){
            $court_id = $this->params['named']['court_id'];
            $condition += array(
                'Courtattendance.court_id'   => $court_id,
            );
        }

        // if(isset($this->params['named']['attendance_date']) && $this->params['named']['attendance_date'] != ''){
        //     $attendance_date = $this->params['named']['attendance_date'];
        //     $condition += array(
        //         'date(Courtattendance.attendance_date)'   => date("Y-m-d", strtotime($attendance_date)),
        //     );
        // }
                
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_load_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_load_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_load_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }       
        $this->paginate = array(
            "recursive"     => -1,
            'conditions'    => $condition,
            "fields"        => array(
                "Courtattendance.magisterial_id",
                "Courtattendance.court_id",
                "Courtattendance.case_no",
                "count(Courtattendance.id) AS no_of_cases",
            ),
            'group'         => array(
                "Courtattendance.magisterial_id",
                "Courtattendance.court_id",
                "Courtattendance.case_no",
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        $finalData = array();
        if(isset($datas) && count($datas)>0){
            foreach ($datas as $key => $value) {
                $finalData[$value['Courtattendance']['magisterial_id']][$value['Courtattendance']['court_id']][] = $value[0]['no_of_cases'];
            }
        }
        $this->set(array(
            'datas'             => $finalData,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status,
            'prison_id'         => $prison_id,
        ));
    }

    public function courtsTrackingReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
            ));
        $magestrilareaList = $this->Magisterial->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Magisterial.id',
                        'Magisterial.name',
                    ),
                    'conditions'    => array(
                        'Magisterial.is_enable'     => 1,
                        'Magisterial.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Magisterial.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonerListData'  => $prisonerListData,
                    'magestrilareaList' => $magestrilareaList,
                ));
    }
    public function stayListReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
            ));
        $magestrilareaList = $this->Magisterial->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Magisterial.id',
                        'Magisterial.name',
                    ),
                    'conditions'    => array(
                        'Magisterial.is_enable'     => 1,
                        'Magisterial.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Magisterial.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonerListData'  => $prisonerListData,
                    'magestrilareaList' => $magestrilareaList,
                ));
    }

    public function stayTrackingReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition      = array('AND' => array( 
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'         => 1,
                'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                'Prisoner.transfer_status !='        => 'Approved',
                'OR' => array( 
                        array('Prisoner.prisoner_sub_type_id' => 7,'date(Prisoner.created) >' => date('Y-m-d',strtotime("-180 days"))), 
                        array('Prisoner.prisoner_sub_type_id' => 3,'date(Prisoner.created) >' => date('Y-m-d',strtotime("-60 days"))), 
                ) 
            ),
            
        );
               
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Prisoner');
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status
        ));
    }
    
    public function courtsTrackingReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition      = array('AND' => array( 
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'         => 1,
                'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                'Prisoner.transfer_status !='        => 'Approved',
                'OR' => array( 
                        array('Prisoner.prisoner_sub_type_id' => 7,'date(Prisoner.created) <' => date('Y-m-d',strtotime("-180 days"))), 
                        array('Prisoner.prisoner_sub_type_id' => 3,'date(Prisoner.created) <' => date('Y-m-d',strtotime("-60 days"))), 
                ) 
            ),
            
        );
               
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Prisoner');
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status
        ));
    }
    public function courtsTrackingReportNew()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
            ));
        $magestrilareaList = $this->Magisterial->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Magisterial.id',
                        'Magisterial.name',
                    ),
                    'conditions'    => array(
                        'Magisterial.is_enable'     => 1,
                        'Magisterial.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Magisterial.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonerListData'  => $prisonerListData,
                    'magestrilareaList' => $magestrilareaList,
                ));
    }
    
    public function courtsTrackingReportNewAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition      = array('AND' => array( 
                'Prisoner.is_trash'                 => 0,
                'Prisoner.prison_id'                => $this->Auth->user('prison_id'),
                'Prisoner.prisoner_type_id'         => Configure::read('REMAND'),
                'PrisonerSentence.date_of_committal <'        => date('Y-m-d',strtotime("-2 years")),
                'Prisoner.transfer_status !='        => 'Approved',                
            ),
            
        );
               
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }     

        $this->paginate = array(
            'recursive'     => -1,
            "joins" => array(
                array(
                    "table" => "prisoner_sentences",
                    "alias" => "PrisonerSentence",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerSentence.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            'conditions'    => $condition,
            "fields"        => array(
                "Prisoner.*",
                "PrisonerSentence.date_of_committal",
            ),
            'order'         => array(
                'Prisoner.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Prisoner');
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status
        ));
    }
}