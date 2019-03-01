<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'InPrisonOffenceCapture',
            'action'                    => 'punishmentListAjax',
            'prisoner_id'               => $prisoner_id,
            'status'                    => $status,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "punishmentListAjax/prisoner_id:$prisoner_id/status:$status";
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
    }
?>
<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?> 
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        
            <th>SL#</th>
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Punishment Date</th>
            <th>Start Date </th>
            <th>End Date</th>
            <th>Details</th>
            <th style="text-align: left;">Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){//debug($data);
  $display_status = ($data['InPrisonPunishment']['status']=='Final-Approved') ? 'Approved' : Configure::read($data['InPrisonPunishment']['status']);
  $prisonerDetails = $funcall->getPrisonerDetails($data['InPrisonPunishment']['prisoner_id']);
?>
    <tr>
        <td><?php echo $rowCnt; ?></td>
        <td><?php echo $prisonerDetails["Prisoner"]["prisoner_no"]?> </td>
        <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td>  
        <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data["InPrisonPunishment"]['punishment_date'])); ?></td>          
        
        <td><?php echo (isset($data["InPrisonPunishment"]["punishment_start_date"]) && $data["InPrisonPunishment"]["punishment_start_date"]!='') ? date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data["InPrisonPunishment"]["punishment_start_date"])) : 'NA'; ?></td>
        <td><?php echo (isset($data["InPrisonPunishment"]["punishment_end_date"]) && $data["InPrisonPunishment"]["punishment_end_date"]!='') ? date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data["InPrisonPunishment"]["punishment_end_date"])) : 'NA'; ?></td>
        
        <td>
          <!-- Trigger the modal with a button -->
          <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['InPrisonPunishment']['id']; ?>">View Details</button>

          <!-- Modal -->
          <div id="myModal<?php echo $data['InPrisonPunishment']['id']; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Details</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-bordered data-table table-responsive">
                      <tbody>
                          <tr>
                            <td>Punishment Type</td>
                            <td><?php echo $data["InternalPunishment"]["name"] ;?></td>
                          </tr>
                          <tr>
                              <td><b>Remarks</b></td>
                              <td><?php echo $data["InPrisonPunishment"]["remarks"] ;?></td>
                          </tr>
                          <tr>
                              <td><b>Punishment Details</b></td>
                              <td>
                                <?php
                                echo (isset($data["InPrisonPunishment"]["deducted_amount"]) && $data["InPrisonPunishment"]["deducted_amount"]!='') ? "Deducted Amount : ".$data["InPrisonPunishment"]["deducted_amount"] : '';
                                if(isset($data["InPrisonPunishment"]["privilege_id"]) && $data["InPrisonPunishment"]["privilege_id"]!=''){
                                    $privilage = array();
                                    foreach (explode(",", $data["InPrisonPunishment"]["privilege_id"]) as $key => $value) {
                                        $privilage[] = $funcall->getName($value,"PrivilegeRight","name");
                                    }
                                    echo "Deducted Privilege : ".implode(", ", $privilage);
                                }
                                if(isset($data["InPrisonPunishment"]["loss_type"]) && $data["InPrisonPunishment"]["loss_type"]!=''){
                                    echo "Loss Type : ".$data["InPrisonPunishment"]["loss_type"]." <br>";
                                    echo (isset($data["InPrisonPunishment"]["duration_month"]) && $data["InPrisonPunishment"]["duration_month"]!='') ? "Duration : ".$data["InPrisonPunishment"]["duration_month"]." Months " : '';
                                    echo (isset($data["InPrisonPunishment"]["duration_days"]) && $data["InPrisonPunishment"]["duration_days"]!='') ? $data["InPrisonPunishment"]["duration_days"]." days" : '';
                                }
                                if(isset($data["InPrisonPunishment"]["demotion_ward_id"]) && $data["InPrisonPunishment"]["demotion_ward_id"]!=''){
                                    echo "Changed Ward : ".$funcall->getName($data["InPrisonPunishment"]["demotion_ward_id"],"Ward","name");
                                }
                                if(isset($data["InPrisonPunishment"]["demotion_stage_id"]) && $data["InPrisonPunishment"]["demotion_stage_id"]!=''){
                                    echo "Changed Stage : ".$funcall->getName($data["InPrisonPunishment"]["demotion_stage_id"],"Stage","name");
                                }
                                ?>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>

        </td>
        
        <td>
        <?php if($data["InPrisonPunishment"]['status'] == 'Draft')
        {
          echo $display_status;
        }
        else 
        {
          $status_info = '<b>Status: </b>'.$display_status.'<br>';
          if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
            $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
          if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
            $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
          ?>
          <a href="javaScript:void(0);" class="pop btn-success btn-mini" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $display_status;?></a>
          <?php 
        }?>
      </td>
      <td>

            <!-- Trigger the modal with a button -->
            <?php if((isset($data['InPrisonPunishmentConfinement'][0]['status']) && $data['InPrisonPunishmentConfinement'][0]['status'] == 'Draft') || (isset($data['InPrisonPunishmentConfinement'][0]['approval_status']) && $data['InPrisonPunishmentConfinement'][0]['approval_status'] == 'Terminate')){?>
            <?php }else{
            ?>
              <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModalOther<?php echo $data['InPrisonPunishment']['id']; ?>">Action</button>
            <?php 
            }
            ?>

          <!-- Modal -->
          <div id="myModalOther<?php echo $data['InPrisonPunishment']['id']; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Details</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-bordered data-table table-responsive">
                      <tbody>
                          <tr>
                            <td>Ward</td>
                            <td><?php 
                            echo $this->Form->input("prisoner_id.".$data['InPrisonPunishment']['id'],array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>$data['InPrisonPunishment']['prisoner_id']));
                            echo $this->Form->input("ward_id.".$data['InPrisonPunishment']['id'],array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$funcall->showWard($prisonerDetails["Prisoner"]["gender_id"]), 'empty'=>'-- Select Ward --','required','title'=>'Please select ward','onchange'=>'showCell(this.value,'.$data['InPrisonPunishment']['id'].')'));
                            ?></td>
                          </tr>
                          <tr>
                              <td>Cell No.</td>
                              <td><?php echo $this->Form->input('ward_cell_id'.$data['InPrisonPunishment']['id'],array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Cell --','title'=>'Please select ward'));?></td>
                          </tr>
                          <tr>
                              <td>&nbsp;</td>
                              <td align="center" colspan="2">
                                <?php if((isset($data['InPrisonPunishmentConfinement'][0]['status']) && $data['InPrisonPunishmentConfinement'][0]['status'] == 'Draft') || (isset($data['InPrisonPunishmentConfinement'][0]['approval_status']) && $data['InPrisonPunishmentConfinement'][0]['approval_status'] == 'Terminate')){?>
          
                                  <?php }else{
                                    if(isset($data['InPrisonPunishmentConfinement'][0]['approval_status']) && $data['InPrisonPunishmentConfinement'][0]['approval_status']=='Halt'){
                                    ?>
                                      <input type="button" class="btn-sm btn-warning btn-mini" value="Continue" onclick="confinementHistory('Continue',<?php echo $data['InPrisonPunishment']['id']?>)">
                                    <?php
                                    }
                                    if(isset($data['InPrisonPunishmentConfinement'][0]['approval_status']) && $data['InPrisonPunishmentConfinement'][0]['approval_status']=='Continue' || !isset($data['InPrisonPunishmentConfinement'][0]['approval_status'])){
                                    ?>
                                      <input type="button" class="btn-sm btn-warning btn-mini" value="Halt" onclick="confinementHistory('Halt',<?php echo $data['InPrisonPunishment']['id']?>)">
                                    <?php
                                    }
                                    ?>
                                      <input type="button" class="btn-sm btn-danger btn-mini" value="Terminate" onclick="confinementHistory('Terminate',<?php echo $data['InPrisonPunishment']['id']?>)">
                                  <?php 
                                  }
                                  ?>
                            </td>
                          </tr>
                      </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-mini" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>

      
      </td>
    </tr>
<?php
$rowCnt++;
}
?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
?>
...
<?php    
}
?>    