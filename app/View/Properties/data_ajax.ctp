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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<?php //echo $modelName; exit;
//echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination" style="margin-left: 0px;">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#dataList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Properties',
                                                    'action'                => 'dataAjax'
                                                    
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
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
    $exUrl = "dataAjax/";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlpdf = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlpdf, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
 }
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
$methodName = '';
if($modelName == 'CashItem')
{
  $methodName = 'creditList';
}
else 
{
  $methodName = 'debitList';
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/properties/'.$methodName));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<?php if(@$file_type != 'pdf') { ?>
<button type="submit" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" <?php if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php }?>><?php echo $btnName;?></button>
<?php } ?>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
          <?php 
          //if($status == 'Draft')
          //{?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php //}?>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">Prisoner Number</th>
          <th style="text-align: left;">
            Date
          </th>
          <th style="text-align: left;">
            Amount
          </th>
          <?php 
          if($modelName == 'CashItem')
          {?>
            <th style="text-align: left;">
            Description
            </th>
            <th style="text-align: left;">
            Source
            </th>
          <?php }
          else 
          {?>
            <th style="text-align: left;">
            Reason
            </th>
          <?php }?>
          
          <th style="text-align: left;">
          Status
          </th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;
    $amt=0;
    $totalamount=0;
      foreach($datas as $data)
      {
        $credit_id = $data[$modelName]['id'];?>
        <tr>
          <?php 
          //if($status == 'Draft')
          //{?>
            <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id') == Configure::read('GATEKEEPER_USERTYPE') && ($data[$modelName]['status'] == 'G-Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                ));
              }
              ?>
            </td>
          <?php //}?>
          <td><?php echo $rowCnt; ?></td>
          <?php 
          if($modelName == 'CashItem')
          {?>
            <td><?php echo isset($data['PhysicalProperty']['Prisoners']['prisoner_no'])?$data['PhysicalProperty']['Prisoners']['prisoner_no']:'';?></td>
            <td><?php echo date('d/m/Y',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
            <?php $currencySymbol = $data['Currency']['symbol'] ; 
              if(strpos($currencySymbol,'fa-') !== false){
                  $symbolHtml = "<span class= 'fa " . $currencySymbol . " ' ></span>";
              }else{
                $symbolHtml = "<span class='fa'>".$currencySymbol ."</span>";
              }
            ?>

           <td><?php echo $data['CashItem']['amount'].' '.$data['Currency']['name']; ?><i class="icon <?php echo $data['Currency']['symbol'] ; ?>"></i></td>
            <td style="max-width: 200px;text-overflow: scroll;    overflow-y: hidden;"><?php echo $data['PhysicalProperty']['description'];?></td>
            <td style="max-width: 200px;text-overflow: scroll;    overflow-y: hidden;"><?php echo $data['PhysicalProperty']['source'];?></td>
          <?php 
          }
          else 
          {?>
            <td><?php echo $data['Prisoners']['prisoner_no'];?></td>
            <td><?php echo date('d/m/Y',strtotime($data[$modelName]['debit_date_time']));?></td>
            <td><?php echo $data[$modelName]['debit_amount'].' '.$data['Currency']['name'];?></td>
            <td><?php echo $data[$modelName]['reason'];?></td>
          <?php }?>
          <td>
            <?php if($data[$modelName]['status'] == 'Draft')
            {
              echo $data[$modelName]['status'];
            }
            else 
            {
              $status_info = '<b>Status: </b>'.Configure::read($data[$modelName]['status']).'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo Configure::read($data[$modelName]['status']);?></a>
              <?php 
            }?>
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
}
else 
{
  echo '...';
}
$ajaxUrl    = $this->Html->url(array('controller'=>'Properties','action'=>'creditList'));
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
function forwardList()
{
    var url = '<?php //echo $ajaxUrl;?>';
    $.post(url, $('#SearchCreditListForm').serialize(), function(res) {
        if (res) {
            $('#dataList').html(res);
        }
    });
}
//open dynamic modal popup
  $(function() {
        // $(".pop").click(function(){
        //   var pageTitle = $(this).attr('pageTitle');
        //   var pageBody = $(this).attr('pageBody');
        //   $("#dynamic-modal .modal-title").html(pageTitle);
        //   $("#dynamic-modal .modal-body").html(pageBody);
        //   $("#dynamic-modal").modal("show");
        //   return false;
        // });
  });
</script>
<?php } ?>