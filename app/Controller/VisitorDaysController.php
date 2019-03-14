<?php
App::uses('AppController','Controller');
class VisitorDaysController extends AppController{
    public $layout='table';
    public function index(){
        $menuId = $this->getMenuId("/VisitorDays");
                $moduleId = $this->getModuleId("visitor");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('VisitorDay');
        if(isset($this->data['VisitorDayDelete']['id']) && (int)$this->data['VisitorDayDelete']['id'] != 0){
            if($this->VisitorDay->exists($this->data['VisitorDayDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->VisitorDay->updateAll(array('VisitorDay.is_trash' => 1), array('VisitorDay.id'  => $this->data['VisitorDayDelete']['id']))){
                    if($this->auditLog('VisitorDay', 'VisitorDays', $this->data['VisitorDayDelete']['id'], 'Trash', json_encode(array('VisitorDay.is_trash' => 1)))){
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
        $condition =array();
        if($this->Session->read('Auth.User.usertype_id')!= Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!= Configure::read('ADMIN_USERTYPE')){
            
            $prison_id = $this->Session->read('Auth.User.prison_id');

            $condition += array(
                'VisitorDay.prison_id ' => $prison_id,
            );
        }
        $datas=$this->VisitorDay->find('all',array(
            'conditions'    => array(
                'VisitorDay.is_trash' => 0
            )+$condition,
            'order'         => array(
                'VisitorDay.id'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        $menuId = $this->getMenuId("/VisitorDays");
                $moduleId = $this->getModuleId("visitor");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        if($this->request->is(array('post','put')) && isset($this->data['VisitorDay']) && is_array($this->data['VisitorDay']) && count($this->data['VisitorDay']) >0){
            if(isset($this->data['VisitorDay']['days']) && is_array($this->data['VisitorDay']['days']) && count($this->data['VisitorDay']['days'])>0){
                $this->request->data['VisitorDay']['days'] = implode(",", $this->data['VisitorDay']['days']);
            }
            $visitorDayData = $this->request->data;

             $visitorDay = $this->VisitorDay->find("first",array(
                "conditions"    => array(
                        "VisitorDay.is_enable"=>1,
                        "VisitorDay.is_trash"=>0,
                        "VisitorDay.prison_id"=>$visitorDayData['VisitorDay']['prison_id'],
                        "VisitorDay.prisoner_type_id"=>$visitorDayData['VisitorDay']['prisoner_type_id'],
                        

                    ),
            ));
             if(count($visitorDay) > 0 ){
                    $visitorDayData['VisitorDay']['id'] = $visitorDay['VisitorDay']['id'];
             }
            $db = ConnectionManager::getDataSource('default');
            $db->begin();   
            if($this->VisitorDay->saveAll($visitorDayData)){
                if(isset($this->data['VisitorDay']['id']) && (int)$this->data['VisitorDay']['id'] != 0){
                    if($this->auditLog('VisitorDay', 'VisitorDay', $this->data['VisitorDay']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('VisitorDay', 'VisitorDay', $this->VisitorDay->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['VisitorDayEdit']['id']) && (int)$this->data['VisitorDayEdit']['id'] != 0){
            if($this->VisitorDay->exists($this->data['VisitorDayEdit']['id'])){

                $this->data = $this->VisitorDay->findById($this->data['VisitorDayEdit']['id']);
            }
        }
         $this->loadModel('PrisonerType');
         $prisonerType = $this->PrisonerType->find('list', array(
            'fields'=>array(
                'PrisonerType.id',
                'PrisonerType.name'
            )
        ));
        $visitorDay = $this->VisitorDay->find("first",array(
            "conditions"    => array(
                    "VisitorDay.is_enable"=>1,
                    "VisitorDay.is_trash"=>0,
                    "VisitorDay.prison_id"=>0,
                    "VisitorDay.prisoner_type_id"=>0,


                ),
        ));
        $conditionPrison =array();

       if($this->Session->read('Auth.User.usertype_id')!= Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!= Configure::read('ADMIN_USERTYPE')){
            
            $prison_id = $this->Session->read('Auth.User.prison_id');

            $conditionPrison += array(
                'Prison.id ' => $prison_id,
            );
        }

        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                // 'Prison.id !='       => $prison_id
            )+$conditionPrison,
            'order'         => array(
                'Prison.name'
            ),
        ));
           
        $rparents=$this->VisitorDay->find('list',array(
            'conditions'=>array(
                'VisitorDay.is_enable'=>1,
            ),
            'order'=>array(
                'VisitorDay.id'
            ),
        ));
         $this->set(array(
            'prisonerType'=>$prisonerType,
            'prisonList'=>$prisonList
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }

    public function getPrisonerTypes(){
        $this->autoRender = false;
        $this->loadModel('PrisonerType');

        if(isset($this->data['prison_id']) && (int)$this->data['prison_id'] != 0){
            $prisonerTypeList =array();
                $visitorDays = $this->VisitorDay->find('all',array(
                    'recursive'=>'-1',
                    'conditions'=>array(
                        'VisitorDay.prison_id'=>$this->data['prison_id']
                    )
                ));
                $existingIds = array();
                $condition =array();

                foreach ($visitorDays as $day) {
                    array_push($existingIds, $day['VisitorDay']['prisoner_type_id']);
                }
                $existings = array_values($existingIds);

                if(count($existings) > 0){
                    $implodedExistings = implode(',', $existings);
                    $condition += array('PrisonerType.id not in ('.$implodedExistings .')');
                }
                $prisonerTypeList = $this->PrisonerType->find('list', array(
                    'recursive'=>'-1',
                    'fields' => array(
                        "PrisonerType.id",
                        "PrisonerType.name",
                    ),
                    'conditions'=>$condition
                ));
            if(is_array($prisonerTypeList) && count($prisonerTypeList)>0){
                echo '<option value="">--Select Prisoner Type--</option>';
                foreach($prisonerTypeList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Prisoner Type--</option>';
            }
        }else{
            echo '<option value="">--Select Prisoner Type--</option>';
        }
        exit;
    }
}
