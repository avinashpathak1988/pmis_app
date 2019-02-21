<?php
App::uses('AppController', 'Controller');
class StagesController  extends AppController {
    public $layout='table';
    public $uses=array('Stage','StageAssign','StagePromotion','StageDemotion','StageReinstatement','Prisoner','StageHistory','ApprovalProcess', 'EarningRatePrisoner','EarningGradePrisoner', 'EarningRate');

    public function stageReinstatementList()
    {
        $this->set('funcall',$this);
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
                $status = $this->setApprovalProcess($items, 'StageReinstatement', $status, $remark);
                if($status == 1)
                {
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
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
                    'default_status'=>$default_status
                ));
    }
    public function stagereinstatementListAjax(){
        $this->layout           = 'ajax';
        $prisoner_id    = '';
        $condition              = array(
            'StageReinstatement.is_trash'      => 0,
        );
         if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'StageReinstatement.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('StageReinstatement.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('StageReinstatement.status !='=>'Draft');
                $condition      += array('StageReinstatement.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('StageReinstatement.status !='=>'Draft');
                $condition      += array('StageReinstatement.status !='=>'Saved');
                $condition      += array('StageReinstatement.status !='=>'Review-Rejected');
                $condition      += array('StageReinstatement.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'StageReinstatement.prisoner_id'   => $prisoner_id,
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
                'StageReinstatement.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('StageReinstatement');
        $this->set(array(
            'datas'                     => $datas,
            'prisoner_id'                   => $prisoner_id,
        ));
    }
    public function stagePromotionList()
    {
        $this->set('funcall',$this);
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
                $result_status = $this->setApprovalProcess($items, 'StagePromotion', $status, $remark);
                if($result_status == 1)
                {
                    if($status == Configure::read('Approved'))
                    {
                        $fIdList = array_column($items, 'fid');
                        $idlist = implode(',',$fIdList);
                        $earning_stages = Configure::read('STAGE-III').','.Configure::read('STAGE-IV').','.Configure::read('SPECIAL-STAGE');

                        if(count($fIdList) > 0)
                        {
                            // $sql = "select DISTINCT(prisoner_id) from stage_promotions where new_stage_id in (".$earning_stages.") and status = 'Approved' and id in (".$idlist.")";
                            // $prisonerIdList = $this->Prisoner->query($sql);
                            //update prisoner grade and earning rate based on stage 
                            $sql = "select DISTINCT(a.prisoner_id) from stage_promotions a inner join prisoners b on a.prisoner_id = b.id where b.earning_rate_id = 0 and b.earning_grade_id = 0 and a.new_stage_id = ".Configure::read('STAGE-III')." and a.status = 'Approved' and a.id in (".$idlist.")";
                            $prisonerIdList = $this->Prisoner->query($sql);
                            //debug($sql); //exit;
                            if(count($prisonerIdList) > 0)
                            {
                               $gradec_earning_rate_data = $this->EarningRate->find('first', array(
                                    
                                    'conditions'    => array(
                                        'EarningRate.earning_grade_id' => Configure::read('GRADE-C')
                                    )
                                ));
                                $gradec_earning_rate = 0;
                                if(isset($gradec_earning_rate_data['EarningRate']['id']))
                                    $gradec_earning_rate = $gradec_earning_rate_data['EarningRate']['id'];
                                $prisonerIdList = array_column($prisonerIdList, 'a');
                                if(count($prisonerIdList) > 0)
                                {
                                    //debug($prisonerIdList); exit;
                                    foreach($prisonerIdList as $prisonerId)
                                    {
                                        //set prisoner earning grade details 
                                        $prisonerGradeData = '';
                                        $prisonerGradeData['EarningGradePrisoner']['assignment_date'] = date('Y-m-d');
                                        $prisonerGradeData['EarningGradePrisoner']['prisoner_id'] = $prisonerId['prisoner_id'];
                                        $prisonerGradeData['EarningGradePrisoner']['grade_id'] = Configure::read('GRADE-C');
                                        $prisonerGradeData['EarningGradePrisoner']['status'] = Configure::read('Approved');
                                        $prisonerGradeData['EarningGradePrisoner']['created'] = date('Y-m-d H:i:s');
                                        if($this->EarningGradePrisoner->save($prisonerGradeData))
                                        {
                                            if($this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', '', 'Insert', json_encode($prisonerGradeData)))
                                            {}
                                        }
                                        //set prisoner earning rate details 
                                        $prisonerRateData = '';
                                        $prisonerRateData['EarningRatePrisoner']['date_of_assignment'] = date('Y-m-d');
                                        $prisonerRateData['EarningRatePrisoner']['prisoner_id'] = $prisonerId['prisoner_id'];
                                        $prisonerRateData['EarningRatePrisoner']['earning_rate_id'] = $gradec_earning_rate;
                                        $prisonerRateData['EarningRatePrisoner']['created'] = date('Y-m-d H:i:s');
                                        if($this->EarningRatePrisoner->save($prisonerRateData)){
                                            if($this->auditLog('EarningRatePrisoner', 'earning_rate_prisoners', '', 'Insert', json_encode($prisonerRateData)))
                                            {}
                                        }
                                        //update prisoner earning rate and grade details 
                                        $prisonerData['Prisoner']['id'] = $prisonerId['prisoner_id'];
                                        $prisonerData['Prisoner']['earning_rate_id'] = $gradec_earning_rate;
                                        $prisonerData['Prisoner']['earning_grade_id'] = Configure::read('GRADE-C');
                                        if($this->Prisoner->save($prisonerData)){
                                            if($this->auditLog('Prisoner', 'prisoners', '', 'Update', json_encode($prisonerData)))
                                            {}
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
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
                    'default_status'=>$default_status
                ));
    }
    public function stagepromotionListAjax(){
        $this->layout           = 'ajax';
        $prisoner_id    = '';
        $condition              = array(
            'StagePromotion.is_trash'      => 0,
        );
         if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'StagePromotion.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('StagePromotion.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('StagePromotion.status !='=>'Draft');
                $condition      += array('StagePromotion.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('StagePromotion.status !='=>'Draft');
                $condition      += array('StagePromotion.status !='=>'Saved');
                $condition      += array('StagePromotion.status !='=>'Review-Rejected');
                $condition      += array('StagePromotion.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'StagePromotion.prisoner_id'   => $prisoner_id,
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
                'StagePromotion.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('StagePromotion');
        $this->set(array(
            'datas'                     => $datas,
            'prisoner_id'                   => $prisoner_id,
        ));
    }
    public function index(){
        		/*
				 *Code for add the court attendance records
				*/				
				if(isset($this->data['Stage']) && is_array($this->data['Stage']) && count($this->data['Stage']) >0){
					
					if(isset($this->data['Stage']['uuid']) && $this->data['Stage']['uuid'] == ''){
						$uuidArr = $this->Stage->query("select uuid() as code");
						$this->request->data['Stage']['uuid'] = $uuidArr[0][0]['code'];
					}
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();  
					if($this->Stage->save($this->data)){

                        $refId = 0;
                        $action = 'Add';
                        if(isset($this->request->data['Stage']['id']) && (int)$this->request->data['Stage']['id'] != 0)
                        {
                            $refId = $this->request->data['Stage']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('Stage', 'stages', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect('/stages');
                        }
	                    else {
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
				
				/*
				 *Code for edit the court attendance records
				*/				
		        if(isset($this->data['StageEdit']['id']) && (int)$this->data['StageEdit']['id'] != 0){
		            if($this->Stage->exists($this->data['StageEdit']['id'])){
		                $this->data = $this->Stage->findById($this->data['StageEdit']['id']);
		            }
		        }
		        /*
		         *Code for delete the court attendance records
		         */	
		        if(isset($this->data['StageDelete']['id']) && (int)$this->data['StageDelete']['id'] != 0){
		            if($this->Stage->exists($this->data['StageDelete']['id'])){
	                    $this->Stage->id = $this->data['StageDelete']['id'];
                        $db = ConnectionManager::getDataSource('default');
                        $db->begin();
	                    if($this->Stage->saveField('is_trash',1)){
                            if($this->auditLog('Stage', 'stages', $this->data['StageDelete']['id'], 'Delete', json_encode(array('is_trash'=>1))))
                            {
                                $db->commit(); 
    							$this->Session->write('message_type','success');
    		                    $this->Session->write('message','Deleted Successfully !');
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Delete Failed !');
                            }
	                    }else{
							$db->rollback();
                            $this->Session->write('message_type','error');
		                    $this->Session->write('message','Delete Failed !');
	                    }
	                    $this->redirect('/stages/');		                
		            }
		        }       
    }  
    public function indexAjax()
	 {
	 	$this->layout 			= 'ajax';
    	$name 		= '';
    	$privileges_descr 		= '';
    	
    	$condition 				= array(
    		'Stage.is_trash'		=> 0,
    		'Stage.is_enable'	    => 1,
    	);
		if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
    		$name = $this->params['named']['name'];
    		$condition += array(
    			'Stage.name'	=> $name,
    		);    		
    	}
		if(isset($this->params['named']['privileges_descr']) && $this->params['named']['privileges_descr'] != ''){
    		$privileges_descr = $this->params['named']['privileges_descr'];
    		$condition += array(
    			'Stage.privileges_descr'	=> $privileges_descr,
    		);     		
    	}      	
		  	
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','discharge_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','discharge_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    	
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    		 'Stage.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('Stage');
    	$this->set(array(
    		'datas'						=> $datas,
    		'name'						=> $name,
    		'privileges_descr'			=> $privileges_descr,
    	));     	
    	}

	public function stagesAssign($uuid)
	{

		if($uuid){
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));
            $longterm=$this->Prisoner->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.is_long_term_prisoner',
                    ),
                    'conditions'    => array(
                        'Prisoner.uuid'    => $uuid,
                    ),
            ));
            $is_lingterm = '';
            $is_lingterm = $longterm["Prisoner"]["is_long_term_prisoner"];
            $stagehistorylast=$this->StageHistory->find('first',array(
                    'conditions'    => array(
                        'StageHistory.prisoner_id'    => $prisonList['Prisoner']['id'],
                        //'StageHistory.type'    => 'Stage Promotion',
                        'StageHistory.is_trash'    =>0,
                    ),
                    'order'=>'StageHistory.id DESC',
            ));
            //debug($stagehistorylast); exit;
            $probationary_period_list=array("3 Month"=>"3 Month","6 Month"=>"6 Month","12 Month"=>"12 Month",);
         if(isset($prisonList['Prisoner']['id']) && (int)$prisonList['Prisoner']['id'] != 0){
                $prisoner_id = $prisonList['Prisoner']['id'];   
                //debug($prisoner_id);
            /*
            *code add the Stage Assign
            */
            $dataArr['StageHistory']['prisoner_id']=$prisoner_id;//To insert into history
              if(isset($this->data['StageAssign']) && is_array($this->data['StageAssign']) && $this->data['StageAssign']!='')
              {
                 if(isset($this->data['StageAssign']['uuid']) && $this->data['StageAssign']['uuid']=='')
                 {
                   
                    $uuidArr=$this->StageAssign->query("select uuid() as code");
                    $this->request->data['StageAssign']['uuid']=$uuidArr[0][0]['code'];
                   
                 }  
                 if(isset($this->data['StageAssign']['date_of_assign']) && $this->data['StageAssign']['date_of_assign']!="" )
                 {
                    $this->request->data['StageAssign']['date_of_assign']=date('Y-m-d',strtotime($this->data['StageAssign']['date_of_assign']));
                 }
                 
                $dataArr['StageHistory']['stage_id']=$this->data['StageAssign']['stage_id'];
                $dataArr['StageHistory']['type']="Stage Assigned";
                $dataArr['StageHistory']['date_of_stage']=$this->data['StageAssign']['date_of_assign'];
                
                $db = ConnectionManager::getDataSource('default');
                $db->begin(); 
                if($this->StageAssign->save($this->data))
                {
                    $refId = 0;
                    $action = 'Add';
                    if(isset($this->request->data['StageAssign']['id']) && (int)$this->request->data['StageAssign']['id'] != 0)
                    {
                        $refId = $this->request->data['StageAssign']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if(!$this->auditLog('StageAssign', 'stage_assigns', $refId, $action, json_encode($this->data)))
                    {
                        $db->rollback();
                    }
                    if($this->StageHistory->save($dataArr))
                    {
                        if(!$this->multipleAuditLog(array('StageAssign','StageHistory'), array('stage_assigns','stage_histories'), array($refId, 0), array($action,'Add'), array(json_encode($this->data), json_encode($dataArr))))
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                        else 
                        {
                            $db->commit();  
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved successfully');
                            $this->redirect('/stages/stagesAssign/'.$uuid.'#stageAssign');
                        }
                    }
                    else
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                } 
                else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','saving failed');

                 }
              }
            /*
             *Code for edit the Stage Assign
             */
            if(isset($this->data['StageAssignEdit']['id']) && (int)$this->data['StageAssignEdit']['id'] != 0){
                if($this->StageAssign->exists($this->data['StageAssignEdit']['id'])){
                    $this->data = $this->StageAssign->findById($this->data['StageAssignEdit']['id']);
                }
            }


            /*
            *code add the StagePromotion 
            */
          if(isset($this->data['StagePromotion']) && is_array($this->data['StagePromotion']) && $this->data['StagePromotion']!='')
          {
            //debug($this->data['InPrisonOffenceCapture']['uuid']);
             if(isset($this->data['StagePromotion']['uuid']) && $this->data['StagePromotion']['uuid']=='')
             {
               
                $uuidArr=$this->StagePromotion->query("select uuid() as code");
                $this->request->data['StagePromotion']['uuid']=$uuidArr[0][0]['code'];
               
             }  
             if(isset($this->data['StagePromotion']['promotion_date']) && $this->data['StagePromotion']['promotion_date']!="" )
             {
                $this->request->data['StagePromotion']['promotion_date']=date('Y-m-d',strtotime($this->data['StagePromotion']['promotion_date']));
             }
             $dataArr['StageHistory']['stage_id']=$this->data['StagePromotion']['new_stage_id'];
             $dataArr['StageHistory']['type']="Stage Promotion";
             $dataArr['StageHistory']['date_of_stage']=$this->data['StagePromotion']['promotion_date'];
                
             $db = ConnectionManager::getDataSource('default');
             $db->begin(); 
             if($this->StagePromotion->save($this->data))
             {
                $refId = 0;
                $action = 'Add';
                if(isset($this->request->data['StagePromotion']['id']) && (int)$this->request->data['StagePromotion']['id'] != 0)
                {
                    $refId = $this->request->data['StagePromotion']['id'];
                    $action = 'Edit';
                }
                if($this->StageHistory->save($dataArr))
                {
                    if(!$this->multipleAuditLog(array('StagePromotion','StageHistory'), array('stage_promotions','stage_histories'), array($refId, 0), array($action,'Add'), array(json_encode($this->data), json_encode($dataArr))))
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                    else 
                    {
                        $db->commit();  
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/stages/stagesAssign/'.$uuid.'#stagePromotion');
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
             } 
             else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','saving failed');

             }
          }
            /*
             *Code for edit the Stage Promotion
             */
            if(isset($this->data['StagePromotionEdit']['id']) && (int)$this->data['StagePromotionEdit']['id'] != 0){
                if($this->StagePromotion->exists($this->data['StagePromotionEdit']['id'])){
                    $this->data = $this->StagePromotion->findById($this->data['StagePromotionEdit']['id']);
                }
            }
            /*
            *code add the StageDemotion 
            */
          if(isset($this->data['StageDemotion']) && is_array($this->data['StageDemotion']) && $this->data['StageDemotion']!='')
          {
            //debug($this->data['InPrisonOffenceCapture']['uuid']);
             if(isset($this->data['StageDemotion']['uuid']) && $this->data['StageDemotion']['uuid']=='')
             {
               
                $uuidArr=$this->StageDemotion->query("select uuid() as code");
                $this->request->data['StageDemotion']['uuid']=$uuidArr[0][0]['code'];
               
             }  
             if(isset($this->data['StageDemotion']['demotion_date']) && $this->data['StageDemotion']['demotion_date']!="" )
             {
                $this->request->data['StageDemotion']['demotion_date']=date('Y-m-d',strtotime($this->data['StageDemotion']['demotion_date']));
             }
             $dataArr['StageHistory']['stage_id']=$this->data['StageDemotion']['new_stage_id'];
             $dataArr['StageHistory']['type']="Stage Demotion";
             $dataArr['StageHistory']['date_of_stage']=$this->data['StageDemotion']['demotion_date'];
                
             $db = ConnectionManager::getDataSource('default');
             $db->begin(); 
             if($this->StageDemotion->save($this->data))
             {
                $refId = 0;
                $action = 'Add';
                if(isset($this->request->data['StageDemotion']['id']) && (int)$this->request->data['StageDemotion']['id'] != 0)
                {
                    $refId = $this->request->data['StageDemotion']['id'];
                    $action = 'Edit';
                }
                //save audit log 
                if(!$this->auditLog('StageDemotion', 'stage_demotions', $refId, $action, json_encode($this->data)))
                {
                    $db->rollback();
                }
                if($this->StageHistory->save($dataArr))
                {
                    if(!$this->auditLog('StageHistory', 'stage_histories', 0, 'Add', json_encode($dataArr)))
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                    else 
                    {
                        $db->commit();  
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/stages/stagesAssign/'.$uuid.'#stageDemotion');
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');

                }
                
             } 
             else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','saving failed');

             }
          }
            /*
             *Code for edit the Stage Demotion
             */
            if(isset($this->data['StageDemotionEdit']['id']) && (int)$this->data['StageDemotionEdit']['id'] != 0){
                if($this->StageDemotion->exists($this->data['StageDemotionEdit']['id'])){
                    $this->data = $this->StageDemotion->findById($this->data['StageDemotionEdit']['id']);
                }
            }
	        /*
	        *code add the Stage Reinstatement 
	        */
            if(isset($this->data['StageReinstatement']) && is_array($this->data['StageReinstatement']) && $this->data['StageReinstatement']!='')
            {
                //debug($this->data['InPrisonOffenceCapture']['uuid']);
                if(isset($this->data['StageReinstatement']['uuid']) && $this->data['StageReinstatement']['uuid']=='')
                {
                    $uuidArr=$this->StageReinstatement->query("select uuid() as code");
                    $this->request->data['StageReinstatement']['uuid']=$uuidArr[0][0]['code'];
                }  
                if(isset($this->data['StageReinstatement']['reinstatement_date']) && $this->data['StageReinstatement']['reinstatement_date']!="" )
                {
                    $this->request->data['StageReinstatement']['reinstatement_date']=date('Y-m-d',strtotime($this->data['StageReinstatement']['reinstatement_date']));
                }
                $dataArr['StageHistory']['stage_id']=$this->data['StageReinstatement']['stage_reinstated_to'];
                $dataArr['StageHistory']['type']="Stage Reinstatement";
                $dataArr['StageHistory']['date_of_stage']=$this->data['StageReinstatement']['reinstatement_date'];
                $dataArr['StageHistory']['probationary_period']=$this->data['StageReinstatement']['probationary_period'];
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->StageReinstatement->save($this->data))
                {
                    $this->StageHistory->save($dataArr);
                    $refId = 0;
                    $action = 'Add';
                    if(isset($this->request->data['StageReinstatement']['id']) && (int)$this->request->data['StageReinstatement']['id'] != 0)
                    {
                        $refId = $this->request->data['StageReinstatement']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if(!$this->auditLog('StageReinstatement', 'stage_reinstatements', $refId, $action, json_encode($this->data)))
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                    else 
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/stages/stagesAssign/'.$uuid.'#stageReinstatement');
                    }
                } 
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
            /*
             *Code for edit the Stage Reinstatement
             */
            if(isset($this->data['StageReinstatementEdit']['id']) && (int)$this->data['StageReinstatementEdit']['id'] != 0){
            	
                if($this->StageReinstatement->exists($this->data['StageReinstatementEdit']['id'])){
                    $this->data = $this->StageReinstatement->findById($this->data['StageReinstatementEdit']['id']);
                }
            }
             $oldSatgeList=$this->Stage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Stage.id',
                        'Stage.name',
                    ),
                    'conditions'    => array(
                        'Stage.is_enable'    => 1,
                        'Stage.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Stage.name'
                    )
                )); 
             if(!empty($stagehistorylast)){
                if($stagehistorylast["StageHistory"]["stage_id"]!=''){
                    $newSatgeListcount=$this->Stage->find('all',array());

                     $newSatgeListfirst=$this->Stage->find('first',array(
                        'conditions'    => array(
                            'Stage.id'    => $stagehistorylast["StageHistory"]["stage_id"],
                            'Stage.is_enable'    => 1,
                            'Stage.is_trash'     => 0,
                        ),

                    ));
                     //$newSatgeListfirst["Stage"]["stage_order"];
                   // exit;
                    $newSatgeList=$this->Stage->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Stage.id',
                            'Stage.name',
                        ),
                        'conditions'    => array(
                            'Stage.id !='    => $stagehistorylast["StageHistory"]["stage_id"],
                            'Stage.is_enable'    => 1,
                            'Stage.is_trash'     => 0,
                        ),
                        // 'order'=>array(
                        //     'Stage.name'
                        // ),
                        'limit'=>count($newSatgeListcount),
                        'offset'=>$newSatgeListfirst["Stage"]["stage_order"]-1,
                    )); 
                }
             }
             else{
                $newSatgeList=$this->Stage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Stage.id',
                        'Stage.name',
                    ),
                    'conditions'    => array(
                        'Stage.is_enable'    => 1,
                        'Stage.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Stage.name'
                    )
                )); 
             }
             
             $reinstated_stage_List=$this->Stage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Stage.id',
                        'Stage.name',
                    ),
                    'conditions'    => array(
                        'Stage.is_enable'    => 1,
                        'Stage.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Stage.name'
                    )
                )); 
            $this->set(array(
                    'uuid'              => $uuid,
                    'is_lingterm'=>$is_lingterm,
                    'prisoner_id'       => $prisoner_id,
                    'oldSatgeList'		=> $oldSatgeList,
                    'newSatgeList'		=> $newSatgeList,
                    'reinstated_stage_List'	=> $reinstated_stage_List,
                    'stagehistorylast'=>$stagehistorylast,
                    'probationary_period_list'=>$probationary_period_list
                ));
             }
      
      else{
                return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
           
         }
        } else{
            return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
        }   
	}
    public function stagesAssignAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageAssign.prisoner_id'     => $prisoner_id,
                'StageAssign.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageAssign.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StageAssign');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     }
     public function deleteStageAssign()
     {
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'StageAssign.is_trash'    => 1,
            );
            $conds = array(
                'StageAssign.uuid'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->StageAssign->updateAll($fields, $conds)){
                if($this->auditLog('StageAssign', 'stage_assigns', $uuid, 'Delete', json_encode(array($fields,$uuid))))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else 
                {
                    $db->rollback();
                    echo 'FAIL';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
	public function stagesPromotionAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StagePromotion.prisoner_id'     => $prisoner_id,
                'StagePromotion.is_trash'        => 0,
                'StagePromotion.status'          => 'Draft',
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StagePromotion.id'    => 'DESC',
                ),
                'limit'  => 1
            );
            $datas = $this->paginate('StagePromotion');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     } 
     public function deleteStagePromotion()
     {
        $this->autoRender = false;
        
        if(isset($this->params['named']['paramId'])){
            $uuid = $this->params['named']['stagePromotion_uuid'];
            $paramId = $this->params['named']['paramId'];
            $prisoner_id=$this->params['named']['prisoner_id'];

            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.id'     => $prisoner_id,
                ),
            ));
            $new_stage_id=$this->params['named']['new_stage_id'];
            $fields = array(
                'StagePromotion.is_trash'    => 1,
            );
            $conds = array(
                'StagePromotion.id'    => $paramId,
                'StagePromotion.new_stage_id'    => $new_stage_id,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->StagePromotion->updateAll($fields, $conds)){

                $fields = array(
                    'StageHistory.is_trash'    => 1,
                );
                $conds = array(
                    'StageHistory.prisoner_id'    => $prisonList["Prisoner"]["id"],
                    'StageHistory.type'    => 'Stage Promotion',
                    'StageHistory.stage_id'    => $new_stage_id,
                );
                $this->StageHistory->updateAll($fields, $conds);
                if($this->auditLog('StagePromotion', 'stage_promotions', $uuid, 'Delete', json_encode(array($fields,$uuid))))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else 
                {
                    $db->rollback();
                    echo 'FAIL';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
      
	public function stagesDemotionAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageDemotion.prisoner_id'     => $prisoner_id,
                'StageDemotion.is_trash'        => 0,
                'StagePromotion.status'         => 'Draft'
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageDemotion.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StageDemotion');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     } 
     public function deleteStageDemotion()
     {
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'StageDemotion.is_trash'    => 1,
            );
            $conds = array(
                'StageDemotion.uuid'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->StageDemotion->updateAll($fields, $conds)){
                if($this->auditLog('StageDemotion', 'stage_demotions', $uuid, 'Delete', json_encode(array($fields,$uuid))))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else 
                {
                    $db->rollback();
                    echo 'FAIL';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
      public function stagesReinstatementAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageReinstatement.prisoner_id'     => $prisoner_id,
                'StageReinstatement.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','reinstatement_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','reinstatement_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }   
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageReinstatement.id'    => 'DESC',
                ),
                'limit'  => 1
            );
            $datas = $this->paginate('StageReinstatement');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     } 
     public function deleteStageReinstatement()
     {
        $this->autoRender = false;

        if(isset($this->params['named']['paramId'])){
            $uuid = $this->params['named']['stagesReinstatement_uuid'];
            $paramId = $this->params['named']['paramId'];
            $prisoner_id=$this->params['named']['prisoner_id'];

            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.id'     => $prisoner_id,
                ),
            ));
            $stage_reinstated_to=$this->params['named']['stage_reinstated_to'];
            $fields = array(
                'StageReinstatement.is_trash'    => 1,
            );
            $conds = array(
                'StageReinstatement.id'    => $paramId,
                'StageReinstatement.stage_reinstated_to'    => $stage_reinstated_to,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->StageReinstatement->updateAll($fields, $conds)){

                $stage_history_id=$this->StageHistory->find('first',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'StageHistory.prisoner_id'    => $prisonList["Prisoner"]["id"],
                        'StageHistory.type'    => 'Stage Reinstatement',
                        'StageHistory.stage_id'    => $stage_reinstated_to,
                    ),
                    'order'=>array(
                        'StageHistory.id DESC'
                    ),
                    'limit'=>1
                )); 
                $fields = array(
                    'StageHistory.is_trash'    => 1,
                );
                $conds = array(
                    'StageHistory.id'    => $stage_history_id["StageHistory"]["id"],
                    'StageHistory.prisoner_id'    => $prisonList["Prisoner"]["id"],
                    'StageHistory.type'    => 'Stage Reinstatement',
                    'StageHistory.stage_id'    => $stage_reinstated_to,
                );
                $this->StageHistory->updateAll($fields, $conds);
                if($this->auditLog('StageReinstatement', 'stage_reinstatements', $uuid, 'Delete', json_encode(array($fields,$uuid))))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else 
                {
                    $db->rollback();
                    echo 'FAIL';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
      //Stages History
      public function stagesHistoryAjax()
        {
            $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageHistory.prisoner_id'     => $prisoner_id,
                'StageHistory.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageHistory.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StageHistory');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
    } 
    
 }