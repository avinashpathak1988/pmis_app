<?php

App::uses('AppController','Controller','NonFormalProgramModule','ModuleStage');
class NonFormalProgramsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('NonFormalProgram');
        if(isset($this->data['NonFormalProgramDelete']['id']) && (int)$this->data['NonFormalProgramDelete']['id'] != 0){
            if($this->NonFormalProgram->exists($this->data['NonFormalProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->NonFormalProgram->updateAll(array('NonFormalProgram.is_trash' => 1), array('NonFormalProgram.id'  => $this->data['NonFormalProgramDelete']['id']))){
                    if($this->auditLog('NonFormalProgram', 'NonFormalPrograms', $this->data['NonFormalProgramDelete']['id'], 'Trash', json_encode(array('NonFormalProgram.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }   
        $datas=$this->NonFormalProgram->find('all',array(
            'conditions'    => array(
                'NonFormalProgram.is_trash' => 0,
                'NonFormalProgram.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'NonFormalProgram.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['NonFormalProgram']) && is_array($this->data['NonFormalProgram']) && count($this->data['NonFormalProgram']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->NonFormalProgram->save($this->request->data)){
                if(isset($this->data['NonFormalProgram']['id']) && (int)$this->data['NonFormalProgram']['id'] != 0){
                    if($this->auditLog('NonFormalProgram', 'NonFormalPrograms', $this->data['NonFormalProgram']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('NonFormalProgram', 'NonFormalPrograms', $this->NonFormalProgram->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['NonFormalProgramEdit']['id']) && (int)$this->data['NonFormalProgramEdit']['id'] != 0){
            if($this->NonFormalProgram->exists($this->data['NonFormalProgramEdit']['id'])){
                $this->data = $this->NonFormalProgram->findById($this->data['NonFormalProgramEdit']['id']);
            }
        }
        $rparents=$this->NonFormalProgram->find('list',array(
            'conditions'=>array(
                'NonFormalProgram.is_enable'=>1,
            ),
            'order'=>array(
                'NonFormalProgram.name'
            ),
        ));
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Non Formal Program created.";
                        $notifyUser = $this->User->find('list',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        debug($notifyUser);
                        $this->addManyNotification($notifyUser,$notification_msg,"NonFormalPrograms");
                        
                        
                    }
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }

     public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id="";
        $condition      = array(
            'NonFormalProgram.is_trash'         => 0,
            'NonFormalProgram.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
        );
        // if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
        //     $from_date = $this->params['named']['from_date'];
        //     $condition += array('DATE(SocialProgramLevel.created) >=' => $from_date );
        // }
        // if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
        //     $to_date = $this->params['named']['to_date'];
        //     $condition += array('DATE(SocialProgramLevel.created) <=' => $to_date );
        // }  
        // if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0)
        // {
        //     $prison_id = $this->params['named']['prison_id'];
        //     $condition += array('SocialProgramLevel.prison_id' => $prison_id );
        // } 
        if(isset($this->params['named']['id']) && (int)$this->params['named']['id'] != 0)
        {
            $id = $this->params['named']['id'];
            $condition += array('NonFormalProgram.id' => $id );
        }    
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','nonformal_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','nonformal_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','nonformal_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }                       
        
         $this->paginate = array(
            'conditions'    => array(
                'NonFormalProgram.is_trash' => 0
            ),
            'order'         => array(
                'NonFormalProgram.id'
            ),
        )+$limit;
        $datas  = $this->paginate('NonFormalProgram');
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }


     public function programModule(){
        $this->loadModel('NonFormalProgramModule');
       
        $datas=$this->NonFormalProgramModule->find('all',array(
            'conditions'    => array(
                'NonFormalProgramModule.is_trash' => 0,
            ),
            'order'         => array(
                'NonFormalProgramModule.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }
    public function programStage(){
        $this->loadModel('ModuleStage');
       
        $datas=$this->ModuleStage->find('all',array(
            'conditions'    => array(
                'ModuleStage.is_trash' => 0,
            ),
            'order'         => array(
                'ModuleStage.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }



    public function addProgramModule(){
        $this->loadModel('NonFormalProgramModule');

        $subSchoolProgram=array();
            if(isset($this->data['SchoolProgramEdit']['id']) && (int)$this->data['SchoolProgramEdit']['id'] != 0){
            if($this->NonFormalProgramModule->exists($this->data['SchoolProgramEdit']['id'])){
                    $subSchoolProgram = $this->NonFormalProgramModule->find('first', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'NonFormalProgramModule.is_enable'    => 1,
                            'NonFormalProgramModule.id '=>$this->data['SchoolProgramEdit']['id'],
                        )
                  ));
               }
            } 
            $parent = $this->NonFormalProgram->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'NonFormalProgram.is_enable'    => 1,
                'NonFormalProgram.is_trash'=>0,
            ),
            'fields'        => array(
                'NonFormalProgram.name'
            ),
        ));
        $this->set(array(            
            'parent'      => $parent,
            'subSchoolProgram'=>$subSchoolProgram
        ));
    }

    public function addModuleSubmit(){
        $this->loadModel('NonFormalProgramModule');

            //$result = $this->request->data['SubSchoolProgram']['name']; 
             $data = $this->request->data;
           if ($this->NonFormalProgramModule->saveAll($data)) {
                echo "here3";

                $this->Session->write('message_type','success');
                $this->Session->write('message','Program Module saved Successfully !');
            } else {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Non Formal Program Module created.";
                        $notifyUser = $this->User->find('list',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        debug($notifyUser);
                        $this->addManyNotification($notifyUser,$notification_msg,"NonFormalPrograms/programModule");
                        
                        
                    }
        exit;   
    }

    public function addModuleStage(){
        $this->loadModel('NonFormalProgramModule');

        $this->loadModel('ModuleStage');
        
        $subSchoolProgram=array();
            if(isset($this->data['SchoolProgramEdit']['id']) && (int)$this->data['SchoolProgramEdit']['id'] != 0){
            if($this->ModuleStage->exists($this->data['SchoolProgramEdit']['id'])){
                    $subSchoolProgram = $this->ModuleStage->find('first', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'ModuleStage.is_enable'    => 1,
                            'ModuleStage.id '=>$this->data['SchoolProgramEdit']['id'],
                        )
                  ));
               }
            }

            $parent = $this->NonFormalProgramModule->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'NonFormalProgramModule.is_enable'    => 1,
                'NonFormalProgramModule.is_trash'=>0,
            ),
            'fields'        => array(
                'NonFormalProgramModule.name'
            ),
        ));
             if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Non Formal Program Module Stage created.";
                        $notifyUser = $this->User->find('list',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        debug($notifyUser);
                        $this->addManyNotification($notifyUser,$notification_msg,"NonFormalPrograms/programStage");
                        
                        
                    }
        $this->set(array(            
            'parent'      => $parent,
            'subSchoolProgram'=>$subSchoolProgram
        ));
    }

    public function addModuleStageSubmit(){
        $this->loadModel('ModuleStage');


            //$result = $this->request->data['SubSchoolProgram']['name']; 
             $data = $this->request->data;
           if ($this->ModuleStage->saveAll($data)) {
                echo "here3";

                $this->Session->write('message_type','success');
                $this->Session->write('message','Program module Stage saved Successfully !');
            } else {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        exit;   
    }

    public function deleteProgramModule(){
        $this->loadModel('NonFormalProgramModule');

            if(isset($this->data['SchoolProgramDelete']['id']) && (int)$this->data['SchoolProgramDelete']['id'] != 0){
            if($this->NonFormalProgramModule->exists($this->data['SchoolProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->NonFormalProgramModule->updateAll(array('NonFormalProgramModule.is_trash' => 1), array('NonFormalProgramModule.id'  => $this->data['SchoolProgramDelete']['id']))){
                    if($this->auditLog('NonFormalProgramModule', 'NonFormalProgramModules', $this->data['SchoolProgramDelete']['id'], 'Trash', json_encode(array('NonFormalProgramModule.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }  
            
                $this->redirect(array('action'=>'/programModule'));

        //exit;
       }

       public function deleteProgramModuleStage(){
        $this->loadModel('ModuleStage');

            if(isset($this->data['SchoolProgramDelete']['id']) && (int)$this->data['SchoolProgramDelete']['id'] != 0){
            if($this->ModuleStage->exists($this->data['SchoolProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->ModuleStage->updateAll(array('ModuleStage.is_trash' => 1), array('ModuleStage.id'  => $this->data['SchoolProgramDelete']['id']))){
                    if($this->auditLog('ModuleStage', 'ModuleStages', $this->data['SchoolProgramDelete']['id'], 'Trash', json_encode(array('ModuleStage.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }  
            
                $this->redirect(array('action'=>'/programStage'));

        //exit;
       }
    
}