<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Departments</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Add New Department',array(
                                    
                                    'action'=>'add'
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
                  <th>SL#</th>
                  <th>Name</th>
                  <th>Is Enabled ?</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i=0;
                    foreach($datas as $data){
                        $i++;
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data['Department']['name']; ?></td>
                            <td>
                                <?php
if($data['Department']['is_enable'] == 1){
  echo $this->Html->link("Click To Disable",array(
    'controller'=>'departments',
    'action'=>'disable',
    $data['Department']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-primary btn-mini',
    'onclick'=>"return confirm('Are you sure you want to disable?');"
  ));
}else{
  echo $this->Html->link("Click To Enable",array(
    'controller'=>'departments',
    'action'=>'enable',
    $data['Department']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-danger btn-mini',
    'onclick'=>"return confirm('Are you sure you want to enable?');"
  ));
}
                                 ?>
                            </td>
                            <td>
<?php
echo $this->Html->link('<i class="icon icon-edit"></i>',array(
  'action'=>'edit',
  $data['Department']['id']
),array(
    'escape'=>false,
    'class'=>'btn btn-success btn-mini'
  ));
 ?>

 <?php
 echo $this->Html->link('<i class="icon icon-trash"></i>',array(
   'action'=>'delete',
   $data['Department']['id']
 ),array(
    'escape'=>false,
    'class'=>'btn btn-danger btn-mini',
    'onclick'=>"return confirm('Are you sure you want to delete?');"
  ));
  ?>
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
