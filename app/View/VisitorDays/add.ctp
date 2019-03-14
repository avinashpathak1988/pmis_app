<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Visitor Day</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('VisitorDay List',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('VisitorDay',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                     <?php echo $this->Form->input('prison_id', array('type'=>'hidden', 'value'=>$this->Session->read('Auth.User.prison_id'))) ?> 
                     
                    <div class="row-fluid">

                    <div class="span6">
                        <div class="control-group">
                         <label class="control-label">Prison Station <?php echo $req; ?>:</label>
                            <div class="controls">
                                <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','empty'=>'','type'=>'select','options'=>$prisonList, 'required','id'=>'prison_id','title'=>"Please select prison"));?>
                                           
                            </div>
                        </div>
                    </div>
                     <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Prisoner Type<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','required','empty'=>'-- Select Prisoner Type --','options'=>$prisonerType));?>
                        </div>
                        </div>
                     </div>
                   </div>

                   <div class="row-fluid">
                     <div class="span6">
                        <div class="control-group">
                        <label class="control-label">Visitor Days:</label>
                        <div class="controls">


                           <?php  
                           //debug($prisonerType);
                           if(isset($this->data['VisitorDay']['days'])){
                            $daysSelected = explode(',', $this->data['VisitorDay']['days']);
                           }else{
                            $daysSelected =array();
                           }
                           $default =array();
                           foreach ($daysSelected as $key => $value) {
                               /*echo $value;
                            array_push($myDays, array($value=>$value));*/
                            $default += array($value=>$value);
                           }
                            $this->request->data['VisitorDay']['days']=$default;

                           //debug($this->request->data['VisitorDay']['days']);
                            $days = array(
                                                'sunday'=>'Sunday',
                                                'Monday'=>'Monday',
                                                'Tuesday'=>'Tuesday',
                                                'Wednesday'=>'Wednesday',
                                                'Thursday'=>'Thursday',
                                                'Friday'=>'Friday',
                                                'Saturday'=>'Saturday'
                                            );
                           echo $this->Form->input('days',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','required','empty'=>'-- Select Prisoner Type --','options'=>$days,'multiple'=>true,'default'=>$default));?>
                            </div>
                        </div>
                      </div>              
                     <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Is Enabled ?</label>
                            <div class="controls">
                                <?php echo $this->Form->checkbox('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$is_enables,'default'=>1,));?>
                            </div>
                        </div>
                     </div>
                    </div>
                 
                    <div class="row-fluid">
                     <div class="span12">
                        <div class="form-actions" align="center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                     </div>
                 </div>
                    
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(function(){
    $("#VisitorDayAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[VisitorDay][prisoner_type_id]': {
                    required: true,
                },
                
            },
            messages: {
                'data[VisitorDay][prisoner_type_id]': {
                    required: "Please enter Prisoner Type.",
                },
            },
               
    });
  });
  </script>