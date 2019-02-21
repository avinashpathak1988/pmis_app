<?php
App::uses('AppController', 'Controller');
class SectionOfLawController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Offence'); 
        $this->loadModel('SectionOfLaw');
        if(isset($this->data['SOLDelete']['id']) && (int)$this->data['SOLDelete']['id'] != 0){
        	if($this->SectionOfLaw->exists($this->data['SOLDelete']['id'])){
        		if($this->SectionOfLaw->updateAll(array('SectionOfLaw.is_trash'	=> 1), array('SectionOfLaw.id'	=> $this->data['SOLDelete']['id']))){
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
        $offenceList   = $this->Offence->find('list');
        $this->set(array(
            'offenceList'         => $offenceList,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('Offence'); 
        $this->loadModel('SectionOfLaw');
        $this->layout = 'ajax';
        $offence_id  = '';
        $solname  = '';
        $condition = array('SectionOfLaw.is_trash'	=> 0);
        if(isset($this->params['named']['offence_id']) && (int)$this->params['named']['offence_id'] != 0){
            $offence_id = $this->params['named']['offence_id'];
            $condition += array('SectionOfLaw.offence_id' => $offence_id );
        } 
        if(isset($this->params['named']['solname']) && $this->params['named']['solname'] != ''){
            $solname = $this->params['named']['solname'];
            $condition += array("SectionOfLaw.name LIKE '%$solname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'SectionOfLaw.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('SectionOfLaw');
        $this->set(array(
            'offence_id'          => $offence_id,
            'solname'          => $solname,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("SectionOfLaw"); 
		$this->loadModel('Offence');
		if (isset($this->data['SectionOfLaw']) && is_array($this->data['SectionOfLaw']) && count($this->data['SectionOfLaw'])>0){			
			if ($this->SectionOfLaw->save($this->request->data)) {
				$this->Flash->success(__('The SectionOfLaw has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The SectionOfLaw could not be saved. Please, try again.'));
			}
		}
        if(isset($this->data['SOLEdit']['id']) && (int)$this->data['SOLEdit']['id'] != 0){
            if($this->SectionOfLaw->exists($this->data['SOLEdit']['id'])){
                $this->data = $this->SectionOfLaw->findById($this->data['SOLEdit']['id']);
            }
        }		
		$offenceList = $this->Offence->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Offence.id',
				'Offence.name',
			),
			'conditions'	=> array(
				'Offence.is_trash'	=> 0,
				'Offence.is_enable'	=> 1,
			),			
			'order'			=> array(
				'Offence.name'
			),
		));
		$this->set(array(
			'offenceList'		=> $offenceList,
		));
	}
}
