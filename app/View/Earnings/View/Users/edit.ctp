<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>

<?php
echo $this->Form->create('User',array(
'class'=>'form-horizontal'
));
?>


<div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit User</h5>
            <div style="float:right;padding-top: 3px;">


            <?php echo $this->Html->link('Users List',array(
                                    
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

               <div class="control-group">
                 <label class="control-label">Name<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('id');
                       echo $this->Form->input('name',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'type'=>'text',
                         'placeholder'=>'Full Name',
                         'required'
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Mail ID<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('mail_id',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'type'=>'email',
                         'required',
                         'placeholder'=>'yourmailid@domain.com',
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Mobile No.:</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('mobile_no',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'type'=>'text',
                         'placeholder'=>'9999995699',
                         'required'=>false,
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Login ID<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('login_id',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'type'=>'text',
                         'placeholder'=>'Login ID/User ID',
                         'required'
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Password<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('password',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'type'=>'password',
                         'placeholder'=>'Password',
                         'required'
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Department :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('department_id',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$department_id,
                         'empty'=>'-- Select Department --',
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Designation<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('designation_id',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$designation_id,
                         'empty'=>'-- Select Designation --',
                         'required'
                       ));
                    ?>
                 </div>
               </div>
				
               <div class="control-group">
                 <label class="control-label">Is Enable ?</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('is_enable',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$is_enable,
                         'default'=>1
                       ));
                    ?>
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Is Admin ?</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('is_admin',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$is_admin,
                         'default'=>0
                       ));
                    ?>
                 </div>
               </div>


              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>

          </div>
        </div>
      </div>


    </div>
  </div>
<?php
    echo $this->Form->end();
 ?>