
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Admission Details</h5>
        </div>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Personal Number<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','readonly','value'=>$this->request->data['Prisoner']['personal_no']));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'prisoner_no',  'readonly','value'=>$this->request->data['Prisoner']['prisoner_no']));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Prison Station<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('prisoner_station',array(
                            'type'=>'hidden',
                            'class'=>'prison_station',
                            'value'=>$prison_id
                          )); 
                        echo $this->Form->input('prison_station_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prison Station','required','readonly','value'=>$prison_name, 'id'=>'prison_station_name'));?>
                    </div>
                </div>
            </div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">No of Previous Conviction <?php// echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('no_of_prev_conviction',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter No of Previous Conviction",'required'=>false,'id'=>'no_of_prev_conviction', 'readonly', 'value'=>$prev_conviction));?>
                    </div>
                </div>
            </div>
        </div>
    </div>

