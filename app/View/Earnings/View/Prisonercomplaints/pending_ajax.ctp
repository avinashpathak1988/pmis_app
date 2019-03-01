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
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisonercomplaints',
            'action'                => 'pendingAjax',
            'from'             => $from,
            'to'             => $to,       
            'status'             => $status,       
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
    
      $exUrl = "pendingAjax/from:$from/to:$to/status:$status";
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
if(@$file_type != 'pdf')
{
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
      echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Prisonercomplaints/add'));?>
      <?php if($isModal == 1)
      {?>
        <!-- Verify Modal START -->
        <?php echo $this->element('verify-modal');?>                       
        <!-- Verify Modal END -->
      <?php }?>
      <button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
      <?php
      }
}

?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
    <?php
        if(!isset($is_excel)){
          ?>
        <!-- <th>
            <?php 
            // echo $this->Form->input('checkAll', array(
            //       'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
            //       'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            // ));
            ?>
          </th> -->
          <?php
        }
          ?>
      <th><?php echo 'Sl no'; ?></th>                
      <th><?php echo 'Date'; ?></th>
      
      <th><?php echo 'Prisoner No'; ?></th>
      <th><?php echo 'Priority'; ?></th>
      
      <th><?php echo 'Complaint'; ?></th>

      <th><?php echo 'Duration Taken to Handle Complaint'; ?></th>
      <th><?php echo 'Days Elapsed'; ?></th>
      
      <?php
        if(!isset($is_excel)){
          ?>
      <th width="8%">Details</th>
      <?php
      }
      ?>
     
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['Prisonercomplaint']['status']);
?>
    <tr>
        
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['Prisonercomplaint']['date'])); ?>&nbsp;</td> 
      
      <td><?php echo $funcall->getPrisonerNumber($data['Prisonercomplaint']['prisoner_no']); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Prisonercomplaint']['priority'])); ?>&nbsp;</td>
      
      <td><?php echo ucwords(h($data['Prisonercomplaint']['complaint'])); ?>&nbsp;</td>
      
      <td>
        <?php
        if(isset($data['Prisonercomplaint']['action_date']) && $data['Prisonercomplaint']['action_date']!=''){
            $daysElapsed = (strtotime($data['Prisonercomplaint']['action_date']) - strtotime($data['Prisonercomplaint']['date'])) / 84600;
            echo ($daysElapsed!=0) ? round($daysElapsed)." days" : 'Same Day';
        }
        ?>
      </td>
      <td>
        <?php
        if(isset($data['Prisonercomplaint']['date']) && $data['Prisonercomplaint']['date']!=''){
            $daysElapsed = (strtotime(date("Y-m-d")) - strtotime($data['Prisonercomplaint']['date'])) / 84600;
            echo ($daysElapsed!=0) ? round($daysElapsed)." days" : '';
        }
        ?>
        
      </td>
      <?php
        if(!isset($is_excel)){
          ?>
      <td class="actions">
        <?php
        if($data['Prisonercomplaint']['status']!='Draft'){
          $status_info = '';
          if(isset($data['Prisonercomplaint']['respond_by']) && ($data['Prisonercomplaint']['respond_by'] != ''))
          $status_info .= '<b>Respond By: </b>'.$funcall->getName($data['Prisonercomplaint']['respond_by'],"User","name").'<br>';
          if(isset($data['Prisonercomplaint']['date_of_response']) && ($data['Prisonercomplaint']['date_of_response'] != ''))
          $status_info .= '<b>Date of Response: </b>'.date("d-m-Y", strtotime($data['Prisonercomplaint']['date_of_response'])).'<br>';
          if(isset($data['Prisonercomplaint']['response']) && ($data['Prisonercomplaint']['response'] != ''))
          $status_info .= '<b>Response: </b>'.$data['Prisonercomplaint']['response'].'<br>';
          if(isset($data['Prisonercomplaint']['action_by']) && ($data['Prisonercomplaint']['action_by'] != ''))
          $status_info .= '<b>Action By: </b>'.$funcall->getName($data['Prisonercomplaint']['action_by'],"User","name").'<br>';
          if(isset($data['Prisonercomplaint']['action_date']) && ($data['Prisonercomplaint']['action_date'] != ''))
          $status_info .= '<b>Action Date: </b>'.date("d-m-Y", strtotime($data['Prisonercomplaint']['action_date'])).'<br>';
          if(isset($data['Prisonercomplaint']['action']) && ($data['Prisonercomplaint']['action'] != ''))
          $status_info .= '<b>Action Remark: </b>'.$data['Prisonercomplaint']['action'].'<br>';
        ?>
        <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Details Info" pageBody="<?php echo $status_info;?>">Details</a>
        <?php
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