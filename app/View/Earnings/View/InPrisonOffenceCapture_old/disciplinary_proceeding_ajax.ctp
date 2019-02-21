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
// debug($datas); exit;
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
            'action'                    => 'disciplinaryProceedingAjax',
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
    $exUrl = "disciplinaryProceedingAjax/prisoner_id:$prisoner_id/status:$status";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
echo '&nbsp;&nbsp;';
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
//echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/InPrisonOffenceCapture/punishmentList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button> 
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
<table id="districtTable" class="table table-bordered table-striped">
    <thead>
        <tr>
        
            <th>SL#</th>
            <th>Plea Type</th>
            <th>Date Of hearing</th>
            <th>Evident Summary</th>
            <th style="text-align: left;">Status</th>
            <?php
            if(!isset($is_excel) && ($isAccess == 1)){
              ?> 
              <th colspan="2">Action</th>
              <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
<?php 
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['DisciplinaryProceeding']['status']);
  $disciplinary_proceeding_id = $data['DisciplinaryProceeding']['id'];
  //debug($data);
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['DisciplinaryProceeding']["plea_type"] ;?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['DisciplinaryProceeding']["date_of_hearing"]));?> </td>
            <td><?php echo $data['DisciplinaryProceeding']["summary"] ;?></td>
            <td>
            <?php if($data['DisciplinaryProceeding']['status'] == 'Draft')
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
          <td style="text-align: center;">
            <?php 
            if($data['DisciplinaryProceeding']['status']=='Draft'){

              echo $this->Form->create('DisciplinaryProceedingEdit',array('url'=>'/inPrisonOffenceCapture/index/'.$uuid.'#disciplinaryProceedings','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $disciplinary_proceeding_id)); ?>
                <?php echo $this->Form->button("<i class='icon-edit'></i>", array('label'=>false,'class'=>'btn btn-primary','div'=>false,'escape'=>false)); 
                echo $this->Form->end();
                echo '&nbsp;';

                echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteDisciplinaryProceeding('$disciplinary_proceeding_id');"));
            }
            ?>
        </td>
        </tr>
<?php
$rowCnt++;
}
?>
    </tbody>
</table>
<?php
//echo $this->Form->end();
}else{
?>
...
<?php    
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
var btnName = '<?php echo (isset($btnName) && $btnName!='') ? $btnName : '';?>';
var isModal = '<?php echo (isset($isModal) && $isModal!='') ? $isModal : '';?>';
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
      $('#ApprovalProcessFormPunishmentListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>