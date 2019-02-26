<?php
App::uses('Controller', 'Controller');

class PrisonsController extends AppController{
   public $layout='table';
    public $uses=array('Prison', 'Mastersecurity', 'Stationcategory','Magisterial','Tier', 'Court', 'District','PrisonDistrict','GeographicalDistrict');
    /**
     * Index Function
     */
    public function index(){
   
          $datas=$this->Prison->find('all',array(
            'conditions'=>array(
                  'Prison.is_trash'=>0,
              ), 
              'order'=>array(
                  'Prison.name'
              )
          ));
          $this->set(compact('datas'));
    }
    /**
     * Edit Function
     */
    public function edit($id = ''){
        $this->layout='table';
        if($this->request->is(array('post','put'))){

          if(isset($this->request->data['Prison']) && $this->request->data['Prison'] != '')
          {
            $magisterial_id="";
            $magisterialidget=$this->request->data["Prison"]["magisterial_id"];
            foreach ($magisterialidget as $value) {
              $magisterial_id .=$value.',';
              # code...
            }
            $this->request->data["Prison"]["magisterial_id"]=$magisterial_id;

            $phone_no_list="";
            $phone_nos=$this->request->data["Prison"]["phone"];
            foreach ($phone_nos as $phone_no) {
              $phone_no_list .=$phone_no.',';
            }
            $this->request->data["Prison"]["phone"]=$phone_no_list;
            
            $this->request->data['Prison']['date_of_opening']=date('Y-m-d',strtotime($this->request->data['Prison']['date_of_opening']));
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Prison->save($this->request->data)){
                $refId = 0;
                $action = 'Add';
                if(isset($this->request->data['Prison']['id']) && (int)$this->request->data['Prison']['id'] != 0)
                {
                    $refId = $this->data['Prison']['id'];
                    $action = 'Edit';
                }
                //save audit log 
                if($this->auditLog('Prison', 'prisons', $refId, $action, json_encode($this->data)))
                {
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving failed');
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
          }
          else 
          {
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
          }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
          $security_id=$this->Mastersecurity->find('list',array(
                'conditions'=>array(
                  'Mastersecurity.is_enable'=>1,
                  'Mastersecurity.is_trash'=>0,
                ),
                'order'=>array(
                  'Mastersecurity.name'
                )
          ));
          $this->loadModel('Stationcategory');
          $stationcategory_id=$this->Stationcategory->find('list',array(
                'conditions'=>array(
                  'Stationcategory.is_enable'=>1,
                  'Stationcategory.is_trash'=>0,
                ),
                'order'=>array(
                  'Stationcategory.name'
                )
          ));

          $magisterial_id=$this->Magisterial->find('list',array(
                'conditions'=>array(
                  'Magisterial.is_enable'=>1,
                  'Magisterial.is_trash'=>0,
                ),
                'order'=>array(
                  'Magisterial.name'
                )
          ));
         /* $this->loadModel('State');
          $state=$this->State->find('list',array(
                'conditions'=>array(
                  'State.is_enable'=>1,
                  'State.is_trash'=>0,
                ),
                'order'=>array(
                  'State.name'
                )
          ));*/

          $this->loadModel('GeographicalRegion');
          $geographical=$this->GeographicalRegion->find('list',array(
                'conditions'=>array(
                  'GeographicalRegion.is_enable'=>1,
                  'GeographicalRegion.is_trash'=>0,
                ),
                'order'=>array(
                  'GeographicalRegion.name'
                )
          ));


        $this->set(compact('is_enable','security_id','stationcategory_id','magisterial_id','state','geographical'));
        $this->request->data=$this->Prison->findById($id);
        if(isset($id) && $id != '')
        {
          $this->request->data['Prison']['date_of_opening']=date('d-m-Y',strtotime($this->request->data['Prison']['date_of_opening']));
        }

        if(isset($id) && $id != '')
        {

                    $state = $this->State->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'State.id',
                        'State.name',
                    ),
                    'conditions'    => array(
                        'State.id'    => $this->data['Prison']['state_id'],
                    ),          
               
                    ));
                    

                    $districtList = $this->PrisonDistrict->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonDistrict.id',
                        'PrisonDistrict.name',
                    ),
                    'conditions'    => array(
                        'PrisonDistrict.id'    => $this->data['Prison']['district_id'],
                    ),          
               
                    ));
                    

                    $geodistrictList = $this->GeographicalDistrict->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'GeographicalDistrict.id',
                        'GeographicalDistrict.name',
                    ),
                    'conditions'    => array(
                        'GeographicalDistrict.id'    => $this->data['Prison']['geographical_id'],
                    ),          
               
                    ));

                     $this->set(array(
                    'districtList'     => $districtList,
                    'geodistrictList'  => $geodistrictList,
                    'state'            => $state
                ));  

                   
        }

    }
    //Detail view of prison
    function detail($id)
    {
      if($id != '')
      {
        $pCount = 0; $courtList = 'N/A';
        $prisonData  = $this->Prison->findById($id);
        if(isset($prisonData) && count($prisonData)>0)
        {
          //get no of prisoners in this station 
          $PrisonerCount = $this->Prisoner->find('all', array(
              
              'recursive'     => -1,
              'fields' => array('count(Prisoner.id)   AS total_amount'),
              'conditions' => array(
                'Prisoner.prison_id'    => $id,
                'Prisoner.is_trash'     => 0
              )
          ));
          if(isset($PrisonerCount[0][0]['total_amount']))
          {
            $pCount = $PrisonerCount[0][0]['total_amount'];
          }
          $capacity = $prisonData['Prison']['capacity'];
          $congestion_level = $capacity - $pCount;
          $prisonData['Prison']['pCount'] = $pCount;
          $prisonData['Prison']['congestion_level'] = $congestion_level;
          if($prisonData['Prison']['magisterial_id'] != '')
          {
            $courtData = $this->Court->find('list', array(
              
                'recursive'     => -1,
                'fields' => array('Court.name'),
                'conditions' => array(
                  'Court.magisterial_id' => $prisonData['Prison']['magisterial_id'],
                  'Court.is_trash'       => 0,
                  'Court.is_enable'      => 1
                ),
            ));  
            if(count($courtData)>0){
              
              $courtList = implode(', ',$courtData);
            }
            // if(count($courtData)== 1) 
            // {
            //   $courtList = $courtData;
            // }
          }
          $this->set(compact('prisonData','courtList'));
          //echo '<pre>'; print_r($courtData); exit;
        }
        else 
        {
          $this->redirect(array('action'=>'index'));
        }
      }
      else 
      {
        $this->redirect(array('action'=>'index'));
      }
    }
    /////////////////////
    public function disable($id){
        $this->Prison->id=$id;
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        if($this->Prison->saveField('is_enable',0))
        {
            if($this->auditLog('Prison', 'prisons', $id, 'Disable', json_encode(array('is_enable',0))))
            {
                $db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Disabled Successfully !');
                $this->redirect(array('action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Disable failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Disable failed');
        }
    }
    /////////////////////////
    public function enable($id){
        $this->Prison->id=$id;
        $db = ConnectionManager::getDataSource('default');
        $db->begin();  
        if($this->Prison->saveField('is_enable',1))
        {
            if($this->auditLog('Prison', 'prisons', $id, 'Enable', json_encode(array('is_enable',1))))
            {
                $db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Enabled Successfully !');
                $this->redirect(array('action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
        }
        
    }
    public function trash($id){
        $this->Prison->id=$id;
        $db = ConnectionManager::getDataSource('default');
        $db->begin(); 
        if($this->Prison->updateAll(
            array('Prison.is_trash' => 1),
            array('Prison.id' => $id)
        ))
        {
            if($this->auditLog('Prison', 'prisons', $id, 'Trash', json_encode(array('is_trash',1))))
            {
                $db->commit(); 
                $this->Session->write("message_type",'success');
                $this->Session->write('message','Trashed Successfully !');
                $this->redirect(array('controller'=>'prisons','action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
        }
    }
    public function getMiniMaxVal(){
      $this->autoRender = false;
      $tierid = $this->request->data['tierid'];
       $tier=$this->Tier->find('first',array(
                'conditions'=>array(
                  'Tier.id'=>$tierid,
                  'Tier.is_enable'=>1,
                  'Tier.is_trash'=>0,
                ),
        ));
       $minimum=$tier["Tier"]["minimum"];
       $maximum=$tier["Tier"]["maximum"];
       echo json_encode(array("minimum"=>$minimum,"maximum"=>$maximum));
    }
    public function getTierVal(){
      $this->autoRender = false; 
      $capacity = $this->request->data['capacity'];
      $tier=$this->Tier->find('first',array(
                'conditions'=>array(
                  'Tier.minimum <='=>$capacity,
                  'Tier.maximum >='=>$capacity,
                  'Tier.maximum !='=>0,
                ),
      ));
      if(count($tier)>0)
      {
        $name=$tier["Tier"]["name"];
      }
      else{
        $tier=$this->Tier->find('first',array(
                'conditions'=>array(
                  'Tier.minimum <'=>$capacity,
                 
                ),
          ));
        $name=$tier["Tier"]["name"];
      }

      echo json_encode(array("name"=>$name));
    }

     public function getgeodistrictAjax()
    {
        $this->autoRender = false;
        $district_id  = '';
        $this->loadModel("GeographicalDistrict"); 
      
        if(isset($this->params['named']['district_id']) && (int)$this->params['named']['district_id'] != 0){
            $district_id = $this->params['named']['district_id'];
            $condition = array('GeographicalDistrict.district_id' => $district_id );
            $geodistrict = $this->GeographicalDistrict->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($geodistrict) && count($geodistrict)>0){
                echo '<option value="">--Select Geographical District--</option>';
                foreach($geodistrict as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Geographical District--</option>';
            }
        }else
        {
            echo '<option value="">--Select Geographical District--</option>';
        }
        
    }

    /*public function getgeographicalAjax()
    {
        $this->autoRender = false;
        $geographicalr_id  = '';
        $this->loadModel("GeographicalRegion"); 
      
        if(isset($this->params['named']['geographicalr_id']) && (int)$this->params['named']['geographicalr_id'] != 0){
            $geographicalr_id = $this->params['named']['geographicalr_id'];
            $condition = array('GeographicalRegion.id' => $geographicalr_id );
            $georegion = $this->GeographicalRegion->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($georegion) && count($georegion)>0){
                echo '<option value="">--Select UPS Region--</option>';
                foreach($georegion as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select UPS Region--</option>';
            }
        }else
        {
            echo '<option value="">--Select UPS Region--</option>';
        }
        
    }*/
 public function getgeographicalAjax()
    {
        $this->autoRender = false;
        //$id  = '';
        $this->loadModel("State"); 
      
        if(isset($this->params['named']['geographical_region_id']) && (int)$this->params['named']['geographical_region_id'] != 0){
            $geographical_region_id = $this->params['named']['geographical_region_id'];
            $condition = array('State.geographical_region_id' => $geographical_region_id);
            $upsregion = $this->State->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($upsregion) && count($upsregion)>0){
                echo '<option value="">--Select UPS Region--</option>';
                foreach($upsregion as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select UPS Region--</option>';
            }
        }else
        {
            echo '<option value="">--Select UPS Region--</option>';
        }
        
    }
	public function getdistrictAjax()
    {
        $this->autoRender = false;
        $this->loadModel("PrisonDistrict"); 
       if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $condition = array('PrisonDistrict.state_id' => $this->params['named']['state_id'] );
            $district = $this->PrisonDistrict->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($district) && count($district)>0){
                echo '<option value="">--Select PrisonDistrict--</option>';
                foreach($district as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select PrisonDistrict--</option>';
            }
        }else
        {
            echo '<option value="">--Select PrisonDistrict--</option>';
        }
        
    } 
}
