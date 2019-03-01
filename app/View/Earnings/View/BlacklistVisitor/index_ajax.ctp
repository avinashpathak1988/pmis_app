<?php
if(is_array($datas) && count($datas)>0){
 // debug($datas);
  if(!isset($is_excel)){
?>
<style type="text/css">
  .prisoner-item-show{
    padding-left: 20px;
  }
  
</style>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'BlacklistVisitor',
            'action'                => 'indexAjax',
        )+$searchData
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = $this->Html->url(array('controller'=>'BlacklistVisitor','action'=>'indexAjax')+$searchData,true);
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
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}

else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/BlacklistVisitor/index'));?>
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
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
          <?php
          	if($this->Session->read('Auth.User.usertype_id')==Configure::read("ADMIN_USERTYPE")){
		        if(!isset($is_excel)){
		          ?>
		        <th>
		            <?php echo $this->Form->input('checkAll', array(
		                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
		                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
		            ));?>
		          </th>
		          <?php
		        }
    		}else if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
		        if(!isset($is_excel)){
		          ?>
		        <th>
		            <?php echo $this->Form->input('checkAll', array(
		                  'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
		                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
		            ));?>
		          </th>
		          <?php
		        }
    		}
          ?>
          
            <th><?php echo 'Sl no'; ?></th>  
            <th><?php echo 'Visitor Name'; ?></th> 
            <th><?php echo 'Prison Name'; ?></th>  
            <th><?php echo 'Id type'; ?></th>  
            <th><?php echo 'Id Number'; ?></th> 
            <th><?php echo 'Reason'; ?></th>                
            <th><?php echo 'Status'; ?></th>                



      
        </tr>
    </thead>
<tbody>

<?php
//debug($datas);
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  //debug($data);
?>
    <tr>
      <?php
        if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE') && $data["BlacklistedVisitor"]['status'] == 'Draft')
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['BlacklistedVisitor']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data["BlacklistedVisitor"]['status'] != 'Draft' && $data["BlacklistedVisitor"]['status'] != 'Approved')
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['BlacklistedVisitor']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              
            ?>
          </td>
          <?php
          }
      
          ?>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $data['BlacklistedVisitor']['name']; ?></td>
      <td><?php echo $data['Prison']['name']; ?></td>
      <td><?php echo $data['Iddetail']['name']; ?></td>
      <td><?php echo $data['BlacklistedVisitor']['visitor_id_no']; ?></td>
      <td><?php echo $data['BlacklistedVisitor']['reason']; ?></td>
      <td><?php echo $data['BlacklistedVisitor']['status']; ?></td>

    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>


<?php
}else{
echo Configure::read("NO-RECORD");    
}
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
<?php
$getVisitorRowAjaxUrl= $this->Html->url(array('controller'=>'Visitors','action'=>'getVisitorRow'));
$getVisitorItemAjaxUrl= $this->Html->url(array('controller'=>'Visitors','action'=>'getVisitorItem'));
?>


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
</script>
<?php } ?>  