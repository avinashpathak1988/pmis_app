<?php
App::uses('AppController','Controller');
class SocialProgramsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('SocialProgram');
        if(isset($this->data['SocialProgramDelete']['id']) && (int)$this->data['SocialProgramDelete']['id'] != 0){
            if($this->SocialProgram->exists($this->data['SocialProgramDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialProgram->updateAll(array('SocialProgram.is_trash' => 1), array('SocialProgram.id'  => $this->data['SocialProgramDelete']['id']))){
                    if($this->auditLog('SocialProgram', 'SocialPrograms', $this->data['SocialProgramDelete']['id'], 'Trash', json_encode(array('SocialProgram.is_trash' => 1)))){
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
        $datas=$this->SocialProgram->find('all',array(
            'conditions'    => array(
                'SocialProgram.is_trash' => 0,
                'SocialProgram.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'SocialProgram.program_name'
            ),
            'limit'         => 50,
        ));       
        //debug($datas);
        $this->set(compact('datas'));
        
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['SocialProgram']) && is_array($this->data['SocialProgram']) && count($this->data['SocialProgram']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();

            $sdate = date('Y-m-d',strtotime($this->request->data['SocialProgram']['start_date']));         $this->request->data['SocialProgram']['start_date'] = $sdate;
            $edate = date('Y-m-d',strtotime($this->request->data['SocialProgram']['end_date']));         $this->request->data['SocialProgram']['end_date'] = $sdate;

            if($this->SocialProgram->save($this->request->data)){
                if(isset($this->data['SocialProgram']['id']) && (int)$this->data['SocialProgram']['id'] != 0){
                    if($this->auditLog('SocialProgram', 'SocialPrograms', $this->data['SocialProgram']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SocialProgram', 'SocialPrograms', $this->SocialProgram->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SocialProgramEdit']['id']) && (int)$this->data['SocialProgramEdit']['id'] != 0){
            if($this->SocialProgram->exists($this->data['SocialProgramEdit']['id'])){
                $this->data = $this->SocialProgram->findById($this->data['SocialProgramEdit']['id']);
            }
        }
        $rparents=$this->SocialProgram->find('list',array(
            'conditions'=>array(
                'SocialProgram.is_enable'=>1,
            ),
            'order'=>array(
                'SocialProgram.program_name'
            ),
        ));
        $countProgramNo = "SELECT MAX(program_no) AS pno FROM social_programs"; 
        $ProgramNo=$this->SocialProgram->query($countProgramNo);
        $pno = $ProgramNo[0][0]['pno']+1;

        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));

        $this->loadModel('SocialProgramLevel');
        $List = $this->SocialProgramLevel->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'SocialProgramLevel.is_enable'    => 1,
            ),
            'fields'        => array(
                'SocialProgramLevel.id',
                'SocialProgramLevel.name',
            ),
        ));
        $this->set(array(            
            'List'      => $List,
        ));

         $this->loadModel('SocialProgramCategory');
        $Listing = $this->SocialProgramCategory->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'SocialProgramCategory.is_enable'    => 1,
            ),
            'fields'        => array(
                'SocialProgramCategory.id',
                'SocialProgramCategory.name'
            ),
        ));
        $this->set(array(            
            'Listing'      => $Listing,
            'pno'    => $pno
        ));
    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id="";
        $condition      = array(
            'SocialProgram.is_trash'         => 0,
            'SocialProgram.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
        );
        // if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
        //     $from_date = $this->params['named']['from_date'];
        //     $condition += array('DATE(SocialProgramLevel.created) >=' => $from_date );
        // }
        // if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
        //     $to_date = $this->params['named']['to_date'];
        //     $condition += array('DATE(SocialProgramLevel.created) <=' => $to_date );
        // }
        // if(isset($this->params['named']['program_category_id']) && (int)$this->params['named']['program_category_id'] != 0)
        //  {
        //      $program_level_id = $this->params['named']['program_category_id'];
        //      $condition += array('SocialProgramCategory.program_category_id' => $program_category_id );
        //  }   
        // if(isset($this->params['named']['program_level_id']) && (int)$this->params['named']['program_level_id'] != 0)
        //  {
        //      $program_level_id = $this->params['named']['program_level_id'];
        //      $condition += array('SocialProgramLevel.program_level_id' => $program_level_id );
        //  } 
        if(isset($this->params['named']['id']) && (int)$this->params['named']['id'] != 0)
        {
            $id = $this->params['named']['id'];
            $condition += array('SocialProgram.id' => $id );
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
       
        $this->paginate = array(
            'conditions'    => array(
                'SocialProgram.is_trash' => 0
            ),
            'order'         => array(
                'SocialProgram.id'
            ),
        )+$limit;
        $datas  = $this->paginate('SocialProgram');
       
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
}