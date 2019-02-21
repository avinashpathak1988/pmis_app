<?php //echo '<pre>'; print_r($datas); exit;
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
            'controller'            => 'PrisonerTransfers',
            'action'                => 'indexAjax',
            'prisoner_no'           => $prisoner_no,
            'date_from'             => $date_from,
            'date_to'               => $date_to,
            'transfer_to_station_id'=> $transfer_to_station_id,
            'escorting_officer'     => $escorting_officer,
            'status'                => $status,
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
    $exUrl = "indexAjax/prisoner_no:$prisoner_no/date_from:$date_from/date_to:$date_to/transfer_to_station_id:$transfer_to_station_id/escorting_officer:$escorting_officer/status:$status";
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
<button type="submit" tabcls="next" id="saveBtn" title="Transfer Now" class="btn btn-warning allBot" style="margin:3px 1px;" onclick="forwardTransfer('','Process');");"><i class='icon-check'></i></button>
<button type="submit" tabcls="next" title="Add to transfer list" id="saveBtn" class="btn btn-warning allBot" onclick="forwardTransfer('','Saved');");"><i class='icon-plus'></i></button>
<?php
}
?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <?php
      if(!isset($is_excel)){
      ?>
      <th><?php echo $this->Form->input('checkbox', array('type'=>'checkbox','label'=>false,'id'=>"selectAll",'class' => 'checkboxbutton')); ?></th>                   
      <?php
      }
      ?>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php
      echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransfers','action' => 'indexAjax', 'prisoner_no' => '', 'prisoner_name' => '')));
      ?></th>
      <th>Origin Station</th>
      <th>Destination Station</th>
      <th>Escorting Team</th>
      <th>Date Of Transfer</th>
      <th>Reason</th>
      <th>Transfer Type</th>
      <th>Status</th>
      <?php
      if(!isset($is_excel)){
      ?> 
        <th width="15%">Action</th>
      <?php }?>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  // debug($data);
    $id = $data['PrisonerTransfer']['id'];  
    $status = Configure::read('STATUS');
?>
    <tr>      
      <?php
      if(!isset($is_excel)){
      ?>
        <td>
        <?php
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['status'] == 'Draft'){
            echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
              'type'=>'checkbox', 'value'=>$data['PrisonerTransfer']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkbox",
              'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
        ));
        }
        ?>
        </td>
        <?php
        }
        ?>
        <td><?php echo $rowCnt; ?>&nbsp;</td>
        <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
        <td><?php echo $data['Prison']['name'];?></td>
        <td><?php echo $data['ToPrison']['name'];?></td>
        <td><?php echo $data['EscortTeam']['name'];?></td>
        <td><?php echo date('d-m-Y', strtotime($data['PrisonerTransfer']['transfer_date'])); ?></td>
        <td><?php echo $data['PrisonerTransfer']['reason'];?></td>
        <td><?php echo ($status['outgoing'][$data['PrisonerTransfer']['status']]); ?></td>
        <td><?php echo $data['PrisonerTransfer']['regional_transfer']." Regional";?></td>
      <?php
      if(!isset($is_excel)){
      ?>   
        <td>
          <?php
          if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['PrisonerTransfer']['status']=='Draft'){
          ?>
          <?php echo $this->Form->button("<i class='icon-check'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot', 'onclick'=>"javascript:forwardTransfer('$id','Process');",'title'=>'transfer now'))?>
          <?php echo $this->Form->button("<i class='icon-plus'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-warning singleBot', 'onclick'=>"javascript:forwardTransfer('$id','Saved');",'title'=>'Add to transfer list'))?>
          <?php echo $this->Form->button("<i class='icon-trash'></i>", array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteTransfer('$id');",'title'=>'delete'))?>
        </td>
      <?php
        }
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
if(!isset($is_excel)){
?>    
<script type="text/javascript">
    $(document).ready(function(){
       $('.allBot').prop('disabled', true);
       $('.allBot').hide();
       $('#selectAll').click(function (e) {$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);});
    });
    $('.checkbox').click(function(){
        var cnt = 0;
        $('.checkbox').each(function() {
            if($(this).is(':checked')){
                cnt = cnt + 1;
            }            
        });
        if(cnt > 1){
            $('.allBot').prop('disabled', false);
            $('.allBot').show();
            $('.singleBot').prop('disabled', true);
            $('.singleBot').hide();
        }else{
            $('.allBot').prop('disabled', true);
            $('.allBot').hide();
            $('#verifyId').val('');
            $('.singleBot').prop('disabled', false);
            $('.singleBot').show();
        }
    });
</script>
<?php
}
?>