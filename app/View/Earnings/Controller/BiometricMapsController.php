<?php
App::uses('AppController','Controller');
class BiometricMapsController extends AppController{
    public $layout='table';
    public function index() {

    	$this->loadModel('BiometricMap'); 
       // $this->loadModel('District');
        if(isset($this->data['BiometricMapDelete']['id']) && (int)$this->data['BiometricMapDelete']['id'] != 0){
        	if($this->BiometricMap->exists($this->data['BiometricMapDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->BiometricMap->updateAll(array('BiometricMap.is_trash'	=> 1), array('BiometricMap.id'	=> $this->data['BiometricMapDelete']['id']))){
                    if($this->auditLog('BiometricMap', 'biometric_maps', $this->data['BiometricMapDelete']['id'], 'Trash', json_encode(array('BiometricMap.is_trash' => 1)))){
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
         $condition = array();
		 if ($this->Session->read('Auth.User.prison_id')!='') {
		 	$condition += array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
		 }
		 $prisonsList = $this->Prison->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Prison.id',
				'Prison.name',
			),
			'conditions'	=> array(
				'Prison.is_trash'	=> 0,
				'Prison.is_enable'	=> 1,
			)+$condition,			
			'order'			=> array(
				'Prison.name'
			),
		));
        $biometric   = $this->BiometricMap->find('list');
        $userList    = $this->User->find('list');

       // $prisonsList    = $this->Prison->find('list');
        $this->set(array(
            'biometric'         => $biometric,
            'userList'          => $userList,
            'prisonsList'       => $prisonsList,
        )); 

    }

     public function indexAjax(){
      	//$this->loadModel('User'); 
        $this->loadModel('BiometricMap');
        $this->layout = 'ajax';
        $biometric_id  = '';
        $usertype_id  = '';
        $prison_id  = '';
        $condition = array('BiometricMap.is_trash'	=> 0);

        if(isset($this->params['named']['biometric_id']) && (int)$this->params['named']['biometric_id'] != 0){
            $biometric_id = $this->params['named']['biometric_id'];
            $condition += array('BiometricMap.biometric_id' => $biometric_id );

        } 
        if(isset($this->params['named']['usertype_id']) && (int)$this->params['named']['usertype_id'] != 0){
            $usertype_id = $this->params['named']['usertype_id'];
            $condition += array('BiometricMap.usertype_id' => $usertype_id );
        } 
        if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('BiometricMap.prison_id' => $prison_id );
        } 
        $this->paginate = array(
             'conditions'    => $condition,
            'order'         =>array(
                'BiometricMap.biometric_id'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('BiometricMap');
        $this->set(array(
            'biometric_id'          => $biometric_id,
            'usertype_id'          => $usertype_id,
            'prison_id'          => $prison_id,
            'datas'             => $datas,
        )); 
    }

    public function add() {
    	//debug($this->request->data); exit;

    	 if($this->request->is(array('post','put')) && isset($this->data['BiometricMap']) && is_array($this->data['BiometricMap']) && count($this->data['BiometricMap']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->BiometricMap->save($this->request->data)){
                if(isset($this->data['BiometricMap']['id']) && (int)$this->data['BiometricMap']['id'] != 0){
                    if($this->auditLog('BiometricMap', 'BiometricMap', $this->data['BiometricMap']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('BiometricMap', 'BiometricMap', $this->BiometricMap->id, 'Add', json_encode($this->data))){
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
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        if(isset($this->data['BiometricMapEdit']['id']) && (int)$this->data['BiometricMapEdit']['id'] != 0){
            if($this->BiometricMap->exists($this->data['BiometricMapEdit']['id'])){
                $this->data = $this->BiometricMap->findById($this->data['BiometricMapEdit']['id']);
            }
        }
        $rparents=$this->BiometricMap->find('list',array(
        	 'conditions'=>array(
                'BiometricMap.is_enable'=>1,
            ),
            
        ));
        $userList = $this->User->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'User.id',
				'User.name',
			),
			'conditions'	=> array(
				'User.is_trash'	=> 0,
				'User.is_enable'	=> 1,
			),			
			'order'			=> array(
				'User.name'
			),
		));
		 $condition = array();
		 if ($this->Session->read('Auth.User.prison_id')!='') {
		 	$condition += array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
		 }
		 $prisonsList = $this->Prison->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Prison.id',
				'Prison.name',
			),
			'conditions'	=> array(
				'Prison.is_trash'	=> 0,
				'Prison.is_enable'	=> 1,
			)+$condition,			
			'order'			=> array(
				'Prison.name'
			),
		));
		

		$this->set(array(
			'userList'		=> $userList,
			'prisonsList'   => $prisonsList,
		));


        //$this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
    
		
	
}
