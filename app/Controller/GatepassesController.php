  <?php
App::uses('AppController', 'Controller');
class GatepassesController    extends AppController {
	public $layout='table';
	public $uses=array('Gatepass');
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('gatepassPdf');
    }
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
				
				$prisoner_id 	= $prisonerData['Prisoner']['id'];
				/*
				 *Code for add the court attendance records
				*/					
				if(isset($this->data['GatePass']) && is_array($this->data['GatePass']) && count($this->data['GatePass']) >0){
					if(isset($this->data['GatePass']['gp_date']) && $this->data['GatePass']['gp_date'] != ''){
						$this->request->data['GatePass']['gp_date'] = date('Y-m-d', strtotime($this->request->data['GatePass']['gp_date']));
					}
					if(isset($this->data['GatePass']['uuid']) && $this->data['GatePass']['uuid'] == ''){
						$uuidArr = $this->GatePass->query("select uuid() as code");
						$this->request->data['GatePass']['uuid'] 		= $uuidArr[0][0]['code'];
					}
					$this->request->data['GatePass']['prisoner_id'] 	= $prisoner_id;						
					if($this->GatePass->save($this->data)){
	                    $this->Session->write('message_type','success');
	                    $this->Session->write('message','Saved Successfully !');
	                    $this->redirect('/GatePasses/index/'.$uuid);
					}else{
		                $this->Session->write('message_type','error');
		                $this->Session->write('message','Saving Failed !');
					}
				}
				/*
				 *Code for edit the Gate Pass records
				*/				
		        if(isset($this->data['GatePassEdit']['id']) && (int)$this->data['GatePassEdit']['id'] != 0){
		            if($this->GatePass->exists($this->data['GatePassEdit']['id'])){
		                $this->data = $this->GatePass->findById($this->data['GatePassEdit']['id']);
		            }
		        }
		        /*
		         *Code for delete the Gate Pass records
		         */	
		        if(isset($this->data['GatePassDelete']['id']) && (int)$this->data['GatePassDelete']['id'] != 0){
		            if($this->GatePass->exists($this->data['GatePassDelete']['id'])){
	                    $this->GatePass->id = $this->data['GatePassDelete']['id'];
	                    if($this->GatePass->saveField('is_trash',1)){
							$this->Session->write('message_type','success');
		                    $this->Session->write('message','Deleted Successfully !');
	                    }else{
							$this->Session->write('message_type','error');
		                    $this->Session->write('message','Delete Failed !');
	                    }
	                    $this->redirect('/GatePasses/index/'.$uuid);		                
		            }
		        }	
				

				$this->set(array(
					'uuid'					=> $uuid,
					'prisoner_id'			=> $prisoner_id
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
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
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
    			'GatePass.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('GatePass');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas
    	));     	
    }
    public function saveInverification(){
        $this->layout='ajax';
        if(isset($this->params['named']['gatepassId']) && (int)$this->params['named']['gatepassId'] != 0){
                $updateData = $this->Gatepass->updateAll(array(
                    "Gatepass.inverification_remark"=>"'".$this->params['named']['inverification_remark']."'",
                    "Gatepass.inverification_time"=>"'".date("Y-m-d H:i:s")."'",
                ),array(
                    "Gatepass.gp_no"=>$this->params['named']['gatepassId']
                ));

                if($updateData){
                    echo 'SUCC';exit;
                }else{
                    echo 'FAIL';exit;
                }
            }else{
                echo 'FAIL';exit;
            }   
    }
    public function gatepass() {
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
			
			$prisoner_id 	= $prisonerData['Prisoner']['id'];
			/*
			 *Code for add the court attendance records
			*/					
			if(isset($this->data['GatePass']) && is_array($this->data['GatePass']) && count($this->data['GatePass']) >0){
				if(isset($this->data['GatePass']['gp_date']) && $this->data['GatePass']['gp_date'] != ''){
					$this->request->data['GatePass']['gp_date'] = date('Y-m-d', strtotime($this->request->data['GatePass']['gp_date']));
				}
				if(isset($this->data['GatePass']['uuid']) && $this->data['GatePass']['uuid'] == ''){
					$uuidArr = $this->GatePass->query("select uuid() as code");
					$this->request->data['GatePass']['uuid'] 		= $uuidArr[0][0]['code'];
				}
				$this->request->data['GatePass']['prisoner_id'] 	= $prisoner_id;						
				if($this->GatePass->save($this->data)){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect('/GatePasses/index/'.$uuid);
				}else{
	                $this->Session->write('message_type','error');
	                $this->Session->write('message','Saving Failed !');
				}
			}
			/*
			 *Code for edit the Gate Pass records
			*/				
	        if(isset($this->data['GatePassEdit']['id']) && (int)$this->data['GatePassEdit']['id'] != 0){
	            if($this->GatePass->exists($this->data['GatePassEdit']['id'])){
	                $this->data = $this->GatePass->findById($this->data['GatePassEdit']['id']);
	            }
	        }
	        /*
	         *Code for delete the Gate Pass records
	         */	
	        if(isset($this->data['GatePassDelete']['id']) && (int)$this->data['GatePassDelete']['id'] != 0){
	            if($this->GatePass->exists($this->data['GatePassDelete']['id'])){
                    $this->GatePass->id = $this->data['GatePassDelete']['id'];
                    if($this->GatePass->saveField('is_trash',1)){
						$this->Session->write('message_type','success');
	                    $this->Session->write('message','Deleted Successfully !');
                    }else{
						$this->Session->write('message_type','error');
	                    $this->Session->write('message','Delete Failed !');
                    }
                    $this->redirect('/GatePasses/index/'.$uuid);		                
	            }
	        }	
			

			$this->set(array(
				'uuid'					=> $uuid,
				'prisoner_id'			=> $prisoner_id
			));
		}else{
			return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
		}
		
    }
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
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
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
    			'GatePass.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('GatePass');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas
    	));     	
    }

    // listing for process the discharge module
    public function gatepassList() {
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Gatepass.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status !='=>'Saved');
            $condition      += array('Gatepass.status !='=>'Review-Rejected');
            $condition      += array('Gatepass.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Verified'; 
                $remark = '';
                // if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                // {
                //     if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                //     {
                //         $status = $this->request->data['ApprovalProcessForm']['type']; 
                //         $remark = $this->request->data['ApprovalProcessForm']['remark'];
                //     }
                // }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'Gatepass', $status, $remark);
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
        $prisonerListData = $this->Gatepass->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Gatepass.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            // 'conditions'    => array(
            //     'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
            // ),
        ));

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }

        $gatepassType = $this->Gatepass->find("list", array(
            'fields'    => array(
                "Gatepass.id",
                "Gatepass.gatepass_type",
            ),
            // 'conditions'    => array(
            //     'Gatepass.' 
            // ),
           
        ));

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'statusListData'     => $statusList,
            'default_status'    => $default_status,
            'gatepassType'    => $gatepassType
        ));
    }

    public function gatepassListAjax(){
        $this->layout   = 'ajax';
        $searchData = $this->params['named'];
        $condition              = array(
            'Gatepass.is_trash'      => 0,
            'Gatepass.approval_status'      => "Approved",
            'date(Gatepass.created)'      => date("Y-m-d"),
            // 'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Gatepass.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Gatepass.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Gatepass.status !='=>'Draft');
                $condition      += array('Gatepass.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Gatepass.status'=>'Draft');
            }   
        }
        // debug($this->Session->read('Auth.User.usertype_id'));
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $gatepass_status = 'OUT';
            $condition += array(
                'Gatepass.gatepass_status'   => 'OUT',
            );
        }
        

        if(isset($this->params['named']['gatepass_status']) && $this->params['named']['gatepass_status'] != ''){
            if(isset($condition['Gatepass.gatepass_status'])){
                unset($condition['Gatepass.gatepass_status']);
            }
            $gatepass_status = $this->params['named']['gatepass_status'];
            $condition += array(
                'Gatepass.gatepass_status'   => $gatepass_status,
            );
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Gatepass.prisoner_id'   => $prisoner_id,
            );
        }

        if(isset($this->params['named']['gatepass_type']) && $this->params['named']['gatepass_type'] != ''){
            $gatepass_type = $this->params['named']['gatepass_type'];
            $condition += array(
                'Gatepass.gatepass_type'   => $gatepass_type,
            );
        }

        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "Gatepass.gp_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
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
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            // 'order'         => array(
            //     'Gatepass.modified'  => 'DESC',
            // ),
        )+$limit;
        $datas = $this->paginate('Gatepass');
        
        $this->set(array(
            'datas'         => $datas,
            'searchData'    => $searchData,
        ));
    }

    // function getPrisonerDetails($id){
    //     $this->Prisoner->recursive = -1;
    //     return $this->Prisoner->findById($id);
    // }


    // gatepass list transfer partha
    public function gatepassTransferList() {
         $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Gatepass.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status !='=>'Saved');
            $condition      += array('Gatepass.status !='=>'Review-Rejected');
            $condition      += array('Gatepass.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Verified'; 
                $remark = '';
                // if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                // {
                //     if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                //     {
                //         $status = $this->request->data['ApprovalProcessForm']['type']; 
                //         $remark = $this->request->data['ApprovalProcessForm']['remark'];
                //     }
                // }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'Gatepass', $status, $remark);
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
        /*$prisonerListData = $this->Gatepass->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Gatepass.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
            ),
        ));*/
        $this->loadModel('PrisonerTransfer');
        $prisonerListData = $this->PrisonerTransfer->find('list', array(                    
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'left',
                    'conditions'=> array('PrisonerTransfer.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'PrisonerTransfer.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerTransfer.transfer_to_station_id IN ('.$this->Session->read('Auth.User.prison_id').')',
            ),
            'order'         => array(
                'PrisonerTransfer.prisoner_id'
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

        $gatepassType = $this->Gatepass->find("list", array(
            "fields"    => array(
                "Gatepass.gatepass_type",
                "Gatepass.gatepass_type",
            ),
            "group"     => array(
                "Gatepass.gatepass_type",
            ),
        ));

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'statusListData'     => $statusList,
            'default_status'    => $default_status,
            'gatepassType'    => $gatepassType
        ));
    }
    
        public function gatepassTransferListAjax() {
        $this->layout   = 'ajax';
        $searchData = $this->params['named'];
        $condition              = array(
            'Gatepass.is_trash'      => 0,
            'Gatepass.model_name' => 'PrisonerTransfer',
            'Gatepass.gatepass_type' => 'Prisoner Transfer',
            //'Gatepass.in_time' => '0000-00-00 00:00:00',
           // 'Gatepass.out_time !=' => '0000-00-00 00:00:00',
            'PrisonerTransfer.transfer_to_station_id IN ('.$this->Session->read('Auth.User.prison_id').')',
        );
        
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Gatepass.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Gatepass.status !='=>'Draft');
                $condition      += array('Gatepass.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Gatepass.status'=>'Draft');
            }   
        
        // debug($this->Session->read('Auth.User.usertype_id'));
        /*if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $gatepass_status = 'OUT';
            $condition += array(
                'Gatepass.gatepass_status'   => 'OUT',
            );
        }*/
        

        
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Gatepass.prisoner_id'   => $prisoner_id,
            );
        }

        //debug($condition);exit;

        /*if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "Gatepass.gp_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
        }*/
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
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
       // debug($condition);exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'joins' => array(
                array(
                    'table' => 'prisoner_transfers',
                    'alias' => 'PrisonerTransfer',
                    'type' => 'left',
                    'conditions'=> array('PrisonerTransfer.id = Gatepass.reference_id'),
                ),
            ),
            'order'         => array(
                'Gatepass.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Gatepass');
        // /debug($datas);exit;
        $this->set(array(
            'datas'         => $datas,
            'searchData'    => $searchData,
        ));

    }

    
    public function recieveTransferItemCash(){
          
        $this->loadModel('PrisonerTransferPhysicalProperty'); 
        $this->loadModel('PrisonerTransferCashProperty'); 


         $transferId = $this->request->data['RecieveItemCash']['transfer_id'];
         //debug($this->request->data);exit;
         $items = $this->request->data['RecievePrisonerItem'];
         $cashItems =  $this->request->data['RecievePrisonerCashItem'];
         foreach ($items as $item) {
                if(isset($item['quantity']) && $item['quantity'] != ''){
                    $updateFields = array(
                        'PrisonerTransferPhysicalProperty.rcv_quentity'           => $item['quantity']
                    );
                    $updateConds =array(
                        'PrisonerTransferPhysicalProperty.id'    => $item['id'],
                    );

                    $this->PrisonerTransferPhysicalProperty->updateAll($updateFields, $updateConds);
                }
             
         }
         foreach ($cashItems as $cashItem) {
                if(isset($cashItem['amount']) && $cashItem['amount'] != ''){
                    $updateFields = array(
                        'PrisonerTransferCashProperty.rcv_amount' => $cashItem['amount']
                    );
                    $updateConds =array(
                        'PrisonerTransferCashProperty.id'    => $cashItem['id'],
                    );

                    $this->PrisonerTransferCashProperty->updateAll($updateFields, $updateConds);
                }
             
         }
         echo "success";
         //$transferId = 
         exit;
    }

    
      public function ajaxAddNewCashItem(){
        $this->loadModel('PrisonerTransferCashProperty'); 

        $this->layout = 'ajax';

        $amount = $this->request->data['amount'];
        $currency = $this->request->data['currency'];
        $transferid = $this->request->data['transfer_id'];

        $transferCashItem['PrisonerTransferCashProperty']['prisoner_transfer_id'] = $transferid;
        $transferCashItem['PrisonerTransferCashProperty']['currency_id'] = $currency;
        $transferCashItem['PrisonerTransferCashProperty']['amount'] = $amount;

        $this->PrisonerTransferCashProperty->saveAll($transferCashItem);
        echo "success";exit;
    }
    public function ajaxAddNewItem(){
        $this->loadModel('PrisonerTransferPhysicalProperty'); 

        $this->layout = 'ajax';

        $itemName = $this->request->data['itemName'];
        $itemQuantity = $this->request->data['itemQuantity'];
        $transferId = $this->request->data['transfer_id'];

        $prisonerItem['PrisonerTransferPhysicalProperty']['item_id'] = $itemName;
        $prisonerItem['PrisonerTransferPhysicalProperty']['quantity'] = $itemQuantity;
        $prisonerItem['PrisonerTransferPhysicalProperty']['prisoner_transfer_id'] = $transferId;

        $this->PrisonerTransferPhysicalProperty->saveAll($prisonerItem);
        echo "success";exit;
    }


    function getPropertyRow(){
        $this->layout = 'ajax';
        $this->loadModel('PrisonerTransferPhysicalProperty'); 
        $this->loadModel('PrisonerTransferCashProperty'); 

        $this->loadModel('Gatepass'); 
        $this->loadModel('Propertyitem'); 

        $transferId = $this->data['transferId'];

        $tranferPropertyItems = $this->PrisonerTransferPhysicalProperty->find('all',array(
            'recursive'=>-1,
            'conditions'=>array(
                'PrisonerTransferPhysicalProperty.prisoner_transfer_id'=>$transferId
            )
        ));

        $tranferCashItems = $this->PrisonerTransferCashProperty->find('all',array(
            'recursive'=>-1,
            'conditions'=>array(
                'PrisonerTransferCashProperty.prisoner_transfer_id'=>$transferId
            )
        ));

        $propertyItemList = $this->Propertyitem->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,

                )
            ));
        
        $data ='';
        $count =0;
        $allCollected ='true';
        $data .= '<div><h5 style="display:inline-block;"">Prisoner Property Items</h5><button style="display:inline-block;margin-left:10px;" class="add_more_property btn btn-success"><span class="icon icon-plus"></span></button></div>';
        $data .= '<div id="add_item_form" style="display:none">';
         $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Item Name</th><th>Quantity</th><th>Action</th></tr></thead><tbody>';
         $data .= '<tr><td><select  name="newItemName" id="newItemName">';
         foreach ($propertyItemList as $pitem) {
            $data .= '<option value="'.$pitem["Propertyitem"]["id"].'">'.$pitem["Propertyitem"]["name"].'</option>';

         }
         $data .= '</select></td><td><input type="number" name="newItemQuantity" id="newItemQuantity"></td><td><button class="btn btn-success insert_property_item">add</button></td></tr>';
         $data .= '</tbody></table></div>';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Item Name</th><th>Quantity</th></tr></thead><tbody>';
        //debug($tranferPropertyItems);exit;
        foreach ($tranferPropertyItems as $prisonerItemDetail) {
                        $itemTypeId = $prisonerItemDetail['PrisonerTransferPhysicalProperty']['item_id'];
                        $propertyItemName='';
                        //debug($propertyItemList);
                        foreach ($propertyItemList as $propertyItem) {
                          //debug($propertyItem);
                            if($propertyItem['Propertyitem']['id'] ==$itemTypeId ){
                                $propertyItemName = $propertyItem['Propertyitem']['name'];
                            }
                        }
                        $data .= '<tr>';

                        $data .= '<td>';
                        $data .= '<input type="hidden" name="data[RecievePrisonerItem]['.$count .'][id] " value="'. $prisonerItemDetail['PrisonerTransferPhysicalProperty']['id'] .'">';
                        $data .= $propertyItemName .'</td>';
                        if( $prisonerItemDetail['PrisonerTransferPhysicalProperty']['rcv_quentity'] != '' && $prisonerItemDetail['PrisonerTransferPhysicalProperty']['rcv_quentity'] != null){
                            $data .= '<td>'. $prisonerItemDetail['PrisonerTransferPhysicalProperty']['quantity'] .'</td>';
                            $allCollected ='true';
                        }else{
                            $data .= '<td><input type="number" name="data[RecievePrisonerItem]['.$count .'][quantity] " value="'. $prisonerItemDetail['PrisonerTransferPhysicalProperty']['quantity'] .'"></td>';
                            $allCollected ='false';

                        }
                        //$data .= '<td>'. $prisonerItemDetail['PrisonerTransferPhysicalProperty']['quantity'] .'</td>';
                       
                        $data .='</tr>';
                        $count++;
                    }
                        $data .= '</tbody></table>';

        //get cash details

        $this->loadModel('Currency');
        $currencyList = $this->Currency->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'Currency.is_enable'    => 1,
                        'Currency.is_trash'     => 0,
                    ),
                    
                ));
        $data .= '<div><h5 style="display:inline-block;">Prisoner Cash Items</h5><button class="add_more_cash btn btn-success" style="display:inline-block;margin-left:10px;"><span class="icon icon-plus"></span></button></div>';
        $data .= '<div id="add_more_cash_form" style="display:none">';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Amount</th><th>Currency</th><th>Action</th></tr></thead><tbody>';
        $data .= '<tr><td><input type="number" name="newItemAmount" id="newItemAmount"></td><td><select  name="newItemCurrency" id="newItemCurrency">';
         foreach ($currencyList as $pitem) {
            $data .= '<option value="'.$pitem["Currency"]["id"].'">'.$pitem["Currency"]["name"].'</option>';

         }
        $data .= '</select></td><td><button class="btn btn-success insert_property_cash_item">add</button></td></tr>';
        $data .= '</tbody></table></div>';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Amount</th><th>Currency</th></tr></thead><tbody>';

        $count=0;
        foreach ($tranferCashItems as $prisonerCash) {
                        // /debug($prisonerCash);
                        $data .= '<tr>';

                        $data .= '<td><input type="hidden" name="data[RecievePrisonerCashItem]['.$count .'][id] " value="'. $prisonerCash['PrisonerTransferCashProperty']['id'] .'">';

                        if( $prisonerCash['PrisonerTransferCashProperty']['rcv_amount'] != '' && $prisonerCash['PrisonerTransferCashProperty']['rcv_amount'] != null){
                            $data .=  $prisonerCash['PrisonerTransferCashProperty']['amount'];
                            $allCollected ='true';
                        }else{
                            $data .= '<input type="number" name="data[RecievePrisonerCashItem]['.$count .'][amount] " value="'. $prisonerCash['PrisonerTransferCashProperty']['amount'] .'">';
                            $allCollected ='false';

                        }
                        $data .='</td>';
                        $currencyTypeId = $prisonerCash['PrisonerTransferCashProperty']['currency_id'];
                        $currencyName= '';
                        foreach ($currencyList as $currency) {
                          //debug($propertyItem);
                            if($currency['Currency']['id'] ==$currencyTypeId ){
                                $currencyName = $currency['Currency']['name'];
                            }
                        }
                            $data .= '<td>'. $currencyName .'</td>';
                       
                        
                        $data .='</tr>';

                        $count++;
                    }         
                        $data .= '</tbody></table>';

        //end


        $data .= '<div style="display:none;" id="allCollectedResponse">'.$allCollected.'</div>';


        echo $data;exit;
    }


    function gatepassPdf($gatepass_id)
    {
        if(!empty($gatepass_id))
        {
            $this->layout = 'print';
            $gatepassData = $this->Gatepass->findById($gatepass_id);
            $this->loadModel($gatepassData['Gatepass']['model_name']);
            $referenceData = $this->$gatepassData['Gatepass']['model_name']->find('first', array(
                "recursive"     => -1,
                "conditions"    => array(
                    $gatepassData['Gatepass']['model_name'].".id"     => $gatepassData['Gatepass']['reference_id'],
                ),
            ));
            // debug($gatepassData);
            // debug($referenceData);
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/gatepass";

            $variables = array();
            $variables['gatepass_no']       = $gatepassData['Gatepass']['gp_no'];
            // $variables['permission_for']    = $gatepassData['Gatepass']['permission_granted'];
            
            // $excortList = $this->Gatepass->find("list", array(
            //     "conditions"    => array(
            //         ""
            //     ),
            // ));
            $escort_team_members = $this->getName($gatepassData['Gatepass']['escort_team'],"EscortTeam","members");
            $escortForceNo = array();
            foreach (explode(",", $escort_team_members) as $escort_team_memberkey => $escort_team_membervalue) {
                $escortForceNo[] = $this->User->field("force_number",array("User.id"=>$escort_team_membervalue));
            }
            $variables['escort']            = $this->getName($gatepassData['Gatepass']['escort_team'],"EscortTeam","name")."(".implode(",", $escortForceNo).")";//$gatepassData['Gatepass']['gatepass_type']=='Transfer'
            $variables['purpose']           = $gatepassData['Gatepass']['purpose'];
            $variables['destination']           = '';
            // conditions start for destination according to module
            // debug($gatepassData['Gatepass']['model_name']);
            if($gatepassData['Gatepass']['model_name']=="PrisonerTransfer"){
                $transfer_to_station_id = $this->PrisonerTransfer->field("transfer_to_station_id", array("PrisonerTransfer.id"=>$gatepassData['Gatepass']['reference_id']));
                $variables['destination'] = "Prison  :".$this->Prison->field("name", array("Prison.id"=>$transfer_to_station_id));
            }
            $this->loadModel("Court");
            if($gatepassData['Gatepass']['model_name']=="Courtattendance"){
                $court_id = $this->Courtattendance->field("court_id", array("Courtattendance.id"=>$gatepassData['Gatepass']['reference_id']));
                $variables['destination'] = "Court Name : ".$this->Court->field("name", array("Court.id"=>$court_id));
            }
            $this->loadModel("Hospital");
            if($gatepassData['Gatepass']['model_name']=="MedicalSeriousIllRecord"){
                $hospital_id = $this->MedicalSeriousIllRecord->field("hospital_id", array("MedicalSeriousIllRecord.id"=>$gatepassData['Gatepass']['reference_id']));
                $variables['destination'] = "Hospital Name : ".$this->Hospital->field("name", array("Hospital.id"=>$hospital_id));
                $variables['purpose']           = "Medical";
            }

            $prisonerList = $this->Gatepass->find("list",array(
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type'  => 'left',
                        'conditions'=> array('Gatepass.prisoner_id = Prisoner.id'),
                    ),
                ),
                "conditions"    => array(
                    // "Gatepass.escort_team"  => $gatepassData['Gatepass']['escort_team'],
                    "Gatepass.gp_no"  => $gatepassData['Gatepass']['gp_no'],
                    // "Gatepass.gp_date"  => $gatepassData['Gatepass']['gp_date'],
                    // "Gatepass.gatepass_type"  => $gatepassData['Gatepass']['gatepass_type'],
                ),
                "fields"    => array(
                    "Gatepass.id",
                    'Prisoner.prisoner_no',
                ),
            ));
            // debug($gatepassData['Gatepass']['model_name']);
            $variables['permission_for']    = (isset($prisonerList) && count($prisonerList)>0) ? implode(", ", $prisonerList) : '';
            // =============================================================
            $variables['prison_name']       = $this->getName($gatepassData['Gatepass']['prison_id'],"Prison","name");
            $variables['gatepass_date']     = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($gatepassData['Gatepass']['gp_date']));
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
           
           
            echo $template;
            // echo $this->htmlToPdf($template); exit;
        }
        else 
        {
            return 'FAIL';
        } 
        // exit;
    }

    function gatepassViewPdf($gatepass_id)
    {
        if(!empty($gatepass_id))
        {
            $this->layout = 'gatepass';
            $gatepassData = $this->Gatepass->findById($gatepass_id);
            $this->loadModel($gatepassData['Gatepass']['model_name']);
            $referenceData = $this->$gatepassData['Gatepass']['model_name']->find('first', array(
                "recursive"     => -1,
                "conditions"    => array(
                    $gatepassData['Gatepass']['model_name'].".id"     => $gatepassData['Gatepass']['reference_id'],
                ),
            ));
            // debug($gatepassData);
            // debug($referenceData);
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/gatepass";

            $variables = array();
            $variables['gatepass_no']       = $gatepassData['Gatepass']['gp_no'];
            // $variables['permission_for']    = $gatepassData['Gatepass']['permission_granted'];
            
            // $excortList = $this->Gatepass->find("list", array(
            //     "conditions"    => array(
            //         ""
            //     ),
            // ));
            $escort_team_members = $this->getName($gatepassData['Gatepass']['escort_team'],"EscortTeam","members");
            $escortForceNo = array();
            foreach (explode(",", $escort_team_members) as $escort_team_memberkey => $escort_team_membervalue) {
                $escortForceNo[] = $this->User->field("force_number",array("User.id"=>$escort_team_membervalue));
            }
            $variables['escort']            = $this->getName($gatepassData['Gatepass']['escort_team'],"EscortTeam","name")."(".implode(",", $escortForceNo).")";//$gatepassData['Gatepass']['gatepass_type']=='Transfer'
            $variables['purpose']           = $gatepassData['Gatepass']['purpose'];
            $variables['destination']           = '';
            // conditions start for destination according to module
            // debug($gatepassData['Gatepass']['model_name']);
            if($gatepassData['Gatepass']['model_name']=="PrisonerTransfer"){
                $transfer_to_station_id = $this->PrisonerTransfer->field("transfer_to_station_id", array("PrisonerTransfer.id"=>$gatepassData['Gatepass']['reference_id']));
                $variables['destination'] = "Prison  :".$this->Prison->field("name", array("Prison.id"=>$transfer_to_station_id));
            }
            $this->loadModel("Court");
            if($gatepassData['Gatepass']['model_name']=="Courtattendance"){
                $court_id = $this->Courtattendance->field("court_id", array("Courtattendance.id"=>$gatepassData['Gatepass']['reference_id']));
                $variables['destination'] = "Court Name : ".$this->Court->field("name", array("Court.id"=>$court_id));
            }
            $this->loadModel("Hospital");
            if($gatepassData['Gatepass']['model_name']=="MedicalSeriousIllRecord"){
                $hospital_id = $this->MedicalSeriousIllRecord->field("hospital_id", array("MedicalSeriousIllRecord.id"=>$gatepassData['Gatepass']['reference_id']));
                $variables['destination'] = "Hospital Name : ".$this->Hospital->field("name", array("Hospital.id"=>$hospital_id));
                $variables['purpose']           = "Medical";
            }

            $prisonerList = $this->Gatepass->find("list",array(
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type'  => 'left',
                        'conditions'=> array('Gatepass.prisoner_id = Prisoner.id'),
                    ),
                ),
                "conditions"    => array(
                    // "Gatepass.escort_team"  => $gatepassData['Gatepass']['escort_team'],
                    "Gatepass.gp_no"  => $gatepassData['Gatepass']['gp_no'],
                    // "Gatepass.gp_date"  => $gatepassData['Gatepass']['gp_date'],
                    // "Gatepass.gatepass_type"  => $gatepassData['Gatepass']['gatepass_type'],
                ),
                "fields"    => array(
                    "Gatepass.id",
                    'Prisoner.prisoner_no',
                ),
            ));
            // debug($gatepassData['Gatepass']['model_name']);
            $variables['permission_for']    = (isset($prisonerList) && count($prisonerList)>0) ? implode(", ", $prisonerList) : '';
            // =============================================================
            $variables['prison_name']       = $this->getName($gatepassData['Gatepass']['prison_id'],"Prison","name");
            $variables['gatepass_date']     = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($gatepassData['Gatepass']['gp_date']));
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
           
           
            echo $template;
            // echo $this->htmlToPdf($template); exit;
        }
        else 
        {
            return 'FAIL';
        } 
        // exit;
    }

    // listing for process the discharge module
    public function gatepassGroupList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Gatepass.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status !='=>'Saved');
            $condition      += array('Gatepass.status !='=>'Review-Rejected');
            $condition      += array('Gatepass.approval_status'=>'Draft');
        }   
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Verified'; 
                $remark = '';
                // if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                // {
                //     if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                //     {
                //         $status = $this->request->data['ApprovalProcessForm']['type']; 
                //         $remark = $this->request->data['ApprovalProcessForm']['remark'];
                //     }
                // }
                $items = $this->request->data['ApprovalProcess'];
                //==============================================================
                if(count($items) > 0)
                {
                    $prison_id = $this->Session->read('Auth.User.prison_id');
                    $login_user_id = $this->Session->read('Auth.User.id');
                    $i = 0;
                    $data = array(); $idList = array();
                    foreach($items as $item)
                    {
                        $idList[] = $this->Gatepass->field("gp_no", array("Gatepass.id"=>$item['fid']));
                        $data[$i]['ApprovalProcess'] = $item;
                        $data[$i]['ApprovalProcess']['prison_id'] = $prison_id;
                        $data[$i]['ApprovalProcess']['model_name'] = "Gatepass";
                        $data[$i]['ApprovalProcess']['status'] = "Approved";
                        $data[$i]['ApprovalProcess']['remark'] = "";
                        $data[$i]['ApprovalProcess']['user_id'] = $login_user_id;
                        $i++;
                    }
                    if(count($data) > 0)
                    {
                        // debug($idList);exit;
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                            $fields = array(
                                'Gatepass.is_verify'    => 1,
                                'Gatepass.verify_datetime'    => "'".date("Y-m-d H:i:s")."'",
                            );
                            $status = 'Verified'; 
                        }else{
                            $fields = array(
                                'Gatepass.approval_status'    => "'Approved'",
                                'Gatepass.approve_datetime'    => "'".date("Y-m-d H:i:s")."'",
                            );
                            $status = 'Approved'; 
                        }
                       
                        
                        $conds = array(
                            "Gatepass.gp_no in ('".implode("','", $idList)."')",
                        );
                        $db = ConnectionManager::getDataSource('default');
                        $db->begin();
                        if($this->ApprovalProcess->saveAll($data))
                        {
                            if($this->auditLog('ApprovalProcess', 'approval_processes', 0, 'Add', json_encode($data)))
                            {
                                if($this->Gatepass->updateAll($fields, $conds))
                                {
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
                        else 
                        {
                            $db->rollback();
                            $result = 0;
                        }
                    }
                }
                
                //===============================================================-=

                if($result == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if($status=="Verified"){
                            $this->Session->write('message','Verified Successfully !');
                        }
                        if($status=="Approved"){
                            $this->Session->write('message','Approved Successfully !');
                        }
                        
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect('gatepassGroupList');
            }
        }
        $prisonerListData = $this->Gatepass->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Gatepass.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
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

        $gatepassType = $this->Gatepass->find("list", array(
            "fields"    => array(
                "Gatepass.gatepass_type",
                "Gatepass.gatepass_type",
            ),
            "group"     => array(
                "Gatepass.gatepass_type",
            ),
        ));

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'statusListData'     => $statusList,
            'default_status'    => $default_status,
            'gatepassType'    => $gatepassType
        ));
    }

    public function gatepassGroupListAjax(){
        $this->layout   = 'ajax';
        $searchData = $this->params['named'];
        $condition              = array(
            'Gatepass.is_trash'      => 0,
            'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
        );
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $condition += array("Gatepass.approval_status" => "Approved");
            $condition += array("Gatepass.gatepass_status" => "IN");
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Gatepass.approval_status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Gatepass.approval_status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Gatepass.status !='=>'Draft');
                $condition      += array('Gatepass.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Gatepass.approval_status'=>'Draft');
            }   
        }
        // debug($this->Session->read('Auth.User.usertype_id'));
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $gatepass_status = 'OUT';
            $condition += array(
                'Gatepass.gatepass_status'   => 'OUT',
            );
        }

        if(isset($this->params['named']['gatepass_status']) && $this->params['named']['gatepass_status'] != ''){
            if(isset($condition['Gatepass.gatepass_status'])){
                unset($condition['Gatepass.gatepass_status']);
            }
            $gatepass_status = $this->params['named']['gatepass_status'];
            $condition += array(
                'Gatepass.gatepass_status'   => $gatepass_status,
            );
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Gatepass.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['gatepass_type']) && $this->params['named']['gatepass_type'] != ''){
            $gatepass_type = $this->params['named']['gatepass_type'];
            $condition += array(
                'Gatepass.gatepass_type'   => $gatepass_type,
            );
        }
        if(isset($this->params['named']['is_verify']) && $this->params['named']['is_verify'] != ''){
            $is_verify = $this->params['named']['is_verify'];
            $condition += array(
                'Gatepass.is_verify'   => $is_verify,
            );
        }

        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "Gatepass.gp_date between ? and ?" => array(date("Y-m-d", strtotime($date_from)),date("Y-m-d", strtotime($date_to))),
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
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            "fields"        => array(
                "Gatepass.gp_no",
                "Gatepass.gp_date",
                "Gatepass.gatepass_type",
                "Gatepass.status",
                "Gatepass.approval_status",
                "Gatepass.is_verify",
                "Gatepass.inverification_verify",
                "max(Gatepass.id) as id",
            ),
            "group"        => array(
                "Gatepass.gp_no",
                "Gatepass.gp_date",
                "Gatepass.gatepass_type",
                "Gatepass.status",
                "Gatepass.approval_status",
                "Gatepass.is_verify",
                "Gatepass.inverification_verify",
            ),
            'order'         => array(
                'Gatepass.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Gatepass');
        // debug($condition);
        $this->set(array(
            'datas'         => $datas,
            'searchData'    => $searchData,
        ));
    }

    public function updatePhysicalProperty(){
        $this->loadModel('Gatepass');
        //debug($this->data);exit;

        $this->loadModel('PrisonerTransfer'); 
        $this->loadModel('PrisonerTransferPhysicalProperty'); 
        $this->loadModel('PrisonerTransferCashProperty'); 


         $transferId = $this->request->data['Gatepass']['id'];
         //debug($this->request->data);exit;
         $items = $this->request->data['PrisonerTransferPhysicalProperty'];
         $cashItems =  $this->request->data['PrisonerTransferCashProperty'];
         foreach ($items as $item) {
                if(isset($item['quantity']) && $item['quantity'] != ''){
                    $updateFields = array(
                        'PrisonerTransferPhysicalProperty.rcpt_rcv_quentity'           => $item['quantity']
                    );
                    $updateConds =array(
                        'PrisonerTransferPhysicalProperty.id'    => $item['id'],
                    );

                    $this->PrisonerTransferPhysicalProperty->updateAll($updateFields, $updateConds);
                }
             
         }
         foreach ($cashItems as $cashItem) {
                if(isset($cashItem['amount']) && $cashItem['amount'] != ''){
                    $updateFields = array(
                        'PrisonerTransferCashProperty.rcpt_rcv_amount' => $cashItem['amount']
                    );
                    $updateConds =array(
                        'PrisonerTransferCashProperty.id'    => $cashItem['id'],
                    );

                    $this->PrisonerTransferCashProperty->updateAll($updateFields, $updateConds);
                }
             
         }

        
         $prisonerTransfer = $this->PrisonerTransfer->findById($transferId);
         $prisonerTransfer['PrisonerTransfer']['ward_id'] = $this->request->data['Gatepass']['ward_id'];
         $prisonerTransfer['PrisonerTransfer']['ward_cell_id'] = $this->request->data['Gatepass']['ward_cell_id'];
         $prisonerTransfer['PrisonerTransfer']['remarks'] = $this->request->data['Gatepass']['verify_remark'];
         $prisonerTransfer['PrisonerTransfer']['instatus'] = 'Saved';

         $this->PrisonerTransfer->saveAll($prisonerTransfer);


         echo "SUCC";
         //$transferId = 
         exit;
    }
}