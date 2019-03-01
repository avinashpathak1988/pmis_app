<?php
App::uses('AppController', 'Controller');
class DangerousController extends AppController {
	public $layout='table';
	public function add() {

        $db = ConnectionManager::getDataSource('default');
        $db->begin();  

        if (isset($this->data['Dangerous']) && is_array($this->data['Dangerous']) && count($this->data['Dangerous'])>0){
            unset($_POST['data'],$_POST['_method']);
            $this->request->data['Dangerous'] += $_POST;
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if ($this->Dangerous->save($this->request->data)) {
                if(isset($this->data['Dangerous']['id']) && (int)$this->data['Dangerous']['id'] != 0){
                    if($this->auditLog('Dangerous', 'dangerous_prisoner_review_forms', $this->data['Dangerous']['id'], 'Update', json_encode($this->data))){
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
            }
        }
    }

    public function add1() {
        $this->layout = "star";
        $options = array('Good'=>'Good','Bad'=>'Bad','High Risk'=>'High Risk','Low Risk'=>'Low Risk','Medium Risk'=>'Medium Risk');
        $db = ConnectionManager::getDataSource('default');
        $db->begin();  

        if (isset($this->data['Dangerous']) && is_array($this->data['Dangerous']) && count($this->data['Dangerous'])>0){
            unset($_POST['data'],$_POST['_method']);
            $this->request->data['Dangerous'] += $_POST;
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if ($this->Dangerous->save($this->request->data)) {
                if(isset($this->data['Dangerous']['id']) && (int)$this->data['Dangerous']['id'] != 0){
                    if($this->auditLog('Dangerous', 'dangerous_prisoner_review_forms', $this->data['Dangerous']['id'], 'Update', json_encode($this->data))){
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
            }
        }

        $prisonList   = $this->Prison->find('list',array(
            'conditions'=>array(
                'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
            ),
            'order'=>array(
                'Prison.name'
            )
        ));

        $this->set(array(
            'options'=>$options,
            'prisonList'=>$prisonList,
        ));
    }

    public function index() {

        
    }
    public function newIndex() {
        $this->loadModel('Prison'); 
        $this->loadModel('Prisoner');
        //$this->loadModel('PrisonerTransfer');
        $this->loadModel('Dangerous');
        $this->layout='ajax';
        
        $prisonList   = $this->Prison->find('list',array(
            'conditions'=>array(
                'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
            ),
            'order'=>array(
                'Prison.name'
            )
        ));
        $this->set(compact('prison_id'));
        $this->set(array(
            'prisonList'         => $prisonList,
        ));
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                //'Prisoner.id'=>$this->Session->read('Auth.User.prison_id'),
                'Prisoner.is_trash'       => 0
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        $this->set(array(
            'prisonerList'       => $prisonerList,
        ));
    }
   
     public function getPrisoner(){
        $this->autoRender = false;
        if(isset($this->data['prison_id']) && (int)$this->data['prison_id'] != 0){
            //$transferStatus = Configure::read('STATUS');
            $prisonernameList = $this->Prisoner->find("list", array(
                "conditions"    => array(
                    "Prisoner.prison_id"    => $this->data['prison_id'],
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    'Prisoner.transfer_status !='   => 'Approved'
                ),
                "fields"        => array(
                    "Prisoner.id",
                    "Prisoner.prisoner_no",
                ),
                "order"         => array(
                    "Prisoner.prisoner_no"  => "asc",
                ),
            ));
            if(is_array($prisonernameList) && count($prisonernameList)>0){
                echo '<option value="">--Select Prisoner--</option>';
                foreach($prisonernameList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Prisoner--</option>';
            }
        }else{
            echo '<option value="">--Select Prisoner--</option>';
        }
        
    }
    public function indexAjax(){
      	$this->loadModel('Prison'); 
        $this->loadModel('Prisoner');
        $this->loadModel('Dangerous');
        $this->layout = 'ajax';
        $prisoner_station  = '';
        $prisoner_no  = '';
        $condition ='';
        //$condition = array('Dangerous.prisoner_no'	=> 0);
        if(isset($this->params['named']['prisoner_station']) && (int)$this->params['named']['prisoner_station'] != 0){
            $prisoner_station = $this->params['named']['prisoner_station'];
            $condition = array('Dangerous.prisoner_station' => $prisoner_station );
        } 
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('Dangerous.prisoner_id'  => $prisoner_id );
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Dangerous.prisoner_station'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Dangerous');
        $this->set(array(
            'prisoner_station'        => $prisoner_station,
            'datas'                   => $datas,
        )); 
    }
}
