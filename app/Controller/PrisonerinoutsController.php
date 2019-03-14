<?php
App::uses('AppController', 'Controller');
class PrisonerinoutsController  extends AppController {
	public $layout='table';
   public $uses=array('Prisonerinout','Prisoner','User','Usertype', 'Gatepass');
	public function index() {
		
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        $menuId = $this->getMenuId("/prisonerinouts");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        $categoryList=array("Prisoners"=>"Prisoners","Persons"=>"Persons");
        
        if(isset($this->data['PrisonerinoutDelete']['id']) && (int)$this->data['PrisonerinoutDelete']['id'] != 0){
        	
            $this->Prisonerinout->id=$this->data['PrisonerinoutDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Prisonerinout->saveField('is_trash',1))
            {
                if($this->auditLog('Prisonerinout', 'personerinouts', $this->data['PrisonerinoutDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete failed');
            }
        }
        $this->set(array(    
                'default_status'=>$default_status,
                'sttusListData'=>$statusList,
                'categoryList'=>$categoryList
      ));
    }
    public function indexAjax(){
      	
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $status='';
        $folow_from="";
        $folow_to="";
        $category="";
        $condition = array('Prisonerinout.is_trash'   => 0,
        'Prisonerinout.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );

       
        // if(isset($this->params['named']['folow_from']) && $this->params['named']['folow_from'] != '' && isset($this->params['named']['folow_to']) && $this->params['named']['folow_to'] != ''){
        //       $folow_from = $this->params['named']['folow_from'];
        //       $folow_to = $this->params['named']['folow_to'];
        //       $condition += array(
        //           "Prisonerinout.date between '".date("Y-m-d",strtotime($folow_from))."' and '".date("Y-m-d",strtotime($folow_to))."'"
            
        //     );
        //       //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
        //   }


          if(isset($this->params['named']['folow_from']) && $this->params['named']['folow_from'] != '' &&
           isset($this->params['named']['folow_to']) && $this->params['named']['folow_to'] != ''){
            $folow_from = $this->params['named']['folow_from'];
            $folow_to = $this->params['named']['folow_to'];

         $condition += array('Prisonerinout.date >= ' => date('Y-m-d', strtotime($folow_from)),
                              'Prisonerinout.date <= ' => date('Y-m-d', strtotime($folow_to))
                             );        
        } 
          if(isset($this->params['named']['category']) && $this->params['named']['category'] != ''){
              $category = $this->params['named']['category'];
              $condition += array(
                "Prisonerinout.category"=>$category
            
          );
          }
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
              $status = $this->params['named']['status'];
              $condition += array(
                  'Prisonerinout.status'   => $status,
              );
          }
           if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_inout_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_inout_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','prisoner_inout_report_'.date('d_m_Y').'.pdf');
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
            'order'         =>array(
                'Prisonerinout.date'
            ),            
            'limit'         => 20,
        );
         //debug($condition);
        $datas  = $this->paginate('Prisonerinout');

        $this->set(array(
            'folow_from'         => $folow_from,
            'folow_to'         => $folow_to,
            'category'         => $category, 
            'status'            =>$status,
            'datas'             => $datas,
        )); 
        

    }
    public function gatepassList(){
        $menuId = $this->getMenuId("/prisonerinouts/gatepassList");
                $moduleId = $this->getModuleId("station");
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
        $prison_id = $this->Session->read('Auth.User.prison_id');
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Prisonerinout.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Prisonerinout.status !='=>'Draft');
            $condition      += array('Prisonerinout.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Prisonerinout.status !='=>'Draft');
            $condition      += array('Prisonerinout.status !='=>'Saved');
            $condition      += array('Prisonerinout.status !='=>'Review-Rejected');
            $condition      += array('Prisonerinout.status'=>'Reviewed');
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
                $status = $this->setGatepass($items, 'Prisonerinout',$gatepassDetails);
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
                        "Discharge.prisoner_id = Prisoner.id",
                        'Discharge.prison_id' => $prison_id,
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
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
	                $data[$i]['Gatepass']['gatepass_type'] = 'Prisoner InOut';	 
	                $dischargeData = $this->Prisonerinout->findById($item['fid']);           
	                $data[$i]['Gatepass']['prisoner_id'] = $dischargeData['Prisonerinout']['prisoner_no'];
	                $notificationPrisoner[] = $dischargeData['Prisonerinout']['prisoner_no'];
                    $this->loadModel('EscortTeam');
                    // echo $gatepassDetails['escort_team'];
                    // debug($gatepassDetails);exit;
                      $this->EscortTeam->updateAll(array('EscortTeam.is_available'=>'"NO"'),array('EscortTeam.id'=>$gatepassDetails['escort_team'],
                        )
                    );
                    $this->Prisoner->updateAll(array('Prisoner.is_available'=>'"NO"'),array('Prisoner.id'=>$dischargeData['Prisonerinout']['prisoner_no'],
                        )
                    );
            	}                
                $i++;
            }
            // debug($data); exit;
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
     public function gatepassListAjax(){
          // $menuId = $this->getMenuId("/prisonerinouts/gatepassList");
          //       $moduleId = $this->getModuleId("station");
          //       $isAccess = $this->isAccess($moduleId,$menuId,'is_approve');
          //       if($isAccess != 1){
          //               $this->Session->write('message_type','error');
          //               $this->Session->write('message','Not Authorized!');
          //               $this->redirect(array('action'=>'../sites/dashboard')); 
          //       }
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $prisoner_no    = '';
        $status = '';
        $teamList ='';
        $this->loadModel('Prisonerinout');
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
                'EscortTeam.escort_type'  => "InOut",
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));
       
        $condition              = array(
            'Prisonerinout.is_trash'      => 0,
            'Prisonerinout.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                // 'Prisonerinout.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Prisonerinout.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Prisonerinout.status !='=>'Draft');
                $condition      += array('Prisonerinout.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Prisonerinout.status !='=>'Draft');
                $condition      += array('Prisonerinout.status !='=>'Saved');
                $condition      += array('Prisonerinout.status !='=>'Review-Rejected');
                $condition      += array('Prisonerinout.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Prisonerinout.prisoner_id'   => $prisoner_id,
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
                'Prisonerinout.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Prisonerinout');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
            'teamList'        => $teamList,
            'prisoner_no'     => $prisoner_no,
        ));
    }

  
	public function add() { 
          $menuId = $this->getMenuId("/prisonerinouts");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $id1=$this->Session->read('Auth.User.prison_id');
       // $id1=$this->Session->read('Auth.User.id');
		$gateKeepers=$this->User->find('list',array(
                'fields'        => array(
                    'User.id',
                    'User.first_name',
                ),
                'conditions'=>array(
                  'User.is_enable'=>1,
                  'User.is_trash'=>0,
                  'User.usertype_id'=>10,//Gate keeper User
                  'User.id'=>$id1
                ),
                'order'=>array(
                  'User.first_name'
                )
          ));
        $categoryList=array("Prisoners"=>"Prisoners","Persons"=>"Persons");
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
                $status = $this->setApprovalProcess($items, 'Prisonerinout', $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
                        
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
                $this->redirect(array('action'=>'index'));
            }
		 //debug($staffcategory_id);
		if (isset($this->data['Prisonerinout']) && is_array($this->data['Prisonerinout']) && count($this->data['Prisonerinout'])>0)
        {	
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if(isset($this->request->data['Prisonerinout']['date']) && $this->request->data['Prisonerinout']['date'] != ''){
                        // $date = $this->request->data['Courtattendance']['date'];
                        // $res = explode("-", $date);
                        // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                        // echo $changedDate; // prints 2014-10-24
                        $this->request->data['Prisonerinout']['date'] = date('Y-m-d', strtotime($this->request->data['Prisonerinout']['date']));
                    }
                    if (isset($this->request->data['Prisonerinout']['category']) && ($this->request->data['Prisonerinout']['category'] == 'Prisoners')){
                        $this->request->data['Prisonerinout']['time_in'] = 'N/A';
                    }
                    $this->request->data['Prisonerinout']['prison_id'] = $this->Session->read('Auth.User.prison_id');
            if ($this->Prisonerinout->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Prisonerinout']['id']) && (int)$this->data['Prisonerinout']['id'] != 0)
                {
                    $refId  = $this->data['Prisonerinout']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('Prisonerinout', 'personerinouts', $refId, $action, json_encode($this->data)))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');

                    if(isset($this->data['Prisonerinout']['id']) && ($this->data['Prisonerinout']['id'] != '')){
                    $this->Session->write('message','Record updated successfully.');
                    }
                    else{
                    $this->Session->write('message','Record saved successfully.');
                    }
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Failed to save the record. Please, try again.');
                }
            } else {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Failed to save the record. Please, try again.');
            }
		}
         // $this->Prisoner->updateAll(array('Prisoner.is_available'=>'"YES"'),array('Prisoner.id'=> $prisonerList,
                    //     )
                    // );

        if(isset($this->data['PrisonerinoutEdit']['id']) && (int)$this->data['PrisonerinoutEdit']['id'] != 0){
            if($this->Prisonerinout->exists($this->data['PrisonerinoutEdit']['id'])){
                $this->data = $this->Prisonerinout->findById($this->data['PrisonerinoutEdit']['id']);
            }
        }
       //get prisoner list
          $prison_id = $this->Session->read('Auth.User.prison_id');
          $prisonerList = $this->Prisoner->find('list', array(
            // 'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id'      => $prison_id,
                'Prisoner.is_available'   => "YES"
            ),
            // 'order'         => array(
            //     'Prisoner.prisoner_no'
            // ),
        ));
        $this->set(array(
            'prisonerList'    => $prisonerList,
             'gateKeepers'    => $gateKeepers,
             'categoryList'=>$categoryList
        ));
	}
}