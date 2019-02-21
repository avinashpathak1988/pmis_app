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
            'controller'            => 'InformalCounsellings',
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
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")), $urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")), $urlExcel, array("escape" => false)));
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
          echo $this->Paginator->sort('InformalCounselling.sponser','Sponser',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InformalCounsellings','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('InformalCounselling.counselling_id','Counselling By',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InformalCounsellings','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('InformalCounselling.prisoner_id','Prisoner No.',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InformalCounsellings','action' => 'indexAjax')));
          ?></th>
          <th>
            <?php                 
          echo $this->Paginator->sort('InformalCounselling.theme_id','Social Theme',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InformalCounsellings','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php  
           echo $this->Paginator->sort('InformalCounselling.is_enable','Is Enable?',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'InformalCounsellings','action' => 'indexAjax')));
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
            <td><?php echo $data['InformalCounselling']['sponser']; ?></td>
            <td><?php echo $data['User']['name']; ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['SocialTheme']['name']; ?></td>
            <td><?php
                if($data['InformalCounselling']['is_enable'] == 1){
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
				
                <?php echo $this->Form->create('InformalCounsellingEdit',array('url'=>'/InformalCounsellings/add','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['InformalCounselling']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
				<?php echo $this->Form->end();?>
            
                <?php echo $this->Form->create('InformalCounsellingDelete',array('url'=>'/InformalCounsellings/index','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['InformalCounselling']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
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