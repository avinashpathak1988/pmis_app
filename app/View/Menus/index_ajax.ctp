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
            'controller'            => 'Menus',
            'action'                => 'indexAjax',
            'parent_id'             => $parent_id,
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
                <th>
                  <?php 
                  //echo $this->Paginator->sort('Menu.parent_id',isset($modelArr['parent_id'])?$modelArr['parent_id']:'Parent',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Menus','action' => 'indexAjax')));
                  echo __(isset($modelArr['parent_id'])?$modelArr['parent_id']:'Parent'); 
                  ?>  
                </th>
                <th>
                  <?php 
                  echo $this->Paginator->sort('Menu.name',isset($modelArr['name'])?$modelArr['name']:'Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Menus','action' => 'indexAjax')));
                  ?>
                </th>
                <th>
                  <?php 
                  echo $this->Paginator->sort('Menu.url',isset($modelArr['url'])?$modelArr['url']:'Url',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Menus','action' => 'indexAjax')));
                  ?>  
                </th>
                <th>
                  <?php 
                  echo $this->Paginator->sort('Menu.module_id',isset($modelArr['module_id'])?$modelArr['module_id']:'Module name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Menus','action' => 'indexAjax')));
                  ?>  
                </th>
                <th>
                  <?php 
                  echo $this->Paginator->sort('Menu.order',isset($modelArr['order'])?$modelArr['order']:'Order',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Menus','action' => 'indexAjax')));
                  ?>  
                </th>
                <th>
                  <?php echo __(isset($modelArr['is_enable'])?$modelArr['is_enable']:'Is Enable'); ?> 
                </th>
                <th><?php echo __('Actions'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php
              $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
              foreach($datas as $menu){
            ?>
              <tr>
                	<td><?php echo $rowCnt; ?>&nbsp;</td>
        					<td><?php if(isset($menu['MainMenu']['parentname']) && $menu['MainMenu']['parentname']!='')echo ucwords(h($menu['MainMenu']['parentname']));else echo Configure::read('NA'); ?>&nbsp;</td>
        					<td><?php if($menu['Menu']['name']!='')echo ucwords(h($menu['Menu']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>	
        					<td><?php if($menu['Menu']['url']!='')echo ucwords(h($menu['Menu']['url']));else echo Configure::read('NA'); ?>&nbsp;</td>
                  <td><?php echo $funcall->getName($menu['Menu']['module_id'],"Module","name"); ?>&nbsp;</td>
        					<td><?php echo ucwords(h($menu['Menu']['order'])); ?>&nbsp;</td>							
        					<td><?php if($menu['Menu']['is_enable'] == '1'){
        							                    echo "<font color=green>Yes</font>"; 
        						                }else{
        							                    echo "<font color=red>No</font>"; 
        						                }?>&nbsp;
                	</td>					
          				<td class="actions">          					
                    <!-- edit form -->
                    <div style="float:left;margin-right:3px;">
                      <?php echo $this->Form->create('MenuEdit',array('url'=>'/Menus/addMenu','admin'=>false)); ?>  
                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $menu['Menu']['id'])); ?>
                      <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                      <?php echo $this->Form->end();?>
                    </div>
                    <!-- edit form -->

                    <!--delete form -->
                    <div style="float:left;">
                      <?php echo $this->Form->create('MenuDelete',array('url'=>'/Menus/addMenu','admin'=>false)); ?>  
                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $menu['Menu']['id'])); ?>
                      <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                      <?php echo $this->Form->end();?> 
                    <!--delete form -->
                    </div>
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