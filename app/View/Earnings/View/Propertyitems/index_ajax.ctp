<?php
if(is_array($datas) && count($datas)>0){
  //debug($datas);
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
            'controller'            => 'Propertyitems',
            'action'                => 'indexAjax',
            'name'                  => $name,      
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>

    </div>
</div>

<?php 
$modelName='Propertyitem';
//Approval process start
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Propertyitems/'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){ ?>
  
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName; ?></button>
<?php
}
//Approval process start
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php } ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>

        <?php 
           if(!isset($is_excel)){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              )); ?>
            </th>
          <?php } ?>

      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        <?php                 
          echo $this->Paginator->sort('Propertyitem.name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Propertyitems','action' => 'indexAjax')));
          ?>
      </th>
      <th>
        Allowed Item
      </th>
      <th>
        Prohibited Item
      </th>
      <th>
        Property Type
      </th>
      <th><?php echo $this->Paginator->sort('is_enable'); ?></th>
      <?php if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE')){?>
        <th>Status</th>
      <?php }?>
      <th><?php echo __('Action'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
         $credit_id = $data[$modelName]['id'];
         $isAllowed='';
         $isProhibited='';
          $propertyType='';
        if($data['Propertyitem']['is_allowed'] == 1){
          $isAllowed = 'Yes';
          $isProhibited ='No';
          $propertyType='';
        }else if($data['Propertyitem']['is_prohibited'] == 1){
          $isAllowed = 'No';
          $isProhibited = 'Yes';
          $propertyType=$data['Propertyitem']['property_type_prohibited'];

        }
  
?>

    <tr>
      <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft') && ($data[$modelName]['added_by_recep'] == 1))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Saved') && ($data[$modelName]['added_by_recep'] == 1))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['status'] == 'Reviewed') && ($data[$modelName]['added_by_recep'] == 1))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Propertyitem']['name'])); ?>&nbsp;</td>	 	
      <td>
        <?php echo $isAllowed ?>
      </td>			
      <td>
        <?php echo $isProhibited ?>
      </td>     
      <td>
        <?php echo $propertyType ?>
      </td>     

      <td>
<?php 
    if($data['Propertyitem']['is_enable'] == '1'){
        echo "<font color=green>Yes</font>"; 
    }else{
        echo "<font color=red>No</font>"; 
    }
?>
      </td>

      <?php if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE')){?>
        <td>
        <?php echo $data['Propertyitem']['status'] ?>
      </td>
      <?php }?>

      
      <?php
echo $this->Form->end();
?>
      <td class="actions">

        <?php
              $allowedToEdit = 'false';
        if($this->Session->read('Auth.User.usertype_id') ==Configure::read('ADMIN_USERTYPE')){
              $allowedToEdit = 'true';
            }else if($data['Propertyitem']['added_by_recep'] == 1){
              if($this->Session->read('Auth.User.usertype_id') ==Configure::read('RECEPTIONIST_USERTYPE') && $data['Propertyitem']['status'] == 'Draft'){
                  $allowedToEdit = 'true';

              }else if($this->Session->read('Auth.User.usertype_id') ==Configure::read('OFFICERINCHARGE_USERTYPE')){
                  $allowedToEdit = 'true';
              }else{
                $allowedToEdit = 'false';
              }
            }
        ?>
        <?php if($allowedToEdit == 'true'){?>
            <?php echo $this->Form->create('PropertyitemEdit',array('url'=>'/propertyitems/add','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Propertyitem']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            <?php echo $this->Form->end();?> 
        
            <?php echo $this->Form->create('PropertyitemDelete',array('url'=>'/propertyitems/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Propertyitem']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            <?php echo $this->Form->end();?>
       <?php } ?>
        
      </td>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>

<?php
echo $this->Js->writeBuffer();
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>   

<script type="text/javascript">
$(document).ready(function(){
  
  $('#verifyBtn').click(function(){
        if($("#ApprovalProcessFormIndexAjaxForm").valid()){
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