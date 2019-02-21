<?php
if(is_array($datas) && count($datas)>0){
  //debug(count($datas));
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
            'controller'            => 'VisitorsController',
            'action'                => 'gateBookReportAjax',
            'from'                  => $from,
            'to'                    => $to,       
        )
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
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo 'Sl no'; ?></th>  
      <th><?php echo 'Category'; ?></th>                
      <th><?php echo 'Date'; ?></th>
      
      <th><?php echo 'Reason'; ?></th>

      <th><?php echo 'Prisoner No'; ?></th>
       <th><?php echo 'Prisoner Name'; ?></th>
        <th><?php echo 'Gate keeper Name'; ?></th>
      <th><?php echo 'Time In'; ?></th>
      <th><?php echo 'Time Out'; ?></th>
      <th><?php echo 'Duration'; ?></th>
      
       <th><?php echo 'View'; ?></th>
      <!-- <th width="8%"><?php //echo 'Action'; ?></th> -->
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Visitor']['category'])); ?>&nbsp;</td>
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['Visitor']['date'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['Visitor']['reason'])); ?>&nbsp;</td>

      <td><?php echo $data['Visitor']['prisoner_no']; ?>&nbsp;</td>
      <td><?php
      if(isset($data['Visitor']['name']) && $data['Visitor']['name'] !=''){
       echo $funcall->getPrisonerName($data['Visitor']['name']); 
     }
       ?>&nbsp;</td> 
      <td><?php echo ucwords(h($data['Visitor']['gate_keeper'])); ?>&nbsp;</td>                 
      <td><?php echo $data['Visitor']['time_in']; ?>&nbsp;</td>
      <!-- <td>
      <?php
              if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
        ?>
      <center>
        <?php 
        if($data['Visitor']['time_out'] == ''){
        echo $this->Form->button('Time Out', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger btn-mini', 'onclick'=>"javascript:timeOut(".$data['Visitor']['id'].");"));
      }else{
        echo $data['Visitor']['time_out'];
      }

        ?>
        </center>
        <?php } ?>
      </td> -->
      <td><?php echo $data['Visitor']['time_out']; ?>&nbsp;</td>
      <td><?php
       if($data['Visitor']['duration'] != ''){
     //   echo $data['Visitor']['duration']." Min"; 
     // }
      //echo $data['Visitor']['duration'];
      $duration = $data['Visitor']['duration'];
      $durationArray = explode(':', $duration);
      //debug($durationArray);
      echo $durationArray[0]." Hr ".":".$durationArray[1]." Min";
    }
       ?></td>
       
       <td class="actions">
            
  <?php
  echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                        'action'=>'../visitors/view',
                        $data['Visitor']['id']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-success btn-mini'
                    ));
                    ?>
          </td>
       <?php if($data['Visitor']['time_out'] == ''){ ?>
       <?php
              if($this->Session->read('Auth.User.usertype_id') == Configure::read('MAIN_GATEKEEPER_USERTYPE')){
               ?>
        <!-- <td class="actions">
          <?php echo $this->Form->create('VisitorEdit',array('url'=>'/visitors/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Visitor']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('type'=>'button','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return editForm();")); ?> 
          <?php echo $this->Form->end();?>

          <?php echo $this->Form->create('VisitorDelete',array('url'=>'/visitors/index','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Visitor']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('type'=>'button','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return deleteForm();")); ?>
      </td> -->
      <?php } ?>
       <?php }else{ ?>
        <!--  <td></td> -->
        <?php } ?>
          <?php echo $this->Form->end();?>
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
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    
