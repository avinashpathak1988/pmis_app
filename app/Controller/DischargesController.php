<?php
App::uses('AppController', 'Controller');
class DischargesController    extends AppController {
	public $layout='table';
	public $uses=array('Prisoner', 'Discharge', 'DischargeType','DeathInCustody', 'DischargeEscape','PrisonerSentenceDetail','PrisonerChildDetail','Gatepass','MedicalCheckupRecord','PhysicalPropertyItem','PrisonerSaving','PropertyTransaction');
	
	public function index($uuid) {
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
			
			if(isset($prisonerData['Prisoner']['id']) && (int)$prisonerData['Prisoner']['id'] != 0){
				/**
				 *Query for for getting the discharge details record
				 */
				$dischargeData = $this->Discharge->find('count', array(
					'recursive'		=> -1,
					'conditions'	=> array(
						'Discharge.prisoner_id'		=> $prisonerData['Prisoner']['id'],
						'Discharge.is_trash'		=> 0,
						"Discharge.status NOT IN ('Review-Rejected','Approve-Rejected')",
					),
				));
				$courtList 		= array();
				$prisoner_id 	= $prisonerData['Prisoner']['id'];
				/*
				 *Code for add the prisoner discharge records
				*/					
				if(isset($this->data['Discharge']) && is_array($this->data['Discharge']) && count($this->data['Discharge']) >0){
					if(isset($this->data['Discharge']['discharge_date']) && $this->data['Discharge']['discharge_date'] != ''){
						$this->request->data['Discharge']['discharge_date'] = date('Y-m-d', strtotime($this->data['Discharge']['discharge_date']));
					}
					if(isset($this->data['Discharge']['escape_date']) && $this->data['Discharge']['escape_date'] != ''){
					    $this->request->data['Discharge']['escape_date'] = date('Y-m-d H:i', strtotime($this->data['Discharge']['escape_date']));
					}
					if(isset($this->data['Discharge']['execution_date']) && $this->data['Discharge']['execution_date'] != ''){
					    $this->request->data['Discharge']['execution_date'] = date('Y-m-d H:i', strtotime($this->data['Discharge']['execution_date']));
					}
					if(isset($this->data['Discharge']['bail_date']) && $this->data['Discharge']['bail_date'] != ''){
						$this->request->data['Discharge']['bail_date'] = date('Y-m-d', strtotime($this->data['Discharge']['bail_date']));
					}
					if(isset($this->data['Discharge']['end_bail_date']) && $this->data['Discharge']['end_bail_date'] != ''){
						$this->request->data['Discharge']['end_bail_date'] = date('Y-m-d', strtotime($this->data['Discharge']['end_bail_date']));
					}
					if(isset($this->data['Discharge']['epd']) && $this->data['Discharge']['epd'] != ''){
						$this->request->data['Discharge']['epd'] = date('Y-m-d', strtotime($this->data['Discharge']['epd']));
					}
					if(isset($this->data['Discharge']['death_warrant']) && $this->data['Discharge']['death_warrant'] != ''){
						$this->request->data['Discharge']['death_warrant'] = date('Y-m-d', strtotime($this->data['Discharge']['death_warrant']));
					}
					if(isset($this->data['Discharge']['lpd']) && $this->data['Discharge']['lpd'] != ''){
						$this->request->data['Discharge']['lpd'] = date('Y-m-d', strtotime($this->data['Discharge']['lpd']));
					}
					if(isset($this->data['Discharge']['uuid']) && $this->data['Discharge']['uuid'] == ''){
						$uuidArr = $this->Discharge->query("select uuid() as code");
						$this->request->data['Discharge']['uuid'] 		= $uuidArr[0][0]['code'];
					}
					if(isset($this->data['Discharge']['clearance']) && is_array($this->data['Discharge']['clearance']) && count($this->data['Discharge']['clearance'])>0){
						$clearance = $this->data['Discharge']['clearance'];
						$this->request->data['Discharge']['clearance'] 		= implode(",", $this->data['Discharge']['clearance']);
					}
					// debug($this->request->data);
					if(isset($this->data['Discharge']['custody_escaped']) && is_array($this->data['Discharge']['custody_escaped']) && count($this->data['Discharge']['custody_escaped'])>0){
						$custody_escaped = $this->data['Discharge']['custody_escaped'];
						$this->request->data['Discharge']['custody_escaped'] 		= implode(",", $this->data['Discharge']['custody_escaped']);
					}
					$this->request->data['Discharge']['prisoner_id'] 	= $prisoner_id;	
					$this->request->data['Discharge']['prison_id'] 	= $this->Session->read('Auth.User.prison_id');	
					if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
		                $this->request->data['Discharge']['status'] = 'Reviewed';
		            }
		            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
		                $this->request->data['Discharge']['status'] = 'Saved';
		            }
					// debug($this->request->data);exit;
					$db = ConnectionManager::getDataSource('default');
                	$db->begin();  					
					if($this->Discharge->saveAll($this->request->data)){
						$refId = 0;
	                    $action = 'Add';
	                    if(isset($this->request->data['Discharge']['id']) && (int)$this->request->data['Discharge']['id'] != 0)
	                    {
	                        $refId = $this->request->data['Discharge']['id'];
	                        $action = 'Edit';
	                    }
	                    //save audit log 
	                    if($this->auditLog('Discharge', 'discharges', $refId, $action, json_encode($this->data)))
	                    {
	                        $db->commit();
		                    $this->Session->write('message_type','success');
		                    $this->Session->write('message','Saved Successfully !');
		                    $this->redirect('/discharges/index/'.$uuid);
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
				 *Code for edit the prisoner discharge records
				*/				
		        if(isset($this->data['DischargeEdit']['id']) && (int)$this->data['DischargeEdit']['id'] != 0){
		            if($this->Discharge->exists($this->data['DischargeEdit']['id'])){
		                $this->data = $this->Discharge->findById($this->data['DischargeEdit']['id']);
		                $this->request->data['Discharge']['discharge_date'] = date('d-m-Y', strtotime($this->data['Discharge']['discharge_date']));
		            }
		        }
		        /*
		         *Code for delete the prisoner discharge records
		         */	
		        if(isset($this->data['DischargeDelete']['id']) && (int)$this->data['DischargeDelete']['id'] != 0){
		            if($this->Discharge->exists($this->data['DischargeDelete']['id']))
		            {
	                    $this->Discharge->id = $this->data['DischargeDelete']['id'];
	                    $db = ConnectionManager::getDataSource('default');
                		$db->begin();  
	                    if($this->Discharge->saveField('is_trash',1))
	                    {
	                    	if($this->auditLog('Discharge', 'discharges', $this->data['DischargeDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
	                    $this->redirect('/discharges/index/'.$uuid);		                
		            }
		        }

				/*
				 *Query for get the discharge type list
				 */
				// check the prisoner id condumed
				$this->loadModel("PrisonerSentence");
				$deathPunishmentRecord = $this->PrisonerSentence->find("count", array(
					"conditions"	=> array(
						"PrisonerSentence.prisoner_id"	=> $prisonerData['Prisoner']['id'],
						"PrisonerSentence.sentence_of"	=> 4,
					),
				));
				$dischargeTypeCondi = array("DischargeType.id NOT IN (1,3,4,6,7,10)");
				$notDischargeTypeList = "4,6";
				if(isset($deathPunishmentRecord) && $deathPunishmentRecord==1){
					$dischargeTypeCondi = array("DischargeType.id IN (5,7,3,10)");
				}
				// discharge condition for court attendance prisoner start here ==============
				$this->loadModel('ReturnFromCourt');
				$returnCourt = $this->ReturnFromCourt->find("first", array(
					"conditions"	=> array(
						"ReturnFromCourt.prisoner_id"	=> $prisonerData['Prisoner']['id'],
					),
					"order"			=> array(
						"ReturnFromCourt.id"	=> "desc",
					),
				));
				
				if(isset($returnCourt) && is_array($returnCourt) && count($returnCourt)>0){
					// remark "nolle proseque" and release on bond then discharge should be triggered.
					if($returnCourt['ReturnFromCourt']['remark']==6){
						$dischargeTypeCondi = array("DischargeType.id IN (2)");
					}	
					// 5:Case dismissed --> discharge should be initiated.
					if($returnCourt['ReturnFromCourt']['remark']==14){
						$dischargeTypeCondi = array("DischargeType.id IN (2)");
					}	

					// rulling-> no case to answer-> prisoner should trigger to discharge.
					if($returnCourt['ReturnFromCourt']['case_status']=='Ruling' && $returnCourt['ReturnFromCourt']['case_to_answer']=='No'){
						$dischargeTypeCondi = array("DischargeType.id IN (2)");
					}
					
					// judgement-> acquited -> prisoner should trigger to discharge.
					if($returnCourt['ReturnFromCourt']['remark']==10){
						$dischargeTypeCondi = array("DischargeType.id IN (2)");
					}
					// [If Bail Legal Requirement is Met – Is selected then the remand Prisoner can discharge permanently]
					
					if($returnCourt['ReturnFromCourt']['remark']==3 && $returnCourt['ReturnFromCourt']['bail_legal_status']==1){
						$dischargeTypeCondi = array("DischargeType.id IN (2)");
					}
					// Grant bail if Bail Legal Requirement not Met then prisoner can go for discharge.(Release on bail)
					if($returnCourt['ReturnFromCourt']['remark']==3 && $returnCourt['ReturnFromCourt']['bail_legal_status']!=1){
						$dischargeTypeCondi = array("DischargeType.id IN (1)");
					}
				}else{
					if(isset($prisonerData['Prisoner']['prisoner_type_id']) && $prisonerData['Prisoner']['prisoner_type_id']==Configure::read("REMAND")){
						unset($dischargeTypeCondi[0]);
						$dischargeTypeCondi = array("DischargeType.id IN (5)");
					}
				}
				//===========================================================================
				if(isset($prisonerData['Prisoner']['is_death']) && $prisonerData['Prisoner']['is_death']==1){
					$dischargeTypeCondi = array("DischargeType.id IN (3)");
				}

				// condition on reales for pardon========

				$this->loadModel('PrisonerPetition');
				$relaseonpardon = $this->PrisonerPetition->find("first", array(
					"conditions"	=> array(
						"PrisonerPetition.prisoner_id"	=> $prisonerData['Prisoner']['id'],
						"PrisonerPetition.status"=>'Approved'

					),
					"order"			=> array(
						"PrisonerPetition.id"	=> "desc",
					),
				));

				// debug($relaseonpardon);
				

				

				if(isset($relaseonpardon['PrisonerPetition']['petition_result']) && $relaseonpardon['PrisonerPetition']['petition_result']=='Discharge'){
						$dischargeTypeCondi = array("DischargeType.id IN (14)");
					}
				

				
				//==========================================
				$dischargetypeList = $this->DischargeType->find('list', array(
					'recursive'		=> -1,
					'fields'		=> array(
						'DischargeType.id',
						'DischargeType.name',
					),
					'conditions'	=> array(
						'DischargeType.is_enable'		=> 1,
						'DischargeType.is_trash'		=> 0,
					)+$dischargeTypeCondi,
					'order'			=> array(
						'DischargeType.name',
					),
				));

				// debug($dischargeTypeCondi);
				/*
				 *Query for get the Medical officers list
				 */
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
				  /*
				 *Query for get the sentence list
				 */
				  $sentences=$this->PrisonerSentenceDetail->find('list',array(
	                'fields'        => array(
	                    'PrisonerSentenceDetail.id',
	                    'PrisonerSentenceDetail.sentence_type',
	                ),
	                'conditions'=>array(
	                  'PrisonerSentenceDetail.prisoner_id'=>$prisonerData['Prisoner']['id'],//Gate keeper User
	                ),
	                'order'=>array(
	                  'PrisonerSentenceDetail.id'
	                )
         		 ));
				  $this->loadModel('Country');
				   $countryList = $this->Country->find('list', array(
			            'recursive'     => -1,
			            'fields'        => array(
			                'Country.id',
			                'Country.name',
			            ),
			            'conditions'    => array(
			                'Country.is_enable'      => 1,
			                'Country.is_trash'       => 0,
			            ),
			            'order'         => array(
			                'Country.name'
			            ),
			        ));
				$this->set(array(
					'uuid'					=> $uuid,
					'dischargetypeList'		=> $dischargetypeList,
					'sentences'				=> $sentences,
					'medicalOfficers'		=> $medicalOfficers,
					'countryList'           => $countryList, 
					'prisoner_id'			=> $prisoner_id,
					'prisonerData'			=> $prisonerData,
					'dischargeData'			=> $dischargeData,
				));
			}else{
				return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
			}
		}else{
			return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
		}
    }
    public function indexAjax(){
		$this->layout 			= 'ajax';
    	$discharge_date 		= '';
    	$discharge_type_id 		= '';
    	$uuid 					= '';
    	$condition 				= array(
    		'Discharge.is_trash'		=> 0,
    	);
		if(isset($this->params['named']['discharge_date']) && $this->params['named']['discharge_date'] != ''){
    		$discharge_date = $this->params['named']['discharge_date'];
    		$condition += array(
    			'Discharge.discharge_date'	=> date('Y-m-d', strtotime($discharge_date)),
    		);    		
    	}
		if(isset($this->params['named']['discharge_type_id']) && $this->params['named']['discharge_type_id'] != ''){
    		$discharge_type_id = $this->params['named']['discharge_type_id'];
    		$condition += array(
    			'Discharge.discharge_type_id'	=> $discharge_type_id,
    		);     		
    	}      	
		if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
    		$uuid = $this->params['named']['uuid'];
    		$prisonerData = $this->Prisoner->findByUuid($uuid);
    		$condition += array(
    			'Discharge.prisoner_id'	=> $prisonerData['Prisoner']['id'],
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
        // debug($condition);
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'Discharge.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('Discharge');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas,
    		'discharge_date'			=> $discharge_date,
    		'discharge_type_id'			=> $discharge_type_id,
    	));     	
    }
    //Get Prisoner GatePass list  
	public function gatepassAjax(){
		$this->layout 			= 'ajax';
    	$uuid 					= '';
    	$condition 				= array(
    		'GatePass.is_trash'		=> 0,
    	);	
		if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
    		$uuid = $this->params['named']['uuid'];
    		
    	}    	
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gatepass_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    	
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'GatePass.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('GatePass');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas,
    		'funcall'						=> $this,
    	));     	
    }
    //Death in custody
    public function DeathInCustodyAjax(){
		$this->layout 			= 'ajax';
    	$date_of_death 		= '';
    	
    	$uuid 					= '';
    	$condition 				= array(
    		'DeathInCustody.is_trash'		=> 0,
    	);
		if(isset($this->params['named']['date_of_death']) && $this->params['named']['date_of_death'] != ''){
    		$date_of_discharge = $this->params['named']['date_of_death'];
    		$condition += array(
    			'DeathInCustody.date_of_death'	=> date('Y-m-d', strtotime($date_of_death)),
    		);    		
    	}
		   	
		if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
    		$uuid = $this->params['named']['uuid'];
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
    			'DeathInCustody.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('DeathInCustody');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas,
    		'date_of_death'			=> $date_of_death,
    		
    	));     	
    } 
    //Discharge on escape
    public function DischargeEscapeAjax(){
		$this->layout 			= 'ajax';
    	$date_of_escape 		= '';
    	
    	$uuid 					= '';
    	$condition 				= array(
    		'DischargeEscape.is_trash'		=> 0,
    	);
		if(isset($this->params['named']['date_of_escape']) && $this->params['named']['date_of_escape'] != ''){
    		$date_of_escape = $this->params['named']['date_of_escape'];
    		$condition += array(
    			'DischargeEscape.date_of_escape'	=> date('Y-m-d', strtotime($date_of_escape)),
    		);    		
    	}
		   	
		if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
    		$uuid = $this->params['named']['uuid'];
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
    			'DischargeEscape.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('DischargeEscape');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas,
    		'date_of_escape'			=> $date_of_escape,
    		
    	));     	
    }

    // listing for process the discharge module
    public function dischargeList(){
    	$menuId = $this->getMenuId("/Discharges/dischargeList");
                $moduleId = $this->getModuleId("discharge");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Discharge.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Discharge.status !='=>'Draft');
            $condition      += array('Discharge.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Discharge.status !='=>'Draft');
            $condition      += array('Discharge.status !='=>'Saved');
            $condition      += array('Discharge.status !='=>'Review-Rejected');
            $condition      += array('Discharge.status'=>'Reviewed');
        }   
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
                $status = $this->setApprovalProcess($items, 'Discharge', $status, $remark);
                if($status == 1)
                {
                	//notification on approval of Discharge --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Discharge list of prisoner are pending for review.";
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
                                "url_link"   => "discharges/dischargeList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Discharge list of prisoner are pending for approve";
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
                                "url_link"   => "discharges/dischargeList",                    
                            ));
                        }
                    }
                    //notification on approval of Discharge --END--

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                            $this->Session->write('message','Reviewed Successfully !');}
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                            $this->Session->write('message','Rejected Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
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
                }
                $this->redirect('dischargeList');
            }
        }
        $prisonerListData = $this->Discharge->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Discharge.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Discharge.prison_id'        => $this->Auth->user('prison_id')
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

    public function dischargeListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'Discharge.is_trash'      => 0,
            'Discharge.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Discharge.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Discharge.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Discharge.status !='=>'Draft');
                $condition      += array('Discharge.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Discharge.status !='=>'Draft');
                $condition      += array('Discharge.status !='=>'Saved');
                $condition      += array('Discharge.status !='=>'Review-Rejected');
                $condition      += array('Discharge.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Discharge.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','discharge_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','discharge_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','discharge_list_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Discharge.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Discharge');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }

    // listing for process the discharge module
    public function gatepassList(){
    	$menuId = $this->getMenuId("/Discharges/gatepassList");
                $moduleId = $this->getModuleId("discharge");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Discharge.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Discharge.status !='=>'Draft');
            $condition      += array('Discharge.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Discharge.status !='=>'Draft');
            $condition      += array('Discharge.status !='=>'Saved');
            $condition      += array('Discharge.status !='=>'Review-Rejected');
            $condition      += array('Discharge.status'=>'Reviewed');
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
                $status = $this->setGatepass($items, 'Discharge',$gatepassDetails);
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
        $prisonerListData = $this->Discharge->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Discharge.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Discharge.prison_id'        => $this->Auth->user('prison_id')
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
                'EscortTeam.is_available'    => "YES",
                'EscortTeam.escort_type'  => "Dicharge",
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));
        $condition              = array(
            'Discharge.is_trash'      => 0,
            'Discharge.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Discharge.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Discharge.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Discharge.status !='=>'Draft');
                $condition      += array('Discharge.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Discharge.status !='=>'Draft');
                $condition      += array('Discharge.status !='=>'Saved');
                $condition      += array('Discharge.status !='=>'Review-Rejected');
                $condition      += array('Discharge.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Discharge.prisoner_id'   => $prisoner_id,
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
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Discharge.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Discharge');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
            'teamList'        => $teamList,
        ));
    }

    //Prisoner Child Detail START
    public function childDetailAjax(){        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerChildDetail.is_trash'         => 0,
        );
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid']!=''){
        	$uuid = $this->params['named']['uuid'];
        }
        /*
		 *Code for add the update the child details as realease
		*/					
		if(isset($this->data['PrisonerChildDetail']) && is_array($this->data['PrisonerChildDetail']) && count($this->data['PrisonerChildDetail']) >0){
			if(isset($this->data['PrisonerChildDetail']['date_of_handover']) && $this->data['PrisonerChildDetail']['date_of_handover'] != ''){
				$this->request->data['PrisonerChildDetail']['date_of_handover'] = date('Y-m-d', strtotime($this->data['PrisonerChildDetail']['date_of_handover']));
			}
			$this->request->data['PrisonerChildDetail']['prison_id'] = $this->Session->read('Auth.User.prison_id');
			$this->request->data['PrisonerChildDetail']['handed_over_date_time'] = date("Y-m-d H:i:s");
			$db = ConnectionManager::getDataSource('default');
        	$db->begin();  					
			if($this->PrisonerChildDetail->saveAll($this->data)){
				$refId = 0;
                $action = 'Add';
                if(isset($this->request->data['PrisonerChildDetail']['id']) && (int)$this->request->data['PrisonerChildDetail']['id'] != 0)
                {
                    $refId = $this->request->data['PrisonerChildDetail']['id'];
                    $action = 'Edit';
                }
                //save audit log 
                if($this->auditLog('PrisonerChildDetail', 'release child', $refId, $action, json_encode($this->data)))
                {
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Update Successfully !');
                    $this->redirect('/discharges/index/'.$uuid.'#child_release');
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
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id = $this->params['named']['uuid'];
            $condition += array('PrisonerChildDetail.puuid' => $prisoner_id );
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

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerChildDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerChildDetail');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id    
        ));
    }

    // listing for process the discharge module
    public function childDetailList(){
    	$menuId = $this->getMenuId("/Discharges/childDetailList");
                $moduleId = $this->getModuleId("discharge");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerChildDetail.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerChildDetail.status !='=>'Draft');
            $condition      += array('PrisonerChildDetail.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('PrisonerChildDetail.status !='=>'Draft');
            $condition      += array('PrisonerChildDetail.status !='=>'Saved');
            $condition      += array('PrisonerChildDetail.status !='=>'Review-Rejected');
            $condition      += array('PrisonerChildDetail.status'=>'Reviewed');
        }   
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
                $status = $this->setApprovalProcess($items, 'PrisonerChildDetail', $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                            $this->Session->write('message','Reviewed Successfully !');}
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                            $this->Session->write('message','Rejected Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
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
                }
                $this->redirect('gatepassList');
            }
        }
        $prisonerListData = $this->PrisonerChildDetail->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerChildDetail.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'PrisonerChildDetail.prison_id'        => $this->Auth->user('prison_id')
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
            'statusListData'     => $statusList,
            'default_status'    => $default_status
        ));
    }

    public function childDetailListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'PrisonerChildDetail.is_trash'      => 0,
            'PrisonerChildDetail.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerChildDetail.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('PrisonerChildDetail.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('PrisonerChildDetail.status !='=>'Draft');
                $condition      += array('PrisonerChildDetail.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('PrisonerChildDetail.status !='=>'Draft');
                $condition      += array('PrisonerChildDetail.status !='=>'Saved');
                $condition      += array('PrisonerChildDetail.status !='=>'Review-Rejected');
                $condition      += array('PrisonerChildDetail.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'PrisonerChildDetail.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','child_details_list_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','child_details_list_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','child_details_list_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerChildDetail.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('PrisonerChildDetail');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }

    // function getPrisonerDetails($id){
    //     $this->Prisoner->recursive = -1;
    //     return $this->Prisoner->findById($id);
    // }

    function checkDischargePossablity(){
    	$this->layout = 'ajax';
    	$prison_id = $this->Session->read('Auth.User.prison_id');
    	$prisonerDetails = $this->Prisoner->find("first", array(
    		"recursive"	=> -1,
    		"conditions"	=> array(
    			"Prisoner.id"	=> $this->params['named']['prisoner_id'],
    		),
    	));
    	//get escorting officer list
        $officerList = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'    => 1,
                'User.is_trash'     => 0,
                'User.prison_id'    => $prison_id
            ),
            'order'         => array(
                'User.name'
            ),
        ));
        $this->loadModel('EscapeType');
        $escapeType = $this->EscapeType->find('list', array(
        	 'fields'        => array(
                'EscapeType.name',
                'EscapeType.name',
            )
        ));
		$this->loadModel('Country');
		   $countryList = $this->Country->find('list', array(
	            'recursive'     => -1,
	            'fields'        => array(
	                'Country.id',
	                'Country.name',
	            ),
	            'conditions'    => array(
	                'Country.is_enable'      => 1,
	                'Country.is_trash'       => 0,
	            ),
	            'order'         => array(
	                'Country.name'
	            ),
        ));


        /**
		 * Query for get the sentence list
		 */
		  $sentences=$this->PrisonerSentenceDetail->find('list',array(
            'fields'        => array(
                'PrisonerSentenceDetail.id',
                'PrisonerSentenceDetail.sentence_type',
            ),
            'conditions'	=>	array(
              'PrisonerSentenceDetail.prisoner_id'	=>	$prisonerDetails['Prisoner']['id'],//Gate keeper User
            ),
            'order'=>array(
              'PrisonerSentenceDetail.id'
            )
 		 ));
		// check the clearance for all module
		if(!in_array($this->params['named']['discharge_type_id'],array(5))){
			$clearanceData = $this->checkClearanceStatus($prisonerDetails['Prisoner']['id'],$this->params['named']['discharge_type_id']);
			if($clearanceData!=1){
				echo $clearanceData;exit;
			}
		}
    	$discharge_type_id = $this->params['named']['discharge_type_id'];
    	switch ($discharge_type_id) {
    		case 2://normanl discharge
    			
    			// if($prisonerDetails['Prisoner']['prisoner_type_id']!=Configure::read('CONVICTED')){
    			// 	echo "This prisoner not convicted";exit;
    			// }
    			// check the discharge date
    			$discharge_date = ($prisonerDetails['Prisoner']['dor']!='') ? $this->checkHoliday($prisonerDetails['Prisoner']['dor']) : '';
    			if(strtotime($discharge_date) > strtotime(date("d-m-Y"))){
    				echo "Please wait for discharge till discharge date";exit;
    			}
    			//check the cases
    			$this->loadModel('PrisonerSentence');    			
    			$pendingCases = $this->PrisonerSentence->find("count", array(
    				"conditions"=> array(
    					"PrisonerSentence.prisoner_id"=>$this->params['named']['prisoner_id'],
    					"PrisonerSentence.waiting_for_confirmation"=>0,
    					"PrisonerSentence.status"=>'Approved',
    					)
    				));
    			if($pendingCases != 0){
    				echo "This prisoner still have some pending cases.";exit;
    			}

    			break;

    		case 8://[UCFR-44] Release on License to be at Large
    			if($prisonerDetails['Prisoner']['habitual_prisoner']!=1){
    				echo "This prisoner is not habitual prisoner";exit;
    			}
				$years = round((time()-strtotime($prisonerDetails['Prisoner']['created']))/(3600*24*365.25));
    			if($years < 3){
    				echo "This prisoner has not completed 3 years of imprisonment";exit;
    			}
    			if(strtotime($prisonerDetails['Prisoner']['epd']) > strtotime(date("d-m-Y"))){
    				echo "This prisoner has not completed EPD";exit;
    			}    			
    			break;

    		case 3://Prisoner Death    			
    			$this->loadModel('MedicalDeathRecord');    			
    			$pendingCases = $this->MedicalDeathRecord->find("count", array(
    				"conditions"=> array(
    					"MedicalDeathRecord.prisoner_id"=>$this->params['named']['prisoner_id'],
    					"MedicalDeathRecord.status"=>"Approved",
    					)
    				));
    			if($pendingCases != 1){
    				echo "Death medical record not found.";exit;
    			}
    			break;


    		default:
    			echo "";
    			break;
    	}
    	if(isset($this->params['named']['discharge_transfer_id']) && $this->params['named']['discharge_transfer_id']!=''){
    		$this->request->data = $this->Discharge->findById($this->params['named']['discharge_transfer_id']);
    	}
    	// echo $prisonerDetails['Prisoner']['prisoner_type_id'];
    	$this->set(array(
            'discharge_type_id'         => $discharge_type_id,
            'officerList'				=> $officerList,
            'prisonerDetails'			=> $prisonerDetails,
            'countryList'               => $countryList,
            'sentences'					=> $sentences,
            'escapeType'                => $escapeType,
        ));
    }
    function checkDischargeDetails(){
    	$this->layout = 'ajax';
    	$prison_id = $this->Session->read('Auth.User.prison_id');
    	$prisonerDetails = $this->Prisoner->find("first", array(
    		"recursive"	=> -1,
    		"conditions"	=> array(
    			"Prisoner.id"	=> $this->params['named']['prisoner_id'],
    		),
    	));
    	//get escorting officer list
        $officerList = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'    => 1,
                'User.is_trash'     => 0,
                'User.prison_id'    => $prison_id
            ),
            'order'         => array(
                'User.name'
            ),
        ));
    	$discharge_type_id = $this->params['named']['discharge_type_id'];
    	switch ($discharge_type_id) {
    		case 2://normanl discharge
    			if($prisonerDetails['Prisoner']['prisoner_type_id']!=Configure::read('CONVICTED')){
    				// echo "This prisoner not convicted";exit;
    			}
    			$this->loadModel('PrisonerSentenceAppeal');    			
    			$pendingCases = $this->PrisonerSentenceAppeal->find("count", array(
    				"conditions"=> array(
    					"PrisonerSentenceAppeal.prisoner_id"=>$this->params['named']['prisoner_id'],
    					"PrisonerSentenceAppeal.prisoner_waiting_confirmation"=>0
    					)
    				));
    			if($pendingCases != 0){
    				echo "This prisoner still have some pending cases.";exit;
    			}
    			break;

    		case 3://Prisoner Death    			
    			$this->loadModel('MedicalDeathRecord');    			
    			$pendingCases = $this->MedicalDeathRecord->find("count", array(
    				"conditions"=> array(
    					"MedicalDeathRecord.prisoner_id"=>$this->params['named']['prisoner_id'],
    					"MedicalDeathRecord.status"=>"Approved",
    					)
    				));
    			if($pendingCases != 1){
    				// echo "Death medical record not found.";exit;
    			}
    			break;


    		default:
    			echo "";
    			break;
    	}
    	if(isset($this->params['named']['discharge_transfer_id']) && $this->params['named']['discharge_transfer_id']!=''){
    		$this->request->data = $this->Discharge->findById($this->params['named']['discharge_transfer_id']);
    	}
    	// echo $prisonerDetails['Prisoner']['prisoner_type_id'];
    	$this->set(array(
            'discharge_type_id'         => $discharge_type_id,
            'officerList'				=> $officerList,
            'prisonerDetails'			=> $prisonerDetails,
        ));
    }

    function getDetails($modelName, $id){
    	$this->loadModel($modelName);
        $this->$modelName->recursive = -1;
        return $this->$modelName->findByPrisonerId($id);
    }

    public function checkHoliday($NewDate){

   //  	if(date('N', strtotime($date))==4){
			// 	return date('d-m-Y', strtotime("-1 day", strtotime($date)));
			// }else{
			// 	return "";
			// }
    	$this->loadModel('Holiday');
    	$holidayCount = $this->Holiday->find('count', array(
			"conditions"	=> array(
				"Holiday.holiday_date"	=> date("Y-m-d",strtotime($NewDate)),
			),
		));
		if($holidayCount > 0){
			$NewDate = date('d-m-Y', strtotime("-1 day", strtotime($NewDate)));
			return $this->checkHoliday($NewDate);
		}else{
			if(date('N', strtotime($NewDate))==7){
				$NewDate = date('d-m-Y', strtotime("-1 day", strtotime($NewDate)));
				return $this->checkHoliday($NewDate);
			}else{
				return date("d-m-Y", strtotime($NewDate));
			}
		}   	
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
            		$data[$i]['Gatepass']			= $gatepassDetails;
            		$data[$i]['Gatepass']['gp_date']	= date("Y-m-d", strtotime($gatepassDetails['gp_date']));
		            $data[$i]['Gatepass']['gp_no']	= "GP-".str_pad($this->Session->read('Auth.User.prison_id'),3,"0",STR_PAD_LEFT)."-".str_pad($recordCount,5,"0",STR_PAD_LEFT);
		            $uuidArr = $this->Gatepass->query("select uuid() as code");
            		$data[$i]['Gatepass']['uuid']		= $uuidArr[0][0]['code'];
		            
            		$data[$i]['Gatepass']['prison_id']	= $prison_id;
	                $data[$i]['Gatepass']['model_name']	= $model;
	                $data[$i]['Gatepass']['user_id']	= $login_user_id;
	                $data[$i]['Gatepass']['reference_id'] = $item['fid'];	                
	                $data[$i]['Gatepass']['gatepass_type'] = 'Discharge';	     
	                $dischargeData = $this->Discharge->findById($item['fid']);           
	                $data[$i]['Gatepass']['prisoner_id'] = $dischargeData['Discharge']['prisoner_id'];
	                $notificationPrisoner[] = $dischargeData['Discharge']['prisoner_id'];
	                 $this->loadModel('EscortTeam');
                      $this->EscortTeam->updateAll(array('EscortTeam.is_available'=>'"NO"'),array('EscortTeam.id'=>$gatepassDetails['escort_team'],
                        )
                    );
                       $this->Prisoner->updateAll(array('Prisoner.is_available'=>'"NO"'),array('Prisoner.id'=>$dischargeData['Discharge']['prisoner_id'],
                        )
                    );
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
			                        "url_link"   => "/discharge/gatepassList",
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


    public function checkClearanceStatus($prisoner_id,$discharge_type){
    	// check medical exit process
    	$pendingText = '';
		$pendingMedicalCheckup = $this->MedicalCheckupRecord->find("count", array(
			"conditions"=> array(
				"MedicalCheckupRecord.prisoner_id"=>$prisoner_id,
				"MedicalCheckupRecord.check_up"=>'Exit',
				)
			));
		if($pendingMedicalCheckup==0){
			if($discharge_type!=3){
				$pendingText .= "Medical Pending, ";
			}			
		}
		$pendingMedicalCheckup = $this->PhysicalPropertyItem->find("all", array(
			"recursive"	=> -1,
			"joins" => array(
                array(
                    "table" => "physical_properties",
                    "alias" => "PhysicalProperty",
                    "type" => "left",
                    "conditions" => array(
                        "PhysicalProperty.id = PhysicalPropertyItem.physicalproperty_id"
                    ),
                ),
            ),
			"conditions"=> array(
				"PhysicalProperty.prisoner_id"=>$prisoner_id,
			),
			"group"		=> array(
				"PhysicalPropertyItem.item_status",
			),
			"fields"	=> array(
				"PhysicalPropertyItem.item_status",
				"sum(PhysicalPropertyItem.quantity) as count",
			),
		));

		if(isset($pendingMedicalCheckup) && count($pendingMedicalCheckup)>0){
			$incomingProperty = 0;
			$outgoingProperty = 0;
			foreach ($pendingMedicalCheckup as $key => $value) {
				if($value['PhysicalPropertyItem']['item_status']=='Destroy'){
					$outgoingProperty += $value[0]['count'];
				}
				if($value['PhysicalPropertyItem']['item_status']=='Incoming'){
					$incomingProperty += $value[0]['count'];
				}
				if($value['PhysicalPropertyItem']['item_status']=='Outgoing'){
					$outgoingProperty += $value[0]['count'];
				}
			}

			if($incomingProperty != $outgoingProperty){
				$pendingText .= "Property Pending, ";
			}
		}

		// checking for cash property 
		$cashItem = $this->PropertyTransaction->find("all", array(
			"recursive"	=> -1,			
			"conditions"=> array(
				"PropertyTransaction.prisoner_id"=>$prisoner_id,
			),
			"group"		=> array(
				"PropertyTransaction.transaction_type",
			),
			"fields"	=> array(
				"PropertyTransaction.transaction_type",
				"sum(PropertyTransaction.transaction_amount) as count",
			),
		));

		if(isset($cashItem) && count($cashItem)>0){
			$incomingProperty = 0;
			$outgoingProperty = 0;
			foreach ($cashItem as $key => $value) {
				if($value['PropertyTransaction']['transaction_type']=='Debit'){
					$outgoingProperty += $value[0]['count'];
				}
				if($value['PropertyTransaction']['transaction_type']=='Credit'){
					$incomingProperty += $value[0]['count'];
				}
			}

			if($incomingProperty != $outgoingProperty){
				$pendingText .= "Cash Property Pending, ";
			}
		}
		// =========================================================
		
		if($this->PrisonerSaving->field("total_amount",array("prisoner_id"=>$prisoner_id),"id desc") != 0){
			$pendingText .= "Earning Pending, ";
		}
		
		if($pendingText!=''){
			return $pendingText;
		}else{
			return 1;
		}
    }

    public function eascapePdf($discharge_id)
    {
        if(!empty($discharge_id))
        {
        	$this->layout="print";
        	$this->Discharge->bindModel(
		        array('belongsTo' => array(
		                'Prisoner' => array(
		                    'className' => 'Prisoner'
		                )
		            )
		        )
		    );
            $dischargeData = $this->Discharge->findById($discharge_id);
            $sentanceData = $this->PrisonerSentence->findByPrisonerId($dischargeData['Discharge']['prisoner_id']);
            // $discplinarypro = $this->PrisonerOffence->find('all', array(
            // 	'conditions' => array(
            // 		'PrisonerOffence.prisoner_id'=>$dischargeData,
            // 	),
            // ));

            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF13";
            $dataArray = $dischargeData['Prisoner'] + $dischargeData['Discharge'] + $dischargeData['DischargeType'];
            // debug($dataArray);
            

            $variables = array();
            $variables = $dataArray;
            $variables['gender_id'] = $this->getName($variables['gender_id'],"Gender","name");
            $variables['escape_time'] = date("h:i A", strtotime($variables['escape_date']));
            $variables['escape_date'] = date("d-m-Y", strtotime($variables['escape_date']));
            $variables['prison_name'] = $this->getName($variables['prison_id'],"Prison","name");
            $variables['tribe_id'] = $this->getName($variables['tribe_id'],"Tribe","name");
            $variables['classification_id'] = $this->getName($variables['classification_id'],"Classification","name");
            $variables['occupation_id'] = $this->getName($variables['occupation_id'],"Occupation","name");
            $variables['officer_incharge'] = '';
            $offenceArr =array();
            $variables['crime'] = '';
            if(isset($sentanceData['PrisonerSentence']['offence']) && $sentanceData['PrisonerSentence']['offence']!=''){
                foreach (explode(",", $sentanceData['PrisonerSentence']['offence']) as $offencekey => $offencevalue) {
                    $offenceArr[] = $this->getName($offencevalue,"Offence","name");
                }
                $variables['crime'] = implode(", ", $offenceArr);
            }
            $variables['section_law'] = '';
            $section_of_lawArr =array();
            if(isset($sentanceData['PrisonerSentence']['section_of_law']) && $sentanceData['PrisonerSentence']['section_of_law']!=''){
                foreach (explode(",", $sentanceData['PrisonerSentence']['section_of_law']) as $section_of_lawkey => $section_of_lawvalue) {
                    $section_of_lawArr[] = $this->getName($section_of_lawvalue,"SectionOfLaw","name");
                }
                $variables['section_law'] = implode($section_of_lawArr);
            }
            $variables['sentance'] = '';
            $lpd = (isset($dischargeData['Prisoner']['sentence_length']) && $dischargeData['Prisoner']['sentence_length']!='') ? json_decode($dischargeData['Prisoner']['sentence_length']) : array();
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
                $variables['sentance'] = implode(", ", $remission); 
            }  
            $variables['case_no'] = (isset($sentanceData['PrisonerSentence']['case_file_no']) && $sentanceData['PrisonerSentence']['case_file_no']!='') ? $sentanceData['PrisonerSentence']['case_file_no'] : '';
            $variables['occupation'] = '';
            $variables['chest'] = '';
            $variables['color'] = '';
            $variables['phone_number'] = '';
            $variables['date_at'] = date("d-m-Y");
            
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
           // echo $template;
           echo  $this->htmlToPdf($template, "EscapedPrisoner".".pdf");
            exit;
           
           // echo $template;
             //echo $this->htmlToPdf($template); exit;
        }
        else 
        {
            return 'FAIL';
        } 
    }

}