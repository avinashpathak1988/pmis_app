<?php
App::uses('AppController', 'Controller');
class MedicalRecordsController  extends AppController {
	public $layout='table';
	public $uses=array('Prisoner','MedicalSickRecord', 'Disease', 'Hospital', 'MedicalSeriousIllRecord', 'MedicalDeathRecord','MedicalCheckupRecord','User','ApprovalProcess','Height');	
	public function index() {
		$this->loadModel('MedicalRecord'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        /*if(isset($this->data['PrisonercomplaintDelete']['id']) && (int)$this->data['PrisonercomplaintDelete']['id'] != 0){
        	
                 $this->MedicalRecord->id=$this->data['PrisonercomplaintDelete']['id'];
                 $this->MedicalRecord->saveField('is_trash',1);
        		 
			     $this->Session->write('message_type','success');
			     $this->Session->write('message','Deleted Successfully !');
			     $this->redirect(array('action'=>'index'));
        	
        }*/
    }
   public function getPrisnerInfo(){
   		$this->autoRender = false;
   		$this->loadModel('Prisoner'); 
   		$prisoner_id = $this->request->data['prisoner_id'];
   		$prisonerListData = $this->Prisoner->find('first', array(
				'conditions'	=> array(
					'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
					'Prisoner.id'        => $prisoner_id
				),
			));
   		$prisoner_name=$prisonerListData["Prisoner"]["fullname"];
   		$gender_id=$prisonerListData["Prisoner"]["gender_id"];
   		if($gender_id==2){$gender="Female";}
   		else if($gender_id==1){$gender="Male";}
   		$age=$prisonerListData["Prisoner"]["age"];
   		$height_feet=$prisonerListData["Prisoner"]["height_feet"];
   		$height_inch=$prisonerListData["Prisoner"]["height_inch"];
   		echo json_encode(array("prisoner_name"=>$prisoner_name,"gender"=>$gender,"age"=>$age,"height_feet"=>$height_feet,"height_inch"=>$height_inch));
   }
   public function getCheckupPrisnerInfo(){
   		$this->layout = 'ajax';
   		$this->loadModel('Prisoner'); 
   		$this->loadModel('MedicalCheckupRecord');
   		$check_up = $this->request->data['check_up'];
   		//If checkup type Initial
   		if($check_up=="Intial"){
   			$prisonerListData = $this->MedicalCheckupRecord->find('list', array(
	   				'recursive'=>-1,
					'fields'		=> array(
						'MedicalCheckupRecord.prisoner_id',
					),
					'group'	=> array(
						"MedicalCheckupRecord.prisoner_id" 
					)
			));
			$prisnor_ids = '';
			if(!empty($prisonerListData) && count($prisonerListData) > 0)
			{
				$prisnor_ids = implode(',',$prisonerListData);
			}
			$prisonerConditions = array(
				'Prisoner.is_trash'		=> 0,
				'Prisoner.present_status'		=> 1
			);
			if($prisnor_ids != ''){
				$prisonerConditions += array(
					"Prisoner.id NOT IN (".$prisnor_ids.")" 
				);
			}
	   		$prisonerListData1 = $this->Prisoner->find('list', array(
	   				'fields'		=> array(
						'Prisoner.id',
						'Prisoner.prisoner_no',
					),
					'conditions'	=> $prisonerConditions
				));
   		}
   		//If checkup type Exit
   		if($check_up=="Exit"){

   			$prisonerListDataexit = $this->MedicalCheckupRecord->find('list', array(
   				'recursive'=>-1,
   				'fields' => array(
   					'MedicalCheckupRecord.prisoner_id'
   				),
   				'conditions'	=> array(
					"MedicalCheckupRecord.check_up"=>'Exit', 
				),
				'group'	=> array(
					"MedicalCheckupRecord.prisoner_id" 
				)
			));
		
			$prisnor_idexits = '';
			if(!empty($prisonerListDataexit) && count($prisonerListDataexit) > 0)
			{
				$prisnor_idexits = implode(',',$prisonerListDataexit);
			}
			$prisonerConditions = array(
				'Prisoner.is_trash'		=> 0,
				'Prisoner.present_status'		=> 1,
				'MedicalCheckupRecord.status in ("Approved", "Review-Rejected", "Approve-Rejected")'
			);
			if($prisnor_idexits != ''){
				$prisonerConditions += array(
					"Prisoner.id NOT IN (".$prisnor_idexits.")" 
				);
			}
			$prisonerListData1 = $this->MedicalCheckupRecord->find('list', array(
				'recursive'=>-1,
				'fields'		=> array(
					'Prisoner.id',
					'Prisoner.prisoner_no',
				),
				'joins' => array(
	                array(
	                'table' => 'prisoners',
	                'alias' => 'Prisoner',
	                'type' => 'inner',
	                'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
	                ),
	            ),
				'conditions'	=> $prisonerConditions
			));
   		}
   		$this->set(array(
        	'prisonerListData1'		=> $prisonerListData1,
        	'check_up'=>$check_up,
        ));
   }
   public function approvalmedicalrecord(){
   		$this->autoRender = false;
   		
   	
   }
	public function add($uuid='') { 
		if(!isset($uuid)){$uuid="";}
		$uuidParam="";
		$isEdit=0;
		$prisonerListData = $this->Prisoner->find('list', array(
				'fields'		=> array(
					'Prisoner.id',
					'Prisoner.prisoner_no',
				),
				'conditions'	=> array(
					'Prisoner.is_trash'		=> 0,
					'Prisoner.present_status'		=> 1,
					'Prisoner.prison_id'        => $this->Auth->user('prison_id')
				),
		));
		$medicalOfficerListData = $this->User->find('list', array(
				'fields'		=> array(
					'User.id',
					'User.name',
				),
				'conditions'	=> array(
					'User.is_trash'		=> 0,
					'User.usertype_id'        => Configure::read('MEDICALOFFICE_USERTYPE')
				),
		));
		$attendence_description_search=array("New Attendence"=>"New Attendence","Re-Attendence"=>"Re-Attendence");
		//get height in feet list 
                $heightInFeetList = $this->Height->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Height.id',
                        'Height.name',
                    ),
                    'conditions'    => array(
                        'Height.is_enable'      => 1,
                        'Height.is_trash'       => 0,
                        'Height.height_type'    => 'Feet',
                    ),
                    'order'         => array(
                        'Height.name'
                    ),
                ));
                //get height in inches list 
                $heightInInchList = $this->Height->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Height.id',
                        'Height.name',
                    ),
                    'conditions'    => array(
                        'Height.is_enable'      => 1,
                        'Height.is_trash'       => 0,
                        'Height.height_type'    => 'Inch',
                    ),
                    'order'         => array(
                        'Height.name'
                    ),
                ));
		$priorityList=array("High"=>"High","Medium"=>"Medium","Low"=>"Low");
		// $recomendationcategoryList=array(""=>"");
		$default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo('Medical');
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
		$tbList = array("Nagetive"=>"-ve","Positve"=>"+ve");
		$mentalcaseList=array("No"=>"No","Yes"=>"Yes");
		$checkupData=array("Intial"=>"Intial","Exit"=>"Exit");
		$death_placeList=array("In"=>"In","Out"=>"Out");
		$attendanceList=array("New Attendence"=>"New Attendence","Re-Attendence"=>"Re-Attendence");
				/*
				 *Code start for insert and update the data of medical check up records
				 */


		$status = 'Saved'; 
        $remark = '';
        
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                $modelname=$this->request->data['ApprovalProcessForm']['modelname'];

                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, $modelname, $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')))
	                {
	                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
	                    {
	                        if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
	                        if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
	                        if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
	                        
	                    }
	                }
                    else{
                        $this->Session->write('message','Saved Successfully !');
                    }
                    //$this->redirect('/medicalRecords/add#health_checkup');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        	if(isset($this->data['MedicalCheckupRecord']) && is_array($this->data['MedicalCheckupRecord']) && count($this->data['MedicalCheckupRecord'])>0 && $this->data['MedicalCheckupRecord']['id']!=""){
					
					$this->request->data['MedicalCheckupRecord']['medical_officer_id']=$this->Auth->user('id');

					if(isset($this->data['MedicalCheckupRecord']['uuid']) && $this->data['MedicalCheckupRecord']['uuid'] == ''){
						$uuidArr = $this->MedicalCheckupRecord->query("select uuid() as code");
						$this->request->data['MedicalCheckupRecord']['uuid'] = $uuidArr[0][0]['code'];	
						}
						if(isset($this->data['MedicalCheckupRecord']['follow_up']) && $this->data['MedicalCheckupRecord']['follow_up'] != ''){

							$follow_up = $this->request->data['MedicalCheckupRecord']['follow_up'];
							$parts = explode('-',$follow_up);
							$follow_up = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
							$this->request->data['MedicalCheckupRecord']['follow_up'] = $follow_up;
						}	
						$db = ConnectionManager::getDataSource('default');
	            		$db->begin(); 			
						if($this->MedicalCheckupRecord->save($this->data)){

							$refId = 0;
							$action = 'Add';
							if(isset($this->data['MedicalCheckupRecord']['id']) && (int)$this->data['MedicalCheckupRecord']['id'] != 0)
							{
								$refId = $this->data['MedicalCheckupRecord']['id'];
								$action = 'Edit';
							}
							if($this->auditLog('MedicalCheckupRecord', 'medical_checkup_records', $refId, $action, json_encode($this->data)))
							{
		                        $db->commit(); 
		                        $this->Session->write('message_type','success');
			                    $this->Session->write('message','Saved Successfully !');	
								$this->redirect('/medicalRecords/add#health_checkup');                       
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
				if(isset($this->data['MedicalCheckupRecord']) && is_array($this->data['MedicalCheckupRecord']) && count($this->data['MedicalCheckupRecord'])>0 && $this->data['MedicalCheckupRecord']['id']==""){	
					$this->request->data['MedicalCheckupRecord']['medical_officer_id']=$this->Auth->user('id');
					$check_up=$this->data['MedicalCheckupRecord']['check_up'];
					$prisoner_id=$this->data['MedicalCheckupRecord']['prisoner_id'];
					$medicalcheckListData = $this->MedicalCheckupRecord->find('first', array(
						'conditions'	=> array(
							'MedicalCheckupRecord.prisoner_id'=> $prisoner_id,
							'MedicalCheckupRecord.check_up'        => $check_up
						),
					));
					
					if(count($medicalcheckListData)==0){
						if(isset($this->data['MedicalCheckupRecord']['uuid']) && $this->data['MedicalCheckupRecord']['uuid'] == ''){
						$uuidArr = $this->MedicalCheckupRecord->query("select uuid() as code");
						$this->request->data['MedicalCheckupRecord']['uuid'] = $uuidArr[0][0]['code'];	
						}
						if(isset($this->data['MedicalCheckupRecord']['follow_up']) && $this->data['MedicalCheckupRecord']['follow_up'] != ''){
							$follow_up = $this->request->data['MedicalCheckupRecord']['follow_up'];
							$parts = explode('-',$follow_up);
							$follow_up = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
							$this->request->data['MedicalCheckupRecord']['follow_up'] = $follow_up;
						}	
						$db = ConnectionManager::getDataSource('default');
	            		$db->begin(); 			
						if($this->MedicalCheckupRecord->save($this->data)){

							$refId = 0;
							$action = 'Add';
							if(isset($this->data['MedicalCheckupRecord']['id']) && (int)$this->data['MedicalCheckupRecord']['id'] != 0)
							{
								$refId = $this->data['MedicalCheckupRecord']['id'];
								$action = 'Edit';
							}
							if($this->auditLog('MedicalCheckupRecord', 'medical_checkup_records', $refId, $action, json_encode($this->data)))
							{
		                        $db->commit(); 
		                        $this->Session->write('message_type','success');
			                    $this->Session->write('message','Saved Successfully !');	
								$this->redirect('/medicalRecords/add#health_checkup');                       
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
					else{
						$this->Session->write('message_type','error');
			            $this->Session->write('message','Saving Failed !'.$check_up.' check up already exist.');
					}
					
				}
				/*
				 *Code for edit the medical medical check up records
				*/
				$prisonerListData1=array();
				if(!isset($this->request->data['ApprovalProcess'])){
		        if(isset($this->data['MedicalCheckupRecordEdit']['id']) && (int)$this->data['MedicalCheckupRecordEdit']['id'] != 0){
		            if($this->MedicalCheckupRecord->exists($this->data['MedicalCheckupRecordEdit']['id'])){
		            	$isEdit = 1;
		                $this->data = $this->MedicalCheckupRecord->findById($this->data['MedicalCheckupRecordEdit']['id']);


		                if($this->data["MedicalCheckupRecord"]["check_up"]=="Intial"){
					   			$prisonerListData = $this->MedicalCheckupRecord->find('list', array(
						   				'recursive'=>-1,
										'fields'		=> array(
											'MedicalCheckupRecord.prisoner_id',
										),
										'conditions'	=> array(
											"MedicalCheckupRecord.check_up"=>'Exit', 
										),
										'group'	=> array(
											"MedicalCheckupRecord.prisoner_id" 
										)
								));
								$prisnor_ids = '';
								if(!empty($prisonerListData) && count($prisonerListData) > 0)
								{
									$prisnor_ids = implode(',',$prisonerListData);
								}
								$prisonerConditions = array(
									'Prisoner.is_trash'		=> 0,
									'Prisoner.present_status'		=> 1
								);
								if($prisnor_ids != ''){
									$prisonerConditions += array(
										"Prisoner.id NOT IN (".$prisnor_ids.")" 
									);
								}
						   		$prisonerListData1 = $this->Prisoner->find('list', array(
						   				'fields'		=> array(
											'Prisoner.id',
											'Prisoner.prisoner_no',
										),
										'conditions'	=> $prisonerConditions
									));
					   		}
					   		if($this->data["MedicalCheckupRecord"]["check_up"]=="Exit"){
					   			$prisonerListDataexit = $this->MedicalCheckupRecord->find('list', array(
					   				'recursive'=>-1,
					   				'fields' => array(
					   					'MedicalCheckupRecord.prisoner_id'
					   				),
					   				'conditions'	=> array(
										"MedicalCheckupRecord.check_up"=>'Exit', 
									),
									'group'	=> array(
										"MedicalCheckupRecord.prisoner_id" 
									)
								));
							
								$prisnor_idexits = '';
								if(!empty($prisonerListDataexit) && count($prisonerListDataexit) > 0)
								{
									$prisnor_idexits = implode(',',$prisonerListDataexit);
								}
								$prisonerConditions = array(
									'Prisoner.is_trash'		=> 0,
									'Prisoner.present_status'		=> 1,
									'MedicalCheckupRecord.status in ("Approved", "Review-Rejected", "Approve-Rejected")'
								);
								if($prisnor_idexits != ''){
									$prisonerConditions += array(
										"Prisoner.id NOT IN (".$prisnor_idexits.")" 
									);
								}
								$prisonerListData1 = $this->MedicalCheckupRecord->find('list', array(
									'recursive'=>-1,
									'fields'		=> array(
										'Prisoner.id',
										'Prisoner.prisoner_no',
									),
									'joins' => array(
						                array(
						                'table' => 'prisoners',
						                'alias' => 'Prisoner',
						                'type' => 'inner',
						                'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
						                ),
						            ),
									'conditions'	=> $prisonerConditions
								));
							
							
					   			
						   		
						   		
					   		}
		            }
		        }
		    	}
				$this->set(array(
					'prisonerListData1'		=> $prisonerListData1,
					
				));
		         /*
		         *Code for delete the medical medical check up records
		         */	
		         if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalCheckupDelete']['id']) && (int)$this->data['MedicalCheckupDelete']['id'] != 0){
			            if($this->MedicalCheckupRecord->exists($this->data['MedicalCheckupDelete']['id'])){
		                    $this->MedicalCheckupRecord->id = $this->data['MedicalCheckupDelete']['id'];
		                    if($this->MedicalCheckupRecord->saveField('is_trash',1)){
								$this->Session->write('message_type','success');
			                    $this->Session->write('message','Deleted Successfully !');
		                    }else{
								$this->Session->write('message_type','error');
			                    $this->Session->write('message','Delete Failed !');
		                    }
		                    $this->redirect('/medicalRecords/add#health_checkup');		                
			            }
			        }
		    	}
				/*
				 *Code start for insert and update the data of medical sick records
				 */
				if(isset($this->data['MedicalSickRecord']) && is_array($this->data['MedicalSickRecord']) && count($this->data['MedicalSickRecord'])>0){	
					$this->request->data['MedicalSickRecord']['medical_officer_id']=$this->Auth->user('id');
					if(isset($this->data['MedicalSickRecord']['uuid']) && $this->data['MedicalSickRecord']['uuid'] == ''){
						$uuidArr = $this->MedicalSickRecord->query("select uuid() as code");
						$this->request->data['MedicalSickRecord']['uuid'] = $uuidArr[0][0]['code'];	
					}
					if(isset($this->data['MedicalSickRecord']['check_up_date']) && $this->data['MedicalSickRecord']['check_up_date'] != ''){
						$check_up_date = $this->request->data['MedicalSickRecord']['check_up_date'];
						$parts = explode('-',$check_up_date);
						$check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						$this->request->data['MedicalSickRecord']['check_up_date'] = $check_up_date;
					}	
					$db = ConnectionManager::getDataSource('default');
            		$db->begin();			
					if($this->MedicalSickRecord->save($this->data)){
						$refId = 0;
						$action = 'Add';
						if(isset($this->data['MedicalSickRecord']['id']) && (int)$this->data['MedicalSickRecord']['id'] != 0)
						{
							$refId = $this->data['MedicalSickRecord']['id'];
							$action = 'Edit';
						}
						if($this->auditLog('MedicalSickRecord', 'medical_sick_records', $refId, $action, json_encode($this->data)))
						{
	                        $db->commit(); 
	                        $this->Session->write('message_type','success');
		                    $this->Session->write('message','Saved Successfully !');		
							$this->redirect('/medicalRecords/add#sick');
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
				/*
				 *Code for edit the medical sick records
				*/
				//debug($this->request->data['ApprovalProcess']);
				//exit;
				if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalSickRecordEdit']['id']) && (int)$this->data['MedicalSickRecordEdit']['id'] != 0){
			        	$isEdit = 1;
			            if($this->MedicalSickRecord->exists($this->data['MedicalSickRecordEdit']['id'])){
			                $this->data = $this->MedicalSickRecord->findById($this->data['MedicalSickRecordEdit']['id']);
			            }
			        }
			    }


			     /*
		         *Code for delete the medical medical check up records
		         */	
		         if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalSickRecordDelete']['id']) && (int)$this->data['MedicalSickRecordDelete']['id'] != 0){
			            if($this->MedicalSickRecord->exists($this->data['MedicalSickRecordDelete']['id'])){
		                    $this->MedicalSickRecord->id = $this->data['MedicalSickRecordDelete']['id'];
		                    if($this->MedicalSickRecord->saveField('is_trash',1)){
								$this->Session->write('message_type','success');
			                    $this->Session->write('message','Deleted Successfully !');
		                    }else{
								$this->Session->write('message_type','error');
			                    $this->Session->write('message','Delete Failed !');
		                    }
		                    $this->redirect('/medicalRecords/add#sick');		                
			            }
			        }
		    	}

		        /*
		         *Code for insert and update the data of medical serious ill records
		         */
				if(isset($this->data['MedicalSeriousIllRecord']) && is_array($this->data['MedicalSeriousIllRecord']) && count($this->data['MedicalSeriousIllRecord'])>0){	
					$this->request->data['MedicalSeriousIllRecord']['medical_officer_id']=$this->Auth->user('id');
					if(isset($this->data['MedicalSeriousIllRecord']['uuid']) && $this->data['MedicalSeriousIllRecord']['uuid'] == ''){
						$uuidArr = $this->MedicalSeriousIllRecord->query("select uuid() as code");
						$this->request->data['MedicalSeriousIllRecord']['uuid'] = $uuidArr[0][0]['code'];
					}	
					if(isset($this->data['MedicalSeriousIllRecord']['check_up_date']) && $this->data['MedicalSeriousIllRecord']['check_up_date'] != ''){
						$check_up_date = $this->request->data['MedicalSeriousIllRecord']['check_up_date'];
						$parts = explode('-',$check_up_date);
						$check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						$this->request->data['MedicalSeriousIllRecord']['check_up_date'] = $check_up_date;
					}		
					$db = ConnectionManager::getDataSource('default');
            		$db->begin();		
					if($this->MedicalSeriousIllRecord->save($this->data)){

						$refId = 0;
						$action = 'Add';
						if(isset($this->data['MedicalSeriousIllRecord']['id']) && (int)$this->data['MedicalSeriousIllRecord']['id'] != 0)
						{
							$refId = $this->data['MedicalSeriousIllRecord']['id'];
							$action = 'Edit';
						}
						if($this->auditLog('MedicalSeriousIllRecord', 'medical_serious_ill_records', $refId, $action, json_encode($this->data)))
						{
	                        $db->commit(); 
	                        $this->Session->write('message_type','success');
		                    $this->Session->write('message','Saved Successfully !');	
							$this->redirect('/medicalRecords/add#seriouslyill');
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
				/*
				 *Code for edit the medical serious ill records
				*/	
				if(!isset($this->request->data['ApprovalProcess'])){			
			        if(isset($this->data['MedicalSeriousIllRecordEdit']['id']) && (int)$this->data['MedicalSeriousIllRecordEdit']['id'] != 0){
			            if($this->MedicalSeriousIllRecord->exists($this->data['MedicalSeriousIllRecordEdit']['id'])){
			            	$isEdit = 1;
			                $this->data = $this->MedicalSeriousIllRecord->findById($this->data['MedicalSeriousIllRecordEdit']['id']);
			            }
			        }
			    }


			    /*
		         *Code for delete the SeriousIll records
		         */	
		         if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalSeriousIllRecordDelete']['id']) && (int)$this->data['MedicalSeriousIllRecordDelete']['id'] != 0){
			            if($this->MedicalSeriousIllRecord->exists($this->data['MedicalSeriousIllRecordDelete']['id'])){
		                    $this->MedicalSeriousIllRecord->id = $this->data['MedicalSeriousIllRecordDelete']['id'];
		                    if($this->MedicalSeriousIllRecord->saveField('is_trash',1)){
								$this->Session->write('message_type','success');
			                    $this->Session->write('message','Deleted Successfully !');
		                    }else{
								$this->Session->write('message_type','error');
			                    $this->Session->write('message','Delete Failed !');
		                    }
		                    $this->redirect('/medicalRecords/add#seriouslyill');		                
			            }
			        }
		    	}
				/*
		         *Code for insert and update the data of medical death records
		         */
				if(isset($this->data['MedicalDeathRecord']) && is_array($this->data['MedicalDeathRecord']) && count($this->data['MedicalDeathRecord'])>0){
				$this->request->data['MedicalDeathRecord']['medical_officer_id']=$this->Auth->user('id');	
					if(isset($this->data['MedicalDeathRecord']['uuid']) && $this->data['MedicalDeathRecord']['uuid'] == ''){
						$uuidArr = $this->MedicalDeathRecord->query("select uuid() as code");
						$this->request->data['MedicalDeathRecord']['uuid'] = $uuidArr[0][0]['code'];	
					}
					if(isset($this->data['MedicalDeathRecord']['check_up_date']) && $this->data['MedicalDeathRecord']['check_up_date'] != ''){

						$check_up_date = $this->request->data['MedicalDeathRecord']['check_up_date'];
						$parts = explode('-',$check_up_date);
						$check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						$this->request->data['MedicalDeathRecord']['check_up_date'] = $check_up_date;

						
					}			
					$db = ConnectionManager::getDataSource('default');
            		$db->begin();	
					if($this->MedicalDeathRecord->save($this->data)){

						$refId = 0;
						$action = 'Add';
						if(isset($this->data['MedicalDeathRecord']['id']) && (int)$this->data['MedicalDeathRecord']['id'] != 0)
						{
							$refId = $this->data['MedicalDeathRecord']['id'];
							$action = 'Edit';
						}
						if($this->auditLog('MedicalDeathRecord', 'medical_death_records', $refId, $action, json_encode($this->data)))
						{
	                        $db->commit(); 
	                        $this->Session->write('message_type','success');
		                    $this->Session->write('message','Saved Successfully !');	
							$this->redirect('/medicalRecords/add#death');
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
				/*
				 *Code for edit the medical death records
				*/				
				if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalDeathRecordEdit']['id']) && (int)$this->data['MedicalDeathRecordEdit']['id'] != 0){
			            if($this->MedicalDeathRecord->exists($this->data['MedicalDeathRecordEdit']['id'])){
			            	$isEdit = 1;
			                $this->data = $this->MedicalDeathRecord->findById($this->data['MedicalDeathRecordEdit']['id']);
			            }
			        }
		        }

		        /*
		         *Code for delete the medical death records
		         */	
		         if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalDeathDelete']['id']) && (int)$this->data['MedicalDeathDelete']['id'] != 0){
			            if($this->MedicalDeathRecord->exists($this->data['MedicalDeathDelete']['id'])){
		                    $this->MedicalDeathRecord->id = $this->data['MedicalDeathDelete']['id'];
		                    if($this->MedicalDeathRecord->saveField('is_trash',1)){
								$this->Session->write('message_type','success');
			                    $this->Session->write('message','Deleted Successfully !');
		                    }else{
								$this->Session->write('message_type','error');
			                    $this->Session->write('message','Delete Failed !');
		                    }
		                    $this->redirect('/medicalRecords/add#death');		                
			            }
			        }
		    	}		        
		        /*
		         *Query for get the disease list
		         */
		        $diseaseList = $this->Disease->find('list', array(
		        	'recursive'		=> -1,
		        	'fields'		=> array(
		        		'Disease.id',
		        		'Disease.name',
		        	),
		        	'conditions'	=> array(
		        		'Disease.is_enable'		=> 1,
		        		'Disease.is_trash'		=> 0
		        	),
		        	'order'			=> array(
		        		'Disease.name'
		        	),
		        ));
		        /*
		         *Query for get the hospital List
		         */
		        $hospitalList = $this->Hospital->find('list', array(
		        	'recursive'		=> -1,
		        	'fields'		=> array(
		        		'Hospital.id',
		        		'Hospital.name'
		        	),
		        	'conditions'	=> array(
		        		'Hospital.is_enable'	=> 1,
		        		'Hospital.is_trash'		=> 0,
		        	),
		        	'order'			=> array(
		        		'Hospital.name'	
		        	),
		        ));
		         $medicalOfficers=$this->User->find('list',array(
                'fields'        => array(
                    'User.id',
                    'User.first_name',
                ),
                'conditions'=>array(
                  'User.is_enable'=>1,
                  'User.is_trash'=>0,
                  'User.usertype_id'=>6	,//Gate keeper User
                ),
                'order'=>array(
                  'User.first_name'
                )
          ));

		        $this->set(array(
		        	'diseaseList'		=> $diseaseList,
		        	'medicalOfficerListData'=>$medicalOfficerListData,
		        	'prisonerListData'=>$prisonerListData,
		        	'hospitalList'		=> $hospitalList,
                    'medicalOfficers'	=> $medicalOfficers,
		        ));
			
    	$this->set(array(  	
		        	'prisonerListData'=>$prisonerListData, 
		        	'tbList'=>$tbList,
		        	'priorityList'=>$priorityList,
		        	'death_placeList'=>$death_placeList,
		        	'mentalcaseList'=>$mentalcaseList,
		        	'checkupData'=>$checkupData,
		        	'default_status'=>$default_status,
		        	'sttusListData'=>$statusList,
		        	'isEdit'=>$isEdit,
		        	'uuid'=>$uuid,
		        	'attendanceList'=>$attendanceList,
		        	'heightInInchList'=>$heightInInchList,
		        	'heightInFeetList'=>$heightInFeetList,
		        	'attendence_description_search'=>$attendence_description_search,
		));
	}
	//Medical Health check up
	public function medicalCheckupDatapdf(){
		$this->layout = 'ajax';
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
			//$prisoner_id 	= $this->params['named']['prisoner_id'];
			//$uuid 			= $this->params['named']['uuid'];
		$status="";
		$prisoner_id="";
		$uuid="";
			$condition 		= array(
				
				'MedicalCheckupRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalCheckupRecord.status'   => $status,
	            );
	        }
	        else{
	            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	            {
	                $condition      += array('MedicalCheckupRecord.status'=>'Draft');
	            }
	            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	            {
	                $condition      += array('MedicalCheckupRecord.status !='=>'Draft');
	                $condition      += array('MedicalCheckupRecord.status'=>'Saved');
	            }
	            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	            {
	                $condition      += array('MedicalCheckupRecord.status !='=>'Draft');
	                $condition      += array('MedicalCheckupRecord.status !='=>'Saved');
	                $condition      += array('MedicalCheckupRecord.status !='=>'Review-Rejected');
	                $condition      += array('MedicalCheckupRecord.status'=>'Reviewed');
	            }   
	        }
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 
	        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    		$prisoner_id = $this->params['named']['prisoner_id'];
	    		$condition += array(
	    			'MedicalCheckupRecord.prisoner_id'	=> $prisoner_id,
	    		);
	    	}
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalCheckupRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalCheckupRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalCheckupRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'=>$prisoner_id,
				'uuid'			=> $uuid,
			));
		//}
	}
	public function medicalCheckupData(){
		$this->layout = 'ajax';
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
			//$prisoner_id 	= $this->params['named']['prisoner_id'];
			//$uuid 			= $this->params['named']['uuid'];
		$status="";
		$prisoner_id="";
		$uuid="";
			$condition 		= array(
				
				'MedicalCheckupRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalCheckupRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalCheckupRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalCheckupRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalCheckupRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalCheckupRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalCheckupRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalCheckupRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalCheckupRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	            $prisoner_id = $this->params['named']['prisoner_id'];
	             $prisoner_id = str_replace('-', '/', $prisoner_id);
	             $prisoner_id_arr=explode("/",$prisoner_id);
	            $condition += array(
	            	"MedicalCheckupRecord.prisoner_id"=>(int)$prisoner_id_arr[1]
	    			
	    		);
	        }
	        if(isset($this->params['named']['age_from']) && $this->params['named']['age_from'] != '' && isset($this->params['named']['age_to']) && $this->params['named']['age_to'] != '' )
		        {
		            $age_from = $this->params['named']['age_from'];
		            $age_to = $this->params['named']['age_to'];

		            $condition += array(
	            		"MedicalCheckupRecord.age between '".$age_from."' and '".$age_to."'"
	    			
	    			);
		            
		    }
		    if(isset($this->params['named']['hgt_ft']) && $this->params['named']['hgt_ft'] != ''){
	            $hgt_ft = $this->params['named']['hgt_ft'];
	            $condition += array(
	            	"MedicalCheckupRecord.height_feet"=>$hgt_ft
	    			
	    		);
	        }
	        if(isset($this->params['named']['hgt_inch']) && $this->params['named']['hgt_inch'] != ''){
	            $hgt_inch = $this->params['named']['hgt_inch'];
	            $condition += array(
	            	"MedicalCheckupRecord.height_inch"=>$hgt_inch
	    			
	    		);
	        }
	        if(isset($this->params['named']['weight_search']) && $this->params['named']['weight_search'] != ''){
	            $weight_search = $this->params['named']['weight_search'];
	            $condition += array(
	            	"MedicalCheckupRecord.weight"=>$weight_search
	    			
	    		);
	        }
	        if(isset($this->params['named']['folow_from']) && $this->params['named']['folow_from'] != '' && isset($this->params['named']['folow_to']) && $this->params['named']['folow_to'] != ''){
	            $folow_from = $this->params['named']['folow_from'];
	            $folow_to = $this->params['named']['folow_to'];
	            $condition += array(
	            		"MedicalCheckupRecord.follow_up between '".date("Y-m-d",strtotime($folow_from))."' and '".date("Y-m-d",strtotime($folow_to))."'"
	    			
	    			);
	            //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
	        }
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalCheckupRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
	    	
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','initial_exit_checkup_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','initial_exit_checkup_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalCheckupRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalCheckupRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'=>$prisoner_id,
				'uuid'			=> $uuid,
			));
		//}
	}
	public function deleteMedicalCheckupRecords(){
		$this->autoRender = false;
		if(isset($this->data['paramId'])){
			$uuid = $this->data['paramId'];
			$fields = array(
				'MedicalCheckupRecord.is_trash'	=> 1,
			);
			$conds = array(
				'MedicalCheckupRecord.uuid'	=> $uuid,
			);
			$db = ConnectionManager::getDataSource('default');
            $db->begin();	
			if($this->MedicalCheckupRecord->updateAll($fields, $conds)){
				if($this->auditLog('MedicalCheckupRecord', 'medical_checkup_records', $uuid, 'Delete', json_encode($fields)))
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
	public function medicalSickDatapdf(){

		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalSickRecord.prisoner_id'		=> $prisoner_id,
				'MedicalSickRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalSickRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSickRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSickRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSickRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSickRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSickRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalSickRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalSickRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    		$prisoner_id = $this->params['named']['prisoner_id'];
	    		$condition += array(
	    			'MedicalSickRecord.prisoner_id'	=> $prisoner_id,
	    		);
	    	}
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalSickRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalSickRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalSickRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
			));
		//}

	}
	public function medicalSickData(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalSickRecord.prisoner_id'		=> $prisoner_id,
				'MedicalSickRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalSickRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSickRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSickRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSickRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSickRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSickRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalSickRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalSickRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	// if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    	// 	$prisoner_id = $this->params['named']['prisoner_id'];
	    	// 	$condition += array(
	    	// 		'MedicalSickRecord.prisoner_id'	=> $prisoner_id,
	    	// 	);
	    	// }
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	            $prisoner_id = $this->params['named']['prisoner_id'];
	             $prisoner_id = str_replace('-', '/', $prisoner_id);
	             $prisoner_id_arr=explode("/",$prisoner_id);
	            $condition += array(
	            	"MedicalSickRecord.prisoner_id"=>(int)$prisoner_id_arr[1]
	    			
	    		);
	        }
	        if(isset($this->params['named']['sick_checkup_from']) && $this->params['named']['sick_checkup_from'] != '' && isset($this->params['named']['sick_checkup_to']) && $this->params['named']['sick_checkup_to'] != ''){
	            $sick_checkup_from = $this->params['named']['sick_checkup_from'];
	            $sick_checkup_to = $this->params['named']['sick_checkup_to'];
	            $condition += array(
	            		"MedicalSickRecord.check_up_date between '".date("Y-m-d",strtotime($sick_checkup_from))."' and '".date("Y-m-d",strtotime($sick_checkup_to))."'"
	    			
	    			);
	            //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
	        }
	        if(isset($this->params['named']['attendence_search']) && $this->params['named']['attendence_search'] != ''){
	            $attendence_search = $this->params['named']['attendence_search'];
	            $condition += array(
	            	"MedicalSickRecord.attendance"=>$attendence_search
	    			
	    		);
	        }
	        if(isset($this->params['named']['lab_test_search']) && $this->params['named']['lab_test_search'] != ''){
	            $lab_test_search = $this->params['named']['lab_test_search'];
	            $condition += array(
	            	"MedicalSickRecord.disease_id"=>$lab_test_search
	    			
	    		);
	        }
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalSickRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalSickRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalSickRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
			));
		//}
	}
	public function deleteMedicalSickRecords(){
		$this->autoRender = false;
		if(isset($this->data['paramId'])){
			$uuid = $this->data['paramId'];
			$fields = array(
				'MedicalSickRecord.is_trash'	=> 1,
			);
			$conds = array(
				'MedicalSickRecord.uuid'	=> $uuid,
			);
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
			if($this->MedicalSickRecord->updateAll($fields, $conds)){
				if($this->auditLog('MedicalSickRecord', 'medical_sick_records', $uuid, 'Delete', json_encode($fields)))
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
				echo 'FAIL';
			}
		}else{
			echo 'FAIL';
		}
	}
	public function showMedicalSeriousIllRecordsPdf(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalSeriousIllRecord.prisoner_id'		=> $prisoner_id,
				'MedicalSeriousIllRecord.is_trash'		=> 0,
			);

			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalSeriousIllRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSeriousIllRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSeriousIllRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalSeriousIllRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    		$prisoner_id = $this->params['named']['prisoner_id'];
	    		$condition += array(
	    			'MedicalSeriousIllRecord.prisoner_id'	=> $prisoner_id,
	    		);
	    	}
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalSeriousIllRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_seriousill_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_seriousill_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalSeriousIllRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalSeriousIllRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
			));
		//}
	}
	public function showMedicalSeriousIllRecords(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalSeriousIllRecord.prisoner_id'		=> $prisoner_id,
				'MedicalSeriousIllRecord.is_trash'		=> 0,
			);

			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalSeriousIllRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSeriousIllRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSeriousIllRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalSeriousIllRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalSeriousIllRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	// if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    	// 	$prisoner_id = $this->params['named']['prisoner_id'];
	    	// 	$condition += array(
	    	// 		'MedicalSeriousIllRecord.prisoner_id'	=> $prisoner_id,
	    	// 	);
	    	// }
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	            $prisoner_id = $this->params['named']['prisoner_id'];
	             $prisoner_id = str_replace('-', '/', $prisoner_id);
	             $prisoner_id_arr=explode("/",$prisoner_id);
	             // debug($prisoner_id_arr);
	             if(count($prisoner_id_arr)>1){
		            $condition += array(
		            	"MedicalSeriousIllRecord.prisoner_id"=>(int)$prisoner_id_arr[1]
		    			
		    		);
	        	}
	        	else{
	        		$condition += array(
		            	"MedicalSeriousIllRecord.prisoner_id"=>(int)$prisoner_id_arr[0]
		    			
		    		);
	        	}
	        }
	        if(isset($this->params['named']['recommendation_from']) && $this->params['named']['recommendation_from'] != '' && isset($this->params['named']['recommendation_to']) && $this->params['named']['recommendation_to'] != ''){
	            $recommendation_from = $this->params['named']['recommendation_from'];
	            $recommendation_to = $this->params['named']['recommendation_to'];
	            $condition += array(
	            		"MedicalSeriousIllRecord.check_up_date between '".date("Y-m-d",strtotime($recommendation_from))."' and '".date("Y-m-d",strtotime($recommendation_to))."'"
	    			
	    			);
	            //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
	        }
	        if(isset($this->params['named']['priority_searched']) && $this->params['named']['priority_searched'] != ''){
	            $priority_searched = $this->params['named']['priority_searched'];
	            $condition += array(
	            	"MedicalSeriousIllRecord.priority"=>$priority_searched
	    			
	    		);
	        }
	        if(isset($this->params['named']['medical_off_ser']) && $this->params['named']['medical_off_ser'] != ''){
	            $medical_off_ser = $this->params['named']['medical_off_ser'];
	            $condition += array(
	            	"MedicalSeriousIllRecord.medical_officer_id_other"=>$medical_off_ser
	    			
	    		);
	        }
	        if(isset($this->params['named']['hos_id_search']) && $this->params['named']['hos_id_search'] != ''){
	            $hos_id_search = $this->params['named']['hos_id_search'];
	            $condition += array(
	            	"MedicalSeriousIllRecord.hospital_id"=>$hos_id_search
	    			
	    		);
	        }
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalSeriousIllRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_seriousill_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_seriousill_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalSeriousIllRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalSeriousIllRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
			));
		//}
	}
	public function deleteMedicalSeriousillRecords(){
		$this->autoRender = false;
		if(isset($this->data['paramId'])){
			$uuid = $this->data['paramId'];
			$fields = array(
				'MedicalSeriousIllRecord.is_trash'	=> 1,
			);
			$conds = array(
				'MedicalSeriousIllRecord.uuid'	=> $uuid,
			);
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
			if($this->MedicalSeriousIllRecord->updateAll($fields, $conds)){
				if($this->auditLog('MedicalCheckupRecord', 'medical_serious_ill_records', $uuid, 'Delete', json_encode($fields)))
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
	public function showMedicalDeathRecordspdf(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalDeathRecord.prisoner_id'		=> $prisoner_id,
				'MedicalDeathRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalDeathRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalDeathRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalDeathRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalDeathRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    		$prisoner_id = $this->params['named']['prisoner_id'];
	    		$condition += array(
	    			'MedicalDeathRecord.prisoner_id'	=> $prisoner_id,
	    		);
	    	}
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalDeathRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_death_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_death_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalDeathRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalDeathRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
			));
		//}
	}
	public function showMedicalDeathRecords(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalDeathRecord.prisoner_id'		=> $prisoner_id,
				'MedicalDeathRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalDeathRecord.status'   => $status,
	            );
	        }
	        // else{
	        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalDeathRecord.status'=>'Draft');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalDeathRecord.status'=>'Saved');
	        //     }
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
	        //     {
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Draft');
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Saved');
	        //         $condition      += array('MedicalDeathRecord.status !='=>'Review-Rejected');
	        //         $condition      += array('MedicalDeathRecord.status'=>'Reviewed');
	        //     }   
	        // }
	    	// if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	    	// 	$prisoner_id = $this->params['named']['prisoner_id'];
	    	// 	$condition += array(
	    	// 		'MedicalDeathRecord.prisoner_id'	=> $prisoner_id,
	    	// 	);
	    	// }
	    	// if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	     //        $prisoner_id = $this->params['named']['prisoner_id'];
	     //        echo $prisoner_id = str_replace('-', '/', $prisoner_id);
	     //        $condition += array(
	     //        	"MedicalDeathRecord.prisoner_id LIKE '%$prisoner_id%'"
	    			
	    	// 	);
	     //    }
	        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	            $prisoner_id = $this->params['named']['prisoner_id'];
	             $prisoner_id = str_replace('-', '/', $prisoner_id);
	             $prisoner_id_arr=explode("/",$prisoner_id);
	             // debug($prisoner_id_arr);
	             if(count($prisoner_id_arr)>1){
		            $condition += array(
		            	"MedicalDeathRecord.prisoner_id"=>(int)$prisoner_id_arr[1]
		    			
		    		);
	        	}
	        	else{
	        		$condition += array(
		            	"MedicalDeathRecord.prisoner_id"=>(int)$prisoner_id_arr[0]
		    			
		    		);
	        	}
	        }
	        if(isset($this->params['named']['death_from']) && $this->params['named']['death_from'] != '' && isset($this->params['named']['death_to']) && $this->params['named']['death_to'] != ''){
	            $death_from = $this->params['named']['death_from'];
	            $death_to = $this->params['named']['death_to'];
	            $condition += array(
	            		"MedicalDeathRecord.check_up_date between '".date("Y-m-d",strtotime($death_from))."' and '".date("Y-m-d",strtotime($death_to))."'"
	    			
	    			);
	            //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
	        }
	        if(isset($this->params['named']['medi_off_death']) && $this->params['named']['medi_off_death'] != ''){
	            $medi_off_death = $this->params['named']['medi_off_death'];
	            $condition += array(
	            	"MedicalDeathRecord.medical_officer_id_death"=>$medi_off_death
	    			
	    		);
	        }
	    	if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
	    		$uuid = $this->params['named']['uuid'];
	    		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						'Prisoner.uuid'        => $uuid
					),
				));
	    		
	    		$condition += array(
	    			'MedicalDeathRecord.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_death_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_death_report_'.date('d_m_Y').'.doc');
	            }
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 20);
	        } 			
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalDeathRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalDeathRecord');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
			));
		//}		
	}
	public function deleteMedicalDeathRecords(){
		$this->autoRender = false;
		if(isset($this->data['paramId'])){
			$uuid = $this->data['paramId'];
			$fields = array(
				'MedicalDeathRecord.is_trash'	=> 1,
			);
			$conds = array(
				'MedicalDeathRecord.uuid'	=> $uuid,
			);
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
			if($this->MedicalDeathRecord->updateAll($fields, $conds)){
				if($this->auditLog('MedicalDeathRecord', 'medical_death_records', $uuid, 'Delete', json_encode($fields)))
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
}