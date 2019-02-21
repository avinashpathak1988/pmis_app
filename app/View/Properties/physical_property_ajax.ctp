<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
#img_prev_0{
  max-width:80px !important;
}
</style>
<?php //echo $modelName; exit;
//echo '<pre>'; print_r($datas); exit;
//debug($datas);

if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid phy-pro">
    <div class="span5">
        <ul class="pagination">
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

    $exUrl = "physicalPropertyAjax/status:$status/item_id:$item_id/bag_no:$bag_no/date_from:$date_from/date_to:$date_to/property_type:$property_type";
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
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/properties/physicalPropertyList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php } if(!isset($is_excel)){?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<?php
}
//Approval process start
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
          <!-- <th style="text-align: left;">
            Item
          </th>
          <th style="text-align: left;">
            Quantity
          </th>
          <th style="text-align: left;">
            Bag No.
          </th>
          <th style="text-align: left;">
            Property Type
          </th> -->
          <!-- <th style="text-align: left;">
          Description
          </th> -->
          <th style="text-align: left;">
          View Details
          </th>
          <th style="text-align: left;">
          Source
          </th>
          <th style="text-align: left;">
          Attachment
          </th>
           <th style="text-align: left;">
            Tagged
          </th>
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
        $credit_id = $data['PhysicalProperty']['id'];?>
        <tr>
          <?php 
           if(!isset($is_excel)){
          ?>
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
              else if($current_usertype_id == Configure::read('GATEKEEPER_USERTYPE') && ($data[$modelName]['status'] == 'G-Draft'))
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
          <td><?php echo isset($data['PhysicalProperty']['Prisoners']['prisoner_no'])?$data['PhysicalProperty']['Prisoners']['prisoner_no']:'';?></td>
          <td><?php echo isset($data['PhysicalProperty']['Prisoners']['first_name'])?$data['PhysicalProperty']['Prisoners']['first_name']:''; ?> <?php echo isset($data['PhysicalProperty']['Prisoners']['last_name'])?$data['PhysicalProperty']['Prisoners']['last_name']:'';?></td>
          <td><?php echo date('d/m/Y',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
          <!-- <td><?php echo $data['Propertyitem']['name'];?></td>
          <td><?php echo $data['PhysicalPropertyItem']['quantity'];?></td>
          <td><?php echo $data['PhysicalPropertyItem']['bag_no'];?></td>
          <td><?php echo $data['PhysicalPropertyItem']['property_type'];?></td> -->


          <td><button type="button" class="btn btn-success btn-mini button-gap" data-toggle="modal" onclick="setPropertyView('<?php echo $data['PhysicalProperty']['id']; ?>');" data-target="#propertyView">view</button></td>


          <!-- <td><?php// echo $data['PhysicalProperty']['description'];?></td> -->
          <td><?php echo $data['PhysicalProperty']['source'];?></td>

            <td>
                  <?php if($data['PhysicalProperty']['photo'] != null && $data['PhysicalProperty']['photo'] != '') { ?>
                  <span id="previewPane" class="img_preview_panel">
                        <a class="example-image-link prevImage_0" href="" data-lightbox="example-set"><img id="img_prev_0" src="<?php echo $this->webroot; ?>app/webroot/files/physicalitems/<?php echo $data['PhysicalProperty']['photo'];?>" class='img_prev_list' /></a>
                        
                  </span>
                  <?php } ?>
                </td>
          <td><?php echo $data[$modelName]['item_status'];?></td>

          <td>
            <?php if( $data[$modelName]['item_status'] == 'Destroy'){ ?>

              <span style="color:red;">Destroyed</span>
           
            <?php }else if($data[$modelName]['status'] == 'Draft')
            {
              echo $data[$modelName]['status'];
            }
            else 
            {
              // debug($modelName);
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
$getPropertyRowAjaxUrl    = $this->Html->url(array('Properties'=>'Properties','action'=>'getPropertyRow'));

?>
<?php if(@$file_type != 'pdf') { ?>
<script>
$(function(){
$("#ApprovalProcessFormPhysicalPropertyAjaxForm").validate({
     
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
        if($("#ApprovalProcessFormPhysicalPropertyAjaxForm").valid()){
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
  }
  else 
  {
    $('#ApprovalProcessFormPhysicalPropertyAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
function setPropertyView(id){
  //alert(id);
  $('#visitorprisonerPropertyDiv').html('');
    url = <?php echo '\''.$getPropertyRowAjaxUrl.'\'' ?>;
      $.post(url, {propertyId:id}, function(res) {
            if (res) {

              $('#visitorprisonerPropertyDiv').html(res);

                }
            });
}
//Dynamic confirmation modal -- END --
</script>  
<?php } ?>