<?php
App::uses('AppController', 'Controller');
class ShiftDeploymentsController  extends AppController {
	public $layout='table';
    public $uses = array('Shift','Officer','ShiftDeployment','AreaOfDeployment');
	public function index() { 
         $menuId = $this->getMenuId("/ShiftDeployments");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        //debug($this->data); exit;
        if(isset($this->data['ShiftDeploymentDelete']['id']) && (int)$this->data['ShiftDeploymentDelete']['id'] != 0){
            $this->ShiftDeployment->id=$this->data['ShiftDeploymentDelete']['id'];

            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->ShiftDeployment->saveField('is_trash',1))
            {
                if($this->auditLog('ShiftDeployment', 'shift_deployments', $this->data['ShiftDeploymentDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
        $shiftList = $this->Shift->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Shift.id',
                'Shift.name',
            ),
            'conditions'    => array(
                'Shift.is_enable'      => 1,
                'Shift.is_trash'       => 0
            ),
            'order'         => array(
                'Shift.name'
            ),
        ));
        $forceList = $this->Officer->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Officer.id',
                'Officer.force_number',
            ),
            'conditions'    => array(
                'Officer.is_enable'      => 1,
                'Officer.is_trash'       => 0,
                'Officer.force_number !='    => ''
            ),
            'order'         => array(
                'Officer.force_number'
            ),
        ));
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
       $arealist = $this->AreaOfDeployment->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'AreaOfDeployment.name',
            ),
            'order'         => array(
                'AreaOfDeployment.name'
            ),
        ));
        $this->set(array(
            'shiftList'  => $shiftList,
            'forceList'  => $forceList,
            'arealist'   => $arealist,
            'prisonList' => $prisonList,
            
        )); 
    }
    public function indexAjax(){
     
        $this->layout = 'ajax';
        $shift_id = '';
        $force_id = '';
        $deploy_area = '';
        $shift_date = '';
        $created = '';
        $condition = array('ShiftDeployment.is_trash' => 0, 'ShiftDeployment.prison_id' => $this->Session->read('Auth.User.prison_id'));

        debug($this->params['named']);
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('ShiftDeployment.prison_id' => $prison_id );
        }

        if(isset($this->params['named']['shift_id']) && $this->params['named']['shift_id'] != ''){
            $shift_id = $this->params['named']['shift_id'];
            $condition += array("ShiftDeployment.shift_id"=>$shift_id);
        } 
        if(isset($this->params['named']['force_id']) && $this->params['named']['force_id'] != ''){
            $force_id = $this->params['named']['force_id'];
            $condition += array("ShiftDeployment.user_id like '%".$force_id."%'");
        } 
        if(isset($this->params['named']['deploy_area']) && $this->params['named']['deploy_area'] != ''){
            $area_name = $this->params['named']['deploy_area'];
            $condition += array("ShiftDeployment.deploy_area"=>$area_name);
        } 
        if(isset($this->params['named']['shift_date']) && $this->params['named']['shift_date'] != ''){
            $date = $this->params['named']['shift_date'];
            $condition += array("ShiftDeployment.shift_date"=>date('Y-m-d', strtotime($date)));
        } 
         if(isset($this->params['named']['created']) && $this->params['named']['created'] != ''){
            $created = $this->params['named']['created'];
            $condition += array("ShiftDeployment.shift_date"=>date('Y-m-d', strtotime($created)));
        } 
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','shift_deployments_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','shift_deployments_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','shift_deployments_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
         debug($condition);

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'ShiftDeployment.name'
            ),            
            'limit'         => 10,
        );
        $datas  = $this->paginate('ShiftDeployment');
        $this->set(array(
            'datas' => $datas,
            'shift_id' => $shift_id,
            'force_id' => $force_id,
            'deploy_area' => $deploy_area,
            'shift_date'       => $shift_date,
        )); 
    }
	public function add() { 
        $menuId = $this->getMenuId("/ShiftDeployments");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
		

		if (isset($this->data['ShiftDeployment']) && is_array($this->data['ShiftDeployment']) && count($this->data['ShiftDeployment'])>0){	//debug($this->data);exit;
                $this->request->data['ShiftDeployment']['user_id'] = implode(',',$this->data['ShiftDeployment']['user_id']);
                $is_exist = $this->duplicateforceid($this->data['ShiftDeployment']['shift_id'],$this->data['ShiftDeployment']['user_id'], $this->data['ShiftDeployment']['shift_date']);

                $this->request->data['ShiftDeployment']['shift_date'] = date('Y-m-d',strtotime($this->request->data['ShiftDeployment']['shift_date']));
                // debug($this->request->data); exit;

                if($is_exist > 0)
                {
                     $this->Session->write('message_type','error');
                        $this->Session->write('message','The record already exists for this day .');
                        $this->redirect(array('action'=>'index'));
                }
                else
                 {   
                    
                    $this->request->data['ShiftDeployment']['shift_date'] = date('Y-m-d', strtotime($this->data['ShiftDeployment']['shift_date']));	
                    //debug($this->request->data);exit;
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();
        			if ($this->ShiftDeployment->save($this->data)) {
        				$refId = 0;
                        $action = 'Add';
                        if(isset($this->data['ShiftDeployment']['id']) && (int)$this->data['ShiftDeployment']['id'] != 0)
                        {
                            $refId  = $this->data['ShiftDeployment']['id'];
                            $action = 'Edit';
                        }
                        if($this->auditLog('ShiftDeployment', 'shift_deployments', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Record saved successfully.');
                            $this->redirect(array('action'=>'index'));
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','The record could not be saved. Please, try again.');
                        }
        			} else {
        				$db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','The record could not be saved. Please, try again.');
        			   }
        		    }
        }
        if(isset($this->data['ShiftDeploymentEdit']['id']) && (int)$this->data['ShiftDeploymentEdit']['id'] != 0){
            if($this->ShiftDeployment->exists($this->data['ShiftDeploymentEdit']['id'])){
                $this->data = $this->ShiftDeployment->findById($this->data['ShiftDeploymentEdit']['id']);
            }
        }
       $shiftList = $this->Shift->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Shift.id',
                'Shift.name',
            ),
            'conditions'    => array(
                'Shift.is_enable'      => 1,
                'Shift.is_trash'       => 0
            ),
            'order'         => array(
                'Shift.name'
            ),
        ));
        $forceList = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.force_number',
            ),
            'conditions'    => array(
                'User.is_enable'      => 1,
                'User.is_trash'       => 0,
                'User.force_number !='    => ''
            ),
            'order'         => array(
                'User.force_number'
            ),
        ));
        $deploymentlist = $this->AreaOfDeployment->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'AreaOfDeployment.id',
                'AreaOfDeployment.name',
            ),
            'conditions'    => array(
                'AreaOfDeployment.is_enable'      => 1,
                'AreaOfDeployment.is_trash'      => 0,
                
            ),
            'order'         => array(
                'AreaOfDeployment.id'
            ),
        ));
        $this->set(array(
            'shiftList'  => $shiftList,
            'forceList'  => $forceList,
            'deploymentlist' => $deploymentlist,
        ));
	}

    // duplicate authentication partha code
    public function duplicateforceid($shift_id='', $force_id='', $shift_date='') {

           $is_exist = $this->ShiftDeployment->find('count',array(
                                        'conditions'=>array(
                                            'ShiftDeployment.shift_id' => $shift_id,
                                            'ShiftDeployment.user_id IN ("'.$force_id.'")',
                                            'ShiftDeployment.shift_date' => date('Y-m-d',strtotime($shift_date ))

                                        ),

           ));    
           return  $is_exist;     

    }
    public function forceList(){
        $this->autoRender=false;
        $this->layout='ajax';
        //debug($this->data);
        $shiftDeploymentList = $this->ShiftDeployment->find('all',array(
            'conditions'=>array(
                'ShiftDeployment.shift_id' => $this->data['shift_id'],
                'ShiftDeployment.shift_date' => date('Y-m-d',strtotime($this->data['shift_date'] ))
            ),
           ));    
        //debug($shiftDeploymentList);
        $forceno=array();
        foreach ($shiftDeploymentList as $key => $value) {
            if($value['ShiftDeployment']['shift_id']!=12){
                $forceno[] = $value['ShiftDeployment']['user_id'];
            }
        }
        $forceIDs=implode(',', $forceno);
        $condition=array();
        if($forceIDs!=''){
            $condition=array('User.id NOT IN ('.$forceIDs.')');
        }
        
        $forceList = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.force_number',
            ),
            'conditions'    => array(
                'User.is_enable'      => 1,
                'User.is_trash'       => 0,
                'User.force_number !='    => '',
            )+$condition,
            'order'         => array(
                'User.force_number'
            ),
        ));
          
        if(is_array($forceList) && count($forceList)>0){
            echo '<option>-- Select Force Id --</option>';
            foreach($forceList as $forceListKey=>$forceListVal){
                echo '<option value="'.$forceListKey.'">'.$forceListVal.'</option>';
            }
        }else{
                echo '<option>-- Select Force Id --</option>';
        }
    }
}