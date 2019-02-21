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

<?php //echo $modelName; exit;
//echo '<pre>'; print_r($datas); exit;
//debug($datas);
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination" style="margin-top: 0;margin-left: 0px;">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#dataList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Properties',
                                                    'action'                => 'physicalPropertyAjax',
                                                    'status'           => $status,
                                                    'item_id'           => $item_id,
                                                    'bag_no'           => $bag_no,
                                                    'date_from'           => $date_from,
                                                    'date_to'           => $date_to,
                                                    'property_type'           => $property_type,

                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
  if(isset($is_excel)){
    ?>
    <style type="text/css">
        th, td{border: 1px solid black;}
     </style>
    <?php
  }
?>
<?php

    $exUrl = "destroyedPropertyListAjax/status:$status/item_id:$item_id/bag_no:$bag_no/date_from:$date_from/date_to:$date_to/property_type:$property_type";
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
  $isModal = 2;

}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = Configure::read('REVIEW');
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $isModal = 1;
  $btnName = Configure::read('APPROVE');
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/properties/destroyedPropertyList'));?>
<?php if($isModal == 2)
{?>
  <?php echo $this->element('verify-destroy-property');?>                       
<?php }else if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" style="margin:3px 1px;"><?php echo $btnName;?></button>
<?php
}
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
          <?php 
           if(!isset($is_excel)){
          ?>
            <th>
              <?php echo $this->Form->input('checkAll', array(
                    'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                    'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
              ));?>
            </th>
          <?php }?>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">Prisoner Number</th>
          <th style="text-align: left;">Prisoner Name</th>
          <th style="text-align: left;">
            Date
          </th>
          <th style="text-align: left;">
            Property Item
          </th>
          <th style="text-align: left;">
            Quantity
          </th>
          <th style="text-align: left;">
            Bag No.
          </th>
          <th style="text-align: left;">
            Property Type
          </th>
          <th style="text-align: left;">
          Description
          </th>
          <th style="text-align: left;">
          Source
          </th>
          <th style="text-align: left;">
          Status
          </th>
          <th>Cause of Destruction</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;
    $amt=0;
    $totalamount=0;
        $count =1;

      foreach($datas as $data)
      {
        $credit_id = $data[$modelName]['id'];?>
        <tr>
          <?php 
           if(!isset($is_excel)){
          ?>
            <td>
              <?php 
              if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data[$modelName]['destroy_status'] == 'Draft'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['destroy_status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['destroy_status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                        'type'=>'checkbox', 'value'=>$credit_id,'hiddenField' => false, 'label'=>false,
                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              ?>
            </td>
          <?php }?>
          
          <td><?php echo $rowCnt; ?></td>

          <td><?php echo $data['PhysicalProperty']['Prisoners']['prisoner_no'];?></td>
          <td><?php echo $data['PhysicalProperty']['Prisoners']['first_name'];?> <?php echo $data['PhysicalProperty']['Prisoners']['last_name'];?></td>
          <td><?php echo date('d/m/Y',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
          <td><?php echo $data['Propertyitem']['name'];?></td>
          <td><?php echo $data['PhysicalPropertyItem']['quantity'];?></td>
          <td><?php echo $data['PhysicalPropertyItem']['bag_no'];?></td>
          <td><?php echo $data['PhysicalPropertyItem']['property_type'];?></td>
          <td><?php echo $data['PhysicalProperty']['description'];?></td>
          <td><?php echo $data['PhysicalProperty']['source'];?></td>
          <td>
           <?php if( $data[$modelName]['item_status'] == 'Destroy'){ ?>

              <span ><?php echo Configure::read($data[$modelName]['destroy_status']); ?> </span>
           <?php } ?>
          </td>
          <td>
            <?php

            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data[$modelName]['destroy_status'] == 'Saved'))
              { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#destuctionCause_<?php echo $count ?>">
                  view 
              </button>
                <?php }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data[$modelName]['destroy_status'] == 'Reviewed')){ ?>
                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#destuctionCause_<?php echo $count ?>">
                  view 
              </button>
              
             <?php  }else { ?>

               <?php echo ''; ?>
              <?php }

               ?>
            
              <div class="modal fade" id="destuctionCause_<?php echo $count ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Destroy Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                            <table style="width: 100%;">
                              <thead>
                                <tr>
                                  <th>Destroy date</th>
                                  <th style="width:200px;">Cause</th>
                                  <th style="width:200px;">Mode of destruction</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><?php echo date('d-m-Y',strtotime($data['PhysicalPropertyItem']['destroy_date'])) ; ?></td>
                                  <td style="width:200px;"><?php echo $data['PhysicalPropertyItem']['destroy_cause'] ; ?></td>
                                  <td style="width:200px;"><?php echo $data['PhysicalPropertyItem']['destruction_mode'] ; ?></td>

                                </tr>
                                
                                
                                
                              </tbody>
                            </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
          </td>
        </tr>
        <?php   
        $rowCnt++;
        $count++;

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
<?php if(@$file_type != 'pdf') {?>
<script>
$(function(){
$("#ApprovalProcessFormDestroyedPropertyListAjaxForm").validate({
     
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
        if($("#ApprovalProcessFormDestroyedPropertyListAjaxForm").valid()){
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
  }else if(isModal == 2)
  {
    $('#verify').modal('show');

  }
  else 
  {
    $('#ApprovalProcessFormDestroyedPropertyListAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
//Dynamic confirmation modal -- END --
</script>  
<?php } ?>