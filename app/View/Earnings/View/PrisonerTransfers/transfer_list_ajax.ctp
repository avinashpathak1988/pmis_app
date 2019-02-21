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
            'action'                => 'transferListAjax',
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
    $exUrl = "transferListAjax/prisoner_no:$prisoner_no/date_from:$date_from/date_to:$date_to/transfer_to_station_id:$transfer_to_station_id/escorting_officer:$escorting_officer/status:$status";
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
    <button type="submit" tabcls="next" id="saveBtn" class="btn btn-danger allBot" onclick="forwardTransfer('','Draft');");" title="Remove From List"><i class='icon-trash'></i></button>
    <button type="submit" tabcls="next" id="saveBtn" title="Transfer Now" class="btn btn-warning allBot" onclick="forwardTransfer('','Process');");"><i class='icon-check'></i></button>
    <?php
}
if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-success allBot">Verify</button>
    <?php
}
if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
    ?>
   <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-success allBot">Approve</button>
    <?php
}
?>
<?php
}
?>
<table class="table table-bordered data-table table-responsive">
    <thead>
		<tr>
        <?php
        if(!isset($is_excel)){
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
		<th>Escorting Officer</th>
		<th>Date Of Transfer Out</th>
        <th>Reason</th>
        <th>Transfer Type</th>
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
            $status = Configure::read('STATUS');
            ?>
            <tr>
                <?php
                if(!isset($is_excel)){
                ?>
            	<td>
            		<?php
			        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Saved'){
			            echo $this->Form->input('PrisonerAttendance.'.$rowCnt.'.prisoner_id', array(
			              'type'=>'checkbox', 'value'=>$data['PrisonerTransfer']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkboxbutton",
			              'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));
			        }

                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Process'){
                        echo $this->Form->input('PrisonerAttendance.'.$rowCnt.'.prisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['PrisonerTransfer']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkboxbutton",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));
                    }

                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Reviewed'){
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
                <td><?php echo ucwords($data['PrisonerTransfer']['regional_transfer'])." Regional";?></td>
		        <td>
                    <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal<?php echo $data["PrisonerTransfer"]["id"]; ?>" class="btn btn-link"><?php echo $status['outgoing'][$data['PrisonerTransfer']['status']];?></a>      
                </td>
		        <td>
		        <?php
                if(!isset($is_excel)){ 
		        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Saved'){
		        	echo $this->Form->button("<i class='icon-trash'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger btn-sm singleBot', 'onclick'=>"javascript:forwardTransfer('".$data['PrisonerTransfer']['id']."','Draft');",'title'=>"Remove from list"));
                    echo "&nbsp;&nbsp;"; 
                    echo $this->Form->button("<i class='icon-check'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot', 'onclick'=>"javascript:forwardTransfer('".$data['PrisonerTransfer']['id']."','Process');",'title'=>"Transfer Now")); 
		     	}
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Process'){
                    echo $this->Form->button('Verify', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-mini btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Reviewed'){
                    echo $this->Form->button('Verify', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-mini btn-warning singleBot','onclick'=>"javascript:verifyPrisonerSetData('".$data["PrisonerTransfer"]["id"]."');")); 
                }
		        ?>

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
                            <?php

                            if($data['PrisonerTransfer']['status']=='Draft'){
                                ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['created'])); ?><br>
                                <?php
                            }elseif ($data['PrisonerTransfer']['status']=='Saved') {
                                 ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['final_save_by'],"User","name"); ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['final_save_date'])); ?><br>
                                <b>Remarks :</b> <?php echo $data['PrisonerTransfer']['remarks']; ?><br>
                                <?php
                            }elseif ($data['PrisonerTransfer']['status']=='Process') {
                                 ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['final_save_by'],"User","name"); ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['created'])); ?><br>
                                <b>Remarks :</b> <?php echo $data['PrisonerTransfer']['remarks']; ?><br>
                                <?php
                            }elseif ($data['PrisonerTransfer']['status']=='Reviewed') {
                                 ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['out_reviewed_by'],"User","name"); ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['out_reviewed_date'])); ?><br>
                                <b>Remarks     :</b>  <?php echo nl2br($data['PrisonerTransfer']['review_remarks']); ?>
                                <?php
                            }elseif ($data['PrisonerTransfer']['status']=='Review Reject') {
                                 ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['discharge_reviewed_by'],"User","name"); ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['created'])); ?><br>
                                <b>Remarks     :</b>  <?php echo nl2br($data['PrisonerTransfer']['review_remarks']); ?>
                                <?php
                            }elseif ($data['PrisonerTransfer']['status']=='Approved') {
                                 ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['out_approved_by'],"User","name"); ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['out_approved_date'])); ?><br>
                                <b>Remarks     :</b>  <?php echo nl2br($data['PrisonerTransfer']['final_remarks']); ?>
                                <?php
                            }elseif ($data['PrisonerTransfer']['status']=='Final Reject') {
                                 ?>
                                <b>Status :</b> <?php echo $data['PrisonerTransfer']['status']; ?><br>
                                <b>Action By :</b> <?php echo $funcall->getName($data['PrisonerTransfer']['out_approved_by'],"User","name"); ?><br>
                                <b>Date :</b> <?php echo date("d-m-Y",strtotime($data['PrisonerTransfer']['out_approved_date'])); ?><br>
                                <b>Remarks     :</b>  <?php echo nl2br($data['PrisonerTransfer']['final_remarks']); ?>
                                <?php
                            }
                            ?> 
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
?>                    
<script type="text/javascript">
    $(document).ready(function(){
       $('.allBot').prop('disabled', true);
       $('.allBot').hide();
       $('#selectAll').click(function (e) {$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);});
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
            $('.singleBot').prop('disabled', false);
            $('.singleBot').show();
        }
    });
</script>