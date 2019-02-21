<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Prison Station List</h5>
            <div style="float:right;padding-top: 7px;">
            <?php 
             echo $this->Html->link('Add New Prison Station',array(
                   'action'=>'edit'
               ),array(
                   'escape'=>false,
                   'class'=>'btn btn-success btn-mini'
               )); ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive">
              <thead>
                <tr>
                  <th>SL#</th>
                  <th>Region Area</th>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Capacity</th>
                  <th>Date Of Opening</th>
                  <th>Geographical District</th>
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
                            <td><?php echo $funcall->getName($data['Prison']['state_id'],"State","name"); ?></td>
                            <td><?php echo $data['Prison']['name']; ?></td>
                            <td><?php echo $data['Prison']['code']; ?></td>
                            <td><?php echo $data['Prison']['capacity']; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($data['Prison']['date_of_opening'])); ?></td>
                            <td><?php echo $data['GeographicalDistrict']['name']; ?></td>
                            <td>
                                <?php
                                if($data['Prison']['is_enable'] == 1){
                                  echo $this->Html->link("Click To Disable",array(
                                    'controller'=>'prisons',
                                    'action'=>'disable',
                                    $data['Prison']['id']
                                  ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-primary btn-mini',
                                    'onclick'=>"return confirm('Are you sure you want to disable?');"
                                  ));
                                }else{
                                  echo $this->Html->link("Click To Enable",array(
                                    'controller'=>'prisons',
                                    'action'=>'enable',
                                    $data['Prison']['id']
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
                                $data['Prison']['id']
                              ),array(
                                  'escape'=>false,
                                  'class'=>'btn btn-success btn-mini'
                                ));
                               ?>

                               <?php

                               // echo $this->Html->link('<i class="icon icon-trash"></i>',array(
                               //     'action'=>'trash',
                               //     $data['Prison']['id']
                               //   ),array(
                               //      'escape'=>false,
                               //      'class'=>'btn btn-danger btn-mini',
                               //      'onclick'=>"return confirm('Are you sure you want to delete?');"
                               //    ));

                              echo '&nbsp;'.$this->Html->link('<i class="icon icon-eye-open"></i>',array(
                                   'action'=>'detail',
                                   $data['Prison']['id']
                                 ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
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
