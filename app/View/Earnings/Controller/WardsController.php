<?php
App::uses('AppController','Controller');
class WardsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Ward');
        if(isset($this->data['WardDelete']['id']) && (int)$this->data['WardDelete']['id'] != 0){
            if($this->Ward->exists($this->data['WardDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Ward->updateAll(array('Ward.is_trash' => 1), array('Ward.id'  => $this->data['WardDelete']['id']))){
                    if($this->auditLog('Ward', 'wards', $this->data['WardDelete']['id'], 'Trash', json_encode(array('Ward.is_trash' => 1)))){
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
        $datas=$this->Ward->find('all',array(
            'conditions'    => array(
                'Ward.is_trash' => 0,
                'Ward.id !='    => Configure::read('SUPERADMIN_Ward'),
            ),
            'order'         => array(
                'Ward.name'
            ),
            'limit'         => 50,
        ));    
         $this->loadModel('WardType');
          $this->loadModel('Prison');    
        $prisonlist   = $this->Prison->find('list');   
        $wardlist   = $this->WardType->find('list');   
         $this->set(array(
            'wardlist'  => $wardlist,
            'prisonlist' => $prisonlist,
            //'shift_id'   => $shift_change,

        )); 

        $this->set(compact('datas'));

    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Ward']) && is_array($this->data['Ward']) && count($this->data['Ward']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Ward->save($this->request->data)){
                if(isset($this->data['Ward']['id']) && (int)$this->data['Ward']['id'] != 0){
                    if($this->auditLog('Ward', 'Wards', $this->data['Ward']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Ward', 'Wards', $this->Ward->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['WardEdit']['id']) && (int)$this->data['WardEdit']['id'] != 0){
            if($this->Ward->exists($this->data['WardEdit']['id'])){
                $this->data = $this->Ward->findById($this->data['WardEdit']['id']);
            }
        }
        $rparents=$this->Ward->find('list',array(
            'conditions'=>array(
                'Ward.is_enable'=>1,
            ),
            'order'=>array(
                'Ward.name'
            ),
        ));
        $this->loadModel('WardType');
        $wardlist = $this->WardType->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'WardType.id',
                'WardType.name',
            ),
            'conditions'    => array(
                'WardType.is_enable'      => 1,
                'WardType.is_trash'       => 0
            ),
            'order'         => array(
                'WardType.name'
            ),
        ));
         $this->loadModel('Gender');
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        $this->loadModel('Prison');
        $prisonlist = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

         $this->set(array(
            'wardlist'  => $wardlist,
            'prisonlist' => $prisonlist,
            'genderList' => $genderList,
            //'shift_id'   => $shift_change,

        )); 
       
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
   public function indexAjax(){
        $this->loadModel('WardType');
        $this->loadModel('Prison');
        $this->layout   = 'ajax';
        $prison      = '';
        $ward_type        = '';
        $id=  '';
    
        $condition      = array(
            'Ward.is_trash'         => 0,
           // 'Ward.id !='   => Configure::read('SUPERADMIN_Ward'),
        );
        //debug($this->params['named']);
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
            $condition += array('Ward.id' => $id );
        }    
        if(isset($this->params['named']['prison']) && (int)$this->params['named']['prison'] != 0)
        {
            $id = $this->params['named']['prison'];
            $condition += array('Ward.prison' => $id );
        }   
         if(isset($this->params['named']['ward_type']) && (int)$this->params['named']['ward_type'] != 0)
        {
            $id = $this->params['named']['ward_type'];
            $condition += array('Ward.ward_type' => $id );
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
                $this->layout='export_xls';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }                       
      // debug($condition);
         $this->paginate = array(
            'conditions'    => array(
                'Ward.is_trash' => 0
            )+$condition,
            'order'         => array(
                'Ward.id'
            ),
        )+$limit;
        $datas  = $this->paginate('Ward');  

        //debug($datas);          
        //$this->set(compact('datas'));
        //$datas = $this->paginate('Ward');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'             =>$id,
            'prison'            => $prison, 
            'ward_type'         => $ward_type, 
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
}