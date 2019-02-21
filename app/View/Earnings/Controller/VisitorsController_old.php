<?php
App::uses('AppController', 'Controller');
class VisitorsController   extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Visitor'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        if(isset($this->data['VisitorDelete']['id']) && (int)$this->data['VisitorDelete']['id'] != 0){
        	
            $this->Visitor->id=$this->data['VisitorDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Visitor->saveField('is_trash',1))
            {
                if($this->auditLog('Visitor', 'visitors', $this->data['VisitorDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$this->Session->read('Auth.User.prison_id').")"
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        $allowUpdate = true;
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $allowUpdate = true;
        }elseif ($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')) {
            $gatekeeperData = $this->User->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "(".$this->Session->read('Auth.User.prison_id').")"." IN (User.prison_id)",
                ),
            ));
            if($gatekeeperData == 0){
                $allowUpdate = false;
            }
        }else{
            $allowUpdate = false;
        }
        
         $this->set(array(
            'prisonList'         => $prisonList,
            'allowUpdate'        => $allowUpdate,
            //'gatekeeperData'     => $gatekeeperData
        )); 
    }
    public function indexAjax(){
      	$this->loadModel('Visitor'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $condition = array('Visitor.is_trash'   => 0);
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
        $condition += array("Visitor.prison_id IN (".$this->Session->read('Auth.User.prison_id').")");
      }else{
        $condition += array("Visitor.prison_id" => $this->Session->read('Auth.User.prison_id'));
      }
        //debug($this->params);exit;
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

         $condition += array('Visitor.date >= ' => date('Y-m-d', strtotime($from)),
                              'Visitor.date <= ' => date('Y-m-d', strtotime($to))
                             );        
        }
       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Visitor.created' => 'DESC'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Visitor');
        //debug($datas);

        $this->set(array(
            'from'         => $from,
            'to'           => $to,
            'datas'        => $datas,
        )); 

    }

      public function view($prison_id = ''){
            $visitorList = $this->Visitor->find('all', array(
            //'recursive'     => -1,
            'conditions'    => array(
                //'Visitor.is_enable'      => 1,
                'Visitor.is_trash'       => 0,
                'Visitor.id'      => $prison_id
            ),
            'order'         => array(
                'Visitor.prisoner_no'
            ),
        ));
        $this->set(array(
            'visitorList'         => $visitorList,
        )); 

  }
	public function add() { 
		$this->loadModel('Visitor');
		$this->loadModel('PPCash');
        $this->loadModel('Article');
        $this->loadModel('Relationship');
        //debug($this->request->data);exit;
		 //debug($staffcategory_id);
		if (isset($this->data['Visitor']) && is_array($this->data['Visitor']) && count($this->data['Visitor'])>0){			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if(isset($this->data['Visitor']['date']) && $this->data['Visitor']['date'] !=''){
                $this->request->data['Visitor']['date'] = date('Y-m-d',strtotime($this->data['Visitor']['date']));
            }
            if(isset($this->data['Visitor']['time_in']) && $this->data['Visitor']['time_in'] !=''){
                $this->request->data['Visitor']['time_in'] = $this->data['Visitor']['time_in'];
            }else{
                $this->request->data['Visitor']['time_in'] = date("h:i A");
            }
            if(isset($this->data['Visitor']['prison_id']) && $this->data['Visitor']['prison_id'] !=''){
                $this->request->data['Visitor']['prison_id'] = $this->data['Visitor']['prison_id'];
            }else{
                $this->request->data['Visitor']['prison_id']=$this->Session->read('Auth.User.prison_id');
            }
            //debug($this->request->data);exit;
            if ($this->Visitor->saveAll($this->data)) {
                if($this->request->data['Visitor']['id'] == ''){
                     if($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){
                        $refId = 0;
                        $action = 'Add';
                        //$user1 = Configure::read('GATEKEEPER_USERTYPE');
                        $user2 = Configure::read('RECEPTIONIST_USERTYPE');
                        $userData = $this->User->find("list", array(
                            "conditions" => array(
                                "User.usertype_id IN (".$user2.")",
                                "User.id NOT IN('".$this->Session->read('Auth.User.id')."')",
                                "User.prison_id" => $this->Session->read('Auth.User.prison_id'),
                                ),
                            ));
                        //debug($userData);exit;
                        if(isset($userData) && $userData!=''){
                            foreach ($userData as $key => $value) {
                                $this->addNotification(array(
                                    "user_id"=>$key,
                                    "content"=>"Visitor Added Successfully",
                                    "url_link"=>"visitors"));
                                }
                            }
                        }
                     if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                         $refId = 0;
                        $action = 'Add';
                        $user1 = Configure::read('GATEKEEPER_USERTYPE');
                        $user2 = Configure::read('RECEPTIONIST_USERTYPE');
                        $userData = $this->User->find("all", array(
                            "conditions" => array(
                                "User.prison_id" => $this->request->data['Visitor']['prison_id'],
                                "User.usertype_id IN (".$user1.",".$user2.")",
                                ),
                            ));
                        //debug($userData);exit;
                        if(isset($userData) && $userData!=''){
                            foreach ($userData as $key => $value) {
                                //debug($value);exit;
                                $this->addNotification(array(
                                    "user_id"=>$value['User']['id'],
                                    "content"=>"Visitor Added Successfully",
                                    "url_link"=>"visitors"));
                                }
                            }

                         }
                }
                if(isset($this->data['Visitor']['id']) && (int)$this->data['Visitor']['id'] != 0)
                {
                    $refId  = $this->data['Visitor']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('Visitor', 'visitors', $refId, $action, json_encode($this->data)))
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
        if(isset($this->data['VisitorEdit']['id']) && (int)$this->data['VisitorEdit']['id'] != 0){
            if($this->Visitor->exists($this->data['VisitorEdit']['id'])){
                $this->data = $this->Visitor->findById($this->data['VisitorEdit']['id']);
            }
        }
       //get prisoner list
          $prison_id = $_SESSION['Auth']['User']['prison_id'];
          $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id'      => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
          $prisonernameList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.fullname',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id'      => $prison_id
            ),
            'order'         => array(
                'Prisoner.fullname'
            ),
        ));
          $gateKeepers = $this->User->find('list',array(
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
          $ppcash = $this->PPCash->find('list',array(
                'fields'        => array(
                    'PPCash.id',
                    'PPCash.name',
                ),
                'conditions'=>array(
                  'PPCash.is_enable'=>1,
                  'PPCash.is_trash'=>0,
                ),
                'order'=>array(
                  'PPCash.name'
                )
          ));
          $relation = $this->Relationship->find('list',array(
                'fields'        => array(
                    'Relationship.id',
                    'Relationship.name',
                ),
                'conditions'=>array(
                  'Relationship.is_enable'=>1,
                  'Relationship.is_trash'=>0,
                ),
                'order'=>array(
                  'Relationship.name'
                )
          ));
          //debug($_SESSION);
          $prison_id = $_SESSION['Auth']['User']['prison_id'];
          $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$prison_id.")" 
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
          $article = $this->Article->find('first');
        $this->set(array(
            'prisonerList'    => $prisonerList,
            'gateKeepers'     => $gateKeepers,
            'ppcash'          => $ppcash,
            'article'         => $article,
            'relation'        => $relation,
            'prisonernameList'=> $prisonernameList,
            'prisonList'      => $prisonList
        ));
	}
    public function timeout()
     {
       $this->autoRender = false;
        if(isset($this->params['named']) && $this->params['named'] !=''){
            $visitor_id = $this->params['named']['visitor_id'];
            $visitorTimeIn = $this->Visitor->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Visitor.id'     => $visitor_id,
                ),
            ));
            $time_in = $visitorTimeIn['Visitor']['time_in'];
            $duration = date("h:i") - $time_in;
            
            // $checkTime = strtotime('03:06');
            // $loginTime = strtotime('05:07');
            // $diff = $checkTime - $loginTime;

            //Calculate Time Duration
            $in = strtotime($time_in);
            $out = strtotime(date("h:i A"));
            $duration = $in - $out;
            $timeDuration = gmdate("H:i", abs($duration));

            $fields = array(
                'Visitor.time_out'    => "'".date("h:i A")."'",
                'Visitor.duration'    => "'".$timeDuration."'",
            );
            $conds = array(
                'Visitor.id'    => $visitor_id,
            );

            if($this->Visitor->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
     } 
     public function alert()
     {
       $this->autoRender = false;
        if(isset($this->params['named']) && $this->params['named'] !=''){
             $gatekeeperData = $this->User->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "(".$this->Session->read('Auth.User.prison_id').")"." IN (User.prison_id)",
                ),
            ));
            $userId = $gatekeeperData['User']['id'];
            $this->addNotification(array(
                "user_id"=>$userId,
                "content"=>"Visitor Not Reached Yet",
                "url_link"=>"visitors"));
            echo 'SUCC';
      
        }else{
            echo 'FAIL';
        }
     }
//code by smita for gate book//
public function gateBookReport(){
    $this->loadModel('Visitor'); 
        //debug($this->data['RecordStaffDelete']['id']);
        //return false;
        if(isset($this->data['VisitorDelete']['id']) && (int)$this->data['VisitorDelete']['id'] != 0){
            
            $this->Visitor->id=$this->data['VisitorDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Visitor->saveField('is_trash',1))
            {
                if($this->auditLog('Visitor', 'visitors', $this->data['VisitorDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$this->Session->read('Auth.User.prison_id').")"
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        $allowUpdate = true;
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $allowUpdate = true;
        }elseif ($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')) {
            $gatekeeperData = $this->User->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "(".$this->Session->read('Auth.User.prison_id').")"." IN (User.prison_id)",
                ),
            ));
            if($gatekeeperData == 0){
                $allowUpdate = false;
            }
        }else{
            $allowUpdate = false;
        }
        
         $this->set(array(
            'prisonList'         => $prisonList,
            'allowUpdate'        => $allowUpdate,
            //'gatekeeperData'     => $gatekeeperData
        )); 
}

public function gateBookReportAjax(){
        $this->loadModel('Visitor'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $category='';
        $name='';
        //$category = $this->params['named']['category'];
        $condition = array('Visitor.is_trash'   => 0);
        
        //debug($this->params);exit;
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

         $condition += array('Visitor.date >= ' => date('Y-m-d', strtotime($from)),
                              'Visitor.date <= ' => date('Y-m-d', strtotime($to))
                             );        
        }

        if(isset($this->params['named']['category']) && $this->params['named']['category'] != ''){
            //$prisoner_id = str_replace('/', ' ', $this->params['named']['category']);
            $category = str_replace('/', '', $this->params['named']['category']);
             
            // $condition += array(
            //     "Visitor.category"=>$category
                
            // );
            $condition += array(
                'Visitor.category'   => $category,
            );
        }

        if(isset($this->params['named']['gate_keeper_name']) && $this->params['named']['gate_keeper_name'] != ''){
            $name = str_replace('%20', '', $this->params['named']['gate_keeper_name']);
            $condition += array("Visitor.gate_keeper LIKE '%$name%'");            
        }

        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $condition += array("Visitor.prison_id IN (".$this->Session->read('Auth.User.prison_id').")");
        }else{
            $condition += array("Visitor.prison_id" => $this->Session->read('Auth.User.prison_id'));
        }
             
        
        
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Visitor.created' => 'DESC'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Visitor');
        //debug($datas);

        $this->set(array(
            'from'         => $from,
            'to'           => $to,
            'datas'        => $datas,
        )); 
    }

}