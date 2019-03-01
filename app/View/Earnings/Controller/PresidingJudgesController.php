<?php
App::uses('AppController', 'Controller');
class PresidingJudgesController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
        $this->loadModel('PresidingJudge');
        $this->loadModel('Magisterial');
        $magisterialList=$this->Magisterial->find('list',array(
              'conditions'=>array(
                'Magisterial.is_enable'=>1,
                'Magisterial.is_trash'=>0,
              ),
              'order'=>array(
                'Magisterial.name'
              )
        ));
        if(isset($this->data['PresidingJudgeDelete']['id']) && (int)$this->data['PresidingJudgeDelete']['id'] != 0){
        	if($this->PresidingJudge->exists($this->data['PresidingJudgeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->PresidingJudge->updateAll(array('PresidingJudge.is_trash'	=> 1), array('PresidingJudge.id'	=> $this->data['PresidingJudgeDelete']['id']))){
                    if($this->auditLog('PresidingJudge', 'districts', $this->data['PresidingJudgeDelete']['id'], 'Trash', json_encode(array('PresidingJudge.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
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
        $stateList   = $this->State->find('list');
        $this->set(array(
            'magisterialList'   => $magisterialList,
            'stateList'         => $stateList,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('State'); 
        $this->loadModel('PresidingJudge');
        $this->layout = 'ajax';
        $state_id  = '';
        $distname  = '';
        $condition = array('PresidingJudge.is_trash'	=> 0);
        if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $state_id = $this->params['named']['state_id'];
            $condition += array('PresidingJudge.state_id' => $state_id );
        } 
        if(isset($this->params['named']['distname']) && $this->params['named']['distname'] != ''){
            $distname = $this->params['named']['distname'];
            $condition += array("PresidingJudge.name LIKE '%$distname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'PresidingJudge.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('PresidingJudge');
        $this->set(array(
            'state_id'          => $state_id,
            'distname'          => $distname,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("PresidingJudge"); 
		$this->loadModel('State');
        $this->loadModel('Magisterial');
        $magisterialList=$this->Magisterial->find('list',array(
              'conditions'=>array(
                'Magisterial.is_enable'=>1,
                'Magisterial.is_trash'=>0,
              ),
              'order'=>array(
                'Magisterial.name'
              )
        ));

		if (isset($this->data['PresidingJudge']) && is_array($this->data['PresidingJudge']) && count($this->data['PresidingJudge'])>0){
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            // debug($this->request->data);exit;
			if ($this->PresidingJudge->saveAll($this->request->data)) {
                if(isset($this->data['PresidingJudge']['id']) && (int)$this->data['PresidingJudge']['id'] != 0){
                    if($this->auditLog('PresidingJudge', 'presiding_judges', $this->data['PresidingJudge']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('PresidingJudge', 'presiding_judges', $this->PresidingJudge->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['PresidingJudgeEdit']['id']) && (int)$this->data['PresidingJudgeEdit']['id'] != 0){
            if($this->PresidingJudge->exists($this->data['PresidingJudgeEdit']['id'])){
                $this->data = $this->PresidingJudge->findById($this->data['PresidingJudgeEdit']['id']);
            }
        }		
		$stateList = $this->State->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'State.id',
				'State.name',
			),
			'conditions'	=> array(
				'State.is_trash'	=> 0,
				'State.is_enable'	=> 1,
			),			
			'order'			=> array(
				'State.name'
			),
		));
		$this->set(array(
            'stateList'         => $stateList,
			'magisterialList'   => $magisterialList,
		));
	}
}
