<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class MedicalRecordsController  extends AppController {
	public $layout='table';
	public $uses=array('Prisoner','MedicalSickRecord', 'Disease', 'Hospital', 'MedicalSeriousIllRecord', 'MedicalDeathRecord','MedicalCheckupRecord','MedicalRelease','User','ApprovalProcess','Height','Notification','Gatepass','PrisonerWardHistory');	
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
   		if (isset($prisoner_id) && $prisoner_id != '') {
	   		$prisonerListData = $this->Prisoner->find('first', array(
					'conditions'	=> array(
						//'Prisoner.prison_id' => $this->Auth->user('prison_id'),
						'Prisoner.id'        => $prisoner_id
					),
				));
	   		$prisoner_name=$prisonerListData["Prisoner"]["fullname"];
	   		$is_restricted=$prisonerListData["Prisoner"]["is_restricted"];
	   		$gender_id=$prisonerListData["Prisoner"]["gender_id"];
	   		$gender= '';
	   		if($gender_id==2){$gender="Female";}
	   		else if($gender_id==1){$gender="Male";}
	   		$age=$prisonerListData["Prisoner"]["age"];

	   		$height_feet=$prisonerListData["Prisoner"]["height_feet"];
	   		$height_inch=$prisonerListData["Prisoner"]["height_inch"];
	   		$is_unfit_labour=$prisonerListData["Prisoner"]["is_unfit_labour"];
	   		echo json_encode(array("prisoner_name"=>$prisoner_name,"gender"=>$gender,"age"=>$age,"height_feet"=>$height_feet,"height_inch"=>$height_inch,"is_restricted"=>$is_restricted,"is_unfit_labour"=>$is_unfit_labour));

	   		
	   	}else{
	   		echo json_encode(array("prisoner_name","gender","age","height_feet","height_inch","is_restricted","is_unfit_labour"));
	   	}
   }
   public function getCheckupPrisnerInfo(){
   		$this->layout = 'ajax';
   		$this->loadModel('Prisoner'); 
   		$this->loadModel('MedicalCheckupRecord');
   		$check_up = $this->request->data['check_up'];
   		//$recommendationList="";
   		//If checkup type Initial
   		$prisonerListData1="";
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
			
			$prisonerConditions = array(
				'Prisoner.is_trash'		=> 0,
				'Prisoner.is_enable'		=> 1,
				'Prisoner.present_status'		=> 1,
				'Prisoner.is_death'		=> 0,
				'Prisoner.is_approve'		=> 1

			);
			ksort($prisonerListData);
			if(!empty($prisonerListData) && count($prisonerListData) > 0)
			{
				$prisonerConditions += array(
					"Prisoner.id NOT IN (".rtrim(implode(',',array_filter($prisonerListData)),",").")"
				);
			}
	   		$prisonerListData1 = $this->Prisoner->find('list', array(
	   				'fields'		=> array(
						'Prisoner.id',
						'Prisoner.prisoner_no',
					),

	                'conditions'	=> array(
					'Prisoner.prison_id' => $this->Auth->user('prison_id'),					
				)+$prisonerConditions,
					 
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
				'Prisoner.is_death'		=> 0,
				'Prisoner.is_approve'		=> 1,
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
	                
	                'conditions'	=> array(
					'Prisoner.prison_id' => $this->Auth->user('prison_id'),
					'MedicalCheckupRecord.prisoner_id = Prisoner.id'
				),
	            
	                ),
	            ),
				'conditions'	=> $prisonerConditions
			));
			// debug($prisonerConditions);
   		}
   		
   		$this->set(array(
        	'prisonerListData1'=>$prisonerListData1,
        	'check_up'=>$check_up,
        ));
   }
   public function approvalmedicalrecord(){
   		$this->autoRender = false;
   		
   	
   }
	public function add($uuid='') { 
		if(!isset($uuid)){$uuid="";}
		$prisoner_uuid = $uuid;
		$uuidParam="";
		$isEdit=0; 
		if(isset($uuid) && ($uuid != ''))
		{
			$isEdit=1; 
		}
		$prisonListData = $this->Prison->find('list', array(
				'fields'		=> array(
					'Prison.id',
					'Prison.name',
				),
				'conditions'	=> array(
					'Prison.is_trash'				=> 0,
					'Prison.is_enable'			=> 1,
				),
		));
		$this->loadModel('PrisonerState');
		$prisonerStateList = $this->PrisonerState->find('list', array(
			'fields'		=> array(
				'PrisonerState.id',
				'PrisonerState.name',
			),
			'conditions'	=> array(
				'PrisonerState.is_trash'				=> 0,
				'PrisonerState.is_enable'			=> 1,
			),
		));
		$this->loadModel('BmiTreatment');
		$bmiTreatmentList = $this->BmiTreatment->find('list', array(
			'fields'		=> array(
				'BmiTreatment.name',
				'BmiTreatment.name',
			),
		));
		$this->loadModel('BloodGroup');
		$bloodGroupList = $this->BloodGroup->find('list', array(
			'fields'		=> array(
				'BloodGroup.name',
				'BloodGroup.name',
			),
		));
		$this->loadModel('Ward');
		$wardMaster = $this->Ward->find("list", array(
            "conditions"    => array(
                "Ward.is_trash"     => 0,
                "Ward.is_enable"    => 1,
                "Ward.prison"    => $this->Session->read('Auth.User.prison_id'),

                "Ward.ward_type"    => Configure::read('MEDICAL-WORDTYPE'),
            ),
        )); 
		$prisonerListData = $this->Prisoner->find('list', array(
			'joins' => array(
                array(
                'table' => 'medical_checkup_records',
                'alias' => 'MedicalCheckupRecord',
                'type' => 'inner',
                'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
                ),
            ),
			'fields'		=> array(
				'Prisoner.id',
				'Prisoner.prisoner_no',
			),
			'conditions'	=> array(
				'Prisoner.is_trash'				=> 0,
				'Prisoner.is_enable'			=> 1,
				'Prisoner.present_status'		=> 1,
				'Prisoner.is_death'				=> 0,
				'MedicalCheckupRecord.check_up'	=> 'Intial',
				'MedicalCheckupRecord.status'	=> 'Approved',
				'Prisoner.transfer_status !='	=> 'Approved',
				'Prisoner.prison_id'			=> $this->Auth->user('prison_id')
			),
		));
		$prisonerReleaseListCond = array();
		$medicalReleaseData = $this->MedicalRelease->find('list', array(
							'fields'		=> array(
								'MedicalRelease.id',
								'MedicalRelease.prisoner_id',
							),
							'conditions'	=> array(
								'MedicalRelease.is_trash'				=> 0,
							),
					));
		// $this->MedicalRelease->find('first', array(
		// 	'conditions'	=> array(
		// 		'MedicalRelease.prisoner_id'=> $prisoner_id,
		// 		//'MedicalRelease.check_up'        => $check_up
		// 	),
		// ));
		$prisonerReleaseListCond = array("Prisoner.id NOT IN (".implode(",", $medicalReleaseData).")");
		$prisonerReleaseListData = $this->Prisoner->find('list', array(
			'joins' => array(
                array(
                'table' => 'medical_checkup_records',
                'alias' => 'MedicalCheckupRecord',
                'type' => 'inner',
                'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
                ),
            ),
			'fields'		=> array(
				'Prisoner.id',
				'Prisoner.prisoner_no',
			),
			'conditions'	=> array(
				'Prisoner.is_trash'				=> 0,
				'Prisoner.is_enable'			=> 1,
				'Prisoner.is_death'				=> 0,
				'Prisoner.present_status'		=> 1,
				'MedicalCheckupRecord.check_up'	=> 'Intial',
				'MedicalCheckupRecord.status'	=> 'Approved',
				'Prisoner.transfer_status !='	=> 'Approved',
				'Prisoner.prison_id'			=> $this->Auth->user('prison_id')
			)+$prisonerReleaseListCond,
		));
		$deathListArr = $this->MedicalDeathRecord->find('list', array(
				'fields'		=> array(
					'MedicalDeathRecord.id',
					'MedicalDeathRecord.prisoner_id',
				),
				'conditions'	=> array(
					'MedicalDeathRecord.is_trash'				=> 0,
					'MedicalDeathRecord.prison_id'			=> $this->Auth->user('prison_id')
				),
		));
		$prisonerDeathListCond = array();
		if(isset($deathListArr) && count($deathListArr)>0){
			$prisonerDeathListCond = array("Prisoner.id NOT IN (".implode(",", $deathListArr).")");
		}
		$prisonerDeathListData = $this->Prisoner->find('list', array(
			'joins' => array(
                array(
                'table' => 'medical_checkup_records',
                'alias' => 'MedicalCheckupRecord',
                'type' => 'inner',
                'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
                ),
            ),
			'fields'		=> array(
				'Prisoner.id',
				'Prisoner.prisoner_no',
			),
			'conditions'	=> array(
				'Prisoner.is_trash'				=> 0,
				'Prisoner.is_enable'			=> 1,
				'Prisoner.present_status'		=> 1,
				// 'Prisoner.is_approve'			=> 1,
				'MedicalCheckupRecord.check_up'	=> 'Intial',
				'MedicalCheckupRecord.status'	=> 'Approved',
				'Prisoner.transfer_status !='	=> 'Approved',
				'Prisoner.prison_id'			=> $this->Auth->user('prison_id')
			)+$prisonerDeathListCond,
		));
		 // debug($prisonerDeathListData);
		$medicalOfficerListData = $this->User->find('list', array(
				'fields'		=> array(
					'User.id',
					'User.name',
				),
				'conditions'	=> array(
					'User.is_trash'		=> 0,
					'User.id'				=> $this->Auth->user('id'),
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
                        'Height.height_type'    => 'Centimetre',
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
        $hivtesting=array("Yes"=>"Yes","No"=>"No");
		$tbList = array("Positve"=>"+ve","Nagetive"=>"-ve");
		$mentalcaseList=array("Yes"=>"Yes","No"=>"No");
		$checkupData=array("Intial"=>"Intial","Exit"=>"Exit");
		$death_placeList=array("Inside the Prison"=>"In","Out"=>"Out");
		$attendanceList=array("New Attendence"=>"New Attendance","Re-Attendance"=>"Re-Attendance");
		$prisonerstateList=array("Good"=>"Good","Bad"=>"Bad");
				/*
				 *Code start for insert and update the data of medical check up records
				 */


		$status = 'Saved'; 
        $remark = '';
        
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
            	// debug($this->data);exit;
                $status = 'Saved'; 
                $remark = '';
                $modelname=$this->request->data['ApprovalProcessForm']['modelname'];

                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }

                $approveStatus = $status;
                $items = $this->request->data['ApprovalProcess'];
                //update PF-20 by OC at the time of review
                if(isset($this->data['MedicalReleasepf']) && is_array($this->data['MedicalReleasepf']) && count($this->data['MedicalReleasepf'])>0){
                	$fieldss = array(
						'MedicalRelease.prisoner_supporter' => "'".$this->data['MedicalReleasepf']['prisoner_supporter']."'",
						'MedicalRelease.prisoner_wishes' => "'".$this->data['MedicalReleasepf']['prisoner_wishes']."'",
						'MedicalRelease.prisoner_crime' => "'".$this->data['MedicalReleasepf']['prisoner_crime']."'",
						'MedicalRelease.prisoner_relocation' => "'".$this->data['MedicalReleasepf']['prisoner_relocation']."'",
					);
					foreach ($items as $key => $value) {
						$condss = array(
							'MedicalRelease.id' => $value['fid'],
						);
						$this->MedicalRelease->updateAll($fieldss, $condss);
					}
                }
                $status = $this->setApprovalProcess($items, $modelname, $status, $remark);
                if($modelname == 'MedicalSeriousIllRecord' && Configure::read('OFFICERINCHARGE_USERTYPE') == $this->Session->read('Auth.User.usertype_id') && $approveStatus=='Reviewed'){
                	$status = $this->setApprovalProcess($items, $modelname, "Approved", $remark);
                }
                if($status == 1)
                {
                	if(isset($this->request->data['ApprovalProcessForm']['modelname']) && $this->request->data['ApprovalProcessForm']['modelname']=='MedicalSickRecord' && $approveStatus=='Approved'){
	                	if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess'])>0){
	                		foreach ($this->request->data['ApprovalProcess'] as $wardkey => $wardvalue) {
	                			$wardvalue['fid'];

	                			$ward_prisoner_id = $this->MedicalSickRecord->field("prisoner_id", array("MedicalSickRecord.id"=>$wardvalue['fid']));
	                			$assignedWardId = $this->MedicalSickRecord->field("ward_id", array("MedicalSickRecord.id"=>$wardvalue['fid']));
	                			$assignedWardCellId = $this->MedicalSickRecord->field("ward_id", array("MedicalSickRecord.id"=>$wardvalue['fid']));
			                	$wardData["Prisoner"]["id"] = $ward_prisoner_id;
			                    $wardData["Prisoner"]["assigned_ward_id"] =  $assignedWardId;
			                    $wardData["Prisoner"]["assigned_ward_cell_id"] =  $assignedWardCellId;

			                    $wardHistory = array();
			                    $wardData["PrisonerWardHistory"]["prison_id"] = $this->Session->read('Auth.User.prison_id');
			                    $wardData["PrisonerWardHistory"]["prisoner_id"] = $ward_prisoner_id;
			                    $wardData["PrisonerWardHistory"]["ward_id"] = $assignedWardId;
			                    $wardData["PrisonerWardHistory"]["ward_cell_id"] = $assignedWardCellId;
			                    // debug($wardData);exit;
			                    if($this->Prisoner->save($wardData))
			                    {
			                    	$this->auditLog('Prisoner','prisoners',$ward_prisoner_id, 'update', json_encode($wardData["Prisoner"]));
			                        if($this->PrisonerWardHistory->save($wardData)){
			                            $this->auditLog('PrisonerWardHistory','prisoner_ward_histories',$ward_prisoner_id, 'insert', json_encode($wardData["PrisonerWardHistory"]));
			                            //notification --START--
			                            $notification_msg = "Prisoner no ".$this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$ward_prisoner_id))." is admitted in hospital and assigned ward ".$this->getName($assignedWardId,"Ward","name")." and cell ".$this->getName($assignedWardCellId,"WardCell","cell_name");
			                            $usertypes = array(
							                Configure::read('RECEPTIONIST_USERTYPE'),
							                Configure::read('PRINCIPALOFFICER_USERTYPE'),
							                Configure::read('OFFICERINCHARGE_USERTYPE')
							            );
							            $usertypes = implode(',',$usertypes);
							            $userList = $this->User->find("list", array(
						                    'fields'        => array(
						                        'User.id',
						                        'User.name',
						                    ),
						                    'conditions'    => array(
						                        'User.is_enable'	=> 1,
						                        'User.is_trash'		=> 0,
						                        'User.prison_id'	=> $this->Session->read('Auth.User.prison_id'),
						                        'User.usertype_id IN ('.$usertypes.')'
						                    )
						                ));
						                
						                $url_link = '#';
						                // debug($userList);
						                $this->addManyNotification($userList, $notification_msg, $url_link);
						                //notification --END--
			                        }
			                    }
	                		}
	                	}
	                }

                    $this->Session->write('message_type','success');
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')))
	                {
	                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
	                    {
	                        if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
	                        if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
	                        if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
	                    }
	                }
                    else{
                        $this->Session->write('message','Forwarded Successfully !');
                    }

                    if($modelname=='MedicalSeriousIllRecord'){
                    	//notification on approval of Disciplinary proceeding list --START--
	                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	                    {
	                        $notification_msg = "Recommended For Referral list of prisoner are pending for review.";
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
	                                "url_link"   => "medicalRecords/add#seriouslyill",
	                            )); 
	                        }
	                    }

	                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	                    {
	                        $notification_msg = "Recommended For Referral list of prisoner are pending for approve";
	                        $notifyUser = $this->User->find('first',array(
	                            'recursive'     => -1,
	                            'conditions'    => array(
	                                'User.usertype_id'	=> Configure::read('RECEPTIONIST_USERTYPE'),
	                                'User.is_trash'     => 0,
	                                'User.is_enable'	=> 1,
	                                'User.prison_id'	=> $this->Session->read('Auth.User.prison_id')
	                            )
	                        ));
	                        if(isset($notifyUser['User']['id']))
	                        {
	                            $this->addNotification(array(
	                                "user_id"   => $notifyUser['User']['id'],
	                                "content"   => $notification_msg,
	                                "url_link"   => "medicalRecords/add#release_recom",
	                            ));
	                        }
	                    }
	                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	                    {
	                        $notification_msg = "Recommended For Referral is approved, Please intiate gatepass for this prisoner.";
	                        $notifyUser = $this->User->find('first',array(
	                            'recursive'     => -1,
	                            'conditions'    => array(
	                                'User.usertype_id'	=> Configure::read('RECEPTIONIST_USERTYPE'),
	                                'User.is_trash'     => 0,
	                                'User.is_enable'	=> 1,
	                                'User.prison_id'	=> $this->Session->read('Auth.User.prison_id')
	                            )
	                        ));
	                        if(isset($notifyUser['User']['id']))
	                        {
	                            $this->addNotification(array(
	                                "user_id"   => $notifyUser['User']['id'],
	                                "content"   => $notification_msg,
	                                "url_link"   => "MedicalRecords/gatepassList",
	                            ));
	                        }
	                    }
	                    //notification on approval of Disciplinary proceeding list --END--
                    }

                    if($modelname=='MedicalRelease'){
                    	//notification on approval of Disciplinary proceeding list --START--
	                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
	                    {
	                        $notification_msg = "Recommended For Release list of prisoner are pending for review.";
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
	                                "url_link"   => "medicalRecords/add#release_recom",
	                            )); 
	                        }
	                    }

	                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
	                    {
	                        $notification_msg = "Recommended For Release list of prisoner are pending for approve";
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
	                                "url_link"   => "medicalRecords/add#seriouslyill",
	                            ));
	                        }
	                    }
	                    //notification on approval of Disciplinary proceeding list --END--
                    }

                    
                    //$this->redirect('/medicalRecords/add#health_checkup');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Forwarding failed');
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
							 
						//debug($this->data);exit;
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
								if(isset($this->data['height_feet'])){
									$uuid = $this->data['paramId'];
									$fields = array(
										'Prisoner.height_feet' => $this->data['MedicalCheckupRecord']['height_feet'],
									);
									$conds = array(
										'Prisoner.id' => $this->data['MedicalCheckupRecord']['prisoner_id'],
									);
									$this->Prisoner->updateAll($fields, $conds);
								}

		                        $db->commit(); 
		                        $this->Session->write('message_type','success');

		                        if(isset($this->data['MedicalCheckupRecord']['id'])) {
			                    	$this->Session->write('message','Updated Successfully !');
			                    }

			                    else{
			                    $this->Session->write('message','Saved Successfully !');
			                    }	

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
						$this->request->data['MedicalCheckupRecord']['prison_id'] = $this->Session->read('Auth.User.prison_id');
						$this->request->data['MedicalCheckupRecord']['status'] = 'Approved';
	            		// debug($this->request->data['MedicalCheckupRecord']);exit;
						$db = ConnectionManager::getDataSource('default');
	            		$db->begin(); 			

						if($this->MedicalCheckupRecord->saveAll($this->request->data)){

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
				if(isset($this->data['MedicalRelease']) && is_array($this->data['MedicalRelease']) && count($this->data['MedicalRelease'])>0){	//&& $this->data['MedicalRelease']['id']==""
					//debug($this->data);exit;
					$this->request->data['MedicalRelease']['medical_officer_id']=$this->Auth->user('id');
					//$check_up=$this->data['MedicalRelease']['check_up'];
					$prisoner_id=isset($this->data['MedicalRelease']['prisoner_id']) && $this->data['MedicalRelease']['prisoner_id']!=''?$this->data['MedicalRelease']['prisoner_id']:'';
					$medicalcheckListData = $this->MedicalRelease->find('first', array(
						'conditions'	=> array(
							'MedicalRelease.prisoner_id'=> $prisoner_id,
							'MedicalRelease.is_trash'=> 0,
							//'MedicalRelease.check_up'        => $check_up
						),
					));
					
					if(count($medicalcheckListData)==0 || $this->data['MedicalRelease']['id']!=''){
						if(isset($this->data['MedicalRelease']['uuid']) && $this->data['MedicalRelease']['uuid'] == ''){
						$uuidArr = $this->MedicalRelease->query("select uuid() as code");
						$this->request->data['MedicalRelease']['uuid'] = $uuidArr[0][0]['code'];	
						}
						/*if(isset($this->data['MedicalRelease']['follow_up']) && $this->data['MedicalRelease']['follow_up'] != ''){
							$follow_up = $this->request->data['MedicalRelease']['follow_up'];
							$parts = explode('-',$follow_up);
							$follow_up = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
							$this->request->data['MedicalRelease']['follow_up'] = $follow_up;
						}*/	
						if(isset($this->data['MedicalRelease']['check_up_date']) && $this->data['MedicalRelease']['check_up_date'] != ''){
							$check_up_date = date("Y-m-d",strtotime($this->request->data['MedicalRelease']['check_up_date']));
							// $parts = explode('-',$check_up_date);
							// $check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
							$this->request->data['MedicalRelease']['check_up_date'] = $check_up_date;
						}	
						// implode presentation partha
							if(isset($this->data['MedicalRelease']['presentation_id']) && count($this->data['MedicalRelease']['presentation_id'])>0){
							$this->request->data['MedicalRelease']['presentation_id'] = implode(",", $this->data['MedicalRelease']['presentation_id']);
							// $parts = explode('-',$check_up_date);
							// $check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
							$this->request->data['MedicalRelease']['check_up_date'] = $check_up_date;
						}		
						$this->request->data['MedicalRelease']['prison_id'] = $this->Session->read('Auth.User.prison_id');
						// $this->request->data['MedicalRelease']['status'] = 'Approved';
	            		//debug($this->request->data['MedicalRelease']);exit;
						$db = ConnectionManager::getDataSource('default');
	            		$db->begin(); 			

						if($this->MedicalRelease->saveAll($this->request->data)){

							$refId = 0;
							$action = 'Add';
							if(isset($this->data['MedicalRelease']['id']) && (int)$this->data['MedicalRelease']['id'] != 0)
							{
								$refId = $this->data['MedicalRelease']['id'];
								$action = 'Edit';
							}
							if($this->auditLog('MedicalRelease', 'medical_releases', $refId, $action, json_encode($this->data)))
							{
		                        $db->commit(); 
		                        $this->Session->write('message_type','success');
			                    $this->Session->write('message','Saved Successfully !');	
								$this->redirect('/medicalRecords/add#release_recom');                       
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
			            $this->Session->write('message','Saving Failed !'.$prisoner_id.' Prisoner already exist.');
					}
					
				}

				/*
				 *Code for edit the medical release
				*/				
				if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalReleaseEdit']['id']) && (int)$this->data['MedicalReleaseEdit']['id'] != 0){
			            if($this->MedicalRelease->exists($this->data['MedicalReleaseEdit']['id'])){
			            	$isEdit = 1;
			                $this->data = $this->MedicalRelease->findById($this->data['MedicalReleaseEdit']['id']);
			            }
			        }
		        }

		        /*
		         *Code for delete the medical medical check up records
		         */	
		         if(!isset($this->request->data['ApprovalProcess'])){
			        if(isset($this->data['MedicalReleaseDelete']['id']) && (int)$this->data['MedicalReleaseDelete']['id'] != 0){
			            if($this->MedicalRelease->exists($this->data['MedicalReleaseDelete']['id'])){
		                    $this->MedicalRelease->id = $this->data['MedicalReleaseDelete']['id'];
		                    if($this->MedicalRelease->saveField('is_trash',1)){
								$this->Session->write('message_type','success');
			                    $this->Session->write('message','11Deleted Successfully !');
		                    }else{
								$this->Session->write('message_type','error');
			                    $this->Session->write('message','Delete Failed !');
		                    }
		                    $this->redirect('/medicalRecords/add#release_recom');		                
			            }
			        }
		    	}
		    	//code for final save partha starts death


		    	 if(isset($this->data['MedicalDeathfinalsaveId']['id']) && (int)$this->data['MedicalDeathfinalsaveId']['id'] != 0){
           	 if($this->MedicalDeathRecord->exists($this->data['MedicalDeathfinalsaveId']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin(); 

                if($this->MedicalDeathRecord->updateAll(array('MedicalDeathRecord.is_final_save' => 1), array('MedicalDeathRecord.id'  => $this->data['MedicalDeathfinalsaveId']['id']))){
                    if($this->auditLog('MedicalDeathRecord', 'wards', $this->data['MedicalDeathfinalsaveId']['id'], 'is_final_save', json_encode(array('MedicalDeathRecord.is_final_save' => 1)))){
                        $db->commit();
		    	// debug($this->data);exit;

                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Final Saved Successfully !');
                        //$this->redirect('/medicalRecords/add#release_recom');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        } 
		    	//code for final save partha ends death
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
					'wardMaster'			=> $wardMaster,
					
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
					if(isset($this->data['MedicalSickRecord']['check_up_date1']) && $this->data['MedicalSickRecord']['check_up_date1'] != ''){
						$check_up_date = date("Y-m-d",strtotime($this->request->data['MedicalSickRecord']['check_up_date1']));
						// $parts = explode('-',$check_up_date);
						// $check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						$this->request->data['MedicalSickRecord']['check_up_date'] = $check_up_date;
					}	
					if(isset($this->data['MedicalSickRecord']['malnutrition_type_id']) && $this->data['MedicalSickRecord']['malnutrition_type_id'] != 2){
							$this->request->data['MedicalSickRecord']['state_of_prisoner'] = '';
					}
					if(isset($this->data['MedicalSickRecord']['malnutrition_type_id']) && $this->data['MedicalSickRecord']['malnutrition_type_id'] != 1){
						$this->request->data['MedicalSickRecord']['restricted_prisoner'] = 0;
						$this->request->data['MedicalSickRecord']['restricted_work'] = '';
					}

					$this->request->data['MedicalSickRecord']['status'] = 'Approved';
					if($this->data['MedicalSickRecord']['checkup_type']== 'In Patient'){
						$this->request->data['MedicalSickRecord']['status'] = 'Draft';
					}

					if(isset($this->data['MedicalSickRecord']['disease_id']) && is_array($this->data['MedicalSickRecord']['disease_id']) && count($this->data['MedicalSickRecord']['disease_id'])>0){
						$this->request->data['MedicalSickRecord']['disease_id'] = implode(",",array_filter($this->data['MedicalSickRecord']['disease_id']));
					}
					
						// debug($this->request->data);exit;
					$this->request->data['MedicalSickRecord']['prison_id'] = $this->Session->read('Auth.User.prison_id');
					
					$db = ConnectionManager::getDataSource('default');
            		$db->begin();			
					if($this->MedicalSickRecord->save($this->request->data)){
						$refId = 0;
						$action = 'Add';
						if(isset($this->data['MedicalSickRecord']['id']) && (int)$this->data['MedicalSickRecord']['id'] != 0)
						{
							$refId = $this->data['MedicalSickRecord']['id'];
							$action = 'Edit';
						}
						if($this->auditLog('MedicalSickRecord', 'medical_sick_records', $refId, $action, json_encode($this->data)))
						{
							
	                        if(isset($this->request->data['MedicalSickRecord']['restricted_prisoner']) && $this->request->data['MedicalSickRecord']['restricted_prisoner']==1){
	                        	$this->loadModel("RestrictionHistory");
	                        	$history_data = array('prisoner_id'=>$this->data['MedicalSickRecord']['prisoner_id'], 'from_date'=>date('Y-m-d'));
	                        	$this->RestrictionHistory->saveAll($history_data);
								$this->Prisoner->updateAll(array("Prisoner.is_restricted"=>1),array("Prisoner.id"=>$this->data['MedicalSickRecord']['prisoner_id']));
								// send notification for death
		                        $userList = $this->User->find("list", array(
		                        	"conditions"	=> array(
						                "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
						                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
						            )
					            ));
					            // data[MedicalDeathRecord][prisoner_id]
					            $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $this->data['MedicalSickRecord']['prisoner_id']));
					            $uuid = $this->Prisoner->field('uuid',array('id' => $this->data['MedicalSickRecord']['prisoner_id']));
					            if(isset($userList) && is_array($userList) && count($userList)>0){
					                foreach ($userList as $key => $value) {
					                    $this->Notification->saveAll(array(
					                        "user_id"   => $key,
					                        "content"   => "Prisoner no ".$prisoner_no." has been marked as restricted prisoner.",
					                        "url_link"   => $this->webroot."prisoners/details/".$uuid,
					                    ));
					                }
					            }
					           $db->commit(); 
					           // exit;
							}else{
								$this->loadModel("RestrictionHistory");
								
								$this->RestrictionHistory->updateAll(array("RestrictionHistory.to_date"=>"'".date("Y-m-d")."'"),array("RestrictionHistory.prisoner_id"=>$this->data['MedicalSickRecord']['prisoner_id'],"RestrictionHistory.to_date IS NULL"));
								$this->Prisoner->updateAll(array("Prisoner.is_restricted"=>0),array("Prisoner.id"=>$this->data['MedicalSickRecord']['prisoner_id']));
								$db->commit(); 
							}


							//hight upadte partha
							if (isset($this->request->data['MedicalSickRecord']['height_feet']) && $this->request->data['MedicalSickRecord']['height_feet']!='') {

								$this->loadModel("Prisoner");
	                        	
	                        	$this->Prisoner->updateAll(array("Prisoner.height_feet"=>$this->request->data['MedicalSickRecord']['height_feet']),array("Prisoner.id"=>$this->data['MedicalSickRecord']['prisoner_id']));
							}
							// height upadte

							// Unfit Prisoner partha code


							if(isset($this->request->data['MedicalSickRecord']['prisoner_state_id']) && $this->request->data['MedicalSickRecord']['prisoner_state_id']==6){
	                        	$this->loadModel("UnfitHistory");
	                        	$history_data_unfit = array(
	                        		'prisoner_id'=>$this->data['MedicalSickRecord']['prisoner_id'],
	                        		'prison_id'=>$this->Session->read('Auth.User.prison_id'),
	                        		 'from_date'	=>	date('Y-m-d')
	                        	);
	                        	//debug($history_data_unfit); exit();

	                        	$this->UnfitHistory->saveAll($history_data_unfit);
	                        	$this->Prisoner->updateAll(array("Prisoner.is_unfit_labour"=>1),array("Prisoner.id"=>$this->data['MedicalSickRecord']['prisoner_id']));
								
								// send notification for unfit for labour
		                        $userList = $this->User->find("list", array(
		                        	"conditions"	=> array(
						                "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
						                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
						            )
					            ));
					            // data[MedicalDeathRecord][prisoner_id]
					            $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $this->data['MedicalSickRecord']['prisoner_id']));
					            $uuid = $this->Prisoner->field('uuid',array('id' => $this->data['MedicalSickRecord']['prisoner_id']));
					            if(isset($userList) && is_array($userList) && count($userList)>0){
					                foreach ($userList as $key => $value) {
					                    $this->Notification->saveAll(array(
					                        "user_id"   => $key,
					                        "content"   => "Prisoner no ".$prisoner_no." has been marked as Unfit for labour.",
					                        "url_link"   => $this->webroot."prisoners/details/".$uuid,
					                    ));
					                }
					            }
					           $db->commit(); 
					           // exit;
							}else{
								$this->loadModel("UnfitHistory");
								
								$this->UnfitHistory->updateAll(array("UnfitHistory.to_date"=>"'".date("Y-m-d")."'"),array("UnfitHistory.prisoner_id"=>$this->data['MedicalSickRecord']['prisoner_id'],"UnfitHistory.to_date IS NULL"));
								$this->Prisoner->updateAll(array("Prisoner.is_unfit"=>0),array("Prisoner.id"=>$this->data['MedicalSickRecord']['prisoner_id']));
								$db->commit(); 
							}


							
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
						$check_up_date = date('Y-m-d',strtotime($check_up_date));
						// $parts = explode('-',$check_up_date);
						// $check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						$this->request->data['MedicalSeriousIllRecord']['check_up_date'] = $check_up_date;
					}		
					$db = ConnectionManager::getDataSource('default');
            		$db->begin();	
            		//debug($this->request->data);exit;	
            		$this->request->data['MedicalSeriousIllRecord']['prison_id'] = $this->Session->read('Auth.User.prison_id');
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

						// $check_up_date = $this->request->data['MedicalDeathRecord']['check_up_date'];
						// $parts = explode('-',$check_up_date);
						// $check_up_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						// $this->request->data['MedicalDeathRecord']['check_up_date'] = $check_up_date;
						$fdate = date('Y-m-d h:i',strtotime($this->request->data['MedicalDeathRecord']['check_up_date'])); 
        				$this->request->data['MedicalDeathRecord']['check_up_date'] = $fdate;
						
					}			
					$this->request->data['MedicalDeathRecord']['prison_id'] = $this->Session->read('Auth.User.prison_id');
					$this->request->data['MedicalDeathRecord']['status'] = "Approved";
					// debug($this->data);
					$db = ConnectionManager::getDataSource('default');
            		$db->begin();	
					if($this->MedicalDeathRecord->saveAll($this->data)){
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
	                        if(isset($this->data['MedicalDeathRecord']['id']) && $this->data['MedicalDeathRecord']['id'] == '')
							{
								$this->Prisoner->updateAll(array('Prisoner.is_death'=>1),array("Prisoner.id"=>$this->data['MedicalDeathRecord']['prisoner_id']));
								$this->medicalPdf($this->data['MedicalDeathRecord']['prisoner_id']);
		                        // send notification for death
		                        $userList = $this->User->find("list", array(
		                        	"conditions"	=> array(
						                "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
						                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
						            )
					            ));
					            // data[MedicalDeathRecord][prisoner_id]
					            $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $this->data['MedicalDeathRecord']['prisoner_id']));
					            $uuid = $this->Prisoner->field('uuid',array('id' => $this->data['MedicalDeathRecord']['prisoner_id']));
					            if(isset($userList) && is_array($userList) && count($userList)>0){
					            	$message = "";
					            	if(isset($this->data['MedicalDeathRecord']['attachment']) && $this->data['MedicalDeathRecord']['attachment']==''){
					            		$message = "Postmotorm report not uploaded";
					            	}
					            	if(isset($this->data['MedicalDeathRecord']['prisoner_id']) && $this->data['MedicalDeathRecord']['prisoner_id']==''){
					            		$message .= " Pathologist Report not uploaded";
					            	}
					            	$this->addManyNotification($userList,"Prisoner no ".$prisoner_no." has been death. ".$message." Please start discharge process","discharges/index/".$uuid."#discharge_prisoner");
					            }

					            // send notification for death
		                        $userList = $this->User->find("list", array(
		                        	"conditions"	=> array(
						                "User.usertype_id"  => Configure::read('WELFAREOFFICER_USERTYPE'),
						                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
						            )
					            ));
					            // data[MedicalDeathRecord][prisoner_id]
					            $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $this->data['MedicalDeathRecord']['prisoner_id']));
					            $uuid = $this->Prisoner->field('uuid',array('id' => $this->data['MedicalDeathRecord']['prisoner_id']));
					            if(isset($userList) && is_array($userList) && count($userList)>0){
					            	$this->addManyNotification($userList,"Prisoner no ".$prisoner_no." has been death","");
					            }
					        }
	                        //=============================================
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
		        $this->loadModel('Recommendation');
		        $this->loadModel('MalnutritionType');
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
		        $malnutritionlist = $this->MalnutritionType->find('list', array(
		        	'recursive'		=> -1,
		        	'fields'		=> array(
		        		'MalnutritionType.id',
		        		'MalnutritionType.name',
		        	),
		        	'conditions'	=> array(
		        		'MalnutritionType.is_enable' => 1,
		        		'MalnutritionType.is_trash'  => 0
		        	),
		        	'order'			=> array(
		        		'MalnutritionType.name'
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

		        $recommendationList = $this->Recommendation->find('list', array(
	   				'fields'		=> array(
						'Recommendation.id',
						'Recommendation.name',
					),

	                'conditions'	=> array(
					'Recommendation.is_trash' =>0,
					'Recommendation.is_enable' =>1,
					),

					'order'			=> array(
		        		'Recommendation.name'	
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
		        $this->loadModel('Category');
	         $categoryList = $this->Category->find('list', array(
	        	'recursive'		=> -1,
	        	'fields'		=> array(
	        		'Category.id',
	        		'Category.name'
	        	),
	        	'conditions'	=> array(
	        		'Category.is_enable'	=> 1,
	        		'Category.is_trash'		=> 0,
	        	),
	        	'order'			=> array(
	        		'Category.name'	
	        	),
	        ));

        $this->set(array(
        	'diseaseList'		=> $diseaseList,
        	'medicalOfficerListData'=>$medicalOfficerListData,
        	'prisonerListData'=>$prisonerListData,
        	'hospitalList'		=> $hospitalList,
            'medicalOfficers'	=> $medicalOfficers,
            'malnutritionlist'  => $malnutritionlist,
            'categoryList'		=> $categoryList,
            'recommendationList' => $recommendationList,
            'prisonerStateList'	=> $prisonerStateList,
        ));

        //if $uuid get prisoner id 
        $prisoner_id = '';
        $isPrisonerInitialCheckup = 0;
        if(isset($prisoner_uuid) && !empty($prisoner_uuid))
        {
        	$prisonerDetail =  $this->Prisoner->find('first', array(
        						'recursive'=>-1,
							  	'conditions'=>array('Prisoner.uuid'=>$prisoner_uuid),
							  	'fields'=>array('Prisoner.id')
							));
        	if(isset($prisonerDetail['Prisoner']['id']) && !empty($prisonerDetail['Prisoner']['id']))
        	{
        		$prisoner_id = $prisonerDetail['Prisoner']['id'];
        		$prisonerListData1 = $this->Prisoner->find('list', array(
	   				'fields'		=> array(
						'Prisoner.id',
						'Prisoner.prisoner_no',
					),

	                'conditions'	=> array(
					'Prisoner.id' => $prisoner_id,					
					)
					
				));
        		//debug($prisonerListData1); exit;
        		
        		$isPrisonerInitialCheckup = $this->MedicalCheckupRecord->find('count', array(
		   				'recursive'=>-1,
						'fields'		=> array(
							'MedicalCheckupRecord.prisoner_id',
						),
						'conditions'		=> array(
							'MedicalCheckupRecord.prisoner_id' => $prisoner_id,
						),
						'group'	=> array(
							"MedicalCheckupRecord.prisoner_id" 
						)
				));
        	}
        }
        
			
    	$this->set(array(  	
		        	'prisonerListData'=>$prisonerListData, 
		        	'tbList'=>$tbList,
		        	'bmiTreatmentList'=>$bmiTreatmentList,
		        	'bloodGroupList'=>$bloodGroupList, 
		        	'hivtesting'=>$hivtesting,
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
		        	'prisonerstateList'=>$prisonerstateList,
		        	'prisonListData'=>$prisonListData,
		        	'prisonerDeathListData'=>$prisonerDeathListData,
		        	'prisoner_id'=>$prisoner_id,
		        	'isPrisonerInitialCheckup'=>$isPrisonerInitialCheckup,
		        	'prisonerListData1'=>$prisonerListData1,
		        	'prisonerReleaseListData'=>$prisonerReleaseListData
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
	            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
	            $limit = array('limit'  => 10);
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
		$prison_id = '';
		$condition 		= array(
			
			'MedicalCheckupRecord.is_trash'		=> 0,
		);
		// if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
  //           $status = $this->params['named']['status'];
  //           $condition += array(
  //               'MedicalCheckupRecord.status'   => $status,
  //           );
  //       }
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
			$condition 		= array(
				'MedicalCheckupRecord.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
			);
		}

		if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array(
                'MedicalCheckupRecord.prison_id'   => $prison_id,
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
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
        //     {
        //         $condition      += array('MedicalCheckupRecord.status !='=>'Draft');
        //         $condition      += array('MedicalCheckupRecord.status !='=>'Saved');
        //         $condition      += array('MedicalCheckupRecord.status !='=>'Review-Rejected');
        //         $condition      += array('MedicalCheckupRecord.status'=>'Reviewed');
        //     }   
        // }
    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
             // $prisoner_id = $this->params['named']['prisoner_id'];
              $prisoner_id = str_replace('-', '/', $this->params['named']['prisoner_id']);
             // $prisoner_id_arr=explode("/",$prisoner_id);
             // $prisoner_id=(isset($prisoner_id_arr[1]))?$prisoner_id_arr[1]:'';
    		    $getPrisonerId = $this->Prisoner->find('all', array(
    		    	'conditions'=>array('Prisoner.prisoner_no like "%'.$prisoner_id.'%"')
    		    ));
    		    //debug($getPrisonerId);
    		    $Prisonerid=array();
    		    foreach ($getPrisonerId as $key => $value) {

    		    	$Prisonerid[]=$value['Prisoner']['id'];
    		    }
    		    $prisonoidimp = implode(',', $Prisonerid);
            $condition += array(
            	"MedicalCheckupRecord.prisoner_id IN (".$prisonoidimp.")"
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
    	   if(isset($this->params['named']['blood_group_search']) && $this->params['named']['blood_group_search'] != ''){
            $blood = $this->params['named']['blood_group_search'];
            $condition += array(
            	"MedicalCheckupRecord.blood_group"=>$blood
    			
    		);
        }
        // debug($this->params['named']);
        // debug($condition);
    	
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','initial_exit_checkup_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','initial_exit_checkup_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','initial_exit_checkup_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 10);
        } 			
     //    if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
    		
    	// 	$condition += array(
    	// 		'MedicalCheckupRecord.status'	=> "Draft",

    	// 	);
    	// }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
    	// 		$condition += array(
    	// 		'MedicalCheckupRecord.status'	=> "Saved",

    	// 	);
    	// }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
    	// 		$condition += array(
    	// 		'MedicalCheckupRecord.status'	=> "Approved",

    	// 	);
    	// }	
    	// debug($condition);
		$this->paginate = array(
			'conditions'	=> $condition,
			'order'			=> array(
				'MedicalCheckupRecord.created'	=> 'DESC',
			),
		)+$limit;
		$datas = $this->paginate('MedicalCheckupRecord');
		$this->set(array(
			'datas'			=> $datas,
			'status'	=> $status,
			'prisoner_id'=>$prisoner_id,
			'uuid'			=> $uuid,
			'prison_id'	=> $prison_id,
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
			$prison_id_searchrecommend = '';

			if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
				$condition 		= array(
					'MedicalSickRecord.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
				);
			}
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
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
				'prison_id_searchrecommend'	=> $prison_id_searchrecommend,
			));
		//}

	}
	public function medicalSickData(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		$patient_type = '';
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalSickRecord.prisoner_id'		=> $prisoner_id,
				'MedicalSickRecord.is_trash'		=> 0,
			);
			$prison_id = '';

			if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
				$condition 		= array(
					'MedicalSickRecord.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
				);
			}
			if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
	            $prison_id = $this->params['named']['prison_id'];
	            $condition += array(
	            	"MedicalSickRecord.prison_id"=>$prison_id
	    			
	    		);
	        }

			// if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	  //           $status = $this->params['named']['status'];
	  //           $condition += array(
	  //               'MedicalSickRecord.status'   => $status,
	  //           );
	  //       }
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
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
	    	//debug($this->params['named']);
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	             $prisoner_id = $this->params['named']['prisoner_id'];
	             $prisoner_id = str_replace('-', '/', $prisoner_id);
	             //$prisoner_id_arr=explode("/",$prisoner_id);
	             //debug($prisoner_id_arr);
	             //$prisoner_id=(isset($prisoner_id_arr[1]))?$prisoner_id_arr[1]:'';
	            $condition += array(
	            	//"MedicalSickRecord.prisoner_id"=>(int)$prisoner_id
	            	"Prisoner.prisoner_no like '%".$prisoner_id."%'"
	    			
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
	        if(isset($this->params['named']['patient_type']) && $this->params['named']['patient_type'] != ''){
	            $patient_type = $this->params['named']['patient_type'];
	            $condition += array(
	            	"MedicalSickRecord.checkup_type"=>$patient_type
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
	            }else if($this->params['named']['reqType']=='PDF'){
	                $this->layout='pdf';
	                $this->set('file_type','pdf');
	                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.pdf');
	            }else if($this->params['named']['reqType']=='PRINT'){
					$this->layout='print';

				}
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 10);
	        } 		
	     //    if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
	    		
	    	// 	$condition += array(
	    	// 		'MedicalSickRecord.status'	=> "Draft",

	    	// 	);
	    	// }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
	    	// 		$condition += array(
	    	// 		'MedicalSickRecord.status'	=> "Saved",

	    	// 	);
	    	// }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
	    	// 		$condition += array(
	    	// 		'MedicalSickRecord.status'	=> "Approved",

	    	// 	);
	    	// }		
	    	 //debug($condition);
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalSickRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalSickRecord');
			//debug($datas);
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
				'prison_id'	=> $prison_id,
				'patient_type'=> $patient_type,
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
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
	            $limit = array('limit'  => 10);
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
		$prison_id = '';
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				'MedicalSeriousIllRecord.is_trash'		=> 0,
			);

			if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
				$condition 		+= array(
					'MedicalSeriousIllRecord.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
				);
			}

			if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
	            $prison_id = $this->params['named']['prison_id'];
	            $condition += array(
	                'MedicalSeriousIllRecord.prison_id'   => $prison_id,
	            );
	        }

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
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
	        if(isset($this->params['named']['prison_id_searchrecommend']) && $this->params['named']['prison_id_searchrecommend'] != ''){
	            $prison_id_searchrecommend = $this->params['named']['prison_id_searchrecommend'];
	            $condition += array(
	            	"MedicalSeriousIllRecord.hospital_id"=>$prison_id_searchrecommend
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
	            }else if($this->params['named']['reqType']=='PDF'){
	                $this->layout='pdf';
	                $this->set('file_type','pdf');
	                $this->set('file_name','medical_seriousill_report_'.date('d_m_Y').'.pdf');
	            }else if($this->params['named']['reqType']=='PRINT'){
					$this->layout='print';

				}
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 10);
	        } 		

	        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
	    		
	    		$condition += array(
	    			'MedicalSeriousIllRecord.status'	=> "Draft",

	    		);
	    	}else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
	    			$condition += array(
	    			'MedicalSeriousIllRecord.status'	=> "Saved",

	    		);
	    	}else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
	    			$condition += array(
	    			'MedicalSeriousIllRecord.status'	=> "Approved",

	    		);
	    	}	
	    	// debug($condition);
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
				'prison_id'	=> $prison_id,
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

	////////////////////////Medical Release Record start//////////////////////////////////////////////////

	public function showMedicalRelease(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		$prison_id = '';
		
			$condition 		= array(
				'MedicalRelease.is_trash'		=> 0,
			);

			if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
				$condition 		+= array(
					'MedicalRelease.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
				);
			}

			if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
	            $prison_id = $this->params['named']['prison_id'];
	            $condition += array(
	                'MedicalRelease.prison_id'   => $prison_id,
	            );
	        }

			if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	            $status = $this->params['named']['status'];
	            $condition += array(
	                'MedicalRelease.status'   => $status,
	            );
	        }
	        
	    	if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
	            $prisoner_id = $this->params['named']['prisoner_id'];
	             $prisoner_id = str_replace('-', '/', $prisoner_id);
	             $prisoner_id_arr=explode("/",$prisoner_id);
	             // debug($prisoner_id_arr);
	             if(count($prisoner_id_arr)>1){
		            $condition += array(
		            	"MedicalRelease.prisoner_id"=>(int)$prisoner_id_arr[1]
		    			
		    		);
	        	}
	        	else{
	        		$condition += array(
		            	"MedicalRelease.prisoner_id"=>(int)$prisoner_id_arr[0]
		    			
		    		);
	        	}
	        }
	        if(isset($this->params['named']['recommendation_from']) && $this->params['named']['recommendation_from'] != '' && isset($this->params['named']['recommendation_to']) && $this->params['named']['recommendation_to'] != ''){
	            $recommendation_from = $this->params['named']['recommendation_from'];
	            $recommendation_to = $this->params['named']['recommendation_to'];
	            $condition += array(
	            		"MedicalRelease.check_up_date between '".date("Y-m-d",strtotime($recommendation_from))."' and '".date("Y-m-d",strtotime($recommendation_to))."'"
	    			
	    			);
	            //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
	        }
	        if(isset($this->params['named']['priority_searched']) && $this->params['named']['priority_searched'] != ''){
	            $priority_searched = $this->params['named']['priority_searched'];
	            $condition += array(
	            	"MedicalRelease.priority"=>$priority_searched
	    			
	    		);
	        }
	        
	        if(isset($this->params['named']['prison_id_searchrecommend']) && $this->params['named']['prison_id_searchrecommend'] != ''){
	            $prison_id_searchrecommend = $this->params['named']['prison_id_searchrecommend'];
	            $condition += array(
	            	"MedicalRelease.hospital_id"=>$prison_id_searchrecommend
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
	    			'MedicalRelease.prisoner_id'	=> $prisonerListData["Prisoner"]["id"],
	    		);
	    	}
			if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
	            if($this->params['named']['reqType']=='XLS'){
	                $this->layout='export_xls';
	                $this->set('file_type','xls');
	                $this->set('file_name','medical_release_report_'.date('d_m_Y').'.xls');
	            }else if($this->params['named']['reqType']=='DOC'){
	                $this->layout='export_xls';
	                $this->set('file_type','doc');
	                $this->set('file_name','medical_release_report_'.date('d_m_Y').'.doc');
	            }else if($this->params['named']['reqType']=='PDF'){
	                $this->layout='pdf';
	                $this->set('file_type','pdf');
	                $this->set('file_name','medical_release_report_'.date('d_m_Y').'.pdf');
	            }else if($this->params['named']['reqType']=='PRINT'){
					$this->layout='print';

				}
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 10);
	        } 		

	        /*if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
	    		
	    		$condition += array(
	    			'MedicalRelease.status'	=> "Draft",

	    		);
	    	}else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
	    			$condition += array(
	    			'MedicalRelease.status'	=> "Saved",

	    		);
	    	}else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
	    			$condition += array(
	    			'MedicalRelease.status'	=> "Approved",

	    		);
	    	}*/	
	    	//$condition += array('MedicalRelease.is_trash'	=> 0,);
	    	 //debug($condition);
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalRelease.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalRelease');
			$this->set(array(
				'datas'			=> $datas,
				'status'	=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
				'prison_id'	=> $prison_id,
			));
		//}
	}
	public function deleteMedicalRelease(){
		$this->autoRender = false;
		if(isset($this->data['paramId'])){
			$uuid = $this->data['paramId'];
			$fields = array(
				'MedicalRelease.is_trash'	=> 1,
			);
			$conds = array(
				'MedicalRelease.uuid'	=> $uuid,
			);
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
			if($this->MedicalRelease->updateAll($fields, $conds)){
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


	//////////////////////////////Medical release record end/////////////////////////////////////////////////
	////////////////////////////////////////View Medical Release/////////////////////////////////////
	function getMedicalReleaseViewAjax(){
		$this->layout = 'ajax';
		$comm_name = $this->User->field('name',array('User.usertype_id'=>Configure::read('COMMISSIONERGENERAL_USERTYPE')));
		
			$condition 		= array(
				'MedicalRelease.is_trash'		=> 0,
			);

					
			if(isset($this->data['id']) && $this->data['id'] != ''){
	            $condition += array(
	            	"MedicalRelease.id"=>$this->data['id']
	    		);
	        }
	        /*if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
	    		
	    		$condition += array(
	    			'MedicalRelease.status'	=> "Draft",

	    		);
	    	}else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
	    			$condition += array(
	    			'MedicalRelease.status'	=> "Saved",

	    		);
	    	}else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
	    			$condition += array(
	    			'MedicalRelease.status'	=> "Approved",

	    		);
	    	}*/	
	    	//$condition += array('MedicalRelease.is_trash'	=> 0,);
	    	 //debug($condition);
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalRelease.modified'	=> 'DESC',
				),
			);
			$datas = $this->paginate('MedicalRelease');
			$this->set(array(
				'datas'			=> $datas,
				'comm_name'	=> $comm_name
			));
	}




	//////////////////////////////////////////////////////////////////////////////////////////////////

	public function showMedicalDeathRecordspdf(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		$prison_id="";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalDeathRecord.prisoner_id'		=> $prisoner_id,
				'MedicalDeathRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
	            $prison_id = $this->params['named']['prison_id'];
	            $condition += array(
	                'MedicalDeathRecord.prison_id'   => $prison_id,
	            );
	        }
			if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
				$condition 		= array(
					'MedicalSeriousIllRecord.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
				);
			}
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
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
				'prison_id'		=> $prison_id,
			));
		//}
	}
	public function showMedicalDeathRecords(){
		$this->layout = 'ajax';
		$status="";
		$prisoner_id="";
		$uuid="";
		$prison_id = "";
		// if(isset($this->params['named']['prisoner_id']) && (int)$this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
		// 	$prisoner_id 	= $this->params['named']['prisoner_id'];
		// 	$uuid 			= $this->params['named']['uuid'];
			$condition 		= array(
				//'MedicalDeathRecord.prisoner_id'		=> $prisoner_id,
				'MedicalDeathRecord.is_trash'		=> 0,
			);
			if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
	            $prison_id = $this->params['named']['prison_id'];
	            $condition += array(
	                'MedicalDeathRecord.prison_id'   => $prison_id,
	            );
	        }
			if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE')){
				$condition 		= array(
					'MedicalDeathRecord.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
				);
			}
			// if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
	  //           $status = $this->params['named']['status'];
	  //           $condition += array(
	  //               'MedicalDeathRecord.status'   => $status,
	  //           );
	  //       }

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
	        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
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
	             $condition += array(
	            	//"MedicalSickRecord.prisoner_id"=>(int)$prisoner_id
	            	"Prisoner.prisoner_no like '%".$prisoner_id."%'"
	    		);
	             //$prisoner_id_arr=explode("/",$prisoner_id);
	             // debug($prisoner_id_arr);
	       //       if(count($prisoner_id_arr)>1){
		      //       $condition += array(
		      //       	"MedicalDeathRecord.prisoner_id"=>(int)$prisoner_id_arr[1]
		    			
		    		// );
	       //  	}
	       //  	else{
	       //  		$condition += array(
		      //       	"MedicalDeathRecord.prisoner_id"=>(int)$prisoner_id_arr[0]
		    			
		    		// );
	       //  	}
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
	            }else if($this->params['named']['reqType']=='PDF'){
	                $this->layout='pdf';
	                $this->set('file_type','pdf');
	                $this->set('file_name','medical_death_report_'.date('d_m_Y').'.pdf');
	            }else if($this->params['named']['reqType']=='PRINT'){
					$this->layout='print';

				}
	            $this->set('is_excel','Y');         
	            $limit = array('limit' => 2000,'maxLimit'   => 2000);
	        }else{
	            $limit = array('limit'  => 10);
	        }

	       



	     //    if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
	    		
	    	// 	$condition += array(
	    	// 		'MedicalDeathRecord.status'	=> "Draft",

	    	// 	);
	    	// }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
	    	// 		$condition += array(
	    	// 		'MedicalDeathRecord.status'	=> "Saved",

	    	// 	);
	    	// }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
	    	// 		$condition += array(
	    	// 		'MedicalDeathRecord.status'	=> "Approved",

	    	// 	);
	    	// }	
	    	// debug($condition);
			$this->paginate = array(
				'conditions'	=> $condition,
				'order'			=> array(
					'MedicalDeathRecord.modified'	=> 'DESC',
				),
			)+$limit;
			$datas = $this->paginate('MedicalDeathRecord');
			//debug($datas);
			$this->set(array(
				'datas'			=> $datas,
				'status'		=> $status,
				'prisoner_id'	=> $prisoner_id,
				'uuid'			=> $uuid,
				'prison_id'		=> $prison_id,
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

	public function medicalSickReport(){
		$prison_id = $this->Session->read('Auth.User.prison_id');
        $status ='';

        $prisonerList = $this->Prisoner->find('list', array(           
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.fullname',
            ),
            'conditions'    => array(
                "Prisoner.prison_id"  => $prison_id,
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_approve'      => 1,
                'Prisoner.is_trash'       => 0,                
            ),
            'order'         => array(
                'Prisoner.prisoner_no',
            ),
        ));
        $prisonerNoList = $this->Prisoner->find('list', array(           
            'fields'        => array(
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                "Prisoner.prison_id"  => $prison_id,
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_approve'      => 1,
                'Prisoner.is_trash'       => 0,                
            ),
            'order'         => array(
                'Prisoner.prisoner_no',
            ),
        ));

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

        $this->set(array(
            'prisonerList'      => $prisonerList,
            'diseaseList'       => $diseaseList,
            'prisonerNoList'	=> $prisonerNoList
        ));
	}

public function medicalSickReportAjax(){
		$this->layout = 'ajax';
		$status = '';
		$prisoner_id = '';
		$uuid = '';
		$sick_checkup_from = '';
		$sick_checkup_to = '';
		$disease_id = '';
		$treatement_rx = '';
		
		$condition 		= array(
			//'MedicalSickRecord.prisoner_id'		=> $prisoner_id,
			'MedicalSickRecord.is_trash'		=> 0,
		);
		 //debug($this->params['named']);
    	if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_id = $this->params['named']['prisoner_no'];
             //echo $prisoner_id = str_replace('-', '/', $prisoner_id);
            // echo $prisoner_id_arr=explode("/",$prisoner_id);
            $condition += array(
            	"MedicalSickRecord.prisoner_id"=>(int)$prisoner_id
    			
    		);
        }
        if(isset($this->params['named']['prisoner_nos']) && $this->params['named']['prisoner_nos'] != ''){
            $prisoner_id = $this->params['named']['prisoner_nos'];
             //echo $prisoner_id = str_replace('-', '/', $prisoner_id);
            // echo $prisoner_id_arr=explode("/",$prisoner_id);
            $condition += array(
            	"MedicalSickRecord.prisoner_id"=>(int)$prisoner_id
    			
    		);
        }
        if(isset($this->params['named']['disease_id']) && $this->params['named']['disease_id'] != ''){
            $disease_id = $this->params['named']['disease_id'];
            $condition += array(
            	"MedicalSickRecord.disease_id"=>(int)$disease_id
    			
    		);
        }

        if(isset($this->params['named']['treatement_rx']) && $this->params['named']['treatement_rx'] != ''){
            $treatement_rx = $this->params['named']['treatement_rx'];
             //echo $prisoner_id = str_replace('-', '/', $prisoner_id);
            // echo $prisoner_id_arr=explode("/",$prisoner_id);
            $condition += array(
            	0=>"lower(MedicalSickRecord.treatement_rx) like '%".strtolower($treatement_rx)."%'"
    		);
        }

        if(isset($this->params['named']['sick_checkup_from']) && $this->params['named']['sick_checkup_from'] != '' && isset($this->params['named']['sick_checkup_to']) && $this->params['named']['sick_checkup_to'] != ''){
            $sick_checkup_from = $this->params['named']['sick_checkup_from'];
            $sick_checkup_to = $this->params['named']['sick_checkup_to'];
            $condition += array(
            		1=>"MedicalSickRecord.check_up_date between '".date("Y-m-d",strtotime($sick_checkup_from))."' and '".date("Y-m-d",strtotime($sick_checkup_to))."'"
    			
    			);
            //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
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
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.pdf');
			}else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 10);
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
			'prisoner_id'	=> $prisoner_id,
			'sick_checkup_from'	=> $sick_checkup_from,
			'sick_checkup_to'	=> $sick_checkup_to,
			'disease_id'	=> $disease_id,
			'treatement_rx'	=> $treatement_rx,
		));
	}

	function statePrison(){
		$this->loadModel('StateOfPrisoner');
		
		if(isset($this->data['StateOfPrisoner']) && count($this->data['StateOfPrisoner'])>0 && is_array($this->data['StateOfPrisoner'])){
			//debug($this->data);exit;
			$this->request->data['StateOfPrisoner']['prison_date'] = date("Y-m-d",strtotime($this->data['StateOfPrisoner']['prison_date']));
			$this->request->data['StateOfPrisoner']['prisoner_date'] = date("Y-m-d",strtotime($this->data['StateOfPrisoner']['prisoner_date']));
			$this->request->data['StateOfPrisoner']['prison_id'] = $this->Session->read('Auth.User.prison_id');
			$db = ConnectionManager::getDataSource('default');
			$db->begin();	
			if($this->StateOfPrisoner->saveAll($this->data)){
				$db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');	
				$this->redirect('/medicalRecords/prisonerStateList');
			}
			else 
            {
            	$db->rollback();
            	$this->Session->write('message_type','error');
            	$this->Session->write('message','Saving Failed !');
            }
		}
		if(isset($this->data['StateOfPrisonerEdit']) && count($this->data['StateOfPrisonerEdit']>0 && is_array($this->data['StateOfPrisonerEdit']))){
			//debug($this->data);exit;
			$this->request->data=$this->StateOfPrisoner->findById($this->data['StateOfPrisonerEdit']['id']);
		}
	}

function prisonerStateList(){
		$this->loadModel('StateOfPrisoner');
	}

function prisonerStateListAjax(){
		$this->layout = 'ajax';
		$this->loadModel('StateOfPrisoner');
		$prisoner_state='';
		$prison_state='';
		$condition 		= array(
			'StateOfPrisoner.is_trash'		=> 0,
			'StateOfPrisoner.prison_id'		=> $this->Session->read('Auth.User.prison_id'),
		);
		if(isset($this->params['named']['prisoner_state']) && $this->params['named']['prisoner_state'] != '')
		{
	        $prisoner_state=$this->params['named']['prisoner_state'];
	        $condition += array('StateOfPrisoner.prisoner_state' => $prisoner_state);
	    }
	    if(isset($this->params['named']['prison_state']) && $this->params['named']['prison_state'] != '')
		{
	        $prison_state=$this->params['named']['prison_state'];
	        $condition += array('StateOfPrisoner.prison_state' => $prison_state);
	    }
		$limit = array('limit'  => 20);		
		$this->paginate = array(
			'conditions'	=> $condition,
			'order'			=> array(
				'StateOfPrisoner.modified'	=> 'DESC',
			),
		)+$limit;
		$datas = $this->paginate('StateOfPrisoner');
		$this->set(array(
			'datas'			=> $datas,
			'prisoner_state'=> $prisoner_state,
			'prison_state'	=> $prison_state,
		));
	}


	function statePrisonDelete(){
		$this->loadModel('StateOfPrisoner');
		if(isset($this->data['StateOfPrisonerDelete']) && count($this->data['StateOfPrisonerDelete']>0 && is_array($this->data['StateOfPrisonerDelete']))){
			//debug($this->data);exit;
			$is_exists=$this->StateOfPrisoner->findById($this->data['StateOfPrisonerDelete']['id']);
			//debug($is_exists);exit;
			if(count($is_exists) >0){
			$is_exists['StateOfPrisoner']['is_trash']=1;
			$db = ConnectionManager::getDataSource('default');
			$db->begin();	
			if($this->StateOfPrisoner->save($is_exists)){
				$db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Deleted Successfully !');	
				$this->redirect('/medicalRecords/prisonerStateList');
			}
			else 
            {
            	$db->rollback();
            	$this->Session->write('message_type','error');
            	$this->Session->write('message','Deletion Failed !');
            }
			}
		}
	}

	// listing for process the discharge module
    public function gatepassList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('MedicalSeriousIllRecord.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('MedicalSeriousIllRecord.status !='=>'Draft');
            $condition      += array('MedicalSeriousIllRecord.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('MedicalSeriousIllRecord.status !='=>'Draft');
            $condition      += array('MedicalSeriousIllRecord.status !='=>'Saved');
            $condition      += array('MedicalSeriousIllRecord.status !='=>'Review-Rejected');
            $condition      += array('MedicalSeriousIllRecord.status'=>'Reviewed');
        }   
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
                $status = $this->setGatepass($items, 'MedicalSeriousIllRecord',$gatepassDetails);
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
                $this->redirect('gatepassList');
            }
        }
        $prisonerListData = $this->MedicalSeriousIllRecord->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "MedicalSeriousIllRecord.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'MedicalSeriousIllRecord.prison_id'        => $this->Auth->user('prison_id')
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
            'prisonerListData'  => $prisonerListData,
            'sttusListData'     => $statusList,
            'default_status'    => $default_status
        ));
    }

    public function gatepassListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';

        $this->loadModel('EscortTeam');
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
                'EscortTeam.escort_type'  => "Hospital",                
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));
        
        $condition              = array(
            'MedicalSeriousIllRecord.status'      => 'Approved',
            'MedicalSeriousIllRecord.is_trash'      => 0,
            'MedicalSeriousIllRecord.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'MedicalSeriousIllRecord.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.doc');
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
                'MedicalSeriousIllRecord.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('MedicalSeriousIllRecord');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
            'teamList'    		=> $teamList
        ));
    }

    // function getPrisonerDetails($id){
    //     $this->Prisoner->recursive = -1;
    //     return $this->Prisoner->findById($id);
    // }

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
            		$data[$i]['Gatepass']			= $gatepassDetails;
            		$data[$i]['Gatepass']['gp_date']	= date("Y-m-d", strtotime($gatepassDetails['gp_date']));
		            $data[$i]['Gatepass']['gp_no']	= "GP-".str_pad($this->Session->read('Auth.User.prison_id'),3,"0",STR_PAD_LEFT)."-".str_pad($recordCount,5,"0",STR_PAD_LEFT);
		            $uuidArr = $this->Gatepass->query("select uuid() as code");
            		$data[$i]['Gatepass']['uuid']		= $uuidArr[0][0]['code'];
		            
            		$data[$i]['Gatepass']['prison_id']	= $prison_id;
	                $data[$i]['Gatepass']['model_name']	= $model;
	                $data[$i]['Gatepass']['user_id']	= $login_user_id;
	                $data[$i]['Gatepass']['reference_id'] = $item['fid'];	                
	                $data[$i]['Gatepass']['gatepass_type'] = 'Medical Release';	     
	                $dischargeData = $this->MedicalSeriousIllRecord->findById($item['fid']);           
	                $data[$i]['Gatepass']['prisoner_id'] = $dischargeData['MedicalSeriousIllRecord']['prisoner_id'];
	                $notificationPrisoner[] = $dischargeData['MedicalSeriousIllRecord']['prisoner_id'];
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
                    		"conditions"	=> array(
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
			                foreach ($userList as $key => $value) {
			                    $this->addNotification(array(
			                        "user_id"   => $key,
			                        "content"   => "Gatepass generated for the prisoner(s) ".implode(", ", $prisonerName),
			                        "url_link"   => "/Gatepass/gatepassList",
			                    ));
			                }
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

    function medicalPdf($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
		    $this->loadModel('PrisonerSentence');
		    $this->loadModel('PrisonerKinDetail');
            $deathData = $this->MedicalDeathRecord->findByPrisonerId($prisoner_id);
            // $this->PrisonerSentence->recursive = -1;
            $sentanceData = $this->PrisonerSentence->findByPrisonerId($prisoner_id);
            $prisonerKinDetail = $this->PrisonerKinDetail->find("first",array(
            	"recursive"	=> -1,
            	"conditions"	=> array(
            		"PrisonerKinDetail.prisoner_id"	=> $prisoner_id,
            		"PrisonerKinDetail.status"	=> 'Approved',
            	),
            ));
            // debug($prisonerKinDetail);
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF22";
            $dataArray = $deathData['Prisoner'] + $deathData['MedicalDeathRecord'];
            // debug($dataArray);exit;
            

            $variables = array();
            $variables = $dataArray;
           
            $variables['check_up_date'] = date("d-m-Y h:i A", strtotime($variables['check_up_date']));
            $variables['prison_name'] = $this->getName($variables['prison_id'],"Prison","name");
            
            $variables['officer_incharge'] = '';
            $variables['section_of_law'] = '';//$sentanceData['PrisonerSentence']['section_of_law'];
            $variables['offence'] = '';
            if(isset($sentanceData['PrisonerSentence']['offence']) && $sentanceData['PrisonerSentence']['offence']!=''){
            	foreach (explode(",", $sentanceData['PrisonerSentence']['offence']) as $key => $value) {
            		$variables['offence'] .= $this->getName($value,"Offence","name").",";
            	}
            }
            
            $variables['sentence'] = '';//$sentanceData['SentenceOf']['name'];
            $variables['case_file_no'] = '';//$sentanceData['PrisonerSentence']['case_file_no'];
            $variables['crb_no'] = '';//$sentanceData['PrisonerSentence']['crb_no'];

            $variables['place_of_offence'] = $sentanceData['PrisonerSentence']['place_of_offence'];
            $variables['address_of_kin'] = (isset($prisonerKinDetail['PrisonerKinDetail']['physical_address']) && $prisonerKinDetail['PrisonerKinDetail']['physical_address']!='') ? $prisonerKinDetail['PrisonerKinDetail']['physical_address'] : '';
            $variables['date_of_committal'] = date("d-m-Y", strtotime($sentanceData['PrisonerSentence']['date_of_committal']));
            $variables['medical_officer'] = $this->Auth->user('name');

            
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $templateUrl2 = $baseURL."app/webroot/forms/PF21";

            $template2 = file_get_contents($templateUrl2);

            foreach($variables as $key => $value)
            {
                $template2 = str_replace('{'.$key.'}', $value, $template2);
            }
            
           // exit;
           $userData = $this->User->find("list", array(
                "conditions"    => array(
                    "User.usertype_id IN (".Configure::read('RECEPTIONIST_USERTYPE').",".Configure::read('MEDICALOFFICE_USERTYPE').Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('COMMISSIONERGENERAL_USERTYPE').")",
                    "User.prison_id"    => $variables['prison_id'],
                ),
                "fields"	=> array(
                	"User.mail_id",
                	"User.mail_id",
                ),
            ));
            // print_r($userData);
            $prisonerNo = $this->Prisoner->field("prisoner_no",array("id"=>$prisoner_id));
            // echo $template;
            $fileDeath = $this->htmlToMedicalPdf($template,"death1".$prisoner_id.".pdf");
            $fileDeath21 = $this->htmlToMedicalPdf($template2,"death21".$prisoner_id.".pdf");
            if(isset($userData) && count($userData)>0){
            	$email = new CakeEmail('smtp');
	            $email->to($userData);
	            $email->from("itishree.behera@lipl.in");
	            $email->emailFormat('html');
	            $email->subject('Prisoner Death('.$prisonerNo.')');
	            $email->attachments(array(1 => WWW_ROOT.DS.$fileDeath,2 => WWW_ROOT.DS.$fileDeath21));
	            // $email->viewVars(array('key'=>$key,'id'=>$id,'rand'=> mt_rand()));
	            // $email->template('reset');
	            try {
	            	$email->send('<p>Dear All</p><p></p><p>Please find attached file for prisoner death</p>');
	            	return true;
	            }catch(Exception $e) {
	            	// echo 'Message: ' .$e->getMessage();
	            }
            }
            
            // exit;
        }
        else 
        {
            return false;
        } 
    }

    function htmlToMedicalPdf($html, $file_name='')
    {
        if($html != '')
        {
            //echo $html; exit;
            App::import('Vendor','xtcpdf');
            $pdf = new XTCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); 
            $pdf->SetCreator(PDF_CREATOR);
            error_reporting(0);
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, false, false, '');
     
            $pdf->lastPage();
            $prisoner_id = '';
            if(empty($file_name))
                $file_name = 'report_'.time().'_'.rand().'.pdf';
             
            $pdf->Output(APP.'webroot/files/pdf'.DS.$file_name, 'F');
            return 'files/pdf/'.$file_name;
        }
    }

    function medicalPdfDownload($prisoner_id)
    {
    	Configure::read('debug',0);
        if(!empty($prisoner_id))
        {
        	// $this->layout="print";
      //$this->Discharge->bindModel(
		    //     array('belongsTo' => array(
		    //             'Prisoner' => array(
		    //                 'className' => 'Prisoner'
		    //             )
		    //         )
		    //     )
		    // );
		    $this->loadModel('PrisonerSentence');
		    $this->loadModel('PrisonerKinDetail');
		    // debug($this->Prisoner->field("prisoner_no",array("id"=>$prisoner_id)));
		    
            $deathData = $this->MedicalDeathRecord->findByPrisonerId($prisoner_id);
            // $this->PrisonerSentence->recursive = -1;
            $sentanceData = $this->PrisonerCaseFile->findByPrisonerId($prisoner_id);
            // debug();
            $prisonerKinDetail = $this->PrisonerKinDetail->find("first",array(
            	"recursive"		=> -1,
            	"conditions"	=> array(
            		"PrisonerKinDetail.prisoner_id"	=> $prisoner_id,
            		"PrisonerKinDetail.status"	=> 'Approved',
            	),
            ));
            // debug($prisonerKinDetail);
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF22";
            $dataArray = $deathData['Prisoner'] + $deathData['MedicalDeathRecord'];
            // debug($dataArray);exit;
            

            $variables = array();
            $variables = $dataArray;
           
            $variables['check_up_date'] = date("d-m-Y h:i A", strtotime($variables['check_up_date']));
            $variables['prison_name'] = $this->getName($variables['prison_id'],"Prison","name");
            
            $variables['officer_incharge'] = '';
            $variables['section_of_law'] = '';//$sentanceData['PrisonerSentence']['section_of_law'];
            $variables['offence'] = '';
            $variables['place_of_offence'] = '';
            if(isset($sentanceData['PrisonerOffence']) && $sentanceData['PrisonerOffence']!=''){
            	foreach ($sentanceData['PrisonerOffence'] as $key => $value) {
            		$variables['offence'] .= $this->getName($value['offence'],"Offence","name").",";
            		$variables['place_of_offence'] .= $value['place_of_offence'];
            	}
            }
            $sentanceDetails = '';
            $sentanceLength = $this->Prisoner->field("sentence_length",array("id"=>$prisoner_id));
            $lpd = (isset($sentanceLength) && $sentanceLength!='') ? json_decode($sentanceLength) : array();
                $remission = array();
                if(isset($lpd) && count((array)$lpd)>0){
                    foreach ($lpd as $key => $value) {
                        if($key == 'days'){
                            $remission[2] = $value." ".$key;
                        }
                        if($key == 'years'){
                            $remission[0] = $value." ".$key;
                        }
                        if($key == 'months'){
                            $remission[1] = $value." ".$key;
                        }                        
                    }
                    ksort($remission);
                    $sentanceDetails = implode(", ", $remission); 
                } 
            $variables['sentence'] = $sentanceDetails;
            $variables['case_file_no'] = (isset($sentanceData['PrisonerCaseFile']['case_file_no']) && $sentanceData['PrisonerCaseFile']['case_file_no']!='') ? $sentanceData['PrisonerCaseFile']['case_file_no'] : '';
            $variables['crb_no'] = (isset($sentanceData['PrisonerCaseFile']['crb_no']) && $sentanceData['PrisonerCaseFile']['crb_no']!='') ? $sentanceData['PrisonerCaseFile']['crb_no'] : '';

            
            // address_of_kin
            $variables['address_of_kin'] = (isset($prisonerKinDetail['PrisonerKinDetail']['physical_address']) && $prisonerKinDetail['PrisonerKinDetail']['physical_address']!='') ? $prisonerKinDetail['PrisonerKinDetail']['physical_address'] : '';
            $date_of_conviction = $this->PrisonerSentence->field("date_of_conviction",array("prisoner_id"=>$prisoner_id));
            $variables['date_of_committal'] = (isset($date_of_conviction) && $date_of_conviction!='') ? date("d-m-Y", strtotime($this->PrisonerSentence->field("date_of_conviction",array("prisoner_id"=>$prisoner_id)))) : '';
            $variables['medical_officer'] = $this->Auth->user('name');

            
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $templateUrl2 = $baseURL."app/webroot/forms/PF21";

            $template2 = file_get_contents($templateUrl2);

            foreach($variables as $key => $value)
            {
                $template2 = str_replace('{'.$key.'}', $value, $template2);
            }
            
           // exit;
           $userData = $this->User->find("list", array(
                "conditions"    => array(
                    "User.usertype_id IN (".Configure::read('RECEPTIONIST_USERTYPE').",".Configure::read('MEDICALOFFICE_USERTYPE').Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('COMMISSIONERGENERAL_USERTYPE').")",
                    "User.prison_id"    => $variables['prison_id'],
                ),
                "fields"	=> array(
                	"User.mail_id",
                	"User.mail_id",
                ),
            ));
            // print_r($userData);
            $prisonerNo = $this->Prisoner->field("prisoner_no",array("id"=>$prisoner_id));
            // echo $template;exit;
            echo  $this->htmlToPdf($template, "death1".$prisoner_id.".pdf");
            // echo $template;
            // $fileDeath = $this->htmlToMedicalPdf($template,"death1".$prisoner_id.".pdf");
            // $fileDeath21 = $this->htmlToMedicalPdf($template2,"death21".$prisoner_id.".pdf");
            // if(isset($userData) && count($userData)>0){
            // 	$email = new CakeEmail('smtp');
	           //  $email->to($userData);
	           //  $email->from("itishree.behera@lipl.in");
	           //  $email->emailFormat('html');
	           //  $email->subject('Prisoner Death('.$prisonerNo.')');
	           //  $email->attachments(array(1 => WWW_ROOT.DS.$fileDeath,2 => WWW_ROOT.DS.$fileDeath21));
	           //  // $email->viewVars(array('key'=>$key,'id'=>$id,'rand'=> mt_rand()));
	           //  // $email->template('reset');
	           //  try {
	           //  	$email->send('<p>Dear All</p><p></p><p>Please find attached file for prisoner death</p>');
	           //  	return true;
	           //  }catch(Exception $e) {
	           //  	// echo 'Message: ' .$e->getMessage();
	           //  }
            // }
            
            // exit;
        }
        else 
        {
            return false;
        } 
    }

    public function getBmiInfo(){
   		$this->autoRender = false;
   		$this->loadModel('BmiClassification'); 
   		// debug($this->request->data);
   		if(isset($this->request->data['height']) && $this->request->data['height']!='' && isset($this->request->data['weight']) && $this->request->data['weight']!=''){
   			$height = $this->Height->field("name",array("Height.id"=>$this->request->data['height']));
	   		$weight = $this->request->data['weight'];

	   		$bmiValue = round(($weight/($height * $height)) * 10000 , 2);

	   		$classificationBmi = $this->BmiClassification->find('first', array(
					'conditions'	=> array(
						'BmiClassification.max_value > ' => $bmiValue,
						'BmiClassification.min_value < ' => $bmiValue,
					),
				));
	   		if(isset($classificationBmi) && count($classificationBmi)>0){
	   			echo $bmiValue,"*****".$classificationBmi['BmiClassification']['name'];exit;
	   		}else{
	   			echo "FAIL";exit;
	   		}
   		}else{
   			echo "FAIL";exit;
   		}
    }

    public function restoreWard(){    	
   		$this->autoRender = false;
   		$id = $this->data['id'];
   		$this->loadModel('BmiClassification'); 
   		if(isset($id) && $id!=''){
   			$ward_prisoner_id = $this->MedicalSickRecord->field("prisoner_id", array("MedicalSickRecord.id"=>$id));
   			$historyWardId = $this->MedicalSickRecord->field("ward_id", array("MedicalSickRecord.id"=>$id));
   			$historyWardCellId = $this->MedicalSickRecord->field("ward_cell_id", array("MedicalSickRecord.id"=>$id));

   			$historyWard = $this->PrisonerWardHistory->field("ward_id", array(
   					"PrisonerWardHistory.prisoner_id"	=> $ward_prisoner_id,
   					"PrisonerWardHistory.ward_id !="	=> $historyWardId,
   					"PrisonerWardHistory.ward_cell_id !="	=> $historyWardCellId,
   				),"PrisonerWardHistory.id desc");
        	$wardData["Prisoner"]["id"] = $ward_prisoner_id;
            $wardData["Prisoner"]["assigned_ward_id"] =  $historyWardId;
            $wardData["Prisoner"]["assigned_ward_cell_id"] =  $historyWardCellId;

            $wardHistory = array();
            $wardData["PrisonerWardHistory"]["prison_id"] = $this->Session->read('Auth.User.prison_id');
            $wardData["PrisonerWardHistory"]["prisoner_id"] = $ward_prisoner_id;
            $wardData["PrisonerWardHistory"]["ward_id"] = $historyWardId;
            $wardData["PrisonerWardHistory"]["ward_cell_id"] = $historyWardCellId;
            // debug($wardData);exit;
            if($this->Prisoner->save($wardData))
            {
            	$this->auditLog('Prisoner','prisoners',$ward_prisoner_id, 'update', json_encode($wardData["Prisoner"]));
                if($this->PrisonerWardHistory->save($wardData)){
                	$this->MedicalSickRecord->updateAll(array("MedicalSickRecord.is_discharge"=>"'yes'"),array("MedicalSickRecord.id"=>$id));
                    $this->auditLog('PrisonerWardHistory','prisoner_ward_histories',$ward_prisoner_id, 'insert', json_encode($wardData["PrisonerWardHistory"]));
                    $notification_msg = "Prisoner no ".$this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$ward_prisoner_id))." is discharged from hospital and assigned ward ".$this->getName($historyWardId,"Ward","name")." and cell ".$this->getName($historyWardCellId,"WardCell","cell_name");
                    $usertypes = array(
		                Configure::read('RECEPTIONIST_USERTYPE'),
		                Configure::read('PRINCIPALOFFICER_USERTYPE'),
		                Configure::read('OFFICERINCHARGE_USERTYPE')
		            );
		            $usertypes = implode(',',$usertypes);
		            $userList = $this->User->find("list", array(
	                    'fields'        => array(
	                        'User.id',
	                        'User.name',
	                    ),
	                    'conditions'    => array(
	                        'User.is_enable'	=> 1,
	                        'User.is_trash'		=> 0,
	                        'User.prison_id'	=> $this->Session->read('Auth.User.prison_id'),
	                        'User.usertype_id IN ('.$usertypes.')'
	                    )
	                ));
	                
	                $url_link = '#';
	                // debug($userList);
	                $this->addManyNotification($userList, $notification_msg, $url_link);
	                echo "SUCC";exit;
                }else{
	            	echo "FAIL";exit;
	            }
            }else{
            	echo "FAIL";exit;
            }
   		}else{
   			echo "FAIL";exit;
   		}
    }

    public function getPrisonerSentence($prisoner_id){
        $this->loadModel('PrisonerSentence');
        $solList = $this->PrisonerSentence->find('first', array(
                'conditions'    => array(
                    'PrisonerSentence.prisoner_id'     => $prisoner_id,
                ),
            ));
        return $solList;
    }

    public function showWardCell() {
        $this->autoRender = false;
        $this->loadModel("WardCell");
        if(isset($this->data['assigned_ward_id']) && (int)$this->data['assigned_ward_id'] != 0){
            $disabilityList = $this->WardCell->find('list', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'WardCell.ward_id '=> $this->data['assigned_ward_id'],

                ),
                'fields'        => array(
                    'WardCell.id',
                    'WardCell.cell_name',
                ),
               
                'order'         => array(
                    'WardCell.cell_name'
                ),
            ));
            if(is_array($disabilityList) && count($disabilityList)>0){
                echo '<option value="">-- Select Cell --</option>';
                foreach($disabilityList as $disabilityListKey=>$disabilityListVal){
                    echo '<option value="'.$disabilityListKey.'">'.$disabilityListVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select Cell --</option>';
            }
        }else{
            echo '<option value="">-- Select Cell --</option>';
        }

    }

    public function mentalCases() {
    	$prison_id = $this->Session->read('Auth.User.prison_id');
    	
		$prisonerListData = $this->Prisoner->find('list', array(
			'joins' => array(
                array(
                'table' => 'medical_checkup_records',
                'alias' => 'MedicalCheckupRecord',
                'type' => 'inner',
                 'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
                ),
            ),
			'fields'		=> array(
				'Prisoner.id',
				'Prisoner.prisoner_no',
			),
			'conditions'	=> array(
				'Prisoner.is_trash'				=> 0,
				'Prisoner.is_enable'			=> 1,
				'Prisoner.present_status'		=> 1,
				'Prisoner.is_death'				=> 0,
				'MedicalCheckupRecord.check_up'	=> 'Intial',
				'MedicalCheckupRecord.status'	=> 'Approved',
				'Prisoner.transfer_status !='	=> 'Approved',
				'Prisoner.prison_id'			=> $prison_id
			),
		));
		$mentalcasesList=array("Yes"=>"Yes","No"=>"No");
		$mentalcheckList=array("Certified"=>"Certified","Under Observation"=>"Under Observation");

		 if($this->request->is(array('post','put')) && isset($this->data['MentalCase']) && is_array($this->data['MentalCase']) && count($this->data['MentalCase']) >0){

		 	if(isset($this->data['MentalCase']['date']) && $this->data['MentalCase']['date'] != ''){
							$check_up_date = date("Y-m-d",strtotime($this->request->data['MentalCase']['date']));
							$this->request->data['MentalCase']['date'] = $check_up_date;
						}	
		 	//debug($this->data); exit;
            $db = ConnectionManager::getDataSource('default');
            $db->begin();         
            $this->loadModel('MentalCase');    
            if($this->MentalCase->save($this->request->data)){
                if(isset($this->data['MentalCase']['id']) && (int)$this->data['MentalCase']['id'] != 0){
                    if($this->auditLog('MentalCase', 'MentalCase', $this->data['MentalCase']['id'], 'Update', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'mentalCaseList'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }else{
                    if($this->auditLog('MentalCase', 'MentalCase', $this->MentalCase->id, 'Add', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'mentalCaseList'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
		$this->set(array(
					'prisonerListData'		=> $prisonerListData,
					'mentalcasesList'       => $mentalcasesList,
					'mentalcheckList'       => $mentalcheckList,
					
					
				));


    }
    public function mentalCaseList() {
    	$prison_id = $this->Session->read('Auth.User.prison_id');
    	$prisonerListData = $this->Prisoner->find('list', array(
			'joins' => array(
                array(
                'table' => 'medical_checkup_records',
                'alias' => 'MedicalCheckupRecord',
                'type' => 'inner',
                 'conditions'=> array('MedicalCheckupRecord.prisoner_id = Prisoner.id')
                ),
            ),
			'fields'		=> array(
				'Prisoner.id',
				'Prisoner.prisoner_no',
			),
			'conditions'	=> array(
				'Prisoner.is_trash'				=> 0,
				'Prisoner.is_enable'			=> 1,
				'Prisoner.present_status'		=> 1,
				'Prisoner.is_death'				=> 0,
				'MedicalCheckupRecord.check_up'	=> 'Intial',
				'MedicalCheckupRecord.status'	=> 'Approved',
				'Prisoner.transfer_status !='	=> 'Approved',
				'Prisoner.prison_id'			=> $prison_id
			),
		));
		$this->set(array(
					'prisonerListData'		=> $prisonerListData,
					
				));


    }
    public function mentalCaseAjax() {
      $prison_id = $this->Session->read('Auth.User.prison_id');
      $this->layout = 'ajax';
      $this->loadModel('MentalCase');
      $prison_no = "";
      $condition      = array('MentalCase.prison_id' => $prison_id);
     
      if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
          $prison_no = $this->params['named']['prisoner_id'];
          $condition += array('MentalCase.prisoner_id' => $prison_no);
      }
     //  debug($this->params['named']);
     // debug($condition);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.pdf');
          }
			$this->set('is_excel','Y');
			$limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        $this->paginate=array(
         'conditions'=>$condition,
         'order'=>array(
         	'id'=>'DESC'
         )
       );      
		
		
		  $datas = $this->paginate('MentalCase');
			//debug($datas); exit;
		  $this->set(array(
          'datas'          => $datas,
          
      ));

    }

    public function urbanLabour() {
    	$this->loadModel('Prisoner');
    $this->loadModel('UnfitHistory');
    $prisonerList = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                'joins'         => array(
                    array(
                        'table'         => 'unfit_histories',
                        'alias'         => 'UnfitHistory',
                        'type'          => 'inner',
                        'conditions'    => array('UnfitHistory.prisoner_id = Prisoner.id')
                    ),
                ),
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.is_enable'  => 1,
                    'Prisoner.is_trash'   => 0,
                    'Prisoner.prison_id'  => $this->Session->read('Auth.User.prison_id'),
                ),
                'order'         => array(
                    'Prisoner.prisoner_no'       => 'ASC',
                ),
            ));
    $this->set(array(
                'prisonerList'         => $prisonerList,
            ));

    }
   
    public function urbanLabourListAjax() {
    	$this->layout = 'ajax';
    	$this->loadModel('UnfitHistory');
      $prisoner_id='';
      $from_date      = '';
      $to_date        = "";
      $condition=array(
      	 "UnfitHistory.prison_id"	=> $this->Session->read('Auth.User.prison_id'),
      );
      if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
          $prisoner_id = $this->params['named']['prisoner_id'];
          $condition += array('UnfitHistory.prisoner_id' => $prisoner_id );
      }
      if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
          $from_date = $this->params['named']['from_date'];
          $fd=explode('-',$from_date);
          $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
          $condition += array("UnfitHistory.from_date >=" => $fd);
      }
      if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
          $to_date = $this->params['named']['to_date'];
          $td=explode('-',$to_date);
          $td=$td[2].'-'.$td[1].'-'.$td[0];
          $condition += array("UnfitHistory.to_date <=" => $td);
      }
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','restricted_prisoner_'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','restricted_prisoner_'.date('d_m_Y').'.doc');
          }
          $this->set('is_excel','Y');
      }
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'UnfitHistory.modified1'   => 'DESC',
                ),
            );
            //debug($condition);
            $datas = $this->paginate('UnfitHistory');
            //debug($datas);
            $this->set(array(
                'datas'        => $datas,
                'prisoner_id'  => $prisoner_id,
                'from_date'    => $from_date,
                'to_date'      => $to_date
            ));
    }
    public function prisonerAgeVerification() {
    	// $this->loadModel('Prisoner');

		 	if($this->request->is(array('post','put')) && isset($this->data['PrisonerAgeVerification']) && is_array($this->data['PrisonerAgeVerification']) && count($this->data['PrisonerAgeVerification']) >0){
			// debug($this->data['PrisonerAgeVerification']); exit;
            $db = ConnectionManager::getDataSource('default');
            $db->begin();       
            $this->loadModel('PrisonerAgeVerification');      
            if($this->PrisonerAgeVerification->save($this->request->data)){
                if(isset($this->data['PrisonerAgeVerification']['id']) && (int)$this->data['PrisonerAgeVerification']['id'] != 0){
                    if($this->auditLog('PrisonerAgeVerification', 'PrisonerAgeVerification', $this->data['PrisonerAgeVerification']['id'], 'Update', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Age Verified Successfully !');
                        // $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }else{
                    if($this->auditLog('PrisonerAgeVerification', 'PrisonerAgeVerification', $this->PrisonerAgeVerification->id, 'Add', json_encode($this->data))){
                        $db->commit(); 
                        $prisoner_id = $this->request->data['PrisonerAgeVerification']['prisoner_id'];
                        $prisoner_no = $this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$prisoner_id));
                        // notification start
                        $notification_msg = "Age is verified for prisoner no ".$prisoner_no." and age is ".$this->request->data['PrisonerAgeVerification']['age'];
			                            $usertypes = array(
							                Configure::read('RECEPTIONIST_USERTYPE'),
							                Configure::read('PRINCIPALOFFICER_USERTYPE'),
							                Configure::read('OFFICERINCHARGE_USERTYPE')
							            );
							            $usertypes = implode(',',$usertypes);
							            $userList = $this->User->find("list", array(
						                    'fields'        => array(
						                        'User.id',
						                        'User.name',
						                    ),
						                    'conditions'    => array(
						                        'User.is_enable'	=> 1,
						                        'User.is_trash'		=> 0,
						                        'User.prison_id'	=> $this->Session->read('Auth.User.prison_id'),
						                        'User.usertype_id IN ('.$usertypes.')'
						                    )
						                ));
						                
						                $url_link = '#';
						                // debug($userList);
						                $this->addManyNotification($userList, $notification_msg, $url_link);
                        // notification end
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Age Verified Successfully!');
                        // $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
    }

	     // debug($condition); exit();

	     $prisonerListname = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'=> array(
                    	'Prisoner.suspect_on_age'=>1,
                    ),

                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
	     $prisonListname = $this->Prison->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prison.id',
                        'Prison.name'
                    ),
                    
                    'order'=>array(
                        'Prison.id'
                    )
                ));
	     $this->set(array(
			
			'prisonerListname'  => $prisonerListname,
			'prisonListname'    => $prisonListname,
		
		));

    }
    public function prisonerAgeVerificationAjax() {
    	$this->layout = 'ajax';
		$this->loadModel('Prisoner');
		// $this->loadModel('Prison');
		$prisoner_state='';
		$prison_state='';
		 $condition = array('Prisoner.suspect_on_age'=>1);
		 if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != '')
		 {
	        $prisoner_no=$this->params['named']['prisoner_no'];
	         $condition += array('Prisoner.id' => $prisoner_no);
	     }
	     // debug($this->params['named']);
	     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '')
		 {
	        $prison=$this->params['named']['prison_id'];
	         $condition += array('Prisoner.prison_id' => $prison);
	     }
		 $verify_age = array("OFF Age"=>"OFF Age","Under Age"=>"Under Age");
		 $limit = array('limit'  => 20);		
		$this->paginate = array(
			'recursive' => -1,
			"joins" => array(
                array(
                    "table" => "prisoner_age_verifications",
                    "alias" => "PrisonerAgeVerification",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.id = PrisonerAgeVerification.prisoner_id"
                    ),
                ),
            ),
			'fields'=> array(
                    'Prisoner.*',
                    'PrisonerAgeVerification.photo'

                ),
			'conditions'	=> $condition,
			
		)+$limit;
		// debug($condition);
		$datas = $this->paginate('Prisoner');
		// debug($datas); exit;
		$this->set(array(
			'datas'			=> $datas,
			'verify_age'    => $verify_age,
			// 'prisonerList'  => $prisonerList,
		
		));


    }
   public  function isVerifyAge($prisoner_id)
    {
        $result = 0;
        if(!empty($prisoner_id))
        {
        	$this->loadModel('PrisonerAgeVerification');
            $result   = $this->PrisonerAgeVerification->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerAgeVerification.prisoner_id'  => $prisoner_id
                )
            ));
        }
        return $result;
    }
    
}