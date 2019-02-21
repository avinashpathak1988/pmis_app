<?php
echo $this->Html->link('Manage Social Programs',array(
  'action'=>'index'
));
 ?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">

      <div class="col-md-7">
      <?php echo $this->Form->create('SocialProgram'); ?>
          <div class="box-body">
            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Social Program <font style='color:red'>*</font></label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('program_no',array(
                      'div'=>false,
                      'label'=>false,
                      'placeholder'=>'Enter Social Program',
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
                <label class="col-sm-4 col-md-3 lableTopMar">Program Name</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('program_name',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$program_name,
                     ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Start Date</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('start_date',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$start_date,
                     ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">End Date</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('end_date',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$end_date,
                     ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Comment</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('comment',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$comment,
                     ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Social Program Level ID</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('program_level_id',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$List,
                      'default'=>1,
                    ));
                   ?>
                 </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <label class="col-sm-4 col-md-3 lableTopMar">Social Program Cat. ID</label>
                <div class="col-sm-5 col-md-6">
                  <?php
                    echo $this->Form->input('program_category_id',array(
                      'div'=>false,
                      'label'=>false,
                      'class'=>'form-control',
                      'required',
                      'options'=>$Listing,
                      'default'=>1,
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
