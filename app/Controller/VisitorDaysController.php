<?php
App::uses('AppController','Controller');
class VisitorDaysController extends AppController{
    public $layout='table';
    public function index(){
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
        $datas=$this->VisitorDay->find('all',array(
            'conditions'    => array(
                'VisitorDay.is_trash' => 0,
                'VisitorDay.prison_id' => $this->Session->read('Auth.User.prison_id')
            ),
            'order'         => array(
                'VisitorDay.id'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        $conditions = array();
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

        ));
        $visitorDay = $this->VisitorDay->find("first",array(
            "conditions"    => array(
                    "VisitorDay.is_enable"=>1,
                    "VisitorDay.is_trash"=>0,
                    "VisitorDay.prison_id"=>0,
                    "VisitorDay.prisoner_type_id"=>0,


                ),
        ));
        if ($this->Session->read('Auth.User.prison_id')!='') {
            
        
         $prisonList = $this->Prison->find("list",array(
                "conditions"    => array(
                    "Prison.is_enable"=>1,
                    "Prison.is_trash"=>0,
                    "Prison.is_trash"=>0,
                    "Prison.id"=>$this->Session->read('Auth.User.prison_id'),

                ),
                "fields"        => array(
                    "Prison.id",
                    "Prison.name",
                ),
            ));
        }else{
             $prisonList = $this->Prison->find("list",array(
                "conditions"    => array(
                    "Prison.is_enable"=>1,
                    "Prison.is_trash"=>0,
                    "Prison.is_trash"=>0,
                    

                ),
                "fields"        => array(
                    "Prison.id",
                    "Prison.name",
                ),
            ));
        }
           
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
}
