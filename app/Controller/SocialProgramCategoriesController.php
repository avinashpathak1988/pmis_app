<?php
App::uses('AppController','Controller');
class SocialProgramCategoriesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('SocialProgramCategory');
        if(isset($this->data['SocialProgramCategoryDelete']['id']) && (int)$this->data['SocialProgramCategoryDelete']['id'] != 0){
            if($this->SocialProgramCategory->exists($this->data['SocialProgramCategoryDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialProgramCategory->updateAll(array('SocialProgramCategory.is_trash' => 1), array('SocialProgramCategory.id'  => $this->data['SocialProgramCategoryDelete']['id']))){
                    if($this->auditLog('SocialProgramCategory', 'SocialProgramCategories', $this->data['SocialProgramCategoryDelete']['id'], 'Trash', json_encode(array('SocialProgramCategory.is_trash' => 1)))){
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
        $datas=$this->SocialProgramCategory->find('all',array(
            'conditions'    => array(
                'SocialProgramCategory.is_trash' => 0,
                'SocialProgramCategory.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'SocialProgramCategory.name'
            ),
            'limit'         => 50,
        ));             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['SocialProgramCategory']) && is_array($this->data['SocialProgramCategory']) && count($this->data['SocialProgramCategory']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->SocialProgramCategory->save($this->request->data)){
                if(isset($this->data['SocialProgramCategory']['id']) && (int)$this->data['SocialProgramCategory']['id'] != 0){
                    if($this->auditLog('SocialProgramCategory', 'SocialProgramCategories', $this->data['SocialProgramCategory']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SocialProgramCategory', 'SocialProgramCategories', $this->SocialProgramCategory->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SocialProgramCategoryEdit']['id']) && (int)$this->data['SocialProgramCategoryEdit']['id'] != 0){
            if($this->SocialProgramCategory->exists($this->data['SocialProgramCategoryEdit']['id'])){
                $this->data = $this->SocialProgramCategory->findById($this->data['SocialProgramCategoryEdit']['id']);
            }
        }
        $rparents=$this->SocialProgramCategory->find('list',array(
            'conditions'=>array(
                'SocialProgramCategory.is_enable'=>1,
            ),
            'order'=>array(
                'SocialProgramCategory.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }

    public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id='';
        $condition      = array(
            'SocialProgramCategory.is_trash'         => 0,
            'SocialProgramCategory.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
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
            $condition += array('SocialProgramCategory.id' => $id );
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
        // $datas=$this->SocialProgramCategory->find('all',array(
        //     'conditions'    => array(
        //         'SocialProgramCategory.is_trash' => 0
        //     ),
        //     'order'         => array(
        //         'SocialProgramCategory.name'
        //     ),
        //     'limit'         => 20,
        // ))+$limit;
         $this->paginate = array(
            'conditions'    => array(
                'SocialProgramCategory.is_trash' => 0
            ),
            'order'         => array(
                'SocialProgramCategory.id'
            ),
        )+$limit;
        $datas  = $this->paginate('SocialProgramCategory');  
        //debug($datas);          
        //$this->set(compact('datas'));
       // $datas = $this->paginate('SocialProgramCategory');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
}