<?php
App::uses('AppController','Controller');
class SchoolProgramsController extends AppController{
    public $layout='table';
        public $uses=array('SchoolProgram','SubSchoolProgram','SubCategorySchoolProgram');

    public function index(){
        $this->loadModel('SchoolProgram');
        if(isset($this->data['SchoolProgramDelete']['id']) && (int)$this->data['SchoolProgramDelete']['id'] != 0){
            if($this->SchoolProgram->exists($this->data['SchoolProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SchoolProgram->updateAll(array('SchoolProgram.is_trash' => 1), array('SchoolProgram.id'  => $this->data['SchoolProgramDelete']['id']))){
                    if($this->auditLog('SchoolProgram', 'SchoolPrograms', $this->data['SchoolProgramDelete']['id'], 'Trash', json_encode(array('SchoolProgram.is_trash' => 1)))){
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
        $datas=$this->SchoolProgram->find('all',array(
            'conditions'    => array(
                'SchoolProgram.is_trash' => 0,
                'SchoolProgram.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'SchoolProgram.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }
    public function add(){
        //debug($datas);
        if($this->request->is(array('post','put')) && isset($this->data['SchoolProgram']) && is_array($this->data['SchoolProgram']) && count($this->data['SchoolProgram']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->SchoolProgram->save($this->request->data)){
                if(isset($this->data['SchoolProgram']['id']) && (int)$this->data['SchoolProgram']['id'] != 0){
                    if($this->auditLog('SchoolProgram', 'SchoolPrograms', $this->data['SchoolProgram']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SchoolProgram', 'SchoolPrograms', $this->SchoolProgram->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SchoolProgramEdit']['id']) && (int)$this->data['SchoolProgramEdit']['id'] != 0){
            if($this->SchoolProgram->exists($this->data['SchoolProgramEdit']['id'])){
                $this->data = $this->SchoolProgram->findById($this->data['SchoolProgramEdit']['id']);
            }
        }
        $rparents=$this->SchoolProgram->find('list',array(
            'conditions'=>array(
                'SchoolProgram.is_enable'=>1,
                'SchoolProgram.is_trash'=>0,
            ),
            'order'=>array(
                'SchoolProgram.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));

        $parent = $this->SchoolProgram->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'SchoolProgram.is_enable'    => 1,
                'SchoolProgram.is_trash' => 0,

            ),
            'fields'        => array(
                'SchoolProgram.name'
            ),
        ));
        $this->set(array(            
            'parent'      => $parent,
        ));
        $sub_parent = $this->SchoolProgram->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'SchoolProgram.is_enable'    => 1,
                'SchoolProgram.is_trash'    => 0,

            ),
            'fields'        => array(
                'SchoolProgram.name'
            ),
        ));
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Rehabilitation School Program created.";
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
                        $this->addManyNotification($notifyUser,$notification_msg,"SchoolPrograms");
                        
                        
                    }
        $this->set(array(            
            'sub_parent'      => $sub_parent,
        ));
    }

    public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id="";
        $condition      = array(
            'SchoolProgram.is_trash'         => 0,
            'SchoolProgram.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
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
            $condition += array('SchoolProgram.id' => $id );
        }    
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
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
        // $this->paginate = array(
        //     'conditions'    => $condition,
        //     'order'         => array(
        //         'SocialProgramLevel.modified',
        //     ),
        // );
        //+$limit;
        // $datas=$this->SchoolProgram->find('all',array(
        //     'conditions'    => array(
        //         'SchoolProgram.is_trash' => 0
        //     ),
        //     'order'         => array(
        //         'SchoolProgram.name'
        //     ),
        //     'limit'         => 20,
        // ))+$limit;  
        $this->paginate = array(
            'conditions'    => array(
                'SchoolProgram.is_trash' => 0
            ),
            'order'         => array(
                'SchoolProgram.id'
            ),
        )+$limit;
        $datas  = $this->paginate('SchoolProgram'); 
        //debug($datas);          
        //$this->set(compact('datas'));
        //$datas = $this->paginate('SchoolProgram');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }

     public function subcategory(){
       
       
        $datas=$this->SubSchoolProgram->find('all',array(
            'conditions'    => array(
                'SubSchoolProgram.is_trash' => 0,
            ),
            'order'         => array(
                'SubSchoolProgram.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }
    public function addsubcategorysubmit(){
            //$result = $this->request->data['SubSchoolProgram']['name']; 
             $data = $this->request->data;
           if ($this->SubSchoolProgram->saveAll($data)) {
                $this->Session->write('message_type','success');
                $this->Session->write('message','Sub category Program saved Successfully !');
            } else {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        //$this->redirect(array('action'=>'/subcategory'));
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Subcategory School Program created.";
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
                        $this->addManyNotification($notifyUser,$notification_msg,"SchoolPrograms/subcategory");
                        
                        
                    }
        exit;   
    }

       public function subcategoryAjax(){
        $this->layout   = 'ajax';
        
        $this->paginate = array(
            'conditions'    => array(
                'SubSchoolProgram.is_trash' => 0
            ),
            'order'         => array(
                'SubSchoolProgram.id'
            ),
        )+$limit;
        $datas  = $this->paginate('SubSchoolProgram'); 
        //debug($datas);          
        //$this->set(compact('datas'));
        //$datas = $this->paginate('SchoolProgram');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }


        public function addsubcategory(){
            $subSchoolProgram=array();
            if(isset($this->data['SchoolProgramEdit']['id']) && (int)$this->data['SchoolProgramEdit']['id'] != 0){
            if($this->SubSchoolProgram->exists($this->data['SchoolProgramEdit']['id'])){
                    $subSchoolProgram = $this->SubSchoolProgram->find('first', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'SubSchoolProgram.is_enable'    => 1,
                            'SubSchoolProgram.id '=>$this->data['SchoolProgramEdit']['id'],
                        )
                  ));
               }
            } 
            $parent = $this->SchoolProgram->find('list', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'SchoolProgram.is_enable'    => 1,
                    'SchoolProgram.is_trash'    => 0,

                ),
                'fields'        => array(
                    'SchoolProgram.name'
                ),
            ));
        $this->set(array(            
            'parent'      => $parent,
            'subSchoolProgram'=>$subSchoolProgram
        ));
    }

    public function addsubsubcategory(){
        $subSchoolProgram=array();
            $parent=array();

            if(isset($this->data['SchoolProgramEdit']['id']) && (int)$this->data['SchoolProgramEdit']['id'] != 0){
            if($this->SubCategorySchoolProgram->exists($this->data['SchoolProgramEdit']['id'])){
                    $subSchoolProgram = $this->SubCategorySchoolProgram->find('first', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'SubCategorySchoolProgram.is_enable'    => 1,
                            'SubCategorySchoolProgram.id '=>$this->data['SchoolProgramEdit']['id'],
                        )
                  ));
                    $parent = $this->SubSchoolProgram->find('list', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'SubSchoolProgram.is_enable'    => 1,
                            'SubSchoolProgram.is_trash'    => 0,
                            'SubSchoolProgram.school_program_id != "null"',
                        ),
                        'fields'        => array(
                            'SubSchoolProgram.name'
                        ),
                    ));
               }

               $this->request->data =$subSchoolProgram;
            } 
            
            $schoolPrograms = $this->SchoolProgram->find('list', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'SchoolProgram.is_enable'    => 1,
                    'SchoolProgram.is_trash'    => 0,

                ),
                'fields'        => array(
                    'SchoolProgram.name'
                ),
            ));
            
        
        $this->set(array(            
            'parent'      => $parent,
            'schoolPrograms'=>$schoolPrograms,
            'subSchoolProgram'=>$subSchoolProgram
        ));
    }
    public function addsubsubcategorysubmit(){
             $data = $this->request->data;
           if ($this->SubCategorySchoolProgram->saveAll($data)) {
                $this->Session->write('message_type','success');
                $this->Session->write('message','Sub category Program saved Successfully !');
            } else {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
                //$this->redirect(array('action'=>'/subsubcategory'));
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
                    {
                        
                        $notification_msg = "Sub Subcategory School Program created.";
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
                        $this->addManyNotification($notifyUser,$notification_msg,"SchoolPrograms/subsubcategory");
                        
                        
                    }
        exit;  
    }

    public function subsubcategory(){
       
        $datas=$this->SubCategorySchoolProgram->find('all',array(
            'conditions'    => array(
                'SubCategorySchoolProgram.is_trash' => 0,
            ),
            'order'         => array(
                'SubCategorySchoolProgram.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }

       public function subsubcategoryAjax(){
        $this->layout   = 'ajax';
        
        $this->paginate = array(
            'conditions'    => array(
                'SubCategorySchoolProgram.is_trash' => 0
            ),
            'order'         => array(
                'SubCategorySchoolProgram.id'
            ),
            'limit'=>10
        );
        $datas  = $this->paginate('SubCategorySchoolProgram'); 
        //debug($datas);          
        //$this->set(compact('datas'));
        //$datas = $this->paginate('SchoolProgram');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            //'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
       public function deleteSubSchoolProgram(){
            if(isset($this->data['SchoolProgramDelete']['id']) && (int)$this->data['SchoolProgramDelete']['id'] != 0){
            if($this->SubSchoolProgram->exists($this->data['SchoolProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SubSchoolProgram->updateAll(array('SubSchoolProgram.is_trash' => 1), array('SubSchoolProgram.id'  => $this->data['SchoolProgramDelete']['id']))){
                    if($this->auditLog('SubSchoolProgram', 'SubSchoolPrograms', $this->data['SchoolProgramDelete']['id'], 'Trash', json_encode(array('SubSchoolProgram.is_trash' => 1)))){
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
            
                $this->redirect(array('action'=>'/subcategory'));

        //exit;
       }

       public function deleteSubSubProgram(){
            if(isset($this->data['SchoolProgramDelete']['id']) && (int)$this->data['SchoolProgramDelete']['id'] != 0){
            if($this->SubSchoolProgram->exists($this->data['SchoolProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SubCategorySchoolProgram->updateAll(array('SubCategorySchoolProgram.is_trash' => 1), array('SubCategorySchoolProgram.id'  => $this->data['SchoolProgramDelete']['id']))){
                    if($this->auditLog('SubCategorySchoolProgram', 'SubCategorySchoolPrograms', $this->data['SchoolProgramDelete']['id'], 'Trash', json_encode(array('SubCategorySchoolProgram.is_trash' => 1)))){
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
            
                $this->redirect(array('action'=>'/subsubcategory'));

        //exit;
       }
    
}