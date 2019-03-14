<?php
App::uses('Controller', 'Controller');

class StationjournalsController extends AppController{
		public $layout='table';
    	public $uses=array('Stationjournal','Prison');
     /**
     * Index Function
     */
    public function index() {
        $menuId = $this->getMenuId("/Stationjournals ");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
    if($this->request->is(array('post','put'))){//debug($this->data);exit;
        if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
        {
            $status = 'Saved'; 
            $remark = '';
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
            {
                if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                {
                    $status = $this->request->data['ApprovalProcessForm']['type']; 
                    $remark = $this->request->data['ApprovalProcessForm']['remark'];
                }
            }
            $items = $this->request->data['ApprovalProcess'];
            //debug($status);exit;
            $status = $this->setApprovalProcess($items, 'Stationjournal', $status, $remark);
            if($status == 1)
            {
                //notification on approval of payment list --START--
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                {
                    $notification_msg = "Station journal list of prisoners are pending for approval.";
                    $notifyUser = $this->User->find('first',array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                            'User.is_trash'     => 0,
                            'User.is_enable'     => 1,
                            'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                        )
                    ));
                    if(isset($notifyUser['User']['id']))
                    {
                        $this->addNotification(array(                        
                            "user_id"   => $notifyUser['User']['id'],                        
                            "content"   => $notification_msg,                        
                            "url_link"   => "stationjournals",                    
                        )); 
                    }
                }
                
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
            }
            else 
            {
                $this->Session->write('message_type','error');
                $this->Session->write('message','saving failed');
            }
        }
    }
       if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }else{
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }
       $this->set(compact('prisonList'));
    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $journal_date      = '';
        $condition      = array(
            'Stationjournal.is_trash'   => 0
        );
         $condition      += array(
            'Stationjournal.prison_id'   => $this->Session->read('Auth.User.prison_id')
        );
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Stationjournal.prison_id' => $prison_id );
        }
        // if(isset($this->params['named']['journal_date']) && $this->params['named']['journal_date'] != ''){
        //     $journal_date = $this->params['named']['journal_date'];
        //     $journal_date=date('Y-m-d',  strtotime($journal_date));
        //     $condition += array('Stationjournal.journal_date' => $journal_date );
        // }

         if(isset($this->params['named']['journal_date']) && $this->params['named']['journal_date'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['journal_date'];
            $to = $this->params['named']['to'];

            $condition += array(
                'Stationjournal.journal_date >= ' => date('Y-m-d', strtotime($from)),
                'Stationjournal.journal_date <= ' => date('Y-m-d', strtotime($to))
            );        
        } 
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','station_journals_mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','station_journals_mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','station_journals_mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Stationjournal.modified',
            ),
        )+$limit;
        $datas = $this->paginate('Stationjournal');
        $this->set(array(
            'datas'         => $datas,
            'journal_date'     => $journal_date,   
            'prison_id'     => $prison_id,      
        ));
    }
    /**
     * Add Function
     */
    function getStationsName($id=''){
         $this->autoRender = false;
        $id = $this->data['id'];
        $this->loadModel('Prison');
            $condition = array(
                'Prison.id'    => $id
            );
            $data = $this->Prison->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.name'
                ),
                'conditions'    => $condition
            ));
         return $data['Prison']['name'];
    }
    public function add(){
        $menuId = $this->getMenuId("/Stationjournals ");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        
        $this->layout='table';
        if($this->request->is(array('post','put'))){//debug($this->data);exit;
            $this->request->data['Stationjournal']['journal_date']=date('Y-m-d',strtotime($this->request->data['Stationjournal']['journal_date']));
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Stationjournal->save($this->request->data)){
                if($this->auditLog('Stationjournal', 'station_journals', 0, 'Add', json_encode($this->data)))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $prison_id=$this->Prison->find('list',array(
                'conditions'=>array(
                  'Prison.is_enable'=>1,
                  'Prison.is_trash'=>0,
                ),
                'order'=>array(
                  'Prison.name'
                )
          ));
        $psid=$this->Prison->find('list',array(
                'conditions'=>array(
                  'Prison.is_enable'=>1,
                  'Prison.is_trash'=>0,
                ),
                'fields'=>array(
                  'Prison.id',
                  'Prison.code'
                ),
                'order'=>array(
                  'Prison.name'
                )
          ));
        $duty_officer=$this->User->find('list',array(
                'conditions'=>array(
                  'User.usertype_id'=>Configure::read('OFFICERINCHARGE_USERTYPE')

                ),
                'order'=>array(
                  'User.name'
                )
          ));
        //debug($duty_officer);
          
        $this->set(compact('is_enable','prison_id','duty_officer','psid'));
    
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $this->request->data['Stationjournal']['journal_date']=date('Y-m-d',strtotime($this->request->data['Stationjournal']['journal_date']));
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Stationjournal->save($this->request->data)){
                if($this->auditLog('Stationjournal', 'station_journals', $this->request->data['Stationjournal']['id'], 'Edit', json_encode($this->data)))
                {
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Updated Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Updating Failed !');
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Updating Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $prison_id=$this->Prison->find('list',array(
                'conditions'=>array(
                  'Prison.is_enable'=>1,
                  'Prison.is_trash'=>0,
                ),
                'order'=>array(
                  'Prison.name'
                )
          ));
        $duty_officer=$this->User->find('list',array(
                'conditions'=>array(
                  'User.usertype_id'=>Configure::read('OFFICERINCHARGE_USERTYPE')

                ),
                'order'=>array(
                  'User.name'
                )
          ));
        $this->set(compact('is_enable','prison_id','duty_officer'));
        $this->request->data=$this->Stationjournal->findById($id);
    }
    /////////////////////
    public function disable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        $this->Stationjournal->id=$id;
        if($this->Stationjournal->saveField('is_enable',0))
        {
            if($this->auditLog('Stationjournal', 'station_journals', $id, 'Disable', json_encode(array('is_enable',0))))
            {
                $db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Disabled Successfully !');
                $this->redirect(array('action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
        else {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
        }
    }
    /////////////////////////
    public function enable($id){
        $this->Stationjournal->id=$id;
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        if($this->Stationjournal->saveField('is_enable',1))
        {
            if($this->auditLog('Stationjournal', 'station_journals', $id, 'Disable', json_encode(array('is_enable',1))))
            {
                $db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Enabled Successfully !');
                $this->redirect(array('action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
        else {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
        }
    }
    public function trash($id){
      $this->Stationjournal->id=$id;
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        if($this->Stationjournal->updateAll(
            array('Stationjournal.is_trash' => 1),
            array('Stationjournal.id' => $id)
        ))
        {
            if($this->auditLog('Stationjournal', 'station_journals', $id, 'Disable', json_encode(array('is_trash',1))))
            {
                $db->commit(); 
                $this->Session->write("message_type",'success');
                $this->Session->write('message','Deleted Successfully !');
                $this->redirect(array('controller'=>'stationjournals','action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
        }
    }
    public function getStationName($id){
        $prisonDetail=$this->Prison->find('first',array(
                'conditions'=>array(
                  'Prison.id'=>$id,
                ),
                
        ));
        return $prisonDetail["Prison"]["name"];
    }

    //approve attendance start
    // public function approveJournal()
    // {
    //     $prison_id = $this->Session->read('Auth.User.prison_id');
    //     $login_user_id = $this->Session->read('Auth.User.id');
    //     $default_status = ''; $approvalStatusList = '';
    //     $statusInfo = $this->getApprovalStatusInfo();
    //     if(is_array($statusInfo) && count($statusInfo) > 0)
    //     {
    //         $default_status = $statusInfo['default_status']; 
    //         $approvalStatusList = $statusInfo['statusList']; 
    //     }
    //     //save approval process 
    //     if($this->request->is(array('post','put')))
    //     {debug($this->data);exit;
    //         //save approval status 
    //         if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
    //         {
    //             $status = 'Saved'; 
    //             $remark = '';
    //             if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
    //             {
    //                 if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
    //                 {
    //                     $status = $this->request->data['ApprovalProcessForm']['type']; 
    //                     $remark = $this->request->data['ApprovalProcessForm']['remark'];
    //                 }
    //             }
    //             $items = $this->request->data['ApprovalProcess'];
    //             $status = $this->setApprovalProcess($items, 'Stationjournal', $status, $remark);
    //             if($status == 1)
    //             {
    //                 //notification on approval of payment list --START--
    //                 if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
    //                 {
    //                     $notification_msg = "Station journal list of prisoners are pending for approval.";
    //                     $notifyUser = $this->User->find('first',array(
    //                         'recursive'     => -1,
    //                         'conditions'    => array(
    //                             'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
    //                             'User.is_trash'     => 0,
    //                             'User.is_enable'     => 1,
    //                             'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
    //                         )
    //                     ));
    //                     if(isset($notifyUser['User']['id']))
    //                     {
    //                         $this->addNotification(array(                        
    //                             "user_id"   => $notifyUser['User']['id'],                        
    //                             "content"   => $notification_msg,                        
    //                             "url_link"   => "stationjournals",                    
    //                         )); 
    //                     }
    //                 }
    //                 // if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
    //                 // {
    //                 //     $notification_msg = "Earning Attendance list of prisoners are pending for approve";
    //                 //     $notifyUser = $this->User->find('first',array(
    //                 //         'recursive'     => -1,
    //                 //         'conditions'    => array(
    //                 //             'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
    //                 //             'User.is_trash'     => 0,
    //                 //             'User.is_enable'     => 1,
    //                 //             'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
    //                 //         )
    //                 //     ));
    //                 //     if(isset($notifyUser['User']['id']))
    //                 //     {
    //                 //         $this->addNotification(array(                        
    //                 //             "user_id"   => $notifyUser['User']['id'],                        
    //                 //             "content"   => $notification_msg,                        
    //                 //             "url_link"   => "stationjournals",                    
    //                 //         ));
    //                 //     }
    //                 // }
    //                 //notification on approval of payment list --END--
    //                 $this->Session->write('message_type','success');
    //                 $this->Session->write('message','Saved Successfully !');
    //             }
    //             else 
    //             {
    //                 $this->Session->write('message_type','error');
    //                 $this->Session->write('message','saving failed');
    //             }
    //         }
    //     }
    //}
        
}
?>