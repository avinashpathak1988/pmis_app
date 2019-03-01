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
            'controller'            => 'SocialPrograms',
            'action'                => 'indexAjax',
            'id'                    =>  $id,
            //'from_date'             => $from_date,
            //'to_date'               => $to_date,
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
<?php
    $exUrl = "indexAjax/id:$id/";
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
            <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgram.program_no','Program Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgram.program_name','Program Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgram.start_date','Start Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgram.end_date','End Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgram.comment','Comment',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgramLevel.name','Social Program Level',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('SocialProgramCategory.name','Social Program Category',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php  
           echo $this->Paginator->sort('SocialProgram.is_enable','Is Enable?',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'SocialPrograms','action' => 'indexAjax')));
          ?></th>
          
<?php
if(!isset($is_excel)){
?>            
            <th>Action</th>
<?php
}
?>            
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
      //debug($data);
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            
            <td><?php if($data['SocialProgram']['program_no']!='')echo ucwords(h($data['SocialProgram']['program_no']));else echo Configure::read('NA'); ?>&nbsp;</td>


           
            <td><?php if($data['SocialProgram']['program_name']!='')echo ucwords(h($data['SocialProgram']['program_name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['SocialProgram']['start_date']));//$data['SocialProgram']['start_date']; ?></td>

             <td><?php echo (isset($data['SocialProgram']['start_date']) && $data$data['SocialProgram']['start_date'] != '0000-00-00') ? echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['SocialProgram']['start_date'])) :  Configure::read('NA'); ?>&nbsp;</td>  

            <td><?php echo date(configure::read('UGANDA-DATE-FORMAT'),strtotime($data['SocialProgram']['end_date'])) ?></td>

             <td><?php echo (isset($data['Holiday']['holiday_date']) && $data['Holiday']['holiday_date'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['Holiday']['holiday_date'])))) :  Configure::read('NA'); ?>&nbsp;</td>  

         
            <td><?php if($data['SocialProgram']['comment']!='')echo ucwords(h($data['SocialProgram']['comment']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php if($data['SocialProgramLevel']['name']!='')echo ucwords(h($data['SocialProgramLevel']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            <td><?php if($data['SocialProgramCategory']['name']!='')echo ucwords(h($data['SocialProgramCategory']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            <td><?php
                if($data['SocialProgram']['is_enable'] == 1){
            echo "<font color='green'>Yes</font>";
        }else{
            echo "<font color='red'>No</font>";
        }?>
            </td>

<?php
        if(!isset($is_excel)){
?>            
            
<?php
// if($data['SocialProgramLevels']['is_enable'] == 1){
//     echo $this->Html->link("Disable",array('controller'=>'SocialProgramLevels','action'=>'disable',$data['SocialProgramLevels']['id']),array('escape'=>false,'class'=>'btn btn-success btn-mini','onclick'=>"return confirm('Are you sure you want to disable?');"));
// }else{
//     echo $this->Html->link("Enable",array('controller'=>'SocialProgramLevels','action'=>'enable',$data['SocialProgramLevels']['id']),array('escape'=>false,'class'=>'btn btn-danger btn-mini','onclick'=>"return confirm('Are you sure you want to enable?');"));
// }
?>

            <td>
				
                <?php echo $this->Form->create('SocialProgramEdit',array('url'=>'/SocialPrograms/add','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SocialProgram']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
				<?php echo $this->Form->end();?>
            
                <?php echo $this->Form->create('SocialProgramDelete',array('url'=>'/SocialPrograms/index','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SocialProgram']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
				<?php echo $this->Form->end();?>
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
?>
    <span style="color:red;">No records found!</span>
<?php    
}
?>                    