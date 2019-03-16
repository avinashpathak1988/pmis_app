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
            'controller'            => 'prisoners',
            'action'                => 'transferIncomingListAjax',
            'prisoner_no'   => $prisoner_no,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer' => $escorting_officer,
            'status' => $status,
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
    $exUrl = "transferIncomingListAjax/prisoner_no:$prisoner_no/date_from:$date_from/date_to:$date_to/transfer_to_station_id:$transfer_to_station_id/escorting_officer:$escorting_officer/status:$status";
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
    <!-- <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Acknowledge</button> -->
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','');">Discharge</button> -->
    <?php
}
?>
<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-warning allBot">Review</button>
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
}
?>
<table class="table table-bordered data-table table-responsive">
    <thead>
		<tr>
        <?php 
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
            ?>
		<th><?php echo $this->Form->input('checkbox', array('type'=>'checkbox','label'=>false,'id'=>"selectAll",'class' => 'checkboxbutton')); ?></th>  
        <?php
        }
        ?>              
		<th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
		<th>
			<?php
			echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransfers','action' => 'transferListAjax', 'prisoner_no' => '', 'prisoner_name' => '')));
			?>
		</th>
		<th>Origin Station</th>
		<th>Destination Station</th>
		<th>Escorting Team</th>
		<th>Date Of Transfer</th>
        <th>Reason</th>
		<th>Status</th>
        <?php
        if(!isset($is_excel)){
        ?> 
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
            <?php 
            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE')){
                ?>
            	<td>
            		<?php
			        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['instatus'] == 'Draft'){
			            echo $this->Form->input('PrisonerAttendance.'.$rowCnt.'.prisoner_id', array(
			              'type'=>'checkbox', 'value'=>$data['PrisonerTransfer']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkboxbutton",
			              'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));
			        }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['PrisonerTransfer']['instatus'] == 'Saved'){
                        echo $this->Form->input('PrisonerAttendance.'.$rowCnt.'.prisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['PrisonerTransfer']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkboxbutton",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')  && $data['PrisonerTransfer']['instatus'] == 'Reviewed'){
                        echo $this->Form->input('PrisonerAttendance.'.$rowCnt.'.prisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['PrisonerTransfer']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkboxbutton",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));
                    }
			        ?>
            	</td>
                <?php
                }
                ?>
            	<td><?php echo $rowCnt; ?></td>
            	<td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
		        <td><?php echo $data['Prison']['name'];?></td>
		        <td><?php echo $data['ToPrison']['name'];?></td>
		        <td><?php echo $data['EscortTeam']['name'];?></td>
		        <td><?php echo date('d-m-Y', strtotime($data['PrisonerTransfer']['transfer_date'])); ?></td>
                <td><?php echo $data['PrisonerTransfer']['reason'];?></td>
		        <td>
                    <?php
                    if(!isset($is_excel)){
                    ?>
                    <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-link"><?php echo $data['PrisonerTransfer']['instatus'];?></a> 
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
                                            <td><?php echo $data['PrisonerTransfer']['instatus']; ?></td>
                                        </tr>  
                                        <?php
                                        if ($data['PrisonerTransfer']['instatus']=='Saved') {
                                            ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['rcv_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['rcv_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['received_remark']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['instatus']=='Reviewed') {
                                             ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['in_reviewed_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['in_reviewed_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['review_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['instatus']=='Review Reject') {
                                            ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['rejected_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['rejected_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['review_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['instatus']=='Approved') {
                                            ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['out_approved_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['out_approved_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['final_remarks']; ?></td>
                                            </tr>
                                            <?php
                                        }elseif ($data['PrisonerTransfer']['instatus']=='Final Reject') {
                                            ?>
                                            <tr>
                                                <th>Action By</th>
                                                <td><?php echo $funcall->getName($data['PrisonerTransfer']['rejected_by'],"User","name"); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dated</th>
                                                <td><?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['rejected_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo $data['PrisonerTransfer']['final_remarks']; ?></td>
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
		        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['instatus'] == 'Draft'){
                    ?>
                    <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModalRcv<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning">Process</a>      
                    <!-- <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php //echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-sm singleBot">Process</a> -->
                    <?php
                    // echo $this->Form->button('Acknowledge', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
		     	}
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['PrisonerTransfer']['instatus'] == 'Saved'){
                    echo $this->Form->button('Review', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')  && $data['PrisonerTransfer']['instatus'] == 'Reviewed'){
                    echo $this->Form->button('Approve', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                }
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
                                echo $this->Form->input('statusaaa', array('type'=>'hidden','id'=>'displayStatus'.$data["PrisonerTransfer"]["id"],'value'=>'Acknowledged'));
                                }elseif ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) {
                                    echo $this->Form->input('statusaaa', array('type'=>'hidden','id'=>'displayStatus'.$data["PrisonerTransfer"]["id"],'value'=>'verify'));
                                }elseif ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')) {
                                    echo $this->Form->input('statusaaa', array('type'=>'hidden','id'=>'displayStatus'.$data["PrisonerTransfer"]["id"],'value'=>'Acknowledgeda'));
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
                                                $options = array();
                                                if(isset($data["PrisonerTransfer"]["discharge_close"]) && $data["PrisonerTransfer"]["discharge_close"]!=''){
                                                    foreach (explode(",", $data["PrisonerTransfer"]["discharge_close"]) as $key => $value) {
                                                        $options[$value] = $value;
                                                    }
                                                }
                                                
                                                if(count($options)>0){
                                                    $selected = array();
                                                    if(isset($options) && is_array($options) && count($options)>0){
                                                        foreach ($options as $optionskey => $optionsvalue) {
                                                            $selected[] = $optionskey;
                                                        }  
                                                    }
                                                    // $selected = array(1, 3);
                                                    echo $this->Form->input('property', array('label'=>false,'multiple' => 'checkbox','class'=>'property'.$data["PrisonerTransfer"]["id"],'div'=>false, 'options' => $options,'selected' => $selected)); //, 
                                                }else{
                                                    echo "No any physical property";
                                                }
                                                
                                                ?>
                                                <div style="clear:both;"></div>
                                                <div class="error-message" id="verification_type_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label">Cash Property<?php //echo $req; ?> :</label>
                                            <div class="controls uradioBtn">
                                                <?php
                                                $options = array();
                                                if(isset($data["PrisonerTransfer"]["discharge_cash_close"]) && $data["PrisonerTransfer"]["discharge_cash_close"]!=''){
                                                    foreach (explode(",", $data["PrisonerTransfer"]["discharge_cash_close"]) as $key => $value) {
                                                        $options[$value] = $value;
                                                    }
                                                }
                                                if(count($options)>0){
                                                    $selected = array();
                                                    if(isset($options) && is_array($options) && count($options)>0){
                                                        foreach ($options as $optionskey => $optionsvalue) {
                                                            $selected[] = $optionskey;
                                                        }  
                                                    }
                                                    echo $this->Form->input('cash', array('label'=>false,'multiple' => 'checkbox','class'=>'cash'.$data["PrisonerTransfer"]["id"],'div'=>false, 'options' => $options,'selected' => $selected)); //, 'selected' => $selected
                                                }else{
                                                    echo "No any cash physical property";
                                                }
                                                
                                                ?>
                                                <div style="clear:both;"></div>
                                                <div class="error-message" id="verification_type_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php /* ?>
                                    <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label">Earning<?php //echo $req; ?> :</label>
                                            <div class="controls uradioBtn">
                                                <?php
                                                echo $options = $data["PrisonerTransfer"]["discharge_earning_close"];
                                                // $selected = array(1, 3);
                                                echo $this->Form->input('earning', array('label'=>false,'class'=>'earning'.$data["PrisonerTransfer"]["id"],'type'=>'hidden','value'=>$data["PrisonerTransfer"]["discharge_earning_close"])); //, 'selected' => $selected
                                                ?>
                                                <div style="clear:both;"></div>
                                                <div class="error-message" id="verification_type_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php */ ?>
                                    <?php
                                    echo $this->Form->input('earning', array('label'=>false,'class'=>'earning'.$data["PrisonerTransfer"]["id"],'type'=>'hidden','value'=>$data["PrisonerTransfer"]["discharge_earning_close"]));
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
                                                    $verification_type = array('Reviewed'=>'Reviewed','Review Reject'=>'Reject');
                                                    $default = array("default"=>"Reviewed");
                                                }
                                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                                    $verification_type = array('Approved'=>'Approve','Final Reject'=>'Reject');    
                                                    $default = array("default"=>"Approved");
                                                }
                                                
                                                 echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type'.$data["PrisonerTransfer"]["id"])+$default); 
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
                                    <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn'.$data["PrisonerTransfer"]["id"],'onclick'=>'verifyFunction('.$data["PrisonerTransfer"]["id"].')'))?>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>                       
                <!-- Verify Modal END -->
                    <!-- Modal -->
                <?php
                }
                ?>
                
                <!-- Modal -->
                <div id="myModalRcv<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Details</h4>
                            </div>
                            <?php
                            echo $this->Form->create('Gatepass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Gatepasses/gatepassList','id'=>'PropertyData'.$data["PrisonerTransfer"]["id"]));
                            echo $this->Form->input('id',array('type'=>'hidden','value'=>$data["PrisonerTransfer"]["id"]));
                            echo $this->Form->input('request_from',array('type'=>'hidden','value'=>'Transfer'));
                            echo $this->Form->input('is_property_received',array('type'=>'hidden','value'=>1));
                            ?>
                            <div class="modal-body">                                    
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Physical Property<?php //echo $req; ?> :</label>
                                        <div class="controls uradioBtn">
                                        <?php
                                        $physicalPropertyList = $this->requestAction('/PrisonerTransfers/getTransferPropertyDetails/'.$data["PrisonerTransfer"]["id"]);
                                        $propertyExit = false;
                                        if(isset($physicalPropertyList) && is_array($physicalPropertyList) && count($physicalPropertyList)>0){
                                            $propertyExit = true;
                                        ?>
                                        <table class="table table-bordered data-table table-responsive">
                                            <tr>
                                                <th>S No.</th>
                                                <th>Particulers</th>
                                                <th>Quentity</th>
                                                <th>Gate Rcv. Quentity</th>
                                                <th>Rcv. Quentity</th>
                                            </tr>
                                            <?php
                                            $i = 1;
                                            foreach ($physicalPropertyList as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $funcall->getName($value['PrisonerTransferPhysicalProperty']['item_id'],"Propertyitem","name"); ?></td>
                                                <td><?php echo $value['PrisonerTransferPhysicalProperty']['quantity']; ?></td>
                                                <td><?php echo $value['PrisonerTransferPhysicalProperty']['rcv_quentity']; ?></td>
                                                <td>
                                                    <?php echo $this->Form->input('PrisonerTransferPhysicalProperty.'.$i.'.id',array('type'=>'hidden','value'=>$value['PrisonerTransferPhysicalProperty']['id']));?>
                                                    <?php echo $this->Form->input('PrisonerTransferPhysicalProperty.'.$i.'.quantity',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Quentity','class'=>'form-control span11','required','value'=>$value['PrisonerTransferPhysicalProperty']['rcv_quentity']));?>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                        <?php
                                        }else{
                                            echo "No any physical property found.";
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Cash Property<?php //echo $req; ?> :</label>
                                        <div class="controls">
                                        <?php
                                        $cashPropertyList = $this->requestAction('/PrisonerTransfers/getTransferCashDetails/'.$data["PrisonerTransfer"]["id"]);
                                        
                                        if(isset($cashPropertyList) && is_array($cashPropertyList) && count($cashPropertyList)>0){
                                            $propertyExit = true;
                                        ?>
                                        <table class="table table-bordered table-responsive">
                                            <tr>
                                                <th>S No.</th>
                                                <th>Currancy</th>
                                                <th>Amount</th>
                                                <th>Gate Rcv. Quentity</th>
                                                <th>Rcv. Quentity</th>
                                            </tr>
                                            <?php
                                            $i = 1;
                                            foreach ($cashPropertyList as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $funcall->getName($value['PrisonerTransferCashProperty']['currency_id'],"Currency","name"); ?></td>
                                                <td><?php echo $value['PrisonerTransferCashProperty']['amount']; ?></td>
                                                <td><?php echo $value['PrisonerTransferCashProperty']['rcv_amount']; ?></td>
                                                <td>
                                                    <?php echo $this->Form->input('PrisonerTransferCashProperty.'.$i.'.id',array('type'=>'hidden','value'=>$value['PrisonerTransferCashProperty']['id']));?>
                                                    <?php echo $this->Form->input('PrisonerTransferCashProperty.'.$i.'.amount',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Amount','class'=>'form-control span11','required','value'=>$value['PrisonerTransferCashProperty']['rcv_amount']));?>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                        <?php
                                        }else{
                                            echo "No any cash property found.";
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Ward Name :</label>
                                        <div class="controls uradioBtn">
                                            <?php
                                            echo $this->Form->input("ward_id",array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$funcall->showWard($data['Prisoner']['gender_id'],''), 'empty'=>'-- Select Ward --','required','title'=>'Please select ward','onchange'=>'showCell(this.value,'.$data["PrisonerTransfer"]["id"].')'));
                                            ?>
                                            <div style="clear:both;"></div>
                                            <div class="error-message" id="verification_message_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Cell No. :</label>
                                        <div class="controls uradioBtn">
                                            <?php echo $this->Form->input('ward_cell_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), "id"=>'ward_cell_id'.$data["PrisonerTransfer"]["id"],'empty'=>'-- Select Cell --','title'=>'Please select ward'));?>
                                            <div style="clear:both;"></div>
                                            <div class="error-message" id="verification_message_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                        </div>
                                    </div>
                                </div>
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
                            <!-- <div class="modal-footer text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <?php //echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','onclick'=>'saveProperty('.$data["PrisonerTransfer"]["id"].')')); ?>
                            </div> -->
                            <div class="form-actions" align="center" style="background:#fff;">
                                <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','onclick'=>'saveProperty('.$data["PrisonerTransfer"]["id"].')'))?>
                            </div>
                            <?php
                                echo $this->Form->end();
                            ?>
                        </div>
                    </div>
                </div>
		        </td>
            </tr>
            <?php $rowCnt++;
        }?>
    </tbody>
</table>

<?php  
echo $this->Js->writeBuffer();  
}else{
echo Configure::read('NO-RECORD');   
}
if(!isset($is_excel)){
?>                    
<script type="text/javascript">
    $(document).ready(function(){
       $('.allBot').prop('disabled', true);
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
            $('.singleBot').prop('disabled', true);
        }else{
            $('.allBot').prop('disabled', true);
            $('#verifyId').val('');
            $('.singleBot').prop('disabled', false);
        }
    });
</script>
<?php
}
?>