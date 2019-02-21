<?php
echo $this->Html->link('Manage Marital Status',array(
  'action'=>'index'
));
 ?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">

      <div class="col-md-7">
      <?php echo $this->Form->create('MaritalStatus'); ?>
          <div class="box-body">
            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Marital Status  <font style='color:red'>*</font></label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('name',array(
                      'div'=>false,
                      'label'=>false,
                      'placeholder'=>'Enter Marital Status',
                      'class'=>'form-control',
                      'required'
                    ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Reporting To</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('reporting_to',array(
                      'div'=>false,
                      'label'=>false,
                      'empty'=>'--Reporting To--',
                      'class'=>'form-control',
                      'required'=>false,
                      'options'=>$rparents,
                    ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Is Enable ?</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('is_enable',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$is_enables,
                      'default'=>1,
                    ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                 <label class="col-sm-4 col-md-3 lableTopMar"></label>
                   <div class="col-sm-5 col-md-6">
                        <button type="submit" class="btn btn-success btn-lg">Submit</button>
                    </div>
              </div>
            </div>

          </div>
        <?php echo $this->Form->end(); ?>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</section>
