<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Modify Prison Station</h5>
            <div style="float:right;padding-top: 7px;">


           <?php echo $this->Html->link('Prison Station List',array(
                                    
                                    'action'=>'index'
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
             <?php
                  echo $this->Form->create('Station',array(
                    'class'=>'form-horizontal'
                  ));
                 echo $this->Form->input('id');
               ?>
              <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Station Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'type'=>'text',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Station Code<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('code',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumeric',
                                'type'=>'text',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                </div>  
                <div class="row-fluid">
                    <div class="span6">
                         <div class="control-group">
                          <label class="control-label">Capacity Of Station<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('capacity',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11 numeric',
                                  'type'=>'text',
                                  'required',
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
                   <div class="span6">   
                      <div class="control-group">
                          <label class="control-label">Date Of Opening<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('date_of_opening',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'class'=>'span11 datepicker',
                                  'data-date-format'=>"dd-mm-yyyy",
                                  'readonly'=>'readonly',
                                  'required',
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
                 </div> 
                    
                  <div class="row-fluid">
                    <div class="span6">
                         <div class="control-group">
                          <label class="control-label">Security Level<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php

                                 echo $this->Form->input('security_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'options'=>$security_id,
                                   'empty'=>'-- Select Security Level--',
                                   'required',
                                   'class'=>'span11',
                                 ));
                             ?>
                          </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                          <label class="control-label">Category<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                 echo $this->Form->input('stationcategory_id',array(
                                   'div'=>false,
                                   'label'=>false,
                                   'options'=>$stationcategory_id,
                                   'empty'=>'-- Select Category--',
                                   'required',
                                   'class'=>'span11',
                                 ));
                             ?>
                          </div>
                        </div>
                    </div>
                  </div>

                   <div class="row-fluid">
                      <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Physical Address<?php echo MANDATORY; ?> :</label>
                            <div class="controls">
                              <?php
                                  echo $this->Form->input('physical_address',array(
                                    'type'=>'textarea',
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11 alpha',
                                    'required',
                                    'rows'=>3
                                  ));
                               ?>
                            </div>
                          </div>
                      </div>
                      <div class="span6">
                          <div class="control-group">
                            <label class="control-label">Postal Address :</label>
                            <div class="controls">
                              <?php
                                  echo $this->Form->input('postal_address',array(
                                    'type'=>'textarea',
                                    'div'=>false,
                                    'label'=>false,
                                    'class'=>'span11 alpha',
                                    'rows'=>3
                                  ));
                               ?>
                            </div>
                          </div>
                      </div>
                   </div>

                   <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">GPS location :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('gps_location',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Phone Number :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('phone',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 phone',
                                'type'=>'text',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Fax Number :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('fax',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Email address :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('email',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'email',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="row-fluid">
                   <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Magisterial Area :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('magisterial_area',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                              ));
                           ?>
                        </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                      <label class="control-label">Is Enabled ?</label>
                      <div class="controls">
                        <?php
                            echo $this->Form->input('is_enable',array(
                              'div'=>false,
                              'label'=>false,
                              'class'=>'span11',
                              'options'=>$is_enable,
                              'default'=>1,
                              'style'=>'width:120px',
                            ));
                         ?>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
