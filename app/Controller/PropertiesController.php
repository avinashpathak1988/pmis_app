<?php
App::uses('AppController', 'Controller');
class PropertiesController   extends AppController {
    public $layout='table';
    public $uses=array('Prisoner', 'Property', 'Propertyitem', 'PropertyDestroy','PropertyOutgoing','PropertyDischarge','PhysicalPropertyItem','PhysicalProperty','Currency','CashItem','PropertyTransaction','PrisonerType','ApprovalProcess','DebitCash','Transaction','OutgoingStatus','PrisonerKinDetail');

    
    public function getPropertyType(){
        $this->layout   = 'ajax';
        $id = $this->request->data['id'];

        $prisonId = $this->Session->read('Auth.User.prison_id');

       $propertyItem =  $this->Propertyitem->findById($id);

        if(isset($propertyItem['Propertyitem']['is_allowed'])){

            if($propertyItem['Propertyitem']['is_allowed'] == 1){
                echo 'allowed';
            }else if(isset($propertyItem['Propertyitem']['is_prohibited']) && $propertyItem['Propertyitem']['is_prohibited'] == 1){
                echo 'prohibited,'.$propertyItem['Propertyitem']['property_type_prohibited'];
            }

        }else{
            echo 'failure';
        }
        exit;
    }

    public function getPropertyTypeNew($id=''){
        if($id != ''){
             $prisonId = $this->Session->read('Auth.User.prison_id');

               $propertyItem =  $this->Propertyitem->findById($id);

                if(isset($propertyItem['Propertyitem']['is_allowed'])){

                if($propertyItem['Propertyitem']['is_allowed'] == 1){
                    return 'allowed';
                }else if(isset($propertyItem['Propertyitem']['is_prohibited']) && $propertyItem['Propertyitem']['is_prohibited'] == 1){
                    return 'prohibited,'.$propertyItem['Propertyitem']['property_type_prohibited'];
                }

            }else{
                return 'failure';
            }
        }else{
            return 'failure';

        }
       
    }

    public function getitemname($id){
        $propertyItemList = $this->Propertyitem->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Propertyitem.id',
                        'Propertyitem.name',
                    ),
                    'conditions'    => array(
                        'Propertyitem.is_enable'    => 1,
                        'Propertyitem.id'    => $id,
                        'Propertyitem.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Propertyitem.name'
                    )
                ));
        return $propertyItemList["Propertyitem"]["name"];
    }
    public function bagnoExistproperty(){
        $this->autoRender = false;
        $this->loadModel('PhysicalPropertyItem');
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $bagNo=$this->request->data['bagNo'];
        $propertyid=$this->request->data["propertyid"];
        if($propertyid==""){
            $bagno_existance=$this->PhysicalPropertyItem->find('count',array(
                    'recursive' => -1,
                    'conditions'=>array(
                        'PhysicalPropertyItem.bag_no'=>$bagNo,
                        'PhysicalPropertyItem.prison_id'=> $prison_id,                    
                    ),
               )
            );

            if(isset($bagno_existance) && $bagno_existance==1){
                echo "false";
            }else{
                echo "true";            
            }
            exit;
        }
        else{
            echo "true";
            exit;
        }
    }
    
   
    public function getcurrencyname($id){
        $currencyList = $this->Currency->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Currency.id',
                        'Currency.name',
                    ),
                    'conditions'    => array(
                        'Currency.is_enable'    => 1,
                        'Currency.id'    => $id,
                        'Currency.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Currency.name'
                    )
                ));
        return $currencyList["Currency"]["name"];
    }
    
    public function index($prisoner_uuid)
    {
        $prisoner_id="";
        $prisonList = $this->Prisoner->find('first', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Prisoner.uuid'     => $prisoner_uuid,
            ),
        ));
        if(isset($prisonList['Prisoner']['id']) && (int)$prisonList['Prisoner']['id'] != 0){

            $prisoner_id = $prisonList['Prisoner']['id'];
        }
        $prisonerKin = $this->PrisonerKinDetail->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonerKinDetail.id',
                        'PrisonerKinDetail.first_name',
                        'PrisonerKinDetail.middle_name',
                        'PrisonerKinDetail.last_name',


                    ),
                    'conditions'    => array(
                        'PrisonerKinDetail.is_trash'     => 0,
                         'PrisonerKinDetail.prisoner_id'     => $prisoner_id,

                    ),
                    'order'=>array(
                        'PrisonerKinDetail.id' => 'desc',
                    )
                )); 
            //debug($prisonerKin);
        $propertyItemList = $this->Propertyitem->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'Propertyitem.id',
                    'Propertyitem.name',
                ),
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,
                    'Propertyitem.is_prohibited'     => 0,

                ),
                'order'=>array(
                    'Propertyitem.name'
                )
            ));
        $currencyList = $this->Currency->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Currency.id',
                'Currency.name',
            ),
            'conditions'    => array(
                'Currency.is_enable'    => 1,
                'Currency.is_trash'     => 0,
            ),
            'order'=>array(
                'Currency.name'
            )
        )); 
        //get prisoner currency list 
        $prisonerCurrencyList = $this->CashItem->find('list',array(
            'recursive' => 1,
            'fields'        => array(
                'CashItem.currency_id',
            ),
            'conditions'    => array(
                'CashItem.is_trash'         => 0,
                'PhysicalProperty.prisoner_id' => $prisoner_id
            ),
        ));  
        //echo '<pre>'; print_r($prisonerCurrencyList); exit;   
        $debitCurrencyList = '';
        if(count($prisonerCurrencyList) > 0)
        {
            $prisonerCurrencyList = implode(',',$prisonerCurrencyList);
            $debitCurrencyList = $this->Currency->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'Currency.id',
                    'Currency.name',
                ),
                'conditions'    => array(
                    'Currency.is_enable'    => 1,
                    'Currency.is_trash'     => 0,
                    'Currency.id in ('.$prisonerCurrencyList.')'
                ),
                'order'=>array(
                    'Currency.name'
                )
            )); 
        } 
        $statusList=array('Incoming'=>'Incoming','Supplementary Incoming'=>'Supplementary Incoming','Outgoing'=>'Outgoing','Supplementary Outgoing'=>'Supplementary Outgoing','Destroy'=>'Destroyed');
        if(isset($this->data['PhysicalPropertyDelete']['id']) && (int)$this->data['PhysicalPropertyDelete']['id'] != 0){
            if($this->PhysicalProperty->exists($this->data['PhysicalPropertyDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->PhysicalProperty->updateAll(array('PhysicalProperty.is_trash' => 1), array('PhysicalProperty.id'  => $this->data['PhysicalPropertyDelete']['id']))){
                    $propertyItems = $this->PhysicalPropertyItem->find('all',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'PhysicalPropertyItem.physicalproperty_id'    => $this->data['PhysicalPropertyDelete']['id'],
                            )
                        ));
                    foreach ($propertyItems as $propertyItem) {
                        $this->PhysicalPropertyItem->updateAll(array('PhysicalPropertyItem.is_trash' => 1), array('PhysicalPropertyItem.id'  => $propertyItem['PhysicalPropertyItem']['id']));
                    }

                    if($this->auditLog('PhysicalProperty', 'Properties', $this->data['PhysicalPropertyDelete']['id'], 'Trash', json_encode(array('PhysicalProperty.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                        $this->redirect(array('action'=>'index/'.$prisoner_uuid.'#physical_property'));
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

        if(isset($this->data['PhysicalPropertyCashDelete']['id']) && (int)$this->data['PhysicalPropertyCashDelete']['id'] != 0){
            if($this->PhysicalProperty->exists($this->data['PhysicalPropertyCashDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->PhysicalProperty->updateAll(array('PhysicalProperty.is_trash' => 1), array('PhysicalProperty.id'  => $this->data['PhysicalPropertyCashDelete']['id']))){
                    if($this->auditLog('PhysicalProperty', 'Properties', $this->data['PhysicalPropertyCashDelete']['id'], 'Trash', json_encode(array('PhysicalProperty.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                        $this->redirect(array('action'=>'index/'.$prisoner_uuid.'#cash_property'));
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
        $this->set('funcall',$this);
        $cdate = date('d-m-Y H:i:s');

        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }

        //save cash property 
         if($this->request->is(array('post','put')))
         {
            //echo '<pre>'; print_r($this->request->data); exit;
            if(isset($this->request->data['CashItem']) && count($this->request->data['CashItem'])>0)
            {
                $this->cashproperty($this->request->data);
                unset($this->request->data['CashItem']);
            }
            if(isset($this->request->data['DebitCash']) && count($this->request->data['DebitCash'])>0)
            {
                $this->debitCash($this->request->data);
                unset($this->request->data['DebitCash']);
                
            }
         }
        $isCreditEdit = 0;
        if(isset($this->data["CashPropertyEdit"]))
        {//debug($this->data);exit;
            $isCreditEdit = 1;
            $this->request->data=$this->PhysicalProperty->findById($this->data["CashPropertyEdit"]["id"]);
        }
        $canCredit=0;
        $isDebitEdit = 0;
        if(isset($this->data["DebitCashEdit"]))
        {
            $isDebitEdit = 1;
            $this->request->data=$this->DebitCash->findById($this->data["DebitCashEdit"]["id"]);
        }

        $canDebit = 0;
        if($prisoner_id != '')
        {
            $canDebit = $this->DebitCash->find('count',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'DebitCash.status !='    => 'Approved',
                    'DebitCash.is_trash'     => 0,
                    'DebitCash.prisoner_id'  => $prisoner_id
                )
            ));
        }
    
        $this->set(array(
            'prisoner_uuid'         => $prisoner_uuid,
            'prisoner_id'=>$prisoner_id, 
            'statusList'=>$statusList,
            'propertyItemList'=>$propertyItemList,
            'currencyList'=>$currencyList,
            'cdate'=>$cdate,
            'debitCurrencyList' => $debitCurrencyList,
            'default_status'    => $default_status,
            'approvalStatusList'    => $approvalStatusList,
            'isDebitEdit'       =>  $isDebitEdit,
            'canDebit'          => $canDebit,
            'canCredit'         => $canCredit,
            'isCreditEdit'      => $isCreditEdit,
            'prisonerKin'       => $prisonerKin,
            'current_usertype_id'  => $this->Session->read('Auth.User.usertype_id')
        )); 

    }
    public function indexAjaxCash(){
        $this->layout   = 'ajax';
        
        $currency_id='';
        $amount='';
        $propertyfrom_date='';
        $propertyto_date='';
        $status_type="";
        
        if(isset($this->params['named']['status_type']) && $this->params['named']['status_type'] != '') {
            $status_type = $this->params['named']['status_type'];
             
             $condition      = array(
                'PhysicalProperty.is_trash'=>0,
                'PhysicalProperty.is_enable'=>1,
                'PhysicalProperty.login_user_id'=>$this->Auth->user('id'),
                'PhysicalProperty.property_type'=>"Cash",
                'PhysicalProperty.id in(select physicalproperty_id from cash_items where status="'.$status_type.'")',

            );
        }
        else{
            $condition      = array(
                'PhysicalProperty.is_trash'=>0,
                'PhysicalProperty.is_enable'=>1,
                'PhysicalProperty.login_user_id'=>$this->Auth->user('id'),
                'PhysicalProperty.property_type'=>"Cash",
                'PhysicalProperty.id in(select physicalproperty_id from cash_items where status="Incoming")',

            );
        }
        

        if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != ''){
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id=$prisonList["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
        }
       
        if(isset($this->params['named']['propertyfrom_date']) && $this->params['named']['propertyfrom_date'] != '') {
            $propertyfrom_date = $this->params['named']['propertyfrom_date'];
             $condition += array('DATE(PhysicalProperty.property_date_time) >='=>date('Y-m-d', strtotime($propertyfrom_date)));
        }
        if(isset($this->params['named']['propertyto_date']) && $this->params['named']['propertyto_date'] != '') {
            $propertyto_date = $this->params['named']['propertyto_date'];
             $condition += array('DATE(PhysicalProperty.property_date_time) <='=>date('Y-m-d', strtotime($propertyto_date)));
        }

        if(isset($this->params['named']['currency_id']) && $this->params['named']['currency_id'] != '') {
            $currency_id = $this->params['named']['currency_id'];
            
        }
        if(isset($this->params['named']['amount']) && $this->params['named']['amount'] != '') {
            $amount = $this->params['named']['amount'];
             
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
            'conditions'    => $condition,
             'group'=>array('PhysicalProperty.id'),
            'order'         => array(
                'PhysicalProperty.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PhysicalProperty');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_uuid'     => $prisoner_uuid,
            'currency_id'     => $currency_id,
            'amount'=>$amount,
            'propertyfrom_date'=>$propertyfrom_date,
            'propertyto_date'=>$propertyto_date,
            'status_type'=>$status_type        

        )); 
    }
    public function transAjaxCash(){
        $this->layout   = 'ajax';
        $condition      = array();

        if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != ''){
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id=$prisonList["Prisoner"]["id"];
            $condition += array('PropertyTransaction.prisoner_id' => $prisoner_id);
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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

        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PropertyTransaction.id' => 'asc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PropertyTransaction');
        $this->set(array(
            'datas'             => $datas,
            'prisoner_uuid'     => $prisoner_uuid,   
            
        )); 
    }
    public function indexAjax(){
        $this->layout   = 'ajax';
       // debug("here");exit;

        $item_id='';
        $bag_no='';
        $propertyfrom_date='';
        $propertyto_date='';
        $status_type="";
        if(isset($this->params['named']['status_type']) && $this->params['named']['status_type'] != '') {
            $status_type = $this->params['named']['status_type'];
             $condition      = array(
                'PhysicalProperty.is_trash'=>0,
                'PhysicalProperty.is_enable'=>1,
                'PhysicalProperty.login_user_id'=>$this->Auth->user('id'),
                'PhysicalProperty.property_type'=>"Physical Property",
                'PhysicalProperty.id in(select physicalproperty_id from physical_property_items where item_status="'.$status_type.'")',

            );
        }
        else{
            $condition      = array(
                'PhysicalProperty.is_trash'=>0,
                'PhysicalProperty.is_enable'=>1,
                'PhysicalProperty.login_user_id'=>$this->Auth->user('id'),
                'PhysicalProperty.property_type'=>"Physical Property",
                'PhysicalProperty.id in(select physicalproperty_id from physical_property_items where item_status in ("Incoming","Supplementary Incoming","Outgoing","Supplementary Outgoing","Destroy"))',

            );
        }

        if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != ''){
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            if(isset($prisonList["Prisoner"]["id"]))
            {
                $prisoner_id=$prisonList["Prisoner"]["id"];
                $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
            }
        }
       if(isset($this->params['named']['propertyfrom_date']) && $this->params['named']['propertyfrom_date'] != '') {
            $propertyfrom_date = $this->params['named']['propertyfrom_date'];
             $condition += array('DATE(PhysicalProperty.property_date_time) >='=>date('Y-m-d', strtotime($propertyfrom_date)));
        }
        if(isset($this->params['named']['propertyto_date']) && $this->params['named']['propertyto_date'] != '') {
            $propertyto_date = $this->params['named']['propertyto_date'];
             $condition += array('DATE(PhysicalProperty.property_date_time) <='=>date('Y-m-d', strtotime($propertyto_date)));
        }
        if(isset($this->params['named']['item_id']) && $this->params['named']['item_id'] != '') {
            $item_id = $this->params['named']['item_id'];
            
        }
        if(isset($this->params['named']['bag_no']) && $this->params['named']['bag_no'] != '') {
            $bag_no = $this->params['named']['bag_no'];
             
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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

        //debug($condition);exit;
    
        $this->paginate = array(
            'conditions'    => $condition,
             'group'=>array('PhysicalProperty.id'),
            'order'         => array(
                'PhysicalProperty.id DESC',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PhysicalProperty');
        //debug($datas);exit;
        $outgoingStatusList = $this->OutgoingStatus->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'OutgoingStatus.id',
                        'OutgoingStatus.name'
                    ),
                    'order'=>array(
                        'OutgoingStatus.id'
                    )
                ));
        ///////witness list//////////////////////////////////
        $witnessList = $this->User->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'    => 1,
                'User.is_trash'     => 0,
                'User.prison_id'    => $this->Session->read('Auth.User.prison_id')
            ),
            'order'=>array(
                'User.name'
            )
        ));
        $this->set(array(
            'datas'         => $datas,
            'prisoner_uuid'     => $prisoner_uuid,   
            'item_id'     => $item_id,
            'bag_no'=>$bag_no,
            'propertyfrom_date'=>$propertyfrom_date,
            'propertyto_date'=>$propertyto_date,
            'status_type'=>$status_type,
            'outgoingStatusList'=>$outgoingStatusList,
            'witnessList'       =>$witnessList    
        )); 
    }
    public function statusindexAjaxCash(){
        $this->layout   = 'ajax';
       
        $prisoner_uuid='';
        $status_type='';
        $condition      = array(
            'PhysicalProperty.is_trash'=>0,
            'PhysicalProperty.is_enable'=>1,
            'PhysicalProperty.login_user_id'=>$this->Auth->user('id'),
            'PhysicalProperty.property_type'=>"Physical Property",
            //'PhysicalProperty.id in(select physicalproperty_id from physical_property_items where item_status="Incoming")',
            //'PhysicalProperty.item_status'=>"Incoming",

        );

        if(isset($this->request->data['prisoner_uuid']) && $this->request->data['prisoner_uuid'] != ''){
            $prisoner_uuid = $this->request->data['prisoner_uuid'];
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id=$prisonList["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
        }
        if(isset($this->request->data['status_type']) && $this->request->data['status_type'] != ''){
            $status_type = $this->request->data['status_type'];
            $condition += array('PhysicalProperty.id in(select physicalproperty_id from cash_items where item_status="'.$status_type.'")');
        }
       if(isset($this->request->data['propertyfrom_date']) && $this->request->data['propertyfrom_date'] != '') {
            $propertyfrom_date = $this->request->data['propertyfrom_date'];
             $condition += array('DATE(PhysicalProperty.property_date_time) >='=>date('Y-m-d', strtotime($propertyfrom_date)));
        }
        if(isset($this->request->data['propertyto_date']) && $this->request->data['propertyto_date'] != '') {
            $propertyto_date = $this->request->data['propertyto_date'];
             $condition += array('DATE(PhysicalProperty.property_date_time) <='=>date('Y-m-d', strtotime($propertyto_date)));
        }
        if(isset($this->request->data['item_id']) && $this->request->data['item_id'] != '') {
            $item_id = $this->request->data['item_id'];
            
        }
        if(isset($this->request->data['bag_no']) && $this->request->data['bag_no'] != '') {
            $bag_no = $this->params['named']['bag_no'];
             
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
            'conditions'    => $condition,
             'group'=>array('PhysicalProperty.id'),
            'order'         => array(
                'PhysicalProperty.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PhysicalProperty');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_uuid'     => $prisoner_uuid,  
            'status_type'=>$status_type, 

            // 'search_category_id'     => $search_category_id,      
            // 'search_uploaded_date'     => $search_uploaded_date,      
            // 'search_tag_id'     => $search_tag_id,      
        ));
    }
    public function statusindexAjax(){
        $this->layout   = 'ajax';
        $prisoner_uuid='';
        $status_type='';
        $condition      = array(
            'PhysicalProperty.is_trash'=>0,
            'PhysicalProperty.is_enable'=>1,
            'PhysicalProperty.login_user_id'=>$this->Auth->user('id'),
            'PhysicalProperty.property_type'=>"Physical Property",
            //'PhysicalProperty.id in(select physicalproperty_id from physical_property_items where item_status="Incoming")',
            //'PhysicalProperty.item_status'=>"Incoming",

        );

        if(isset($this->request->data['prisoner_uuid']) && $this->request->data['prisoner_uuid'] != ''){
            $prisoner_uuid = $this->request->data['prisoner_uuid'];
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id=$prisonList["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
        }
        if(isset($this->request->data['status_type']) && $this->request->data['status_type'] != ''){
            $status_type = $this->request->data['status_type'];
            $condition += array('PhysicalProperty.id in(select physicalproperty_id from physical_property_items where item_status="'.$status_type.'")');
        }
       
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
            'conditions'    => $condition,
             'group'=>array('PhysicalProperty.id'),
            'order'         => array(
                'PhysicalProperty.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PhysicalProperty');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_uuid'     => $prisoner_uuid,  
            'status_type'=>$status_type, 

            // 'search_category_id'     => $search_category_id,      
            // 'search_uploaded_date'     => $search_uploaded_date,      
            // 'search_tag_id'     => $search_tag_id,      
        ));
    }
    public function destroyAjax(){
        $this->layout   = 'ajax';
        
        $ids=$this->request->data['ids'];
        $destroy_date=date('Y-m-d', strtotime($this->request->data['destroy_date']));
        $destroy_cause=$this->request->data['destroy_cause'];
        $destroy_desc=$this->request->data['destroy_desc'];
        $fields=array();
        if(isset($this->data['photo']) && is_array($this->data['photo']))
        {
            if(isset($this->data['photo']['tmp_name']) && $this->data['photo']['tmp_name'] != '' && (int)$this->data['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['photo']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/physicalitems/'.$softName;
                if(move_uploaded_file($this->data['photo']['tmp_name'],$pathName)){
                    unset($this->request->data['photo']);
                    $this->request->data['photo'] = $softName;
                }else{
                    unset($this->request->data['photo']);
                }
            }else{
                unset($this->request->data['photo']);
            }
        }
        else 
        {
            unset($this->request->data['photo']);
        }
        //echo $this->data['destruction_mode'];
        $fields = array(
                    'PhysicalPropertyItem.item_status'      => "'Destroy'",
                    'PhysicalPropertyItem.destroy_status' => "'Draft'",
                    'PhysicalPropertyItem.destroy_date'  => "'$destroy_date'",
                    'PhysicalPropertyItem.destroy_desc'  => "'$destroy_desc'",
                    'PhysicalPropertyItem.destroy_cause'      => "'$destroy_cause'",
                    
                );
        if(isset($this->data['property_witness']) && $this->data['property_witness']!='')
        {
            $fields += array('PhysicalPropertyItem.property_witness'      => "'".implode(',',$this->data['property_witness'])."'");
        }
        if(isset($this->data['destruction_mode']) && $this->data['destruction_mode']!='')
        {
            $fields += array('PhysicalPropertyItem.destruction_mode'      => "'".$this->data['destruction_mode']."'");
        }
        if(isset($this->data['photo']) && $this->data['photo']!='')
        {
            $fields += array('PhysicalPropertyItem.photo'      => "'".$this->data['photo']."'");
        }
        $conds = array(
            'PhysicalPropertyItem.id' => $ids
        );
        //debug($fields);exit;
        $this->PhysicalPropertyItem->updateAll($fields, $conds);
        

        exit;
    }
    public function outgoingAjax(){
        $this->layout   = 'ajax';
        $ids=$this->request->data['ids'];
        $destroy_date=date('Y-m-d', strtotime($this->request->data['destroy_date']));
        $destroy_cause=$this->request->data['destroy_cause'];
        $outgoing_source=$this->request->data['outgoing_source'];
        $outgoing_desc=$this->request->data['outgoing_desc'];
        $status = $this->request->data['status'];
        $recipient_contact = $this->request->data['recipient_contact'];
        $recipient_address = $this->request->data['recipient_address'];
        $prisoner_kin_detail_id = $this->request->data['prisoner_kin_detail_id'];
        $is_biometric_verified = $this->request->data['is_biometric_verified'];
        $quan_total = $this->request->data['quan_total'];
        $quan_outgoing = $this->request->data['quan_outgoing'];
        $quantity_remaining = $this->request->data['quantity_remaining'];
        $outgoing_type = $this->request->data['outgoing_type'];

        $quan_remaining_curr = (int)$quantity_remaining-(int)$quan_outgoing;
        $quan_outgoing_curr = (int)$quan_total-(int)$quan_remaining_curr;
        $propertyItem = $this->PhysicalPropertyItem->findById($ids[0]);
        $this->loadModel('Prisoner');

        $prisoner = $this->Prisoner->findById($propertyItem['PhysicalProperty']['prisoner_id']);

        
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('OFFICERINCHARGE_USERTYPE')){
                $allowed =false;

                if($prisoner['Prisoner']['is_death']){
                    $allowed =true;
                }
                if($prisoner['Prisoner']['is_escaped']){
                    $allowed =false;
                }
                if($allowed){
                 
                        $fields = array(
                            'PhysicalPropertyItem.item_status'      => "'outgoing_type'",
                            'PhysicalPropertyItem.outgoing_desc'      => "'$outgoing_desc'",
                            'PhysicalPropertyItem.destroy_date'  => "'$destroy_date'",
                            'PhysicalPropertyItem.recipient_contact'  => "'$recipient_contact'",
                            'PhysicalPropertyItem.recipient_address'  => "'$recipient_address'",
                            'PhysicalPropertyItem.destroy_cause'      => "'$destroy_cause'",
                            'PhysicalPropertyItem.outgoing_source'      => "'$outgoing_source'",
                            'PhysicalPropertyItem.outgoing_status'      => "'Approved'",
                            'PhysicalPropertyItem.outgoing_status_selected'      => "'$status'",
                            'PhysicalPropertyItem.is_biometric_verified'      => "'$is_biometric_verified'",
                            'PhysicalPropertyItem.quantity_remaining'      => "'$quan_remaining_curr'",
                            'PhysicalPropertyItem.quantity_outgoing'      => "'$quan_outgoing_curr'",
                            'PhysicalPropertyItem.withdraw_by' => $this->Session->read('Auth.User.usertype_id')

                        );

                    $conds = array(
                        'PhysicalPropertyItem.id' => $ids
                    );

                    $this->PhysicalPropertyItem->updateAll($fields, $conds);
                    echo "allowed";
                    exit;
                }else{
                    echo "notAllowed";
                    exit;

                }

        }else{
                $allowed =true;

                if($prisoner['Prisoner']['is_death']){
                    $allowed =false;
                }
                if($prisoner['Prisoner']['is_escaped']){
                    $allowed =false;
                }
                if($allowed){
                     $fields = array(
                            'PhysicalPropertyItem.item_status'      => "'$outgoing_type'",
                            'PhysicalPropertyItem.outgoing_status'      => "'Draft'",
                            'PhysicalPropertyItem.outgoing_desc'      => "'$outgoing_desc'",
                            'PhysicalPropertyItem.destroy_date'  => "'$destroy_date'",
                            'PhysicalPropertyItem.recipient_contact'  => "'$recipient_contact'",
                            'PhysicalPropertyItem.recipient_address'  => "'$recipient_address'",
                            'PhysicalPropertyItem.destroy_cause'      => "'$destroy_cause'",
                            'PhysicalPropertyItem.outgoing_source'      => "'$outgoing_source'",
                            'PhysicalPropertyItem.outgoing_status'      => "'Draft'",
                            'PhysicalPropertyItem.outgoing_status_selected'      => "'$status'",
                            'PhysicalPropertyItem.is_biometric_verified'      => "'$is_biometric_verified'",
                            'PhysicalPropertyItem.quantity_remaining'      => "'$quan_remaining_curr'",
                            'PhysicalPropertyItem.quantity_outgoing'      => "'$quan_outgoing_curr'",
                            'PhysicalPropertyItem.withdraw_by' => $this->Session->read('Auth.User.usertype_id')

                        );
                    $conds = array(
                        'PhysicalPropertyItem.id' => $ids
                    );
                    $this->PhysicalPropertyItem->updateAll($fields, $conds);
                    echo "allowed";
                    exit;
                }else{
                    echo "notAllowed";
                    exit;

                }

        }
        exit;
    }
    public function cashdestroyAjax(){
        $this->layout   = 'ajax';
        $ids=$this->request->data['ids'];
        $destroy_date=date('Y-m-d', strtotime($this->request->data['destroy_date']));
        $destroy_cause=$this->request->data['destroy_cause'];
        $fields = array(
                    'CashItem.item_status'      => "'Destroy'",
                    'CashItem.destroy_date'  => "'$destroy_date'",
                    'CashItem.destroy_cause'      => "'$destroy_cause'",
                );
        $conds = array(
            'CashItem.id' => $ids
        );
        $this->CashItem->updateAll($fields, $conds);
        

        exit;
    }
    public function cashoutgoingAjax(){
        $this->layout   = 'ajax';
        $ids=$this->request->data['ids'];
        $destroy_date=date('Y-m-d', strtotime($this->request->data['destroy_date']));
        $destroy_cause=$this->request->data['destroy_cause'];
        $outgoing_source=$this->request->data['outgoing_source'];
        $fields = array(
                    'CashItem.item_status'      => "'Outgoing'",
                    'CashItem.destroy_date'  => "'$destroy_date'",
                    'CashItem.destroy_cause'      => "'$destroy_cause'",
                    'CashItem.outgoing_source'      => "'$outgoing_source'",
                );
        $conds = array(
            'CashItem.id' => $ids
        );
        $this->CashItem->updateAll($fields, $conds);
        exit;
    }
    
    public function finaldischargeAjax(){
        $this->autoRender = false;
        $destdata  = '';
        if(isset($this->data['dischargedata']) && $this->data['dischargedata'] != '' && isset($this->data['discharge_date']) && $this->data['discharge_date'] != '' && isset($this->data['discharge_cause']) && $this->data['discharge_cause'] != '' && isset($this->data['prisoner_id']) && (int)$this->data['prisoner_id'] != 0){
            $dischargedata       = $this->data['dischargedata'];
            $discharge_date   = date('Y-m-d', strtotime($this->data['discharge_date']));
            $discharge_cause  = $this->data['discharge_cause'];
            
            $prisoner_id    = $this->data['prisoner_id'];
            $arr            = explode(',', $dischargedata);
            $uuidArr = $this->PropertyDestroy->query("select uuid() as code");
            $dataArr['PropertyDischarge']['uuid']             = $uuidArr[0][0]['code'];    
            $dataArr['PropertyDischarge']['prisoner_id']      = $prisoner_id;         
            $dataArr['PropertyDischarge']['discharge_date']     = $discharge_date;
            $dataArr['PropertyDischarge']['discharge_cause']    = $discharge_cause;
            $dataArr['PropertyDischarge']['property_ids']     = $dischargedata;
            $dataArr['PropertyDischarge']['user_id']          = $this->Auth->user('id');
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->PropertyDischarge->save($dataArr)){

                $property_discharge_id = $this->PropertyDischarge->id;
                $fields = array(
                    'Property.final_discharge_status'      => 1,
                    'Property.property_discharge_id'  => $property_discharge_id,
                );
                $conds = array(
                    'Property.id' => $arr
                ); 
                if($this->Property->updateAll($fields, $conds)){
                    if($this->multipleAuditLog(array('PropertyDischarge','Property'), array('property_discharges','properties'), array(0,0), array('Add','Update'), array(json_encode($dataArr),json_encode(array($fields,$arr)))))
                    {
                        $db->commit();
                        echo 1;
                    }
                    else {
                        $db->rollback();
                        echo 1;
                    }
                }else{
                    $db->rollback();
                    echo 0;
                }
            }else{
                $db->rollback();
                echo 0;
            }
        }else{
            echo 0;
        }
    }
    //debit cash 
    function debitCash($data)
    {
        /*DebitCash:id=>prisoner_id=>11debit_date_time=>18-05-2018 15:02:59currency_id=>0balance_amount=>0debit_amount=>reason=>*/
        //debug($data); exit;
        $prisoner_id = $data['DebitCash']['prisoner_id'];
        $currency_id = $data['DebitCash']['currency_id'];
        $debitAmount = $data['DebitCash']['debit_amount'];
        $reason = $data['DebitCash']['reason'];
        $prisoner = '';
        if (isset($data) && is_array($data) && count($data)>0)
        {
            if($prisoner_id != '' && $currency_id != '' && $debitAmount != '' && $reason != '' && $debitAmount != 0 ){
                $prisoner = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.id'     => $prisoner_id,
                ),
            ));
            if(isset($prisoner) && !empty($prisoner))
            {
                $prisoner_uuid = $prisoner['Prisoner']['uuid'];
            }    
            // foreach ($prisoner as $key => $value) {
            //     $prisoner_uuid = $value['uuid'];
            //     break;
            // }
                
            $haslimit = $this->checkTotalBalanceForDebit($prisoner_uuid,$currency_id,$debitAmount);

            if($haslimit)
            {
                 if($this->Session->read('Auth.User.usertype_id') == Configure::read('OFFICERINCHARGE_USERTYPE')){
                    $allowed =false;

                    if($prisoner['Prisoner']['is_death']){
                        $allowed =true;
                    }
                    if($prisoner['Prisoner']['is_escaped']){
                        $allowed =false;
                    }
                    if($allowed){

                        $data["DebitCash"]["balance_amount"] = $data["DebitCash"]["prev_amount"] - $debitAmount ;
                        $data["DebitCash"]["login_user_id"] = $this->Auth->user('id');
                        $data["DebitCash"]["debit_date_time"] = date('Y-m-d H:i:s', strtotime($data["DebitCash"]["debit_date_time"]));
                        $data["DebitCash"]["prison_id"] = $this->Session->read('Auth.User.prison_id');
                        $data["DebitCash"]["status"] = 'Approved';
                        // debug($data);exit;
                        $db = ConnectionManager::getDataSource('default');
                        $db->begin();  
                        if ($this->DebitCash->save($data)) 
                        {
                            if($this->auditLog('DebitCash', 'Properties', 0, 'Add', json_encode($data)))
                            {
                                $db->commit();
                                $this->Session->write('message_type','success');
                                $this->Session->write('message','Cash Debited Successfully !');
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Failed To Debit Cash!');
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Failed To Debit Cash!');
                        }
                    }else{
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Failed To Debit Cash, Not allowed!');
                    }
                }else if($this->Session->read('Auth.User.usertype_id') == Configure::read('RECEPTIONIST_USERTYPE')){
                        $data["DebitCash"]["balance_amount"] = $data["DebitCash"]["prev_amount"] - $debitAmount ;
                        $data["DebitCash"]["login_user_id"] = $this->Auth->user('id');
                        $data["DebitCash"]["debit_date_time"] = date('Y-m-d H:i:s', strtotime($data["DebitCash"]["debit_date_time"]));
                        $data["DebitCash"]["prison_id"] = $this->Session->read('Auth.User.prison_id');
                        // debug($data);exit;
                        $db = ConnectionManager::getDataSource('default');
                        $db->begin();  
                        if ($this->DebitCash->save($data)) 
                        {
                            if($this->auditLog('DebitCash', 'Properties', 0, 'Add', json_encode($data)))
                            {
                                $db->commit();
                                $this->Session->write('message_type','success');
                                $this->Session->write('message','Cash Debited Successfully !');
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Failed To Debit Cash!');
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Failed To Debit Cash!');
                        }
                }else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Failed To Debit Cash, Not allowed!');
                }




                
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Credit Not Availaible!');
            }
          }else{
            $this->Session->write('message_type','error');
            $this->Session->write('message','Please fil all Mandatory fields!');
          }
        }
        else 
        {
            $this->Session->write('message_type','error');
            $this->Session->write('message','Failed To Debit Cash!');
        }
    }
    //credit cash 
    function cashproperty($data){

        

        if (isset($data) && is_array($data) && count($data)>0) 
        {
            $prisoneruuid = $this->Prisoner->field('uuid', array('Prisoner.id'=>$data['PhysicalProperty']['prisoner_id']));
            //debug($data);exit;
            $data["PhysicalProperty"]["property_type"] = "Cash";
            $data["PhysicalProperty"]["property_date_time"] = date('Y-m-d H:i:s', strtotime($data["PhysicalProperty"]["property_date_time"]));
            $data["PhysicalProperty"]["login_user_id"] = $this->Auth->user('id');

            foreach ($data['CashItem'] as $key => $value) {
                $data['CashItem'][$key]['prison_id'] = $this->Session->read('Auth.User.prison_id');
            }
            // debug($data);exit;
            if($data["PhysicalProperty"]["id"]!=""){
                $conds = array(
                    'CashItem.physicalproperty_id'    => $data["PhysicalProperty"]["id"],
                );
                 $this->CashItem->deleteAll($conds,false);
            }
            if ($this->PhysicalProperty->saveAll($data)) {
                $physicalproperty_id = $this->PhysicalProperty->id;
                $this->Session->write('message_type','success');
                $this->Session->write('message','Physically property saved Successfully !');
                 $this->redirect(array('action'=>'index/'.$prisoneruuid.'#credit'));
                // $this->redirect(array('action'=>'index/'.$prisoneruuid.'#credit'));

            } else {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
    }
    function findKey($array, $keySearch)
    {
        foreach ($array as $key => $item) {
            if ($key == $keySearch) {
                echo 'yes, it exists';
                return true;
            }
            else {
                if (is_array($item) && findKey($item, $keySearch)) {
                   return true;
                }
            }
        }

        return false;
    }

    function CashPropertyEdit($id){
        echo $id;
        exit;
    }


    function property($prisoner_uuid)
    {
        $isEdit = 0;
        $prisonerKin = array();
        $prison_id = $this->Session->read('Auth.User.prison_id');

        //debug($this->data);exit;
        if(isset($this->data["PhysicalPropertyEdit"]))
        {
            $isEdit = 1;
            $this->request->data=$this->PhysicalProperty->findById($this->data["PhysicalPropertyEdit"]["id"]);
        }
        else if(isset($this->data["PhysicalPropertyCashEdit"])){
            $isEdit = 1;
            $this->request->data=$this->PhysicalProperty->findById($this->data["PhysicalPropertyCashEdit"]["id"]);
            
        }
        else if (isset($this->data["PhysicalProperty"]) && is_array($this->data["PhysicalProperty"]) && count($this->data["PhysicalProperty"])>0) {
                //debug($this->data);exit;
                if (array_key_exists("property_date_time",$this->data["PhysicalProperty"]) && array_key_exists("source",$this->data["PhysicalProperty"])){  //&& array_key_exists("description",$this->data["PhysicalProperty"])
                $this->request->data["PhysicalProperty"]["property_type"]="Physical Property";

                if(isset($this->data['PhysicalProperty']['property_date_time']) && $this->data['PhysicalProperty']['property_date_time'] != ''){

                    $property_date_time = $this->request->data['PhysicalProperty']['property_date_time'];
                    $property_date_time = date('Y-m-d',strtotime($property_date_time));
                    // $parts = explode('-',$property_date_time);
                    // $parts1 = explode(' ',$parts[2]);
                    // $property_date_time = $parts1[0] . '-' . $parts[0] . '-' . $parts[1].' '.$parts1[1];
                    $this->request->data['PhysicalProperty']['property_date_time'] = $property_date_time;
                }
                //$this->request->data["PhysicalProperty"]["prisoner_id"]=$prisoner_uuid;
                $this->request->data["PhysicalProperty"]["login_user_id"]=$this->Auth->user('id');
                if($this->request->data["PhysicalProperty"]["id"]!=""){
                    $conds = array(
                        'PhysicalPropertyItem.physicalproperty_id'    => $this->request->data["PhysicalProperty"]["id"],
                    );
                     $this->PhysicalPropertyItem->deleteAll($conds,false);
                    
                }
                $data = $this->request->data;
                
                $physicalPropertyItems = $data['PhysicalPropertyItem'];
                foreach ($physicalPropertyItems as $key=>$physicalPropertyItem) {
                    
                        $propertyItem =  $this->Propertyitem->findById($physicalPropertyItem['item_id']);
                       // debug($propertyItem);exit;

                        $propertyStatus = $this->getPropertyTypeNew($physicalPropertyItem['item_id']);
                        
                                 $data['PhysicalPropertyItem'][$key]['prison_id'] = $prison_id;
                                 //echo $propertyStatus;
                        if($propertyStatus !== ''){
                            if(strpos($propertyStatus, 'allowed') !== false){
                               //echo "here";
                            

                                 $data['PhysicalPropertyItem'][$key]['is_provided'] = 'Allowed' ;
                                 if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE') || $this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){
                                    $data['PhysicalPropertyItem'][$key]['item_status'] = 'Incoming' ;
                                 }else{
                                    $data['PhysicalPropertyItem'][$key]['item_status'] = 'Supplementary Incoming' ;
                                 }

                            }else{
                                 $match = explode(',', $propertyStatus);
                               if($match[1] == 'Destroyed'){
                                    $data['PhysicalPropertyItem'][$key]['is_provided'] = 'Prohibited' ;
                                    $data['PhysicalPropertyItem'][$key]['item_status'] = 'Destroy' ;
                                    $data['PhysicalPropertyItem'][$key]['destroy_status'] = 'Draft' ;
                                    $data['PhysicalPropertyItem'][$key]['property_type'] = 'Destroyed';
                               }else{
                                     $data['PhysicalPropertyItem'][$key]['is_provided'] = 'Prohibited' ;
                                    if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE') || $this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){
                                        $data['PhysicalPropertyItem'][$key]['item_status'] = 'Incoming' ;
                                     }else{
                                        $data['PhysicalPropertyItem'][$key]['item_status'] = 'Supplementary Incoming' ;
                                     }
                                    $data['PhysicalPropertyItem'][$key]['property_type'] = 'In Store';
                               }
                            }

                        }else{
                            continue;
                        }

                        
                }
                //debug($data); exit;
                if ($this->PhysicalProperty->saveAll($data)) {
                    $physicalproperty_id = $this->PhysicalProperty->id;
                    $this->Session->write('message_type','success');
                    if($this->request->data["PhysicalProperty"]["id"]==""){
                        $this->Session->write('message','Saved Successfully !');
                    }else{
                        $this->Session->write('message','Updated Successfully !');
                    }
                    
                    $this->redirect(array('action'=>'index/'.$prisoner_uuid.'#physical_property'));
                    // $this->Flash->success(__('The application has been saved.'));
                    // return $this->redirect(array('action' => 'preview',$application_id));
                } else {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                    // $this->Flash->error(__('The application could not be saved. Please, try again.'));
                }
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                }
            }
        
    
        
        $currencyList=array();
        $propertypropertytype=array("In Use"=>"In Use","In Store"=>"In Store");
        if($prisoner_uuid){
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
             
            if(isset($prisonList['Prisoner']['id']) && (int)$prisonList['Prisoner']['id'] != 0){

                $prisoner_id = $prisonList['Prisoner']['id'];

                $prisonerKin = $this->PrisonerKinDetail->find('all',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonerKinDetail.id',
                        'PrisonerKinDetail.first_name',
                        'PrisonerKinDetail.middle_name',
                        'PrisonerKinDetail.last_name',
                    ),
                    'conditions'    => array(
                        'PrisonerKinDetail.is_trash'     => 0,
                         'PrisonerKinDetail.prisoner_id'     => $prisoner_id,
                         'PrisonerKinDetail.status'     => "Approved",

                    ),
                    'order'=>array(
                        'PrisonerKinDetail.id' => 'desc',
                    )
                )); 
            //debug($prisonerKin);
                $propertyItemList = $this->Propertyitem->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Propertyitem.id',
                        'Propertyitem.name',
                    ),
                    'conditions'    => array(
                        'Propertyitem.is_enable'    => 1,
                        'Propertyitem.is_trash'     => 0,
                         /*'Propertyitem.is_prohibited'     => 0,*/

                    ),
                    'order'=>array(
                        'Propertyitem.name'
                    )
                )); 
                //debug($propertyItemList);exit;
                foreach ($propertyItemList as $key => $value) {
                    $item = $this->Propertyitem->findById($key);
                    if($item['Propertyitem']['added_by_recep'] == 1){
                        if($item['Propertyitem']['prison_id'] != $prison_id || $item['Propertyitem']['status'] != 'Approved' ){
                           unset($propertyItemList[$key]);
                        }
                    }
                    //debug($item);exit;
                }
                 $prohibitedpropertyItemList = $this->Propertyitem->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Propertyitem.id',
                        'Propertyitem.name',
                    ),
                    'conditions'    => array(
                        'Propertyitem.is_enable'    => 1,
                        'Propertyitem.is_trash'     => 0,
                         'Propertyitem.is_prohibited'     => 1,

                    ),
                    'order'=>array(
                        'Propertyitem.name'
                    )
                )); 
                //get prisoner currency list 
                $prisonerCurrencyList = $this->CashItem->find('list',array(
                    'recursive' => 1,
                    'fields'        => array(
                        'CashItem.currency_id',
                    ),
                    'conditions'    => array(
                        'CashItem.is_trash'         => 0,
                        'PhysicalProperty.prisoner_id' => $prisoner_id
                    ),
                ));  
                //echo '<pre>'; print_r($prisonerCurrencyList); exit;   
                $debitCurrencyList = '';
                if(count($prisonerCurrencyList) > 0)
                {
                    $prisonerCurrencyList = implode(',',$prisonerCurrencyList);
                    $debitCurrencyList = $this->Currency->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Currency.id',
                            'Currency.name',
                        ),
                        'conditions'    => array(
                            'Currency.is_enable'    => 1,
                            'Currency.is_trash'     => 0,
                            'Currency.id in ('.$prisonerCurrencyList.')'
                        ),
                        'order'=>array(
                            'Currency.name'
                        )
                    )); 
                }     
                $currencyList = $this->Currency->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Currency.id',
                        'Currency.name',
                    ),
                    'conditions'    => array(
                        'Currency.is_enable'    => 1,
                        'Currency.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Currency.name'
                    )
                ));        
                
                $this->set(array(
                    'prisoner_id'           => $prisoner_id,
                    'propertypropertytype'=>$propertypropertytype,
                    'propertyItemList'      => $propertyItemList,
                    'prisoner_uuid'         =>  $prisoner_uuid,
                    'currencyList'=>$currencyList,
                    'prisoner_uuid'=>$prisoner_uuid,
                    'debitCurrencyList' =>   $debitCurrencyList,
                    'isEdit'=>$isEdit,
                    'prisonerKin' => $prisonerKin,
                    'prohibitedpropertyItemList' => $prohibitedpropertyItemList,
                    'current_usertype_id'  => $this->Session->read('Auth.User.usertype_id')
                ));
            }
            else 
            {
                return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
            }
        }
        else 
        {
            return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
        }
         $this->set(array(
                    
                    'isEdit'=>$isEdit,
                ));
        
    }
    //function to get credit cash list 
    function creditAjax()
    {

        $this->layout   = 'ajax';
        $condition      = array();
        //echo "<pre>"; print_r($this->params['data']['CreditSearch']); //exit;
        if(isset($this->params['data']['CreditSearch']['sprisoner_no']) && $this->params['data']['CreditSearch']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['CreditSearch']['sprisoner_no'];
            $prisonerInfo1 = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.prisoner_no'     => $prisonerNo,
                ),
            ));
            $prisoner_id = $prisonerInfo1["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
        }else if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != ''){
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonerInfo = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id = $prisonerInfo["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
        }
        //search condition starts
        if(isset($this->params['data']['CreditSearch']['status']) && $this->params['data']['CreditSearch']['status'] != '' && $this->params['data']['CreditSearch']['status'] != '0')
        {
            $condition += array('CashItem.status' => $this->params['data']['CreditSearch']['status']);
        }
        $Currencies = $this->params['data']['CreditSearch']['currency_id'];
        if(isset($Currencies) && $Currencies!='' && $Currencies != null ){
            $allCurrencyIds =array();
            foreach ($Currencies as  $value) {
                if((int)$value){
                    array_push($allCurrencyIds,(int)$value);
                   
                }
                
            }

             $condition += array('CashItem.currency_id' => $allCurrencyIds);
        }
        
        /*if(isset($this->params['data']['CreditSearch']['currency_id']) && (int)$this->params['data']['CreditSearch']['currency_id'] != '0' )
        {
            $condition += array('CashItem.currency_id' => $this->params['data']['CreditSearch']['currency_id']);
        }*/
        $credit_from_date = '';
        $credit_to_date = '';
        if(isset($this->params['data']['CreditSearch']['credit_from_date']) && $this->params['data']['CreditSearch']['credit_from_date'] != '' )
        {
            $credit_from_date = date('d-m-Y', strtotime($this->params['data']['CreditSearch']['credit_from_date']));
            $credit_from_date1 = $credit_from_date.' 00:00:00';
            $credit_from_date2 = $credit_from_date.' 59:59:59';
        }
        if(isset($this->params['data']['CreditSearch']['credit_to_date']) && $this->params['data']['CreditSearch']['credit_to_date'] != '' )
        {
            $credit_to_date = date('d-m-Y', strtotime($this->params['data']['CreditSearch']['credit_to_date']));
            $credit_to_date1 = $credit_to_date.' 59:59:59';
            $credit_to_date2 = $credit_to_date.' 00:00:00';
        }
        if($credit_from_date != '' && $credit_to_date != '')
        {
            $condition += array(
                'PhysicalProperty.property_date_time >="'.$credit_from_date1.'"',
                'PhysicalProperty.property_date_time <= "'.$credit_to_date1.'"'
            );
        }
        else 
        {
            if($credit_from_date != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$credit_from_date1.'"',
                    'PhysicalProperty.property_date_time <= "'.$credit_from_date2.'"'
                );
            }
            if($credit_to_date != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$credit_to_date1.'"',
                    'PhysicalProperty.property_date_time <= "'.$credit_to_date2.'"'
                );
            }
        }
        //debug($condition); //exit;
        //search condition ends
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
        //echo '<pre>'; print_r($condition); exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'CashItem.id' => 'desc',
            ),
            'limit'         => 10,
        );
        $datas = $this->paginate('CashItem');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_uuid'     => $prisoner_uuid,   
            
        )); 
    }
    //function to get debit cash list 
    function debitAjax1111()
    {
        $this->layout   = 'ajax';
        $condition      = array();
        if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != '')
        {
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonerInfo = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id = $prisonerInfo["Prisoner"]["id"];
            $condition += array('DebitCash.prisoner_id' => $prisoner_id);
        }
        //debug($condition); exit;
        //search condition starts
        if(isset($this->params['data']['DebitSearch']['status']) && $this->params['data']['DebitSearch']['status'] != '' && $this->params['data']['DebitSearch']['status'] != '0')
        {
            $condition += array('DebitCash.status' => $this->params['data']['DebitSearch']['status']);
        }
        $Currencies = $this->params['data']['DebitSearch']['currency_id'];
        // if(isset($Currencies) && $Currencies!='' && $Currencies != null ){
        //     $allCurrencyIds =array();
        //     foreach ($Currencies as  $value) {
        //         if((int)$value){
        //             array_push($allCurrencyIds,(int)$value);
                   
        //         }
                
        //     }

        //      $condition += array('CashItem.currency_id' => $allCurrencyIds);
        // }
        
        /*if(isset($this->params['data']['CreditSearch']['currency_id']) && (int)$this->params['data']['CreditSearch']['currency_id'] != '0' )
        {
            $condition += array('CashItem.currency_id' => $this->params['data']['CreditSearch']['currency_id']);
        }*/
        $debit_from_date = '';
        $debit_to_date = '';
        if(isset($this->params['data']['DebitSearch']['debit_from_date']) && $this->params['data']['DebitSearch']['debit_from_date'] != '' )
        {
            $debit_from_date = date('d-m-Y', strtotime($this->params['data']['DebitSearch']['debit_from_date']));
            $debit_from_date1 = $debit_from_date.' 00:00:00';
            $debit_from_date2 = $debit_from_date.' 59:59:59';
        }
        if(isset($this->params['data']['DebitSearch']['debit_to_date']) && $this->params['data']['DebitSearch']['debit_to_date'] != '' )
        {
            $debit_to_date = date('d-m-Y', strtotime($this->params['data']['DebitSearch']['debit_to_date']));
            $credit_to_date1 = $debit_to_date.' 59:59:59';
            $credit_to_date2 = $debit_to_date.' 00:00:00';
        }
        if($debit_from_date != '' && $debit_to_date != '')
        {
            $condition += array(
                'DebitCash.debit_date_time >="'.$debit_from_date1.'"',
                'DebitCash.debit_date_time <= "'.$debit_to_date1.'"'
            );
        }
        else 
        {
            if($debit_from_date != '')
            {
                $condition += array(
                    'DebitCash.debit_date_time >="'.$debit_from_date1.'"',
                    'DebitCash.debit_date_time <= "'.$debit_from_date2.'"'
                );
            }
            if($debit_to_date != '')
            {
                $condition += array(
                    'DebitCash.debit_date_time >="'.$debit_to_date1.'"',
                    'DebitCash.debit_date_time <= "'.$debit_to_date2.'"'
                );
            }
        }
        //debug($condition); //exit;
        //search condition ends
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
        //echo '<pre>'; print_r($condition); exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'DebitCash.id' => 'desc',
            ),
            'limit'         => 10,
        );
        $datas = $this->paginate('DebitCash');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_uuid'     => $prisoner_uuid,   
            
        )); 
    }
    function debitAjax()
    {
        
        //debug($this->params['data']); exit;
        $modelName = 'DebitCash';
        if(isset($this->params['data']['modelName']))
        {
            $modelName = $this->params['data']['modelName'];
        }
        $this->layout   = 'ajax';
        $condition      = array($modelName.'.is_trash'=>0);
        if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != '')
        {
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonerInfo = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id = $prisonerInfo["Prisoner"]["id"];
            $condition += array('DebitCash.prisoner_id' => $prisoner_id);
        }
        if(isset($this->params['data']['DebitSearch']['status']) && $this->params['data']['DebitSearch']['status'] != '' )
        { 
            $status = $this->params['data']['DebitSearch']['status'];
            $condition      += array($modelName.'.status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array($modelName.'.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array($modelName.'.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if(isset($this->params['data']['DebitSearch']['currency_id']) && $this->params['data']['DebitSearch']['currency_id'] != '' )
        {
            $Currencies = $this->params['data']['DebitSearch']['currency_id'];
            if(isset($Currencies) && $Currencies!='' && $Currencies != null ){
                $allCurrencyIds =array();
                foreach ($Currencies as  $value) {
                    if((int)$value){
                        array_push($allCurrencyIds,(int)$value);
                    }
                }
                $condition += array('DebitCash.currency_id' => $allCurrencyIds);
            }
        }

        if(isset($this->params['data']['DebitSearch']['sprisoner_no']) && $this->params['data']['DebitSearch']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['DebitSearch']['sprisoner_no'];
            $prisonerInfo1 = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.prisoner_no'     => $prisonerNo,
                ),
            ));
            if(isset($prisonerInfo1) && count($prisonerInfo1)>0){

               $prisoner_id = $prisonerInfo1['Prisoner']['id'];
               $condition += array('DebitCash.prisoner_id' => $prisoner_id);
            }
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['DebitSearch']['date_from']) && $this->params['data']['DebitSearch']['date_from'] != '' )
        {
            $date_from_search=$this->params['data']['DebitSearch']['date_from'];
            $date_from = date('Y-m-d', strtotime($this->params['data']['DebitSearch']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['DebitSearch']['date_to']) && $this->params['data']['DebitSearch']['date_to'] != '' )
        {
            $date_to_search=$this->params['data']['DebitSearch']['date_to'];
            $date_to = date('Y-m-d', strtotime($this->params['data']['DebitSearch']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'DebitCash.debit_date_time >="'.$date_from2.'"',
                'DebitCash.debit_date_time <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'DebitCash.debit_date_time >="'.$date_from2.'"',
                    'DebitCash.debit_date_time <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'DebitCash.debit_date_time >="'.$date_to2.'"',
                    'DebitCash.debit_date_time <= "'.$date_to1.'"'
                );
            }
        }
        
        //debug($condition); exit;
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
           // $limit = array('limit'  => 100);
            $limit = 100;
        }
        //debug($limit); exit;
        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                'DebitCash.id desc',
            ),
            'limit'         => $limit
        ); //echo 25; exit;
        $datas = $this->paginate('DebitCash');
        //debug($datas); exit;
        $this->set(array(
            'datas'         => $datas,
            'modelName'        => 'DebitCash'
        ));
    }
    //get total credit balance based on currency id
    function getTotalBalance()
    {
        $this->autoRender = false;
        $prisoner_id = '';
        $currency_id = '';
        $prisoner_id = '';
        $source='';
        $condition   = array();
        $condition2   = array();
        $balance_amount = 0;
        $credit_amount = 0;
        $debit_amount = 0;
        if(isset($this->params['named']['prisoner_uuid']))
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];

        if(isset($this->params['named']['prisoner_uuid']) && $this->params['named']['prisoner_uuid'] != ''){ 
            $prisoner_uuid = $this->params['named']['prisoner_uuid'];
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id = $prisonList["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
            $condition2 += array('DebitCash.prisoner_id' => $prisoner_id);
        }

        if(isset($this->params['named']['currency_id']))
            $currency_id = $this->params['named']['currency_id'];
        if(isset($this->params['named']['source'])){
            $source = $this->params['named']['source'];
        }

        if($currency_id != '' && $prisoner_id != '')
        { 
            $condition += array('CashItem.is_trash' => 0);
            $condition += array('CashItem.status' => 'Approved');
            $condition += array('CashItem.currency_id' => $currency_id);
            if($source != ''){
                $condition += array('CashItem.credit_type' => $source);
            }
            
            $data = $this->CashItem->find('all',array(
                'fields'        => 
                    array('sum(CashItem.amount)   AS total_amount'),
                'conditions'    => $condition,
            )); 
            if(isset($data[0][0]['total_amount']))
            {
                $credit_amount = $data[0][0]['total_amount'];
            }

            $credit_amount += $this->getPrisonerPPCash($prisoner_id);

            $condition2 += array('DebitCash.is_trash' => 0);
            $condition2 += array('DebitCash.status' => 'Approved');
            $condition2 += array('DebitCash.currency_id' => $currency_id);
            $data2 = $this->DebitCash->find('all',array(
                'fields'        => 
                    array('sum(DebitCash.debit_amount)   AS total_amount'),
                'conditions'    => $condition2,
            )); 
            if(isset($data2[0][0]['total_amount']))
            {
                $debit_amount = $data2[0][0]['total_amount'];
            }
            $balance_amount = $credit_amount-$debit_amount;
        }

        return $balance_amount; exit;
    }

     //get total credit balance based on currency id
    function checkTotalBalanceForDebit($prisoner_uuid,$currency_id,$debitAmount)
    {
        
        $prisoner_id = '';
        $condition   = array();
        $balance_amount = 0;
        if(isset($prisoner_uuid)){
            
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $prisoner_uuid,
                ),
            ));
            $prisoner_id = $prisonList["Prisoner"]["id"];
            $condition += array('PhysicalProperty.prisoner_id' => $prisoner_id);
        

        if($currency_id != '' && $prisoner_id != '')
        { 
            $condition += array('CashItem.is_trash' => 0);
            $condition += array('CashItem.status' => 'Approved');
            $condition += array('CashItem.currency_id' => $currency_id);
            $data = $this->CashItem->find('all',array(
                'fields'        => 
                    array('sum(CashItem.amount)   AS total_amount'),
                'conditions'    => $condition,
            )); 
            if(isset($data[0][0]['total_amount']))
            {
                $balance_amount = $data[0][0]['total_amount'];
            }
            }
        }

        if($debitAmount > $balance_amount ){
            return false;
        }else{
            return true;
        }
        
    }
    function creditList()
    { 
        $default_status = ''; $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE'))
                {
                    $status = 'Draft'; 
                }
                $items = $this->request->data['ApprovalProcess'];
                $approveProcess = $this->setApprovalProcess($items, 'CashItem', $status, $remark);
                if($approveProcess == 1)
                {
                    //notification on approval of credit list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE'))
                    {
                        $notification_msg = "Cash credilt list of prisoners added by gatekeeper are pending for final save.";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('RECEPTIONIST_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array(                        
                                "user_id"   => $notifyUser['User']['id'],                        
                                "content"   => $notification_msg,                        
                                "url_link"   => "/properties/creditList",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Cash credilt list of prisoner are pending for review.";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array(                        
                                "user_id"   => $notifyUser['User']['id'],                        
                                "content"   => $notification_msg,                        
                                "url_link"   => "/properties/creditList",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Cash credilt list of prisoner are pending for approve";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array(                        
                                "user_id"   => $notifyUser['User']['id'],                        
                                "content"   => $notification_msg,                        
                                "url_link"   => "/properties/creditList",                    
                            ));
                        }
                    }
                    //notification on approval of credit list --END--
                    
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Cash credit list forwarded Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Failed to forward.');
                }
            }
        }
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }else{
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
                  'Prison.id'=>$this->Session->read('Auth.User.prison_id')
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }

        //get currency list 
        $currencyList = $this->Currency->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Currency.id',
                'Currency.name',
            ),
            'conditions'    => array(
                'Currency.is_enable'    => 1,
                'Currency.is_trash'     => 0,
            ),
            'order'=>array(
                'Currency.name'
            )
        ));  

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,

            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        $datas = $this->paginate('CashItem');
        //echo $default_status; exit;
        $this->set(array(
            'prisonerTypeList'  => $prisonerTypeList,
            'statusList'        => $statusList,
            'default_status'    => $default_status,
            'currencyList'      => $currencyList,
            'prisonList'        => $prisonList
        )); 
    }
    function debitList()
    { 
        $default_status = ''; $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $approveProcess = $this->setApprovalProcess($items, 'DebitCash', $status, $remark);
                if($approveProcess == 1)
                {
                    //notification on approval of debit list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Cash debit list of prisoner are pending for review";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array(                        
                                "user_id"   => $notifyUser['User']['id'],                        
                                "content"   => $notification_msg,                        
                                "url_link"   => "/properties/debitList",                    
                            ));
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Cash debit list of prisoner are pending for approve";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'     => 1,
                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array(                        
                                "user_id"   => $notifyUser['User']['id'],                        
                                "content"   => $notification_msg,                        
                                "url_link"   => "/properties/debitList",                    
                            ));
                        }
                    }
                    //notification on approval of debit list --END--
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Cash debit list '.$status.' Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Cash debit list'.$status.' failed');
                }
            }
        }
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }

        //get currency list 
        $currencyList = $this->Currency->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Currency.id',
                'Currency.name',
            ),
            'conditions'    => array(
                'Currency.is_enable'    => 1,
                'Currency.is_trash'     => 0,
            ),
            'order'=>array(
                'Currency.name'
            )
        ));  

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }else{
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
                  'Prison.id'=>$this->Session->read('Auth.User.prison_id')
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }
        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));

        $datas = $this->paginate('CashItem');
        //echo $default_status; exit;
        $this->set(array(
            'prisonerTypeList'  => $prisonerTypeList,
            'statusList'        => $statusList,
            'default_status'    => $default_status,
            'currencyList'      => $currencyList,
            'prisonList' => $prisonList
        )); 
    }

    //function to get credit cash list 
    function dataAjax()
    {
        //echo '<pre>'; print_r($this->params['data']['dataType']); exit;
        $modelName = 'CashItem';
        if(isset($this->params['data']['modelName']))
        {
            $modelName = $this->params['data']['modelName'];
        }
        $this->layout   = 'ajax';
        $condition      = array($modelName.'.is_trash'=>0);
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            if(isset($this->params['data']['Search']['prison_id']) && $this->params['data']['Search']['prison_id'] != '' )
            { 
                $condition      += array($modelName.'.prison_id'=>$this->params['data']['Search']['prison_id']);
            }
        }else{
                $condition      += array($modelName.'.prison_id'=>$this->Session->read('Auth.User.prison_id'));
        }

        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' )
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array($modelName.'.status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array($modelName.'.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array($modelName.'.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE'))
        {
            $condition      += array($modelName.'.status'=>'G-Draft');
        }
        if(isset($this->params['data']['Search']['currency']) && $this->params['data']['Search']['currency'] != '' )
        {
            $condition      += array($modelName.'.currency_id'=>$this->params['data']['Search']['currency']);
        }
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];
            $prisonerInfo1 = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.prisoner_no'     => $prisonerNo,
                ),
            ));
            if(isset($prisonerInfo1) && count($prisonerInfo1)>0){

               $prisoner_id = $prisonerInfo1['Prisoner']['id'];
               $condition += array($modelName.'.prisoner_id' => $prisoner_id);
            }
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from_search=$this->params['data']['Search']['date_from'];
            $date_from = date('Y-m-d', strtotime($this->params['data']['Search']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to_search=$this->params['data']['Search']['date_to'];
            $date_to = date('Y-m-d', strtotime($this->params['data']['Search']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                $modelName.'.property_date_time >="'.$date_from2.'"',
                $modelName.'.property_date_time <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    $modelName.'.property_date_time >="'.$date_from2.'"',
                    $modelName.'.property_date_time <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    $modelName.'.property_date_time >="'.$date_to2.'"',
                    $modelName.'.property_date_time <= "'.$date_to1.'"'
                );
            }
        }
        
        //print_r($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','creditlist_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','creditlist_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','creditlist_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        //debug($condition);exit;
        $this->paginate = array(
            'recursive' => 2,
            'conditions'    => $condition,
            //'group'  => array('PhysicalProperty.prisoner_id'),
            'order'         => array(
                $modelName.'.id'=>'desc'
            ),
            'limit'         => 20,
        );
        //debug($condition);
        $datas = $this->paginate($modelName);
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'status'        => 'Draft',
            'modelName'        => $modelName
        ));
    }
    //function to get debit cash list 
    function DebitDataAjax()
    {
        $modelName = 'DebitCash';
        if(isset($this->params['data']['modelName']))
        {
            $modelName = $this->params['data']['modelName'];
        }
        $this->layout   = 'ajax';


        $condition      = array($modelName.'.is_trash'=>0);

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            if(isset($this->params['data']['Search']['prison_id']) && $this->params['data']['Search']['prison_id'] != '' )
            { 
                $condition      += array($modelName.'.prison_id'=>$this->params['data']['Search']['prison_id']);
            }
        }else{
                $condition      += array($modelName.'.prison_id'=>$this->Session->read('Auth.User.prison_id'));
        }

        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' )
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array($modelName.'.status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array($modelName.'.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array($modelName.'.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if(isset($this->params['data']['Search']['currency']) && $this->params['data']['Search']['currency'] != '' )
        {
            $condition      += array($modelName.'.currency_id'=>$this->params['data']['Search']['currency']);
        }
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];
            $prisonerInfo1 = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.prisoner_no'     => $prisonerNo,
                ),
            ));
            if(isset($prisonerInfo1) && count($prisonerInfo1)>0){

               $prisoner_id = $prisonerInfo1['Prisoner']['id'];
               $condition += array('DebitCash.prisoner_id' => $prisoner_id);
            }
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from_search=$this->params['data']['Search']['date_from'];
            $date_from = date('Y-m-d', strtotime($this->params['data']['Search']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to_search=$this->params['data']['Search']['date_to'];
            $date_to = date('Y-m-d', strtotime($this->params['data']['Search']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'DebitCash.debit_date_time >="'.$date_from2.'"',
                'DebitCash.debit_date_time <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'DebitCash.debit_date_time >="'.$date_from2.'"',
                    'DebitCash.debit_date_time <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'DebitCash.debit_date_time >="'.$date_to2.'"',
                    'DebitCash.debit_date_time <= "'.$date_to1.'"'
                );
            }
        }
        
        //debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','debitlist_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','debitlist_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','debitlist_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => 2,
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id'=>'desc'
            ),
            'limit'         => 20,
        );
        //debug($condition);
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
        }
        $datas = $this->paginate($modelName);
        $this->set(array(
            'datas'         => $datas,
            'status'        => $default_status,
            'modelName'        => $modelName
        ));
    }
    //function get all physical property list 
    function physicalPropertyList()
    { 
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        
        
        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {//debug($this->data);exit;
                $status = 'Saved'; 
                $remark = '';
                $process="done";
                $fieldss=array();
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if (array_key_exists("type",$this->data["ApprovalProcessForm"]) && array_key_exists("remark",$this->data["ApprovalProcessForm"])){
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                            $process="done";
                        }else{
                            $process="not done";
                        }

                    }
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE'))
                {
                    $status = 'Draft'; 
                }
                if($process=="done"){
                    $items = $this->request->data['ApprovalProcess'];
                           // debug($items);
                     
                        //debug($fieldss);exit;
                    $aproveItems=array();
                    $approveItems = array();
                        foreach ($items as $key => $value) {
                            //echo $value['fid'];
                            //exit;
                            $condss = array(
                                'PhysicalPropertyItem.physicalproperty_id' => $value['fid'],
                            );

                            $propertyItems = $this->PhysicalPropertyItem->find("all", array(
                                'recursive' => -1,
                                "conditions"     => $condss,
                            ));

                            // /debug($propertyItems);
                            foreach ($propertyItems as $key2 => $value2) {
                                
                               $aproveItems += array($key2 => array('fid' => $value2['PhysicalPropertyItem']['id']));
                            }
                           
                           $approveProcess = $this->setApprovalProcess($aproveItems, 'PhysicalPropertyItem', $status, $remark);
                                if($approveProcess == 1)
                                {
                                    //notification on approval of physical property list --START--
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE'))
                                    {
                                        $notification_msg = "Physical property list of prisoners added by gatekeeper are pending for final save.";
                                        $notifyUser = $this->User->find('first',array(
                                            'recursive'     => -1,
                                            'conditions'    => array(
                                                'User.usertype_id'    => Configure::read('RECEPTIONIST_USERTYPE'),
                                                'User.is_trash'     => 0,
                                                'User.is_enable'     => 1,
                                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                            )
                                        ));
                                        if(isset($notifyUser['User']['id']))
                                        {
                                            $this->addNotification(array(                        
                                                "user_id"   => $notifyUser['User']['id'],                        
                                                "content"   => $notification_msg,                        
                                                "url_link"   => "/properties/physicalPropertyList",                    
                                            ));
                                        }
                                    }
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                                    {
                                        $notification_msg = "Physical property list of prisoner are pending for review";
                                        $notifyUser = $this->User->find('first',array(
                                            'recursive'     => -1,
                                            'conditions'    => array(
                                                'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                                'User.is_trash'     => 0,
                                                'User.is_enable'     => 1,
                                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                            )
                                        ));
                                        if(isset($notifyUser['User']['id']))
                                        {
                                            $this->addNotification(array(                        
                                                "user_id"   => $notifyUser['User']['id'],                        
                                                "content"   => $notification_msg,                        
                                                "url_link"   => "/properties/physicalPropertyList",                    
                                            ));
                                        }
                                    }
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                                    {
                                        $notification_msg = "Physical property list of prisoner are pending for approve";
                                        $notifyUser = $this->User->find('first',array(
                                            'recursive'     => -1,
                                            'conditions'    => array(
                                                'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                                'User.is_trash'     => 0,
                                                'User.is_enable'     => 1,
                                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                            )
                                        ));
                                        if(isset($notifyUser['User']['id']))
                                        {
                                            $this->addNotification(array(                        
                                                "user_id"   => $notifyUser['User']['id'],                        
                                                "content"   => $notification_msg,                        
                                                "url_link"   => "/properties/physicalPropertyList",                    
                                            ));
                                        }
                                    }
                                    //notification on approval of physical property list --END--
                                    $this->Session->write('message_type','success');
                                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                                    {
                                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                                            $this->Session->write('message','Physical property Reviewed Successfully !');}
                                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                                            $this->Session->write('message','Physical property Rejected Successfully !');
                                        }
                                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
                                            $this->Session->write('message','Physical property Approved Successfully !');
                                        }
                                    }else{
                                        $this->Session->write('message','Physical property forwarded Successfully !');
                                    }
                                }
                                else 
                                {
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Forwarded failed');
                                }
                        }
                        // /exit;
                    
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Physical property '.$status.' failed');
                }
            }
        }
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }

        //get property item list 
        $propertyItemList = $this->Propertyitem->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Propertyitem.id',
                'Propertyitem.name',
            ),
            'conditions'    => array(
                'Propertyitem.is_enable'    => 1,
                'Propertyitem.is_trash'     => 0,
            ),
            'order'=>array(
                'Propertyitem.name'
            )
        )); 
        ///////witness list//////////////////////////////////
        $witnessList = $this->User->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'    => 1,
                'User.is_trash'     => 0,
                'User.prison_id'    => $this->Session->read('Auth.User.prison_id')
            ),
            'order'=>array(
                'User.name'
            )
        )); 


        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }else{
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
                  'Prison.id'=>$this->Session->read('Auth.User.prison_id')
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        $datas = $this->paginate('CashItem');
        //echo $default_status; exit;

        $this->set(array(
            'prisonerTypeList'  => $prisonerTypeList,
            'sttusListData'=>$statusList,
            'default_status'    => $default_status,
            'default_status'    => $default_status,
            'propertyItemList'  => $propertyItemList,
            'witnessList'      => $witnessList,
            'prisonList' => $prisonList
        )); 
    }
/////////////////////////Manage prisoners property///////////////////////////////////////
    function manageTransactionList()
    { 
         
    }
    function manageTransactionListAjax()
    { 
         //echo '<pre>'; print_r($this); exit;
        $modelName = 'PropertyTransaction';
        $this->layout   = 'ajax';
        $condition      = array(
            'Prisoner.prison_id' =>$this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['propertyfrom_date']) && $this->params['named']['propertyfrom_date'] != '' && isset($this->params['named']['propertyto_date']) && $this->params['named']['propertyto_date'] != '' )
        {
            $condition      += array('date('.$modelName.'.transaction_date) between ? and ?'=>array(date('Y-m-d',strtotime($this->params['named']['propertyfrom_date'])),date('Y-m-d',strtotime($this->params['named']['propertyto_date']))));
        }
        //echo '<pre>'; print_r($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        // debug($condition);

        $datas = $this->$modelName->find('all',array(
            'recursive' => 2,
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'left',
                'conditions'=> array('PropertyTransaction.prisoner_id = Prisoner.id')
                )
            ),
            'conditions'    => $condition,
            'fields'        => array(
                'Currency.id', 
                'Prisoner.prison_id' ,
                'PropertyTransaction.transaction_type',
                'DATE(PropertyTransaction.transaction_date) as transaction_date',
                'SUM(PropertyTransaction.transaction_amount) as transaction_amount'
            ),
            'group' =>array(
                'Currency.id', 
                'Prisoner.prison_id' ,
                'PropertyTransaction.transaction_type',
                'DATE(PropertyTransaction.transaction_date)',
            ),
            'order'         => array(
                'Prisoner.prison_id',
                'Currency.id',
                'DATE(PropertyTransaction.transaction_date) ASC' 
            ),
            'limit'         => 4000,
            'maxLimit'      => 4000
           
        ));
        //$datas = $this->paginate($modelName);
        // debug($datas);exit;
        // debug($limit);
        $newDataArray=array();
        foreach ($datas as $key => $value) {
            // debug($value);
            $newDataArray[$value['Prisoner']['prison_id']][$value['Currency']['id']][$value[0]['transaction_date']][$value['PropertyTransaction']['transaction_type']]=$value[0]['transaction_amount'];
        }
        // debug($newDataArray);
        // exit;
        $this->set(array(
            'newDataArray'         => $newDataArray,
            'modelName'     => $modelName
        ));
    }
    function getOpeningBalance($prisonId,$currencyId,$date){
        $this->autoRender=false;
        $modelName = 'PropertyTransaction';
        $condition      = array();
        if(isset($date) && $date != '' )
        {
            $condition      += array('date('.$modelName.'.transaction_date) <'=>$date);
        }
        if(isset($prisonId) && $prisonId != '' )
        {
            $condition      += array('Prisoner.prison_id'=>$prisonId);
        }
        if(isset($currencyId) && $currencyId != '' )
        {
            $condition      += array('Currency.id'=>$currencyId);
        }
        $datas = $this->$modelName->find('all',array(
            'recursive' => 2,
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'left',
                'conditions'=> array('PropertyTransaction.prisoner_id = Prisoner.id')
                )
            ),
            'conditions'    => $condition,
            'fields'        => array(
                'Currency.id', 
                'Prisoner.prison_id' ,
                'PropertyTransaction.transaction_type',
                //'DATE(PropertyTransaction.transaction_date) as transaction_date',
                'SUM(PropertyTransaction.transaction_amount) as transaction_amount'
            ),
            'group' =>array(
                'Currency.id', 
                'Prisoner.prison_id' ,
                'PropertyTransaction.transaction_type',
                //'DATE(PropertyTransaction.transaction_date)',
            ),
            'order'         => array(
                $modelName.'.id desc',
            ),
           
        ));
        $collAmount=0;
        $debitAmount=0;
        foreach ($datas as $key => $value) {
            if($value['PropertyTransaction']['transaction_type']=='Credit'){
                $collAmount += $value[0]['transaction_amount'];
            }
            if($value['PropertyTransaction']['transaction_type']=='Debit'){
                $debitAmount += $value[0]['transaction_amount'];
            }
        }
        return $collAmount-$debitAmount;
    }

    function getCreditDebit($currencyId){
        $this->autoRender=false;
        $modelName = 'PropertyTransaction';
        $condition      = array();
        
            $condition      = array('date('.$modelName.'.transaction_date) '=>date('Y-m-d'));
        
        if($this->Session->read('Auth.User.prison_id') != '' )
        {
            $condition      += array('Prisoner.prison_id'=>$this->Session->read('Auth.User.prison_id'));
        }
        if(isset($currencyId) && $currencyId != '' )
        {
            $condition      += array('Currency.id'=>$currencyId);
        }
        $datas = $this->$modelName->find('all',array(
            'recursive' => 2,
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'left',
                'conditions'=> array('PropertyTransaction.prisoner_id = Prisoner.id')
                )
            ),
            'conditions'    => $condition,
            'fields'        => array(
                'Currency.id', 
                'Prisoner.prison_id' ,
                'PropertyTransaction.transaction_type',
                //'DATE(PropertyTransaction.transaction_date) as transaction_date',
                'SUM(PropertyTransaction.transaction_amount) as transaction_amount'
            ),
            'group' =>array(
                'Currency.id', 
                'Prisoner.prison_id' ,
                'PropertyTransaction.transaction_type',
                //'DATE(PropertyTransaction.transaction_date)',
            ),
            'order'         => array(
                $modelName.'.id desc',
            ),
           
        ));
        $collAmount=0;
        $debitAmount=0;
        foreach ($datas as $key => $value) {
            if($value['PropertyTransaction']['transaction_type']=='Credit'){
                $collAmount += $value[0]['transaction_amount'];
            }
            if($value['PropertyTransaction']['transaction_type']=='Debit'){
                $debitAmount += $value[0]['transaction_amount'];
            }
        }
        return $collAmount."***".$debitAmount;
    }

    function destroyedPropertyList()
    {
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //debug($this->request->data);exit;
        
        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                $process="done";
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if (array_key_exists("type",$this->data["ApprovalProcessForm"]) && array_key_exists("remark",$this->data["ApprovalProcessForm"])){
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                            $process="done";
                        }else{
                            $process="not done";
                        }

                    }
                }
                if($process=="done"){

                    $items = $this->request->data['ApprovalProcess'];
                    if(isset($this->data['PhysicalPropertyItem']) && is_array($this->data['PhysicalPropertyItem']) && count($this->data['PhysicalPropertyItem'])>0){
                        //debug($this->data['PhysicalPropertyItem']);exit;
                        $fieldss=array();
                        if(isset($this->data['PhysicalPropertyItem']['property_type']) && $this->data['PhysicalPropertyItem']['property_type']=='Destroyed'){
                            if(isset($this->data['PhysicalPropertyItem']['photo']) && is_array($this->data['PhysicalPropertyItem']['photo']))
                            {
                                if(isset($this->data['PhysicalPropertyItem']['photo']['tmp_name']) && $this->data['PhysicalPropertyItem']['photo']['tmp_name'] != '' && (int)$this->data['PhysicalPropertyItem']['photo']['size'] > 0){
                                    $ext        = $this->getExt($this->data['PhysicalPropertyItem']['photo']['name']);
                                    $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                                    $pathName       = './files/physicalitems/'.$softName;
                                    if(move_uploaded_file($this->data['PhysicalPropertyItem']['photo']['tmp_name'],$pathName)){
                                        unset($this->request->data['PhysicalPropertyItem']['photo']);
                                        $this->request->data['PhysicalPropertyItem']['photo'] = $softName;
                                    }else{
                                        unset($this->request->data['PhysicalPropertyItem']['photo']);
                                    }
                                }else{
                                    unset($this->request->data['PhysicalPropertyItem']['photo']);
                                }
                            }
                            else 
                            {
                                unset($this->request->data['PhysicalPropertyItem']['photo']);
                            }
                            $fieldss = array(
                                'PhysicalPropertyItem.is_provided' => "'".$this->data['PhysicalPropertyItem']['is_provided']."'",
                                'PhysicalPropertyItem.destroy_cause' => "'".$this->data['PhysicalPropertyItem']['destroy_cause']."'",
                                
                                'PhysicalPropertyItem.destruction_mode' => "'".$this->data['PhysicalPropertyItem']['destruction_mode']."'",
                                'PhysicalPropertyItem.item_status' => "'Destroy'",
                                'PhysicalPropertyItem.property_witness' => "'".implode(',',$this->data['PhysicalPropertyItem']['property_witness'])."'",
                                'PhysicalPropertyItem.destroy_date' => "'".date('Y-m-d',strtotime($this->data['PhysicalPropertyItem']['destroy_date']))."'",
                                'PhysicalPropertyItem.photo' => "'".$this->data['PhysicalPropertyItem']['photo']."'",
                                'PhysicalPropertyItem.destroy_status' => "'Saved'",
                            );
                        }else{
                            $fieldss = array(
                                'PhysicalPropertyItem.is_provided' => "'".$this->data['PhysicalPropertyItem']['is_provided']."'",
                                
                            );
                            if(isset($this->data['PhysicalPropertyItem']['property_type_prohibited']) && $this->data['PhysicalPropertyItem']['property_type_prohibited']=='In Store'){
                                $fieldss += array(
                                    'PhysicalPropertyItem.property_type' => "'".$this->data['PhysicalPropertyItem']['property_type_prohibited']."'",
                                );
                            }else{
                                $fieldss += array(
                                    'PhysicalPropertyItem.property_type' => "'".$this->data['PhysicalPropertyItem']['property_type']."'",
                                );
                            }
                        }
                        //debug($fieldss);exit;
                        foreach ($items as $key => $value) {
                            $condss = array(
                                'PhysicalPropertyItem.id' => $value['fid'],
                            );
                            $this->PhysicalPropertyItem->updateAll($fieldss, $condss);
                        }
                    }
                    $approveProcess = $this->setApprovalProcessDestroy($items, 'PhysicalPropertyItem', $status, $remark);
                    
                    if($approveProcess == 1)
                    {

                        //notification on approval of destroyed property list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Destroyed property list of prisoner are pending for review";
                            $notifyUser = $this->User->find('first',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                )
                            ));
                            if(isset($notifyUser['User']['id']))
                            {
                                $this->addNotification(array(                        
                                    "user_id"   => $notifyUser['User']['id'],                        
                                    "content"   => $notification_msg,                        
                                    "url_link"   => "/properties/destroyedPropertyList",                    
                                ));
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Destroyed property list of prisoner are pending for approve";
                            $notifyUser = $this->User->find('first',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                )
                            ));
                            if(isset($notifyUser['User']['id']))
                            {
                                $this->addNotification(array(                        
                                    "user_id"   => $notifyUser['User']['id'],                        
                                    "content"   => $notification_msg,                        
                                    "url_link"   => "/properties/destroyedPropertyList",                    
                                ));
                            }
                        }
                        //notification on approval of destroyed property list --END--
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        }
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }

        //get property item list 
        $propertyItemList = $this->Propertyitem->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Propertyitem.id',
                'Propertyitem.name',
            ),
            'conditions'    => array(
                'Propertyitem.is_enable'    => 1,
                'Propertyitem.is_trash'     => 0,
            ),
            'order'=>array(
                'Propertyitem.name'
            )
        )); 

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }else{
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
                  'Prison.id'=>$this->Session->read('Auth.User.prison_id')
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }
        //$datas = $this->paginate('CashItem');
        //echo $default_status; exit;

        
        $this->set(array(
            'prisonerTypeList'  => $prisonerTypeList,
            'sttusListData'=>$statusList,
            'default_status'    => $default_status,
            'propertyItemList'  => $propertyItemList,
            'prisonList'=>$prisonList
        )); 
       }
    
    function destroyedPropertyListAjax()
    {
         //echo '<pre>'; print_r($this); exit;
        $modelName = 'PhysicalPropertyItem';
        $this->layout   = 'ajax';
        $condition      = array();
        $status="";
        $item_id="";
        $bag_no="";
        $date_from_search="";
        $date_to_search="";
        $property_type="";

        $condition = array($modelName.'.is_trash'=>0,$modelName.'.item_status'=>'Destroy');

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            if(isset($this->params['data']['Search']['prison_id']) && $this->params['data']['Search']['prison_id'] != '' )
            { 
                $condition      += array($modelName.'.prison_id'=>$this->params['data']['Search']['prison_id']);
            }
        }else{
                $condition      += array($modelName.'.prison_id'=>$this->Session->read('Auth.User.prison_id'));
            
        }
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' ){ 
            $status = $this->params['data']['Search']['status'];
            $condition      += array($modelName.'.destroy_status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
                $condition      += array($modelName.'.destroy_status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){ 
                $condition      += array($modelName.'.destroy_status not in ("Draft","Saved","Review-Rejected")');
            }
        }

        // debug($condition);
        
        if(isset($this->params['data']['Search']['item_id']) && $this->params['data']['Search']['item_id'] != '' )
        {
            $condition      += array($modelName.'.item_id'=>$this->params['data']['Search']['item_id']);
        }
        if(isset($this->params['data']['Search']['bag_no']) && $this->params['data']['Search']['bag_no'] != '' )
        {
            $condition      += array($modelName.'.bag_no'=>$this->params['data']['Search']['bag_no']);
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from_search=$this->params['data']['Search']['date_from'];
            $date_from = date('Y-m-d', strtotime($this->params['data']['Search']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to_search=$this->params['data']['Search']['date_to'];
            $date_to = date('Y-m-d', strtotime($this->params['data']['Search']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'PhysicalProperty.property_date_time >="'.$date_from2.'"',
                'PhysicalProperty.property_date_time <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$date_from2.'"',
                    'PhysicalProperty.property_date_time <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$date_to2.'"',
                    'PhysicalProperty.property_date_time <= "'.$date_to1.'"'
                );
            }
        }
        if(isset($this->params['data']['Search']['property_type']) && ($this->params['data']['Search']['property_type'] != '' || $this->params['data']['Search']['property_type'] != 0) )
        {
            $property_type=$this->params['data']['Search']['property_type'];
            $condition      += array($modelName.'.property_type'=>$this->params['data']['Search']['property_type']);
        }
        //echo '<pre>'; print_r($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => 2,
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
           
        )+$limit;
        $datas = $this->paginate($modelName);
        //echo '<pre>'; print_r($datas);

        $witnessList = $this->User->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'    => 1,
                'User.is_trash'     => 0,
                'User.prison_id'    => $this->Session->read('Auth.User.prison_id')
            ),
            'order'=>array(
                'User.name'
            )
        )); 


        $this->set(array(
            'datas'         => $datas,
            'status'        => $status,
            'item_id'        => $item_id,
            'bag_no'        => $bag_no,
            'date_from'        => $date_from_search,
            'date_to'        => $date_to_search,
            'property_type'   => $property_type,
            'modelName'        => $modelName,
            'witnessList' => $witnessList

        ));
           }



    function outgoingPropertyList()
    {
            $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        
        
        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                $process="done";
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if (array_key_exists("type",$this->data["ApprovalProcessForm"]) && array_key_exists("remark",$this->data["ApprovalProcessForm"])){
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                            $process="done";
                        }else{
                            $process="not done";
                        }

                    }
                }
                if($process=="done"){
                    $items = $this->request->data['ApprovalProcess'];
                    $approveProcess = $this->setApprovalProcessOutgoing($items, 'PhysicalPropertyItem', $status, $remark);
                    if($approveProcess == 1)
                    {
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        }
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }

        //get property item list 
        $propertyItemList = $this->Propertyitem->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Propertyitem.id',
                'Propertyitem.name',
            ),
            'conditions'    => array(
                'Propertyitem.is_enable'    => 1,
                'Propertyitem.is_trash'     => 0,
            ),
            'order'=>array(
                'Propertyitem.name'
            )
        )); 

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        $datas = $this->paginate('CashItem');
        //echo $default_status; exit;

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }else{
            $prisonList=$this->Prison->find('list',array(
            'fields'=>array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'=>array(
                  'Prison.is_trash'=>0,
                  'Prison.id'=>$this->Session->read('Auth.User.prison_id')
            ), 
            'order'=>array(
                  'Prison.name'
            )
          ));
        }

        $this->set(array(
            'prisonerTypeList'  => $prisonerTypeList,
            'sttusListData'=>$statusList,
            'default_status'    => $default_status,
            'propertyItemList'  => $propertyItemList,
            'prisonList' =>$prisonList
        )); 
       }
    
    function outgoingPropertyListAjax()
    {
         //echo '<pre>'; print_r($this); exit;
        $modelName = 'PhysicalPropertyItem';
        $this->layout   = 'ajax';
        $condition      = array();
        $status="";
        $item_id="";
        $bag_no="";
        $date_from_search="";
        $date_to_search="";
        $property_type="";
        $condition      = array($modelName.'.item_status in ("Outgoing","Supplementary Outgoing")');

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            if(isset($this->params['data']['Search']['prison_id']) && $this->params['data']['Search']['prison_id'] != '' )
            { 
                $condition      += array($modelName.'.prison_id'=>$this->params['data']['Search']['prison_id']);
            }
        }else{
                $condition      += array($modelName.'.prison_id'=>$this->Session->read('Auth.User.prison_id'));
        }
        
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' ){ 
            $status = $this->params['data']['Search']['status'];
            $condition      += array($modelName.'.outgoing_status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
                $condition      += array($modelName.'.outgoing_status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){ 
                $condition      += array($modelName.'.outgoing_status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        /*if(isset($this->params['data']['Search']['item_id']) && $this->params['data']['Search']['item_id'] != '' )
        {
            $condition      += array($modelName.'.item_id'=>$this->params['data']['Search']['item_id']);
        }*/
        if(isset($this->params['data']['Search']['bag_no']) && $this->params['data']['Search']['bag_no'] != '' )
        {
            $condition      += array($modelName.'.bag_no'=>$this->params['data']['Search']['bag_no']);
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from_search=$this->params['data']['Search']['date_from'];
            $date_from = date('Y-m-d', strtotime($this->params['data']['Search']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to_search=$this->params['data']['Search']['date_to'];
            $date_to = date('Y-m-d', strtotime($this->params['data']['Search']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'PhysicalProperty.property_date_time >="'.$date_from2.'"',
                'PhysicalProperty.property_date_time <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$date_from2.'"',
                    'PhysicalProperty.property_date_time <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$date_to2.'"',
                    'PhysicalProperty.property_date_time <= "'.$date_to1.'"'
                );
            }
        }
        if(isset($this->params['data']['Search']['property_type']) && ($this->params['data']['Search']['property_type'] != '' || $this->params['data']['Search']['property_type'] != 0) )
        {
            $property_type=$this->params['data']['Search']['property_type'];
            $condition      += array($modelName.'.property_type'=>$this->params['data']['Search']['property_type']);
        }
        //debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','outgoing_property_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','outgoing_property_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                 $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','outgoing_property_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        // debug($condition);
        $this->paginate = array(
            'recursive' => 2,
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate($modelName);
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas,
            'status'        => $status,
            'item_id'        => $item_id,
            'bag_no'        => $bag_no,
            'date_from'        => $date_from_search,
            'date_to'        => $date_to_search,
            'property_type'   => $property_type,
            'modelName'        => $modelName,
        ));
           }

    //get physical property list
    function physicalPropertyAjax()
    {
        //echo '<pre>'; print_r($this); exit;
        $modelName = 'PhysicalPropertyItem';
        $this->layout   = 'ajax';
        $condition      = array();
        $status="";
        $item_id="";
        $bag_no="";
        $date_from_search="";
        $date_to_search="";
        $property_type="";
        $condition      = array(
            $modelName.'.is_trash'=>0,$modelName .'.item_status != "Destroy"',
        );

        //$condition      = array($modelName.'.is_trash'=>0);
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            if(isset($this->params['data']['Search']['prison_id']) && $this->params['data']['Search']['prison_id'] != '' )
            { 
                $condition      += array($modelName.'.prison_id'=>$this->params['data']['Search']['prison_id']);
            }
        }else{
                $condition      += array($modelName.'.prison_id'=>$this->Session->read('Auth.User.prison_id'));
            
        }
        if(isset($this->params['named']) && is_array($this->params['named']) && count($this->params['named'])>0){
            $this->request->params['data']['Search']=$this->params['named'];
        }
        if(isset($this->params['named']) && is_array($this->params['named']) && count($this->params['named'])>0){
            $this->request->params['data']['Search']=$this->params['named'];
        }
        //debug($this->params);
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' )
        { 
            $status = $this->params['data']['Search']['status'];
            if($status == 'Destroyed'){
                $condition      += array($modelName.'.item_status'=>'Destroy');
            }else{
                $condition      += array($modelName.'.status'=>$status);
            }
            
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array($modelName.'.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array($modelName.'.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE'))
        {
            $condition      += array($modelName.'.status'=>'G-Draft');
        }
        //debug($condition); exit;

        if(isset($this->params['data']['Search']['prisoner_no']) && $this->params['data']['Search']['prisoner_no'] != '' )
        { 
            $prisonerNo = $this->params['data']['Search']['prisoner_no'];

            $prisonerListId = $this->Prisoner->find("list", array(
                "conditions"    => array(
                    "Prisoner.prisoner_no like '%".$prisonerNo."%'",
                ),
                "fields"        => array(
                    "Prisoner.id",
                    "Prisoner.id",
                ),
            ));
            // debug($prisonerListId);exit;
            if(isset($prisonerListId) && count($prisonerListId)>0){
                $condition      += array("PhysicalProperty.prisoner_id IN (".implode(",", $prisonerListId).")");
            }
        }

        if(isset($this->params['data']['Search']['item_id']) && $this->params['data']['Search']['item_id'] != '' )
        {
            $condition      += array($modelName.'.item_id'=>$this->params['data']['Search']['item_id']);
        }
        if(isset($this->params['data']['Search']['bag_no']) && $this->params['data']['Search']['bag_no'] != '' )
        {
            $condition      += array($modelName.'.bag_no'=>$this->params['data']['Search']['bag_no']);
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from_search=$this->params['data']['Search']['date_from'];
            $date_from = date('Y-m-d', strtotime($this->params['data']['Search']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to_search=$this->params['data']['Search']['date_to'];
            $date_to = date('Y-m-d', strtotime($this->params['data']['Search']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'PhysicalProperty.property_date_time >="'.$date_from2.'"',
                'PhysicalProperty.property_date_time <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$date_from2.'"',
                    'PhysicalProperty.property_date_time <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'PhysicalProperty.property_date_time >="'.$date_to2.'"',
                    'PhysicalProperty.property_date_time <= "'.$date_to1.'"'
                );
            }
        }
        if(isset($this->params['data']['Search']['property_type']) && ($this->params['data']['Search']['property_type'] != '' || $this->params['data']['Search']['property_type'] != 0) )
        {
            $property_type=$this->params['data']['Search']['property_type'];
            $condition      += array($modelName.'.property_type'=>$this->params['data']['Search']['property_type']);
        }
        //debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
         ///////witness list//////////////////////////////////
        $witnessList = $this->User->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'    => 1,
                'User.is_trash'     => 0,
                'User.prison_id'    => $this->Session->read('Auth.User.prison_id')
            ),
            'order'=>array(
                'User.name'
            )
        )); 
        $this->paginate = array(
            'recursive' => 2,
            'conditions'    => $condition,
            /*"fields"        => array(
                "PhysicalPropertyItem.*",
                "max(PhysicalPropertyItem.physicalproperty_id) as max_physical_id",
            ),*/
            'order'         => array(
                $modelName.'.id'=>'desc',
            ),
            'group'         => array(
                "PhysicalPropertyItem.physicalproperty_id",
            ),
           /*  "group"        => array(
                "PhysicalPropertyItem.physicalproperty_id",
            ),
           */
        )+$limit;
        $datas = $this->paginate($modelName);
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas,
            'status'        => $status,
            'item_id'        => $item_id,
            'bag_no'        => $bag_no,
            'date_from'        => $date_from_search,
            'date_to'        => $date_to_search,
            'property_type'   => $property_type,
            'modelName'        => $modelName,
            'witnessList'      => $witnessList,
            'current_usertype_id' => $this->Session->read('Auth.User.usertype_id')
        ));
    }


     function prohibitedProperties()
    {
         $propertyItemsList = $this->Propertyitem->find('all',array(
                    'recursive'     => -1,
                    'order'=>array(
                        'Propertyitem.id'
                    )
                ));



          $this->set(array(
            'propertyItemsList' => $propertyItemsList,
            
        ));

    }
     function saveProhibitedProperty()
    {
        //debug($this->request->data);
        $properties = $this->request->data;

        $allpropertyItems = $this->Propertyitem->find('all',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Propertyitem.id',
                        'Propertyitem.is_prohibited',
                        'Propertyitem.name',

                    )
                
                ));
         foreach ($allpropertyItems as $key) {
            $item = $this->Propertyitem->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Propertyitem.id',
                        'Propertyitem.is_prohibited',
                        'Propertyitem.name',

                    ),
                    'conditions'    => array(
                        'Propertyitem.id' =>$key['Propertyitem']['id'],
                        
                    )
                
                ));
                $props['Propertyitem']['id'] = $item['Propertyitem']['id'];
                $props['Propertyitem']['is_prohibited'] = 0 ;
                $this->Propertyitem->save($props);
         }
        foreach ($properties as $key =>$value) {
             $id = str_replace("property_", "", $key);
             //echo $id;

             $propertyItem = $this->Propertyitem->find('first',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Propertyitem.id',
                        'Propertyitem.is_prohibited',
                        'Propertyitem.name',

                    ),
                    'conditions'    => array(
                        'Propertyitem.id' =>$id,
                        
                    )
                
                ));
                $props['Propertyitem']['id'] = $id;
                $props['Propertyitem']['is_prohibited'] = 1 ;
                $this->Propertyitem->save($props);

             }
              $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
         exit;

    }

 
     public function getPropertyRow(){
        $this->layout = 'ajax';
       
        $this->loadModel('PhysicalPropertyItem'); 
        $this->loadModel('PhysicalProperty'); 
        

        $propertyId = $this->data['propertyId'];

        $property = $this->PhysicalProperty->find('first', array(
            'recursive'     => 2,
            'conditions'    => array(
                'PhysicalProperty.id'      => $propertyId
            ),
        ));
        //debug($visitor['VisitorPrisonerCashItem']);

          $propertyItemList = $this->PhysicalPropertyItem->find('all',array(
                'recursive'     => 2,
                'conditions'    => array(
                    'PhysicalPropertyItem.physicalproperty_id'     => $propertyId,

                )
            ));
      //debug($propertyItemList);exit;
        $data ='';
        $count =1;
      
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Sr. No.</th><th>Name</th><th>Quantity</th><th>Bag No</th><th>Property Type</th></tr></thead><tbody>';

        foreach ($propertyItemList as $item) {
                       
                        $data .= '<tr>';
                        $data .= '<td>'.$count.'</td>';
                        $data .= '<td>'.$item['Propertyitem']["name"].'</td>';
                        $data .= '<td>'.$item['PhysicalPropertyItem']["quantity"].'</td>';
                        $data .= '<td>'.$item['PhysicalPropertyItem']["bag_no"].'</td>';
                        $data .= '<td>'.$item['PhysicalPropertyItem']["property_type"].'</td>';

                        $count++;
                    }
                        $data .= '</tbody></table>';

      
        echo $data;
        exit; 
    }
    
}