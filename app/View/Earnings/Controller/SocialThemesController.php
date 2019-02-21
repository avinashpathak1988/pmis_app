<?php
App::uses('AppController','Controller');
class SocialThemesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('SocialTheme');
        if(isset($this->data['SocialThemeDelete']['id']) && (int)$this->data['SocialThemeDelete']['id'] != 0){
            if($this->SocialTheme->exists($this->data['SocialThemeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialTheme->updateAll(array('SocialTheme.is_trash' => 1), array('SocialTheme.id'  => $this->data['SocialThemeDelete']['id']))){
                    if($this->auditLog('SocialTheme', 'SocialThemes', $this->data['SocialThemeDelete']['id'], 'Trash', json_encode(array('SocialTheme.is_trash' => 1)))){
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
        $datas=$this->SocialTheme->find('all',array(
            'conditions'    => array(
                'SocialTheme.is_trash' => 0,
                'SocialTheme.id !='    => Configure::read('SUPERADMIN_USERTYPE'),
            ),
            'order'         => array(
                'SocialTheme.name'
            ),
            'limit'         => 50,
        ));    
         $themeTypes = array(
            'socialisation'=>'Socialisation Programmes',
            'counceling'=>'Councelling and guidance',
            'spiritual'=>'Spiritual and Moral Rehabilitation',
            'behaviour'=>'Behaviour Life Skill',
            'livelihood'=>'Livelihood Skills',
            'specificcase'=>'Specific Case Treatment'
        );         
        $this->set(compact('datas','themeTypes'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['SocialTheme']) && is_array($this->data['SocialTheme']) && count($this->data['SocialTheme']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->SocialTheme->save($this->request->data)){
                if(isset($this->data['SocialTheme']['id']) && (int)$this->data['SocialTheme']['id'] != 0){
                    if($this->auditLog('SocialTheme', 'SocialThemes', $this->data['SocialTheme']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SocialTheme', 'SocialThemes', $this->SocialTheme->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SocialThemeEdit']['id']) && (int)$this->data['SocialThemeEdit']['id'] != 0){
            if($this->SocialTheme->exists($this->data['SocialThemeEdit']['id'])){
                $this->data = $this->SocialTheme->findById($this->data['SocialThemeEdit']['id']);
            }
        }
        $rparents=$this->SocialTheme->find('list',array(
            'conditions'=>array(
                'SocialTheme.is_enable'=>1,
            ),
            'order'=>array(
                'SocialTheme.name'
            ),
        ));
        $themeTypes = array(
            'socialisation'=>'Socialisation Programmes',
            'counceling'=>'Councelling and guidance',
            'spiritual'=>'Spiritual and Moral Rehabilitation',
            'behaviour'=>'Behaviour Life Skill',
            'livelihood'=>'Livelihood Skills',
            'specificcase'=>'Specific Case Treatment'
        );

        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents','themeTypes'));
    }


    public function indexAjax(){
        $this->layout   = 'ajax';
        $from_date      = '';
        $to_date        = '';
        $id='';
        $condition      = array(
            'SocialTheme.is_trash'         => 0,
        );
        /*echo $this->params['data']['Search']['stheme_name'];
        exit;*/
        if(isset($this->params['data']['Search']['stheme_name']) && $this->params['data']['Search']['stheme_name'] != ''){
            $theme_name = $this->params['data']['Search']['stheme_name'];

            $condition += array("SocialTheme.name like '%$theme_name%'");
        }
        if(isset($this->params['data']['Search']['stheme_type']) && $this->params['data']['Search']['stheme_type'] != ''){
            $stheme_type = $this->params['data']['Search']['stheme_type'];

            $condition += array("SocialTheme.type"=>$stheme_type);
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','socialtheme_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','socialtheme_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','socialtheme_report_'.date('d_m_Y').'.pdf');
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
        // $datas=$this->SocialTheme->find('all',array(
        //     'conditions'    => array(
        //         'SocialTheme.is_trash' => 0
        //     ),
        //     'order'         => array(
        //         'SocialTheme.name'
        //     ),
        //     'limit'         => 20,
        // ))+$limit;  
        // //debug($datas);          
        // //$this->set(compact('datas'));
        // $datas = $this->paginate('SocialTheme');
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'SocialTheme.id'
            ),
        )+$limit;
        $datas  = $this->paginate('SocialTheme');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'id'            => $id,  
            //'from_date'     => $from_date,
            //'to_date'       => $to_date,            
        ));
    } 
}