
<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
  
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'stationjournals',
            'action'                => 'indexAjax',
            'journal_date'          => $journal_date,
            'prison_id'             => $prison_id,

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
    $exUrl = "indexAjax/prison_id:$prison_id/journal_date:$journal_date";
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
<?php //echo '<pre>'; print_r($prisonerAttendanceList); exit;
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
$methodName = 'index';
$modelName = 'Stationjournal';    
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/stationjournals/'.$methodName));?>
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
            <th>Date</th>
            <th>Station Name</th>
            <th>State of Prisnors</th>
            <th>State of Prison</th>
<?php
if(!isset($is_excel)){
?> 
            <th>Duty Officer</th>
            <th>Time</th>
            <!-- <th>Status</th> -->
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
      //debug($data);
      $id = $data['Stationjournal']['id'];
?>
        <tr>
            <?php
            if(!isset($is_excel)){
            ?>   
                <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
            <?php } ?>
            <td><?php echo $rowCnt; ?></td>
             <td><?php echo date('d-m-Y',strtotime($data['Stationjournal']['journal_date'])); ?></td>
            <td><?php echo $funcall->getStationName($data['Stationjournal']['prison_id']); ?></td>
            <td><?php echo $data['Stationjournal']['prisnors_state']; ?></td>
            <td><?php echo $data['Stationjournal']['prisons_state']; ?></td>
<?php
        if(!isset($is_excel)){
?>   
<td><?php echo $funcall->getName($data['Stationjournal']['dutyofficer_id'],"User","name"); ?></td>           


   <td><?php
$time = $data['Stationjournal']['modified'];

echo $time = date("H:i",strtotime($time));
  ?></td>
           <?php /* ?> <td>
                      <?php
                      if($data['Stationjournal']['is_enable'] == 1){
                        echo $this->Html->link("Click To Disable",array(
                          'controller'=>'stationjournals',
                          'action'=>'disable',
                          $data['Stationjournal']['id']
                        ),array(
                          'escape'=>false,
                          'class'=>'btn btn-primary btn-mini',
                          'onclick'=>"return confirm('Are you sure you want to disable?');"
                        ));
                      }else{
                        echo $this->Html->link("Click To Enable",array(
                          'controller'=>'stationjournals',
                          'action'=>'enable',
                          $data['Stationjournal']['id']
                        ),array(
                          'escape'=>false,
                          'class'=>'btn btn-danger btn-mini',
                          'onclick'=>"return confirm('Are you sure you want to enable?');"
                        ));
                      }
                                 ?>
            </td>
            <?php */ ?>
            <td>

         
               <?php if($data['Stationjournal']['status'] != 'Approved'){
                      echo $this->Html->link('<i class="icon icon-edit"></i>',array(
                        'action'=>'edit',
                        $data['Stationjournal']['id']
                      ),array(
                          'escape'=>false,
                          'data-toggle'=>'tooltip',
                          'title'=>'Edit',
                          'class'=>'btn btn-success btn-mini',
                          'onclick'=>"return confirm('Are you sure you want to edit?');"
                        ));
                       ?>
                                  
                      <?php

                      echo $this->Html->link('<i class="icon icon-trash"></i>',array(
                           'action'=>'trash',
                           $data['Stationjournal']['id']
                         ),array(
                            'escape'=>false,
                            'data-toggle'=>'tooltip',
                            'title'=>'Delete',
                            'class'=>'btn btn-danger btn-mini',
                            'onclick'=>"return confirm('Are you sure you want to delete?');"
                          ));
                       
                      }  ?>
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
<?php echo $this->Form->end();?>
<?php
}else{
echo Configure::read("NO-RECORD");   
}
?>
<?php if(@$file_type != 'pdf'){?>
<script type="text/javascript">
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
      $('#ApprovalProcessFormIndexAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>
<?php } ?>                    