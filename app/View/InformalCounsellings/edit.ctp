<?php
echo $this->Html->link('Manage Informal Counsellings',array(
  'action'=>'index'
));
 ?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">

      <div class="col-md-7">
      <?php echo $this->Form->create('InformalCounselling'); ?>
          <div class="box-body">
            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">InformalCounselling <font style='color:red'>*</font></label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('sponser',array(
                      'div'=>false,
                      'label'=>false,
                      'placeholder'=>'Enter Informal Counselling',
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
                <label class="col-sm-4 col-md-3 lableTopMar">Counselling By</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('counselling_id',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$counselling_by,
                     ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Prisoner No.</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('prisoner_id',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$prisoner_id,
                     ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Social Theme</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('theme_id',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$theme_id,
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
                      'options'=>'',
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
