<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>SubCounty</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Add New SubCounty',array(
                                    
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
                  <th>Sub County Name</th>
                  <th>County Name</th>
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
                            <td><?php if($data['SubCounty']['name']!='')echo ucwords(h($data['SubCounty']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
                            <td><?php echo $funcall->getName($data['SubCounty']['county_id'],'County','name'); ?></td>
                            <td><?php echo $funcall->getName($data['SubCounty']['district_id'],'District','name'); ?></td>
                            <td>
                                <?php
if($data['SubCounty']['is_enable'] == 1){
  echo $this->Html->link("Click To Disable",array(
    'controller'=>'SubCounties',
    'action'=>'disable',
    $data['SubCounty']['id']
  ),array(
    'escape'=>false,
    'class'=>'btn btn-primary btn-mini',
    'onclick'=>"return confirm('Are you sure you want to disable?');"
  ));
}else{
  echo $this->Html->link("Click To Enable",array(
    'controller'=>'SubCounties',
    'action'=>'enable',
    $data['SubCounty']['id']
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
  $data['SubCounty']['id']
),array(
    'escape'=>false,
    'class'=>'btn btn-success btn-mini'
  ));
 ?>

 <?php
 echo $this->Html->link('<i class="icon icon-trash"></i>',array(
   'action'=>'delete',
   $data['SubCounty']['id']
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