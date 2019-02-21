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
            'controller'            => 'EscortTeams',
            'action'                => 'escortReturnListAjax',
            'prison_id'              => $prison_id,
            'name'              => $name,      
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>
<table id="EscortTeamTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        
        <?php                 
          echo $this->Paginator->sort('EscortTeam.name','EscortTeam',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EscortTeams','action' => 'indexAjax')));
          ?>
      </th>
      <th>
        
        <?php                 
          echo $this->Paginator->sort('EscortTeam.escort_type','Escort Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EscortTeams','action' => 'indexAjax')));
          ?>
      </th>
      <?php
      if($this->Session->read('Auth.User.prison_id')!=''){
      ?>
      <th>
         <?php                 
          echo $this->Paginator->sort('Prison.name','Prison',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EscortTeam','action' => 'indexAjax')));
          ?>
      </th>
      <?php
      }
      ?>
      <th>Members</th>
      <!-- <th><?php //echo $this->Paginator->sort('is_enable'); ?></th> -->
      <!-- <th>Status</th> -->
      <th><?php echo __('Status'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $EscortTeam){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($EscortTeam['EscortTeam']['name'])); ?>&nbsp;</td> 
      <td><?php echo ucwords(h($EscortTeam['EscortTeam']['escort_type'])); ?>&nbsp;</td>	
      <?php 
      if($this->Session->read('Auth.User.prison_id')!=''){
      ?>
      <td><?php echo ucwords(h($EscortTeam['Prison']['name'])); ?>&nbsp;</td>  
      <?php
      }
      ?>         
      <td><?php 
      $team = array();
      if(isset($EscortTeam['EscortTeam']['members']) && $EscortTeam['EscortTeam']['members']!=''){
        foreach (explode(",", $EscortTeam['EscortTeam']['members']) as $key => $value) {
          $team[] = $funcall->getName($value,"User","name");
        }
        echo implode(", ", $team);
      } 
      ?>&nbsp;</td>   				
      
      <!-- <td>status</td> -->
      <td class="actions">
        <?php
                if($EscortTeam['EscortTeam']['is_available'] == 
                  "YES"){
                  echo "Available";
                }else{
                  echo $this->Html->link("Click To Retun",array(
                    'controller'=>'EscortTeams',
                    'action'=>'enable',
                    $EscortTeam['EscortTeam']['id']
                  ),array(
                    'escape'=>false,
                    'class'=>'btn btn-primary btn-mini',
                    'onclick'=>"return confirm('Are you sure you want to Return?');"
                  ));
                }
           ?>
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
...
<?php    
}
?>    