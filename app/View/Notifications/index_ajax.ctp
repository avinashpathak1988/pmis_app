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
            'controller'            => 'Notifications',
            'action'                => 'indexAjax',
            'user_id'              => $user_id,
            'content'              => $content,      
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
<table id="notificationTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <!-- <th>
        <?php                 
          //echo $this->Paginator->sort('Notification.user_id','User',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Notifications','action' => 'indexAjax')));
          ?>
        
      </th> -->
      <th>
        <?php                 
          echo $this->Paginator->sort('Notification.content','Content',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Notifications','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('Notification.url_link','URL',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Notifications','action' => 'indexAjax')));
          ?>
      </th>
      <th><?php echo $this->Paginator->sort('is_read'); ?></th>
      <th>
         <?php                 
          echo $this->Paginator->sort('Notification.created','Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Notifications','action' => 'indexAjax')));
          ?>
      </th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $notification){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <!-- <td><?php //echo $funcall->getName($notification['Notification']['user_id'],"User","name");?>&nbsp;</td>	 -->
      <td><?php echo ucwords(h($notification['Notification']['content'])); ?>&nbsp;</td>
      <td><a style="text-align:center;color:blue;" href="<?php echo $notification['Notification']['url_link'];?>">Visit</a> &nbsp;</td>   				
      <td><?php if($notification['Notification']['is_read'] == '1'){
      echo "<font color=green>Yes</font>"; 
      }else{
      echo "<font color=red>No</font>"; 
      }?>&nbsp;
      </td>
      <td><?php echo ($notification['Notification']['created'] == '0000-00-00' || $notification['Notification']['created'] == '' ) ? '' : date('d-m-Y h:i A',strtotime($notification['Notification']['created'])); 
      ?>&nbsp;</td>
     <!--  <td class="actions">
        <?php //echo $this->Form->create('NotificationEdit',array('url'=>'/notifications/add','admin'=>false));?> 
        <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $notification['Notification']['id'])); ?>
        <?php //echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php //echo $this->Form->end();?> 

            <?php //echo $this->Form->create('NotificationDelete',array('url'=>'/notifications/index','admin'=>false));?> 
            <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $notification['Notification']['id'])); ?>
            <?php //echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            <?php //echo $this->Form->end();?>
      </td> -->
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