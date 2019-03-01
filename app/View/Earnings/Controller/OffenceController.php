<?php
App::uses('AppController', 'Controller');
class OffenceController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Offence'); 
        if(isset($this->data['OffenceDelete']['id']) && (int)$this->data['OffenceDelete']['id'] != 0){
        	if($this->Offence->exists($this->data['OffenceDelete']['id'])){
        		if($this->Offence->updateAll(array('Offence.is_trash'	=> 1), array('Offence.id'	=> $this->data['OffenceDelete']['id']))){
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
      	$this->loadModel('Offence'); 
        $this->layout = 'ajax';
        $offencename  = '';
        $condition = array('Offence.is_trash'	=> 0);
        if(isset($this->params['named']['offencename']) && $this->params['named']['offencename'] != ''){
            $offencename = $this->params['named']['offencename'];
            $condition += array("Offence.name LIKE '%$offencename%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Offence.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Offence');
        $this->set(array(
            'offencename'         => $offencename,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel('Offence');
		if (isset($this->data['Offence']) && is_array($this->data['Offence']) && count($this->data['Offence'])>0){			
			if ($this->Offence->save($this->data)) {
				$this->Flash->success(__('The Offence has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The Offence could not be saved. Please, try again.'));
			}
		}
        if(isset($this->data['OffenceEdit']['id']) && (int)$this->data['OffenceEdit']['id'] != 0){
            if($this->Offence->exists($this->data['OffenceEdit']['id'])){
                $this->data = $this->Offence->findById($this->data['OffenceEdit']['id']);
            }
        }
	}
}