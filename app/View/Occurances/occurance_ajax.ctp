<?php
if(is_array($datas) && count($datas)>0){
?>\
<?php 
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
  // $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
$methodName = 'occurnce';
$modelName = 'Occurance';
?>
<?php 
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Occurances/'.$methodName));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('occurance_verify_modal');?>                       
  <!-- Verify Modal END -->
<?php }?>


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
            'controller'            => 'Occurance',
            'action'                => 'occuranceAjax',
            
           
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "occuranceAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button> 

<table id="occurancetable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
       <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
       </th>
        <th><?php echo $this->Paginator->sort('Sl no'); ?></th> 
        <th> Name </th>
        <th>
        Force Number
        </th>               
        <th>
         Rank
        </th>
         <th>
         Date
        </th>
        <th>
         Area Of Deployment
        </th>
        <th>
         Lockup Details
        </th>
         <th>
          Number Of Absent Staffs
        </th>
         <th>
         Occurance Details
        </th>
        <th style="width:8%"><?php echo __('Action'); ?></th>
      
      
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  //  debug($data);
  // debug($modelName);
  $id = $data['Occurance']['id'];
?>

    <tr>
       <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
                else if($this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE') && ($data[$modelName]['status'] == 'Draft'))
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
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Occurance']['name'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['Occurance']['force_number'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Occurance']['rank'])); ?>&nbsp;</td>
       <td><?php echo date('d-m-Y',strtotime($data['Occurance']['date'])); ?>&nbsp;</td>
      <td>
      <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModalView" onclick="getShiftId(<?php echo $data['Occurance']['shift_id']; ?>,'<?php echo date('d-m-Y',strtotime(
      $data['Occurance']['date'])); ?>')">View</button>

      <?php // echo ucwords(h($data['Occurance']['area_of_deployment'])); ?>&nbsp;
      </td>
      <td>
        <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['Occurance']['id']; ?>" onclick="getLockupAjax(<?php echo $data['Occurance']['id']; ?>,'<?php echo date('d-m-Y',strtotime(
      $data['Occurance']['date'])); ?>')">View Lockup</button>
      </td>
      <td><?php echo ucwords(h($data['Occurance']['number_of_absent_stafs'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Occurance']['occurance_details'])); ?>&nbsp;</td>
      <td class="actions">
        <!-- $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') -->
          <?php if(true){?>

         <?php
         echo $this->Html->link('<i class="icon icon-file-alt"></i>View',array("controller"=>"Occurances","action"=>"view",$data['Occurance']['id']), array("escape" => false,'class'=>'btn btn-primary btn-mini'));
         ?>

         <?php
         if(isset($data['Occurance']['action_by']) && $data['Occurance']['action_by']==NULL){
          ?>
          <?php echo $this->Form->create('OccuranceEdit',array('url'=>'/Occurances/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Occurance']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?>
          <?php echo $this->Form->end();?>

          <?php echo $this->Form->create('OccuranceDelete',array('url'=>'/Occurances/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Occurance']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
          <?php echo $this->Form->end();?>
          <?php
         }
         ?>
          
          <?php } ?>
      </td>
         				
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php echo $this->Js->writeBuffer();
?>

<?php 
}else{
echo Configure::read('NO-RECORD');   
}
?>    
<!-- Modal -->
            <div id="myModalView" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Details</h4>
                  </div>
                  <div class="modal-body" id="lockup">
                    <!-- <table class="table table-bordered data-table table-responsive">
                        <tbody>
                            <tr>
                                <td><b>Offence Name</b></td>
                                <td><?php //echo $data["InternalOffence"]["name"]?></td>
                            </tr>
                            <tr>
                                <td><b>Rules & Regulations</b></td>
                                <td><?php //echo $data["RuleRegulation"]["name"]?></td>
                            </tr>
                        </tbody>
                    </table> -->
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
<script type="text/javascript">
  function getShiftId(id,date){
  var $confirm = $("#myModalView");
  var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'getShiftId'));?>/'+id+'/'+date;
  $.post(strURL,{},function(data){
      if(data) { 
       //alert(data);
        
        //var $confirm_newmodal = $("#modalConfirmYesNo"+key);
        $confirm.modal('show');
        $('#lockup').html(data);
          //$('.areaof').show();
          //$('#areaof').html(data);

      }
      else
      {
          $confirm.modal('hide');  
      }
  });
  }
  function getLockupAjax(id,date){
    var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'lockupReportAjax'));?>/'+id+'/'+date;
    $.post(strURL,{},function(data){
        if(data) { 
         //alert(data);
         var $confirm = $("#myModalView");
        //var $confirm_newmodal = $("#modalConfirmYesNo"+key);
        $confirm.modal('show');
        $('#lockup').html(data);
        }
        else
        {
            $confirm.modal('hide'); 
        }
    });
  }

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
      $('#ApprovalProcessFormOccuranceAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --

</script>