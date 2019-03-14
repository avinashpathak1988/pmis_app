<?php
App::uses('AppController', 'Controller');
class VisitorsController   extends AppController {
	public $layout='table';
   // public $uses=array('VisitorPrisonerItem');
	public function index() {
        $menuId = $this->getMenuId("/visitors");
        $moduleId = $this->getModuleId("visitor");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

		$this->loadModel('Visitor'); 

        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Verified'; 
                $remark = '';
                
                $items = $this->request->data['ApprovalProcess'];
                //======================================================================
                if(isset($items) && count($items) > 0)
                {
                    $prison_id = $this->Session->read('Auth.User.prison_id');
                    $login_user_id = $this->Session->read('Auth.User.id');
                    $i = 0;
                    $data = array(); $idList = '';
                    $remark = '';
                    $model = "Visitor";
                    foreach($items as $item)
                    {
                        if($idList != '')
                        {
                            $idList .= ',';
                        }
                        $idList .= $item['fid'];
                        $data[$i]['ApprovalProcess'] = $item;
                        $data[$i]['ApprovalProcess']['prison_id'] = $prison_id;
                        $data[$i]['ApprovalProcess']['model_name'] = $model;
                        $data[$i]['ApprovalProcess']['status'] = $status;
                        $data[$i]['ApprovalProcess']['remark'] = $remark;
                        $data[$i]['ApprovalProcess']['user_id'] = $login_user_id;
                        $i++;
                    }
                    if(count($data) > 0)
                    {
                        $fields = array(
                            $model.'.verify_status'    => "'".$status."'",
                        );
                        $conds = array(
                            $model.'.id in ('.$idList.')',
                        );
                        $db = ConnectionManager::getDataSource('default');
                        $db->begin();
                        if($this->ApprovalProcess->saveAll($data))
                        {
                            if($this->auditLog('ApprovalProcess', 'approval_processes', 0, 'Add', json_encode($data)))
                            {
                                if($this->$model->updateAll($fields, $conds))
                                {
                                    //save to cash property transaction incase credit & debit cash 
                                    $db->commit();
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Verified Successfully !');
                                }
                                else 
                                {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','saving failed');
                                }
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','saving failed');
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    }
                }
                //========================================================================               
                $this->redirect('index');
            }
        }
        if(isset($this->data['VisitorDelete']['id']) && (int)$this->data['VisitorDelete']['id'] != 0){
        	
            $menuId = $this->getMenuId("/visitors");
            $moduleId = $this->getModuleId("visitor");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }
            $this->Visitor->id=$this->data['VisitorDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Visitor->saveField('is_trash',1))
            {
                if($this->auditLog('Visitor', 'visitors', $this->data['VisitorDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete failed');
            }
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$this->Session->read('Auth.User.prison_id').")"
            )
            
        ));
        $allowUpdate = false;
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $allowUpdate = true;
        }elseif ($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')) {
            $gatekeeperData = $this->User->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    'User.is_enable'      => 1,
                    "prison_id REGEXP CONCAT('(^|,)(', REPLACE(".$this->Session->read('Auth.User.prison_id').", ',', '|'), ')(,|$)')",
                ),
            ));
            if($gatekeeperData == 0){
                $allowUpdate = true;
            }
        }else{
            $allowUpdate = false;
        }
        
        $this->set(array(
            'prisonList'         => $prisonList,
            'allowUpdate'        => $allowUpdate,

            //'gatekeeperData'     => $gatekeeperData
        )); 
    }

    public function blacklistVisitor(){
        $this->layout = 'ajax';
        $this->loadModel('Visitor');
        $this->loadModel('BlacklistedVisitor');
        $visitor_id = $this->request->data['visitor_id'];
        $reason = $this->request->data['reason'];
        $result = "failure";
        $visitor = $this->Visitor->find('first', array(
            'recursive'     => 2,
            'conditions'    => array(
                'Visitor.id'      => $this->request->data['visitor_id']
            ),
        ));
        //debug($visitor);
        if(isset($visitor['VisitorName'])){
            foreach ($visitor['VisitorName'] as $value) {
                //debug($value);
                $blacklist =array();
                $blacklist['BlacklistedVisitor']['prison_id'] = $visitor['Visitor']['prison_id'];
                if(isset($value['Iddetail'])){
                     $blacklist['BlacklistedVisitor']['visitor_id_type'] = $value['nat_id_type'];
                     $blacklist['BlacklistedVisitor']['visitor_id_no'] = $value['nat_id'];
                     $blacklist['BlacklistedVisitor']['reason'] = $reason;

                     if($this->BlacklistedVisitor->saveAll($blacklist)){
                        $updateFields = array(
                            'Visitor.blacklisted' => 1
                        );
                        $updateConds = array(
                            'Visitor.id'      => $visitor_id,
                        );
                        $this->Visitor->updateAll($updateFields, $updateConds);
                        $result = "success";
                    }else{
                        $result = "failure";
                    }
                     
                }else{
                    $result = "failure";
                }
            }
        }else{
            $result = "failure";
        }
        
        echo $result;
        exit;
     }
   public function returnVisitorItem(){
        $this->layout = 'ajax';
        $this->loadModel('VisitorItem');
        $visitor = $this->Visitor->find('first', array(
            //'recursive'     => -1,
            'conditions'    => array(
                'Visitor.id'      => $this->data['ReturnVIsitorItem']['visitor_id']
            ),
        ));
        $collected = 0;
         foreach ($this->data['ReturnVisitorItem'] as $returnItem) {
            if(isset($returnItem['recieved_item_check'])){
                if($returnItem['recieved_item_check'] == 'on'){
                $visitorItemId = $returnItem['id'];

                $visitorItem = $this->VisitorItem->findById($visitorItemId);
                //debug($visitorPrisonerItem);exit;
                    if($visitorItem['VisitorItem']['is_collected'] == false){

                        $collected += 1;
                        $visitorItem['VisitorItem']['is_collected']=1;
                        $this->VisitorItem->saveAll($visitorItem);
                    }
                } // recieved item check end
            }
            
        }
                    echo "success";

                    $this->Session->write('message_type','success');
                    $this->Session->write('message',$collected . 'items Returned.');

        exit;
    }
    public function recieveItemCash(){
        $this->layout = 'ajax';
        $this->loadModel('CashItem');
        $this->loadModel('Prisoner'); 
        $this->loadModel('VisitorPrisonerItem'); 
        $this->loadModel('PhysicalProperty');
        $this->loadModel('PhysicalPropertyItem');
        $this->loadModel('VisitorPrisonerCashItem');

        $success ='';

        $visitor = $this->Visitor->find('first', array(
            //'recursive'     => -1,
            'conditions'    => array(
                'Visitor.id'      => $this->data['RecieveItemCash']['visitor_id']
            ),
        ));
       //debug($this->data['RecievePrisonerItem']);exit;

        $prisoner = $this->Prisoner->find('first', array(
            //'recursive'     => -1,
            'conditions'    => array(
                'Prisoner.prisoner_no'      => $visitor['Visitor']['prisoner_no']
            ),
        ));
        /*aakash recieve prisoner item*/

        $physicalProperty=array();
        $notifyUser = $this->User->find('first',array(
                                            'recursive'     => -1,
                                            'conditions'    => array(
                                                'User.usertype_id'    => Configure::read('RECEPTIONIST_USERTYPE'),
                                                'User.is_trash'     => 0,
                                                'User.is_enable'     => 1,
                                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                            )
                                        ));
            
        foreach ($this->data['RecievePrisonerItem'] as $recievedItem) {
            if(isset($recievedItem['recieved_item_check'])){
                if($recievedItem['recieved_item_check'] == 'on'){
                $visitorPrisonerPropertyId = $recievedItem['id'];

                $visitorPrisonerItem = $this->VisitorPrisonerItem->find('first', array(
                        'conditions'    => array(
                            'VisitorPrisonerItem.id'      => $visitorPrisonerPropertyId
                        ),
                ));
                //debug($visitorPrisonerItem);exit;
                if($visitorPrisonerItem['VisitorPrisonerItem']['is_collected'] == false){

                        $physicalPropertyNew =array();
                        $physicalPropertyNew['PhysicalProperty']['property_date_time']=date('Y-m-d H:i:s');
                        $physicalPropertyNew['PhysicalProperty']['login_user_id']=$notifyUser['User']['id'];
                        $physicalPropertyNew['PhysicalProperty']['description']=$visitor['Visitor']['category'];
                        $physicalPropertyNew['PhysicalProperty']['visitor_id']=$visitor['Visitor']['id'];
                        $physicalPropertyNew['PhysicalProperty']['source']='Visitor';
                        $physicalPropertyNew['PhysicalProperty']['prisoner_id']=$prisoner['Prisoner']['id'];
                        $physicalPropertyNew['PhysicalProperty']['property_type']='Physical Property';
                        $this->PhysicalProperty->saveAll($physicalPropertyNew['PhysicalProperty']);
                    

                    $physicalPropertyItem['PhysicalPropertyItem']['physicalproperty_id']=$this->PhysicalProperty->id;
                    $physicalPropertyItem['PhysicalPropertyItem']['item_id']=$visitorPrisonerItem['VisitorPrisonerItem']['item_type'];
                    $physicalPropertyItem['PhysicalPropertyItem']['quantity']=$visitorPrisonerItem['VisitorPrisonerItem']['quantity'];

                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =$visitorPrisonerItem['VisitorPrisonerItem']['property_type'];
                   $this->loadModel('Propertyitem');
                   $this->loadModel('PhysicalPropertyItem');
                   $this->loadModel('VisitorPrisonerItem');

                   
                   $propertyItem =  $this->Propertyitem->findById($visitorPrisonerItem['VisitorPrisonerItem']['item_type']);
                    $physicalPropertyItem['PhysicalPropertyItem']['prison_id'] =$visitor['Visitor']['prison_id'];

                       // debug($propertyItem);exit;
                        if(isset($propertyItem['Propertyitem']['is_allowed'])){

                            if($propertyItem['Propertyitem']['is_allowed'] == 1){
                                 $physicalPropertyItem['PhysicalPropertyItem']['is_provided'] =  'Allowed';
                                    $physicalPropertyItem['PhysicalPropertyItem']['item_status'] =  'Supplementary Incoming';
                                    $physicalPropertyItem['PhysicalPropertyItem']['status'] =  'Draft';
                                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =  'In Use';
                            }else if(isset($propertyItem['Propertyitem']['is_prohibited']) && $propertyItem['Propertyitem']['is_prohibited'] == 1){
                               if($propertyItem['Propertyitem']['property_type_prohibited'] == 'Destroyed'){
                                    $physicalPropertyItem['PhysicalPropertyItem']['is_provided'] =  'Prohibited';
                                    $physicalPropertyItem['PhysicalPropertyItem']['item_status'] =  'Destroy';
                                    $physicalPropertyItem['PhysicalPropertyItem']['destroy_status'] =  'Draft';
                                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =  'Destroyed';

                               }else{
                                $physicalPropertyItem['PhysicalPropertyItem']['is_provided'] =  'Prohibited';
                                    $physicalPropertyItem['PhysicalPropertyItem']['item_status'] =  'Supplementary Incoming';
                                    $physicalPropertyItem['PhysicalPropertyItem']['status'] =  'Draft';
                                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =  'In Store';
                                    
                               }
                            }

                        }else{
                            continue;
                        }
                    
                    $physicalPropertyItem['PhysicalPropertyItem']['bag_no']='0';
                    // /debug($physicalPropertyItem);exit;
                    if($this->PhysicalPropertyItem->saveAll($physicalPropertyItem['PhysicalPropertyItem'])){
                        $visitorPrisonerItem['VisitorPrisonerItem']['is_collected']=1;
                        $this->VisitorPrisonerItem->saveAll($visitorPrisonerItem['VisitorPrisonerItem']);
                        
                        $this->addNotification(array("user_id"=>$notifyUser['User']['id'],"content"=>"Visitor Physical Property recieved by Gatekeeper","url_link"=>"properties/physicalPropertyList"));
                                        $success = "success";

                    }else{
                                        $success = "failed";

                    }

                }
            } // recieved item check end
            }
            
        }
        /*aakash recieve prisoner item ends*/
        //Recieve cash
        //debug($this->data['RecievePrisonerCashItem']);exit;
        if(isset($this->data['RecievePrisonerCashItem'])){
                 foreach ($this->data['RecievePrisonerCashItem'] as $recievedItem) {
                    if(isset($recievedItem['recieved_item_check'])){
                        if($recievedItem['recieved_item_check'] == 'on'){
                        $visitorPrisonerCashId = $recievedItem['id'];

                        $visitorPrisonerItem = $this->VisitorPrisonerCashItem->find('first', array(
                                'conditions'    => array(
                                    'VisitorPrisonerCashItem.id'      => $visitorPrisonerCashId
                                ),
                        ));
                        //debug($visitorPrisonerItem);exit;
                        if($visitorPrisonerItem['VisitorPrisonerCashItem']['is_collected'] == false){

                                $physicalPropertyNew =array();
                                $physicalPropertyNew['PhysicalProperty']['property_date_time']=date('Y-m-d H:i:s');
                                $physicalPropertyNew['PhysicalProperty']['login_user_id']=$notifyUser['User']['id'];
                                $physicalPropertyNew['PhysicalProperty']['description']=$visitor['Visitor']['category'];
                                $physicalPropertyNew['PhysicalProperty']['visitor_id']=$visitor['Visitor']['id'];
                                $physicalPropertyNew['PhysicalProperty']['source']='Visitor';
                                $physicalPropertyNew['PhysicalProperty']['prisoner_id']=$prisoner['Prisoner']['id'];
                                $physicalPropertyNew['PhysicalProperty']['property_type']='Cash';
                                ;
                            if($this->PhysicalProperty->saveAll($physicalPropertyNew['PhysicalProperty'])){
                                            $cashItem['CashItem']['physicalproperty_id']=$this->PhysicalProperty->id;
                                            $cashItem['CashItem']['amount']=$visitorPrisonerItem['VisitorPrisonerCashItem']['pp_amount'];
                                            $cashItem['CashItem']['currency_id']=$visitorPrisonerItem['VisitorPrisonerCashItem']['pp_cash'];
                                            $cashItem['CashItem']['prison_id']=$visitor['Visitor']['prison_id'];
                                            $cashItem['CashItem']['status']='Draft';

                                            if($this->CashItem->saveAll($cashItem)){
                                                $visitorPrisonerItem['VisitorPrisonerCashItem']['is_collected']=1;
                                                $this->VisitorPrisonerCashItem->save($visitorPrisonerItem);
                                                
                                                $this->addNotification(array("user_id"=>$notifyUser['User']['id'],"content"=>"Visitor cash recieved by Gatekeeper","url_link"=>"properties/creditList"));
                                                $success = "success";
                                            }else{
                                                $success = 'failed';
                                            }

                                        }

                            

                        }
                    } // recieved item check end
                    }
                    
                }
        }
           
        //REcieve CAsh ends
       
        echo $success;
        //debug($prisoner);
        exit;
    }

    public function receipt($id){
        $visitor = $this->Visitor->find('first', array(
            'recursive'     => 2,
            'conditions'    => array(
                'Visitor.id'      => $id
            ),
        ));
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='PRINT'){
              
                $this->layout='print';
            }
            
        }
         $this->set(array(
            'visitor'         => $visitor,

            //'gatekeeperData'     => $gatekeeperData
        )); 
    }

     public function getVisitorItem(){
        $this->layout = 'ajax';
        $this->loadModel('VisitorPrisonerItem'); 
        $this->loadModel('Visitor'); 
        $this->loadModel('Propertyitem'); 
        $this->loadModel('VisitorPrisonerCashItem'); 

        


        $visitorId = $this->data['visitorId'];

        $visitor = $this->Visitor->find('first', array(
            'recursive'     => 2,
            'conditions'    => array(
                'Visitor.id'      => $visitorId
            ),
        ));


        //debug($visitor);

        $data ='';
        $data .= '<div><h5>Visitor Details</h5></div>';
        $data .= '<table class="table table-bordered table-striped table-responsive"><tbody>';
        $data .=  '<tr><td>Category</td><td> ' . $visitor['Visitor']['category'].'</td></tr>';
        $data .=  '<tr><td>Sub Category</td><td> ' . $visitor['Visitor']['subcategory'].'</td></tr>';
        $data .=  '<tr><td>Other Category (if any)</td><td> ' . $visitor['Visitor']['others_category'].'</td></tr>';
        $data .=  '<tr><td>Name</td><td> ' . $visitor['Visitor']['name'].'</td></tr>';
        $data .=  '<tr><td>Gate Keeper</td><td> ' . $visitor['Visitor']['gate_keeper'].'</td></tr>';
        $data .=  '<tr><td>Contact</td><td> ' . $visitor['Visitor']['contact_no'].'</td></tr>';
        $data .=  '<tr><td>Main Gate in time</td><td> ' . $visitor['Visitor']['main_gate_in_time'].'</td></tr>';
        


        $data .= '</tbody></table>';

        $count =0;
        $allCollected ='true';
        $data .= '<div><h5>Visitor Items</h5></div>';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Item Name</th><th>Quantity</th><th>Collected</th></tr></thead><tbody>';

        foreach ($visitor['VisitorItem'] as $visitorItemDetail) {
                        
                        //debug($propertyItemList);
                       
                        $data .= '<tr>';

                        $data .= '<td>';
                        $data .= '<input type="hidden" name="data[ReturnVisitorItem]['.$count .'][id] " value="'. $visitorItemDetail['id'] .'">';
                        $data .= $visitorItemDetail['item'].'</td>';

                        $data .= '<td>'. $visitorItemDetail['quantity'] .'</td>';
                        if($visitorItemDetail['is_collected'] == true){

                            $data .='<td><input type="checkbox" style="margin-left:2px;" name="data[ReturnVisitorItem]['.$count.'][recieved_item_check]" checked="checked"> <span style="color:green">Already Returned</span></td>';
                        }else{
                            $allCollected ='false';
                            $data .='<td><input type="checkbox" style="margin-left:2px;" name="data[ReturnVisitorItem]['.$count.'][recieved_item_check]" ><span style="color:red">Not yet Returned </span></td>';
                        }
                        $data .='</tr>';
                        $count++;
                    }
                        $data .= '</tbody></table>';

        $data .= '<div style="display:none;" id="allCollectedResponse">'.$allCollected.'</div>';
        echo $data;
        exit; 
    }
    public function getVisitorRow(){
        $this->layout = 'ajax';
        $this->loadModel('VisitorPrisonerItem'); 
        $this->loadModel('Visitor'); 
        $this->loadModel('Propertyitem'); 
        $this->loadModel('VisitorPrisonerCashItem'); 

        

        $visitorId = $this->data['visitorId'];

        $visitor = $this->Visitor->find('first', array(
            'recursive'     => 2,
            'conditions'    => array(
                'Visitor.id'      => $visitorId
            ),
        ));
        //debug($visitor['VisitorPrisonerCashItem']);

          $propertyItemList = $this->Propertyitem->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,

                )
            ));
        
        $data ='';
        $count =0;
        $allCollected ='true';
        $data .= '<div><h5 style="display:inline-block;"">Prisoner Property Items</h5><button style="display:inline-block;margin-left:10px;" class="add_more_property btn btn-success"><span class="icon icon-plus"></span></button></div>';
        $data .= '<div id="add_item_form" style="display:none">';
         $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Item Name</th><th>Quantity</th><th>Action</th></tr></thead><tbody>';
         $data .= '<tr><td><select  name="newItemName" id="newItemName">';
         foreach ($propertyItemList as $pitem) {
            $data .= '<option value="'.$pitem["Propertyitem"]["id"].'">'.$pitem["Propertyitem"]["name"].'</option>';

         }
         $data .= '</select></td><td><input type="number" name="newItemQuantity" id="newItemQuantity"></td><td><button class="btn btn-success insert_property_item">add</button></td></tr>';
         $data .= '</tbody></table></div>';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Item Name</th><th>Quantity</th><th>Collected</th></tr></thead><tbody>';

        foreach ($visitor['VisitorPrisonerItem'] as $prisonerItemDetail) {
                        $itemTypeId = $prisonerItemDetail['item_type'];
                        $propertyItemName='';
                        //debug($propertyItemList);
                        foreach ($propertyItemList as $propertyItem) {
                          //debug($propertyItem);
                            if($propertyItem['Propertyitem']['id'] ==$itemTypeId ){
                                $propertyItemName = $propertyItem['Propertyitem']['name'];
                            }
                        }
                        $data .= '<tr>';

                        $data .= '<td>';
                        $data .= '<input type="hidden" name="data[RecievePrisonerItem]['.$count .'][id] " value="'. $prisonerItemDetail['id'] .'">';
                        $data .= $propertyItemName .'</td>';

                        $data .= '<td>'. $prisonerItemDetail['quantity'] .'</td>';
                        if($prisonerItemDetail['is_collected'] == true){

                            $data .='<td><input type="checkbox" style="margin-left:2px;" name="data[RecievePrisonerItem]['.$count.'][recieved_item_check]" checked="checked"> <span style="color:green">Already Recieved</span></td>';
                        }else{
                            $allCollected ='false';
                            $data .='<td><input type="checkbox" style="margin-left:2px;" name="data[RecievePrisonerItem]['.$count.'][recieved_item_check]" ><span style="color:red">Not yet Received </span></td>';
                        }
                        $data .='</tr>';
                        $count++;
                    }
                        $data .= '</tbody></table>';
                    $this->loadModel('Currency');
        $currencyList = $this->Currency->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'Currency.is_enable'    => 1,
                        'Currency.is_trash'     => 0,
                    ),
                    
                ));
        $data .= '<div><h5 style="display:inline-block;">Prisoner Cash Items</h5><button class="add_more_cash btn btn-success" style="display:inline-block;margin-left:10px;"><span class="icon icon-plus"></span></button></div>';
        $data .= '<div id="add_more_cash_form" style="display:none">';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Amount</th><th>Currency</th><th>Action</th></tr></thead><tbody>';
        $data .= '<tr><td><input type="number" name="newItemAmount" id="newItemAmount"></td><td><select  name="newItemCurrency" id="newItemCurrency">';
         foreach ($currencyList as $pitem) {
            $data .= '<option value="'.$pitem["Currency"]["id"].'">'.$pitem["Currency"]["name"].'</option>';

         }
        $data .= '</select></td><td><button class="btn btn-success insert_property_cash_item">add</button></td></tr>';
        $data .= '</tbody></table></div>';
        $data .= '<table class="table table-bordered table-striped table-responsive"><thead><tr><th>Amount</th><th>Currency</th><th>Collected</th></tr></thead><tbody>';

        foreach ($visitor['VisitorPrisonerCashItem'] as $prisonerItemDetail) {
                        
                        $data .= '<tr>';

                        $data .= '<td><input type="hidden" name="data[RecievePrisonerCashItem]['.$count .'][id] " value="'. $prisonerItemDetail['id'] .'">';
                        $data .= $prisonerItemDetail['pp_amount'] .'</td>';

                        if(isset($prisonerItemDetail['CashCurrency']['name'])){
                            $data .= '<td>'. $prisonerItemDetail['CashCurrency']['name'] .'</td>';
                        }else{
                             $data .= '<td></td>';
                        }
                        if($prisonerItemDetail['is_collected'] == true){

                            $data .='<td><input type="checkbox" style="margin-left:2px;" name="data[RecievePrisonerCashItem]['.$count.'][recieved_item_check]" checked="checked"> <span style="color:green">Already Recieved</span></td>';
                        }else{
                            $allCollected ='false';
                            $data .='<td><input type="checkbox" style="margin-left:2px;" name="data[RecievePrisonerCashItem]['.$count.'][recieved_item_check]" ><span style="color:red">Not yet Received </span></td>';
                        }
                        $data .='</tr>';

                        $count++;
                    }         
                        $data .= '</tbody></table>';

        $data .= '<div style="display:none;" id="allCollectedResponse">'.$allCollected.'</div>';
        echo $data;
        exit; 
    }
    public function ajaxAddNewItem(){
        $this->loadModel('Visitor'); 
        $this->loadModel('VisitorPrisonerItem'); 

        $this->layout = 'ajax';

        $itemName = $this->request->data['itemName'];
        $itemQuantity = $this->request->data['itemQuantity'];
        $visitorId = $this->request->data['visitor_id'];

        $visitorPrisonerItem['VisitorPrisonerItem']['item_type'] = $itemName;
        $visitorPrisonerItem['VisitorPrisonerItem']['quantity'] = $itemQuantity;
        $visitorPrisonerItem['VisitorPrisonerItem']['visitor_id'] = $visitorId;
        $visitorPrisonerItem['VisitorPrisonerItem']['is_collected'] = 0;

        $this->VisitorPrisonerItem->saveAll($visitorPrisonerItem);
        echo "success";exit;
    }

    public function ajaxAddNewCashItem(){
        $this->loadModel('Visitor'); 
        $this->loadModel('VisitorPrisonerCashItem'); 

        $this->layout = 'ajax';

        $amount = $this->request->data['amount'];
        $currency = $this->request->data['currency'];
        $visitorId = $this->request->data['visitor_id'];

        $visitorPrisonerItem['VisitorPrisonerCashItem']['pp_cash'] = $currency;
        $visitorPrisonerItem['VisitorPrisonerCashItem']['pp_amount'] = $amount;
        $visitorPrisonerItem['VisitorPrisonerCashItem']['cash_details'] = "Cash";
        $visitorPrisonerItem['VisitorPrisonerCashItem']['visitor_id'] = $visitorId;
        $visitorPrisonerItem['VisitorPrisonerCashItem']['is_collected'] = 0;

        $this->VisitorPrisonerCashItem->saveAll($visitorPrisonerItem);
        echo "success";exit;
    }
    
    public function indexAjax(){
      	$this->loadModel('Visitor'); 
        $this->layout = 'ajax';
        $searchData = $this->params['named'];
        $condition = array('Visitor.is_trash'   => 0);
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $condition += array("Visitor.prison_id IN (".$this->Session->read('Auth.User.prison_id').")");
        }else{
            $condition += array("Visitor.prison_id" => $this->Session->read('Auth.User.prison_id'));
        }
        
        
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

            $condition += array(
                // 'Visitor.date >= ' => date('Y-m-d', strtotime($from)),
                // 'Visitor.date <= ' => date('Y-m-d', strtotime($to))
            );        
        }

        if(isset($this->params['named']['verify_status']) && $this->params['named']['verify_status'] != ''){
            $verify_status = $this->params['named']['verify_status'];

            $condition += array('Visitor.verify_status' => $verify_status);        
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','visiter_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','visitor_report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','visitor_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->loadModel('Propertyitem'); 
        
        /*aakash*/
          $propertyItemList = $this->Propertyitem->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,
                    'Propertyitem.is_prohibited'     => 0,

                )
            ));
          /*end aakash code*/
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Visitor.created' => 'DESC'
            ),
        )+$limit;

        $datas  = $this->paginate('Visitor');
        //debug($datas);
        $allowUpdate =$this->hasMainGate($this->Session->read('Auth.User.prison_id'));

        $this->set(array(
            'searchData'         => $searchData,
            'datas'        => $datas,
            'propertyItemList'  => $propertyItemList,
            'allowUpdate' => $allowUpdate
        )); 

    }

    public function view($visitor_id = ''){
        $menuId = $this->getMenuId("/visitors");
        $moduleId = $this->getModuleId("visitor");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $visitorList = $this->Visitor->find('all', array(
            'recursive'     => 2,
            'conditions'    => array(
                //'Visitor.is_enable'      => 1,
                'Visitor.is_trash'       => 0,
                'Visitor.id'      => $visitor_id
            ),
            'order'         => array(
                'Visitor.prisoner_no'
            ),
        ));
        $this->loadModel('Propertyitem'); 

        $propertyItemList = $this->Propertyitem->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,
                    'Propertyitem.is_prohibited'     => 0,

                )
            ));
         if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='PRINT'){
              
                $this->layout='print';
            }
            
        }

        $this->set(array(
            'visitorList'         => $visitorList,
            'propertyItemList'=>$propertyItemList
        )); 

    }

	public function add() { 
        $menuId = $this->getMenuId("/visitors");
        $moduleId = $this->getModuleId("visitor");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }

        $this->loadModel('Visitor');
        $this->loadModel('PPCash');
        $this->loadModel('Article');
        $this->loadModel('Relationship');
        $this->loadModel('PrisonerKinDetail');
        $this->loadModel('Iddetail');
        $this->loadModel('Propertyitem');
        $this->loadModel('VisitorPrisonerItem');
        $this->loadModel('VisitorPrisonerCashItem');
        $this->loadModel('WeightUnit');
        $this->loadModel('User');
        $this->loadModel('Prisoner');
        $this->loadModel('VisitorName');
        //debug($this->request->data);exit;
           
           //edit data
        $menuId = $this->getMenuId("/Visitors");
        $moduleId = $this->getModuleId("visitor");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        if(isset($this->data['VisitorEdit']['id']) && (int)$this->data['VisitorEdit']['id'] != 0){
            $menuId = $this->getMenuId("/visitors");
            $moduleId = $this->getModuleId("visitor");
            $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');
            if($isAccess != 1){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Not Authorized!');
                    $this->redirect(array('action'=>'../sites/dashboard')); 
            }

            if($this->Visitor->exists($this->data['VisitorEdit']['id'])){
                $this->request->data = $this->Visitor->findById($this->data['VisitorEdit']['id']);
                $this->loadModel('VisitorName');
                $visitorNames = $this->VisitorName->find('all',array(
                        'recursive'     => -1,
                        'conditions'    => array(
                                'VisitorName.visitor_id'    => $this->request->data['Visitor']['id'],
                                'VisitorName.is_trash'    => 0,

                            )
                        ));
                $visitorNamesFinal = array();
                foreach ($visitorNames as $key => $name) {
                    array_push($visitorNamesFinal,$name['VisitorName']);
                }
                
                $this->request->data['VisitorName'] = $visitorNamesFinal;
                //debug($this->request->data);exit;

            }
        }else{
            //save new

            if (isset($this->data['Visitor']) && is_array($this->data['Visitor']) && count($this->data['Visitor'])>0){          //save prisoner
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if(isset($this->data['Visitor']['date']) && $this->data['Visitor']['date'] !=''){
                $this->request->data['Visitor']['date'] = date('Y-m-d',strtotime($this->data['Visitor']['date']));
            }
            if(isset($this->data['Visitor']['prison_id']) && $this->data['Visitor']['prison_id'] !=''){
                $this->request->data['Visitor']['prison_id'] = $this->data['Visitor']['prison_id'];
            }else{
                $this->request->data['Visitor']['prison_id']=$this->Session->read('Auth.User.prison_id');
            }
            if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                $this->request->data['Visitor']['main_gate_in_time'] = date('h:i A');
            }
            if($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){
                $this->request->data['Visitor']['time_in'] = date('h:i A');
                $this->request->data['Visitor']['status'] = 'Gate IN';
            }
            $visitor= $this->data;
            if(count($visitor['VisitorItem'])>0){
                foreach ($visitor['VisitorItem'] as $key => $item) {
                   // debug($item);
                    if($item['quantity'] == ''){
                        unset($visitor['VisitorItem'][$key]);
                    }
                }
            }
            if(count($visitor['VisitorPrisonerCashItem'])>0){
                foreach ($visitor['VisitorPrisonerCashItem'] as $key => $item) {
                   // debug($item);
                    if($item['pp_amount'] == ''){
                        unset($visitor['VisitorPrisonerCashItem'][$key]);
                    }
                }
            }
            if(count($visitor['VisitorPrisonerItem'])>0){
                foreach ($visitor['VisitorPrisonerItem'] as $key => $item) {
                   // debug($item);
                    if($item['quantity'] == ''){
                        unset($visitor['VisitorPrisonerItem'][$key]);
                    }
                }
            }
            
            
           //debug($visitor);
           //exit;
            if(isset($this->data['Visitor']['id'])){
            
                $updateFields = array(
                    'VisitorName.is_trash'           => 1
                );
                 $updateConds =array(
                    'VisitorName.visitor_id'    => $this->data['Visitor']['id'],
                 );

                $this->VisitorName->updateAll($updateFields, $updateConds);

            }

            $allAllowed = 1;

            if(isset($visitor['Visitor']['category'])){
                $category = $visitor['Visitor']['category'];

                if($category == 'Private Visit'){
                    //for private visit only

                                if(isset($visitor['Visiter']['prisoner_id'])){
                                    $prionerId = $visitor['Visiter']['prisoner_id'];
                                    $prisoner = $this->Prisoner->findById($prionerId);
                                    $prisonerToVisit = $this->Prisoner->findById($prionerId);
                                    $visitorNamesRequest = $visitor['VisitorName'];

                                    foreach ($visitorNamesRequest as $visitorName) {

                                        $natIdType = $visitorName['nat_id_type'];
                                        $natId =  $visitorName['nat_id'];
                                        if(isset($prisoner['Prisoner']['prisoner_no'])){
                                            $prisoner_id = $prisoner['Prisoner']['id'];
                                            $checkAllowed = $this->checkIfAllowedToVisitBackendCheck($prisoner_id,$natIdType,$natId);
                                        }
                                        
                                        if($checkAllowed == 2 || $checkAllowed == 0 || $checkAllowed == 3){
                                            $allAllowed = $checkAllowed;break;
                                        }
                                    }
                                }

                                
                    //echo $allAllowed;exit;
                    
                }
            }
            //debug($this->data['VisitorPrisonerItem']);exit;

            if($allAllowed == 1){


                //save data begin
                if ($this->Visitor->saveAll($visitor)) {

            if(isset($visitor['Visitor']['category'])){
                $category = $visitor['Visitor']['category'];

                if($category == 'Private Visit'){

                                        /*aakash code to add visitor prisoner item*/
                                    

                                    if (isset($this->data['VisitorPrisonerItem']) && is_array($this->data['VisitorPrisonerItem']) && count($this->data['VisitorPrisonerItem'])>0 && $this->data['VisitorPrisonerItem'][0]['item_type'] != '') { 
                                            $alreadyExistingItems = $this->VisitorPrisonerItem->find('all',array(
                                                    'recursive'     => -1,
                                                    'conditions'    => array(
                                                        'VisitorPrisonerItem.visitor_id'    => $this->Visitor->id,

                                                    )
                                            ));
                                            if(count($alreadyExistingItems) > 0){

                                            $fields = array(
                                                'VisitorPrisonerItem.is_trash'    => 1,
                                            );

                                            $conds = array(
                                                'VisitorPrisonerItem.visitor_id'    => $this->Visitor->id,
                                            );  
                                            $this->VisitorPrisonerItem->updateAll($fields, $conds);
                                            }
                                            
                                            foreach ($this->data['VisitorPrisonerItem'] as $prisonerItem) {
                                                //debug($prisonerItem);

                                                $visitorPrisonerItem=array();
                                                if(isset($prisonerItem['quantity']) && $prisonerItem['quantity'] != ''){
                                                    $visitorPrisonerItem['VisitorPrisonerItem']['item_type'] =$prisonerItem['item_type'];
                                                $visitorPrisonerItem['VisitorPrisonerItem']['quantity'] =$prisonerItem['quantity'];
                                                $visitorPrisonerItem['VisitorPrisonerItem']['weight'] =$prisonerItem['weight'];
                                                $visitorPrisonerItem['VisitorPrisonerItem']['weight_unit'] =$prisonerItem['weight_unit'];

                                                $propertyItem =  $this->Propertyitem->findById($prisonerItem['item_type']);

                                                if(isset($propertyItem['item_id'])){
                                                    $propertyStatus = $this->getPropertyTypeNew($propertyItem['item_id']);
                                            //echo $propertyStatus;exit;
                                                    if($propertyStatus !=''){

                                                        if($propertyStatus == 'allowed'){
                                                             $visitorPrisonerItem['VisitorPrisonerItem']['property_type'] =$prisonerItem['property_type'];
                                                        }else{
                                                             $match = explode(',', $propertyStatus);
                                                           if($match[1] == 'Destroyed'){
                                                                $visitorPrisonerItem['VisitorPrisonerItem']['property_type'] ='Destroyed';
                                                           }else{
                                                                $visitorPrisonerItem['VisitorPrisonerItem']['property_type'] ='In Store';
                                                           }
                                                        }

                                                    }else{
                                                        continue;
                                                    }
                                                }
                                                
                                              

                                                $visitorPrisonerItem['VisitorPrisonerItem']['prisoner_id'] =$prisonerToVisit['Prisoner']['id'];
                                                $visitorPrisonerItem['VisitorPrisonerItem']['visitor_id'] =$this->Visitor->id;
                                                $this->VisitorPrisonerItem->saveAll($visitorPrisonerItem['VisitorPrisonerItem']);
                                                }
                                                

                                            }
                                        }
                        /*aakash code to add visitor prisoner item ends*/

                                        //add prisoner cash
                                        if (isset($this->data['VisitorPrisonerCashItem']) && is_array($this->data['VisitorPrisonerCashItem']) && count($this->data['VisitorPrisonerCashItem'])>0){ 
                                            if(isset($visitorPrisonerItem['VisitorPrisonerCashItem']['pp_amount'])){
                                                    $this->loadModel('Prisoner'); 

                                                    
                                            $alreadyExistingItems = $this->VisitorPrisonerCashItem->find('all',array(
                                                    'recursive'     => -1,
                                                    'conditions'    => array(
                                                        'VisitorPrisonerCashItem.visitor_id'    => $this->Visitor->id,

                                                    )
                                            ));
                                            if(count($alreadyExistingItems) > 0){

                                            $fields = array(
                                                'VisitorPrisonerCashItem.is_trash'    => 1,
                                            );

                                            $conds = array(
                                                'VisitorPrisonerCashItem.visitor_id'    => $this->Visitor->id,
                                            );  
                                            $this->VisitorPrisonerCashItem->updateAll($fields, $conds);
                                            }

                                            
                                            foreach ($this->data['VisitorPrisonerCashItem'] as $prisonerItem) {
                                                //debug($prisonerItem);
                                             
                                                if(isset($prisonerItem['pp_amount']) && $prisonerItem['pp_amount'] != ''){

                                                $visitorPrisonerItem=array();
                                                $visitorPrisonerItem['VisitorPrisonerCashItem']['cash_details'] =$prisonerItem['cash_details'];
                                                $visitorPrisonerItem['VisitorPrisonerCashItem']['pp_cash'] =$prisonerItem['pp_cash'];
                                                $visitorPrisonerItem['VisitorPrisonerCashItem']['pp_amount'] =$prisonerItem['pp_amount'];
                                                $visitorPrisonerItem['VisitorPrisonerCashItem']['visitor_id'] =$this->Visitor->id;
                                                $this->VisitorPrisonerCashItem->saveAll($visitorPrisonerItem['VisitorPrisonerCashItem']);
                                                }

                                                }
                                            }
                                            
                                        }
                                         //add cash ends 



                                        //check if main gatekeeper not there
                                        $allowUpdate = false;
                                       
                                        $allowUpdate =$this->checkMainGatekeeperExits();
                                        //check main gatekeeper ends
                                        if(!$allowUpdate){
                                            //save Now to property
                                            $this->recieveMainItemCash($this->Visitor->id);
                                        }

                                        //notify users

                                        
                                    if($this->request->data['Visitor']['id'] == ''){
                                        if($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')){
                                            $refId = 0;
                                            $action = 'Add';
                                            //$user1 = Configure::read('GATEKEEPER_USERTYPE');
                                            $user2 = Configure::read('RECEPTIONIST_USERTYPE');
                                            $userData = $this->User->find("list", array(
                                                "recursive"    => -1,
                                                "conditions" => array(
                                                    "User.usertype_id IN (".$user2.")",
                                                    "User.id NOT IN('".$this->Session->read('Auth.User.id')."')",
                                                    "User.prison_id" => $this->Session->read('Auth.User.prison_id'),
                                                    ),
                                                ));
                                            //debug($userData);exit;
                                            if(isset($userData) && $userData!=''){
                                                foreach ($userData as $key => $value) {
                                                    $this->addNotification(array(
                                                        "user_id"=>$key,
                                                        "content"=>"Visitor Added Successfully",
                                                        "url_link"=>"visitors"));
                                                    }
                                                }
                                            }
                                         if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                                             $refId = 0;
                                            $action = 'Add';
                                            $user1 = Configure::read('GATEKEEPER_USERTYPE');
                                            $user2 = Configure::read('RECEPTIONIST_USERTYPE');
                                            $userData = $this->User->find("list", array(
                                                "recursive"    => -1,
                                                "conditions" => array(
                                                    "User.prison_id" => $this->request->data['Visitor']['prison_id'],
                                                    "User.usertype_id IN (".$user1.",".$user2.")",
                                                    ),
                                                ));
                                            //debug($userData);exit;
                                            if(isset($userData) && is_array($userData) && count($userData)>0){
                                                $this->addManyNotification($userData,"Visitor Added Successfully","visitors");
                                                }
                                             }
                                    }

                }
            }
                    //save vehicle item
                    if (isset($this->data['VehicleItem']) && is_array($this->data['VehicleItem']) && count($this->data['VehicleItem'])>0 ) { 

                                            $this->loadModel('VehicleItem'); 

                                            $alreadyExistingItems = $this->VehicleItem->find('all',array(
                                                    'recursive'     => -1,
                                                    'conditions'    => array(
                                                        'VehicleItem.visitor_id'    => $this->Visitor->id,

                                                    )
                                            ));
                                            if(count($alreadyExistingItems) > 0){

                                            $fields = array(
                                                'VehicleItem.is_trash'    => 1,
                                            );

                                            $conds = array(
                                                'VehicleItem.visitor_id'    => $this->Visitor->id,
                                            );  
                                            $this->VehicleItem->updateAll($fields, $conds);
                                            }

                                            
                                            foreach ($this->data['VehicleItem'] as $vehicleItm) {
                                                //debug($prisonerItem);
                                                if(isset($vehicleItm['quantity']) && $vehicleItm['quantity'] != ''){
                                                    
                                                $vehicleItem=array();
                                                $vehicleItem['VehicleItem']['voucher_no'] =$vehicleItm['voucher_no'];
                                                $vehicleItem['VehicleItem']['item'] =$vehicleItm['item'];
                                                $vehicleItem['VehicleItem']['quantity'] =$vehicleItm['quantity'];
                                                $vehicleItem['VehicleItem']['description'] =$vehicleItm['description'];

                                              
                                                $vehicleItem['VehicleItem']['visitor_id'] =$this->Visitor->id;
                                                $this->VehicleItem->saveAll($vehicleItem['VehicleItem']);
                                                }

                                            }
                                        }
                    //save vehicle item ends



                                        //notify end

                                    if(isset($this->data['Visitor']['id']) && (int)$this->data['Visitor']['id'] != 0)
                                    {
                                        $refId  = $this->data['Visitor']['id'];
                                        $action = 'Edit';
                                        if($this->auditLog('Visitor', 'visitors', $refId, $action, json_encode($this->data)))
                                            {
                                                $db->commit(); 
                                                $this->Session->write('message_type','success');
                                                $this->Session->write('message','The record has been saved.');
                                                $this->redirect(array('action'=>'index'));
                                            }
                                            else 
                                            {
                                                $db->rollback();
                                                $this->Session->write('message_type','error');
                                                $this->Session->write('message','The record could not be saved. Please, try again.');
                                            }
                                    }else{
                                        
                                                $db->commit(); 
                                                $this->Session->write('message_type','success');
                                                $this->Session->write('message','The record has been saved.');
                                                $this->redirect(array('action'=>'index'));
                                    }
                                    

                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                         $this->Session->write('message','The record could not be saved. Please, try again.');
                               
                    }
                //save data end
                                                    //save visitor end
            }else{
                if($allAllowed == 2){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Visitors are in Blacklisted list.');
                        $this->redirect(array('action'=>'index'));

                }else if($allAllowed == 3){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Visitor not allowed by stage.');
                        $this->redirect(array('action'=>'index'));
                }else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Visitors not allowed to visit.');
                        $this->redirect(array('action'=>'index'));

                }
            }  
        }

        }
       //get prisoner list
          $prison_id = $_SESSION['Auth']['User']['prison_id'];
          $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id'      => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
          $prisonernameList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.fullname',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id'      => $prison_id
            ),
            'order'         => array(
                'Prisoner.fullname'
            ),
        ));
          $gateKeepers = $this->User->find('list',array(
                'fields'        => array(
                    'User.id',
                    'User.first_name',
                ),
                'conditions'=>array(
                  'User.is_enable'=>1,
                  'User.is_trash'=>0,
                  'User.usertype_id'=>10,//Gate keeper User
                ),
                'order'=>array(
                  'User.first_name'
                )
          ));
          $this->loadModel('Currency');
          $ppcash = $this->Currency->find('list',array(
                'fields'        => array(
                    'Currency.id',
                    'Currency.name',
                ),
                'conditions'=>array(
                  'Currency.is_enable'=>1,
                  'Currency.is_trash'=>0,
                ),
                'order'=>array(
                  'Currency.name'
                )
          ));
          $weight_units = $this->WeightUnit->find('list',array(
                'fields'        => array(
                    'WeightUnit.id',
                    'WeightUnit.name',
                ),
                'conditions'=>array(
                  'WeightUnit.is_enable'=>1,
                  'WeightUnit.is_trash'=>0,
                ),
                'order'=>array(
                  'WeightUnit.name'
                )
          ));
          $relation = $this->Relationship->find('list',array(
                'fields'        => array(
                    'Relationship.id',
                    'Relationship.name',
                ),
                'conditions'=>array(
                  'Relationship.is_enable'=>1,
                  'Relationship.is_trash'=>0,
                ),
                'order'=>array(
                  'Relationship.name'
                )
          ));
          //debug($_SESSION);
          $prison_id = $_SESSION['Auth']['User']['prison_id'];
          $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$prison_id.")" 
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
          $prionerKinLIst = $this->PrisonerKinDetail->find('all', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerKinDetail.first_name',
                'PrisonerKinDetail.middle_name',
                'PrisonerKinDetail.last_name',
                'PrisonerKinDetail.relationship',
                'PrisonerKinDetail.national_id_no',
            ),
            'conditions'    => array(
                'PrisonerKinDetail.is_trash'   => 0,
                'PrisonerKinDetail.is_enable'  => 0,
            ),
            'order'         => array(
                'PrisonerKinDetail.prisoner_id'
            ),
        ));
          $natIdList      = $this->Iddetail->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Iddetail.id',
                        'Iddetail.name',
                    ),
                    'conditions'    => array(
                        'Iddetail.is_enable'      => 1,
                        'Iddetail.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Iddetail.name'
                    ),
                ));
          $whomToMeetList = array();
          $article = $this->Article->find('first');

          /*aakash*/
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
                        if($item['Propertyitem']['prison_id'] != $prison_id || $item['Propertyitem']['status'] != 'Approved'){
                           unset($propertyItemList[$key]);
                        }
                    }
                    //debug($item);exit;
                }

          /*end aakash code*/
        $this->set(array(
            'prisonerList'    => $prisonerList,
            'gateKeepers'     => $gateKeepers,
            'ppcash'          => $ppcash,
            'article'         => $article,
            'relation'        => $relation,
            'prisonernameList'=> $prisonernameList,
            'prisonList'      => $prisonList,
            'prionerKinLIst'  => $prionerKinLIst,
            'natIdList'       => $natIdList,
            'propertyItemList'=>$propertyItemList,
            'weight_units' => $weight_units,
            'whomToMeetList' => $whomToMeetList

        ));

    }

public function getPropertyTypeNew($id=''){
        if($id != ''){
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
        }else{
            echo 'failure';

        }
       
    }

    public function getWhomToMeetUsers(){

        $prisonId =  $this->params['data']['prison_id'];
         $whomToMeetList = $this->User->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.name',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.prison_id'      => $prisonId
                    ),
                    'order'         => array(
                        'User.name'
                    ),
                ));
            
            $htm ='<option value=""> -- select user -- </option>';
            foreach ($whomToMeetList as $key => $value) {
                $htm .= '<option value="'.$key.'">'.$value .' </option>';
            }
            
            echo $htm;
            exit;
           // echo $schoolProgramId;exit;
         } 

    public function recieveMainItemCash($visitor_id){
        $this->loadModel('CashItem');
        $this->loadModel('Prisoner'); 
        $this->loadModel('VisitorPrisonerItem'); 
        $this->loadModel('PhysicalProperty');
        $this->loadModel('PhysicalPropertyItem');
        $this->loadModel('VisitorPrisonerCashItem');

        $visitor = $this->Visitor->findById($visitor_id);
       //debug($this->data['RecievePrisonerItem']);exit;

        $prisoner = $this->Prisoner->find('first', array(
            'conditions'    => array(
                'Prisoner.prisoner_no'      => $visitor['Visitor']['prisoner_no']
            ),
        ));
        /*aakash recieve prisoner item*/
        $visitorPrisonerItems  = $this->VisitorPrisonerItem->find('all',array(
            'conditions'    => array(
                'VisitorPrisonerItem.visitor_id'      => $visitor_id,
                'VisitorPrisonerItem.is_trash'  =>0
            ),
        ));

        $visitorPrisonerCashItems = $this->VisitorPrisonerCashItem->find('all',array(
            'conditions'    => array(
                'VisitorPrisonerCashItem.visitor_id'      => $visitor_id,
                'VisitorPrisonerCashItem.is_trash'  =>0

            ),
        ));
        $physicalProperty=array();
      $notifyUser = $this->User->find('first',array(
                                            'recursive'     => -1,
                                            'conditions'    => array(
                                                'User.usertype_id'    => Configure::read('RECEPTIONIST_USERTYPE'),
                                                'User.is_trash'     => 0,
                                                'User.is_enable'     => 1,
                                                'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                            )
                                        ));
            //debug($visitorPrisonerItems);exit;
        foreach ($visitorPrisonerItems as $recievedItem) {
                $recievedItemId = $recievedItem['VisitorPrisonerItem']['id'];

                $visitorPrisonerItem = $this->VisitorPrisonerItem->findById($recievedItemId);
                
                
                if($visitorPrisonerItem['VisitorPrisonerItem']['is_collected'] == false){

                        $physicalPropertyNew =array();
                        $physicalPropertyNew['PhysicalProperty']['property_date_time']=date('Y-m-d H:i:s');
                        $physicalPropertyNew['PhysicalProperty']['login_user_id']=$notifyUser['User']['id'];
                        $physicalPropertyNew['PhysicalProperty']['description']=$visitor['Visitor']['category'];
                        $physicalPropertyNew['PhysicalProperty']['visitor_id']=$visitor['Visitor']['id'];
                        $physicalPropertyNew['PhysicalProperty']['source']='Visitor';
                        if(isset($prisoner['Prisoner']['id'])){
                          $physicalPropertyNew['PhysicalProperty']['prisoner_id']=$prisoner['Prisoner']['id'];  
                        }
                        
                        $physicalPropertyNew['PhysicalProperty']['property_type']='Physical Property';
                        $this->PhysicalProperty->saveAll($physicalPropertyNew['PhysicalProperty']);
                    

                    $physicalPropertyItem['PhysicalPropertyItem']['physicalproperty_id']=$this->PhysicalProperty->id;
                    $physicalPropertyItem['PhysicalPropertyItem']['item_id']=$visitorPrisonerItem['VisitorPrisonerItem']['item_type'];
                    $physicalPropertyItem['PhysicalPropertyItem']['quantity']=$visitorPrisonerItem['VisitorPrisonerItem']['quantity'];
                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =$visitorPrisonerItem['VisitorPrisonerItem']['property_type'];

                    $propertyItem =  $this->Propertyitem->findById($visitorPrisonerItem['VisitorPrisonerItem']['item_type']);
                    $physicalPropertyItem['PhysicalPropertyItem']['prison_id'] =$this->Session->read('Auth.User.prison_id');
                       // debug($propertyItem);exit;
                        if(isset($propertyItem['Propertyitem']['is_allowed'])){

                            if($propertyItem['Propertyitem']['is_allowed'] == 1){
                                 $physicalPropertyItem['PhysicalPropertyItem']['is_provided'] =  'Allowed';
                                    $physicalPropertyItem['PhysicalPropertyItem']['item_status'] =  'Supplementary Incoming';
                                    $physicalPropertyItem['PhysicalPropertyItem']['status'] =  'Draft';
                            }else if(isset($propertyItem['Propertyitem']['is_prohibited']) && $propertyItem['Propertyitem']['is_prohibited'] == 1){
                               if($propertyItem['Propertyitem']['property_type_prohibited'] == 'Destroyed'){
                                    $physicalPropertyItem['PhysicalPropertyItem']['is_provided'] =  'Prohibited';
                                    $physicalPropertyItem['PhysicalPropertyItem']['item_status'] =  'Destroy';
                                    $physicalPropertyItem['PhysicalPropertyItem']['destroy_status'] =  'Draft';
                                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =  'Destroyed';

                               }else{
                                $physicalPropertyItem['PhysicalPropertyItem']['is_provided'] =  'Prohibited';
                                    $physicalPropertyItem['PhysicalPropertyItem']['item_status'] =  'Supplementary Incoming';
                                    $physicalPropertyItem['PhysicalPropertyItem']['status'] =  'Draft';
                                    $physicalPropertyItem['PhysicalPropertyItem']['property_type'] =  'In Store';
                                    
                               }
                            }

                        }else{
                            continue;
                        }
                    
                    $physicalPropertyItem['PhysicalPropertyItem']['bag_no']=0;
                    if($this->PhysicalPropertyItem->saveAll($physicalPropertyItem['PhysicalPropertyItem'])){
                        $visitorPrisonerItem['VisitorPrisonerItem']['is_collected']=1;
                        $this->VisitorPrisonerItem->saveAll($visitorPrisonerItem['VisitorPrisonerItem']);
                         $this->addNotification(array("user_id"=>$notifyUser['User']['id'],"content"=>"Visitor Physical Property recieved by Gatekeeper","url_link"=>"properties/physicalPropertyList"));
                        
                    }

                }
           
            
        }
        /*aakash recieve prisoner item ends*/
        //Recieve cash
            foreach ($visitorPrisonerCashItems as $recievedItem) {
                $visitorPrisonerCashId = $recievedItem['VisitorPrisonerCashItem']['id'];

                $visitorPrisonerItem = $this->VisitorPrisonerCashItem->findById($visitorPrisonerCashId);
                //debug($visitorPrisonerItem);exit;
                if($visitorPrisonerItem['VisitorPrisonerCashItem']['is_collected'] == false){

                        $physicalPropertyNew =array();
                        $physicalPropertyNew['PhysicalProperty']['property_date_time']=date('Y-m-d H:i:s');
                        $physicalPropertyNew['PhysicalProperty']['login_user_id']=$notifyUser['User']['id'];
                        $physicalPropertyNew['PhysicalProperty']['description']=$visitor['Visitor']['category'];
                        $physicalPropertyNew['PhysicalProperty']['visitor_id']=$visitor['Visitor']['id'];
                        $physicalPropertyNew['PhysicalProperty']['source']='Visitor';
                        $physicalPropertyNew['PhysicalProperty']['prisoner_id']=$prisoner['Prisoner']['id'];
                        $physicalPropertyNew['PhysicalProperty']['property_type']='Cash';
                        ;
                    if($this->PhysicalProperty->saveAll($physicalPropertyNew['PhysicalProperty'])){
                                    $cashItem['CashItem']['physicalproperty_id']=$this->PhysicalProperty->id;
                                    $cashItem['CashItem']['amount']=$visitorPrisonerItem['VisitorPrisonerCashItem']['pp_amount'];
                                    $cashItem['CashItem']['currency_id']=$visitorPrisonerItem['VisitorPrisonerCashItem']['pp_cash'];
                                    $cashItem['CashItem']['status']='Draft';
                                    $cashItem['CashItem']['prison_id'] =$this->Session->read('Auth.User.prison_id');

                                    if($this->CashItem->saveAll($cashItem)){
                                        $visitorPrisonerItem['VisitorPrisonerCashItem']['is_collected']=1;
                                        $this->VisitorPrisonerCashItem->save($visitorPrisonerItem);
                                        
                                        $this->addNotification(array("user_id"=>$notifyUser['User']['id'],"content"=>"Visitor cash recieved by Gatekeeper","url_link"=>"properties/creditList"));
                                        //echo "success";
                                    }else{
                                        //echo 'failed';
                                    }

                                }

                    

                }
            
        }
        return 1;
    }

    public function timeout(){
        $this->autoRender = false;
        if(isset($this->params['named']) && $this->params['named'] !=''){
            $visitor_id = $this->params['named']['visitor_id'];
            $visitorTimeIn = $this->Visitor->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Visitor.id'     => $visitor_id,
                ),
            ));
            $time_in = $visitorTimeIn['Visitor']['time_in'];
            $duration = date("h:i") - $time_in;
            
            // $checkTime = strtotime('03:06');
            // $loginTime = strtotime('05:07');
            // $diff = $checkTime - $loginTime;

            //Calculate Time Duration
            $in = strtotime($time_in);
            $out = strtotime(date("h:i A"));
            $duration = $in - $out;
            $timeDuration = gmdate("H:i", abs($duration));

            $fields = array(
                'Visitor.time_out'    => "'".date("h:i A")."'",
                'Visitor.duration'    => "'".$timeDuration."'",
            );
            $conds = array(
                'Visitor.id'    => $visitor_id,
            );

            if($this->Visitor->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    } 

    public function newTimeout(){
        $this->autoRender = false;
        if(isset($this->params['named']) && $this->params['named'] !=''){
            $visitor_id = $this->params['named']['visitor_id'];
            $visitorTimeIn = $this->Visitor->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Visitor.id'     => $visitor_id,
                ),
            ));
            
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                if($visitorTimeIn['Visitor']['status']=='Gate Out'){
                    $time_in = $visitorTimeIn['Visitor']['main_gate_in_time'];
                    $duration = date("h:i") - $time_in;
                    
                    $in = strtotime($time_in);
                    $out = strtotime(date("h:i A"));
                    $duration = $in - $out;
                    $timeDuration = gmdate("H:i", abs($duration));
                    $fields = array(
                        'Visitor.main_gate_out_time'    => "'".date("h:i A")."'",
                        'Visitor.status'    => "'Exit'",
                        'Visitor.main_gate_duration'    => "'".$timeDuration."'",
                    );
                }              
            }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                if($visitorTimeIn['Visitor']['status']=='IN'){
                    $fields = array(
                        'Visitor.time_in'    => "'".date("h:i A")."'",
                        'Visitor.status'    => "'Gate IN'",
                        // 'Visitor.duration'    => "'".$timeDuration."'",
                    );
                }
                if($visitorTimeIn['Visitor']['status']=='Gate IN'){
                    $time_in = $visitorTimeIn['Visitor']['time_in'];
                    $duration = date("h:i") - $time_in;
                    
                    $in = strtotime($time_in);
                    $out = strtotime(date("h:i A"));
                    $duration = $in - $out;
                    $timeDuration = gmdate("H:i", abs($duration));
                    $fields = array(
                        'Visitor.time_out'    => "'".date("h:i A")."'",
                        'Visitor.status'    => "'Gate Out'",
                        'Visitor.duration'    => "'".$timeDuration."'",
                    );
                    $gatekeeperData = $this->User->find('first', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                            "(".$this->Session->read('Auth.User.prison_id').")"." IN (User.prison_id)",
                        ),
                    ));
                    $userId = $gatekeeperData['User']['id'];
                    $message = $this->getVisitorName($visitor_id).' out from gate.';
                }                
            }
            $conds = array(
                'Visitor.id'    => $visitor_id,
            );

            if($this->Visitor->updateAll($fields, $conds)){    
                if(isset($userId) && $userId!=''){
                    $this->addNotification(array(
                        "user_id"=>$userId,
                        "content"=>$message,
                        "url_link"=>"visitors"));
                } 
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    } 

    public function missing(){
        $this->autoRender = false;
        if(isset($this->params['named']) && $this->params['named'] !=''){
            $visitor_id = $this->params['named']['visitor_id'];
            $visitorTimeIn = $this->Visitor->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Visitor.id'     => $visitor_id,
                ),
            ));
            $time_in = $visitorTimeIn['Visitor']['time_in'];
            $duration = date("h:i") - $time_in;
            
            // $checkTime = strtotime('03:06');
            // $loginTime = strtotime('05:07');
            // $diff = $checkTime - $loginTime;

            //Calculate Time Duration
            $in = strtotime($time_in);
            $out = strtotime(date("h:i A"));
            $duration = $in - $out;
            $timeDuration = gmdate("H:i", abs($duration));

            $fields = array(
                'Visitor.time_out'    => "'".date("h:i A")."'",
                'Visitor.duration'    => "'".$timeDuration."'",
            );
            $conds = array(
                'Visitor.id'    => $visitor_id,
            );

            if($this->Visitor->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    }

    function getKinDetail()
    {
        $this->layout = 'ajax';
        $this->loadModel('PrisonerKinDetail');
        $this->loadModel('Relationship');
        $this->loadModel('Iddetail');
        //$this->autoRender = false;
        $prisoner_id = $this->request->data['name'];
        $data = '';
        if(isset($prisoner_id) && (int)$prisoner_id != 0)
        {
            //$prisonerData = $this->Prisoner->findById($prisoner_id);

            $kinData = $this->PrisonerKinDetail->find('all', array(
                'recursive'     => -1,
                'fields'        => array(

                    'PrisonerKinDetail.first_name',
                    'PrisonerKinDetail.middle_name',
                    'PrisonerKinDetail.last_name',
                    'PrisonerKinDetail.relationship',
                    'PrisonerKinDetail.national_id_no',
                ),
                'conditions'    => array(
                    'PrisonerKinDetail.is_trash'         => 0,
                    'PrisonerKinDetail.is_enable'        => 0,
                    'PrisonerKinDetail.prisoner_id'      => $prisoner_id
                ),
            ));
             $relation = $this->Relationship->find('list',array(
                'fields'        => array(
                    'Relationship.id',
                    'Relationship.name',
                ),
                'conditions'=>array(
                  'Relationship.is_enable'=>1,
                  'Relationship.is_trash'=>0,
                ),
                'order'=>array(
                  'Relationship.name'
                )
          ));
             $natIdList      = $this->Iddetail->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Iddetail.id',
                        'Iddetail.name',
                    ),
                    'conditions'    => array(
                        'Iddetail.is_enable'      => 1,
                        'Iddetail.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Iddetail.name'
                    ),
                ));

            $this->set(array(
                'kinData'         => $kinData,
                'relation'        => $relation,
                'natIdList'		  => $natIdList 
            )); 

        }
    }
    public function alert(){
        $this->autoRender = false;
        if(isset($this->params['named']) && $this->params['named'] !=''){
             $gatekeeperData = $this->User->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "(".$this->Session->read('Auth.User.prison_id').")"." IN (User.prison_id)",
                ),
            ));
            $userId = $gatekeeperData['User']['id'];
            $this->addNotification(array(
                "user_id"=>$userId,
                "content"=>"Visitor Not Reached Yet",
                "url_link"=>"visitors"));
            echo 'SUCC';
      
        }else{
            echo 'FAIL';
        }
    }
    public function newAlert(){
        $this->autoRender = false;
        // debug($this->params);
        if(isset($this->params['named']['visitor_id']) && $this->params['named']['visitor_id'] !=''){
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                
                $gatekeeperData = $this->User->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'User.usertype_id'      => Configure::read('GATEKEEPER_USERTYPE'),
                        'User.prison_id'      => $this->getName($this->params['named']['visitor_id'],"Visitor","prison_id"),
                    ),
                ));
                $userId = $gatekeeperData['User']['id'];
                $visiterName = $this->getVisitorName($this->params['named']['visitor_id']);
                $this->addNotification(array(
                    "user_id"=>$userId,
                    "content"=>$visiterName." Not Reached Yet",
                    "url_link"=>"visitors"));
                echo 'SUCC';exit;
            }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                $gatekeeperData = $this->User->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                        "(".$this->Session->read('Auth.User.prison_id').")"." IN (User.prison_id)",
                    ),
                ));
                $userId = $gatekeeperData['User']['id'];
                $visiterName = $this->getVisitorName($this->params['named']);
                $this->addNotification(array(
                    "user_id"=>$userId,
                    "content"=>$visiterName. " Not Reached Yet",
                    "url_link"=>"visitors"));
                echo 'SUCC';exit;        
            }
        }else{
            echo 'FAIL';exit;
        }
    }


    //code by smita for gate book//
    public function gateBookReport(){
    $this->loadModel('Visitor'); 
        //debug($this->data['RecordStaffDelete']['id']);
        //return false;
        if(isset($this->data['VisitorDelete']['id']) && (int)$this->data['VisitorDelete']['id'] != 0){
            
            $this->Visitor->id=$this->data['VisitorDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Visitor->saveField('is_trash',1))
            {
                if($this->auditLog('Visitor', 'visitors', $this->data['VisitorDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete failed');
            }
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$this->Session->read('Auth.User.prison_id').")"
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        $allowUpdate = true;
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $allowUpdate = true;
        }elseif ($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')) {
            $gatekeeperData = $this->User->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "prison_id REGEXP CONCAT('(^|,)(', REPLACE(".$this->Session->read('Auth.User.prison_id').", ',', '|'), ')(,|$)')",
                ),
            ));
            exit;
            if($gatekeeperData == 0){
                $allowUpdate = false;
            }
        }else{
            $allowUpdate = false;
        }
        
         $this->set(array(
            'prisonList'         => $prisonList,
            'allowUpdate'        => $allowUpdate,
            //'gatekeeperData'     => $gatekeeperData
        )); 
}

public function gateBookReportAjax(){
        $this->loadModel('Visitor'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $category = $this->params['named']['category'];
        $condition = array('Visitor.is_trash'   => 0);
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
        $condition += array("Visitor.prison_id IN (".$this->Session->read('Auth.User.prison_id').")");
      }else{
        $condition += array("Visitor.prison_id" => $this->Session->read('Auth.User.prison_id'));
      }
        //debug($this->params);exit;
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

         $condition += array('Visitor.date >= ' => date('Y-m-d', strtotime($from)),
                              'Visitor.date <= ' => date('Y-m-d', strtotime($to))
                             );        
        }
        if(isset($this->params['named']['category']) && $this->params['named']['category'] != ''){
            $prisoner_id = $this->params['named']['category'];
             
            $condition += array(
                "Visitor.category"=>$category
                
            );
        }
        if(isset($this->params['named']['gate_keeper_name']) && $this->params['named']['gate_keeper_name'] != ''){
             $name = str_replace('  ', '', $this->params['named']['gate_keeper_name']);

            $condition += array("Visitor.gate_keeper LIKE '%$name%'");
              //$condition += array('Visitor.gate_keeper' => $this->params['named']['gate_keeper_name'] );
            //debug($condition);
           // $condition += array("CONCAT('Prisoner.first_name', 'Prisoner.middle', 'Prisoner.lastname') LIKE '%$name%'");
        } 
       
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Visitor.created' => 'DESC'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Visitor');
        //debug($datas);

        $this->set(array(
            'from'         => $from,
            'to'           => $to,
            'datas'        => $datas,
        )); 
    }

    function getVisitorName($visitor_id){
        $this->loadModel('VisitorName');
        $condition = array(
            'VisitorName.visitor_id'    => $visitor_id
        );
        $data = $this->VisitorName->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'VisitorName.id',
                'VisitorName.name',
            ),
            'conditions'    => $condition
        ));
        if(isset($data) && is_array($data) && count($data)>0){
            return implode(", ", $data);
        }else{
            return '';
        }        
    }

    public function checkMainGatekeeperExits(){
        $allowUpdate = false;
        if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            $allowUpdate = true;
        }elseif ($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')) {
            $gatekeeperData = $this->User->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "prison_id REGEXP CONCAT('(^|,)(', REPLACE(".$this->Session->read('Auth.User.prison_id').", ',', '|'), ')(,|$)')",
                ),
            ));
            if($gatekeeperData == 0){
                $allowUpdate = true;
            }
        }else{
            $allowUpdate = false;
        }
        return $allowUpdate;
    }

    public function getPrisoner(){
        $this->layout = 'ajax';
        $prisonernameList = $this->Prisoner->find("list", array(
            "conditions"    => array(
                "Prisoner.prison_id"    => $this->data['prison_id'],
                "Prisoner.is_trash"    => 0,
                "Prisoner.is_enable"    => 1,
                "Prisoner.is_approve"    => 1,
                "Prisoner.present_status"    => 1,
                'Prisoner.transfer_status !='        => 'Approved',
                'Prisoner.status'        => 'Approved',
            ),
            "fields"        => array(
                "Prisoner.id",
                "Prisoner.fullname",
            ),
        ));
        $this->set(array(
            'prisonernameList'         => $prisonernameList,
        ));
    }

    public function hasMainGate($prison_id){
        if ($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE')) {
            return $this->User->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                    "prison_id REGEXP CONCAT('(^|,)(', REPLACE(".$prison_id.", ',', '|'), ')(,|$)')",
                ),
            ));
            return 0;
        }else{
            return 1;
        }
    }

    public function getNextReceive($prisoner_id){
        // $prisoner_id = $this->params['named']['prisoner_id'];
        $this->loadModel('Privilege');
        $stageData = $this->StageHistory->find("first", array(
            "recursive"     => -1,
            "conditions"    => array(
                "StageHistory.prisoner_id"  => $prisoner_id,
                "StageHistory.is_trash"     => 0,
            ),
        ));

        if(isset($stageData) && count($stageData)>0){
            $conditionPri = array();
            $conditionLetter = array();
            $type = '';            
            $conditionPri = array("Privilege.privilege_right_id"=>Configure::read('VISIT-RECEIVE'));

            // check the diciplinary action for privilages =====
            $punishmentData = $this->InPrisonPunishment->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "InPrisonPunishment.prisoner_id"    => $prisoner_id,
                    "InPrisonPunishment.is_trash"       => 0,
                    "InPrisonPunishment.status"         => 'Approved',
                    "InPrisonPunishment.internal_punishment_id"         => 6,
                    "'".date("Y-m-d")."' between InPrisonPunishment.punishment_start_date and InPrisonPunishment.punishment_end_date"
                ),
                "order"         => array(
                    "InPrisonPunishment.id"    => "desc",
                ),
            ));
            // echo "<pre>";print_r($punishmentData);
            $is_punishment = false;
            if(isset($punishmentData['InPrisonPunishment']['privilege_id']) && $punishmentData['InPrisonPunishment']['privilege_id']!=''){
                if(in_array(Configure::read("VISIT-RECEIVE"),explode(",", $punishmentData['InPrisonPunishment']['privilege_id']))){
                    $type = "receive";
                    $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("VISIT-RECEIVE"));
                    $is_punishment = true;
                }
            }

            
            $privilegeData = $this->Privilege->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "Privilege.stage_id"  => $stageData['StageHistory']['stage_id'],
                    "Privilege.is_trash"     => 0,
                )+$conditionPri,
            ));
            // debug($privilegeData);
            if(isset($privilegeData['Privilege']['interval_week']) && $privilegeData['Privilege']['interval_week']!=''){
                $visitersData = $this->Visitor->find("first", array(
                    "recursive"     => -1,
                    "conditions"    => array(
                        "Visitor.prisoner_no"  => $this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$prisoner_id)),
                        "Visitor.is_trash"     => 0,
                    ),
                    "order"         => array(
                        "Visitor.id"    => "desc",
                    ),
                ));
                //=====================================================
                if($is_punishment){
                    $nextReceiveDate = date('d-m-Y', strtotime($punishmentData['InPrisonPunishment']['punishment_end_date']));

                    if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                        $privilage = array();
                        foreach (explode(",", $punishmentData["InPrisonPunishment"]["privilege_id"]) as $key => $value) {
                            $privilage[] = $this->getName($value,"PrivilegeRight","name");
                        }
                        echo "This prisoner receive visiter after ".$nextReceiveDate.". Prisoner has punished by Forfeiture of privileges, restrict for ".implode(", ", $privilage)." till ".$nextReceiveDate;exit;
                    }
                }
                //=====================================================
                if(isset($visitersData) && count($visitersData)>0){
                    $nextReceiveDate = date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($visitersData['Visitor']['date'])));
                    if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                        echo "This prisoner receive visiter after ".date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($visitersData['Visitor']['date']))).". Prisoner belongs to ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name").", So the prisoner will be able to receive visiter in interval of ".$privilegeData['Privilege']['interval_week']." weeks";exit;
                    }                    
                }
            }else{
                echo "Privilege is not updated for ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name");exit;
            }
        }else{
            echo "This prisoner is not in stage system";exit;
        }
        exit;
    }


    public function getStageValidate($prisoner_id){
        // $prisoner_id = $this->params['named']['prisoner_id'];
        $this->loadModel('Privilege');
        $stageData = $this->StageHistory->find("first", array(
            "recursive"     => -1,
            "conditions"    => array(
                "StageHistory.prisoner_id"  => $prisoner_id,
                "StageHistory.is_trash"     => 0,
            ),
        ));

        if(isset($stageData) && count($stageData)>0){
            $conditionPri = array();
            $conditionLetter = array();
            $type = '';            
            $conditionPri = array("Privilege.privilege_right_id"=>Configure::read('VISIT-RECEIVE'));

            // check the diciplinary action for privilages =====
            $punishmentData = $this->InPrisonPunishment->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "InPrisonPunishment.prisoner_id"    => $prisoner_id,
                    "InPrisonPunishment.is_trash"       => 0,
                    "InPrisonPunishment.status"         => 'Final-Approved',
                    "InPrisonPunishment.internal_punishment_id"         => 6,
                    "'".date("Y-m-d")."' between InPrisonPunishment.punishment_start_date and InPrisonPunishment.punishment_end_date"
                ),
                "order"         => array(
                    "InPrisonPunishment.id"    => "desc",
                ),
            ));
            // echo "<pre>";print_r($punishmentData);
            $is_punishment = false;
            if(isset($punishmentData['InPrisonPunishment']['privilege_id']) && $punishmentData['InPrisonPunishment']['privilege_id']!=''){
                if(in_array(Configure::read("VISIT-RECEIVE"),explode(",", $punishmentData['InPrisonPunishment']['privilege_id']))){
                    $type = "receive";
                    $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("VISIT-RECEIVE"));
                    $is_punishment = true;
                }
            }

            
            $privilegeData = $this->Privilege->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "Privilege.stage_id"  => $stageData['StageHistory']['stage_id'],
                    "Privilege.is_trash"     => 0,
                )+$conditionPri,
            ));
            // debug($privilegeData);
            if(isset($privilegeData['Privilege']['interval_week']) && $privilegeData['Privilege']['interval_week']!=''){
                $visitersData = $this->Visitor->find("first", array(
                    "recursive"     => -1,
                    "conditions"    => array(
                        "Visitor.prisoner_no"  => $this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$prisoner_id)),
                        "Visitor.is_trash"     => 0,
                    ),
                    "order"         => array(
                        "Visitor.id"    => "desc",
                    ),
                ));
                //=====================================================
                if($is_punishment){
                    $nextReceiveDate = date('d-m-Y', strtotime($punishmentData['InPrisonPunishment']['punishment_end_date']));

                    if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                        $privilage = array();
                        foreach (explode(",", $punishmentData["InPrisonPunishment"]["privilege_id"]) as $key => $value) {
                            $privilage[] = $this->getName($value,"PrivilegeRight","name");
                        }
                        return "This prisoner receive visiter after ".$nextReceiveDate.". Prisoner has punished by Forfeiture of privileges, restrict for ".implode(", ", $privilage)." till ".$nextReceiveDate;
                    }
                }
                //=====================================================
                if(isset($visitersData) && count($visitersData)>0){
                    $nextReceiveDate = date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($visitersData['Visitor']['date'])));
                    if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                        return "This prisoner receive visiter after ".date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($visitersData['Visitor']['date']))).". Prisoner belongs to ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name").", So the prisoner will be able to receive visiter in interval of ".$privilegeData['Privilege']['interval_week']." weeks";
                    }                    
                }
            }else{
                return "Privilege is not updated for ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name");
            }
        }else{
            return "This prisoner is not in stage system";
        }
    }
    //check pass
   //check pass
    public function checkIfAllowedToVisit(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $data =$this->request->data;
       // debug($data);exit;
        if(isset($this->request->data['prisoner_id']) && $this->request->data['prisoner_id'] != '' ){
            $prisoner_id = $this->request->data['prisoner_id'];
            $natIdType =  $this->request->data['natIdType'];
            $natId =  $this->request->data['natId'];

            $allowed = 'false';
            $prisoner = $this->Prisoner->findById($prisoner_id);
            $prisoner_type_id = $prisoner['Prisoner']['prisoner_type_id'];
            $prison_id = $prisoner['Prison']['id'];
               //echo $prisoner_type_id;exit;
            if(isset($this->request->data['prisoner_id']) && $this->request->data['prisoner_id'] != ''){
                 $this->loadModel('VisitorDay');

                    $visitorDays = $this->VisitorDay->find('first', array(
                        //'recursive'     => -1,
                        'conditions'    => array(
                            'VisitorDay.prison_id'      => $prison_id,
                            'VisitorDay.prisoner_type_id'      => $prisoner_type_id,
                        ),
                    ));
                    $daysAllowed = $visitorDays['VisitorDay']['days'];
                    $daysAllowedArray = explode(',', $daysAllowed);
                   
                    $t=date('d-m-Y');
                    $d = date("D",strtotime($t));
                    $todayDay ='';
                    switch($d) {
                        case 'Sun':$todayDay='Sunday';break;
                        case 'Mon':$todayDay='Monday';break;
                        case 'Tue':$todayDay='Tuesday';break;
                        case 'Wed':$todayDay='Wednesday';break;
                        case 'Thu':$todayDay='Thursday';break;
                        case 'Fri':$todayDay='Friday';break;
                        case 'Sat':$todayDay='Saturday';break;
                            break;
                    }
                    //echo $todayDay;
                    //debug($daysAllowedArray);exit;
                    //debug($todayDay);exit;
                    foreach ($daysAllowedArray as $key => $value) {
                        if($value == $todayDay){
                            $allowed = 'true';break;
                        }
                    }

                if($prisoner['Prisoner']['prisoner_type_id'] == Configure::read('CONVICTED')){
                    if($allowed == 'true'){
                        $stageData = $this->getStageValidate($prisoner_id);
                        if($stageData != ''){
                            $allowed='stageNa';
                        }
                    }
                }
                    //check pass
                if($allowed != 'true'){
                    if(isset($natIdType) && $natIdType != '' && isset($natId) && $natId != ''){
                        $this->loadModel('VisitorPass');
                        $this->loadModel('PassVisitor');
                        $visitorPasses = $this->VisitorPass->find('all', array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'VisitorPass.prison_id'      => $prison_id,
                                'VisitorPass.prisoner_id'      => $prisoner_id,
                            ),
                         ));
                        foreach ($visitorPasses as $pass) {
                            $passVisitors = $this->PassVisitor->find('all',array(
                                    'recursive'     => -1,
                                        'conditions'    => array(
                                            'PassVisitor.pass_id'      => $pass['VisitorPass']['id'],
                                            'PassVisitor.is_trash'      => 0,

                                        ), 
                                ));

                            foreach ($passVisitors as $pass_visitor) {
                                    //debug($pass_visitor);
                                    if($pass_visitor['PassVisitor']['nat_id_type'] == $natIdType && $pass_visitor['PassVisitor']['nat_id'] ==  $natId){
                                        if($pass['VisitorPass']['is_valid'] == 1){
                                            if($pass['VisitorPass']['is_suspended'] == 1){
                                                $visitDate = date('d-m-Y',strtotime($pass['VisitorPass']['suspended_date']));
                                            }else{
                                                $visitDate = date('d-m-Y',strtotime($pass['VisitorPass']['valid_form']));
                                            }

                                             if($visitDate ==  date('d-m-Y') ){
                                                $allowed ='true';
                                                } 
                                        }
                                        
                                           
                                        }
                                }
                                //exit;
                        }
                        //debug($visitorPasses);exit;
                    }
                }

                
                
            }


            //debug($natIdType);exit;
            $this->loadModel('BlacklistedVisitor');
            if($allowed == 'true'){
                $blacklist = $this->BlacklistedVisitor->find('all',array(
                    'recursive' => -1,
                    'conditions'    => array(
                                'BlacklistedVisitor.prison_id'      => $prison_id,
                                'BlacklistedVisitor.visitor_id_type'      => $natIdType,
                                'BlacklistedVisitor.visitor_id_no'      => $natId,
                                'BlacklistedVisitor.status'      => "Approved",
                            ),
                ));
                //debug($blacklist);exit;
                if(count($blacklist) > 0 ){
                    $allowed ="false";
                    echo 'Blacklisted Visitor';
                }else{
                    echo 'allowed';
                }
            }else if($allowed == 'stageNa'){
                echo $stageData; 
            }
            else{
                echo 'not allowed';
            }
        }else{
            echo 'Select Prisoner';
        }
        
        exit;
        
    }


     public function checkIfAllowedToVisitBackendCheck($prisoner_id,$natIdType,$natId){
        //$this->layout = 'ajax';
        $this->loadModel('Prisoner');
        

        $allowed = 'false';
        $prisoner = $this->Prisoner->findById($prisoner_id);
        $prisoner_type_id = $prisoner['Prisoner']['prisoner_type_id'];
        $prison_id = $prisoner['Prison']['id'];
       //echo $prisoner_type_id;exit;
        if($prisoner_id != ''){
             $this->loadModel('VisitorDay');

                $visitorDays = $this->VisitorDay->find('first', array(
                    //'recursive'     => -1,
                    'conditions'    => array(
                        'VisitorDay.prison_id'      => $prison_id,
                        'VisitorDay.prisoner_type_id'      => $prisoner_type_id,
                    ),
                ));
                $daysAllowed = $visitorDays['VisitorDay']['days'];
                $daysAllowedArray = explode(',', $daysAllowed);
               
                $t=date('d-m-Y');
                $d = date("D",strtotime($t));
                $todayDay ='';
                switch($d) {
                    case 'Sun':$todayDay='Sunday';break;
                    case 'Mon':$todayDay='Monday';break;
                    case 'Tue':$todayDay='Tuesday';break;
                    case 'Wed':$todayDay='Wednesday';break;
                    case 'Thu':$todayDay='Thursday';break;
                    case 'Fri':$todayDay='Friday';break;
                    case 'Sat':$todayDay='Saturday';break;
                        break;
                }
                //echo $todayDay;
                //debug($daysAllowedArray);exit;
                //debug($todayDay);exit;
                foreach ($daysAllowedArray as $key => $value) {
                    if($value == $todayDay){
                        $allowed = 'true';break;
                    }
                }

                if($prisoner['Prisoner']['prisoner_type_id'] == Configure::read('CONVICTED')){
                    if($allowed == 'true'){
                        $stageData = $this->getStageValidate($prisoner_id);
                        if($stageData != ''){
                            $allowed='stageNa';
                        }
                    }
                }

                //check pass
                if($allowed != 'true'){
                    if(isset($natIdType) && $natIdType != '' && isset($natId) && $natId != ''){
                        $this->loadModel('VisitorPass');
                        $this->loadModel('PassVisitor');

                        $visitorPasses = $this->VisitorPass->find('all', array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'VisitorPass.prison_id'      => $prison_id,
                                'VisitorPass.prisoner_id'      => $prisoner_id,
                            ),
                         ));
                        foreach ($visitorPasses as $pass) {
                            $passVisitors = $this->PassVisitor->find('all',array(
                                    'recursive'     => -1,
                                        'conditions'    => array(
                                            'PassVisitor.pass_id'      => $pass['VisitorPass']['id'],
                                            'PassVisitor.is_trash'      => 0,
                                        ), 
                                ));

                            foreach ($passVisitors as $pass_visitor) {

                                    if($pass_visitor['PassVisitor']['nat_id_type'] == $natIdType && $pass_visitor['PassVisitor']['nat_id'] ==  $natId){
                                        if($pass['VisitorPass']['is_valid'] == 1){
                                            if($pass['VisitorPass']['is_suspended'] == 1){
                                                $visitDate = date('d-m-Y',strtotime($pass['VisitorPass']['suspended_date']));
                                            }else{
                                                $visitDate = date('d-m-Y',strtotime($pass['VisitorPass']['valid_form']));
                                            }

                                             if($visitDate ==  date('d-m-Y') ){
                                                $allowed ='true';
                                                } 
                                        }
                                        
                                           
                                        }
                                }
                        }
                        //debug($visitorPasses);exit;
                    }
                }

                

        }
        //debug($natIdType);exit;
        $this->loadModel('BlacklistedVisitor');
        if($allowed == 'true'){
            $blacklist = $this->BlacklistedVisitor->find('all',array(
                'recursive' => -1,
                'conditions'    => array(
                            'BlacklistedVisitor.prison_id'      => $prison_id,
                            'BlacklistedVisitor.visitor_id_type'      => $natIdType,
                            'BlacklistedVisitor.visitor_id_no'      => $natId,
                            'BlacklistedVisitor.status'      => "Approved",
                        ),
            ));
            //debug($blacklist);exit;
            if(count($blacklist) > 0 ){
                $allowed ="false";
                return 2;
            }else{
                if($allowed=='stageNa'){
                    return 3;
                }else{
                    return 1;
                }
            }
        }
        else{
            return 0;
        }
        
    }

    public function submitCanteenFood(){
        $this->layout = 'ajax';
        $this->loadModel('CanteenFoodItem');
        $data = $this->request->data;

        $visitor_id =$data['Visitor']['visitor_id'];
        foreach ($data['CanteenFoodItem'] as $food) {
            if($food['food_item'] != '' && $food['quantity'] != ''){
                    
                $canteenfoodItem['CanteenFoodItem']['visitor_id'] =  $visitor_id;
                $canteenfoodItem['CanteenFoodItem']['food_item'] =  $food['food_item'];
                $canteenfoodItem['CanteenFoodItem']['quantity'] =  $food['quantity'];
                $this->CanteenFoodItem->saveAll($canteenfoodItem);

                $this->Session->write('message_type','success');
                $this->Session->write('message','Canteen Food Items Saved Successfully !');
            }
        }
        echo 'success';
        exit;
    }

}