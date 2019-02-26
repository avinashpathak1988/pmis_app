<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    
?>  
<?php 
$modelName5 = 'PrisonerSentenceAppeal';
$btnNameAppeal = Configure::read('SAVE');
$isModal5 = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnNameAppeal = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnNameAppeal = Configure::read('REVIEW');
  $isModal5 = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnNameAppeal = Configure::read('APPROVE');
  $isModal5 = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['Prisoner']['uuid'].'#appeal_against_sentence'));
echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'appeal_against_sentence'));?>
<?php if($isModal5 == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }
if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardAppealBtn" class="btn btn-success btn-mini" style="display: none;"><?php echo $btnNameAppeal;?></button>
<?php
}?>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>File No.</th>
            <th>Count</th>
            <th>Type Of Appelant</th>
            <th>Appeal Status</th>
            <th>Court Level</th>
            <th>Court Name</th>
            <th>Date Of Submission</th>
<?php
if(!isset($is_excel)){
?> 
            <!-- <th>Sentence</th> -->
            <th width="15%">Action</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){

      $id = $data['PrisonerSentenceAppeal']['id'];
?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['PrisonerCaseFile']['file_no']; ?></td>
            <!-- <td><?php// echo $data['PrisonerOffence']['offence_no']; ?></td> -->
            <td><?php 
            $team = array();
            if(isset($data['PrisonerSentenceAppeal']['offence_id']) && $data['PrisonerSentenceAppeal']['offence_id']!=''){
              foreach (explode(",", $data['PrisonerSentenceAppeal']['offence_id']) as $key => $value) {
                $team[] = $funcall->getName($value,"PrisonerOffence","offence_no");
              }
              echo implode(", ", $team);
            } 
            ?>&nbsp;</td> 
            <td><?php echo $data['PrisonerSentenceAppeal']['type_of_appeallant']; ?></td>
            <td>
              <?php if($data['PrisonerSentenceAppeal']['appeal_status'] != '')
                echo $data['PrisonerSentenceAppeal']['appeal_status']; 
              else 
                echo 'Appeal Result';?>
            </td>
            <td><?php echo $data['Courtlevel']['name']; ?></td>
            <td><?php echo $data['Court']['name']; ?></td>
            <td><?php if($data['PrisonerSentenceAppeal']['submission_date']!='')echo date('d-m-Y', strtotime($data['PrisonerSentenceAppeal']['submission_date'])); ?></td>
<?php
        if(!isset($is_excel)){
?>              
            <!-- <td> -->
                <?php //echo $this->Form->create('',array('url'=>'/sentence/index/'.$puuid,'admin'=>false));?>
                <?php //echo $this->Form->end(array('label'=>'Sentence','class'=>'btn btn-primary','div'=>false)); ?> 
                <?php //echo $this->Html->link('Sentence',array('controller'=>'sentence','action'=>'/index/'.$puuid),array('escape'=>false,'class'=>'btn btn-success btn-mini', 'target'=>'_blank')); ?>
            <!-- </td> -->
            <td>
                <?php 
                $viewDetail = '<b>File no : </b>'.$data['PrisonerCaseFile']['file_no'].'<br>';
                // $viewDetail .= '<b>Count : </b>'.$data['PrisonerOffence']['offence_no'].'<br>';
                //get sentence details -- START --
                $sentenceData = $funcall->getSentenceDetail($data['PrisonerSentenceAppeal']['offence_id']);
                $sentenceData = (array)json_decode($sentenceData);
                $appeal_case_file_no = $sentenceData['data']->PrisonerCaseFile->case_file_no;
                $appeal_offence = $sentenceData['data']->Offence->name;
                $appeal_sentence = $sentenceData['data']->PrisonerSentence->sentenceData;
                //get sentence details -- END --
                $viewDetail .= '<b>Case File No : </b>'.$appeal_case_file_no.'<br>';
                $viewDetail .= '<b>Offence : </b>'.$appeal_offence.'<br>';
                $viewDetail .= '<b>Sentence : </b>'.$appeal_sentence.'<br>';

                $viewDetail .= "<b>Type Of Appelant : </b>".$data['PrisonerSentenceAppeal']['type_of_appeallant'].'<br>';
                $viewDetail .= "<b>Appeal Status : </b>".$data['PrisonerSentenceAppeal']['appeal_status'].'<br>';
                $viewDetail .= "<b>Court Level : </b>".$data['Courtlevel']['name'].'<br>';
                $viewDetail .= "<b>Court Name : </b>".$data['Court']['name'].'<br>';
                $viewDetail .= "<b>Date of Submission : </b>".date('d-m-Y', strtotime($data['PrisonerSentenceAppeal']['submission_date'])).'<br>';

                if($data['PrisonerSentenceAppeal']['appeal_status'] != 'Notes of appeal')
                {
                  $viewDetail .= "<b>Appeal No : </b>".$data['PrisonerSentenceAppeal']['appeal_no'].'<br>';
                }

                ?>
                <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Sentence Appeal Details" pageBody="<?php echo $viewDetail;?>">
                    <i class="icon-eye-open"></i>
                </a>
                <?php $toCourtResult = $funcall->checkToCourtEntry($data[$modelName5]['id']);
                $appeal_result = $funcall->checkAppealResult($data['PrisonerSentenceAppeal']['case_file_id'],$data['PrisonerSentenceAppeal']['offence_id']);
                $appeal_result2 = $funcall->getAppealResult($data['PrisonerSentenceAppeal']['case_file_id'],$data['PrisonerSentenceAppeal']['offence_id']);
                
                if($data[$modelName5]['status'] == 'Draft'){
                  if($toCourtResult == 0){
                      echo $this->Form->create('  ',array('url'=>'/Prisoners/edit/'.$puuid.'#appeal_against_sentence','admin'=>false));?> 
                      <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                      echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerSentenceAppeal'));
                      ?>
                      <?php echo $this->Form->button('<i class="icon-edit"></i>',array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')"));
                      echo $this->Form->end();?> 
                      <?php echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteOffenceCount('$id');"));
                  }
                }
                if($data[$modelName5]['appeal_status'] == 'Cause List')
                {
                  if($toCourtResult == 0)
                    echo $this->Html->link('Go To Court',array('action'=>'../Courtattendances/index/'.$data['Prisoner']['uuid'].'/'.$data['PrisonerSentenceAppeal']['id'].'#produceToCourt'),array('escape'=>false,'class'=>'btn btn-warning btn-mini'));
                  else 
                  {
                    if($appeal_result > 0 && $appeal_result2 == 0)
                    {
                      echo $this->Html->link('Appeal Result',array('action'=>'../Prisoners/edit/'.$data['Prisoner']['uuid'].'/'.$data['PrisonerSentenceAppeal']['id'].'/'.$appeal_result.'#appeal_against_sentence'),array('escape'=>false,'class'=>'btn btn-warning btn-mini'));
                    }
                  }
                } ?>
                  <?php 
                  if($data['PrisonerSentenceAppeal']['appeal_status'] == '' && $data['PrisonerSentenceAppeal']['status'] != 'Approved')
                  {
                    echo $this->Form->button('Commit', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:commitAppeal('$id');"));
                  }
                  ?> 
            </td>
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
//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php

    $this->Paginator->options(array(
        'update'                    => '#appeal_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'appealAjax',
            'prisoner_id'             => $prisoner_id,

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
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
    $exUrl = "appealAjax/prisoner_id:$prisoner_id";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
//pagination end 
echo $this->Form->end();
echo $this->Js->writeBuffer();

?>
<script>

$(function(){
  <?php if($btnNameAppeal!=Configure::read('SAVE'))
  {?> 
    $("#ApprovalProcessFormAppealAjaxForm").validate({
       
        ignore: ".ignore, .select2-input",
              rules: {  
                  'data[ApprovalProcessForm][type]': {
                      required: true,
                  },
                  'data[ApprovalProcessForm][remark]': {
                      maxlength: 146,
                  },
              },
              messages: {
                  'data[ApprovalProcessForm][type]': {
                      required: "Please choose verification type.",
                  },
              },
                 
      });
  <?php }?>
});

$(document).ready(function(){
  
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormAppealAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkall = $('input[id="checkAll"]:checked').length;
          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAll').attr('checked', false);
            $('#forwardAppealBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardAppealBtn').show();
          }
          else 
          {
            $('#forwardAppealBtn').hide();
          }
        });
});
//Dynamic confirmation modal -- START --
var btnName5 = '<?php echo $btnNameAppeal;?>';
var isModal5 = '<?php echo $isModal5;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName5+"?",
            btnName5,
            'Cancel',
            MyYesSAppealFunction,
            MyNoSAppealFunction
        );
}

function MyYesSAppealFunction() {
  if(isModal5 == 1)
  {
    $('#verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormAppealAjaxForm').submit();
  }
}
function MyNoSAppealFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>                     

<?php 
}else{
?>
    ...
<?php    
}
$ajaxUrl    = $this->Html->url(array('controller'=>'Properties','action'=>'appealAjax'));
?>
