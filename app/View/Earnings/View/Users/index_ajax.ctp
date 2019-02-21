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
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Users',
            'action'                => 'indexAjax',
            'from_date'             => $from_date,
            'to_date'               => $to_date,
            'prison_id'             => $prison_id,
            'usertype_id'           => $usertype_id
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "indexAjax/from_date:$from_date/to_date:$to_date";
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
    }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Prison</th>
            <th>
            <?php                 
          echo $this->Paginator->sort('User.first_name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php                 
          echo $this->Paginator->sort('User.mail_id','Mail ID',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('User.username','Username',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php                 
          echo $this->Paginator->sort('User.mobile_no','Mobile',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?></th>
            <th><?php                 
          echo $this->Paginator->sort('Usertype.name','User Type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?>
      </th>
            <th><?php                 
          echo $this->Paginator->sort('Designation.name','Designation',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?>
      </th>
            <th>
            <?php                 
          echo $this->Paginator->sort('Department.name','Department',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?>
              
          </th>
            <th>
             <?php                 
          echo $this->Paginator->sort('User.created','Created Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Users','action' => 'indexAjax')));
          ?></th>
<?php
if(!isset($is_excel)){
?>            
            <th>Status</th>
            <th colspan="2">Action</th>
<?php
}
?>            
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
         
            <td><?php if($data['Prison']['name']!='')echo ucwords(h($data['Prison']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            
            <td><?php if($data['User']['first_name']!='')echo ucwords(h($data['User']['first_name']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php if($data['User']['mail_id']!='')echo ucwords(h($data['User']['mail_id']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php if($data['User']['username']!='')echo ucwords(h($data['User']['username']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php if($data['User']['mobile_no']!='')echo ucwords(h($data['User']['mobile_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
            
            <td><?php if($data['Usertype']['name']!='')echo ucwords(h($data['Usertype']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            
            <td><?php if($data['Designation']['name']!='')echo ucwords(h($data['Designation']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>          
            <td><?php if($data['Department']['name']!='')echo ucwords(h($data['Department']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            <td><?php echo date('d-m-Y', strtotime($data['User']['created']))?></td>

            <td><?php echo (isset($data['User']['created']) && $data['User']['created'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['User']['created'])))) :  Configure::read('NA'); ?>&nbsp;</td>  
<?php
        if(!isset($is_excel)){
?>            
            <td>
<?php
if($data['User']['is_enable'] == 1){
    echo $this->Html->link("Disable",array('controller'=>'users','action'=>'disable',$data['User']['id']),array('escape'=>false,'class'=>'btn btn-success btn-mini','onclick'=>"return confirm('Are you sure you want to disable?');"));
}else{
    echo $this->Html->link("Enable",array('controller'=>'users','action'=>'enable',$data['User']['id']),array('escape'=>false,'class'=>'btn btn-danger btn-mini','onclick'=>"return confirm('Are you sure you want to enable?');"));
}
?>
            </td>
            <td>
				
                <?php echo $this->Form->create('UserEdit',array('url'=>'/users/add','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['User']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
				<?php echo $this->Form->end();?>
            
                <?php //echo $this->Form->create('UserDelete',array('url'=>'/users/index','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['User']['id'])); ?>
                <?php //echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
				<?php //echo $this->Form->end();?>
            </td>
<?php
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
echo $this->Js->writeBuffer();
}else{
echo Configure::read("NO-RECORD");  
}
?>                    