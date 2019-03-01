<?php
App::uses('AppController','Controller');
class UsertypesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Usertype');
        if(isset($this->data['UsertypeDelete']['id']) && (int)$this->data['UsertypeDelete']['id'] != 0){
            if($this->Usertype->exists($this->data['UsertypeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Usertype->updateAll(array('Usertype.is_trash' => 1), array('Usertype.id'  => $this->data['UsertypeDelete']['id']))){
                    if($this->auditLog('Usertype', 'usertypes', $this->data['UsertypeDelete']['id'], 'Trash', json_encode(array('Usertype.is_trash' => 1)))){
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
        $datas=$this->Usertype->find('all',array(
            'conditions'    => array(
                'Usertype.is_trash' => 0,
                'Usertype.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'Usertype.name'
            ),
            'limit'         => 50,
        ));           
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Usertype']) && is_array($this->data['Usertype']) && count($this->data['Usertype']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Usertype->save($this->request->data)){
                if(isset($this->data['Usertype']['id']) && (int)$this->data['Usertype']['id'] != 0){
                    if($this->auditLog('Usertype', 'usertypes', $this->data['Usertype']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Usertype', 'usertypes', $this->Usertype->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['UsertypeEdit']['id']) && (int)$this->data['UsertypeEdit']['id'] != 0){
            if($this->Usertype->exists($this->data['UsertypeEdit']['id'])){
                $this->data = $this->Usertype->findById($this->data['UsertypeEdit']['id']);
            }
        }
        $rparents=$this->Usertype->find('list',array(
            'conditions'=>array(
                'Usertype.is_enable'=>1,
            ),
            'order'=>array(
                'Usertype.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
   public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id="";
        $condition      = array(
            'Usertype.is_trash'         => 0,
            'Usertype.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
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
            $condition += array('Usertype.id' => $id );
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
        
         $this->paginate = array(
            'conditions'    => array(
                'Usertype.is_trash' => 0
            ),
            'order'         => array(
                'Usertype.id'
            ),
        )+$limit;
        $datas  = $this->paginate('Usertype');  
        //debug($datas);          
        //$this->set(compact('datas'));
        //$datas = $this->paginate('Usertype');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
}