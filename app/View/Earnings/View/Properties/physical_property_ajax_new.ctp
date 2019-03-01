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
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid">
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
                                                    'action'                => 'physicalPropertyAjax'
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
    $exUrl = "physicalPropertyAjax/";
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
$btnName = 'Save';
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = 'Save';
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = 'Review';
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = 'Approve';
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
<button type="submit" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini" <?php if($isModal == 1){?> data-toggle="modal" data-target="#verify"<?php }?>><?php echo $btnName;?></button>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
          <th style="text-align: left;">SL#</th>
          <!-- <th style="text-align: left;">Prisoner Number</th> -->
          <th style="text-align: left;">
            Prisoner No.
          </th>
          <th style="text-align: left;">
            Date
          </th>
          <th style="text-align: left;">
          Source
          </th>
          <th style="text-align: left;">
          Description
          </th>
          <th style="text-align: left;">
          List Of Items
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
      {?>
        <tr>
          <td><?php echo $rowCnt; ?></td>
          <td><?php echo $data['Prisoners']['prisoner_no'];?></td>
          <td><?php echo date('d/m/Y H:i:s',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
          <td><?php echo $data['PhysicalProperty']['source'];?></td>
          <td><?php echo $data['PhysicalProperty']['description'];?></td>
          <td>
            <table class="table table-bordered data-table">
              <thead>
                  <tr>
                    <th style="text-align: left;">
                      <input type="checkbox" name="">
                    </th>
                    <th style="text-align: left;">
                      Item
                    </th>
                    <th style="text-align: left;">
                    Bag No.
                    </th>
                    <th style="text-align: left;">
                    Quantity
                    </th>
                    <th style="text-align: left;">
                    Type
                    </th>
                    <th style="text-align: left;">
                    Status
                    </th>
                  </tr>
              </thead>
              <tbody>
                <?php 
                foreach($data['PhysicalPropertyItem'] as $items)
                  {?>
                    <tr>
                      <td>
                        <input type="checkbox" name="">
                      </td>
                      <td><?php echo $items['Propertyitem']['name'];?></td>
                      <td><?php echo $items['quantity'];?></td>
                      <td><?php echo $items['bag_no'];?></td>
                      <td><?php echo $items['property_type'];?></td>
                      <td><?php echo $items['status'];?></td>
                    </tr>
                    <?php }
                ?>
              </tbody>
            </table>
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