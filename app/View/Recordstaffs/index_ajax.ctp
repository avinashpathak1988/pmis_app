<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
?>
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
            'controller'            => 'Recordstaffs',
            'action'                => 'indexAjax',
            'from_date'             => $from_date,
            'to_date'             => $to_date,       
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "indexAjax/from_date:$from_date/to_date:$to_date";
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
  $btnName = "Verify";
  $isModal = 0;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/recordstaffs/index'));?>
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
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
          if(!isset($is_excel)){
            ?>
          <th>
              <?php 
              echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));
              ?>
            </th>
            <?php
          }
      }
          ?>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php echo $this->Paginator->sort('Date'); ?></th>
      <th><?php echo $this->Paginator->sort('Force No'); ?></th>
      <th><?php echo $this->Paginator->sort('Time In'); ?></th>
      <th><?php echo $this->Paginator->sort('Time Out'); ?></th>
      <?php
      if(!$this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE'))
      {
      ?>
      <?php if ($this->Session->read('Auth.User.usertype_id')!=2) { ?>
      <th><?php echo __('Action'); ?></th>
      <?php } ?>
      <?php
        }
      if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
      {
      ?>
      <th><?php echo __('Status'); ?></th>
      <?php
        }
      ?>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>
      <?php
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
            if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['RecordStaff']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['RecordStaff']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['RecordStaff']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['RecordStaff']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['RecordStaff']['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['RecordStaff']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
            }
          }
          ?>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo h(date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['RecordStaff']['recorded_date']))); ?>&nbsp;</td> 

   <!--  <td><?php //echo (isset($data['RecordStaff']['recorded_date']) &&$data['RecordStaff']['recorded_date'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['RecordStaff']['recorded_date'])))) :  Configure::read('NA'); ?>&nbsp;</td>  -->
    
      <td><?php if($data['RecordStaff']['force_no']!='')echo ucwords(h($data['RecordStaff']['force_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
      <td><?php echo h(date("h:i A", strtotime($data['RecordStaff']['time_in']))); ?>&nbsp;</td> 
      <td>	<?php if($data['RecordStaff']['time_out']=='' && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')){ ?>
               
                    <span id="link_biometric_span_out<?php echo $data['RecordStaff']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Out Time', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning btn-mini','id'=>'link_biometric_button_out'.$data['RecordStaff']['id'],"onclick"=>"checkData(".$data['RecordStaff']['id'].")"));

                    ?>&nbsp;
                    <?php
              
            }else{
                echo ($data['RecordStaff']['time_out']!='') ? h(date("h:i A", strtotime($data['RecordStaff']['time_out']))): '';
            }
			?>
	  &nbsp;</td>
      <?php
      if ($this->Session->read('Auth.User.usertype_id')!=2) {
      
      ?>
        <td class="actions">
		<?php if($data['RecordStaff']['time_out']=='') { ?>
          <?php echo $this->Form->create('RecordStaffEdit',array('url'=>'/recordstaffs/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RecordStaff']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
          <?php echo $this->Form->end();?>
        
            <?php echo $this->Form->create('RecordStaffDelete',array('url'=>'/recordstaffs/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RecordStaff']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            <?php echo $this->Form->end();?>
		<?php } ?>
      </td>
     
      <?php
        }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
            ?>

            <td>
            <?php 
            // debug($data['ApprovalProcess']);
            if($data["RecordStaff"]['status'] == 'Draft')
            {
              echo $data["RecordStaff"]['status'];
            }
            else 
            {
              // debug($data['ApprovalProcess']);
              $status_info = '<b>Status: </b>'.$data['ApprovalProcess'][0]['status'].'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data['ApprovalProcess'][0]['status'];?></a>
              <?php 
            }?>
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
?>
<span style="color:red;font-weight:bold;"><?php  echo Configure::read('NO-RECORD'); ?></span>
<?php    
}
echo $this->Js->writeBuffer(); 
$ajaxUrl      = $this->Html->url(array('controller'=>'recordstaffs','action'=>'markOutTimeAjax'));
$page      = $this->Html->url(array('controller'=>'recordstaffs','action'=>'index'));
?> 
<script>
function checkData(id)
{
	if(confirm("Are you sure to punch out time"))
	{
		  var Url = '<?php echo $ajaxUrl; ?>';
		  var Url = Url + '/id:'+id;
		  $.post(Url,{},function(res){
			 if(res)
			 {
				 window.location.href = '<?php echo $page;?>' ;
			 }
			
		  });
	}
	else
	{
		return false;
	}
}
</script>   

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