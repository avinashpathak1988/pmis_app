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
    public function gatepassTransferListAjax() {
        $this->layout   = 'ajax';
        $searchData = $this->params['named'];
        $condition              = array(
            'Gatepass.is_trash'      => 0,
            'date(Gatepass.created)'      => date("Y-m-d"),
            'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
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
            'order'         => array(
                'Gatepass.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Gatepass');
        $this->set(array(
            'datas'         => $datas,
            'searchData'    => $searchData,
        ));

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
        debug($this->data);exit;
        if($this->Gatepass->saveAll($this->data)){
            echo "SUCC";
        }else{
            echo "FAIL";
        }
        exit;
    }
}