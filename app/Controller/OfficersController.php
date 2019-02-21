<?php
App::uses('Controller', 'Controller');

class OfficersController extends AppController{
    public $layout='table';
      public $uses=array('Prison','Officer','User','Staffcategory');
     /**
     * Index Function
     */
    public function index() {
       $prison_id=$this->Prison->find('list',array(
                'conditions'=>array(
                  'Prison.is_enable'=>1,
                  'Prison.is_trash'=>0,
                ),
                'order'=>array(
                  'Prison.name'
                )
          ));
       $this->set(compact('prison_id'));
    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $condition      = array(
            'Officer.is_trash'         => 0,
        );
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Officer.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','officer_mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','officer_mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','officer_mis_report_'.date('d_m_Y').'.pdf');
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
                'Officer.id'=>'desc',
            ),
            
        )+$limit;
        $datas = $this->paginate('Officer');
        $this->set(array(
            'datas'         => $datas,
            'prison_id'     => $prison_id,      
        ));
    }
    /**
     * Add Function
     */
    public function add(){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $this->request->data['Officer']['dob']=date('Y-m-d',strtotime($this->request->data['Officer']['dob']));
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Officer->save($this->request->data)){
                if($this->auditLog('Officer', 'officers', 0, 'Add', json_encode($this->data)))
                {
                    $this->request->data["User"]['first_name']=$this->request->data['Officer']['first_name'];
                    $this->request->data["User"]['last_name']=$this->request->data['Officer']['last_name'];
                    $this->request->data["User"]['prison_id']=$this->request->data['Officer']['prison_id'];
                    $this->request->data["User"]['force_number']=$this->request->data['Officer']['force_number'];
                    $this->request->data["User"]['usertype_id']=0;
                    $uuid = $this->User->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['User']['uuid'] = $uuid;
                    $this->request->data['User']['name'] = trim($this->data['User']['first_name']).' '.trim($this->data['User']['last_name']);
                    $this->request->data['User']['officer_id']=$this->Officer->id;
                    if($this->User->saveAll($this->request->data["User"]))
                    {
                        if($this->auditLog('User', 'users', $this->Officer->id, 'Update', json_encode($this->request->data["User"])))
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
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
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
          // $staff_category=array(
          //   'Senior'=>'Senior',
          //   'Junior'=>'Junior'
          // );
          $staff_category = $this->Staffcategory->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Staffcategory.id',
                        'Staffcategory.category_name',
                    ),
                    'conditions'    => array(
                        'Staffcategory.is_enable'     => 1,
                        'Staffcategory.is_trash'      => 0
                    ),
                    'order'         => array(
                        'Staffcategory.category_name'
                    ),
                ));
        $this->set(compact('is_enable','prison_id','staff_category'));
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
             $this->request->data['Officer']['dob']=date('Y-m-d',strtotime($this->request->data['Officer']['dob']));
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Officer->save($this->request->data)){

                if($this->auditLog('Officer', 'officers', $this->request->data['Officer']['id'], 'Edit', json_encode($this->data)))
                {
                    $userList = $this->User->find('first', array(
                        'conditions'    => array(
                            'User.officer_id'    => $this->request->data['Officer']['id'],
                        ),
                    ));
                    if(count($userList)>0)
                    {
                        $first_name=$this->request->data['Officer']['first_name'];
                        $last_name=$this->request->data['Officer']['last_name'];
                        $prison_id=$this->request->data['Officer']['prison_id'];
                        $force_number=$this->request->data['Officer']['force_number'];
                        $userFields = array('User.first_name' => "'$first_name'",'User.last_name' => "'$last_name'",'User.prison_id' => "'$prison_id'",'User.force_number'=>"'$force_number'");
                        if($this->User->updateAll(
                            $userFields,
                            array('User.officer_id' => $this->request->data['Officer']['id'])
                        ))
                        {
                            if($this->auditLog('User', 'users', $this->request->data['Officer']['id'], 'Edit', json_encode($userFields)))
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
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving failed');
                }
            }else{
                $$db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
         $staff_category=array(
            'Senior'=>'Senior',
            'Junior'=>'Junior'
          );
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
        $this->set(compact('is_enable','prison_id','staff_category'));
        $this->request->data=$this->Officer->findById($id);
    }
    /////////////////////
    public function disable($id){

      $userList = $this->Officer->find('first', array(
                    'conditions'    => array(
                        'Officer.id'    => $id,
                    ),
                ));
        $force_number=$userList['Officer']['force_number'];
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        if($this->User->updateAll(
            array('User.is_enable' => 0),
            array('User.force_number' => $force_number)
        ))
        {
            if($this->auditLog('User', 'users', $force_number, 'Disable', json_encode(array('is_enable',0))))
            {
                $this->Officer->id=$id;
                if($this->Officer->saveField('is_enable',0))
                {
                    if($this->auditLog('Officer', 'officers', $force_number, 'Disable', json_encode(array('is_enable',0))))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Disabled Successfully !');
                        $this->redirect(array('action'=>'index'));
                    }
                    else {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Disable failed');
                    }
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Disable failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Disable failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Disable failed');
        }
    }
    /////////////////////////
    public function enable($id){

      $userList = $this->Officer->find('first', array(
                    'conditions'    => array(
                        'Officer.id'    => $id,
                    ),
                ));
        $force_number=$userList['Officer']['force_number'];
        if($this->User->updateAll(
            array('User.is_enable' => 1),
            array('User.force_number' => $force_number)
        ))
        {
            if($this->auditLog('User', 'users', $force_number, 'Enable', json_encode(array('is_enable',1))))
            {
                $this->Officer->id=$id;
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->Officer->saveField('is_enable',1))
                {
                    if($this->auditLog('Officer', 'officers', $force_number, 'Enable', json_encode(array('is_enable',1))))
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
                        $this->Session->write('message','Enable failed');
                    }
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Enable failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Enable failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Enable failed');
        }
    }
    public function trash($id){


       $userList = $this->Officer->find('first', array(
                    'conditions'    => array(
                        'Officer.id'    => $id,
                    ),
                ));
        $force_number=$userList['Officer']['force_number'];
        if($this->User->updateAll(
            array('User.is_trash' => 1),
            array('User.force_number' => $force_number)
        ))
        {
            if($this->auditLog('User', 'users', $force_number, 'Enable', json_encode(array('is_enable',1))))
            {
                $this->Officer->id=$id;
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->Officer->updateAll(
                    array('Officer.is_trash' => 1),
                    array('Officer.id' => $id)
                ))
                {
                    if($this->auditLog('Officer', 'officers', $force_number, 'Enable', json_encode(array('is_enable',1))))
                    {
                        $db->commit();
                        $this->Session->write("message_type",'success');
                        $this->Session->write('message','Trashed Successfully !');
                        $this->redirect(array(
                            'controller'=>'officers',
                            'action'=>'index'
                        ));
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Trash failed');
                    }
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Trash failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Trash failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Trash failed');
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
}
?>