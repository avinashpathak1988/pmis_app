<div style="float:right">
    <?php echo $this->Html->link('Users List',array(
        'action'=>'index'
    ));
    ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>

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
            <h5>Add New User</h5>
          </div>
          <div class="widget-content nopadding">

               <div class="control-group">
                 <label class="control-label">Name<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
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
                 <label class="control-label">Department<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('department_id',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$department_id,
                         'empty'=>'-- Select Department --',
                         'required'
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

      <div class="span6">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
              <h5>Roles</h5>
            </div>
            <div class="widget-content nopadding">

            

                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Upload</th>
                      <th>Download</th>
                      <th>View</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach($role_modules as $id=>$name) {
                    ?>
                     <tr class="<?php echo ($id%2==0)? 'even' : 'odd'; ?> gradeX">
                        <td><?php echo $name; ?></td>
                        <td class="center"><div class="checker"><span><input type="checkbox" name="upload[<?php echo $id;?>]" style="opacity: 0;"></span></div></td>
                        <td class="center"><div class="checker"><span><input type="checkbox" name="download[<?php echo $id;?>]" style="opacity: 0;"></span></div></td>
                        <td class="center"><div class="checker"><span><input type="checkbox" name="view[<?php echo $id;?>]" style="opacity: 0;"></span></div></td>
                        <td class="center"><div class="checker"><span><input type="checkbox" name="remove[<?php echo $id;?>]" style="opacity: 0;"></span></div></td>
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
            <?php
echo $this->Form->end();
             ?>