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
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        'before'                      => '$("#lodding_image").show();',
        'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'Lodgers',
            'action'                    => 'indexAjax',
            'prisoner_id'               => $prisoner_id,
            'status'=>$status,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "indexAjax/prisoner_id:$prisoner_id/status:$status";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
?>
    </div>
</div>

<?php 
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = Configure::read('REVIEW');
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Lodgers/index'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin: :3px 1px;"><?php echo $btnName;?></button> 
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
        <?php
        if(!isset($is_excel)){
          ?>
           <?php if ($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')) {
       
      } else{?>
        <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
          <?php } ?>
          <?php
        }
          ?>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
            <th><?php echo $this->Paginator->sort('Prisoner_no'); ?></th>
            <th><?php echo $this->Paginator->sort('Prisoner Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Origin Station'); ?></th>
            <th><?php echo $this->Paginator->sort('Destination Station'); ?></th>
            <th><?php echo $this->Paginator->sort('Lodging'); ?></th>
            <th><?php echo $this->Paginator->sort('Reason'); ?></th>
            <?php if(!isset($is_excel)) { ?>
            <th style="text-align: left;">View Details</th>
            <?php } ?>
            <?php if ($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')) {
       
      } else{?>
            <th style="text-align: left;">Status</th>
            <?php } ?>
            <?php if(!isset($is_excel)) { ?>
            <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){ 
              ?>
            <th style="text-align: left;">Action</th>

              <?php

          } else{?>
            <th style="text-align: left;">Action</th>
            <?php } ?>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    // debug($data);
    $display_status = Configure::read($data['Lodger']['status']);
    $prisonerDetails = $funcall->getPrisonerDetails($data['Lodger']['prisoner_id']);
?>
        <tr>
        <?php
        if(!isset($is_excel)){
          ?>
           <?php if ($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')) {
       
          } else {?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['Lodger']['status'] == 'Draft') && $data['Lodger']['ward_id']!='')
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Lodger']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['Lodger']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Lodger']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['Lodger']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Lodger']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php } ?>
          <?php
          }
          ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $prisonerDetails["Prisoner"]["prisoner_no"]?> </td>
            <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td> 
            <td><?php echo ucwords(h($funcall->getName($data['Lodger']['original_prison_id'],"Prison","name"))); ?>&nbsp;</td>
            <td><?php echo ucwords(h($funcall->getName($data['Lodger']['destination_prison_id'],"Prison","name"))); ?>&nbsp;</td>
            <td><?php echo ucwords(h(date('d-m-Y h:i A', strtotime($data['Lodger']['in_date'])))); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Lodger']['reason'])); ?>&nbsp;</td>
            <td>
                <?php if(!isset($is_excel)) { ?>
                <!-- Trigger the modal with a button -->
                    <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['Lodger']['id']; ?>">View Details</button>

                    <!-- Modal -->
                    <div id="myModal<?php echo $data['Lodger']['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Details</h4>
                                </div>
                                <div class="modal-body" id="show_details<?php echo $data['Lodger']['id']; ?>">
                                   
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Items</th>
                                                <th>Quantity </th>
                                                <th>Weight </th>
                                                <th>Property Type </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(isset($data['LodgerPrisonerItem']) && is_array($data['LodgerPrisonerItem']) && count($data['LodgerPrisonerItem'])>0){
                                            foreach ($data['LodgerPrisonerItem'] as $key => $value) {
                                                ?>                                                
                                                <tr>
                                                    <td><?= $key+1 ?></td>
                                                    <td><?php echo $funcall->getName($value['item_type'],"Item","name");?></td>
                                                    <td><?= $value['quantity'] ?></td>
                                                    <td><?= $value['weight'] ?></td>
                                                    <td><?= $value['property_type'] ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Cash details</th>
                                                <th>Amount </th>
                                                <th>Currency</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(isset($data['LodgerPrisonerCashItem']) && is_array($data['LodgerPrisonerCashItem']) && count($data['LodgerPrisonerCashItem'])>0){
                                            foreach ($data['LodgerPrisonerCashItem'] as $key => $value) {
                                                ?>                                                
                                                <tr>
                                                    <td><?= $key+1 ?></td>
                                                    <td><?= $value['cash_details'] ?></td>
                                                    <td><?= $value['pp_amount'] ?></td>
                                                   
                                                    <td><?php echo $funcall->getName($value['pp_cash'],"Currency","name");?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    if(isset($data['Lodger']['ward_id']) && $data['Lodger']['ward_id']!=''){
                                    ?>
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Ward Name</th>
                                                <td><?php echo $funcall->getName($data['Lodger']['ward_id'],"Ward","name");  ?></td>
                                            </tr>
                                            <tr>
                                                <th>Cell No.</th>
                                                <td><?php echo $funcall->getName($data['Lodger']['ward_cell_id'],"WardCell","cell_no")  ?></td>
                                            </tr>
                                            <tr>
                                                <th>Cell Name</th>
                                                <td><?php echo $funcall->getName($data['Lodger']['ward_cell_id'],"WardCell","cell_name")  ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <?php
                                	}
                                  ?>
                                  <?php
                                  if(isset($data['Lodger']['place_of_recapture']) && $data['Lodger']['place_of_recapture']!=''){
                                  ?>
                                  <table class="table table-responsive table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Ward Name</th>
                                              <td><?php echo $funcall->getName($data['Lodger']['ward_id'],"Ward","name");  ?></td>
                                          </tr>
                                          <tr>
                                              <th>Cell No.</th>
                                              <td><?php echo  $data['Lodger']['place_of_recapture']; ?></td>
                                          </tr>
                                          <tr>
                                              <th>Cell Name</th>
                                              <td><?php echo $data['Lodger']['place_of_recapture'];  ?></td>
                                          </tr>
                                      </thead>
                                  </table>
                                  <?php
                                }
                                  ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </td>
            <?php if ($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')) {
       
            } else{?>
            <td>
            <?php if($data["Lodger"]['status'] == 'Draft')
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
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $display_status;?></a>
              <?php 
            }?>
          </td>
          <?php } ?>
          <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
            <td>
             <?php if($data['Lodger']['ward_id']=='') { ?>
                <!-- Trigger the modal with a button -->
                    <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModalWard<?php echo $data['Lodger']['id']; ?>">Update Ward</button>

                    <!-- Modal -->
                    <div id="myModalWard<?php echo $data['Lodger']['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Details</h4>
                                </div>
                                <div class="modal-body" id="show_details<?php echo $data['Lodger']['id']; ?>">
                                   
                                    <table class="table table-bordered data-table table-responsive">
                                      <tbody>
                                          <tr>
                                            <td>Ward</td>
                                            <td><?php 
                                            echo $this->Form->input("prisoner_id.".$data['Lodger']['id'],array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>$data['Lodger']['prisoner_id']));
                                            echo $this->Form->input("ward_id.".$data['Lodger']['id'],array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$funcall->showWard($prisonerDetails["Prisoner"]["gender_id"],''), 'empty'=>'-- Select Ward --','required','title'=>'Please select ward','onchange'=>'showCell(this.value,'.$data['Lodger']['id'].')'));
                                            ?></td>
                                          </tr>
                                          <tr>
                                              <td>Cell No.</td>
                                              <td><?php echo $this->Form->input('ward_cell_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), "id"=>'ward_cell_id'.$data['Lodger']['id'],'empty'=>'-- Select Cell --','title'=>'Please select ward'));?></td>
                                          </tr>
                                          <tr>
                                              <td colspan="2" align="center" style="text-align: center;"><input type="button" class="btn btn-success" value="Update" onclick="updateWard('Terminate',<?php echo $data['Lodger']['id']?>)"></td>
                                          </tr>
                                          </tbody>
                                      </table>                                      
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            </td>
            <?php
          } else{?>
        

          <td>
            <?php 
                // if($isEdit == 1)
                // {
                if($data["Lodger"]['status'] == 'Draft'){
                    echo $this->Form->create('LodgerEdit',array('url'=>'/Lodgers/add','admin'=>false));
                    echo $this->Form->end();
                    echo $this->Form->create('LodgerEdit',array('url'=>'/Lodgers/add','admin'=>false,"id"=>"form".$data['Lodger']['id']));
                    echo $this->Form->input('id',array('type'=>'hidden','value'=> $data["Lodger"]['id']));
                    echo $this->Form->input('type',array('type'=>'hidden','value'=> 'Edit',"id"=>"type".$data['Lodger']['id']));
                    echo $this->Form->button('<i class="icon-edit"></i>',array('label'=>'Edit','type'=>'button','class'=>'btn btn-primary','div'=>false, 'onclick'=>"showDelete(".$data['Lodger']['id'].",'Edit')")); 
                // }
                // if($isDelete == 1){
                    echo $this->Form->button('<i class="icon-trash"></i>',array('label'=>'Delete','type'=>'button','class'=>'btn btn-danger','div'=>false, 'onclick'=>"showDelete(".$data['Lodger']['id'].",'Delete')")); 
                    echo $this->Form->end();
                }
                // }
            ?>
          </td>
          <?php  }?>
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
echo $this->Js->writeBuffer();
?>    
<?php if(@$file_type != 'pdf') { ?>
<script>
$(document).ready(function(){
  
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkall = $('input[id="checkAll"]:checked').length;
          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAll').attr('checked', false);
            $('#forwardBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardBtn').show();
          }
          else 
          {
            $('#forwardBtn').hide();
          }
        });
});
var btnName = '<?php echo $btnName;?>';
var isModal = '<?php echo $isModal;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName+"?",
            btnName,
            'Cancel',
            MyYesFunction,
            MyNoFunction
        );
}

function MyYesFunction() {
  if(isModal == 1)
    {
      $('#verify').modal('show');
    }
    else 
    {
      $('#ApprovalProcessFormIndexAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}

function showDelete(hiddenId,type){
    var hiddenId = hiddenId;
    $("#type"+hiddenId).val(type);
    AsyncConfirmYesNo(
            "Are you sure want to "+type+"?",
            "Yes",
            'Cancel',
            function(){
                $("#form"+hiddenId).submit();
            },
            function(){
            }
        );
    
}
</script>
<?php } ?>