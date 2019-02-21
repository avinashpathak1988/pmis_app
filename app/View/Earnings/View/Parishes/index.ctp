<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Parish</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Add New Parish',array(
                                    
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
                  <th>County Name</th>
                  <th>Sub County Name</th>
                  <th>District Name</th>
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
                             <td><?php if($data['Parish']['name']!='')echo ucwords(h($data['Parish']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
                            <td><?php echo $funcall->getName($data['Parish']['county_id'],'County','name'); ?></td>
                            <td><?php echo $funcall->getName($data['Parish']['sub_county_id'],'SubCounty','name'); ?></td>
                            <td><?php echo $funcall->getName($data['Parish']['district_id'],'District','name'); ?></td>
                            <td>
                                <?php
if($data['Parish']['is_enable'] == 1){
  echo $this->Html->link("Click To Disable",array(
    'controller'=>'parishes',
    'action'=>'disable',
    $data['Parish']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-primary btn-mini',
    'onclick'=>"return confirm('Are you sure you want to disable?');"
  ));
}else{
  echo $this->Html->link("Click To Enable",array(
    'controller'=>'parishes',
    'action'=>'enable',
    $data['Parish']['id']
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
  $data['Parish']['id']
),array(
    'escape'=>false,
    'class'=>'btn btn-success btn-mini'
  ));
 ?>

 <?php
 echo $this->Html->link('<i class="icon icon-trash"></i>',array(
   'action'=>'delete',
   $data['Parish']['id']
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
