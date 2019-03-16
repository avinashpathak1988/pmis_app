<?php
App::uses('AppController', 'Controller');
class CourtattendancesController  extends AppController {
    public $layout='table';
    public $uses=array('Prisoner', 'Courtattendance', 'Court', 'Magisterial','Offence','Courtlevel','PrisonerSentence','ApprovalProcess','Gatepass','PresidingJudge','CauseList','EscortTeam','ReturnFromCourt','PrisonerOffence');
    public function courtscheduleList()
    {
        $menuId = $this->getMenuId("/courtattendances/courtscheduleList");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
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
                    'prisonerListData'                  => $prisonerListData,
                    'sttusListData'=>$statusList,
                    'default_status'    => $default_status
                ));
    }
    
    public function courtsscheduleListAjax(){
        $this->layout           = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'Courtattendance.is_trash'      => 0,
            'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id'),            
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
        
        $condition += array('Courtattendance.is_production_warrant !='   => 1);
        
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
    
    public function getAppealCuaseList()
    {
        $this->autoRender = false;
        $case_id = $this->request->data['case_file_id'];
        $caseFileno=$this->PrisonerCaseFile->find('list',array(
                  "recursive" => -1,
                  'conditions'=>array(
                   'PrisonerCaseFile.is_trash'=>0,
                   'PrisonerCaseFile.id'=>$case_id,
                  ),
                  'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no'),
                  'order'=>array(
                    'PrisonerCaseFile.case_file_no'
                  )
            ));
        
        $options = '';      
        if(isset($caseFileno) && $caseFileno != '')
        {
            foreach($caseFileno as $cfkey => $csval)
            {
                
                $options .= '<option value='.$cfkey.'>'.$csval.'</option>';
            }
        }
        echo $options;  
    }
    
    public function getAppealCauseListOffence()
    {
        $this->autoRender = false;
        $case_id = $this->request->data['offence_id'];
         $offenceList = $this->PrisonerOffence->find('all', array(
                'recursive'     => -1,
                'conditions'=> array("PrisonerOffence.id IN (". $case_id ." ) "),
                'fields'        => array(
                    'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                ),
            ));
            
            $offence_name = '';
            if(isset($offenceList) && !empty($offenceList))
            {
                
                foreach($offenceList as $offkey => $offval)
                {
                    $offence_name .= '<option value="'.$offval['PrisonerOffence']['id'].'">'. $this->getName($offval['PrisonerOffence']['offence'],'Offence','name')."(".$offval['PrisonerOffence']['offence_no'].")".'</option>';
                    
                }
            }
            else
            {
                $offence_name = '';
                
            }
        echo $offence_name;
    }
    
    public function index($uuid,$appeal_id='') {
        $this->set('funcall',$this);
        
        /* for cause list records from prisoner sentence appeal*/
        if(isset($appeal_id) && $appeal_id != '')
        {
            $this->loadModel('PrisonerSentenceAppeal');
            //$this->PrisonerSentenceAppeal->recursive = -1;
            $appeal_cause_list = $this->PrisonerSentenceAppeal->find('first',array(
                    'conditions'=>array('PrisonerSentenceAppeal.id' => $appeal_id,'PrisonerSentenceAppeal.appeal_status' => 'Cause List'),
                    'fields'=>array('PrisonerSentenceAppeal.case_file_id','PrisonerSentenceAppeal.offence_id','PrisonerSentenceAppeal.courtlevel_id','PrisonerSentenceAppeal.court_id','PrisonerSentenceAppeal.appeal_no','PrisonerSentenceAppeal.prisoner_no','PrisonerSentenceAppeal.created'),   
            ));
            //debug($appeal_cause_list); exit;
        }
        /* end */

        if($uuid){
            /*
             *Query for validate uuid of priosners
             */
            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
              $courtLevelList  = $this->Courtlevel->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Courtlevel.id',
                        'Courtlevel.name',
                    ),
                    'conditions'    => array(
                        'Courtlevel.is_enable'    => 1,
                        'Courtlevel.is_trash'     => 0
                    ),
                    'order'         => array(
                        'Courtlevel.name'
                    ),
                ));
            // debug($courtLevelList); exit;
            
                        
            $this->loadModel('Magisterial');
            $this->loadModel('PrisonerCaseFile');
            $caseFileno=$this->PrisonerCaseFile->find('list',array(
                  "recursive" => -1,
                  'conditions'=>array(
                   'PrisonerCaseFile.is_trash'=>0,
                   'PrisonerCaseFile.prisoner_id'=>$prisonerData['Prisoner']['id'],
                  ),
                  'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no'),
                  'order'=>array(
                    'PrisonerCaseFile.case_file_no'
                  )
            ));
            
            $casefilesToCourt = $this->getTocourtFileNo($prisonerData['Prisoner']['id'],$prisonerData['Prisoner']['prisoner_type_id']);
            
            
            /* for return form court case file no */
            $this->loadModel('Courtattendance');
            $returncaseFileno=$this->Courtattendance->find('all',array(
            "recursive" => -1,
            'conditions'=>array(
                   'Courtattendance.is_trash'=>0,
                   'Courtattendance.status'=>'Approved',
                   'Courtattendance.prisoner_id'=>$prisonerData['Prisoner']['id'],
                   'Courtattendance.uuid'=>$uuid
                  ),
                  'fields'=>array('Courtattendance.case_no'),
                  'order'=>array('Courtattendance.id'=>'DESC'),
                  'limit'=>1,
                  
            ));
            $fromcourtfile = array();
            if(isset($returncaseFileno[0]['Courtattendance']['case_no']) && $returncaseFileno[0]['Courtattendance']['case_no'] != '')
            {
                $fromcourtcasefile = explode(',',$returncaseFileno[0]['Courtattendance']['case_no']);
                
                
                
                foreach($fromcourtcasefile as $rk => $rv)
                {

                    if($rv != '')
                    {
                        $this->loadModel('ReturnFromCourt');
                        $checkFileNO = $this->ReturnFromCourt->find('count',array('conditions'=>array(
                                    'ReturnFromCourt.case_file_number'=>$rv,
                                     'ReturnFromCourt.prisoner_id'=>$prisonerData['Prisoner']['id'],
                                    'ReturnFromCourt.uuid'=>$uuid
                        )
                        
                        ));
                        if($checkFileNO == 0)
                        {
                            $fromcourtfile[$rv] = @$caseFileno[$rv];
                        }                       
                    }               
                }
            }           
            /* end */
            
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
            
            $remand_prisoner = '';
            
            if(Configure::read('REMAND')==$prisonerData['Prisoner']['prisoner_type_id'])
            {
                $remand_prisoner = 'yes';
            }               
           
            $this->loadModel('ApplicationToCourt');   
            if(isset($prisonerData['Prisoner']['id']) && (int)$prisonerData['Prisoner']['id'] != 0){
                $courtList      = array();
                $prisoner_id    = $prisonerData['Prisoner']['id'];
                /*
                 *Code for add the Application to court list records
                */                  
                if(isset($this->data['ApplicationToCourt']) && is_array($this->data['ApplicationToCourt']) && count($this->data['ApplicationToCourt']) >0){
                 
                                 
                    if(isset($this->request->data['ApplicationToCourt']['submission_date']) && $this->request->data['ApplicationToCourt']['submission_date'] != ''){
                        $this->request->data['ApplicationToCourt']['submission_date'] = date('Y-m-d', strtotime($this->request->data['ApplicationToCourt']['submission_date']));
                    }
                    
                    $this->request->data['ApplicationToCourt']['prisoner_id'] = $prisoner_id;   
                    $application_no = rand(100000,9999999);
                    $this->request->data['ApplicationToCourt']['application_no'] = $application_no; 
                    $this->request->data['ApplicationToCourt']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                    
                    /* check for feedback status granted against case file no */
                    $is_count = $this->ApplicationToCourt->find('count',array(
                                                                    'conditions'=>array(
                                                                    'ApplicationToCourt.case_file_no'=>$this->request->data['ApplicationToCourt']['case_file_no'],
                                                                    'ApplicationToCourt.court_feedback'=>'Granted',
                                                                    )
                                                                
                                                                ));
                    if($is_count > 0)
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Feedback status already Granted to this case file no. !');
                        $this->redirect('/courtattendances/index/'.$uuid.'#causeList');
                        exit;
                    }                       
                    /* end */
                    
                    //debug($this->request->data); exit;
                    
                    if($this->ApplicationToCourt->save($this->request->data)){
                        $this->Session->write('message_type','success');
                        if($this->request->data['ApplicationToCourt']['id']==""){
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
                 *Code for edit the Application to court list records
                */              
                if(isset($this->data['ApplicationToCourtEdit']['id']) && (int)$this->data['ApplicationToCourtEdit']['id'] != 0){
                    if($this->ApplicationToCourt->exists($this->data['ApplicationToCourtEdit']['id'])){
                        $this->data = $this->ApplicationToCourt->findById($this->data['ApplicationToCourtEdit']['id']);
                    }                   
                }
                /*
                 *Code for delete the Application to court list records
                 */ 
                if(isset($this->data['ApplicationToCourtDelete']['id']) && (int)$this->data['ApplicationToCourtDelete']['id'] != 0){
                    if($this->ApplicationToCourt->exists($this->data['ApplicationToCourtDelete']['id'])){
                        $this->ApplicationToCourt->id = $this->data['ApplicationToCourtDelete']['id'];
                        if($this->ApplicationToCourt->saveField('is_trash',1)){
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
                    $offence_count = '';
                    $case_no = '';
                    // code by partha
                    if(isset($this->request->data['Courtattendance']['offence_id']) && $this->request->data['Courtattendance']['offence_id']!= '')
                    {
                        foreach ($this->request->data['Courtattendance']['offence_id'] as $value) {
                        $offence_id .= $value.',';
                        }
                    }
                    
                    $offence_id=rtrim($offence_id,",");
                    $this->request->data['Courtattendance']['offence_id']=$offence_id;
                    
                    
                    if(isset($this->request->data['Courtattendance']['offence_count']) && $this->request->data['Courtattendance']['offence_count']!= '')
                    {
                        
                        foreach ($this->request->data['Courtattendance']['offence_count'] as $value1) {
                                                        
                                $offence_count .= $value1.',';
                            
                        }
                    }
                    
                    if(isset($this->request->data['Courtattendance']['case_no']) && $this->request->data['Courtattendance']['case_no']!= '')
                    {
                        foreach ($this->request->data['Courtattendance']['case_no'] as $value2) {
                        $case_no .= $value2.',';
                        
                        
                        }
                    }
                    
                    $offence_count=rtrim($offence_count,",");
                    $this->request->data['Courtattendance']['offence_count']=$offence_id;
                    
                    $case_no=rtrim($case_no,",");
                    $this->request->data['Courtattendance']['case_no']=$case_no;
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
                     if(isset($this->request->data['Courtattendance']['commence_date']) && $this->request->data['Courtattendance']['commence_date'] != ''){
                       
                        $this->request->data['Courtattendance']['court_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['commence_date']));
                        $this->request->data['Courtattendance']['commence_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['commence_date']));
                    }
                    if(isset($this->request->data['Courtattendance']['production_warrent_date']) && $this->request->data['Courtattendance']['production_warrent_date'] != ''){
                       
                        $this->request->data['Courtattendance']['production_warrent_date'] = date('Y-m-d', strtotime($this->request->data['Courtattendance']['production_warrent_date']));
                    }
                    if(isset($this->data['Courtattendance']['uuid']) && $this->data['Courtattendance']['uuid'] == ''){
                        $uuidArr = $this->Courtattendance->query("select uuid() as code");
                        $this->request->data['Courtattendance']['uuid'] = $uuidArr[0][0]['code'];
                    }
                    $this->request->data['Courtattendance']['prisoner_id']  = $prisoner_id;  
                    $this->request->data['Courtattendance']['prison_id']    = $this->Auth->user('prison_id');
                   // debug($this->data);   exit;
                    //debug($this->validationErrors); exit; 
                   // debug($this->Session->read()); exit;
                   // $check_count = '';
                    if(isset($this->request->data['Courtattendance']['id']) && $this->request->data['Courtattendance']['id'] == '')
                    {
                        $check_count = $this->Courtattendance->find('count',array(
                                        'conditions'=>array(
                                                'Courtattendance.prisoner_id'=>$prisoner_id,
                                                'Courtattendance.prison_id'=>$this->Auth->user('prison_id'),
                                                'Courtattendance.court_date'=>$this->request->data['Courtattendance']['court_date'],
                                                ),
                        ));
                    }
                    
                    if(@$check_count == 0)
                    {
                        $this->Courtattendance->recursive=-1;
                        if($this->request->data['Courtattendance']['from_cause_list']!=1)
                        {
                            //debug($this->data); exit;
                        }
                        
                        $this->request->data['Courtattendance']['appeal_id'] = $appeal_id;
                        if($this->Courtattendance->save($this->data)){
                            $this->Session->write('message_type','success');
                            if($this->request->data['Courtattendance']['id']==""){
                                $this->Session->write('message','Saved Successfully !');    
                            }
                            else{
                                $this->Session->write('message','Updated Successfully !');
                            }
                            $this->redirect('/courtattendances/index/'.$uuid.'#produceToCourt');
                        }else{
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed !');
                            $this->redirect('/courtattendances/index/'.$uuid.'#produceToCourt');
                        }
                    }else{
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Already cause list date prepared !');
                            $this->redirect('/courtattendances/index/'.$uuid.'#produceToCourt');
                        }
                }
                /*
                 *Code for edit the court attendance records
                */          
                
                if(isset($this->data['normalScheduleEdit']['id']) && (int)$this->data['normalScheduleEdit']['id'] != 0){

                    if($this->Courtattendance->exists($this->data['normalScheduleEdit']['id'])){
                        $this->data = $this->Courtattendance->findById($this->data['normalScheduleEdit']['id']);
                        $case_file = $this->request->data['Courtattendance']['case_no'];
                        $offence = $this->request->data['Courtattendance']['offence_id'];
                        $count = $this->request->data['Courtattendance']['offence_count'];
                        //debug($this->request->data['Courtattendance']);
                        $prisoner_case_file_id = rtrim($case_file,',');
                        $file_id = explode(',',$prisoner_case_file_id);
                        if(isset($file_id) && $file_id!='')
                        {
                            foreach($file_id as $value)
                            {
                                $case_file_id .= "'".$value."'".',';
                            }
                        }
                        
                        $case_file_id = rtrim($case_file_id,',');
                        $this->loadModel('PrisonerOffence');
                        $offence_for_edit = array();
                        $offenceList = $this->PrisonerOffence->find('all', array(
                            'recursive'     => -1,
                            'conditions'=> array("PrisonerOffence.prisoner_case_file_id IN (". $case_file_id ." ) "),
                            'fields'        => array(
                                'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                            ),
                        ));
                        foreach($offenceList as $offval)
                        {
                            $offence_for_edit[$offval['PrisonerOffence']['id']] = $this->getName($offval['PrisonerOffence']['offence'],'Offence','name')."(".$offval['PrisonerOffence']['offence_no'].")";
                        }
                        
                        
                    }
                }
                
                if(isset($this->data['causelistEdit']['id']) && (int)$this->data['causelistEdit']['id'] != 0){

                    if($this->Courtattendance->exists($this->data['causelistEdit']['id'])){
                        $this->data = $this->Courtattendance->findById($this->data['causelistEdit']['id']);
                        $case_file = $this->request->data['Courtattendance']['case_no'];
                        //debug($this->request->data['Courtattendance']);
                        $prisoner_case_file_id = rtrim($case_file,',');
                        $file_id = explode(',',$prisoner_case_file_id);
                        if(isset($file_id) && $file_id!='')
                        {
                            foreach($file_id as $value)
                            {
                                $case_file_id .= "'".$value."'".',';
                            }
                        }
                        
                        $case_file_id = rtrim($case_file_id,',');
                        $this->loadModel('PrisonerOffence');
                        $offence_for_edit = array();
                        $offenceList = $this->PrisonerOffence->find('all', array(
                            'recursive'     => -1,
                            'conditions'=> array("PrisonerOffence.prisoner_case_file_id IN (". $case_file_id ." ) "),
                            'fields'        => array(
                                'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                            ),
                        ));
                        foreach($offenceList as $offval)
                        {
                            $offence_for_edit[$offval['PrisonerOffence']['id']] = $this->getName($offval['PrisonerOffence']['offence'],'Offence','name')."(".$offval['PrisonerOffence']['offence_no'].")";
                        }
                    }
                }
                if(isset($this->data['productionwarrantEdit']['id']) && (int)$this->data['productionwarrantEdit']['id'] != 0){

                    if($this->Courtattendance->exists($this->data['productionwarrantEdit']['id'])){
                        $this->data = $this->Courtattendance->findById($this->data['productionwarrantEdit']['id']);
                        $case_file = $this->request->data['Courtattendance']['case_no'];
                        //debug($this->data);
                        $prisoner_case_file_id = rtrim($case_file,',');
                        $file_id = explode(',',$prisoner_case_file_id);
                        if(isset($file_id) && $file_id!='')
                        {
                            foreach($file_id as $value)
                            {
                                $case_file_id .= "'".$value."'".',';
                            }
                        }
                        
                        $case_file_id = rtrim($case_file_id,',');
                        $this->loadModel('PrisonerOffence');
                        $offence_for_edit = array();
                        $offenceList = $this->PrisonerOffence->find('all', array(
                            'recursive'     => -1,
                            'conditions'=> array("PrisonerOffence.prisoner_case_file_id IN (". $case_file_id ." ) "),
                            'fields'        => array(
                                'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                            ),
                        ));
                        foreach($offenceList as $offval)
                        {
                            $offence_for_edit[$offval['PrisonerOffence']['id']] = $this->getName($offval['PrisonerOffence']['offence'],'Offence','name')."(".$offval['PrisonerOffence']['offence_no'].")";
                        }
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
                    'recursive'     => -1,
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
                
                    $offenceList=$this->PrisonerOffence->find('all', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'PrisonerOffence.id',
                            'PrisonerOffence.offence',
                             'PrisonerOffence.offence_no',
                              'PrisonerOffence.offence_category_id',
                        ),
                        'conditions'    => array(
                          "PrisonerOffence.prisoner_id"=> $prisonerData['Prisoner']['id'],
                        ),
                        'order'         => array(
                            'PrisonerOffence.id',
                        ),
                    ));
                    $offenceListarr = array();
                    $offenceCategory = array();
                    if(isset($offenceList) && !empty($offenceList))
                    {
                        
                        foreach($offenceList as $offkey => $offval)
                        {
                            $offenceListarr[$offval['PrisonerOffence']['offence']] = $this->getName($offval['PrisonerOffence']['offence'],'Offence','name')."(".$offval['PrisonerOffence']['offence_no'].")";
                            @$offenceCategory[$offval['PrisonerOffence']['offence_category_id']] = $this->getName($offval['OffenceCategory']['offence_category_id'],'OffenceCategory','name');
                        }
                    }
                    
                    $authority_type = array(1,2,3);
                    $offenceListfromtab=$this->Courtattendance->find('all', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Courtattendance.id',
                            'Courtattendance.offence_id',
                            
                        ),
                        'conditions'    => array(
                          "Courtattendance.authority_type"=> $authority_type ,
                        ),
                        'order'         => array(
                            'Courtattendance.id',
                        ),
                    ));
                    
                    $offenceListarrFromtab = array();
                    if(isset($offenceListfromtab) && !empty($offenceListfromtab))
                    {
                        
                        foreach($offenceListfromtab as $offkey => $offval)
                        {
                             $offenceListarrFromtab[$offval['Courtattendance']['id']] = $this->getName($offval['Courtattendance']['offence_id'],'Offence','name');
                            
                        }
                    }
                    
                $offencecountList = $this->PrisonerOffence->find('list', array(
                //'recursive'     => -1,
                 /*"conditions"    => array(
                        "PrisonerOffence.prisoner_id"=> $prisonerData['Prisoner']['id'],
                        
                    ),*/
                'fields'        => array(
                    'PrisonerOffence.offence_no','PrisonerOffence.offence_no'
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
                /*
                 *Query for get the court List
                 */
                
                    
                      $courtList  = $this->Court->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Court.id',
                        'Court.name',
                    ),
                    'conditions'    => array(
                        'Court.is_enable'    => 1,
                        'Court.is_trash'     => 0
                    ),
                    'order'         => array(
                        'Court.name'
                    ),
                ));
                      // debug($courtList); exit;
                
                
                

                /* return from court*/
            
               
                if(isset($this->data['ReturnFromCourt']) && is_array($this->data['ReturnFromCourt']) && count($this->data['ReturnFromCourt']) >0){
                    if(isset($this->request->data['ReturnFromCourt']['session_date']) && $this->request->data['ReturnFromCourt']['session_date'] != ''){
                        $this->request->data['ReturnFromCourt']['session_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['session_date']));
                    }
                    if(isset($this->request->data['ReturnFromCourt']['decission_date']) && $this->request->data['ReturnFromCourt']['decission_date'] != ''){
                        $this->request->data['ReturnFromCourt']['decission_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['decission_date']));
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
                    if(isset($this->request->data['ReturnFromCourt']['sentence_date']) && $this->request->data['ReturnFromCourt']['sentence_date'] != ''){
                        $this->request->data['ReturnFromCourt']['sentence_date'] = date('Y-m-d', strtotime($this->request->data['ReturnFromCourt']['sentence_date']));
                    }
                    
                    $cashbail = '';
                    if(isset($this->request->data['ReturnFromCourt']['cash_bail']) && $this->request->data['ReturnFromCourt']['cash_bail']!= '')
                    {
                        foreach ($this->request->data['ReturnFromCourt']['cash_bail'] as $value3) {
                            
                        if( $value3 != '0' )
                        {
                            
                            $cashbail .= $value3.',';
                        }
                        
                        }
                         $cashbail =  rtrim($cashbail,',');
                        
                    }
                     $this->request->data['ReturnFromCourt']['cash_bail'] = $cashbail;
                    //$this->data['ReturnFromCourt']['uuid']=$uuid; 
                    $this->request->data['ReturnFromCourt']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                    //debug($this->request->data['ReturnFromCourt']); exit;
                                     
                    if($this->ReturnFromCourt->saveAll($this->request->data)){
                        $return_id = $this->ReturnFromCourt->id;
                        $this->Session->write('message_type','success');
                        if($this->request->data['ReturnFromCourt']['id']==""){
                            $this->Session->write('message','Saved Successfully !');
                            //$this->Session->write('appeal_status',$this->request->data['ReturnFromCourt']['appeal_status']);                  
                        }
                        else{
                            $this->Session->write('message','Updated Successfully !');
                        }
                        //$this->set('appeal_status',$this->request->data['ReturnFromCourt']['appeal_status']);
                        if($this->request->data['ReturnFromCourt']['appeal_status']=='Completed')
                        {
                            $this->redirect('/prisoners/edit/'.$uuid.'/'.$return_id);
                        }
                        else{
                            $this->redirect('/courtattendances/index/'.$uuid.'#returnFromCourt');
                        }                       
                        
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
            
            /* cause list date listing */
            
                    
            $cause_list_date = $this->Courtattendance->find('list',array(
                                                    'conditions'=>array(
                                                        'Courtattendance.authority_type'=>2,
                                                        'Courtattendance.uuid'=>$uuid,
                                                        'Courtattendance.prisoner_id' => $prisonerData['Prisoner']['id'],
                                                        'Courtattendance.prison_id'=> $this->Auth->user('prison_id'),
                                                        'Courtattendance.is_production_warrant'=>1,
                                                        ),
                                                        'fields'=>array('Courtattendance.id','Courtattendance.cause_date'),
                                                        'order' =>array('Courtattendance.cause_date'=>'ASC')                                                        
                                                        ));
            /* cause list date listing end */
            
            $caseTypeList =array(
                '1'=>'Capital Case',
                '2'=>'Petty Case'
            );
            
                 $caseStatusList =array(
                    'Mention'=>'Mention',
                    'Commitment'=>'Commitment',
                    'Hearing'=>'Hearing',
                    'Ruling'=>'Ruling',
                    'Defence'=>'Defence',
                    'Judgement'=>'Judgement',
                    'Sentencing'=>'Sentencing',
                    //'Case Ammended'=>'Case Ammended'
                );
            
            /* for  commitment_date */
            $this->loadModel('ReturnFromCourt');
            $commit_date = $this->ReturnFromCourt->find('all', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'ReturnFromCourt.session_date',
                            'ReturnFromCourt.commitment_date',
                            'ReturnFromCourt.conviction_date',
                            'ReturnFromCourt.aquited_date',
                            'ReturnFromCourt.remark',
                        ),
                        'conditions'    => array(
                            'ReturnFromCourt.uuid'      => $uuid,
                            'ReturnFromCourt.prison_id'     => $this->Auth->user('prison_id'),
                            
                        ),
                        'order'         => array(
                            'ReturnFromCourt.id'=>'DESC'
                        ),
                    ));
                        
            //debug($commit_date); exit;        
            /* -- end ---*/
            foreach($commit_date as $key => $vall)
            {
                if($vall['ReturnFromCourt']['commitment_date'] != '0000-00-00 00:00:00')
                {
                    $commitdate = date('d-m-Y',strtotime($vall['ReturnFromCourt']['commitment_date'])); 
                }
                if($vall['ReturnFromCourt']['conviction_date'] != '0000-00-00 00:00:00')
                {
                    $conviction_date = date('d-m-Y',strtotime($vall['ReturnFromCourt']['conviction_date'])); 
                }
                if($vall['ReturnFromCourt']['aquited_date'] != '0000-00-00 00:00:00')
                {
                    $aquited_date = date('d-m-Y',strtotime($vall['ReturnFromCourt']['aquited_date'])); 
                }
                if($vall['ReturnFromCourt']['session_date'] != '0000-00-00 00:00:00')
                {
                    $session_date = date('d-m-Y',strtotime($vall['ReturnFromCourt']['session_date'])); 
                }
                if($vall['ReturnFromCourt']['remark'] == '7')
                {
                    $remark = $vall['ReturnFromCourt']['remark']; 
                }
                
            }
            
            
            $remarks = array(
                '1'=>'Further remanded',
                '2'=>'Released on Bond',
                '3'=>'Granted Bail',
                '4'=>'Adjourned to next session',
                '5'=>'Case amended',
                '6'=>'Noelle Presque',
                '7'=>'Pending Minister Order',
                '8'=>'Commitment',
                '9'=>'Defence',
                '10'=>'Acquitted',
                '11'=>'Convicted',
                '12'=>'Judgement differed',
                '13'=>'Case Adjourned',
                
            );
            
                    
            
                $this->set(array(
                    'uuid'                  => $uuid,
                    //'courtList'               => $courtList,
                    'magestrilareaList'     => $magestrilareaList,
                    'offenceList'           => $offenceList,
                    'prisoner_id'           => $prisonerData['Prisoner']['id'],
                    'prison_id'             => $this->Auth->user('prison_id'),
                    'prisoner_no'           => $prisonerData['Prisoner']['prisoner_no'],
                    'magisterialList'       => $magisterialList,

                    'prisonerData'          =>$prisonerData,

                    'offencecountList'      => $offencecountList,
                    //'causeList'             => $causeList,
                    'caseTypeList'          => $caseTypeList,
                    'caseStatusList'        => $caseStatusList,
                   // 'case_file_no'      => $PrisonerCaseFile,
                    //'offenceIdList'      => $offenceIdList,
                   // 'remarksList'           => $remarksList,
                    'mentalcaseList'       => $mentalcaseList,
                    'offenceListarr'           => $offenceListarr,
                    'caseFileno'            => $caseFileno,
                    'cause_list_date'       => $cause_list_date,
                    'offenceListarrFromtab' => $offenceListarrFromtab,
                    'remarks'               => $remarks,
                    'offenceCategory'       => $offenceCategory,
                    'prisoner_type_id'      => $prisonerData['Prisoner']['prisoner_type_id'],
                    'session_date'          => @$session_date,
                    'conviction_date'       => @$conviction_date,
                    'aquited_date'          => @$aquited_date,
                    'commit_date'           => @$commitdate,
                    'remark'            => @$remark,
                    'offencess'=>@$offence_for_edit,

                    'remand_prisoner' => $remand_prisoner,
                    'fromcourtfile' => $fromcourtfile,
                    'casefilesToCourt'=>$casefilesToCourt,
                    'courtLevelList'  =>$courtLevelList,
                    'courtList'       =>$courtList,
                    'appeal_cause_list'=>@$appeal_cause_list
                    
                    
                ));
            }else{
                return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));  
            }
        }else{
            return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));  
        }
    }
    
    function showCourtName($id='')
    {
         $this->autoRender = false;
           if(isset($id) && (int)$id != 0){
                
                 $this->loadModel('Court');
                 $courtList = $this->Court->find('all', array(
                'recursive'     => -1,
                'conditions'=> array('Court.courtlevel_id' => $id),
                'fields'        => array(
                    'Court.id,Court.name',
                ),
            ));
            
            if(is_array($courtList) && count($courtList)>0){
                echo '<option value="">-- Select court --</option>';
                foreach($courtList as $courtKey=>$courtVal){
                    echo '<option value="'.$courtVal['Court']['id'].'">'.$courtVal['Court']['name'].'</option>';
                }
            }else{
                //echo "hwsafgcsh";
                echo '<option value="">-- Select court --</option>';
            }
            
           }
           else{
                echo '<option value="">-- Select court --</option>';
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

    public function showCaseTypeReturn($id='',$prisoner_id=''){
        $this->autoRender = false;
        $casetype = '';
        if(isset($id) && (int)$id != 0){
           $condition = array();
            $this->loadModel('PrisonerOffence');
            
           
            $offenceList = $this->PrisonerOffence->find('first', array(
                'recursive'     => -1,
                'conditions'=> array('PrisonerOffence.id' => $id,'PrisonerOffence.prisoner_id'=>$prisoner_id),
                'fields'        => array(
                    'PrisonerOffence.offence_category_id',
                ),
            ));
            $this->loadModel('OffenceCategory');
            $case_type = $this->OffenceCategory->find('list',array('conditions'=>array('OffenceCategory.is_enable'=>1)));
            
            if(is_array($offenceList) && count($offenceList)>0){
                //echo '<option value="">--Select--</option>';
                
                    $casetype .=  '<option value="'.$offenceList['PrisonerOffence']['offence_category_id'].'">'.$this->getName($offenceList['PrisonerOffence']['offence_category_id'],'OffenceCategory','name').'</option>';
               
            }else{
                //echo "hwsafgcsh";
                $casetype .=  '<option value="">-- Select --</option>';
            }
            
            $this->loadModel('PrisonerSentenceAppeal');
            $appealCount = $this->PrisonerSentenceAppeal->find('count',array(
                                    'conditions'=>array('PrisonerSentenceAppeal.offence_id'=>$id),
                                    
                                ));
            
        }else{
            $casetype .= '<option value="">-- Select --</option>';
        }
        echo $casetype.'##'.$appealCount;
        
        
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
        $this->loadModel('ApplicationToCourt');
        $this->layout           = 'ajax';
        $condition              = array(
            'ApplicationToCourt.is_trash'       => 0,
            'ApplicationToCourt.prison_id'     => $this->Session->read('Auth.User.prison_id'),
            
        );
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
             $condition += array('ApplicationToCourt.uuid'  => $uuid);
            
            
            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                    ),
            ));
            $prisoner_id    = $prisonerData['Prisoner']['id'];
            $condition += array(
                'ApplicationToCourt.prisoner_id'  => $prisoner_id,
            ); 
        }       
                       
        if(isset($this->data['ApplicationToCourt']) && is_array($this->data['ApplicationToCourt']) && count($this->data['ApplicationToCourt']) >0){
            
            $db = ConnectionManager::getDataSource('default');
            $db->begin();                   
            if($this->ApplicationToCourt->saveAll($this->data)){
                $refId = 0;
                $action = 'Add';
                if(isset($this->request->data['ApplicationToCourt']['id']) && (int)$this->request->data['ApplicationToCourt']['id'] != 0)
                {
                    $refId = $this->request->data['ApplicationToCourt']['id'];
                    $action = 'Edit';
                }
                //save audit log 
                if($this->auditLog('ApplicationToCourt', 'update status', $refId, $action, json_encode($this->data)))
                {
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Update Successfully !');
                    $this->redirect('/courtattendances/index/'.$uuid);
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

         if(isset($this->data['ApplicationToCourtEdit']['id']) && (int)$this->data['ApplicationToCourtEdit']['id'] != 0){
                    if($this->ApplicationToCourt->exists($this->data['ApplicationToCourtEdit']['id'])){
                        $this->data = $this->ApplicationToCourt->findById($this->data['ApplicationToCourtEdit']['id']);
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
            'conditions'    => $condition,
            'order'         => array(
                'ApplicationToCourt.id' => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('ApplicationToCourt');
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
                'recursive'     => -1,
                'fields'        => array(
                    'Court.id',
                    'Court.name',
                ),
                'conditions'    => array(
                    'Court.courtlevel_id'   => $this->data['magisterial_id']
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
                'recursive'     => -1,
                'conditions'    => array(
                    'Court.id'  => $this->data['court_id']
                ),
            ));
            $courtLevlList = $this->Courtlevel->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Courtlevel.id' => $courtList["Court"]["courtlevel_id"]
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
         $menuId = $this->getMenuId("/courtattendances/courtscheduleGatepassList");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
            /*$condition += array(
                'date(Courtattendance.attendance_date)'   => date("Y-m-d", strtotime($attendance_date)),
            );*/
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
        $menuId = $this->getMenuId("/courtattendances/courtsTrackList");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }

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
        /*$magestrilareaList = $this->Magisterial->find('list', array(
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
                ));*/
        $magestrilareaList=$this->Courtlevel->find('list',array(
                  'conditions'=>array(
                    'Courtlevel.is_enable'=>1,
                    'Courtlevel.is_trash'=>0,
                  ),
                  'order'=>array(
                    'Courtlevel.name'
                  )
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
                'Courtattendance.court_level'   => $magisterial_id,
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
                'date(Courtattendance.court_date)'   => date("Y-m-d", strtotime($attendance_date)),
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
            'recursive' => -1,
            'conditions'    => $condition,
            'joins'=>array(
                           array(
                                'table'         => 'courtattendances',
                                'alias'         => 'Courtattendance',
                                'type'          => 'left',
                                'conditions'    => array('Prisoner.id = Courtattendance.prisoner_id')
                        ),
                        array(
                            'table'         => 'return_from_courts',
                            'alias'         => 'returnFromCourt',
                            'type'          => 'left',
                            'conditions'    => array('Courtattendance.prisoner_id = returnFromCourt.prisoner_id')
                        ),  
            ),      
           'fields' => array(
                 'Prisoner.*',
                 'Courtattendance.*',                 
                 'ReturnFromCourt.case_status',
                 'ReturnFromCourt.remark',
            ),   
            'order'         => array(
                'Prisoner.prisoner_no'  => 'ASC',
            ),
        )+$limit;
        
        $datas = $this->paginate('Prisoner');
        
        //debug($datas);
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
        $menuId = $this->getMenuId("/courtattendances/courtsLoadReport");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
            
        $magestrilareaList=$this->Courtlevel->find('list',array(
                  'conditions'=>array(
                    'Courtlevel.is_enable'=>1,
                    'Courtlevel.is_trash'=>0,
                  ),
                  'order'=>array(
                    'Courtlevel.name'
                  )
            )); 
        /*$magestrilareaList = $this->Magisterial->find('list', array(
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
                ));*/
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
        
        $this->loadModel('Courtattendance');
            
        $condition = array();
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
            
            $condition += array('ReturnFromCourt.remark NOT IN (15,16,10,11,14,6)');
          
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

        $this->Courtattendance->recursive = -1; 
         $this->paginate = array( 
                'joins' => array( 
                array( 
                 'table' => 'return_from_courts', 
                'alias' => 'ReturnFromCourt', 
               'type' => 'left', 
                'conditions'=> array('ReturnFromCourt.prisoner_id = Courtattendance.prisoner_id')
               )
            ),   
            'fields'=>array('Courtattendance.id','Courtattendance.case_no','Courtattendance.court_level','Courtattendance.court_id'),
            'conditions'=> $condition, 
            )+$limit;
                 
        
       
        $datas = $this->paginate('Courtattendance');
        
        $finalData = array();
        
        if(isset($datas) && count($datas)>0){
            foreach ($datas as $key => $value) {
                $finalData[$value['Courtattendance']['court_level']][$value['Courtattendance']['court_id']][] = $value['Courtattendance']['case_no'];
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
         $menuId = $this->getMenuId("/courtattendances/courtsTrackingReport");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
         $menuId = $this->getMenuId("/courtattendances/stayListReport");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
                        array('PrisonerOffence.offence_category_id' => 1,'date(Prisoner.doa) >' => date('Y-m-d',strtotime("-180 days"))), 
                        array('PrisonerOffence.offence_category_id' => 2,'date(Prisoner.doa) >' => date('Y-m-d',strtotime("-60 days"))), 
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
       
        /*   $this->paginate = array(
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.modified'  => 'DESC',
            ),
        )+$limit;*/
        
        $this->paginate = array( 
            'recursive'     => -1,
            'joins' => array( 
            array( 
                    'table' => 'prisoner_case_files', 
                    'alias' => 'PrisonerCaseFile', 
                    'type' => 'left', 
                    'conditions'=> array('PrisonerCaseFile.prisoner_id = Prisoner.id')
           ),
           array(
                   'table' => 'prisoner_offences',
                   'alias' => 'PrisonerOffence',
                   'type' => 'LEFT',
                   'conditions' => array('PrisonerCaseFile.id = PrisonerOffence.prisoner_case_file_id')
            ),
           
        ),   
        'conditions'=> $condition, 
        'fields'=>array('Prisoner.*','PrisonerOffence.offence_category_id'),
        )+$limit;
                 
        
        
        $datas = $this->paginate('Prisoner');
        //debug($datas); exit;
                
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
                        array('PrisonerOffence.offence_category_id' => 1,'date(Prisoner.doa) >' => date('Y-m-d',strtotime("-180 days"))), 
                        array('PrisonerOffence.offence_category_id' => 2,'date(Prisoner.doa) >' => date('Y-m-d',strtotime("-60 days"))), 
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

        
        /*$this->paginate = array(
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.modified'  => 'DESC',
            ),
        )+$limit;*/
        
        $this->paginate = array( 
            'recursive'     => -1,
            'joins' => array( 
            array( 
                    'table' => 'prisoner_case_files', 
                    'alias' => 'PrisonerCaseFile', 
                    'type' => 'left', 
                    'conditions'=> array('PrisonerCaseFile.prisoner_id = Prisoner.id')
           ),
           array(
                   'table' => 'prisoner_offences',
                   'alias' => 'PrisonerOffence',
                   'type' => 'LEFT',
                   'conditions' => array('PrisonerCaseFile.id = PrisonerOffence.prisoner_case_file_id')
            ),
           
        ),   
        'conditions'=> $condition, 
        'fields'=>array('Prisoner.*','PrisonerOffence.offence_category_id'),
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
        $menuId = $this->getMenuId("/courtattendances/courtsTrackingReportNew");
                $moduleId = $this->getModuleId("court_attendance");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
    
    public function saveFeedbackdetail()
    {
        $this->autoRender = false;
        if(isset($this->params['named']) && !empty($this->params['named']))
        {
            $fields  = array(
                'ApplicationToCourt.court_feedback' => "'".$this->params['named']['feedback_status']."'",
                'ApplicationToCourt.feedback_date'  => "'".date('Y-m-d',strtotime($this->params['named']['feedback_date']))."'",
            );
            
            $conds   = array(
                'ApplicationToCourt.id' => $this->params['named']['id'],
            );
            
            $this->loadModel('ApplicationToCourt'); 
            if($this->ApplicationToCourt->updateAll($fields, $conds))
            {
                echo '1';
            }
            else
            {
                echo '0';
            }
        }
    }
    
    public function getCountOffence(){
        
        

        $this->loadModel('PrisonerSentenceAppeal');
        $this->loadModel('PrisonerSentence');
        $this->autoRender = false;
        $prisoner_case_file_id = $this->params['named']['prisoner_case_file_id'];
        $offence_name = '';
        $offence_count = '';
        $case_file_id = '';
        $offences = array();
        $case_file_id_arr1 = '';
        
        $case_file_array = array();
        $case_file_array1 = array();
        $case_file_array2 = array();
        $new_array = array();
        $case_file_id_arr = array();
        
        if(isset($prisoner_case_file_id) && (int)$prisoner_case_file_id != ''){
           $condition = array();
            $this->loadModel('PrisonerOffence');
            $prisoner_case_file_id = rtrim($prisoner_case_file_id,',');
            $file_id = explode(',',$prisoner_case_file_id);
            if(isset($file_id) && $file_id!='')
            {
                foreach($file_id as $value)
                {
                    $case_file_id .= "'".$value."'".',';
                }
            }
            
            $case_file_id = rtrim($case_file_id,',');
            
            
            $sentenceAppeal = $this->PrisonerOffence->find('all',array(
                            'recursive' => -1,
                                        'conditions'=>array(
                                        'PrisonerOffence.prisoner_case_file_id IN ('. $case_file_id.')',
                                        
                                ),
                                'fields'=>array( 'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',)
                    ));
            
            if(isset($sentenceAppeal) && count($sentenceAppeal) > 0)
            {
                foreach($sentenceAppeal as $keys => $vals)
                {
                    array_push($offences,$vals['PrisonerOffence']['id']);
                }
                
                //debug($offences); exit;   
                
                $senerence_count = $this->PrisonerSentence->find('all',array(
                                    'recursive' => -1,
                                    'conditions' => array('PrisonerSentence.offence_id'=>$offences),
                                    'fields' => array('PrisonerSentence.offence_id','PrisonerSentence.is_convicted','PrisonerSentence.wish_to_appeal','PrisonerSentence.waiting_for_confirmation'),
                ));
                
                
                if(isset($senerence_count) && count($senerence_count) > 0)
                        {
                            foreach($senerence_count as $case_file_val)
                            {
                                if($case_file_val['PrisonerSentence']['is_convicted']==0)
                                {
                                    array_push($new_array,  $case_file_val['PrisonerSentence']['offence_id']);
                                    
                                }
                                if($case_file_val['PrisonerSentence']['is_convicted']==1)
                                {
                                    if($case_file_val['PrisonerSentence']['waiting_for_confirmation']==1)
                                    {
                                        array_push($new_array,  $case_file_val['PrisonerSentence']['offence_id']);
                                    }
                                    if($case_file_val['PrisonerSentence']['wish_to_appeal']==1)
                                    {
                                        array_push($case_file_array1,  $case_file_val['PrisonerSentence']['offence_id']);
                                    }
                                    
                                }
                            }
                            
                            if($case_file_array1 != '')
                            {
                                
                                $sentenceAppeal = $this->PrisonerSentenceAppeal->find('all',array(
                                            'recursive' => -1,
                                                        'conditions'=>array(
                                                        'PrisonerSentenceAppeal.appeal_status'=>'Cause List',
                                                        'PrisonerSentenceAppeal.offence_id'=> $case_file_array1,
                                                        //'PrisonerSentenceAppeal.prisoner_id' => $prisoner_id,
                                                ),
                                                'fields'=>array('PrisonerSentenceAppeal.offence_id')
                                    ));
                                    
                                    foreach($sentenceAppeal as $sentval)
                                    {
                                        array_push($new_array,$sentval['PrisonerSentenceAppeal']['offence_id']);
                                    }
                            }
                            
                            $new_array = array_values(array_merge($offences,$case_file_array,$case_file_array2,$case_file_id_arr));     
                                    
                        }
                        else{
                            $new_array = $offences;
                        }
                        
                    
                if(count($new_array) > 0)
                {
                    $offenceList = $this->PrisonerOffence->find('all', array(
                    'recursive'     => -1,
                    'conditions'=> array("PrisonerOffence.id" => $new_array),
                    'fields'        => array(
                        'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                    ),
                    ));
                }
                
                
            
            
                if(isset($offenceList) && !empty($offenceList))
                {
                    
                    foreach($offenceList as $offkey => $offval)
                    {
                        //$offence_name .= '<option value=""></value>';
                         $offence_name .= '<option value="'.$offval['PrisonerOffence']['id'].'">'. $this->getName($offval['PrisonerOffence']['offence'],'Offence','name')."(".$offval['PrisonerOffence']['offence_no'].")".'</option>';
                        
                    }
                }
                else
                {
                    $offence_name = '';
                    
                }
            
                        
            $appealCount = $this->PrisonerSentenceAppeal->find('count',array(
                                    'conditions'=>array('PrisonerSentenceAppeal.case_file_id'=>$prisoner_case_file_id),
                                    
                                    
                                ));
            }
            else
            {
                
            }

        }
        else
        {
           $offence_name = '';
        }
        
        echo rtrim($offence_name,',').'##'.'0'.'##'.@$appealCount;
    }
    
    public function getFromCourtOffence(){
        
        $this->autoRender = false;
        $prisoner_case_file_id = $this->params['named']['prisoner_case_file_id'];
        $prisoner_id = $this->params['named']['prisoner_id'];
        $uuid = $this->params['named']['uuid'];
        
        $offence_name = '';
        $offence_count = '';
        $case_file_id = '';
        if(isset($prisoner_case_file_id) && (int)$prisoner_case_file_id != ''){
           $condition = array();
            $this->loadModel('PrisonerOffence');
            $prisoner_case_file_id = rtrim($prisoner_case_file_id,',');
            $file_id = explode(',',$prisoner_case_file_id);
            if(isset($file_id) && count($file_id) > 0)
            {
                foreach($file_id as $value)
                {
                    $case_file_id .= "'".$value."'".',';
                }
            }
            
            $case_file_id = rtrim($case_file_id,',');
            /*$offenceList = $this->PrisonerOffence->find('all', array(
                'recursive'     => -1,
                'conditions'=> array("PrisonerOffence.prisoner_case_file_id IN (". $case_file_id ." ) "),
                'fields'        => array(
                    'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                ),
            ));
            
            $newarr = array();
            foreach($offenceList as $nkey => $nval)
            {
                $newarr[$nval['PrisonerOffence']['offence']][$nval['PrisonerOffence']['offence_no']] = $nval['PrisonerOffence']['id'];
            }
            */
            
            
            $offencecountList = $this->Courtattendance->find('all', array(
                'recursive'     => -1,
                'conditions'=> array("Courtattendance.case_no IN (". $case_file_id ." ) AND Courtattendance.prisoner_id = ".$prisoner_id." AND Courtattendance.uuid = '".$uuid."' AND Courtattendance.status = 'Approved'"),
                'fields'        => array('Courtattendance.id','Courtattendance.offence_id'),
            ));
            
            $offence_count = '';
            $to_court_id = '';
            
            if(count($offencecountList) > 0)
            {
                    $offenceList = $this->PrisonerOffence->find('all', array(
                    'recursive'     => -1,
                    'conditions'=> array("PrisonerOffence.id IN (". $offencecountList[0]['Courtattendance']['offence_id'] ." ) "),
                    'fields'        => array(
                        'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                    ),
                ));
                
                $offence_count .= '<option value=""></option>';
                if(count($offenceList) > 0)
                {
                        foreach($offenceList as $offkey => $offval)
                        {   
                            $offence_count .= '<option value="'.$offval['PrisonerOffence']['id'].'">'.$this->getName($offval['PrisonerOffence']['offence'],'Offence','name').'-'.$offval['PrisonerOffence']['offence_no'].'</option>';                                  
                        }
                }
                else
                {
                    $offence_count = '';
                    
                }
                
            }
            
        
            $this->loadModel('PrisonerSentenceAppeal');
            $appealCount = $this->PrisonerSentenceAppeal->find('count',array(
                                    'conditions'=>array('PrisonerSentenceAppeal.case_file_id'=>$prisoner_case_file_id),
                                    
                                ));
            
        }
        else
        {
           
        }
        
        echo rtrim($offence_count,',').'##'.@$appealCount.'##'.$to_court_id;
    }
    
    public function getNormalSchedule(){
        $this->layout           = 'ajax';
        $uuid                   = '';
        $auth_type              = '';
        $this->loadModel('Courtattendance');
        
        $condition              = array(
                'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id')
        );
        
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $condition += array('Courtattendance.uuid'=>$uuid);
        }
        
        if(isset($this->params['named']['authority_type']) && $this->params['named']['authority_type'] != '' && $this->params['named']['authority_type'] != 'undefined'){
            $authority_type = $this->params['named']['authority_type'];         
            $condition += array('Courtattendance.authority_type'=>1);
        }
        else{
            $authority_type = $this->params['named']['authority_type'];         
            $condition += array('Courtattendance.authority_type'=>array(1,2,3));
        }   
       
       // debug($condition);     
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
                'Courtattendance.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        //debug($datas); exit;
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
        ));                                         
    }
    
    public function getTocourtListData(){
        $this->layout           = 'ajax';
        $uuid                   = '';
        $auth_type              = '';
        $this->loadModel('Courtattendance');
        
        $condition              = array(
                'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id')
        );
        
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $condition += array('Courtattendance.uuid'=>$uuid);
        }
        
       
       // debug($condition);     
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
                'Courtattendance.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        //debug($datas); exit;
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
        ));                                         
    }
    
     public function indexCauseAjax(){
        $this->layout           = 'ajax';
       // $production_warrent_no  = '';
        //$attendance_date        = '';
        //$attendance_time        = '';
       // $magisterial_id         = '';
       // $court_id               = '';
       // $case_no                = '';
        $uuid                   = '';
        $auth_type              = '';
        
        $this->loadModel('Courtattendance');
        
        $condition              = array(
                'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id')
        );
        
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $condition += array('Courtattendance.uuid'=>$uuid);
        }
        
        if(isset($this->params['named']['authority_type']) && $this->params['named']['authority_type'] != ''){
            $authority_type = $this->params['named']['authority_type'];
            $condition += array('Courtattendance.authority_type'=>$authority_type);
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
                'Courtattendance.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        //debug($datas); exit;
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
        ));                                         
    }
    
     public function getProductionWarrant(){
        $this->layout           = 'ajax';
      
        $uuid                   = '';
        $auth_type              = '';
        
        $this->loadModel('Courtattendance');
        
        $condition              = array(
                'Courtattendance.prison_id'     => $this->Session->read('Auth.User.prison_id')
        );
        
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $condition += array('Courtattendance.uuid'=>$uuid);
        }
        
        if(isset($this->params['named']['authority_type']) && $this->params['named']['authority_type'] != ''){
            $authority_type = $this->params['named']['authority_type'];
            $condition += array('Courtattendance.authority_type'=>$authority_type);
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
                'Courtattendance.id'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Courtattendance');
        //debug($datas); exit;
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
        ));                                         
    }
    public function getfromCauselistdata()
    {
         $this->autoRender           = false;
      
        
        $this->loadModel('Courtattendance');
        $condition = array();
       
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $condition += array('Courtattendance.uuid'=>$uuid);
        }
        
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Courtattendance.prison_id'=>$prison_id);
        }
        
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('Courtattendance.prison_id'=>$prisoner_id);
        }
             
        $condition += array('Courtattendance.is_production_warrant'=>1);
        $this->Courtattendance->recursive = -1;
        $datas = $this->Courtattendance->find('first',array(
            'conditions'    => $condition,
            
        ));
       if(isset($datas) && count($datas) > 0)
       {
           $file_no = $datas['Courtattendance']['case_no'];
           $offence = $datas['Courtattendance']['offence_id'];
           $court_id = $datas['Courtattendance']['court_id'];
           $court_level = $datas['Courtattendance']['court_level'];
           $court_file_no = $datas['Courtattendance']['court_file_no'];
           $high_court_file_no = $datas['Courtattendance']['high_court_file_no'];
          $jsonarr = array(
            'file_no' => $file_no,
            'offence' => $offence,
            'court_id' => $court_id,
            'court_level' => $court_level,
            'court_file_no' => $court_file_no,
            'high_court_file_no' => $high_court_file_no
          );
          
          echo json_encode($jsonarr);
       }
            
       else
       {
            echo '';
       }
          
                                              
    }
    
    function getMultivalue($value,$model,$column = 'title')
    {
        $this->loadModel($model);
        $values = explode(',',$value);
        $data = $this->$model->find('all',array('conditions'=>array($model.'.id'=>$values)));
        
        $name = '';
        if(isset($data) && !empty($data)){
            foreach($data as $val)
            {
                $name .= $val[$model][$column].',';
            }
            
            return rtrim($name,',');
        }else{
            return "";
        }
        
    }
    function getFileNo($value)
    {
        $this->loadModel('PrisonerCaseFile');
        
        
           $offenceList = $this->PrisonerCaseFile->find('all', array(
                'recursive'     => -1,
                'conditions'=> array("PrisonerCaseFile.id IN (". $value ." ) "),
                'fields'        => array(
                    'PrisonerCaseFile.file_no',
                ),
            ));
        
        
        
        $name = '';
        if(isset($offenceList) && !empty($offenceList)){
            foreach($offenceList as $val)
            {
                $name .= $val['PrisonerCaseFile']['file_no'].',';
            }
            
            return rtrim($name,',');
        }else{
            return "";
        }
        
    }
     function getOffenceNameListing($id){
        $this->loadModel('PrisonerOffence');
       
            $offenceList = $this->PrisonerOffence->find('all', array(
                'recursive'     => -1,
                'joins'=>array(
                                array(
                                    'table' => 'prisoner_case_files',
                                    'alias' => 'PrisonerCaseFile',
                                    'type' => 'left',
                                    'foreignKey' => false,
                                    'conditions'=> array(
                                    'PrisonerCaseFile.case_file_no = PrisonerOffence.prisoner_case_file_id',
                                    ),
                            ),
                ),
                'conditions'=> array("PrisonerOffence.prisoner_case_file_id IN (". $id ." ) "),
                'fields'        => array(
                    'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                ),
            ));
            
            
            $offence_name = '';
            if(isset($offenceList) && !empty($offenceList))
            {
                
                foreach($offenceList as $offkey => $offval)
                {
                    $offence_name .= $this->getName($offval['PrisonerOffence']['offence'],'Offence','name').',';
                    
                }
                 return rtrim($offence_name,',');
            }else{
            return "";
        }
    }
    
     function getOffenceNameViewListing($id){
        $this->loadModel('PrisonerOffence');
           
       
           /* $offenceList = $this->PrisonerOffence->find('all', array(
                'recursive'     => -1,
                'conditions'=> array("PrisonerOffence.prisoner_case_file_id IN (". $id ." ) "),
                'fields'        => array(
                    'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',
                ),
            ));*/
            
        
            
        $offenceList = $this->PrisonerOffence->find('all', array(
            'recursive'     => -1,
            'joins' => array(
                                array(
                                    'table' => 'offences',
                                    'alias' => 'Offence',
                                    'type' => 'left',
                                    'foreignKey' => false,
                                    'conditions'=> array(
                                            'Offence.id = PrisonerOffence.offence',
                                    ),
                                
                        ),
                        ),
        'fields'=>array( 'PrisonerOffence.id','PrisonerOffence.offence','PrisonerOffence.offence_no',),
            'conditions'=>array("PrisonerOffence.id IN (". $id ." ) "),
        )); 
            
            $offence_name = '';
            if(isset($offenceList) && !empty($offenceList))
            {
                
                foreach($offenceList as $offkey => $offval)
                {
                    $offence_name .= $this->getName($offval['PrisonerOffence']['offence'],'Offence','name').',';
                    
                }
                 return rtrim($offence_name,',');
            }
            else
            {
                return "";
            }
    }
    
    public function approveAll() {
        $this->autoRender = false;
        $this->loadModel('ReturnFromCourt');
        if (isset($this->params['named']['id']) && $this->params['named']['id'] != '') {
            $id = explode(',', $this->params['named']['id']);
            $approval_status = '';
           
            $update = $this->ReturnFromCourt->updateAll(
                    array('is_final_save' => '1'), array('ReturnFromCourt.id' => $id)
            );

            if ($update) {
                echo 'succ';
            } else {
                echo 'error';
            }
        }
    }
    
    public function getTocourtFileNo($prisoner_id='',$sentene_type='')
    {
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('PrisonerSentenceAppeal');
        
        $case_files = $this->PrisonerCaseFile->find('all', array(
            'recursive'     => -1,
            'joins' => array(
                                array(
                                    'table' => 'prisoner_sentences',
                                    'alias' => 'PrisonerSentence',
                                    'type' => 'left',
                                    'foreignKey' => false,
                                    'conditions'=> array(
                                            'PrisonerSentence.case_id = PrisonerCaseFile.case_file_no',
                                    ),
                                    
                                ),
                                /*array(
                                    'table' => 'courtattendances',
                                    'alias' => 'Courtattendance',
                                    'type' => 'left',
                                    'foreignKey' => false,
                                    'conditions'=> array(
                                            'Courtattendance.case_no = PrisonerSentence.case_id',
                                    ),
                                )*/     
                        ),
        'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no','PrisonerSentence.sentence_type','PrisonerSentence.is_convicted','PrisonerSentence.wish_to_appeal','PrisonerSentence.waiting_for_confirmation'),
            'conditions'=>array('PrisonerCaseFile.prisoner_id'=>$prisoner_id),
        )); 
        
        //$this->PrisonerCaseFile->recursive = -1;
        /*$case_files = $this->PrisonerCaseFile->find('all', array(
            'joins' => array(
                                array(
                                    'table' => 'prisoner_sentences',
                                    'alias' => 'PrisonerSentence',
                                    'type' => 'left',
                                    'foreignKey' => false,
                                    'conditions'=> array(
                                            'PrisonerSentence.case_id = PrisonerCaseFile.id',
                                    ),
                                    
                                )           
                        ),
        'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no','PrisonerSentence.sentence_type'),
            'conditions'=>array('PrisonerCaseFile.prisoner_id'=>$prisoner_id),
        )); */
        //debug($case_files);
        $case_file_array = array();
        $case_file_array1 = array();
        $case_file_array2 = array();
        $case_file_id_arr = array();
        $new_array = array();
        $case_file_id = '';
        $case_file_id1 = '';
        if(isset($case_files) && $case_files != '')
        {
            foreach($case_files as $case_file_val)
            {
                if($case_file_val['PrisonerSentence']['is_convicted']==0)
                {
                    array_push($case_file_array,  $case_file_val['PrisonerCaseFile']['id']);
                    
                }
                if($case_file_val['PrisonerSentence']['is_convicted']==1)
                {
                    if($case_file_val['PrisonerSentence']['waiting_for_confirmation']==1)
                    {
                        array_push($case_file_array2,  $case_file_val['PrisonerCaseFile']['id']);
                    }
                    if($case_file_val['PrisonerSentence']['wish_to_appeal']==1)
                    {
                        array_push($case_file_array1,  $case_file_val['PrisonerCaseFile']['id']);
                    }
                    
                }
            }
            
            if($case_file_array1 != '')
            {
                
                $sentenceAppeal = $this->PrisonerSentenceAppeal->find('all',array(
                            'recursive' => -1,
                                        'conditions'=>array(
                                        'PrisonerSentenceAppeal.appeal_status'=>'Cause List',
                                        'PrisonerSentenceAppeal.case_file_id'=> $case_file_array1,
                                        //'PrisonerSentenceAppeal.prisoner_id' => $prisoner_id,
                                ),
                                'fields'=>array('PrisonerSentenceAppeal.case_file_id')
                    ));
                    
                    foreach($sentenceAppeal as $sentval)
                    {
                        array_push($case_file_id_arr,$sentval['PrisonerSentenceAppeal']['case_file_id']);
                    }
            }
            
            $new_array = array_values(array_merge($case_file_array,$case_file_array2,$case_file_id_arr));       
                    
        }
        
        //debug($case_file_array1); exit;
        if(!empty($new_array))
        {
            
            
         $caseFileno=$this->PrisonerCaseFile->find('list',array(
                  "recursive" => -1,
                  'conditions'=>array(
                   'PrisonerCaseFile.is_trash'=>0,
                   'PrisonerCaseFile.id'=>$new_array,
                  ),
                  'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no'),
                  'order'=>array(
                    'PrisonerCaseFile.case_file_no'
                  )
            ));
            
            return $caseFileno;
        }
        /*else{
            
            $caseFileno=$this->PrisonerCaseFile->find('list',array(
                  "recursive" => -1,
                  'conditions'=>array(
                   'PrisonerCaseFile.is_trash'=>0,
                   'PrisonerCaseFile.prisoner_id'=>$prisoner_id
                  ),
                  'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no'),
                  'order'=>array(
                    'PrisonerCaseFile.case_file_no'
                  )
            ));
            
            return $caseFileno;
        }*/     
        
    }
    
    public function remandBeyondStatutoryReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $usertype = array(1,2);
        $district_condition = array();
        $station_condition = array();
        $region_condition = array();
        if(!in_array($this->Auth->user('usertype_id'),$usertype))
        {
            $district_condition +=  array('District.id' => $this->Auth->user('district_id'));
            $station_condition += array('Prison.district_id' => $this->Auth->user('district_id'));
            $region_condition += array('State.id' => $this->Auth->user('state_id'));
        }
        
       
        $this->loadModel('District');
        $this->loadModel('State');
        $this->loadModel('Country');
        $this->loadModel('Prison');
        
        $prisonListData = $this->Prison->find('list', array(
                'joins'=>array(
                                array(
                                    'table' => 'users',
                                    'alias'=> 'User',
                                    'type'=> 'inner',
                                    'conditions'=>array('User.district_id=Prison.district_id'),
                                ),
                ),
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => $station_condition,
            ));
            
         $districtListData = $this->District->find('list', array(
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => $district_condition,
               
            )); 
            
            $regionListData = $this->State->find('list', array(
                'fields'        => array(
                    'State.id',
                    'State.name',
                ),
                'conditions'    => $region_condition,
               
            )); 
            
        $countryList = $this->Country->find('list', array(
                    'fields'        => array(
                        'Country.id',
                        'Country.name',
                    ),
                    'conditions'    => array(
                        'Country.is_enable'     => 1,
                        'Country.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Country.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonListData'  => $prisonListData,
                    'districtListData' => $districtListData,
                    'regionListData' => $regionListData,
                    'countryList' => $countryList,
                ));
    }
    
    public function remandBeyondStatutoryReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $station_id = '';
        $district_id = '';
        $region_id = '';
        $country_id = '';
        $from_date = '';
        $to_date = '';
        $condition = array();
        
        if(isset($this->params['named']['station_id']) && $this->params['named']['station_id'] != '')
        {
            $condition += array("Prisoner.prison_id IN (".$this->params['named']['station_id'].")");
            $station_id = $this->params['named']['station_id'];
        }
        
        if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '')
        {
            $condition += array("Prisoner.district_id IN (".$this->params['named']['district_id'].")");
            $district_id = $this->params['named']['district_id'];
        }
        
        if(isset($this->params['named']['region_id']) && $this->params['named']['region_id'] != '')
        {
            $condition += array("Prisoner.state_id IN (".$this->params['named']['region_id'].")");
            $region_id = $this->params['named']['region_id'];
        }
        if(isset($this->params['named']['country_id']) && $this->params['named']['country_id'] != '')
        {
            $condition += array('Prisoner.country_id'=>$this->params['named']['country_id']);
            $country_id = $this->params['named']['country_id'];
        }
        
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '')
        {
            $condition += array('Prisoner.doa >'=> date('Y-m-d',strtotime($this->params['named']['from_date'])));
            $from_date = date('Y-m-d',strtotime($this->params['named']['from_date']));
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '')
        {
            $condition += array('Prisoner.doa <'=> date('Y-m-d',strtotime($this->params['named']['to_date'])));
            $to_date = date('Y-m-d',strtotime($this->params['named']['to_date']));
        }
        
        $condition     += array('AND' => array( 
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'   => 1,
                //'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                'Prisoner.transfer_status !='        => 'Approved',
                'OR' => array( 
                        array('PrisonerOffence.offence_category_id' => 1,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-180 days"))), 
                        array('PrisonerOffence.offence_category_id' => 2,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-60 days"))), 
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
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                   'table' => 'prisoner_offences',
                   'alias' => 'PrisonerOffence',
                   'type' => 'LEFT',
                   'conditions' => array('PrisonerCaseFile.id = PrisonerOffence.prisoner_case_file_id')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                array(
                    'table' => 'prisons',
                    'alias' => 'Prison',
                    'type' => 'inner',
                    array('Prisoner.prison_id = Prison.id')
                ),  
               
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
               /*  'Prisoner.age_on_admission',*/
                 'Prisoner.doa',              
                 'Prisoner.first_name',
                 'Prisoner.middle_name',
                 'Prisoner.last_name',
                 'Court.name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'Prison.geographical_id',
                 'PrisonerOffence.offence',
                 'PrisonerOffence.offence_category_id'
               )
        )+$limit;
        
        
        $datas = $this->paginate('Prisoner');
        
        //debug($datas); exit;
        
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'station_id'    => $station_id,
            'district_id'          => $district_id,
            'region_id'   => $region_id,
            'country_id'            => $country_id,
            'from_date'            => $from_date,
            'to_date'            => $to_date
        ));
    }
    
    public function reachMandatoryPeriodReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $usertype = array(1,2);
        $district_condition = array();
        $station_condition = array();
        $region_condition = array();
        if(!in_array($this->Auth->user('usertype_id'),$usertype))
        {
            $district_condition +=  array('District.id' => $this->Auth->user('district_id'));
            $station_condition += array('Prison.district_id' => $this->Auth->user('district_id'));
            $region_condition += array('State.id' => $this->Auth->user('state_id'));
        }
        
        $this->loadModel('District');
        $this->loadModel('State');
        $this->loadModel('Country');
        $this->loadModel('Prison');
        
        $prisonListData = $this->Prison->find('list', array(
                'joins'=>array(
                                array(
                                    'table' => 'users',
                                    'alias'=> 'User',
                                    'type'=> 'inner',
                                    'conditions'=>array('User.district_id=Prison.district_id'),
                                ),
                ),
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => $station_condition,
            ));
            
         $districtListData = $this->District->find('list', array(
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => $district_condition,
               
            )); 
            
            $regionListData = $this->State->find('list', array(
                'fields'        => array(
                    'State.id',
                    'State.name',
                ),
                'conditions'    => $region_condition,
               
            )); 
            
        $countryList = $this->Country->find('list', array(
                    'fields'        => array(
                        'Country.id',
                        'Country.name',
                    ),
                    'conditions'    => array(
                        'Country.is_enable'     => 1,
                        'Country.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Country.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonListData'  => $prisonListData,
                    'districtListData' => $districtListData,
                    'regionListData' => $regionListData,
                    'countryList' => $countryList,
                ));
    }
    
    public function reachMandatoryPeriodReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition = array();
        
        if(isset($this->params['named']['station_id']) && $this->params['named']['station_id'] != '')
        {
            $condition += array("Prisoner.prison_id IN (".$this->params['named']['station_id'].")");
            $station_id = $this->params['named']['station_id'];
        }
        
        if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '')
        {
            $condition += array("Prisoner.district_id IN (".$this->params['named']['district_id'].")");
            $district_id = $this->params['named']['district_id'];
        }
        
        if(isset($this->params['named']['region_id']) && $this->params['named']['region_id'] != '')
        {
            $condition += array("Prisoner.state_id IN (".$this->params['named']['region_id'].")");
            $region_id = $this->params['named']['region_id'];
        }
        if(isset($this->params['named']['country_id']) && $this->params['named']['country_id'] != '')
        {
            $condition += array('Prisoner.country_id'=>$this->params['named']['country_id']);
            $country_id = $this->params['named']['country_id'];
        }
        
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '')
        {
            $condition += array('Prisoner.doa >'=> date('Y-m-d',strtotime($this->params['named']['from_date'])));
            $from_date = date('Y-m-d',strtotime($this->params['named']['from_date']));
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '')
        {
            $condition += array('Prisoner.doa <'=> date('Y-m-d',strtotime($this->params['named']['to_date'])));
            $to_date = date('Y-m-d',strtotime($this->params['named']['to_date']));
        }
        
        $condition     += array('AND' => array( 
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'         => 1,
                //'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                'Prisoner.transfer_status !='        => 'Approved',
                /*'OR' => array( 
                        array('PrisonerOffence.offence_category_id' => 1,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-180 days"))), 
                        array('PrisonerOffence.offence_category_id' => 2,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-60 days"))), 
                ) */
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
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                   'table' => 'prisoner_offences',
                   'alias' => 'PrisonerOffence',
                   'type' => 'LEFT',
                   'conditions' => array('PrisonerCaseFile.id = PrisonerOffence.prisoner_case_file_id')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                array(
                    'table' => 'prisons',
                    'alias' => 'Prison',
                    'type' => 'inner',
                    array('Prisoner.prison_id = Prison.id')
                ),  
               
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
               /*  'Prisoner.age_on_admission',*/
                 'Prisoner.doa',              
                 'Prisoner.first_name',
                 'Prisoner.middle_name',
                 'Prisoner.last_name',
                 'Court.name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'Prison.geographical_id',
                 'PrisonerOffence.offence',
                 'PrisonerOffence.offence_category_id'
               )
        )+$limit;
        
        
        $datas = $this->paginate('Prisoner');
        
        //debug($datas); exit;
        
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status
        ));
    }
    
    public function lengthOfStayRemandReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $usertype = array(1,2);
        $district_condition = array();
        $station_condition = array();
        $region_condition = array();
        if(!in_array($this->Auth->user('usertype_id'),$usertype))
        {
            $district_condition +=  array('District.id' => $this->Auth->user('district_id'));
            $station_condition += array('Prison.district_id' => $this->Auth->user('district_id'));
            $region_condition += array('State.id' => $this->Auth->user('state_id'));
        }
        
        $this->loadModel('District');
        $this->loadModel('State');
        $this->loadModel('Country');
        $this->loadModel('Prison');
        
        $prisonListData = $this->Prison->find('list', array(
                'joins'=>array(
                                array(
                                    'table' => 'users',
                                    'alias'=> 'User',
                                    'type'=> 'inner',
                                    'conditions'=>array('User.district_id=Prison.district_id'),
                                ),
                ),
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => $station_condition,
            ));
            
         $districtListData = $this->District->find('list', array(
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => $district_condition,
               
            )); 
            
            $regionListData = $this->State->find('list', array(
                'fields'        => array(
                    'State.id',
                    'State.name',
                ),
                'conditions'    => $region_condition,
               
            )); 
            
        $countryList = $this->Country->find('list', array(
                    'fields'        => array(
                        'Country.id',
                        'Country.name',
                    ),
                    'conditions'    => array(
                        'Country.is_enable'     => 1,
                        'Country.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Country.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonListData'  => $prisonListData,
                    'districtListData' => $districtListData,
                    'regionListData' => $regionListData,
                    'countryList' => $countryList,
                ));
    }
    
    public function lengthOfStayRemandReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition = array();
        
        if(isset($this->params['named']['station_id']) && $this->params['named']['station_id'] != '')
        {
            $condition += array("Prisoner.prison_id IN (".$this->params['named']['station_id'].")");
            $station_id = $this->params['named']['station_id'];
        }
        
        if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '')
        {
            $condition += array("Prisoner.district_id IN (".$this->params['named']['district_id'].")");
            $district_id = $this->params['named']['district_id'];
        }
        
        if(isset($this->params['named']['region_id']) && $this->params['named']['region_id'] != '')
        {
            $condition += array("Prisoner.state_id IN (".$this->params['named']['region_id'].")");
            $region_id = $this->params['named']['region_id'];
        }
        if(isset($this->params['named']['country_id']) && $this->params['named']['country_id'] != '')
        {
            $condition += array('Prisoner.country_id'=>$this->params['named']['country_id']);
            $country_id = $this->params['named']['country_id'];
        }
        
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '')
        {
            $condition += array('Prisoner.doa >'=> date('Y-m-d',strtotime($this->params['named']['from_date'])));
            $from_date = date('Y-m-d',strtotime($this->params['named']['from_date']));
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '')
        {
            $condition += array('Prisoner.doa <'=> date('Y-m-d',strtotime($this->params['named']['to_date'])));
            $to_date = date('Y-m-d',strtotime($this->params['named']['to_date']));
        }
        
        $condition     += array('AND' => array( 
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'         => 1,
                //'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                'Prisoner.transfer_status !='        => 'Approved',
                'OR' => array( 
                        array('PrisonerOffence.offence_category_id' => 1,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-180 days"))), 
                        array('PrisonerOffence.offence_category_id' => 2,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-60 days"))), 
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
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                   'table' => 'prisoner_offences',
                   'alias' => 'PrisonerOffence',
                   'type' => 'LEFT',
                   'conditions' => array('PrisonerCaseFile.id = PrisonerOffence.prisoner_case_file_id')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                array(
                    'table' => 'prisons',
                    'alias' => 'Prison',
                    'type' => 'inner',
                    array('Prisoner.prison_id = Prison.id')
                ),  
               
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
               /*  'Prisoner.age_on_admission',*/
                 'Prisoner.doa',              
                 'Prisoner.first_name',
                 'Prisoner.middle_name',
                 'Prisoner.last_name',
                 'Court.name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'Prison.geographical_id',
                 'PrisonerOffence.offence',
                 'PrisonerOffence.offence_category_id'
               )
        )+$limit;
        
        
        $datas = $this->paginate('Prisoner');
        
        //debug($datas); exit;
        
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status
        ));
    }
    
    public function caseBackLogReport()
    {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $usertype = array(1,2);
        $district_condition = array();
        $station_condition = array();
        $region_condition = array();
        if(!in_array($this->Auth->user('usertype_id'),$usertype))
        {
            $district_condition +=  array('District.id' => $this->Auth->user('district_id'));
            $station_condition += array('Prison.district_id' => $this->Auth->user('district_id'));
            $region_condition += array('State.id' => $this->Auth->user('state_id'));
        }
        
        $this->loadModel('District');
        $this->loadModel('State');
        $this->loadModel('Country');
        $this->loadModel('Prison');
        
        $prisonListData = $this->Prison->find('list', array(
                'joins'=>array(
                                array(
                                    'table' => 'users',
                                    'alias'=> 'User',
                                    'type'=> 'inner',
                                    'conditions'=>array('User.district_id=Prison.district_id'),
                                ),
                ),
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => $station_condition,
            ));
            
         $districtListData = $this->District->find('list', array(
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => $district_condition,
               
            )); 
            
            $regionListData = $this->State->find('list', array(
                'fields'        => array(
                    'State.id',
                    'State.name',
                ),
                'conditions'    => $region_condition,
               
            )); 
            
        $countryList = $this->Country->find('list', array(
                    'fields'        => array(
                        'Country.id',
                        'Country.name',
                    ),
                    'conditions'    => array(
                        'Country.is_enable'     => 1,
                        'Country.is_trash'      => 0,
                    ),
                    'order'         => array(
                        'Country.name',
                    ),
                ));
      
        $this->set(array(
                    'prisonListData'  => $prisonListData,
                    'districtListData' => $districtListData,
                    'regionListData' => $regionListData,
                    'countryList' => $countryList,
                ));
    }
    
    public function caseBackLogReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $magisterial_id    = '';
        $court_id    = '';
        $attendance_date    = '';
        $status = '';
        $condition = array();
        
        if(isset($this->params['named']['station_id']) && $this->params['named']['station_id'] != '')
        {
            $condition += array("Prisoner.prison_id IN (".$this->params['named']['station_id'].")");
            $station_id = $this->params['named']['station_id'];
        }
        
        if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '')
        {
            $condition += array("Prisoner.district_id IN (".$this->params['named']['district_id'].")");
            $district_id = $this->params['named']['district_id'];
        }
        
        if(isset($this->params['named']['region_id']) && $this->params['named']['region_id'] != '')
        {
            $condition += array("Prisoner.state_id IN (".$this->params['named']['region_id'].")");
            $region_id = $this->params['named']['region_id'];
        }
        if(isset($this->params['named']['country_id']) && $this->params['named']['country_id'] != '')
        {
            $condition += array('Prisoner.country_id'=>$this->params['named']['country_id']);
            $country_id = $this->params['named']['country_id'];
        }
        
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '')
        {
            $condition += array('Prisoner.doa >'=> date('Y-m-d',strtotime($this->params['named']['from_date'])));
            $from_date = date('Y-m-d',strtotime($this->params['named']['from_date']));
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '')
        {
            $condition += array('Prisoner.doa <'=> date('Y-m-d',strtotime($this->params['named']['to_date'])));
            $to_date = date('Y-m-d',strtotime($this->params['named']['to_date']));
        }
        
        $condition     += array('AND' => array( 
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'         => 1,
                //'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                'Prisoner.transfer_status !='        => 'Approved',
               /* 'OR' => array( 
                        array('PrisonerOffence.offence_category_id' => 1,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-180 days"))), 
                        array('PrisonerOffence.offence_category_id' => 2,'date(Prisoner.doa) <' => date('Y-m-d',strtotime("-60 days"))), 
                )*/
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
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                array(
                    "table" => "prisoner_sentences",
                    "alias" => "PrisonerSentence",
                    "type" => "left",
                    "conditions" => array("PrisonerSentence.prisoner_id = Prisoner.id"),
                ),
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                   'table' => 'prisoner_offences',
                   'alias' => 'PrisonerOffence',
                   'type' => 'LEFT',
                   'conditions' => array('PrisonerCaseFile.id = PrisonerOffence.prisoner_case_file_id')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                array(
                    'table' => 'prisons',
                    'alias' => 'Prison',
                    'type' => 'inner',
                    array('Prisoner.prison_id = Prison.id')
                ),  
               
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
               /*  'Prisoner.age_on_admission',*/
                 'Prisoner.doa',              
                 'Prisoner.first_name',
                 'Prisoner.middle_name',
                 'Prisoner.last_name',
                 'Court.name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'Prison.geographical_id',
                 'PrisonerOffence.offence',
                 'PrisonerOffence.offence_category_id',
                 'PrisonerSentence.date_of_committal',
               )
        )+$limit;
        
        
        $datas = $this->paginate('Prisoner');
        
        //debug($datas); exit;
        
        $this->set(array(
            'datas'             => $datas,
            'prisoner_id'       => $prisoner_id,
            'magisterial_id'    => $magisterial_id,
            'court_id'          => $court_id,
            'attendance_date'   => $attendance_date,
            'status'            => $status
        ));
    }
    
    public function showAppCourtFile($prisoner_id = '', $value = '')
    {
        $this->autoRender = false;
        $this->loadModel('PrisonerSentence');
        
        if($prisoner_id != '')
        {
            
            $case_id=$this->PrisonerSentence->find('all',array(
                  "recursive" => -1,
                  'conditions'=>array(
                   'PrisonerSentence.wish_to_appeal'=>1,
                   'PrisonerSentence.prisoner_id'=>$prisoner_id,
                  ),
                  'fields'=>array('PrisonerSentence.case_id'),
                  'order'=>array(
                    'PrisonerSentence.id'
                  )
            ));
            
            $caseFile = '';
            if(count($case_id) > 0)
            {
                
                foreach($case_id as $case_key => $case_val)
                {
                    $caseFile .= $case_val['PrisonerSentence']['case_id'].',';
                }
            }
            
            $caseFile = rtrim($caseFile,',');
            $caseFilearr = explode(',',$caseFile);
            
            if($value==1)
            {
                $caseFileno=$this->PrisonerCaseFile->find('list',array(
                          "recursive" => -1,
                          'conditions'=>array(
                           'PrisonerCaseFile.is_trash'=>0,
                           'PrisonerCaseFile.prisoner_id'=>$prisoner_id,
                           'PrisonerCaseFile.id'=>$caseFilearr,
                          ),
                          'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no'),
                          'order'=>array(
                            'PrisonerCaseFile.case_file_no'
                          )
                    ));
            }
            else
            {
                $caseFileno=$this->PrisonerCaseFile->find('list',array(
                          "recursive" => -1,
                          'conditions'=>array(
                           'PrisonerCaseFile.is_trash'=>0,
                           'PrisonerCaseFile.prisoner_id'=>$prisoner_id,
                        ),
                          'fields'=>array('PrisonerCaseFile.id','PrisonerCaseFile.file_no'),
                          'order'=>array(
                            'PrisonerCaseFile.case_file_no'
                          )
                    ));
            }   
            
            
            
            $options = '';
            if(count($caseFileno) > 0)
            {
                foreach($caseFileno as $filekey => $fileval)
                {
                    $options .= '<option value='.$filekey.'>'.$fileval.'</option>';
                }
            }
            else
            {
                $options .= '<option value=""></option>';
            }
        }
         
        echo $options;
    }
    //PARTHA CODE START PETETION IN COURT ATTENDANCE
     function isPetition($prisoner_id)
    {
        $count = $this->Prisoner->find('count', array(
            'recursive'=>-1,
            'joins' => array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'inner',
                'conditions'=> array('Prisoner.id = PrisonerSentence.prisoner_id')
                )
            ),
            'fields'=>array('PrisonerSentence.sentence_of'),
            'conditions'    => array(
                'PrisonerSentence.prisoner_id' => $prisoner_id,
                //'Prisoner.is_long_term_prisoner' => 1,
                '0' => '(PrisonerSentence.sentence_of = 4 OR PrisonerSentence.sentence_of = 5 OR Prisoner.is_long_term_prisoner = 1)'
            )
        ));
        return $count;
    }

      function prisonerPetition(){
        //debug($this->request->data);
        if(isset($this->request->data["PrisonerPetition"]) && count($this->request->data["PrisonerPetition"])>0)
                        {
                            // debug($this->request->data);exit;
                            if($this->request->data['PrisonerPetition']['petition_date'] != '')
                            {
                                $this->request->data['PrisonerPetition']['petition_date'] = date('Y-m-d', strtotime($this->request->data['PrisonerPetition']['petition_date']));
                            }
                            
                            // $appealData['PrisonerPetition'] = $this->request->data['PrisonerPetition'];
                            

                            // debug($this->request->data['PrisonerPetition']);exit;
                            $this->loadModel('PrisonerPetition');
                                if($this->PrisonerPetition->saveAll($this->request->data))
                                {   //echo '1';exit;
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Petition Saved Successfully !');

                                    $this->redirect(array('action'=>'/index/'.$this->request->data['PrisonerPetition']['uuid'].'#petiontab')); 
                                }
                                else 
                                {   //echo '2';exit;
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Petition Saving Failed !');
                                    $this->redirect(array('action'=>'/index/',$this->request->data['PrisonerPetition']['uuid'].'#petiontab')); 
                                }

                                    
                            }
                            
    }

    function petitionAjax(){
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $this->loadModel('PrisonerPetition');
        $condition      = array(
            'PrisonerPetition.is_trash' => 0
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSentence.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerSentence.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerSentence.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        if(isset($this->params['data']['prisoner_id']) && $this->params['data']['prisoner_id'] != ''){
            $prisoner_id = $this->params['data']['prisoner_id'];
            $condition += array('PrisonerPetition.prisoner_id' => $prisoner_id );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }              
        //debug($condition); exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerPetition.modified',
            ),
            'limit'         => 20,
        );
      
        $datas = $this->paginate('PrisonerPetition');
        // debug($datas); exit;
        $this->set(array(
            'datas'         =>  $datas,  
            'prisoner_id'         =>  $prisoner_id,  
            
        ));
    }

    function getPetitionOffence($case_id)
    {
        $this->autoRender=false;
        $result = array(); 
        // if($prisoner_id != '')
        // {
            $result   = $this->PrisonerOffence->find('list', array(
                'joins' => array(
                    array(
                    'table' => 'prisoner_case_files',
                    'alias' => 'PrisonerCaseFile',
                    'type' => 'inner',
                    'conditions'=> array('PrisonerOffence.prisoner_case_file_id = PrisonerCaseFile.id')
                    ),
                    array(
                    'table' => 'prisoner_sentences',
                    'alias' => 'PrisonerSentence',
                    'type' => 'inner',
                    'conditions'=> array('PrisonerSentence.case_id = PrisonerCaseFile.id')
                    ),
                ), 
                'fields'=>array(
                    'PrisonerOffence.id',
                    'PrisonerOffence.offence_no'
                ),
                'conditions'    => array(
                    'PrisonerCaseFile.is_trash'     => 0,
                   // 'PrisonerSentence.wish_to_appeal'=> 1,
                    'PrisonerOffence.prisoner_case_file_id'  => $case_id
                )
            ));
        //}
            //debug($result);
        if(is_array($result) && count($result)>0){
                echo '<option value=""></option>';
                foreach($result as $resultKey=>$resultVal){
                    echo '<option value="'.$resultKey.'">'.$resultVal.'</option>';
                }
            }else{
                echo '<option value=""></option>';
            }
    }
    public function appealAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerSentenceAppeal.is_trash'         => 0,
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSentenceAppeal.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerSentenceAppeal.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerSentenceAppeal.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        $editPrisoner = 0;
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerSentenceAppeal.prisoner_id' => $prisoner_id );
        }
        if(isset($this->params['named']['puuid']) && $this->params['named']['puuid'] != ''){
            $puuid = $this->params['named']['puuid'];
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.pdf');
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
                'PrisonerSentenceAppeal.modified',
            ),
            'limit'         => 20,
        ); 
        $datas = $this->paginate('PrisonerSentenceAppeal'); 
        $this->set(array(
            'datas'         =>  $datas,  
            'prisoner_id'   =>  $prisoner_id,
            'puuid'         =>  $puuid,
            'editPrisoner'  =>  $editPrisoner,
            'funcall'       =>  $this
        ));
    }
     function courtList()
    {
        $this->autoRender = false;
        $courtlevel_id = $this->request->data['courtlevel_id'];
        //$courtHtml = '<option value="">-- Select Court --</option>';
        $courtHtml = '<option value=""></option>';
        if(isset($courtlevel_id) && (int)$courtlevel_id != 0)
        {
            $courtList = $this->Court->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Court.id',
                    'Court.name',
                ),
                'conditions'    => array(
                    'Court.courtlevel_id'     => $courtlevel_id,
                    'Court.is_enable'      => 1,
                    'Court.is_trash'       => 0,
                ),
                'order'         => array(
                    'Court.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($courtList as $courtKey=>$courtVal)
            {
                $courtHtml .= '<option value="'.$courtKey.'">'.$courtVal.'</option>';
            }
        }
        //$countryHtml .= '<option value="other">Other</option>';
        echo $courtHtml;  
    }
    function getCaseFile($prisoner_id=''){
        $this->loadModel('PrisonerCaseFile');
        
        $condition = array(
            'PrisonerCaseFile.prisoner_id'    => $prisoner_id
        );
          $prisonerCaseFile = $this->PrisonerCaseFile->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerCaseFile.id',
                'PrisonerCaseFile.file_no'

            ),
            'conditions'    => $condition
        ));
            return implode(",", $prisonerCaseFile);
       
       //  return $prisonerCaseFile['PrisonerCaseFile']['file_no'];
     }
     function getOffence($prisoner_id=''){
        $this->loadModel('PrisonerOffence');
        
        $condition = array(
            'PrisonerOffence.prisoner_id'    => $prisoner_id
        );
          $prisonerOffence = $this->PrisonerOffence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerOffence.id',
                'PrisonerOffence.offence_no'

            ),
            'conditions'    => $condition
        ));
            return implode(",", $prisonerOffence);
       
       //  return $prisonerCaseFile['PrisonerCaseFile']['file_no'];
     }
    //PARTHA CODE END PETETION IN COURT ATTENDANCE
    
}