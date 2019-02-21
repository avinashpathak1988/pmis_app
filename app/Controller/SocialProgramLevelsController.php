<?php
App::uses('AppController','Controller');
class SocialProgramLevelsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('SocialProgramLevel');
        if(isset($this->data['SocialProgramLevelDelete']['id']) && (int)$this->data['SocialProgramLevelDelete']['id'] != 0){
            if($this->SocialProgramLevel->exists($this->data['SocialProgramLevelDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialProgramLevel->updateAll(array('SocialProgramLevel.is_trash' => 1), array('SocialProgramLevel.id'  => $this->data['SocialProgramLevelDelete']['id']))){
                    if($this->auditLog('SocialProgramLevel', 'social_program_levels', $this->data['SocialProgramLevelDelete']['id'], 'Trash', json_encode(array('SocialProgramLevel.is_trash' => 1)))){
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
        // $datas=$this->SocialProgramLevel->find('all',array(
        //     'conditions'    => array(
        //         'SocialProgramLevel.is_trash' => 0
        //     ),
        //     'order'         => array(
        //         'SocialProgramLevel.name'
        //     ),
        //     'limit'         => 50,
        // ));  
        // debug($datas);          
        // $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['SocialProgramLevel']) && is_array($this->data['SocialProgramLevel']) && count($this->data['SocialProgramLevel']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->SocialProgramLevel->save($this->request->data)){
                if(isset($this->data['SocialProgramLevel']['id']) && (int)$this->data['SocialProgramLevel']['id'] != 0){
                    if($this->auditLog('SocialProgramLevel', 'social_program_levels', $this->data['SocialProgramLevel']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SocialProgramLevel', 'social_program_levels', $this->SocialProgramLevel->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SocialProgramLevelEdit']['id']) && (int)$this->data['SocialProgramLevelEdit']['id'] != 0){
            if($this->SocialProgramLevel->exists($this->data['SocialProgramLevelEdit']['id'])){
                $this->data = $this->SocialProgramLevel->findById($this->data['SocialProgramLevelEdit']['id']);
            }
        }
        $rparents=$this->SocialProgramLevel->find('list',array(
            'conditions'=>array(
                'SocialProgramLevel.is_enable'=>1,
            ),
            'order'=>array(
                'SocialProgramLevel.name'
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
            'SocialProgramLevel.is_trash'         => 0,
            'SocialProgramLevel.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
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
            $condition += array('SocialProgramLevel.id' => $id );
        }    
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','social_programme_level_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','social_programme_level_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','social_programme_level_report_'.date('d_m_Y').'.pdf');
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
        // $datas=$this->SocialProgramLevel->find('all',array(
        //     'conditions'    => array(
        //         'SocialProgramLevel.is_trash' => 0
        //     ),
        //     'order'         => array(
        //         'SocialProgramLevel.name'
        //     ),
        //     'limit'         => 20,
        // ))+$limit;  
        //debug($datas);          
        //$this->set(compact('datas'));
         $this->paginate = array(
            'conditions'    => array(
                'SocialProgramLevel.is_trash' => 0
            ),
            'order'         => array(
                'SocialProgramLevel.id'
            ),
        )+$limit;
        $datas  = $this->paginate('SocialProgramLevel');  
        //$datas = $this->paginate('SocialProgramLevel');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
}
