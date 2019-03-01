<?php
App::uses('AppController', 'Controller');
class InternalOffenceController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('InternalOffence'); 
        if(isset($this->data['InternalOffenceDelete']['id']) && (int)$this->data['InternalOffenceDelete']['id'] != 0){
        	if($this->InternalOffence->exists($this->data['InternalOffenceDelete']['id'])){
        		if($this->InternalOffence->updateAll(array('InternalOffence.is_trash'	=> 1), array('InternalOffence.id'	=> $this->data['InternalOffenceDelete']['id']))){
					$this->Session->write('message_type','success');
                    $this->Session->write('message','Delete Successfully !');
        		}else{
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
      	$this->loadModel('InternalOffence'); 
        $this->layout = 'ajax';
        $offencename  = '';
        $offence_type  = '';
        $condition = array('InternalOffence.is_trash'	=> 0);
        if(isset($this->params['named']['offencename']) && $this->params['named']['offencename'] != ''){
            $offencename = $this->params['named']['offencename'];
            $condition += array("InternalOffence.name LIKE '%$offencename%'");
        } 
        if(isset($this->params['named']['offence_type']) && $this->params['named']['offence_type'] != ''){
            $offence_type = $this->params['named']['offence_type'];
            $condition += array("InternalOffence.offence_type LIKE '%$offence_type%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'InternalOffence.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('InternalOffence');
        $this->set(array(
            'offencename'         => $offencename,
            'datas'             => $datas,
            'offence_type'             => $offence_type,
        )); 
    }
	public function add() { 
		$this->loadModel('InternalOffence');
		if (isset($this->data['InternalOffence']) && is_array($this->data['InternalOffence']) && count($this->data['InternalOffence'])>0){			
			if ($this->InternalOffence->save($this->data)) {
				$this->Flash->success(__('The Internal Offence has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The InternalOffence could not be saved. Please, try again.'));
			}
		}
        if(isset($this->data['InternalOffenceEdit']['id']) && (int)$this->data['InternalOffenceEdit']['id'] != 0){
            if($this->InternalOffence->exists($this->data['InternalOffenceEdit']['id'])){
                $this->data = $this->InternalOffence->findById($this->data['InternalOffenceEdit']['id']);
            }
        }
	}
}