<?php
App::uses('AppController', 'Controller');
class StagesController  extends AppController {
    public $layout='table';
    public $uses=array('Stage','StageAssign','StagePromotion','StageDemotion','StageReinstatement','Prisoner','StageHistory','ApprovalProcess', 'EarningRatePrisoner','EarningGradePrisoner', 'EarningRate');
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('stagesHistoryAjax');
    }

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
            'StageReinstatement.prison_id'      => $this->Session->read('Auth.User.prison_id'),
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
                $this->set('file_name','stagereinstatement'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','stagereinstatement'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','stagereinstatement'.date('d_m_Y').'.pdf');
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
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
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
                    //notification on approval of stage promotion --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Stage promotion list of prisoner are pending for review.";
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
                                "url_link"   => "/Stages/stagePromotionList",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Stage promotion list of prisoner are pending for approve";
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
                                "url_link"   => "/Stages/stagePromotionList",                    
                            ));
                        }
                    }

                    if(isset($items) && is_array($items) && count($items)>0 && $status=="Approved"){
                        foreach ($items as $itemskey => $itemsvalue) {
                            $fromStageId = $this->StagePromotion->field("old_stage_id",array("StagePromotion.id"=>$itemsvalue));
                            if($fromStageId!=Configure::read('STAGE-IV')){
                                if($this->setApprovalProcess($items, 'StagePromotion', "Final-Approved", $remark)){
                                    $this->addToStageHistory($items);
                                }
                            }else{
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                                {
                                    $notification_msg = "Stage promotion list of prisoner are pending for approve";
                                    $notifyUser = $this->User->find('first',array(
                                        'recursive'     => -1,
                                        'conditions'    => array(
                                            'User.usertype_id'    => Configure::read('COMMISSIONERGENERAL_USERTYPE'),
                                            'User.is_trash'     => 0,
                                            'User.is_enable'     => 1,
                                            // 'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                        )
                                    ));
                                    if(isset($notifyUser['User']['id']))
                                    {
                                        $this->addNotification(array(                        
                                            "user_id"   => $notifyUser['User']['id'],                        
                                            "content"   => $notification_msg,                        
                                            "url_link"   => "/Stages/stagePromotionList",                    
                                        ));
                                    }
                                }
                            }
                        }
                    }
                    if(isset($status) && $status=="Final-Approved"){
                        $this->addToStageHistory($items);
                    }
                    //notification on approval of stage promotion --END--
                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                            $this->Session->write('message','Reviewed Successfully !');}
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Final-Rejected")){
                            $this->Session->write('message','Rejected Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
                            $this->Session->write('message','Approved Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Final-Approved"){
                            $this->Session->write('message','Approved Successfully !');
                        }
                    }else{
                        $this->Session->write('message','Forwarded Successfully !');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                    exit;
                }
            }
        }
        $prisonerListData = $this->StagePromotion->find('list', array(
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'left',
                    'conditions'=> array('StagePromotion.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'StagePromotion.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'StagePromotion.prison_id' => $this->Auth->user('prison_id')
            ),
            'order'         => array(
                'StagePromotion.prisoner_id'
            ),
        ));
        $default_status = '';
        $statusList = '';


        if($this->Session->read('Auth.User.usertype_id')==Configure::read("RECEPTIONIST_USERTYPE"))
        {    
            $default_status = 'Draft';
        } 
        if($this->Session->read('Auth.User.usertype_id')==Configure::read("PRINCIPALOFFICER_USERTYPE"))
        {
            $default_status = 'Saved';
        }
        if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE"))
        {
            $default_status = 'Reviewed';
        }

        if($this->Session->read('Auth.User.usertype_id')==Configure::read("COMMISSIONERGENERAL_USERTYPE"))
        {
            $default_status = 'Approved';
        }

        $statusInfo = array(
            'default_status' => $default_status,
            'statusList' => array(
                'Final-Approved'        => 'Final-Approved',
                'Final-Rejected'        => 'Final-Rejected',
                'Approved'              => 'Approved',
                'Approve-Rejected'      => 'Approve-Rejected',
                'Draft'                 => 'Draft',
                'Reviewed'              => 'Reviewed',
                'Review-Rejected'       => 'Review-Rejected',
                'Saved'                 => 'Forwarded'
            )
        );
        
        
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        
        
        $this->set(array(
            'prisonerListData'      => $prisonerListData,
            'sttusListData'         => $statusList,
            'default_status'        => $default_status,
            'statusInfo'            => $statusInfo,
        ));
    }
    public function stagepromotionListAjax(){
        $this->layout           = 'ajax';
        $prisoner_id    = '';
        $condition              = array(
            'StagePromotion.is_trash'      => 0,
        );
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition              += array(
                    'StagePromotion.prison_id'      => $this->Session->read('Auth.User.prison_id'),
                );
            } 
         if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'StagePromotion.status'   => $status,
            );
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition      += array('StagePromotion.old_stage_id'=>Configure::read('STAGE-IV'));
            }  
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
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition      += array('StagePromotion.status !='=>'Draft');
                $condition      += array('StagePromotion.status !='=>'Saved');
                $condition      += array('StagePromotion.status !='=>'Review-Rejected');
                $condition      += array('StagePromotion.status !='=>'Final-Rejected');
                $condition      += array('StagePromotion.status'=>'Approved');
                $condition      += array('StagePromotion.old_stage_id'=>Configure::read('STAGE-IV'));
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

        $statusInfo = array(
            'statusList' => array(
                'Final-Approved'        => 'Final-Approved',
                'Final-Rejected'        => 'Final-Rejected',
                'Approved'              => 'Approved',
                'Approve-Rejected'      => 'Approve-Rejected',
                'Draft'                 => 'Draft',
                'Reviewed'              => 'Reviewed',
                'Review-Rejected'       => 'Review-Rejected',
                'Saved'                 => 'Forwarded'
            )
        );

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
            'statusInfo'                => $statusInfo,
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
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','discharge_report_'.date('d_m_Y').'.pdf');
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
        $dataArr = array();
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
            
            if(isset($this->request->data['StageAssign']) && is_array($this->request->data['StageAssign']) && count($this->request->data['StageAssign'])>0)
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
                $dataArr['StageHistory']['next_date_of_stage']=date('Y-m-d',strtotime("+3 months", strtotime($this->data['StageAssign']['date_of_assign'])));
                
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
             $this->request->data['StagePromotion']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                
             $db = ConnectionManager::getDataSource('default');
             $db->begin(); 
             // debug($dataArr);
             if($this->StagePromotion->save($this->request->data))
             {

                $refId = 0;
                $action = 'Add';
                if(isset($this->request->data['StagePromotion']['id']) && (int)$this->request->data['StagePromotion']['id'] != 0)
                {
                    $refId = $this->request->data['StagePromotion']['id'];
                    $action = 'Edit';
                }

                if(false)//!$this->multipleAuditLog(array(0=>'StagePromotion'), array(0=>'stage_promotions'), array(0=>$refId), array(0=>$action), array(json_encode($this->data)))
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
             $dataArr['StageHistory']['next_date_of_stage']=date('Y-m-d',strtotime("+".$this->getName($this->data['StageDemotion']['new_stage_id'],"Stage","maximum_duration")." months", strtotime($this->data['StageDemotion']['demotion_date'])));
                
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
                $this->request->data['StageReinstatement']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->StageReinstatement->save($this->request->data))
                {
                    // $this->StageHistory->save($dataArr);
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
                    'probationary_period_list'=>$probationary_period_list,
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
                }else if($this->params['named']['reqType']=='PDF'){
					$this->layout='pdf';
					$this->set('file_type','pdf');
					$this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.pdf');
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
                }else if($this->params['named']['reqType']=='PDF'){
					$this->layout='pdf';
					$this->set('file_type','pdf');
					$this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.pdf');
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
                }else if($this->params['named']['reqType']=='PDF'){
					$this->layout='pdf';
					$this->set('file_type','pdf');
					$this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.pdf');
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
                }else if($this->params['named']['reqType']=='PDF'){
					$this->layout='pdf';
					$this->set('file_type','pdf');
					$this->set('file_name','reinstatement_report_'.date('d_m_Y').'.pdf');
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
                }else if($this->params['named']['reqType']=='PDF'){
					$this->layout='pdf';
					$this->set('file_type','pdf');
					$this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.pdf');
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

    public function addToStageHistory($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); 
            $i = 0; 
            $j = 0; 
            $prisonerGradeData = array();
            $prisonerRateData = array();
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $stagePromotionData = $this->StagePromotion->find('first', array(
                    'recursive'  => 0,
                    'conditions' => array('StagePromotion.id' => $fid)
                ));
                //echo '<pre>'; print_r($creditdata); exit;
                $insertdata[$i]['StageHistory']['prisoner_id'] = $stagePromotionData['StagePromotion']['prisoner_id'];
                $insertdata[$i]['StageHistory']['stage_id'] = $stagePromotionData['StagePromotion']['new_stage_id'];

                //auto assign to grade A for special stage prisoners 
                if($insertdata[$i]['StageHistory']['stage_id'] == Configure::read('SPECIAL-STAGE'))
                {
                    //update prisoner grade details
                    $prisonerGradeData[$j]['EarningGradePrisoner']['assignment_date']=date('Y-m-d');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_id']=$stagePromotionData['StagePromotion']['prisoner_id'];
                    $prisonerGradeData[$j]['EarningGradePrisoner']['grade_id']=Configure::read('GRADE-A');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_stage_id']=Configure::read('SPECIAL-STAGE');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['status']='Approved';
                    // prisoner rate data ============
                    $prisonerRateData[$j]['EarningRatePrisoner']['date_of_assignment']=date('Y-m-d');
                    $prisonerRateData[$j]['EarningRatePrisoner']['prisoner_id']=$stagePromotionData['StagePromotion']['prisoner_id'];
                    $prisonerRateData[$j]['EarningRatePrisoner']['earning_rate_id']=$this->EarningRate->field("id",array("EarningRate.earning_grade_id"=>$prisonerGradeData[$j]['EarningGradePrisoner']['grade_id']));
                    
                }
                // echo $insertdata[$i]['StageHistory']['stage_id']."--".Configure::read('STAGE-IV');
                if($insertdata[$i]['StageHistory']['stage_id'] == Configure::read('STAGE-III') || $insertdata[$i]['StageHistory']['stage_id'] == Configure::read('STAGE-IV'))
                {
                    //update prisoner grade details
                    $prisonerGradeData[$j]['EarningGradePrisoner']['assignment_date']=date('Y-m-d');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_id']=$stagePromotionData['StagePromotion']['prisoner_id'];
                    $prisonerGradeData[$j]['EarningGradePrisoner']['grade_id']=Configure::read('GRADE-B');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_stage_id']=$insertdata[$i]['StageHistory']['stage_id'];
                    $prisonerGradeData[$j]['EarningGradePrisoner']['status']='Approved';
                    
                    // prisoner rate data ============
                    $prisonerRateData[$j]['EarningRatePrisoner']['date_of_assignment']=date('Y-m-d');
                    $prisonerRateData[$j]['EarningRatePrisoner']['prisoner_id']=$stagePromotionData['StagePromotion']['prisoner_id'];
                    $prisonerRateData[$j]['EarningRatePrisoner']['earning_rate_id']=$this->EarningRate->field("id",array("EarningRate.earning_grade_id"=>$prisonerGradeData[$j]['EarningGradePrisoner']['grade_id']));

                    // debug($prisonerGradeData[$j]);exit;
                }

                $insertdata[$i]['StageHistory']['type']="Stage Promotion";
                $insertdata[$i]['StageHistory']['date_of_stage']=$stagePromotionData['StagePromotion']['promotion_date'];
                // logic for UR 48, getting next promotion date on the basis of stage and offence
                // $punishmentData = $this->InPrisonPunishment->find('count', array(
                //     'recursive'  => 0,
                //     'conditions' => array(
                //         'InPrisonPunishment.prisoner_id' => $stagePromotionData['StagePromotion']['prisoner_id'],
                //         'InPrisonPunishment.is_trash' => 0,
                //         'InPrisonPunishment.status' => 'Approved',
                //     ),
                // ));
                // [UR 48]
                // $promotionStage = array(
                //     2  => array(
                //         "normal"    => 1,
                //         "offence"    => 3,
                //     ),
                //     3  => array(
                //         "normal"    => 3,
                //         "offence"    => 15,
                //     ),
                //     4  => array(
                //         "normal"    => 6,
                //         "offence"    => 18,
                //     ),
                // );
                // if($punishmentData > 0){
                //     $promotionMonth = (isset($stagePromotionData['StagePromotion']['new_stage_id'])) ? $promotionStage[$stagePromotionData['StagePromotion']['new_stage_id']]['offence'] : 0;
                // }else{
                //     $promotionMonth = (isset($stagePromotionData['StagePromotion']['new_stage_id'])) ? $promotionStage[$stagePromotionData['StagePromotion']['new_stage_id']]['normal'] : 0;
                // }
                // ==============================================================
                $promotionMonth = $this->Stage->field("maximum_duration",array("Stage.id"=>$stagePromotionData['StagePromotion']['new_stage_id']));
                $insertdata[$i]['StageHistory']['next_date_of_stage']=($promotionMonth!='') ? date('Y-m-d',strtotime("+".$promotionMonth." months", strtotime($stagePromotionData['StagePromotion']['promotion_date']))) : '';
                $db = ConnectionManager::getDataSource('default');
                $db->begin();

                if ($this->StageHistory->saveAll($insertdata[$i])) {
                    // echo $this->StageHistory->id;
                    $this->auditLog('StageHistory', 'stage_histories', '', 'Insert', json_encode($insertdata[$i]['StageHistory']));
                    // debug($prisonerGradeData[$j]['EarningGradePrisoner']);exit;
                    if(isset($prisonerGradeData[$j]['EarningGradePrisoner']) && is_array($prisonerGradeData[$j]['EarningGradePrisoner']) && count($prisonerGradeData[$j]['EarningGradePrisoner'])>0)
                    {
                        if($this->EarningGradePrisoner->saveAll($prisonerGradeData[$j]['EarningGradePrisoner'])){
                            $this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', '', 'Insert', json_encode($prisonerGradeData[$j]['EarningGradePrisoner']));
                            if($this->EarningRatePrisoner->saveAll($prisonerRateData[$j]['EarningRatePrisoner'])){
                                $this->auditLog('EarningRatePrisoner', 'earning_rate_prisoners', '', 'Insert', json_encode($prisonerGradeData[$j]['EarningGradePrisoner']));
                                $prisonerData['Prisoner']['id'] = $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_id'];
                                $prisonerData['Prisoner']['earning_rate_id'] = $prisonerRateData[$j]['EarningRatePrisoner']['earning_rate_id'];
                                $prisonerData['Prisoner']['earning_grade_id'] = $prisonerGradeData[$j]['EarningGradePrisoner']['grade_id'];
                                if($this->Prisoner->saveAll($prisonerData)){
                                    if($this->auditLog('Prisoner', 'prisoners', '', 'Update', json_encode($prisonerData)))
                                    {
                                        $db->commit();
                                    }else{
                                        $db->rollback();
                                    }
                                }else{
                                    $db->rollback();
                                }
                            }else{
                                $db->rollback();
                            }
                        }else{
                            $db->rollback();
                        }
                        
                        $j++;
                    }else{
                        $db->commit();
                    }
                }else{
                    // debug($insertdata[$i]['StageHistory']);exit;
                    $db->rollback();
                }
                $i++;
            }
            return true;
            
        }
        else 
        {
            return false;
        }
    }

    public function checkStagePromotion($prisoner_id, $current_stage_id){
        $this->loadModel("Prisoner");
        $this->loadModel("StageHistory");
        $this->loadModel("StagePromotion");
        $button = false;
        $dataResult = array("message"=>"","button"=>false);
        // check the prisoner is promoted or not
        $stagePromotionlast=$this->StagePromotion->find('first',array(
            'recursive'     => -1,
            'conditions'    => array(
                'StagePromotion.prisoner_id'    => $prisoner_id,
                //'StageHistory.type'    => 'Stage Promotion',
                'StagePromotion.is_trash'    =>0,
            ),
            'order'=>array(
                'StagePromotion.id'=>'DESC',
            ),
        ));
        if(isset($stagePromotionlast) && count($stagePromotionlast)==0){
            $dataResult['button'] = true;
        }
        // check the last stage history record
        $stagehistorylast=$this->StageHistory->find('first',array(
            'conditions'    => array(
                'StageHistory.prisoner_id'    => $prisoner_id,
            ),
            'order'=>array(
                'StageHistory.id'=>'DESC',
            ),
        ));

        if(isset($stagehistorylast['StageHistory']['next_date_of_stage']) && $stagehistorylast['StageHistory']['next_date_of_stage']!=''){
            // start displaying button befor 3 days from promotion
            if(strtotime(date("Y-m-d")) > strtotime("-3 days",strtotime($stagehistorylast['StageHistory']['next_date_of_stage']))){
                $dataResult['button'] = true;
            }else{
                $dataResult['button'] = false;
            }
            // debug($dataResult);exit;

            $diff=date_diff(date_create(date("Y-m-d")), date_create($stagehistorylast['StageHistory']['next_date_of_stage']));

            // start the logic of checking the last period for offence
            $this->loadModel("Stage");
            $minimumDuration = $this->Stage->field("minimum_duration", array("Stage.id"=>$stagehistorylast['StageHistory']['stage_id']));
            $maximumDuration = $this->Stage->field("maximum_duration", array("Stage.id"=>$stagehistorylast['StageHistory']['stage_id']));

            $lastCheckingDate = ($stagehistorylast['StageHistory']['type']!='Stage Demotion') ? date("Y-m-d",strtotime($stagehistorylast['StageHistory']['next_date_of_stage']." -".$minimumDuration." Months")) : date("Y-m-d",strtotime($stagehistorylast['StageHistory']['next_date_of_stage']." -".$maximumDuration." Months"));

            $lastPeriod = '';
            $this->loadModel('InPrisonPunishment');
            $diciplineData = $this->InPrisonPunishment->find("first", array(
                    "conditions"    => array(
                        "InPrisonPunishment.prisoner_id"    => $stagehistorylast['StageHistory']['prisoner_id'],
                        "InPrisonPunishment.status"    => "Final-Approved",
                        "InPrisonPunishment.punishment_date between ? and ?"    => array($lastCheckingDate, $stagehistorylast['StageHistory']['next_date_of_stage']),
                    ),
                    "order"         => array(
                        "InPrisonPunishment.id" => "desc",
                    ),
                ));
            $dataResult['message'] = ($stagehistorylast['StageHistory']['next_date_of_stage']!='') ? "Next promotion date is ".date("d-m-Y", strtotime($stagehistorylast['StageHistory']['next_date_of_stage'])): '';
            // debug($diciplineData);
            if(isset($diciplineData) && is_array($diciplineData) && count($diciplineData)>0){
                //strtotime($stagehistorylast['StageHistory']['next_date_of_stage']." +".$minimumDuration." Months") < strtotime(date("Y-m-d"))
                $nextPromotionAfterPunishment = ($stagehistorylast['StageHistory']['type']!='Stage Demotion') ? date("d-m-Y",strtotime($diciplineData['InPrisonPunishment']['punishment_date']." +".$minimumDuration." Months")) : date("d-m-Y",strtotime($diciplineData['InPrisonPunishment']['punishment_date']." +".$maximumDuration." Months"));
                if(strtotime($diciplineData['InPrisonPunishment']['punishment_date']." +".$minimumDuration." Months") > strtotime(date("Y-m-d"))){
                    $dataResult['button'] = false;
                    $dataResult['message'] = "<span style='color:red;'><br>This prisoner is involve in internal offence(".$this->getName($diciplineData['InPrisonPunishment']['disciplinary_proceeding_id'],"DisciplinaryProceeding","offence_type").") : ".$this->getName($this->getName($diciplineData['InPrisonPunishment']['disciplinary_proceeding_id'],"DisciplinaryProceeding","internal_offence_id"),"InternalOffence","name").", dated : ".date("d-m-Y", strtotime($this->getName($diciplineData['InPrisonPunishment']['disciplinary_proceeding_id'],"DisciplinaryProceeding","offence_date"))).". So next promotion date will be ".$nextPromotionAfterPunishment."</span>";
                }else{
                    $dataResult['button'] = true;
                    $dataResult['message'] = ($stagehistorylast['StageHistory']['next_date_of_stage']!='') ? "Next promotion date is ".$nextPromotionAfterPunishment: '';
                }
                
                
            }
        }else{
            $dataResult['button'] = false;
        }

        if(isset($stagePromotionlast['StagePromotion']['status']) && $stagePromotionlast['StagePromotion']['status']!='Final-Approved'){
            $dataResult['button'] = false;
        }
        return $dataResult;
    }    

    public function listOutStage(){
                $this->loadModel('Prison');
        $this->loadModel('Gender');
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }else{
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));

    }
    public function listOutStageAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        ini_set('memory_limit', '-1');
        $condition      = array( 'Prisoner.is_trash'=> 0,);

    if($this->Session->read('Auth.User.prison_id')!=''){
        $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
    }else{
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
    }
    
    if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
        $prisoner_name = $this->params['named']['prisoner_name'];
        $condition += array("Prisoner.first_name like '%".strtolower($prisoner_name)."%'");
    }
        
        if(isset($this->params['named']['epd_from']) && $this->params['named']['epd_from'] != ''){
          $epd_from = date('Y-m-d',strtotime($this->params['named']['epd_from']));
          $condition += array('Prisoner.epd >= ' => $epd_from );
      }
      
        if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
          $epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
          $condition += array('Prisoner.epd <= ' => $epd_to);
      }

      if(isset($this->params['named']['lpd_from']) && $this->params['named']['lpd_from'] != ''){
          $lpd_from = date('Y-m-d',strtotime($this->params['named']['lpd_from']));
          $condition += array('Prisoner.lpd >= ' => $lpd_from);
      }
      
      if(isset($this->params['named']['lpd_to']) && $this->params['named']['lpd_to'] != ''){
          $lpd_to = date('Y-m-d',strtotime($this->params['named']['lpd_to']));
          $condition += array('Prisoner.lpd >= ' => $lpd_to);
      }
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_outof_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_outof_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_outof_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => array(
          'Prisoner.is_trash'         => 0,
          'Prisoner.prisoner_type_id'         => Configure::read('CONVICTED'),
          // 'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
          'Prisoner.present_status'        => 1,
          'Prisoner.is_approve'        => 1,
          'Prisoner.transfer_status !='        => 'Approved'
        )+$condition,
            'order'         => array(
                'Prisoner.prison_id'    => 'ASC',
                'Prisoner.state_id' => 'ASC',
                'Prisoner.country_id' => 'ASC',
                'Prisoner.prisoner_type_id' => 'ASC',
                'Prisoner.prisoner_sub_type_id' => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prisoner');
        
          $this->set(array(
          'datas'          => $datas,
          
      ));

    }
 }