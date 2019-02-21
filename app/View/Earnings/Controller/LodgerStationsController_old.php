<?php
App::uses('AppController', 'Controller');
class LodgerStationsController    extends AppController {
	public $layout='table';
	public $uses=array('LodgerStation');
	public function index($lodger_type='at') {

		$prison_id = $this->Auth->user('prison_id');
		/*
		 *Code for add the court attendance records
		*/					
		if(isset($this->data['LodgerStation']) && is_array($this->data['LodgerStation']) && count($this->data['LodgerStation']) >0){
            // debug($this->data);exit;
			if(isset($this->data['LodgerStation']['date_of_lodging']) && $this->data['LodgerStation']['date_of_lodging'] != ''){
				$this->request->data['LodgerStation']['date_of_lodging'] = date('Y-m-d H:i', strtotime($this->request->data['LodgerStation']['date_of_lodging']));
			}
			if(isset($this->data['LodgerStation']['uuid']) && $this->data['LodgerStation']['uuid'] == ''){
				$uuidArr = $this->LodgerStation->query("select uuid() as code");
				$this->request->data['LodgerStation']['uuid'] 		= $uuidArr[0][0]['code'];
			}	
            $this->request->data['LodgerStation']['prison_id'] = $this->Session->read('Auth.User.prison_id');
			if($this->LodgerStation->save($this->data)){
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                if($lodger_type=='at')
                {
                    $this->redirect('/LodgerStations');
                }
                else 
                {
                    $this->redirect('/LodgerStations/index/out');
                }
			}else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
			}
		}
		/*
		 *Code for edit the Gate Pass records
		*/				
        if(isset($this->data['LodgerStationEdit']['id']) && (int)$this->data['LodgerStationEdit']['id'] != 0){
            if($this->LodgerStation->exists($this->data['LodgerStationEdit']['id'])){
                $this->data = $this->LodgerStation->findById($this->data['LodgerStationEdit']['id']);
                $this->request->data['LodgerStation']['date_of_lodging'] = date('d-m-Y',strtotime($this->data['LodgerStation']['date_of_lodging']));
            }
        }
        /*
         *Code for delete the Gate Pass records
         */	
        if(isset($this->data['LodgerStationDelete']['id']) && (int)$this->data['LodgerStationDelete']['id'] != 0){
            if($this->LodgerStation->exists($this->data['LodgerStationDelete']['id'])){
                $this->LodgerStation->id = $this->data['LodgerStationDelete']['id'];
                if($this->LodgerStation->saveField('is_trash',1)){
					$this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                }else{
					$this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
                }
                if($lodger_type=='at')
                {
                    $this->redirect('/LodgerStations/index/out');
                }
                else 
                {
                    $this->redirect('/LodgerStations/index/out');
                }		                
            }
        }	
		//get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        //get prisoner list
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id !='   => $prison_id,
                'Prisoner.status'      => 'Approved',
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        // debug($prisonerList);
        // $serchPrisonerList = array();
        $serchPrisonerList = $this->LodgerStation->find('list', array(                    
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type'  => 'left',
                    'conditions'=> array('LodgerStation.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'LodgerStation.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id !='   => $prison_id
            ),
            'order'         => array(
                'LodgerStation.prisoner_id'
            ),
        ));
        
        $heading = 'Lodger at station';
        if($lodger_type == 'out')
        {
            $heading = 'Lodger out of station';
        }

        //Approval status START --
        $default_status = ''; $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //if form submits 
        //debug($this->request->data['ApprovalProcess']); exit;
        if($this->request->is(array('post','put')))
        {
            // debug($this->request); exit;
            //if search data exists 
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
                $approveProcess = $this->setApprovalProcess($items, 'LodgerStation', $status, $remark);
                if($approveProcess == 1)
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
        else 
        {
            if(strstr($this->referer(), "dashboard")){
                $this->request->data['Search']['status'] = 'Approved';
            }else{
                $this->request->data['Search']['status'] = $default_status;
            }
            //get default status reords
        }
        //Approval status END --

		$this->set(array(
			'prisonList'			=> $prisonList,
			'prisonerList'			=> $prisonerList,
            'heading'               => $heading,
            'lodger_type'           => $lodger_type,
            'default_status'        => $default_status,
            'statusList'            => $statusList,
            'serchPrisonerList'     => $serchPrisonerList,
		));
	
		
    }
    public function indexAjax(){
		$this->layout 			= 'ajax';
    	$condition 				= array(
    		'LodgerStation.is_trash'		=> 0,
    	);	
        // debug($this->data);
        $lodger_type = 'at';
        $prisoner_id = '';
        $original_prison = '';
        $search_status = '';
        $date_of_lodging = '';
        $destination_prison = '';
        $from_date = '';
        $to_date = '';
        if(isset($this->params['named']['lodger_type']) && $this->params['named']['lodger_type'] != ''){
            $lodger_type = $this->params['named']['lodger_type'];
            $condition      += array(
                'LodgerStation.lodger_type' => $lodger_type
            );
        }
        if(isset($this->data['Search']['status']) && $this->data['Search']['status'] != ''){
            $search_status = $this->data['Search']['status'];
            $condition      += array(
                'LodgerStation.status' => $search_status,
            );
        }  
        if(isset($this->data['Search']['prisoner_id']) && $this->data['Search']['prisoner_id'] != ''){
            $prisoner_id = $this->data['Search']['prisoner_id'];
            $condition      += array(
                'LodgerStation.prisoner_id' => $prisoner_id,
            );
        } 
        if(isset($this->data['Search']['from_date']) && $this->data['Search']['from_date'] != '' && isset($this->data['Search']['to_date']) && $this->data['Search']['to_date'] != ''){
            $from_date = date("Y-m-d", strtotime($this->data['Search']['from_date']));
            $to_date = date("Y-m-d", strtotime($this->data['Search']['to_date']));
            $condition      += array(
                "date(LodgerStation.date_of_lodging) between '".$from_date."' and '".$to_date."'",
            );
        } 
        if(isset($this->data['Search']['original_prison']) && $this->data['Search']['original_prison'] != ''){
            $original_prison = $this->data['Search']['original_prison'];
            $condition      += array(
                'LodgerStation.original_prison' => $original_prison
            );
        } 
        if(isset($this->data['Search']['destination_prison']) && $this->data['Search']['destination_prison'] != ''){
            $destination_prison = $this->data['Search']['destination_prison'];
            $condition      += array(
                'LodgerStation.destination_prison' => $destination_prison
            );
        }  	
        // debug($this->data['Search']);
        // debug($condition);
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','LodgerStation_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','LodgerStation_report_'.date('d_m_Y').'.doc');
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
    			'LodgerStation.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('LodgerStation');
    	//echo '<pre>'; print_r($datas); exit;
    	$this->set(array(
    		'datas'	               => $datas,
            'prisoner_id'          => $prisoner_id,
            'date_of_lodging'      => $date_of_lodging,
            'original_prison'      => $original_prison,
            'destination_prison'   => $destination_prison,
            'lodger_type'          => $lodger_type,
            'from_date'             => $from_date,
            'to_date'               => $to_date,
    	));     	
    }

    public function out($lodger_type='at') {
        $prison_id = $this->Auth->user('prison_id');
        /*
         *Code for add the court attendance records
        */                  
        if(isset($this->data['LodgerStation']) && is_array($this->data['LodgerStation']) && count($this->data['LodgerStation']) >0){
            if(isset($this->data['LodgerStation']['date_of_lodging']) && $this->data['LodgerStation']['date_of_lodging'] != ''){
                $this->request->data['LodgerStation']['date_of_lodging'] = date('Y-m-d', strtotime($this->request->data['LodgerStation']['date_of_lodging']));
            }
            if(isset($this->data['LodgerStation']['uuid']) && $this->data['LodgerStation']['uuid'] == ''){
                $uuidArr = $this->LodgerStation->query("select uuid() as code");
                $this->request->data['LodgerStation']['uuid']       = $uuidArr[0][0]['code'];
            }                   
            if($this->LodgerStation->save($this->data)){
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                if($lodger_type=='at')
                {
                    $this->redirect('/LodgerStations');
                }
                else 
                {
                    $this->redirect('/LodgerStations/index/out');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        /*
         *Code for edit the Gate Pass records
        */              
        if(isset($this->data['LodgerStationEdit']['id']) && (int)$this->data['LodgerStationEdit']['id'] != 0){
            if($this->LodgerStation->exists($this->data['LodgerStationEdit']['id'])){
                $this->data = $this->LodgerStation->findById($this->data['LodgerStationEdit']['id']);
                $this->request->data['LodgerStation']['date_of_lodging'] = date('d-m-Y',strtotime($this->data['LodgerStation']['date_of_lodging']));
            }
        }
        /*
         *Code for delete the Gate Pass records
         */ 
        if(isset($this->data['LodgerStationDelete']['id']) && (int)$this->data['LodgerStationDelete']['id'] != 0){
            if($this->LodgerStation->exists($this->data['LodgerStationDelete']['id'])){
                $this->LodgerStation->id = $this->data['LodgerStationDelete']['id'];
                if($this->LodgerStation->saveField('is_trash',1)){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                }else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
                }
                if($lodger_type=='at')
                {
                    $this->redirect('/LodgerStations/index/at');
                }
                else 
                {
                    $this->redirect('/LodgerStations/index/out');
                }                       
            }
        }   
        //get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        //get prisoner list
          $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id !='   => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        $heading = 'Lodger at station';
        if($lodger_type == 'out')
        {
            $heading = 'Lodger out of station';
        }

        //Approval status START --
        $default_status = ''; $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //if form submits 
        //debug($this->request->data['ApprovalProcess']); exit;
        if($this->request->is(array('post','put')))
        {
            //debug($this->request); exit;
            //if search data exists 
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
                $approveProcess = $this->setApprovalProcess($items, 'LodgerStation', $status, $remark);
                if($approveProcess == 1)
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
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }
        //Approval status END --

        $this->set(array(
            'prisonList'            => $prisonList,
            'prisonerList'          => $prisonerList,
            'heading'               => $heading,
            'lodger_type'           => $lodger_type,
            'default_status'        => $default_status,
            'statusList'            => $statusList
        ));
    
        
    }
    public function outAjax(){
        $this->layout           = 'ajax';
        $condition              = array(
            'LodgerStation.is_trash'        => 0,
        );  
        //print_r($this->params); exit;
        $lodger_type = 'at';
        if(isset($this->params['named']['lodger_type']) && $this->params['named']['lodger_type'] != '')
        {
            $lodger_type = $this->params['named']['lodger_type'];
            $condition      += array(
            'LodgerStation.lodger_type' => $lodger_type
        );
        }   
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','LodgerStation_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','LodgerStation_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'LodgerStation.modified'    => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('LodgerStation');
        //echo '<pre>'; print_r($datas); exit;
        $this->set(array(
            'datas'                     => $datas,
            'lodger_type'               => $lodger_type
        ));         
    }

    public function getPrisoner(){
        $this->layout = 'ajax';
        $prisonernameList = $this->Prisoner->find("list", array(
            "conditions"    => array(
                "Prisoner.prison_id"    => $this->data['prison_id'],
                "Prisoner.is_trash"    => 0,
                "Prisoner.is_enable"    => 1,
                "Prisoner.present_status"    => 1,
                'Prisoner.transfer_status !='        => 'Approved',
                'Prisoner.status'        => 'Approved',
            ),
            "fields"        => array(
                "Prisoner.id",
                "Prisoner.fullname",
            ),
        ));
        $this->set(array(
            'prisonernameList'  => $prisonernameList,
            'model_name'        => $this->data['model_name'],
        ));
    }

    public function getLodgerData($id){
        $this->LodgerStation->recursive = 0;
        return $this->LodgerStation->findById($id);
    }
}