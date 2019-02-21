  <!-- <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js">
  </script>
  
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Informal Counsellings</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Manage Informal Counselling',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <?php 
                //echo $message; 
                ?>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('InformalCounselling',array('class'=>'form-horizontal'
                      ));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>

                    <div class="control-group">
                        <label class="control-label">Sponser<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('sponser',array('div'=>false,'label'=>false,'class'=>'form-control'));?>
                        </div>
                    </div>
                    
                     <div class="control-group">
                        <label class="control-label">Counselling By<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                    <?php echo $this->Form->input('counselling_id',array('div'=>false,'label'=>false,'class'=>'form-control validate','id'=>'counselling_id','options'=>$user,'empty'=>'--Select--'));?>
                                    </div>
                       </div>

                             <div class="control-group">
                        <label class="control-label">Prisoners No.<?php echo MANDATORY; ?> :</label>
                            <div class="controls">
                                    <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control validate','id'=>'prisoner_id','options'=>$prisoner,'empty'=>'--Select--'));?>
                                    </div>
                            </div>

                            <div class="control-group">
                        <label class="control-label">Social Theme<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                    <?php echo $this->Form->input('theme_id',array('div'=>false,'label'=>false,'class'=>'form-control validate','id'=>'theme_id','options'=>$socialtheme,'empty'=>'--Select--'));?>
                                    </div>
                            </div>

                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1));?>
                        </div>
                    </div>

                    <div class="form-actions" align="center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(function(){
    $("#InformalCounsellingAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[InformalCounselling][counselling_id]': {
                    required: true,
                },
               'data[InformalCounselling][theme_id]': {
                    required: true,
                },
                'data[InformalCounselling][prisoner_id]': {
                    required: true,
                },
                'data[InformalCounselling][sponser]': {
                    required: true,
                }

            },
            messages: {
                'data[InformalCounselling][counselling_id]': {
                    required: "This Field is Required.",
                },
                'data[InformalCounselling][theme_id]': {
                    required: "This Field is Required.",
                },
                'data[InformalCounselling][prisoner_id]': {
                    required: "This Field is Required.",
                },
               'data[InformalCounselling][sponser]': {
                    required: "This Field is Required.",
                }
             }
               
    });
  });
  </script>
  