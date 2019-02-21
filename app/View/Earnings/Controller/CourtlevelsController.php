<?php
App::uses('AppController', 'Controller');
class CourtlevelsController  extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Courtlevel'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        if(isset($this->data['CourtlevelDelete']['id']) && (int)$this->data['CourtlevelDelete']['id'] != 0){
            if($this->Courtlevel->exists($this->data['CourtlevelDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                  
                if($this->Courtlevel->updateAll(array('Courtlevel.is_trash'   => 1), array('Courtlevel.id' => $this->data['CourtlevelDelete']['id']))){
                    if($this->auditLog('Courtlevel', 'courtlevels', $this->data['CourtlevelDelete']['id'], 'Trash', json_encode(array('CourtlevelDelete.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
                        $this->redirect(array('action'=>'index'));
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Delete Failed !');
                    } 
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete Failed !');
            }
        }
    }
    public function indexAjax(){
      	$this->loadModel('Courtlevel'); 
        $this->layout = 'ajax';
        $court_level_name  = '';
        $condition = array('Courtlevel.is_trash' => 0);
        if(isset($this->params['named']['court_level_name']) && $this->params['named']['court_level_name'] != ''){
            $court_level_name = $this->params['named']['court_level_name'];
            $condition += array("Courtlevel.name LIKE '%$court_level_name%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Courtlevel.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Courtlevel');
        $this->set(array(
            'court_level_name'  => $court_level_name,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel('Courtlevel');
		
		 //debug($staffcategory_id);
		if (isset($this->data['Courtlevel']) && is_array($this->data['Courtlevel']) && count($this->data['Courtlevel'])>0){	
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->Courtlevel->save($this->data)) {
                if(isset($this->data['Courtlevel']['id']) && (int)$this->data['Courtlevel']['id'] != 0){
                    if($this->auditLog('Courtlevel', 'courtlevels', $this->data['Courtlevel']['id'], 'Update', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }else{
                    if($this->auditLog('Courtlevel', 'courtlevels', $this->Courtlevel->id, 'Add', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }
			}else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
			}
		}
        if(isset($this->data['CourtlevelEdit']['id']) && (int)$this->data['CourtlevelEdit']['id'] != 0){
            if($this->Courtlevel->exists($this->data['CourtlevelEdit']['id'])){
                $this->data = $this->Courtlevel->findById($this->data['CourtlevelEdit']['id']);
            }
        }
       
	}
}