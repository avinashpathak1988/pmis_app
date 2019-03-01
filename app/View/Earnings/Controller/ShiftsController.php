<?php
App::uses('AppController', 'Controller');
class ShiftsController  extends AppController {
	public $layout='table';
	public function index() { 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        if(isset($this->data['ShiftDelete']['id']) && (int)$this->data['ShiftDelete']['id'] != 0){
        	
            $this->Shift->id=$this->data['ShiftDelete']['id'];
            if($this->Shift->saveField('is_trash',1))
            {
                if($this->auditLog('Shift', 'shifts', $this->data['ShiftDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    // $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else {
                    // $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete failed');
                }
            }
            else 
            {
                // $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete failed');
            }
        }
    }
    public function indexAjax(){ 
        $this->layout = 'ajax';
        $name  = '';
        $condition = array('Shift.is_trash' => 0);
        if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
            $name = $this->params['named']['name'];
            $condition += array("Shift.name LIKE '%$name%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Shift.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Shift');
        $this->set(array(
            'name'  => $name,
            'datas' => $datas,
        )); 
    }
	public function add() { 
		
		 //debug($staffcategory_id);
		if (isset($this->data['Shift']) && is_array($this->data['Shift']) && count($this->data['Shift'])>0){
            if(isset($this->data['Shift']['start_time']) && $this->data['Shift']['start_time']!=''){
                $this->request->data['Shift']['start_time'] = date("H:i:s",strtotime($this->data['Shift']['start_time'].":00"));
            }
            if(isset($this->data['Shift']['end_time']) && $this->data['Shift']['end_time']!=''){
                $this->request->data['Shift']['end_time'] = date("H:i:s",strtotime($this->data['Shift']['end_time'].":00"));
            }			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if ($this->Shift->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Shift']['id']) && (int)$this->data['Shift']['id'] != 0)
                {
                    $refId  = $this->data['Shift']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('Shift', 'shifts', $refId, $action, json_encode($this->data)))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','The record has been saved.');
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
        if(isset($this->data['ShiftEdit']['id']) && (int)$this->data['ShiftEdit']['id'] != 0){
            if($this->Shift->exists($this->data['ShiftEdit']['id'])){
                $this->data = $this->Shift->findById($this->data['ShiftEdit']['id']);
            }
        }
       
	}
}