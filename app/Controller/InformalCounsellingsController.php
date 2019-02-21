<?php
App::uses('AppController','Controller');
class InformalCounsellingsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('InformalCounselling');
        if(isset($this->data['InformalCounsellingDelete']['id']) && (int)$this->data['InformalCounsellingDelete']['id'] != 0){
            if($this->InformalCounselling->exists($this->data['InformalCounsellingDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->InformalCounselling->updateAll(array('InformalCounselling.is_trash' => 1), array('InformalCounselling.id'  => $this->data['InformalCounsellingDelete']['id']))){
                    if($this->auditLog('InformalCounselling', 'InformalCounsellings', $this->data['InformalCounsellingDelete']['id'], 'Trash', json_encode(array('InformalCounselling.is_trash' => 1)))){
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
        $datas=$this->InformalCounselling->find('all',array(
            'conditions'    => array(
                'InformalCounselling.is_trash' => 0,
                'InformalCounselling.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'InformalCounselling.sponser'
            ),
            'limit'         => 50,
        ));       
        //debug($datas);
        $this->set(compact('datas'));
        
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['InformalCounselling']) && is_array($this->data['InformalCounselling']) && count($this->data['InformalCounselling']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();

            if($this->InformalCounselling->save($this->request->data)){
                if(isset($this->data['InformalCounselling']['id']) && (int)$this->data['InformalCounselling']['id'] != 0){
                    if($this->auditLog('InformalCounselling', 'InformalCounsellings', $this->data['InformalCounselling']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('InformalCounselling', 'InformalCounsellings', $this->InformalCounselling->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['InformalCounsellingEdit']['id']) && (int)$this->data['InformalCounsellingEdit']['id'] != 0){
            if($this->InformalCounselling->exists($this->data['InformalCounsellingEdit']['id'])){
                $this->data = $this->InformalCounselling->findById($this->data['InformalCounsellingEdit']['id']);
            }
        }
        $rparents=$this->InformalCounselling->find('list',array(
            'conditions'=>array(
                'InformalCounselling.is_enable'=>1,
            ),
            'order'=>array(
                'InformalCounselling.id'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));

        $this->loadModel('User');
        $user = $this->User->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'User.is_enable'    => 1,
            ),
            'fields'        => array(
                'User.id',
                'User.name',
            ),
        ));
        $this->set(array(            
            'user'      => $user,
        ));

         $this->loadModel('Prisoner');
        $prisoner = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Prisoner.is_enable'    => 1,
            ),
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no'
            ),
        ));
        $this->set(array(            
            'prisoner'      => $prisoner,
        ));
        //debug($prisoner);
        $this->loadModel('SocialTheme');
        $socialtheme = $this->SocialTheme->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'SocialTheme.is_enable'    => 1,
            ),
            'fields'        => array(
                'SocialTheme.id',
                'SocialTheme.name'
            ),
        ));
        $this->set(array(            
            'socialtheme'      => $socialtheme,
        ));
      //debug($socialtheme);exit;
    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id="";
        $condition      = array(
            'InformalCounselling.is_trash'         => 0,
            'InformalCounselling.id !='   => Configure::read('SUPERADMIN_USERTYPE'),
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
        if(isset($this->params['named']['program_level_id']) && (int)$this->params['named']['program_level_id'] != 0)
         {
             $program_level_id = $this->params['named']['program_level_id'];
             $condition += array('SocialProgramLevel.program_level_id' => $program_level_id );
         } 
        if(isset($this->params['named']['id']) && (int)$this->params['named']['id'] != 0)
        {
            $id = $this->params['named']['id'];
            $condition += array('InformalCounselling.id' => $id );
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
        // $this->paginate = array(
        //     'conditions'    => $condition,
        //     'order'         => array(
        //         'SocialProgramLevel.modified',
        //     ),
        // );
        //+$limit;
         //debug($datas); 
        $datas=$this->InformalCounselling->find('all',array(
            'conditions'    => array(
                'InformalCounselling.is_trash' => 0
            ),
            'order'         => array(
                'InformalCounselling.id'
            ),
            'limit'         => 20,
        ))+$limit;  
        //debug($datas);          
        //$this->set(compact('datas'));
        $datas = $this->paginate('InformalCounselling');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    }
}