<?php
App::uses('AppController','Controller');
class AftercareController extends AppController{

    public $layout='table';
    public $uses=array('User','Prisoner','Aftercare','AfterCareActivity');

	public function index(){
        $menuId = $this->getMenuId("/aftercare ");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
		$prison_id = $this->Session->read('Auth.User.prison_id');
		$today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 0,
                        'Prisoner.is_death'   => 0,

                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id
                        
                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));

        $activityList = $this->AfterCareActivity->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'AfterCareActivity.is_trash'   => 0,
                        'AfterCareActivity.is_enable'   => 1,

                    ),
                    'order'=>array(
                        'AfterCareActivity.name'=>'ASC'
                    )
                ));
        if(isset($this->data['AfterCareDelete']['id']) && (int)$this->data['AfterCareDelete']['id'] != 0){
            if($this->Aftercare->exists($this->data['AfterCareDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Aftercare->updateAll(array('Aftercare.is_trash' => 1), array('Aftercare.id'  => $this->data['AfterCareDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }
		$this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'activityList' => $activityList
            
        ));

    }
        public function addAfterCare(){
            $menuId = $this->getMenuId("/aftercare ");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $afterCaresList = $this->Aftercare->find('list',array(
                    "fields"    => array(
                        "Aftercare.prisoner_id"
                    ),
                    'conditions'    => array(
                        'Aftercare.is_trash' => 0,
                    ),
                    
                ));
        
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields' => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 0,
                        'Prisoner.is_death'   => 0,
                        '0'=>"Prisoner.id NOT IN (".implode(",", $afterCaresList).")"
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));

        if(isset($this->request->data['AfterCareEdit']['id'])){
                $menuId = $this->getMenuId("/aftercare ");
                $moduleId = $this->getModuleId("social_rehabilitation");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

                //echo $moduleId;exit;
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
            $this->request->data=$this->Aftercare->findById($this->data["AfterCareEdit"]["id"]);
            
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList
            
        ));

    }
    public function submitAfterCare(){
        

        if ($this->Aftercare->saveAll($this->request->data['Aftercare'])) {
             $this->Session->write('message_type','success');
            $this->Session->write('message','Saved Successfully !');
            $result = 'Success' ;
        } else {
            //debug($this->InformalCouncelling->validationErrors);
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving Failed !');
            $result = 'Failed';
        }


        if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
        {
            $notification_msg = "After Care created.";
            $notifyUser = $this->User->find('list',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                    'User.is_trash'     => 0,
                    'User.is_enable'     => 1,
                    'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                )
            ));
            // debug($notifyUser);
            $this->addManyNotification($notifyUser,$notification_msg,"Aftercare");
            
            
        }
        echo $result ; exit;
       
    }

    public function aftercareAjax(){
        //$this->Paginator->settings = $this->paginate;
        $this->layout   = 'ajax';
        $condition      = array("Aftercare.is_trash"=>0);
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("Aftercare.prisoner_id"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("Aftercare.prisoner_name   like '%$prisonerName%'");
        }
        $modelName = 'Aftercare';


        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => -1, 
            'conditions'    => $condition,
            'order'=>array('Aftercare.id'=>'desc'),
        )+$limit;
      $aftercareDetail = $this->paginate('Aftercare');
        //debug($aftercareDetail);exit;

        $this->set(array(
            'aftercareDetails' => $aftercareDetail,
           
            
        ));
       
    }

   

}