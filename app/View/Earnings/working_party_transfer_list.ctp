<?php
if(is_array($datas) && count($datas)>0){
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
$methodName = 'approveWorkingPartyTransfers';
$modelName = 'WorkingPartyTransfer';    
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Earnings/'.$methodName));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>             
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
		<?php if(@$file_type != 'pdf') { ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
		<?php } ?>
            <th>SL#</th>
            <th>
                <?php 
                echo $this->Paginator->sort('CurrentWorkingParty.name','Current Working Party',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'approveWorkingPartyTransfers')));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.name','Destination Working Party',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'approveWorkingPartyTransfers')));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('WorkingPartyTransfer.start_date','Start Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'approveWorkingPartyTransfers')));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('WorkingPartyTransfer.end_date','End Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'approveWorkingPartyTransfers')));
                ?>
            </th>
            <th>Prisoner Id</th>
            <th>Approve Status</th>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
    	//debug($data);
        $id = $data['WorkingPartyTransfer']['id'];
        //$prisoner_id = $data['Prisoner']['id'];
        $display_status = Configure::read($data[$modelName]['status']);
        $transfered_prisoners = $funcall->getPrisonerNos($data['WorkingPartyTransfer']['prisoner_id']);
        ?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
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
            <?php } ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['CurrentWorkingParty']['name']; ?></td>
            <td><?php echo $data['TransferWorkingParty']['name']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['WorkingPartyTransfer']['start_date'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['WorkingPartyTransfer']['end_date'])); ?></td>

            <td><?php echo $transfered_prisoners; ?></td>
            <td>
                <?php if($data[$modelName]['status'] == 'Draft')
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
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php echo $this->Form->end();?>
<?php
//pagination start 
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
            'controller'            => 'Earnings',
            'action'                => 'approveWorkingPartyTransfers'

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
    $exUrl = "workingPartyTransferList";
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
//pagination end 
}else{
?>
    <span style="color:red;">No records found!</span>
<?php    
}
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'attendances'));
?> 
<?php if(@$file_type != 'pdf') { ?>
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
      $('#ApprovalProcessFormWorkingPartyTransferListForm').submit();
    }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>  
<?php } ?>               