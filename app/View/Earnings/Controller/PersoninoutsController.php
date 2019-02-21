<?php
App::uses('AppController', 'Controller');
class PersoninoutsController  extends AppController {
	public $layout='table';
   public $uses=array('Personinout','User','Usertype');
	public function index() {
		$this->loadModel('Personinout'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        if(isset($this->data['PersoninoutDelete']['id']) && (int)$this->data['PersoninoutDelete']['id'] != 0){
        	
            $this->Personinout->id=$this->data['PersoninoutDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Personinout->saveField('is_trash',1))
            {
                if($this->auditLog('Personinout', 'personinouts', $this->data['PersoninoutDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
    }
    public function indexAjax(){
      	$this->loadModel('Personinout'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $condition = array('Personinout.is_trash'   => 0);
       
        if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
             $from = $this->params['named']['from'];
             $to = $this->params['named']['to'];
              $condition =array('date(Personinout.person_in_out_date) BETWEEN ? and ?' => array($from , $to));
            //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Personinout.person_in_out_date'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Personinout');

        $this->set(array(
            'from'         => $from,
            'to'         => $to,
            'datas'             => $datas,
        )); 

      

    }
	public function add() { 
		$this->loadModel('Personinout');
		
		 //debug($staffcategory_id);
		if (isset($this->data['Personinout']) && is_array($this->data['Personinout']) && count($this->data['Personinout'])>0){			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if ($this->Personinout->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Personinout']['id']) && (int)$this->data['Personinout']['id'] != 0)
                {
                    $refId  = $this->data['Personinout']['id'];
                    $action = 'Edit';
                }
				if($this->auditLog('Personinout', 'personinouts', $refId, $action, json_encode($this->data)))
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
                    $this->Session->write('message','Failed to save the record. Please, try again.');
                }
			} else {
				$db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Failed to save the record. Please, try again.');
			}
		}
        if(isset($this->data['PersoninoutEdit']['id']) && (int)$this->data['PersoninoutEdit']['id'] != 0){
            if($this->Personinout->exists($this->data['PersoninoutEdit']['id'])){
                $this->data = $this->Personinout->findById($this->data['PersoninoutEdit']['id']);
            }
        }
         $gateKeepers=$this->User->find('list',array(
                'fields'        => array(
                    'User.id',
                    'User.first_name',
                ),
                'conditions'=>array(
                  'User.is_enable'=>1,
                  'User.is_trash'=>0,
                  'User.usertype_id'=>10,//Gate keeper User
                ),
                'order'=>array(
                  'User.first_name'
                )
          ));

          $this->set(compact('gateKeepers'));
       
       
	}
}