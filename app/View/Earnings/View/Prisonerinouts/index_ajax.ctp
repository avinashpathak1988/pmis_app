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
            'controller'            => 'Prisonerinouts',
            'action'                => 'indexAjax',
            'folow_from'         => $folow_from,
            'folow_to'         => $folow_to,
            'category'         => $category,
            'status'=>$status,           
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
    
      $exUrl = "indexAjax/folow_from:$folow_from/folow_to:$folow_to/category:$category/status:$status";
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
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Prisonerinouts/add'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<table id="districtTable" class="table table-bordered table-striped table-responsive" style="border:1px solid #dddddd;">
  <thead>
    <tr>
    
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>
                   
      <th><?php                 
                echo $this->Paginator->sort('Prisonerinout.prisoner_no','Prisoner No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('Prisonerinout.name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('Prisonerinout.date','Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
      <th><?php                 
                echo $this->Paginator->sort('Prisonerinout.destination','Destination',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
     <!--  <th><?php                 
               // echo $this->Paginator->sort('Prisonerinout.staff_escort_details','Staff Escort Details',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th> -->
    <!--   <th><?php                 
               // echo $this->Paginator->sort('Prisonerinout.gate_pass_no','Gate Pass No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th> -->
      <!-- <th><?php                 
                //echo $this->Paginator->sort('Prisonerinout.time_in','Time In',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
      <th><?php                 
               // echo $this->Paginator->sort('Prisonerinout.time_out','Time Out',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th> -->
     <!--  <th><?php                 
                //echo $this->Paginator->sort('Prisonerinout.gate_keeper_id','Gate Keeper Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th> -->
            <th><?php                 
                echo $this->Paginator->sort('Prisonerinout.to_whom','To whom you are meeting',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
            <th><?php                 
                echo $this->Paginator->sort('Prisonerinout.reason','Reason',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?></th>
           <!--  <th style="text-align: left;"><?php                 
                //echo $this->Paginator->sort('Prisonerinout.status','Status',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisonerinouts','action' => 'indexAjax','status'=> $status,'folow_from'=> $folow_from,'folow_to'=> $folow_to,'category'=> $category)));
            ?>
              
              </th> -->
      <th width="8%">Action</th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['Prisonerinout']['status']);
?>
    <tr>
       
      <td><?php echo $rowCnt; ?>&nbsp;</td>
       <?php
        if($data['Prisoner']['prisoner_no']==""){
          ?>
          <td>N/A</td>
          <?php
        }else{
          ?>
          <td><?php echo ucwords(h($data['Prisoner']['prisoner_no'])); ?>&nbsp;</td>
          <?php
        }
        ?>
      <td><?php echo ucwords(h($data['Prisonerinout']['name'])); ?>&nbsp;</td> 
      <td><?php echo date('d-m-Y',strtotime($data['Prisonerinout']['date'])); ?>&nbsp;</td> 
      <?php  
        if($data['Prisonerinout']['destination']==""){
          ?>
          <td>N/A</td>
          <?php
        }else{
          ?>
          <td><?php echo ucwords(h($data['Prisonerinout']['destination'])); ?>&nbsp;</td>
          <?php
        }
      ?>
      <?php if($data['Prisonerinout']['time_in']==""){ ?>
      <td>N/A</td>
      <?php }else{ ?>
     <!--  <td><?php //echo ucwords(h($data['Prisonerinout']['time_in'])); ?>&nbsp;</td>
      <?php } ?>
      <td><?php //echo ucwords(h($data['Prisonerinout']['time_out'])); ?>&nbsp;</td> -->
     <!--  <td><?php //echo ucwords(h($data['User']['first_name'])); ?>&nbsp;</td> -->
      <td><?php echo ucwords(h($data['Prisonerinout']['to_whom'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Prisonerinout']['reason'])); ?>&nbsp;</td>
    
      <?php
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('PRINCIPALOFFICER_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')){
      ?>
        <td class="actions">
          <?php echo $this->Form->create('PrisonerinoutEdit',array('url'=>'/prisonerinouts/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Prisonerinout']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
          <?php echo $this->Form->end();?>
        
          <?php echo $this->Form->create('PrisonerinoutDelete',array('url'=>'/prisonerinouts/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Prisonerinout']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
          <?php echo $this->Form->end();?>
      </td>
      <?php
      }else{
        ?>
        <td>N/A</td>
        <?php
      }
      ?>

    </tr>
<?php
echo $this->Js->writeBuffer();
$rowCnt++;
}
?>
  </tbody>
</table>
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
            'controller'            => 'RecordstaffsController',
            'action'                => 'indexAjax',
            'folow_from'         => $folow_from,
            'folow_to'         => $folow_to,
            'category'         => $category,
            'status'=>$status,           
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>
<?php
}else{
?>
<span style="color:red;font-weight:bold;margin-left: 25px;">No Record Found!!</span>
<?php    
}
?> 

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
