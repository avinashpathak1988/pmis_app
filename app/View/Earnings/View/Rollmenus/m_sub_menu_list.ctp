

<?php if(!empty($submenuList)){ ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Sub Menu List</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Add Sub Menu',array(
                                    
                                    'action'=>'m_sub_menu'
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
                    <th>Sub Menu Name</th>
                    <th>Sub Menu URL</th>
                    <th>Menu Icon</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i=0;
                    foreach($submenuList as $key => $val){
                        $i++;
                          ?>
                          <tr>
        <td><?php echo $key+1?></td>
        <td><?php echo h($val['MMenu']['name'])?></td>
        <td><?php echo h($val['MSubMenu']['name'])?></td>
        <td><?php echo h($val['MSubMenu']['sub_menu_url'])?></td>
        <td><i class="fa <?php echo h($val['MSubMenu']['sub_menu_icon'])?>"></i></td>
        <td><?php echo h($val['MSubMenu']['sub_menu_order'])?></td>
        <td>
<?php 
    if($val['MSubMenu']['is_enable'] == 'Y'){
      
      echo 'Active';
    }else{
      
      echo 'Inactive';
    }
?>
        </td>
        <td class="blk2" width="10px">
          <?php echo $this->Form->create('SubMenuedit',array('url'=>'/Rollmenus/m_sub_menu','class'=>'master_table','admin'=>false)); ?>  
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> h($val['MSubMenu']['id']))); ?>
          <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-success  btn-mini','div'=>false));?>
        </td>
        <td class="blk2" width="10px">
          <?php echo $this->Form->create('SubMenudelete',array('url'=>'/Rollmenus/m_sub_menu','class'=>'master_table','admin'=>false)); ?>  
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $val['MSubMenu']['id'])); ?>
          <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger  btn-mini','div'=>false,'onclick'=>'return confirm("Are you sure to delete this ?")'));?>
        </td>
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