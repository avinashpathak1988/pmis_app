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
            'controller'            => 'PrisonerTransfers',
            'action'                => 'transferCancelListAjax',
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
    $exUrl = "transferCancelListAjax/prisoner_no:$prisoner_no/date_from:$date_from/date_to:$date_to/transfer_to_station_id:$transfer_to_station_id/escorting_officer:$escorting_officer/status:$status";
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
        <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
        <th>
            <?php
            echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransfers','action' => 'transferFinalListAjax','prisoner_no'   => $prisoner_no,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer' => $escorting_officer,
            'status' => $status)));
            ?>
        </th>
        <th>Origin Station</th>
        <th>Destination Station</th>
        <th>Escorting Team</th>
        <th>
           <?php
            echo $this->Paginator->sort('PrisonerTransfer.transfer_date','Date Of Transfer',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransfers','action' => 'transferFinalListAjax','prisoner_no'   => $prisoner_no,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer' => $escorting_officer,
            'status' => $status)));
            ?>
        </th>
        <?php
        if(!isset($is_excel)){
        ?> 
        <th>Reason</th>
        <th>Transfer Type</th>
        <th>Details</th>
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
                <td><?php echo $data['Prison']['name'];?></td>
                <td><?php echo $data['ToPrison']['name'];?></td>
                <td><?php echo $data['EscortTeam']['name'];?></td>
                <td><?php echo date('d-m-Y', strtotime($data['PrisonerTransfer']['transfer_date'])); ?></td>
                <td><?php echo $data['PrisonerTransfer']['reason'];?></td>
                <td><?php echo $data['PrisonerTransfer']['regional_transfer']." Regional";?></td>
                <td>
                    <?php
                    if(!isset($is_excel) && $data['PrisonerTransfer']['is_cancel']==1){
                    ?>
                    <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-link">Canceled</a>      
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
                            <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['cancel_by'],"User","name"); ?><br>
                            <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['cancel_date'])); ?><br>
                            <b>Remarks :</b>  <?php echo nl2br($data['PrisonerTransfer']['cancel_remarks']); ?><br>
                           <br>
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
                    
                    if($data['PrisonerTransfer']['is_cancel']==0){
                        ?>
                        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-warning btn-mini singleBot">Cancel</a>
                        <?php
                    }else{

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
                                    <h4 class="modal-title">Discharge Cancellation</h4>
                                </div>
                                <div class="modal-body">
                                    <?php echo $this->Form->create('VerifyPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/verifyPrisoner','id'=>'verifyPrisoner'.$data["PrisonerTransfer"]["id"]));?>
                                    <?php echo $this->Form->input('uuid',array('type'=>'hidden','id'=>'prisoner_uuid'.$data["PrisonerTransfer"]["id"]));?>
                                    <?php echo $this->Form->input('verify_id', array('type'=>'hidden','id'=>'verify_id'.$data["PrisonerTransfer"]["id"],'value'=>$data["PrisonerTransfer"]["id"]))?>  
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden','id'=>'prisoner_id'.$data["PrisonerTransfer"]["id"],'value'=>$data["PrisonerTransfer"]["prisoner_id"]))?>  
                                    <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Remark <?php echo $req; ?>:</label>
                                                <div class="controls uradioBtn">
                                                   <?php echo $this->Form->input('verify_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'cancel_remarks'.$data["PrisonerTransfer"]["id"],'rows'=>3,'required'=>true));?>
                                                   <div style="clear:both;"></div>
                                                    <div class="error-message" id="verification_message_err<?php echo $data["PrisonerTransfer"]["id"]; ?>" style="display:none;">Verification type is required !</div>
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                    <div class="form-actions" align="center" style="background:#fff;">
                                        <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-mini btn-success','onclick'=>'checkTransferCancel('.$data["PrisonerTransfer"]["id"].')'))?>
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