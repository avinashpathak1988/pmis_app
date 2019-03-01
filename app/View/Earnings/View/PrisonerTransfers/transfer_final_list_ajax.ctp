<?php
if(is_array($datas) && count($datas)>0){
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'                => 'PrisonerTransfers',
            'action'                    => 'transferFinalListAjax',
            'prisoner_no'               => $prisoner_no,
            'date_from'                 => $date_from,
            'date_to'                   => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer'         => $escorting_officer,
            'status'                    => $status,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "transferFinalListAjax/prisoner_no:$prisoner_no/date_from:$date_from/date_to:$date_to/transfer_to_station_id:$transfer_to_station_id/escorting_officer:$escorting_officer/status:$status";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
    $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
    ?>
    </div>
</div>
<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Process</button>
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','');">Discharge</button> -->
    <?php
}
?>
<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Verify</button>
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','');">Discharge</button> -->
    <?php
}
?>
<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Approve</button>
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','');">Discharge</button> -->
    <?php
}
?>
<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Approve</button>
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','');">Discharge</button> -->
    <?php
}
?>
<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Approve</button>
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','');">Discharge</button> -->
    <?php
}
}
?>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
        <!-- <th><?php //echo $this->Form->input('checkbox', array('type'=>'checkbox','label'=>false,'id'=>"selectAll",'class' => 'checkboxbutton','value'=>'all')); ?></th> -->                
        <th>S No.</th>                
        <th>Prisoner Number</th>
        <th>Name</th>
        <th>Offence</th>
        <th>Age</th>
        <th>Sentence length</th>
        <th>DOC (date of conviction)</th>
        <th>EPD</th>
        <th>Prisoner Type</th>
        <th>Origin Station</th>
        <th>Destination Station</th>
        <th>Escorting Team</th>
        <th>Date Of Transfer</th>
        <?php
        if(!isset($is_excel)){
        ?> 
        <th>Reason</th>
        <th>Transfer Type</th>
        <th>Status</th>
        <th>Action</th>
        <?php
        }
        ?>             
        </tr>
    </thead>
    <tbody>
        <?php 
        $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
        foreach($datas as $data){  
            $uuid = $data["Prisoner"]["uuid"];
            ?>
            <tr>
                <td><?php echo $rowCnt; ?></td>
                <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
                <td><?php echo $data['Prisoner']['first_name']; ?></td>
                <td>
                    <?php
                    echo $funcall->getPrisonerOffence($data['Prisoner']['id']);
                    ?> 
                </td>
                <td><?php echo $data['Prisoner']['age']; ?></td>

                <td> <?php
                $sentanceDetails = '';
                $lpd = (isset($data['Prisoner']['sentence_length']) && $data['Prisoner']['sentence_length']!='') ? json_decode($data['Prisoner']['sentence_length']) : array();
                    $remission = array();
                    if(isset($lpd) && count((array)$lpd)>0){
                        foreach ($lpd as $key => $value) {
                            if($key == 'days'){
                                $remission[2] = $value." ".$key;
                            }
                            if($key == 'years'){
                                $remission[0] = $value." ".$key;
                            }
                            if($key == 'months'){
                                $remission[1] = $value." ".$key;
                            }                        
                        }
                        ksort($remission);
                        $sentanceDetails = implode(", ", $remission); 
                    }     
                    echo $sentanceDetails;       
                ?></td>
                <td><?php echo date("d-m-Y", strtotime($data['Prisoner']['doc'])); ?></td>
                <td><?php echo date("d-m-Y", strtotime($data['Prisoner']['epd'])); ?></td>
                <td>  <?php echo $funcall->getName($data['Prisoner']['prisoner_type_id'],"PrisonerType","name");?></td>
                <td><?php echo $data['Prison']['name'];?></td>
                <td><?php echo $data['ToPrison']['name'];?></td>
                <td><?php echo $data['EscortTeam']['name'];?></td>
               
                <td><?php echo date('d-m-Y', strtotime($data['PrisonerTransfer']['transfer_date'])); ?></td>
                <td><?php echo $data['PrisonerTransfer']['reason'];?></td>
                <td><?php echo $data['PrisonerTransfer']['regional_transfer']." Regional";?></td>
                <td>
                    <?php
                    if(!isset($is_excel)){
                    ?>
                    <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-mini btn-link"><?php echo $data['PrisonerTransfer']['discharge_status'];?></a>      
                    <!-- Modal -->
                    <div id="myModal<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Details</h4>
                          </div>
                          <div class="modal-body">    
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Physical Property<?php //echo $req; ?> :</label>
                                    <div class="controls uradioBtn">
                                    <?php
                                    $physicalPropertyList = $funcall->getTransferPropertyDetails($data["PrisonerTransfer"]["id"]);
                                    if(isset($physicalPropertyList) && is_array($physicalPropertyList) && count($physicalPropertyList)>0){
                                    ?>
                                    <table class="table table-bordered data-table table-responsive">
                                        <tr>
                                            <th>S No.</th>
                                            <th>Particulers</th>
                                            <th>Quentity</th>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        foreach ($physicalPropertyList as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $funcall->getName($value['PrisonerTransferPhysicalProperty']['item_id'],"Propertyitem","name"); ?></td>
                                            <td><?php echo $value['PrisonerTransferPhysicalProperty']['quantity']; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <?php
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Cash Property<?php //echo $req; ?> :</label>
                                    <div class="controls uradioBtn">
                                    <?php
                                    $cashPropertyList = $funcall->getTransferCashDetails($data["PrisonerTransfer"]["id"]);
                                    if(isset($cashPropertyList) && is_array($cashPropertyList) && count($cashPropertyList)>0){
                                    ?>
                                    <table class="table table-bordered data-table table-responsive">
                                        <tr>
                                            <th>S No.</th>
                                            <th>Currancy</th>
                                            <th>Amount</th>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        foreach ($cashPropertyList as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $funcall->getName($value['PrisonerTransferCashProperty']['currency_id'],"Currency","name"); ?></td>
                                            <td><?php echo $value['PrisonerTransferCashProperty']['amount']; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <?php
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Other Details<?php //echo $req; ?> :</label>
                                    <div class="controls uradioBtn">
                                    <table class="table table-bordered data-table table-responsive">
                                        <tr>
                                            <th>Status</th>
                                            <td><?php echo $data['PrisonerTransfer']['discharge_status']; ?></td>
                                        </tr>  
                                        <?php
                                        if ($data['PrisonerTransfer']['discharge_status']=='Saved') {
                                             ?>
                                             <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_remark']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Reviewed') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_reviewed_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_reviewed_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_review_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Review Reject') {
                                            ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_reviewed_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_reviewed_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_review_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Approved') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_approved_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_approved_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_approved_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Final Reject') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_rejected_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_rejected_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_approved_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        elseif ($data['PrisonerTransfer']['discharge_status']=='Higher Approved') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_final_approved_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_final_approved_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_final_approved_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Higher Reject') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_rejected_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_rejected_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_final_approved_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Comm Reject') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_rejected_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_rejected_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_comm_approved_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['discharge_status']=='Comm Approved') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['discharge_comm_approved_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['discharge_comm_approved_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['discharge_comm_approved_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?> 
                                    </table>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>

                      </div>
                    </div>
                    <?php
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if(!isset($is_excel)){
                    ?>
                    <?php 
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['discharge_status'] == 'Draft'){
                        ?>
                        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-sm singleBot">Process</a>
                        <?php
                        // echo $this->Form->button('Process', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning btn-sm singleBot','tabcls'=>'next','type'=>'button',"data-toggle"=>"modal","data-target"=>"verify".$data["PrisonerTransfer"]["id"])); 
                        //,'onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');"
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['PrisonerTransfer']['discharge_status'] == 'Saved'){
                        ?>
                        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-sm singleBot">Verify</a>
                        <?php
                        //echo $this->Form->button('Verify', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data['PrisonerTransfer']['discharge_status'] == 'Reviewed'){
                        ?>
                        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-mini singleBot">Approve</a>
                        <?php
                        // echo $this->Form->button('Approve', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                    }

                    /// popup for approving transfer
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE') && $data['PrisonerTransfer']['discharge_status'] == 'Approved'){
                        ?>
                        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-sm singleBot">Approve</a>
                        <?php
                        // echo $this->Form->button('Approve', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                    }

                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') && $data['PrisonerTransfer']['discharge_status'] == 'Higher Approved'){
                        ?>
                        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-sm singleBot">Approve</a>
                        <?php
                        // echo $this->Form->button('Approve', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                    }
                    /// =========================================
                    ?>
                    <!-- Verify Modal START -->
                    <div id="verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                    <h4 class="modal-title">Prisoner Verification</h4>
                                </div>
                                <div class="modal-body">
                                    <?php echo $this->Form->create('VerifyPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/verifyPrisoner','id'=>'verifyPrisoner'.$data["PrisonerTransfer"]["id"]));?>
                                    <?php echo $this->Form->input('uuid',array('type'=>'hidden','id'=>'prisoner_uuid'.$data["PrisonerTransfer"]["id"]));?>
                                    <?php echo $this->Form->input('verify_id', array('type'=>'hidden','id'=>'verifyId'.$data["PrisonerTransfer"]["id"],'value'=>$data["PrisonerTransfer"]["id"]))?>  
                                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                    echo $this->Form->input('status', array('type'=>'hidden','id'=>'status'.$data["PrisonerTransfer"]["id"],'value'=>'Saved'));
                                    }else{
                                    echo $this->Form->input('status', array('type'=>'hidden','id'=>'status'.$data["PrisonerTransfer"]["id"],'value'=>''));
                                        }?>
                                                                         
                                    <?php 
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'displayStatus'.$data["PrisonerTransfer"]["id"],'value'=>'Process'));
                                    }else{
                                        echo $this->Form->input('status', array('type'=>'hidden','id'=>'displayStatus'.$data["PrisonerTransfer"]["id"],'value'=>''));
                                        }?>                                        
                                    <div class="row" style="padding-bottom: 14px;">
                                        <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Physical Property<?php //echo $req; ?> :</label>
                                                <div class="controls uradioBtn">
                                                <?php
                                                $physicalPropertyList = $funcall->getPropertyDetails($data["PrisonerTransfer"]["prisoner_id"]);
                                                if(isset($physicalPropertyList) && is_array($physicalPropertyList) && count($physicalPropertyList)>0){
                                                ?>
                                                <table class="table table-bordered data-table table-responsive">
                                                    <tr>
                                                        <th>S No.</th>
                                                        <th>Particulers</th>
                                                        <th>Quentity</th>
                                                    </tr>
                                                    <?php
                                                    $i = 1;
                                                    foreach ($physicalPropertyList as $key => $value) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $funcall->getName($value['PhysicalPropertyItem']['item_id'],"Propertyitem","name"); ?></td>
                                                        <td><?php echo $value['PhysicalPropertyItem']['quantity']; ?></td>
                                                    </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </table>
                                                <?php
                                                }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Cash Property<?php //echo $req; ?> :</label>
                                                <div class="controls uradioBtn">
                                                <?php
                                                $cashPropertyList = $funcall->getCashPropertyDetails($data["PrisonerTransfer"]["prisoner_id"]);
                                                if(isset($cashPropertyList) && is_array($cashPropertyList) && count($cashPropertyList)>0){
                                                ?>
                                                <table class="table table-bordered data-table table-responsive">
                                                    <tr>
                                                        <th>S No.</th>
                                                        <th>Currancy</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                    <?php
                                                    $i = 1;
                                                    foreach ($cashPropertyList as $key => $value) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $funcall->getName($key,"Currency","name"); ?></td>
                                                        <td><?php echo $value; ?></td>
                                                    </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </table>
                                                <?php
                                                }
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        echo $this->Form->input('earning', array('label'=>false,'class'=>'earning'.$data["PrisonerTransfer"]["id"],'type'=>'hidden','value'=>0));
                                        ?>
                                        <?php }else{?>
                                            <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $verification_type = array();
                                                    $default = array();
                                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
                                                        $verification_type = array('Reviewed'=>'Verify','Review Reject'=>'Reject');
                                                        $default = array("default"=>"Reviewed");
                                                    }
                                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                                        $verification_type = array('Approved'=>'Approve','Final Reject'=>'Reject');    
                                                        $default = array("default"=>"Approved");
                                                    }

                                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
                                                        $verification_type = array('Higher Approved'=>'Approve','Higher Reject'=>'Reject');    
                                                        $default = array("default"=>"Higher Approved");
                                                    }

                                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
                                                        $verification_type = array('Comm Approved'=>'Approve','Comm Reject'=>'Reject');    
                                                        $default = array("default"=>"Comm Approved");
                                                    }
                                                    
                                                     echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type')+$default); 
                                                    ?>
                                                    <div style="clear:both;"></div>
                                                    <div class="error-message" id="verification_type_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Remark :</label>
                                                <div class="controls uradioBtn">
                                                   <?php echo $this->Form->input('verify_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'verify_remark'.$data["PrisonerTransfer"]["id"],'rows'=>3,'required'=>false));?>
                                                   <div style="clear:both;"></div>
                                                    <div class="error-message" id="verification_message_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                    <div class="form-actions" align="center" style="background:#fff;">
                                        <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','onclick'=>'verifyFunction('.$data["PrisonerTransfer"]["id"].')'))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                </div>
                            </div>
                        </div>
                    </div>                       
                    <!-- Verify Modal END -->
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <?php $rowCnt++;
        }?>
    </tbody>
</table>

<?php  
echo $this->Js->writeBuffer();  
}else{
?>
    ...
<?php    
}
if(!isset($is_excel)){
?>   

<script type="text/javascript">
    $(document).ready(function(){
       $('.allBot').prop('disabled', true);
       $('.allBot').hide();
       $('#selectAll').click(function (e) {
            $('#verifyId').val('');
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });
    });
    $('.checkboxbutton').click(function(){
        var cnt = 0;
        $('.checkboxbutton').each(function() {
            if($(this).is(':checked')){
                cnt = cnt + 1;
            }            
        });
        if(cnt > 1){
            $('.allBot').prop('disabled', false);
            $('.allBot').show();
            $('.singleBot').prop('disabled', true);
            $('.singleBot').hide();
        }else{
            $('.allBot').prop('disabled', true);
            $('.allBot').hide();
            $('#verifyId').val('');
            $('#ModelNameProperty').prop('checked', false);
            $('#ModelNameEarning').prop('checked', false);
            $('.singleBot').prop('disabled', false);
            $('.singleBot').show();
        }
    });


    
</script>
<?php
}
?>