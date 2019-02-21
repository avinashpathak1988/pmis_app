<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){

    //get prisoner approval status 
  $prisoner_status = $funcall->getName($datas[0]['PrisonerSentence']['prisoner_id'],'Prisoner', 'status');
?> 
<?php 
//Approval process start
$modelName = 'PrisonerSentence';
$btnNameSentenceCapture = Configure::read('SAVE');
$isModalSentenceCapture = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnNameSentenceCapture = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnNameSentenceCapture = Configure::read('REVIEW');
  $isModalSentenceCapture = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnNameSentenceCapture = Configure::read('APPROVE');
  $isModalSentenceCapture = 1;
}

echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$datas[0]['Prisoner']['uuid'].'#sentence_capture'));

echo $this->Form->input('data_type',array('type'=>'hidden','value'=> 'sentence_capture'));
?>
<?php if($isModalSentenceCapture == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNoSentenceCapture();" tabcls="next" id="forwardSentenceBtn" class="btn btn-success btn-mini" style="display: none;"><?php echo $btnNameSentenceCapture;?></button>
<?php
}

//Approval process end
?>                   
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <?php 
            if(!isset($is_excel)  && $prisoner_status == 'Approved'){
            ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'prisonerSentenceCheckAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
            <?php }?>
            <th>SL#</th>
            <th>File No.</th>
            <th>Offence Connect In</th>
            <th>Date Of Conviction</th>
            <th>Awaiting/Sentence Awarded</th>
            <th>Date Of Sentence</th>
            <th>Year</th>
            <th>Month</th>
            <th>Day</th>
            <th>Type Of Sentence</th>
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

    foreach($datas as $data)
    {
        //debug($data); exit;
        $id = $data['PrisonerSentence']['id'];
        $puuid = $data['Prisoner']['uuid'];
        $offenceNames = $funcall->getPrisonerOffenceNames($data['PrisonerOffence']['offence']);
        $sectionOfLawNames = $funcall->getPrisonerSectionOfLawNames($data['PrisonerSentence']['section_of_law']);
        ?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
            <?php 
            if(!isset($is_excel)  && $prisoner_status == 'Approved')
            {?>
                <td>
                    <?php 
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
                    {
                        echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                                'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                                'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                          ));
                    }
                    else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
                    {
                        echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                                'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                                'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                          ));
                    }
                    else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed'))
                    {
                        echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                                'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                                'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                          ));
                    }
                    ?>
                </td>
            <?php }?>
            <td><?php echo $rowCnt; ?></td>
            <td>
                <?php echo $data['PrisonerCaseFile']['file_no'];?>
            </td>
            <td>
                <?php echo $data['PrisonerOffence']['offence_no'];?>
            </td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerSentence']['date_of_conviction'])); ?></td>
            <td>
                <?php $sentence_waiting = Configure::read('SENTENCE-WAITING');
                echo $sentence_waiting_val = $sentence_waiting[$data['PrisonerSentence']['is_convicted']]; 
                ?>
            </td>
            <td>
                <?php 
                if(isset($data['PrisonerSentence']['date_of_sentence']) && !empty($data['PrisonerSentence']['date_of_sentence']) && $data['PrisonerSentence']['date_of_sentence'] != '0000-00-00')
                    echo date('d-m-Y', strtotime($data['PrisonerSentence']['date_of_sentence'])); 
                else 
                    echo Configure::read('NA');?>
            </td>
            <td>
                <?php 
                if(isset($data['PrisonerSentence']['years']) && ($data['PrisonerSentence']['years'] > 0))
                {
                    echo $data['PrisonerSentence']['years'];
                }
                else 
                    echo Configure::read('NA');?>
            </td>
            <td>
                <?php if(isset($data['PrisonerSentence']['months']) && ($data['PrisonerSentence']['months'] > 0))
                {
                    echo $data['PrisonerSentence']['months'];
                }
                else 
                    echo Configure::read('NA');?>
            </td>
            <td>
                <?php if(isset($data['PrisonerSentence']['days']) && ($data['PrisonerSentence']['days'] > 0))
                {
                    echo $data['PrisonerSentence']['days'];
                }
                else 
                    echo Configure::read('NA');?>
            </td>
            <td>
                <?php if(isset($data['PrisonerSentence']['sentence_type']))
                {
                    echo $funcall->getName($data['PrisonerSentence']['sentence_type'],'SentenceType','name');
                }?>
            </td>
            <td>
            <?php if($data[$modelName]['status'] == 'Draft')
            {
              echo $data[$modelName]['status'];
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$data[$modelName]['status'].'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data[$modelName]['status'];?></a>
              <?php 
            }?>
          </td>
          <td>
            <?php
            if(!isset($is_excel))
            {
                $isEdit = 0;
                $isDelete = 0;
                if($login_user_type_id == Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft' || $data[$modelName]['status'] == 'Review-Reject' || $data[$modelName]['status'] == 'Approve-Reject') && $data[$modelName]['login_user_id'] == $login_user_id)
                {
                    $isEdit = 1;
                    $isDelete = 1;
                }
                if($login_user_type_id == Configure::read('OFFICERINCHARGE_USERTYPE') && $data[$modelName]['login_user_id'] == $login_user_id)
                {
                    $isEdit = 1;
                    $isDelete = 1;
                    if($data[$modelName]['status'] == 'Approved')
                    {
                        $isDelete = 0;
                    }
                }
                if($data[$modelName]['login_user_id'] == $login_user_id)
                {
                    $prisoner_type_id = $funcall->getName($data[$modelName]['prisoner_id'], 'Prisoner', 'prisoner_type_id');
                    //$isEdit = 1;
                }
                $viewDetail = '<b>Offence: </b>'.$data['PrisonerOffence']['offence_no'].'<br>';
                // $viewDetail .= "<b>Awaiting/Sentence Awarded: </b>".$$sentence_waiting_val.'<br>';
                
                //$viewDetail .= "<b>Date of Committal: </b>".date('d-m-Y', strtotime($data['PrisonerSentence']['date_of_committal'])).'<br>';
                // $viewDetail .= "<b>Date of Sentence : </b>".date('d-m-Y', strtotime($data['PrisonerSentence']['date_of_sentence'])).'<br>';
                $viewDetail .= "<b>Date of Conviction: </b>".date('d-m-Y', strtotime($data['PrisonerSentence']['date_of_conviction'])).'<br>';
                if ($data['Prisoner']['classification_id']!='') {
                     $viewDetail .= "<b>Class: </b>".$funcall->getName($data['Prisoner']['classification_id'], 'Classification', 'name').'<br>';
                }else{  echo Configure::read('NA');}
               // debug($data['PrisonerSentence']['class']);
               
                // $viewDetail .= "<b>Case File No: </b>".$data['PrisonerSentence']['case_id'].'<br>';
                if ($data['PrisonerSentence']['is_convicted']!='') {
                     $sentenceAwaiting = array('1'=>'Awating Sentencing', '2'=>'Sentence Awarded');
                     $viewDetail .= "<b>Awaiting/Sentence Awarded: </b>".$sentenceAwaiting[$data['PrisonerSentence']['is_convicted']].'<br>';
                }else{echo Configure::read('NA');}
              
                if ($data['PrisonerSentence']['is_convicted']==2) {

                    $viewDetail .= "<b>Date of Sentence: </b>".date("d-m-Y", strtotime($data['PrisonerSentence']['date_of_sentence'])).'<br>';
                }
                if ($data['PrisonerSentence']['is_convicted']==2) {
                     $wish_to_appeal_options2= array('0'=>'No', '1'=>'Yes');
                    $viewDetail .= "<b>Wish To appeal: </b>".$wish_to_appeal_options2[$data['PrisonerSentence']['wish_to_appeal']].'<br>';
                }
                if ($data['PrisonerSentence']['is_convicted']==2) {
                    $waiting_for_confirmation_options= array('0'=>'No', '1'=>'Yes');
                    $viewDetail .= "<b>Waiting For Confirmation: </b>".$waiting_for_confirmation_options[$data['PrisonerSentence']['waiting_for_confirmation']].'<br>';
                }
                if ($data['PrisonerSentence']['waiting_for_confirmation']==1) {
                    $serving_as_options= array('0'=>'Not To Serve', '1'=>'Opt To Serve');
                    $viewDetail .= "<br><b>Serving as: </b>".$serving_as_options[$data['PrisonerSentence']['serving_as']].'<br>';
                }
                if ($data['PrisonerSentence']['is_convicted']==2) {

                    $viewDetail .= "<b>Sentence Of: </b>".$funcall->getName($data['PrisonerSentence']['sentence_of'], 'SentenceOf', 'name').'<br>';
                }

                if($data['SentenceOf']['id'] == 1 || $data['SentenceOf']['id'] == 2)
                {
                    //if impronsonment plus fine only 
                    if($data['SentenceOf']['id'] == 2)
                    {
                        $viewDetail .= "<b>Receipt Number: </b>".$data['PrisonerSentence']['receipt_number'].'<br>';
                        $viewDetail .= "<b>Fine (Amount): </b>".$data['PrisonerSentence']['fine_with_imprisonment'].'<br>';
                    }
                    // imprisonment only or improsonment plus fine only
                    if(isset($data['PrisonerSentence']))
                    {
                        $viewDetail .= "<b>Sentence Type: </b>";
                        $sentenceType = $funcall->getSentenceType($data['PrisonerSentence']['sentence_type']);
                        $viewDetail .= $sentenceType;
                        //sentence details 
                        $viewDetail .= "<br><b>Sentence details: </b>";
                        $sdetail = '';
                        if($data['PrisonerSentence']['years'] > 0)
                        {
                            if($data['PrisonerSentence']['years'] == 1)
                                $sdetail .= $data['PrisonerSentence']['years'].' year';
                            else 
                                $sdetail .= $data['PrisonerSentence']['years'].' years';
                        }
                        if($data['PrisonerSentence']['months'] > 0)
                        {
                            if($sdetail != '')
                                $sdetail .= ' & ';
                            if($data['PrisonerSentence']['months'] == 1)
                                $sdetail .= $data['PrisonerSentence']['months'].' month';
                            else 
                                $sdetail .= $data['PrisonerSentence']['months'].' months';
                        }
                        if($data['PrisonerSentence']['days'] > 0)
                        {
                            if($sdetail != '')
                                $sdetail .= ' & ';
                            if($data['PrisonerSentence']['days'] == 1)
                                $sdetail .= $data['PrisonerSentence']['days'].' day';
                            else 
                                $sdetail .= $data['PrisonerSentence']['days'].' days';
                        }
                        $viewDetail .= $sdetail;
                    }
                }

                
                // if($data['SentenceOf']['id'] == 0)
                // {
                //     // imprisonment only 
                //     $viewDetail .= "<br><b>Sentence count details: </b><br>";
                //     foreach($data['PrisonerSentenceCount'] as $sentenceKey=>$sentenceVal)
                //     {
                //         $sentenceType = $funcall->getSentenceType($sentenceVal['sentence_type']);
                //         $viewDetail .= $sentenceVal['years']."years & ".$sentenceVal['months']."months & ".$sentenceVal['days']."days ".$sentenceType;
                //     }
                // }
                ?>
                <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Sentence Capture Details" pageBody="<?php echo $viewDetail;?>">
                    <i class="icon-eye-open"></i>
                </a>
                <?php 
                if($isEdit == 1)
                {
                    echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$puuid.'#sentence_capture','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                    echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerSentence'));
                    echo $this->Form->button('<i class="icon-edit"></i>',array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); 
                    echo $this->Form->end();
                }
                if($isDelete == 1)
                {
                    echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteSentence('$id');"));
                }?>
            
            <?php
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

//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#sentence_capture_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'sentenceCaptureAjax',
            'prisoner_id'             => $prisoner_id,

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
    $exUrl = "kinDetailAjax/prisoner_id:$prisoner_id";
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
?>
<script type="text/javascript">
//Delete Dynamic confirmation modal -- START --
function ShowDeleteConfirm(formId, funcName) {
    AsyncConfirmYesNo(
            "Are you sure want to delete?",
            'Delete',
            'Cancel',
            MyYesDelete,
            MyNoDelete,
            formId,
            'Delete',
            funcName
        );
}
function MyYesDelete(formId, funcName) 
{
  deleteIdProof(formId);
}
function MyNoDelete() {
}
//Delete Dynamic confirmation modal -- END --

$(function(){
$("#ApprovalProcessFormSentenceCaptureAjaxForm").validate({
     
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
});
$(document).ready(function(){
  
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormSentenceCaptureAjaxForm").valid()){
            if( !confirm('Are you sure to save?')) {
                            return false;
            }
        }
    });
        $("#prisonerSentenceCheckAll").click(function(){
            $('#ApprovalProcessFormSentenceCaptureAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#ApprovalProcessFormSentenceCaptureAjaxForm input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('#ApprovalProcessFormSentenceCaptureAjaxForm input[type="checkbox"]:checked').length;
          
          var is_checkall = $('#ApprovalProcessFormSentenceCaptureAjaxForm input[id="prisonerSentenceCheckAll"]:checked').length;

          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#prisonerSentenceCheckAll').attr('checked', false);
            $('#forwardSentenceBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardSentenceBtn').show();
          }
          else 
          {
            $('#forwardSentenceBtn').hide();
          }
        });
});
//Dynamic confirmation modal -- START --
var btnNameSentenceCapture= '<?php echo $btnNameSentenceCapture;?>';
var isModalSentenceCapture = '<?php echo $isModalSentenceCapture;?>';
function ShowConfirmYesNoSentenceCapture() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnNameSentenceCapture+"?",
            btnNameSentenceCapture,
            'Cancel',
            MyYesSentenceCaptureFunction,
            MyNoSentenceCaptureFunction
        );
}

function MyYesSentenceCaptureFunction() {
  if(isModalSentenceCapture == 1)
  {
    $('#ApprovalProcessFormSentenceCaptureAjaxForm #verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormSentenceCaptureAjaxForm').submit();
  }
}
function MyNoSentenceCaptureFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>  
<?php 
}else{
?>
    ...
<?php    
}
?>                    