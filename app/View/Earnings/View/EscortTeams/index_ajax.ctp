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
            'action'                => 'indexAjax',
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
      <th><?php echo $this->Paginator->sort('is_enable'); ?></th>
      <th><?php echo __('Action'); ?></th>
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
      <td><?php if($EscortTeam['EscortTeam']['is_enable'] == '1'){
      echo "<font color=green>Yes</font>"; 
      }else{
      echo "<font color=red>No</font>"; 
      }?>&nbsp;
      </td>
      <td class="actions">
        <?php
        $funcall->loadModel("Gatepass");
        if(!$funcall->Gatepass->field("escort_team",array("Gatepass.escort_team"=>$EscortTeam['EscortTeam']['id']))){
        ?>
        <?php echo $this->Form->create('EscortTeamEdit',array('url'=>'/EscortTeams/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $EscortTeam['EscortTeam']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?> 

            <?php echo $this->Form->create('EscortTeamDelete',array('url'=>'/EscortTeams/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $EscortTeam['EscortTeam']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            <?php echo $this->Form->end();?>
          <?php
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