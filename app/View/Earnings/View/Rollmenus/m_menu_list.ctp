<?php if(!empty($menuList)){ ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Menu List</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Add Menu',array(
                                    
                                    'action'=>'m_menu'
                                ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
                                )); ?>
              <?php //echo $this->Html->link('Users List',array(
                  //'action'=>'index',
                 // array('escape'=>false,'class'=>'btn btn-success'),
              //));
              ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive">
              <thead>
                <tr>
                     <th>Sl No.</th>
                    <th>Menu Name</th>
                    <th>Menu URL</th>
                    <th>Menu Icon</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Edit</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i=0;
                    foreach($menuList as $key => $val){
                        $i++;
                          ?>
                          <tr>
                    <td><?php echo $key+1?></td>
                    <td><?php echo h($val['MMenu']['name'])?></td>
                    <td><?php echo h($val['MMenu']['menu_url'])?></td>
                    <td><i class="fa <?php echo h($val['MMenu']['menu_icon'])?>"></i></td>
                    <td><?php echo h($val['MMenu']['menu_order'])?></td>
                    <td>
                    <?php 
                    if(($val['MMenu']['is_enable']) == 1){                        
                        echo 'Active';
                    }else{                        
                        echo 'Inactive';
                    }
                    ?>
                    </td>
                    <td>
                        <?php echo $this->Form->create('Menuedit',array('url'=>'/Rollmenus/m_menu','class'=>'master_table','admin'=>false)); ?>  
                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> h($val['MMenu']['id']))); ?>
                        <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false));?>
                    </td>
                    <?php /*<!--<td class="blk2" width="10px">
                        <?php echo $this->Form->create('Menudelete',array('url'=>'/Masters/m_menu','class'=>'master_table','admin'=>false)); ?>  
                        <?php echo $this->Form->input('m_menu_id',array('type'=>'hidden','value'=> $val['MMenu']['m_menu_id'])); ?>
                        <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-primary','div'=>false,'onclick'=>'return confirm("Are you sure to delete this ?")'));?>
                    </td>-->*/?>
                </tr>
                          <?php
                    }
                 ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php }?>