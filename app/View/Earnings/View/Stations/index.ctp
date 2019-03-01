<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Prison Station Details</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Add New Prison Station',array(
                                    
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
                  <th>Code</th>
                  <th>Capacity</th>
                  <th>Date Of Opening</th>
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
                            <td><?php echo $data['Station']['name']; ?></td>
                            <td><?php echo $data['Station']['code']; ?></td>
                            <td><?php echo $data['Station']['capacity']; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($data['Station']['date_of_opening'])); ?></td>
                            <td>
                                <?php
if($data['Station']['is_enable'] == 1){
  echo $this->Html->link("Click To Disable",array(
    'controller'=>'stations',
    'action'=>'disable',
    $data['Station']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-primary btn-mini',
    'onclick'=>"return confirm('Are you sure you want to disable?');"
  ));
}else{
  echo $this->Html->link("Click To Enable",array(
    'controller'=>'stations',
    'action'=>'enable',
    $data['Station']['id']
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
echo $this->Html->link('Edit',array(
  'action'=>'edit',
  $data['Station']['id']
),array(
    'escape'=>false,
    'class'=>'btn btn-success btn-mini'
  ));
 ?>

 <?php

 echo $this->Html->link('Trash',array(
     'action'=>'trash',
     $data['Station']['id']
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
