 <?php
if(is_array($datas) && count($datas)>0){
  $modelArr = $funcall->getLabelsByModel('Menu');
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
            'controller'            => 'Heights',
            'action'                => 'indexAjax',
            'height_type'         => $height_type,
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
    </div>
</div>
 <table id="districtTable" class="table table-bordered table-striped table-responsive">
            <thead>
              <tr>
                <th><?php echo __('SL#'); ?></th>                
                <th><?php echo __('Height'); ?></th>    
                <th>
                  <?php echo __(isset($modelArr['is_enable'])?$modelArr['is_enable']:'Is Enable'); ?> 
                </th>
                <th><?php echo __('Actions'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php
              $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
              foreach($datas as $data){
            ?>
              <tr>
                	<td><?php echo $rowCnt; ?>&nbsp;</td>
        					<td>
                    <?php echo $data['Height']['name'].' '.$data['Height']['height_type']; ?>     
                  </td>			
                
        					<td><?php if($data['Height']['is_enable'] == '1'){
        							                    echo "<font color=green>Yes</font>"; 
        						                }else{
        							                    echo "<font color=red>No</font>"; 
        						                }?>&nbsp;
                	</td>					
          				<td class="actions">
                    <?php echo $this->Form->create('HeightEdit',array('url'=>'/Heights/add','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Height']['id'])); ?>
                    <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                    <?php echo $this->Form->end();?>
                
                    <?php echo $this->Form->create('HeightDelete',array('url'=>'/Heights/index','admin'=>false));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Height']['id'])); ?>
                    <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                    <?php echo $this->Form->end();?>
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