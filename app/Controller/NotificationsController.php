<?php
App::uses('AppController', 'Controller');
class NotificationsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Prison'); 
        $this->loadModel('Notification');
        if(isset($this->data['NotificationDelete']['id']) && (int)$this->data['NotificationDelete']['id'] != 0){
        	if($this->Notification->exists($this->data['NotificationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->Notification->updateAll(array('Notification.is_trash'	=> 1), array('Notification.id'	=> $this->data['NotificationDelete']['id']))){
                    if($this->auditLog('Notification', 'notifications', $this->data['NotificationDelete']['id'], 'Trash', json_encode(array('Notification.is_trash' => 1)))){
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
        $prisonList   = $this->Prison->find('list');
        $this->set(array(
            'prisonList'         => $prisonList,
        ));
    }
    public function indexAjax(){
        $this->loadModel('Prison');
        //debug($this->loadModel('Notification'));exit; 
        $this->loadModel('Notification');
        $this->layout = 'ajax';
        $user_id  = '';
        $content  = '';
        $condition  = '';
        $date_created="";
        $date_modified="";
        $condition = array('Notification.user_id' => $this->Session->read('Auth.User.id'));
          if(isset($this->params['named']['created']) && $this->params['named']['created'] != ''){
            //echo "hhhh";
             $from_date = $this->params['named']['created'];
             $date_created = date('Y-m-d',strtotime($this->params['named']['created']));
             $condition += array("Date(Notification.created)" =>$date_created);
         }
          if(isset($this->params['named']['modified']) && $this->params['named']['modified'] != ''){
             $from_date = $this->params['named']['modified'];
             $date_modified = date('Y-m-d',strtotime($this->params['named']['modified']));
             $condition += array('Notification.created BETWEEN ? and ?' => array($date_created, $date_modified));
         }
          if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');
        }
       // debug($this->params['named']);
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Notification.created'  => 'desc',
                
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Notification');
        //debug($condition);
        //debug($datas);
        $this->set(array(
            'user_id'          => $user_id,
            'content'        => $content,
            'datas'             => $datas,
            'date_created'        => $date_created,
            'date_modified'         => $date_modified,
        )); 
    
}
	public function add() { 
		$this->loadModel("Notification"); 
		$this->loadModel('Prison');
		if (isset($this->data['Notification']) && is_array($this->data['Notification']) && count($this->data['Notification'])>0){
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->Notification->save($this->request->data)) {
                if(isset($this->data['Notification']['id']) && (int)$this->data['Notification']['id'] != 0){
                    if($this->auditLog('Notification', 'notifications', $this->data['Notification']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Notification', 'notifications', $this->Notification->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['NotificationEdit']['id']) && (int)$this->data['NotificationEdit']['id'] != 0){
            if($this->Notification->exists($this->data['NotificationEdit']['id'])){
                $this->data = $this->Notification->findById($this->data['NotificationEdit']['id']);
            }
        }		
		$prisonList = $this->Prison->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Prison.id',
				'Prison.name',
			),
			'conditions'	=> array(
				'Prison.is_trash'	=> 0,
				'Prison.is_enable'	=> 1,
			),			
			'order'			=> array(
				'Prison.name'=>'DESC'
               
			),
		));
		$this->set(array(
			'prisonList'		=> $prisonList,
		));
	}
}
