<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<!-- //code for getting the kin details  -->
<?php
$funcall->loadModel('MedicalDeathRecord');     
$prisonerId = $funcall->Prisoner->field("id",array("Prisoner.uuid"=>$prisoner_uuid));    
$deathRecord = $funcall->MedicalDeathRecord->find("count", array(
    "conditions"=> array(
        "MedicalDeathRecord.prisoner_id"=>$prisonerId,
        "MedicalDeathRecord.status"=>"Approved",
        )
    ));


?>
<div class="container-fluid">
    <div class="row-fluid">
    <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoner Property</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                        <ul class="nav nav-tabs" id="tabs">
                            <li><a href="#physical_property" id="physical_property_tab" class="maintab maintab1">Physical Property</a></li>
                            <li><a href="#cash_property" id="cash_property_tab" class="maintab maintab1" onclick="showTransaction();">Cash</a></li>
                        </ul>
                        <div class="tabscontent firsttab">

                            <div id="physical_property">
                          
                                <div class="row-fluid">
                                <div class="span12">
                                    <div class="widget-box collapsible">
                    <div class="widget-title"><span class="icon"> <i class="icon-align-justify" style="color: #000;"></i> </span>
                    <h5>Physical Property List</h5>
                    <a class="" href="#searchPhysicalProperty" data-toggle="collapse" title="Search"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                     <div style="float:right;padding-top: 6px;margin-right: -6px;">
                        <?php 
                        // isset($prisonerKin) && count($prisonerKin)>0 && 
                        // condition is removed based on last feedback
                        if($deathRecord==0){ ?>
                        <?php echo $this->Html->link('Add Incoming Physical Properties',array('controller'=>'properties','action'=>'property/'.$prisoner_uuid.'#physical_property'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                       <?php }else{
                             echo 'This Prisoner is Dead.';
                        } ?>
                        <input type="hidden" value="<?php echo $prisoner_uuid;?>" id="prisoner_uuid">
                        &nbsp;&nbsp;
                    </div>
              </div>

            </div>
            <div id="searchPhysicalProperty" class="collapse" style="height: 0px;">
            <div class="span12">
                        <?php echo $this->Form->create('Property',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                            <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">From Date  :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('propertyfrom_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Select Date ','required'=>false,'id'=>'propertyfrom_date'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                    <div class="control-group">
                                    <label class="control-label">To Date:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('propertyto_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Select Date ','required'=>false,'id'=>'propertyto_date'));?>
                                    </div>
                                </div>
                            </div> 
                        
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Item :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('item_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$propertyItemList, 'empty'=>array(''=>'-- Select Item --'),'required'=>false, 'style'=>'width:92%','id'=>'item_id'));?>
                                    </div>
                                </div>
                            </div>                        
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Bag No. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('bag_no',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11','type'=>'text', 'placeholder'=>'Bag No.','required'=>false));?>
                                    </div>
                                </div>
                            </div> 
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Status Type <?php echo $req; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('status_type', array('type'=>'select','empty'=>'--Select--', 'div'=>false, 'label'=>false, 'style'=>'width:92%', 'options'=>$statusList, 'default'=>'', 'class'=>'form-control span6', 'id'=>'status_type'))?>    
                                    </div>
                                </div>
                            </div> 
                            <div class="span12">
                                <div class="form-actions" align="center">
                                    <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'id'=>'btnsearchphysicalproperty'))?>
                                    <?php echo $this->Form->button('Reset', array('type'=>'reset','class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'resetPhysicalproperty','onclick'=>"resetPhysicalData('PropertyIndexForm')"))?>
                                </div>
                                
                            </div>  
                        </div>
                        <?php echo $this->Form->end();?>

                    </div>
                </div>
                    </div>            
                        <div class="span12">
                            <div class="form-actions" align="center">
                                <!-- <?php echo $this->Form->button('Destroy', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myDestroyModal','id'=>'btnDestroy'))?> -->
                               <!--  <?php echo $this->Form->button('Outgoing', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'data-toggle'=>'modal', 'data-target'=>'#myOutgoingModal','id'=>'btnOutgoing'))?> -->
                                
                            </div>
                        </div>
                                    
                                </div>    
                                <div class="table-responsive" id="listingDiv">

                                </div>
                            </div> 
                            <div id="cash_property">
                                <ul class="nav nav-tabs">
                                    <li><a href="#transaction" id="transaction_tab">Transaction</a></li>
                                    <li><a href="#credit" id="credit_tab">Credit</a></li>
                                    <li><a href="#debit" id="debit_tab">Debit</a></li>
                                </ul>
                                <div class="tabscontent secondtab">
                                    <div id="transaction">
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="widget-box collapsible">
                                                    <div class="widget-title"><span class="icon"> <i class="icon-align-justify" style="color: #000;"></i> </span>
                                                        <h5>Transaction List</h5>
                                                        <a class="" href="#searchTransaction" data-toggle="collapse" title="Search"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                                                    </div>
                                                 </div>
                                                 <div id="searchTransaction" class="collapse" style="height: 0px;">
                                                    <div class="span12">
                                                        <?php echo $this->Form->create('Property',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                                                        <div class="row-fluid">
                                                            <div class="span6">
                                                                <div class="control-group">
         <label class="control-label">From Date :</label>
             <div class="controls">
             <?php echo $this->Form->input('trans_from_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'propertyfrom_date_cash'));?>
                                                                    </div>
                                                                </div>
                                                            </div>
                        <div class="span6">
                         <div class="control-group">
                     <label class="control-label">To Date :</label>
                                    <div class="controls">
                     <?php echo $this->Form->input('trans_to_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'propertyto_date_cash'));?>
                            </div>
         </div>
                    </div> 
                                                        
<div class="span6">
                     <div class="control-group">
                     <label class="control-label">Currency :</label>
                    <div class="controls">
                <?php echo $this->Form->input('currency_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$debitCurrencyList,'empty'=>array('0'=>'-- Select Currency --'),'required'=>false, 'style'=>'width:90%','id'=>'currency_id'));?>
                        </div>
                                    </div>
                 </div>                        
         <div class="span6">
                     <div class="control-group">
                        <label class="control-label">Transaction Type :</label>
                 <div class="controls">
                     <?php $transactionTypeList = array('Credit'=>'Credit','Debit'=>'Debit');
                         echo $this->Form->input('status_type_cash', array('type'=>'select','empty'=>array('0'=>'--Select Transaction Type--'), 'div'=>false, 'label'=>false, 'options'=>$transactionTypeList, 'default'=>'Incoming', 'class'=>'form-control span11', 'id'=>'status_type_cash'))?>
                    </div>
            </div>
                </div>  
        <div class="span12">
        <div class="form-actions" align="center">
         <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false))?>
         <?php echo $this->Form->button('Reset', array('type'=>'reset','class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'resetDebitSearch'))?>
         </div>
    </div>
            </div>
             <?php echo $this->Form->end();?>
    </div>
                </div>
<div class="">
  <div class="table-responsive" id="transactionDiv_cash"></div>
                 </div>
                        </div>
                    </div>
                                    </div>
<div id="credit">
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box collapsible">
            <div class="widget-title"><span class="icon"> <i class="icon-align-justify" style="color: #000;"></i> </span>
                <h5>Credit List</h5>
                <a class="" id="search-function-credit" href="#searchCredit" data-toggle="collapse" title="Search"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                <?php if(isset($canCredit) && $canCredit == 0)
                {?>                                 
                    <div style="float:right;padding-top: 7px;padding-right: 4px;">
                        <?php 
                        if($deathRecord==0){
                            echo $this->Html->link('Add Credit','#creditCash',array('escape'=>false,'class'=>'btn btn-success btn-mini','id'=>'addCredit','data-toggle'=>"collapse"));
                        }
                         ?>
                    </div>
                <?php }
                else {
                    echo '<div style="float:right;padding-top: 7px;padding-right:7px;">Already credit is pending for approval.</div>';
                }?>
                
            </div>
         </div>
        <div id="creditCash" class="collapse" style="<?php if(isset($isCreditEdit) && ($isCreditEdit == 1)){echo 'height: auto;';}else{echo 'height: 0px;';}?>">
             <div class="span12">
            <?php echo $this->Form->create('PhysicalProperty',array('id'=>'cashproperty','class'=>'form-horizontal','url' => '/properties/index/'.$prisoner_uuid.'#credit'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                         <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                        <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date Time<?php echo $req; ?> :</label>
                                    <div class="controls">
                                         <?php echo $this->Form->input('property_date_time',array('div'=>false,'label'=>false,'class'=>'form-control  span11','type'=>'text', 'placeholder'=>'Enter Date Time','required','readonly'=>'readonly','id'=>'property_date_time','value'=>$cdate));?>
                                    </div>
                             </div>
                            <div class="control-group">
                                <label class="control-label">Description<?php echo $req; ?> :</label>
                                    <div class="controls">
                                    <?php echo $this->Form->input('description',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','placeholder'=>'Description','id'=>'description', 'cols'=>30, 'rows'=>3));?>
                                    </div>
                            </div>
                        </div>                             
                        <div class="span6">
                             <div class="control-group">
                                    <label class="control-label">Source<?php echo $req; ?> :</label>
                                <div class="controls">
                                <?php echo $this->Form->input('source',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','placeholder'=>'Enter Source','required','id'=>'source', 'cols'=>30, 'rows'=>3));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php 
                                    echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in2','onclick'=>"start2()"));
                                    ?>
                                    <?php echo $this->Form->input('is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","id"=>"link_biometric_verified2"));?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div> 
                                    <?php echo $this->element('cash-items');?> 
                                                        </div>
                    <div class="form-actions" align="center">
                    <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success right-margin', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to save?')"))?>
                    <?php if(!isset($isCreditEdit) || ($isCreditEdit == 0)){
                        echo $this->Form->button('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger right-margin', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure you want to reset?')"));
                     }?>
                    
                    <?php echo $this->Html->link('Cancel','#creditCash',array('escape'=>false,'class'=>'btn btn-danger','data-toggle'=>"collapse")); ?>
                    </div>
                    <?php echo $this->Form->end();?>
                    </div>
                    </div>
        <div id="searchCredit" class="collapse" style="height: 0px;">
                        <div class="span12">
                <?php echo $this->Form->create('CreditSearch',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                         <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                            <div class="row-fluid">
                                                            <div class="span6">
                                                                <div class="control-group">
            <label class="control-label">From Date :</label>
                        <div class="controls">
             <?php echo $this->Form->input('credit_from_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'credit_from_date'));?>
                                                                    </div>
                                                                </div>
                                                            </div>
            <div class="span6">
            <div class="control-group">
            <label class="control-label">To Date :</label>
            <div class="controls">
             <?php echo $this->Form->input('credit_to_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'credit_to_date'));?>
                </div>
                 </div>
        </div> 
        <div class="span6">
         <div class="control-group">
                <label class="control-label">Currency :</label>
                    <div class="controls">
                    <?php echo $this->Form->input('currency_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$debitCurrencyList,'placeholder'=>'Select Currency','required'=>false, 'multiple'=>'multiple','style'=>'width:90%'));?>
                     </div>
                    </div>
            </div>
                    <div class="control-group">
            <div class="span6">
                        <label class="control-label">Approval Status :</label>
                        <div class="controls">
                <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$approvalStatusList,'required'=>false, 'empty'=>array('0'=>'-- Select Approval Status --'), 'style'=>'width:92%'));?>
                             </div>
            </div>
                </div>
        <div class="span12">
             <div class="form-actions" align="center">
                <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"showCredit();"))?>
                    <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetCreditData('CreditSearchIndexForm')"))?>
            </div>
                    </div>
                    </div>
        <?php echo $this->Form->end();?>
                        </div>
                    </div>
            <div class="">
                        <div class="table-responsive" id="creditCashList"></div>
                                </div>
                                            </div>
                                        </div>
                                    </div>
    <div id="debit">
         <div class="row-fluid">
            <div class="span12">
                <div class="widget-box collapsible">
                    <div class="widget-title"><span class="icon"> <i class="icon-align-justify" style="color: #000;"></i> </span>
                    <h5>Debit List</h5>
                    <a class="" id="debit-search-function" href="#searchDebit" data-toggle="collapse" title="Search"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <?php if(isset($canDebit) && $canDebit == 0)
                    {?>                                 
                        <div style="float:right;padding-top: 7px;padding-right: 4px;">
                            <?php echo $this->Html->link('Add Debit','#debitCash',array('escape'=>false,'class'=>'btn btn-success btn-mini','id'=>'addDebit','data-toggle'=>"collapse")); ?>
                        </div>
                    <?php }
                    else {
                        echo '<div style="float:right;padding-top: 7px;padding-right:7px;">Already debit is pending for approval.</div>';
                    }?>
              </div>
            </div>
        <div id="debitCash" class="collapse" style="<?php if(isset($isDebitEdit) && ($isDebitEdit == 1)){echo 'height: auto;';}else{echo 'height: 0px;';}?>">
            <div class="span12">
             <?php echo $this->Form->create('DebitCash',array('id'=>'debitcashproperty','class'=>'form-horizontal','url' => '/properties/index/'.$prisoner_uuid.'#debit'));
                //debug($this->request->data);
             ?>
             <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
            <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
            <div class="row" style="padding-bottom: 14px;">
                <div class="span6">
                     <div class="control-group">
                         <label class="control-label">Date Time<?php echo $req; ?> :</label>
                            <div class="controls">
                         <?php echo $this->Form->input('debit_date_time',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Date Time','required','readonly'=>'readonly','id'=>'property_date_time', 'value'=>$cdate));?>
                     </div>
            </div>
        </div>
        <div class="span6">
             <div class="control-group">
                <label class="control-label">Currency<?php echo $req; ?> :</label>
                 <div class="controls">
                    <?php echo $this->Form->input('currency_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$debitCurrencyList, 'empty'=>array('0'=>'-- Select Currency --'),'required'=>'required', 'style'=>'width:90%', 'onChange'=>'getTotalBalance();'));?>
                 </div>
            </div>
        </div>  
        <div class="span6">
             <div class="control-group">
                <label class="control-label">Source<?php echo $req; ?> :</label>
                 <div class="controls">
                    <?php 
                    $sourceList=array('PP Cash'=>'PP Cash','Earning'=>'Earning');
                    echo $this->Form->input('source',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$sourceList, 'empty'=>array('0'=>'-- Select Source --'),'required'=>'required', 'style'=>'width:90%','onChange'=>'getTotalBalance();'));?>
                 </div>
            </div>
        </div>
        <div class="span6">
     <div class="control-group">
                     <label class="control-label">Previous Amount<?php echo $req; ?> :</label>
                            <div class="controls">
                    <?php echo $this->Form->input('prev_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Previous Amount','id'=>'prev_amount'));?>
                                     </div>
                        </div>
                        </div>
                <div class="span6">
                 <div class="control-group">
                      <label class="control-label">Debit Amount<?php echo $req; ?> :</label>
                                 <div class="controls">
                    <?php echo $this->Form->input('debit_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Debit Amount','required','readonly'=>'readonly','id'=>'debit_amount', 'onkeyup'=>'getBalanceAmount(this.value);'));?>
            </div>
             </div>
        </div> 
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Balance Amount<?php echo $req; ?> :</label>
            <div class="controls">
            <?php echo $this->Form->input('balance_amount',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Balance Amount','id'=>'balance_amount'));?>
            </div>
        </div>
    </div>
            
           
                <div class="span6" style="display:<?php echo ($deathRecord==1) ? 'block': 'none'; ?>;">
                    <div class="control-group">
                        <label class="control-label">NOK<?php echo $req; ?>  :</label>
                        <div class="controls">
                             <?php 
                             $prisonerKinList = array();
                             $requiredNok = false;
                             if($deathRecord==1){
                                $requiredNok = true;
                                $prisonerKinArr = $funcall->PrisonerKinDetail->find('all',array(
                                      'recursive'     => -1,
                                      'fields'        => array(
                                          'PrisonerKinDetail.id',
                                          'PrisonerKinDetail.first_name',
                                          'PrisonerKinDetail.middle_name',
                                          'PrisonerKinDetail.last_name',
                                      ),
                                      'conditions'    => array(
                                          'PrisonerKinDetail.is_trash'     => 0,
                                           'PrisonerKinDetail.prisoner_id'     => $prisonerId,
                                           'PrisonerKinDetail.status'     => "Approved",
                                      ),
                                      'order'=>array(
                                          'PrisonerKinDetail.id' => 'desc',
                                      )
                                  )); 
                                 
                                 if(isset($prisonerKinArr) && is_array($prisonerKinArr) && count($prisonerKinArr)){
                                    foreach ($prisonerKinArr as $prisonerKinArrKey => $prisonerKinArrValue) {
                                        $prisonerKinList[$prisonerKinArrValue['PrisonerKinDetail']['id']] = $prisonerKinArrValue['PrisonerKinDetail']['first_name']." ".$prisonerKinArrValue['PrisonerKinDetail']['middle_name']." ".$prisonerKinArrValue['PrisonerKinDetail']['last_name'];
                                    }
                                 }
                             }
                             echo $this->Form->input('prisoner_kin_detail_id',array('div'=>false,'label'=>false,'class'=>'form-control span12','type'=>'select','options'=>$prisonerKinList, 'empty'=>'-- Select --','required'=>$requiredNok,'id'=>'prisoner_kin_detail_id','title'=>"Please select kin details"));?>
                        </div>
                    </div>                        
                </div>                        
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php 
                            echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button_in1','onclick'=>"start1()"));
                            ?>
                            <?php echo $this->Form->input('is_biometric_verified',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'placeholder'=>'Cause Of Execution','required','title'=>"Please verifiy from biometric","value"=>"","id"=>"link_biometric_verified1"));?>
                        </div>
                    </div>
                </div>
            <!-- ================================================== -->
            <div class="span6">
                  <div class="control-group">
                      <label class="control-label">Reason<?php echo $req; ?> :</label>
                    <div class="controls">
                   <?php echo $this->Form->input('reason',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control span11','required','id'=>'reason','placeholder'=>'Enter reason', 'cols'=>30, 'rows'=>3));?>
                         </div>
                    </div>
             </div>
             
             <div class="clearfix"></div>  
                <!-- <div class="clearfix"></div>  -->
                    <?php //echo $this->element('cash-items');?> 
                </div>
             <div class="form-actions" align="center">
             <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>'saveDebitCash();'))?>
             <?php if(!isset($isDebitEdit) || ($isDebitEdit == 0)){
                echo $this->Form->button('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure you want to reset?')"));
             }?>
             <?php echo $this->Html->link('Cancel','#debitCash',array('escape'=>false,'class'=>'btn btn-danger ','data-toggle'=>"collapse")); ?>
                         </div>
                        <?php echo $this->Form->end();?>
                        </div>
             </div>
                <div id="searchDebit" class="collapse" style="height: 0px;">
                     <div class="span12">
                        <?php echo $this->Form->create('DebitSearch',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                    <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
        <div class="row-fluid">
                <div class="span6">
                        <div class="control-group">
                             <label class="control-label">From Date :</label>
                                    <div class="controls">
                 <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'debit_from_date'));?>
                                </div>
                        </div>
                     </div>
                    <div class="span6">
                    <div class="control-group">
                             <label class="control-label">To Date :</label>
                            <div class="controls">
                        <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text', 'placeholder'=>'Enter Date ','required','readonly'=>'readonly','id'=>'debit_to_date'));?>
                            </div>
            </div>
            </div> 
                                                        
        <div class="span6">
    <div class="control-group">
             <label class="control-label">Currency :</label>
                         <div class="controls">
        <?php echo $this->Form->input('currency_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$debitCurrencyList, 'multiple'=>'multiple', 'empty'=>array('0'=>'-- Select Currency --'),'required'=>false, 'style'=>'width:90%','id'=>'currency_id'));?>
            </div>
            </div>
                            </div>  
                 <div class="span12">
                <div class="form-actions" align="center">
                                    <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false, 'onclick'=>'showDebit();'))?>
                                    <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger'))?>
                    </div>
                    </div>
                            </div>
                        <?php echo $this->Form->end();?>
                                                    </div>
                                                 </div>
                                                 <div class="">
                                                    <div class="table-responsive" id="debitCashList"></div>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                      
                                </div>
                                <!-- <div class="table-responsive" id="listingDiv_cash">

                                </div> -->
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="biometricModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="height: 40px;">
        <h5 class="modal-title" id="exampleModalLabel" style="float: left;">Biometric Search</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" align="center">
        <?php echo $this->Html->image('finger.gif', array('alt' => '', 'border' => '0')); ?>
        <br />
        <p>Please press finger on biometric</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="testttt" onclick="stop()" class="btn btn-danger" data-dismiss="modal">Stop</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="biometricModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="height: 40px;">
        <h5 class="modal-title" id="exampleModalLabel" style="float: left;">Biometric Search</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" align="center">
        <?php echo $this->Html->image('finger.gif', array('alt' => '', 'border' => '0')); ?>
        <br />
        <p>Please press finger on biometric</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="testttt" onclick="stop1()" class="btn btn-danger" data-dismiss="modal">Stop</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="biometricModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="height: 40px;">
        <h5 class="modal-title" id="exampleModalLabel" style="float: left;">Biometric Search</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" align="center">
        <?php echo $this->Html->image('finger.gif', array('alt' => '', 'border' => '0')); ?>
        <br />
        <p>Please press finger on biometric</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="testttt" onclick="stop2()" class="btn btn-danger" data-dismiss="modal">Stop</button>
      </div>
    </div>
  </div>
</div>
<?php
    $biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'dataCheck'));
?>

<?php  if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')) { ?>
    <script type="text/javascript">
        var allowedWithoutPunch ='true';
    </script>
<?php }else{ ?>
    <script type="text/javascript">
        var allowedWithoutPunch ='false';
    </script>
<?php }  ?>
<script type="text/javascript">
    var timer = null;
/////////////////////////biometricModal/////////////////////////////////////////////
    function start() {
        $('#biometricModal').modal('show');
        tick();
        timer = setTimeout(start, 1000);  
    };
    function startOther() {
        $('#biometricModal').modal('show');
        timer = setTimeout(stopOther, 1000);  
    };
    function tick() {
        $("#link_biometric_button_in").html("Searching...");
        var url = '<?php echo $biometricSearchAjax; ?>';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    startOther();
                    $("#link_biometric_button_in").html("Verified");
                    $("#link_biometric_button_in").attr("onclick","");
                    $("#link_biometric_verified").val(1);
                    $("#link_biometric_button_in").addClass("btn btn-success");
                }
            },
            async:false
        });
    };
    function stop() {
        $('#biometricModal').modal('hide');
        $("#link_biometric_button_in").html("Get Punch");
        $("#link_biometric_button_in").addClass("btn btn-warning");

        clearTimeout(timer);
    };
    function stopOther() {
        $('#biometricModal').modal('hide');
        clearTimeout(timer);
    };
    /////////////////////////biometricModal1/////////////////////////////////////////////
    function start1() {
        $('#biometricModal1').modal('show');
        tick1();
        timer = setTimeout(start1, 1000);  
    };
    function startOther1() {
        $('#biometricModal1').modal('show');
        timer = setTimeout(stopOther1, 1000);  
    };
    function tick1() {
        $("#link_biometric_button_in1").html("Searching...");
        var url = '<?php echo $biometricSearchAjax; ?>';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    startOther1();
                    $("#link_biometric_button_in1").html("Verified");
                    $("#link_biometric_button_in1").attr("onclick","");
                    $("#link_biometric_verified1").val(1);
                    $("#link_biometric_button_in1").addClass("btn btn-success");
                }
            },
            async:false
        });
    };
    function stop1() {
        $('#biometricModal1').modal('hide');
        $("#link_biometric_button_in1").html("Get Punch");
        $("#link_biometric_button_in1").addClass("btn btn-warning");

        clearTimeout(timer);
    };
    function stopOther1() {
        $('#biometricModal1').modal('hide');
        clearTimeout(timer);
    };
/////////////////////////biometricModal2/////////////////////////////////////////////
    function start2() {
        $('#biometricModal2').modal('show');
        tick2();
        timer = setTimeout(start2, 1000);  
    };
    function startOther2() {
        $('#biometricModal2').modal('show');
        timer = setTimeout(stopOther2, 1000);  
    };
    function tick2() {
        $("#link_biometric_button_in2").html("Searching...");
        var url = '<?php echo $biometricSearchAjax; ?>';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    startOther2();
                    $("#link_biometric_button_in2").html("Verified");
                    $("#link_biometric_button_in2").attr("onclick","");
                    $("#link_biometric_verified2").val(1);
                    $("#link_biometric_button_in2").addClass("btn btn-success");
                }
            },
            async:false
        });
    };
    function stop2() {
        $('#biometricModal2').modal('hide');
        $("#link_biometric_button_in2").html("Get Punch");
        $("#link_biometric_button_in2").addClass("btn btn-warning");

        clearTimeout(timer);
    };
    function stopOther2() {
        $('#biometricModal2').modal('hide');
        clearTimeout(timer);
    };
</script>
<?php
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$ajaxUrl = $this->Html->url(array('controller'=>'properties','action'=>'indexAjax'));
$ajaxUrlCash = $this->Html->url(array('controller'=>'properties','action'=>'indexAjaxCash'));
$ajaxUrltransCash = $this->Html->url(array('controller'=>'properties','action'=>'transAjaxCash'));
$ajaxUrlCredit = $this->Html->url(array('controller'=>'properties','action'=>'creditAjax'));
$totalBalanceUrl = $this->Html->url(array('controller'=>'properties','action'=>'getTotalBalance'));
$ajaxUrlDebit = $this->Html->url(array('controller'=>'properties','action'=>'debitAjax'));
?>
<?php
echo $this->Html->scriptBlock("

    var tab_param = '';
    var tabs;
    jQuery(function($) {
        
        tabs = $('.tabscontent.firsttab').tabbedContent({loop: true}).data('api');
        $('.tabscontent.secondtab').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a.maintab').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });
        var current_url      = window.location.href; 
        var action = current_url.split('#'); 

        if(action[1] == 'transaction' || action[1] == 'credit' || action[1] == 'debit')
        {
            $('#cash_property_tab').click();
            $('#'+action[1]+'_tab').click();
        }

        if(action[1] == 'cash_property')
        {
            $('#cash_property_tab').click();
        }
    });
   
",array('inline'=>false));
?>
<script type="text/javascript">

    function getTotalBalance()
    {
        var url ='<?php echo $totalBalanceUrl?>';
        url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val()+ '/currency_id:' + $('#DebitCashCurrencyId').val()+ '/source:' + $('#DebitCashSource').val();
        $.post(url, {}, function(res) {
                $('#prev_amount').val(res);
                $('#debit_amount').val('');
                $('#balance_amount').val('');
                if(parseFloat(res) > 0)
                {
                    $('#debit_amount').removeAttr('readonly','');
                }
                else 
                {
                    $('#debit_amount').attr('readonly','readonly');
                }
        });
    }
    function getBalanceAmount(debit_amount)
    {
        var prev_amount = $('#prev_amount').val();
        var balance_amount = parseFloat(prev_amount)-parseFloat(debit_amount);
        if(balance_amount < 0){
            dynamicAlertBox("Message","Balance is not available.");
            $('#debit_amount').val('');
            $('#debit_amount').focus();
            $('#balance_amount').val('');

        }else{
            $('#balance_amount').val(balance_amount);
        }
        
    }
    $(document).on('click',"#addCredit", function () {
        var collapseClasses = $('#searchCredit').attr('class');
            $('#cashproperty')[0].reset();
        if(collapseClasses.indexOf('in') >= 0){
            $('#searchCredit').removeClass('in');
            $('#searchCredit').css('height','0px');

        }
        //$('#searchCredit').css('display','none');
    });
    $(document).on('click',"#addDebit", function () {
        var collapseClasses = $('#searchDebit').attr('class');
        
            $('#debitcashproperty')[0].reset();
        
        if(collapseClasses.indexOf('in') >= 0){
            $('#searchDebit').removeClass('in');
            $('#searchDebit').css('height','0px');

        }
        //$('#searchCredit').css('display','none');
    });

    
    $(document).on('click',"#btnsearchphysicalproperty", function () { // button name
        if(!($('#PropertyIndexForm').valid()))
        {
           // fdfhfghfghgf
            return false;
        }
         var status_type=$("#status_type").val();
         if(status_type!=""){
            if(status_type=="Incoming" || status_type=="Supplementary Incoming"){
               // $('.outgoing_btn').css('display','block');
                $('#btnDestroy').show();
                  $('#btnOutgoing').show();
                showData();
            }
            else{
                //$('.outgoing_btn').css('display','none');
                $('#btnDestroy').hide();
                  $('#btnOutgoing').hide();
                showData();
            }
        }
        else{
            $('#btnDestroy').show();
                  $('#btnOutgoing').show();
                showData();
        }
    });
$(document).on('click',"#btnsearchcash", function () { // button name

         var status_type=$("#status_type_cash").val();
         var propertyfrom_date=$("#propertyfrom_date").val();
         if(propertyfrom_date!=""){
            var dtRegex = new RegExp("^([0]?[1-9]|[1-2]\\d|3[0-1])-(01|02|03|04|05|06|07|08|09|10|11|12)-[1-2]\\d{3}$", 'i');
            if(!dtRegex.test(propertyfrom_date)){
                alert("Wrong date format");    
            }
         }
         if(status_type!=""){
            if(status_type=="Incoming"){
                $('#btnDestroyCash').show();
                $('#btnOutgoingCash').show();
                showDataCash();
            }
            else{

                $('#btnDestroyCash').hide();
                $('#btnOutgoingCash').hide();
                showDataCash();
            }
        }
        else{
            alert("Please select status type to search")
        }
    });


$(document).ready(function(){

    <?php 
    if(isset($isDebitEdit) && ($isDebitEdit == 1))
    {?>
        $('#debit_tab').click();
    <?php }?>

        /*setTimeout(function () {
        if($('#creditCash').is(":visible")){
            alert('creditCash');
            $('#searchCredit').css('display','none');
    
        }
        if($('#debitCash').is(":visible")){
            alert('debitCash');
           
            $('#searchDebit').css('display','none');
    
        }
       
      }, 1000);*/
    
    $('#btnDestroy').click(function(){
        if($('input[type="checkbox"]:checked').length==0){
            alert('Please check the boxes to Destroy');
            return false;
        }        
    });
    $('#btnOutgoing').click(function(){
        if($('input[type="checkbox"]:checked').length==0){
            alert('Please check the boxes for outgoing');
            return false;
        }        
    });
    <?php if(!isset($isDebitEdit) || ($isDebitEdit == 0))
    {?>
        $("#item_id").select2('val','');
        var currency_id = $('#DebitCashCurrencyId').val();
        getTotalBalance();
        // if(currency_id != '')
        // {
        //     getTotalBalance();
        // }
    <?php }?>

    $("#status_type option[value='']").attr("selected","selected");
    $("#status_type_cash option[value='']").attr("selected","selected");
        showData();
        showDataCash();
        showCommonHeader();
        showTransaction();
        showCredit();
        showDebit();
});
function showData(){       
    var url ='<?php echo $ajaxUrl?>';
    url = url + '/status_type:' + $('#status_type').val();
    url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
    url = url + '/propertyfrom_date:' + $('#propertyfrom_date').val();
    url = url + '/propertyto_date:'+$('#propertyto_date').val();
    url = url + '/item_id:'+$('#item_id').val();
    url = url + '/bag_no:'+$('#PropertyBagNo').val();
    $.post(url, {}, function(res) {
        if (res) {
            $('#listingDiv').html(res);
            $('.child_physical_item').each(function(i, e) {
                if($(this).find('tbody tr').length==0)
                {
                    $(this).closest(".child_tr").prev(".collop").remove();
                    $(this).closest(".child_tr").remove();
                    
                }
            });
            if($("#physicalpropertyidtbl").find('tbody tr').length==0){
                $('#btnDestroy').hide();
                $('#btnOutgoing').hide();
                $('#listingDiv').html('<span style="color:red;">No searched data found!</span>');
            }
        }
    });
}
function showTransaction(){       
    var url ='<?php echo $ajaxUrltransCash?>';
    url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
    $.post(url, {}, function(res) {
            
        if(res.indexOf('table') > -1) {
            $('#transaction').css('display','block');
            $('#transaction_tab').parent().css('display','block');
            $('#transactionDiv_cash').html(res);
        }else{
            $('#transaction').css('display','none');
                $('#transaction_tab').parent().css('display','none');
                $('#transactionDiv_cash').html('');
        }
    });
}
function showCredit(){       
    var url ='<?php echo $ajaxUrlCredit?>';
    url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
    $.post(url, $('#CreditSearchIndexForm').serialize(), function(res) {
        if (res) {
            $('#creditCashList').html(res);
    $('#transaction').css('display','none');

        }
    });
}
function resetCreditData(id){
    $('#'+id)[0].reset();
    $('select').select2({minimumResultsForSearch: Infinity});
    showCredit();
}
function resetPhysicalData(id){
        
    $('#'+id)[0].reset();
    /*$('select').select2({minimumResultsForSearch: Infinity});*/
     
    $('select').select2().select2("val", null);
    
    showDebit();
}
function showDebit(){   

    //alert(1);
    // var url ='<?php echo $ajaxUrlDebit?>';
    // url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
    // $.post(url, {}, function(res) {
    //     if (res) { 
    //         $('#debitCashList').html(res);
    //         $('#transaction').css('display','none');
    //     }
    // });
    // 
    var url ='<?php echo $ajaxUrlDebit?>';
    url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
    $.post(url, $('#DebitSearchIndexForm').serialize(), function(res) {
        if (res) {
            $('#debitCashList').html(res);
            $('#transaction').css('display','none');
        }
    });
}

function showDataCash(){       
    var url ='<?php echo $ajaxUrlCash?>';
    url = url + '/status_type:' + $('#status_type_cash').val();
    url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
    url = url + '/propertyfrom_date:' + $('#propertyfrom_date_cash').val();
    url = url + '/propertyto_date:'+$('#propertyto_date_cash').val();
    url = url + '/amount:'+$('#amount').val();
    url = url + '/currency_id:'+$('#currency_id').val();
    
    $.post(url, {}, function(res) {
        if (res) {
            $('#listingDiv_cash').html(res);
            $('.child_cash').each(function(i, e) {
                if($(this).find('tbody tr').length==0)
                {
                    $(this).closest(".child_cash_tr").prev(".collop").remove();
                    $(this).closest(".child_cash_tr").remove();

                }
            });

            if($("#cashidtbl").find('tbody tr').length==0){
                $('#btnDestroyCash').hide();
                $('#btnOutgoingCash').hide();
                $('#listingDiv_cash').html('<span style="color:red;">No searched data found!</span>');
            }
        }
    });
}
//common header
    function showCommonHeader(){
        var prisoner_id = "<?php echo $prisoner_id; ?>";
        console.log(prisoner_id);  
        var uuid        = "<?php echo $prisoner_uuid; ?>";
        var url         = "<?php echo $commonHeaderUrl; ?>";
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }
</script>
<script type="text/javascript">

$(function(){
    
    $("#cashproperty").validate({
     
      ignore: "",
            rules: {  
                'data[PhysicalProperty][property_date_time]': {
                    required: true,
                },
                'data[PhysicalProperty][source]': {
                    required: true,
                    maxlength:150
                },
                'data[PhysicalProperty][description]': {
                    required: true,
                    maxlength:150
                },
                'data[CashItem][0][amount]': {
                    required: true,
                },
                'data[CashItem][0][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][1][amount]': {
                    required: true,
                },
                'data[CashItem][1][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][2][amount]': {
                    required: true,
                },
                'data[CashItem][2][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][3][amount]': {
                    required: true,
                },
                'data[CashItem][3][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][4][amount]': {
                    required: true,
                },
                'data[CashItem][4][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][5][amount]': {
                    required: true,
                },
                'data[CashItem][5][currency_id]': {
                    required: true,
                },
                
                'data[CashItem][6][amount]': {
                    required: true,
                },
                'data[CashItem][6][currency_id]': {
                    required: true,
                },
                
                'data[CashItem][7][amount]': {
                    required: true,
                },
                'data[CashItem][7][currency_id]': {
                    required: true,
                },
                


                'data[CashItem][8][amount]': {
                    required: true,
                },
                'data[CashItem][8][currency_id]': {
                    required: true,
                },
                

                'data[CashItem][9][amount]': {
                    required: true,
                },
                'data[CashItem][9][currency_id]': {
                    required: true,
                },
                
                
            },
            messages: {
                'data[PhysicalProperty][property_date_time]': {
                    required: "Please choose datetime.",
                },
                'data[PhysicalProperty][source]': {
                    required: "Please enter source.",
                    maxlength:"should be less than 150 characters"
                },
                'data[PhysicalProperty][description]': {
                    required: "Please enter description.",
                    maxlength:"should be less than 150 characters"
                },
                'data[CashItem][0][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][0][currency_id]': {
                    required: "Please select currency.",
                },
                
                'data[CashItem][1][amount]': {

                    required: "Please enter amount.",
                },
                'data[CashItem][1][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][2][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][2][currency_id]': {
                    required: "Please select currency.",
                },
                
                'data[CashItem][3][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][3][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][4][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][4][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][5][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][5][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][6][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][6][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][7][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][7][currency_id]': {
                    required: "Please select currency.",
                },


                'data[CashItem][8][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][8][currency_id]': {
                    required: "Please select currency.",
                },
                

                'data[CashItem][9][amount]': {
                    required: "Please enter amount.",
                },
                'data[CashItem][9][currency_id]': {
                    required: "Please select currency.",
                },
                

            },
               
    });
$.validator.addMethod( 
        "valueNotEquals", 
        function(value, element) { 
            if (element.value <=0){ 
                return false; 
            }else{
                return true; 
            }
        }, 
        "Please select an option." 
    );
$.validator.addMethod( 
        "DebitValid", 
        function(value, element) { 
            var prev_amount = $('#prev_amount').val();
            //alert(prev_amount);
            if (element.value > prev_amount){ 
                return true; 
            }else{
                return true; 
            }
        },
        'Debit amount should be less than Previous Amount.'
         
    );
$("#debitcashproperty").validate({ 
        rules: {  
                'data[DebitCash][reason]': {
                    required: true,
                    maxlength:150
                },
                'data[DebitCash][prev_amount]':{
                     required: true,
                     valueNotEquals:0,
                },
                'data[DebitCash][currency_id]':{
                     required: true,
                     valueNotEquals:0,
                },
                'data[DebitCash][debit_amount]':{
                     required: true,
                     valueNotEquals:0,
                     DebitValid:true
                },
               ' data[DebitCash][is_biometric_verified]':{
                    required:true
                }
            },
            messages: {
                'data[DebitCash][reason]': {
                    required: "Please fill reason.",
                    maxlength:"should be less than 150 characters"
                },
                'data[DebitCash][prev_amount]': {
                    required: "Please fill Balance.",
                    valueNotEquals:"Amount should be greater than 0.",
                },
                'data[DebitCash][currency_id]': {
                    required: "Please select currency.",
                    valueNotEquals:"Please select currency."
                },
                'data[DebitCash][debit_amount]':{
                    required: "Please fill Debit Amount.",
                    valueNotEquals:"Amount should be greater than 0.",
                    DebitValid:"Debit amount should be less than Previous Amount"
                },
                ' data[DebitCash][is_biometric_verified]':{
                    required:"Please Verify from biometric "
                }

            }
});
    $("#PhysicalPropertyPropertyForm").validate({
     
      ignore: "",
            rules: {  
                'data[PhysicalProperty][property_date_time]': {
                    required: true,
                },
                'data[PhysicalProperty][source]': {
                    required: true,
                    maxlength:150
                },
                'data[PhysicalProperty][description]': {
                    required: true,
                    maxlength:150
                },
                'data[PhysicalPropertyItem][0][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][0][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][0][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][0][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][1][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][1][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][1][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][1][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][2][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][2][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][2][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][2][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][3][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][3][bag_no]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][3][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][3][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][4][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][4][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][4][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][4][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][5][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][5][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][5][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][5][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][6][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][6][bag_no]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][6][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][6][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][7][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][7][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][7][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][7][property_type]': {
                    required: true,
                },


                'data[PhysicalPropertyItem][8][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][8][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][8][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][8][property_type]': {
                    required: true,
                },

                'data[PhysicalPropertyItem][9][item_id]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][9][bag_no]': {
                    required: true,
                    maxlength:3
                },
                'data[PhysicalPropertyItem][9][quantity]': {
                    required: true,
                },
                'data[PhysicalPropertyItem][9][property_type]': {
                    required: true,
                },
                
            },
            messages: {
                'data[PhysicalProperty][property_date_time]': {
                    required: "Please choose datetime.",
                },
                'data[PhysicalProperty][source]': {
                    required: "Please enter source.",
                    maxlength:"should be less than 150 characters"
                },
                'data[PhysicalProperty][description]': {
                    required: "Please enter description.",
                    maxlength:"should be less than 150 characters"
                },
                'data[PhysicalPropertyItem][0][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][0][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][0][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][0][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][1][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][1][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][1][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][1][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][2][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][2][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][2][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][2][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][3][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][3][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][3][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][3][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][4][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][4][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][4][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][4][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][5][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][5][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][5][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][5][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][6][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][6][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][6][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][6][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][7][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][7][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][7][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][7][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][8][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][8][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][8][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][8][property_type]': {
                    required: "Please select property type.",
                },

                'data[PhysicalPropertyItem][9][item_id]': {
                    required: "Please select item.",
                },
                'data[PhysicalPropertyItem][9][bag_no]': {
                    required: "Please enter bag no.",
                    maxlength:"Maximum 3 digits allowed"
                },
                'data[PhysicalPropertyItem][9][quantity]': {
                    required: "Please enter quantity.",
                },
                'data[PhysicalPropertyItem][9][property_type]': {
                    required: "Please select property type.",
                },

            },
               
    });



$("#PropertyIndexForm").validate({
     
      ignore: "",
            rules: {  
                'data[Property][bag_no]': {
                    maxlength: 3
                }
            },
            messages: {
                
                'data[Property][bag_no]': {
                    
                    maxlength: "Please enter no more than 3 characters.",
                },
            }, 
    });
    


$("#search-function-credit").click(function(e){
    e.stopPropagation();   
    var creditCashclasses = $('#creditCash').attr('class');
    var searchCreditclasses = $('#searchCredit').attr('class');
    
    if(creditCashclasses.indexOf('in collapse') < 0 ){
        if(searchCreditclasses.indexOf('in collapse') < 0){
            $('#searchCredit').collapse('show');
        }else{
            $('#searchCredit').collapse('hide');
        }
        
    }
});

$("#debit-search-function").click(function(e){
    e.stopPropagation();   
    var debitCashclasses = $('#debitCash').attr('class');
    var searchDebitclasses = $('#searchDebit').attr('class');
    
    if(debitCashclasses.indexOf('in collapse') < 0 ){
        if(searchDebitclasses.indexOf('in collapse') < 0){
            $('#searchDebit').collapse('show');
        }else{
            $('#searchDebit').collapse('hide');
        }
        
    }
});

});
    
function saveDebitCash() {
    AsyncConfirmYesNo(
            "Are you sure want to save?",
            'Save',
            'Cancel',
            function(){
                if(allowedWithoutPunch == "true"){
                    $('#debitcashproperty').submit();
                }else{
                    var punch =$('#link_biometric_verified1').val();
                    //alert(punch);
                    if(punch == '1'){
                        $('#debitcashproperty').submit();
                    }else{
                        alert("Please verify Biometric first");
                    }
                }
            },
            function(){
              
            }
        );
}
</script>

   