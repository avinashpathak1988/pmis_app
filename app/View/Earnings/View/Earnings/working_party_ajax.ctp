<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    
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
$methodName = 'workingParties';
$modelName = 'WorkingParty';
?>

<?php 
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Earnings/'.$methodName));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button> 
                
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
            <th>Name</th>
            <th>
                <?php 
                echo $this->Paginator->sort('WorkingParty.start_date','Start Date',array('update'=>'#workingparty_listview','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'workingPartyAjax', 'status' => $status, 'keyword' => $keyword, 'officer_incharge'=>$officer_incharge, 'date_from' => $date_from, 'date_to' => $date_to)));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('WorkingParty.end_date','End Date',array('update'=>'#workingparty_listview','evalScripts' => true,'url'=>array('controller' => 'Earnings','action' => 'workingPartyAjax', 'status' => $status, 'keyword' => $keyword, 'officer_incharge'=>$officer_incharge, 'date_from' => $date_from, 'date_to' => $date_to)));
                ?>
            </th>
            <th>In charge working party</th>
            <th>Name of staff in charge</th>
            <th>Capacity</th>
            <th>Remarks</th>
            <!-- <th>Is Enable</th> -->
            <th>Approve Status</th>
            <th>Working Status</th>
<?php
if(!isset($is_excel) && ($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))){
?> 
            <th>Actions</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){

        $id = $data['WorkingParty']['id'];
        $display_status = Configure::read($data[$modelName]['status']);
?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
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
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['WorkingParty']['name']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['WorkingParty']['start_date'])); ?></td>
            <td><?php if(!empty($data['WorkingParty']['end_date']) && $data['WorkingParty']['end_date'] != '0000-00-00')echo date('d-m-Y', strtotime($data['WorkingParty']['end_date'])); ?></td>
            <td><?php echo $data['Officer']['name']; ?></td>
            <td><?php echo $funcall->getName($data['WorkingParty']['login_user_id'],'User','name'); ?></td>
            <td><?php echo $data['WorkingParty']['capacity']; ?></td>
            <td><?php echo substr($data['WorkingParty']['remarks'],0,10); ?></td>
            <!-- <td> -->
              <?php 
              // if($data[$modelName]['is_enable'] == 0)
              // {
              //   echo 'Disabled';
              // }
              // else 
              // {
              //   echo 'Enabled';
              // }
              ?>
            <!-- </td> -->
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
          <td>
            <?php if($data[$modelName]['open_status'] == 1)
            {
              echo 'Open';
              if(!isset($is_excel) && ($data[$modelName]['status']==Configure::read('Approved')))
              {
                echo '&nbsp;|&nbsp;'.$this->Form->button("Close", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-mini btn-warning', 'onclick'=>"javascript:closeworkingParty('$id');"));
              }
            }
            else 
            {
              echo 'Closed';
            }?>
          </td>
<?php
        if(!isset($is_excel) && ($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))){
?>              
            
            <td>
              <?php 
              if(in_array($data[$modelName]['status'], array('Draft','Review-Rejected','Approve-Rejected')))
              {
                $editFormID = "'ApprovalProcessFormWorkingPartyAjaxForm'";?>
                <?php echo $this->Form->create('workingPartyEdit',array('url'=>'/earnings/workingParties','admin'=>false,'id'=>$editFormID));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                ?>
                <?php echo $this->Form->button("<i class='icon-edit'></i>",array('label'=>false,'type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"editForm(".$editFormID.");")); 
                echo $this->Form->end();?> 
               <?php echo $this->Form->button("<i class='icon-remove'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger btn-mini', 'onclick'=>"javascript:deleteworkingParty('$id');"))?>
               
              <?php }?>
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
echo $this->Js->writeBuffer();
//pagination start 
if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#workingparty_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Earnings',
            'action'                => 'workingPartyAjax'

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
    $exUrl = "workingPartyAjax";
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
    ...
<?php    
}?>  
<?php if(@$file_type != 'pdf') { ?>
<script type="text/javascript">
$(document).ready(function(){

        $("#checkAll").click(function(){
            $('#ApprovalProcessFormWorkingPartyAjaxForm input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#ApprovalProcessFormWorkingPartyAjaxForm input[type="checkbox"]').click(function(){
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
//Edit 
function editForm(formID) {
    AsyncConfirmYesNo(
            "Are you sure want to edit?",
            'Edit',
            'Cancel',
            function()
            {
              $('#'+formID).submit();
            },
            function(){}
        );
}
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
      $('#ApprovalProcessFormWorkingPartyAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>
<?php } ?>  
