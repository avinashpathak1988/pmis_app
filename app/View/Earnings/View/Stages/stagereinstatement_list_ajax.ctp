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
    // if(!isset($is_excel)){
?>
<!-- <div class="row">
    <div class="col-sm-5">
        <ul class="pagination"> -->
<?php
    // $this->Paginator->options(array(
    //     'update'                    => '#stageDemotionDiv',
    //     'evalScripts'               => true,
    //     'before'                    => '$("#lodding_image").show();',
    //     'complete'                  => '$("#lodding_image").hide();',
    //     'url'                       => array(
    //         'controller'            => 'StagesController',
    //         'action'                => 'stagesReinstatementAjax',   
    //     )
    // ));         
    // echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    // echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    // echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    // echo $this->Js->writeBuffer();
?>
        <!-- </ul>
    </div>
    <div class="col-sm-7 text-right" style="padding-top:30px;"> -->
<?php
// echo $this->Paginator->counter(array(
//     'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
// ));
?>
<?php
    // $exUrl = "/stages/stagesReinstatementAjax/prisoner_id:$prisoner_id/uuid:$uuid";
    // $urlExcel = $exUrl.'/reqType:XLS';
    // $urlDoc = $exUrl.'/reqType:DOC';
    // echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    // echo '&nbsp;&nbsp;';
    // echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    <!-- </div>
</div> -->
<?php
   // }

?>  
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Stages/stageReinstatementList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>       
<?php 
//echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Stages/stageReinstatementList'));?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button>
<!-- <button type="submit" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" <?php //if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php //}?>><?php //echo $btnName;?></button> -->
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
        <th>
            <?php echo $this->Form->input('checkAll', array(
                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            ));?>
          </th>
            <th>SL#</th>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>Date of Reinstatement</th>
            <th>Probationary period</th>
            <th>Stage reinstated to </th>
            <th>Reason</th>
            <th>Status</th>
            
<?php
if(!isset($is_excel) && ($isAccess == 1)){
?> 
            <!-- <th>Action</th> -->
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){

      $display_status = Configure::read($data['StageReinstatement']['status']);
?>
        <tr>
        
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['StageReinstatement']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['StageReinstatement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['StageReinstatement']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['StageReinstatement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['StageReinstatement']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['StageReinstatement']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $funcall->getName($data["StageReinstatement"]["prisoner_id"],"Prisoner","prisoner_no"); ?></td>
            <td><?php echo $funcall->getName($data["StageReinstatement"]["prisoner_id"],"Prisoner","fullname"); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data["StageReinstatement"]["reinstatement_date"]));?> </td>
            <td><?php echo $data["StageReinstatement"]["probationary_period"] ?></td>
            <td><?php echo $data["Stage"]["name"] ;?></td>
            
            <td><?php echo $data["StageReinstatement"]["comment"] ;?></td>
            <td>
            <?php if($data["StageReinstatement"]['status'] == 'Draft')
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
           
            
           
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $stagesReinstatement_id   = $data['StageReinstatement']['id'];
            $stagesReinstatement_uuid = $data['StageReinstatement']['uuid'];
            $stage_reinstated_to = $data['StageReinstatement']['stage_reinstated_to'];
            $prisoner_id = $data['StageReinstatement']['prisoner_id'];
?>              

            
<?php
        }
?>
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
echo Configure::read('NO-RECORD');
}
?> 
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

//Dynamic confirmation modal -- START --
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
    $('#ApprovalProcessFormStagereinstatementListAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>              