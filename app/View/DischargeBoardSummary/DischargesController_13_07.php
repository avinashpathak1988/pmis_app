<?php
App::uses('AppController', 'Controller');
class DischargesController    extends AppController {
	public $layout='table';
	public $uses=array('Prisoner', 'Discharge', 'DischargeType','GatePass','DeathInCustody', 'DischargeEscape','PrisonerSentenceDetail','PrisonerChildDetail','Gatepass');
	
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
					if(isset($this->data['Discharge']['epd']) && $this->data['Discharge']['epd'] != ''){
						$this->request->data['Discharge']['epd'] = date('Y-m-d', strtotime($this->data['Discharge']['epd']));
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
					$this->request->data['Discharge']['prisoner_id'] 	= $prisoner_id;	
					$this->request->data['Discharge']['prison_id'] 	= $this->Session->read('Auth.User.prison_id');	
					// debug($this->data);exit;
					$db = ConnectionManager::getDataSource('default');
                	$db->begin();  					
					if($this->Discharge->save($this->data)){
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
		                $this->request->data['Discharge']['date_of_discharge'] = date('d-m-Y', strtotime($this->data['Discharge']['date_of_discharge']));
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
		        
		        //add gate pass 
		        if(isset($this->data['GatePass']) && is_array($this->data['GatePass']) && count($this->data['GatePass']) >0){
					if(isset($this->data['GatePass']['gp_date']) && $this->data['GatePass']['gp_date'] != ''){
						$this->request->data['GatePass']['gp_date'] = date('Y-m-d', strtotime($this->request->data['GatePass']['gp_date']));
					}
					if(isset($this->data['GatePass']['uuid']) && $this->data['GatePass']['uuid'] == ''){
						$uuidArr = $this->GatePass->query("select uuid() as code");
						$this->request->data['GatePass']['uuid'] 		= $uuidArr[0][0]['code'];
					}
					$this->request->data['GatePass']['prison_id'] 	= $this->Session->read('Auth.User.prison_id');
					$this->request->data['GatePass']['prisoner_id'] 	= $prisoner_id;	
					if(isset($this->data['GatePass']['id']) && $this->data['GatePass']['id'] == ''){
						$recordCount = $this->GatePass->find("count", array(
							"conditions"	=> array(
								"GatePass.prison_id"	=> $this->Session->read('Auth.User.prison_id'),
							),
						));
						$this->request->data['GatePass']['gp_no'] 	= "GP-".str_pad($this->Session->read('Auth.User.prison_id'),3,"0",STR_PAD_LEFT)."-".str_pad($recordCount,5,"0",STR_PAD_LEFT);	
					}
					$this->request->data['GatePass']['status'] = "Approved";
					// debug($this->request->data);exit;
					$db = ConnectionManager::getDataSource('default');
                	$db->begin();  
					if($this->GatePass->save($this->data)){
						$this->Gatepass->saveAll(array(
							"Gatepass.prison_id"	=>	$this->Session->read('Auth.User.prison_id'), 
							"Gatepass.prisoner_id"	=>	$this->request->data['GatePass']['prisoner_id'], 
							"Gatepass.user_id"	=>	$this->Session->read('Auth.User.user_id'), 
							"Gatepass.gatepass_type"	=>	"Discharge", 
							"Gatepass.model_name"	=>	"Discharge", 
							"Gatepass.gp_date"	=>	date("d-m-Y"), 
							"Gatepass.gp_no"	=>	$this->request->data['GatePass']['gp_no'], 
						));
						$refId = 0;
	                    $action = 'Add';
	                    if(isset($this->request->data['GatePass']['id']) && (int)$this->request->data['GatePass']['id'] != 0)
	                    {
	                        $refId = $this->request->data['GatePass']['id'];
	                        $action = 'Edit';
	                    }
	                    //save audit log 
	                    if($this->auditLog('GatePass', 'gate_passes', $refId, $action, json_encode($this->data)))
	                    {
	                        $db->commit();
		                    $this->Session->write('message_type','success');
		                    $this->Session->write('message','Saved Successfully !');
		                    
		                }
		                else{
							$db->rollback();
			                $this->Session->write('message_type','error');
			                $this->Session->write('message','Saving Failed !');
						}
					}else{
						$db->rollback();
		                $this->Session->write('message_type','error');
		                $this->Session->write('message','Saving Failed !');
					}
					$this->redirect('/discharges/index/'.$uuid.'#gate_pass');
				}
				/*
				 *Code for edit the Gate Pass records
				*/				
		        if(isset($this->data['GatePassEdit']['id']) && (int)$this->data['GatePassEdit']['id'] != 0){
		            if($this->GatePass->exists($this->data['GatePassEdit']['id'])){
		                $this->data = $this->GatePass->findById($this->data['GatePassEdit']['id']);
		                $this->request->data['GatePass']['gp_date'] = date('d-m-Y', strtotime($this->data['GatePass']['gp_date']));
		            }
		        }
		        /*
		         *Code for delete the Gate Pass records
		         */	
		         //Delete gate pass	
		        if(isset($this->data['GatePassDelete']['id']) && (int)$this->data['GatePassDelete']['id'] != 0){
		            if($this->GatePass->exists($this->data['GatePassDelete']['id'])){
	                    $this->GatePass->id = $this->data['GatePassDelete']['id'];
	                    $db = ConnectionManager::getDataSource('default');
                		$db->begin();  
	                    if($this->GatePass->saveField('is_trash',1)){
	                    	if($this->auditLog('GatePass', 'gate_passes', $this->data['GatePassDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
	                    $this->redirect('/discharges/index/'.$uuid.'#gate_pass');		                
		            }
		        }	

				/*
				 *Query for get the discharge type list
				 */
				$dischargetypeList = $this->DischargeType->find('list', array(
					'recursive'		=> -1,
					'fields'		=> array(
						'DischargeType.id',
						'DischargeType.name',
					),
					'conditions'	=> array(
						'DischargeType.is_enable'		=> 1,
						'DischargeType.is_trash'		=> 0,
						"DischargeType.id NOT IN (4,6)",
					),
					'order'			=> array(
						'DischargeType.name',
					),
				));
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
				$this->set(array(
					'uuid'					=> $uuid,
					'dischargetypeList'		=> $dischargetypeList,
					'sentences'				=> $sentences,
					'medicalOfficers'		=> $medicalOfficers,
					'prisoner_id'			=> $prisoner_id,
					'prisonerData'			=> $prisonerData,
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
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('GatePass.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('GatePass.status !='=>'Draft');
            $condition      += array('GatePass.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('GatePass.status !='=>'Draft');
            $condition      += array('GatePass.status !='=>'Saved');
            $condition      += array('GatePass.status !='=>'Review-Rejected');
            $condition      += array('GatePass.status'=>'Reviewed');
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
                $status = $this->setApprovalProcess($items, 'GatePass', $status, $remark);
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
        $prisonerListData = $this->GatePass->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "GatePass.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'GatePass.prison_id'        => $this->Auth->user('prison_id')
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

    public function gatepassListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'GatePass.is_trash'      => 0,
            'GatePass.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'GatePass.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('GatePass.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('GatePass.status !='=>'Draft');
                $condition      += array('GatePass.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('GatePass.status !='=>'Draft');
                $condition      += array('GatePass.status !='=>'Saved');
                $condition      += array('GatePass.status !='=>'Review-Rejected');
                $condition      += array('GatePass.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'GatePass.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='export_xls';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'GatePass.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('GatePass');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
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
			// debug($this->request->data);exit;
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
                $this->set('file_name','gatepass_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='export_xls';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
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

    function getPrisonerDetails($id){
        $this->Prisoner->recursive = -1;
        return $this->Prisoner->findById($id);
    }

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
        /*
		 *Query for get the sentence list
		 */
		  $sentences=$this->PrisonerSentenceDetail->find('list',array(
            'fields'        => array(
                'PrisonerSentenceDetail.id',
                'PrisonerSentenceDetail.sentence_type',
            ),
            'conditions'=>array(
              'PrisonerSentenceDetail.prisoner_id'=>$prisonerDetails['Prisoner']['id'],//Gate keeper User
            ),
            'order'=>array(
              'PrisonerSentenceDetail.id'
            )
 		 ));
    	$discharge_type_id = $this->params['named']['discharge_type_id'];
    	switch ($discharge_type_id) {
    		case 2://normanl discharge
    			if($prisonerDetails['Prisoner']['prisoner_type_id']!=Configure::read('CONVICTED')){
    				echo "This prisoner not convicted";exit;
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
            'sentences'					=> $sentences,
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
}