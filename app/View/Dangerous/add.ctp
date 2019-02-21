<style type="text/css">
    /* Ratings widget */
    .rate {
        display: inline-block;
        border: 0;
    }
    /* Hide radio */
    .rate > input {
        display: none;
    }
    /* Order correctly by floating highest to the right */
    .rate > label {
        float: right;
    }
    /* The star of the show */
    .rate > label:before {
        display: inline-block;
        font-size: 1.1rem;
        padding: .3rem .2rem;
        margin: 0;
        cursor: pointer;
        font-family: FontAwesome;
        content: "\f005 "; /* full star */
    }
    /* Zero stars rating */
    .rate > label:last-child:before {
        content: "\f006 "; /* empty star outline */
    }
    /* Half star trick */
    .rate .half:before {
        content: "\f089 "; /* half star no outline */
        position: absolute;
        padding-right: 0;
    }
    /* Click + hover color */
    input:checked ~ label, /* color current and previous stars on checked */
    label:hover, label:hover ~ label { color: #73B100;  } /* color previous stars on hover */

    /* Hover highlights */
    input:checked + label:hover, input:checked ~ label:hover, /* highlight current and previous stars */
    input:checked ~ label:hover ~ label, /* highlight previous selected stars for new rating */
    label:hover ~ input:checked ~ label /* highlight previous selected stars */ { color: #A6E72D;  } 

</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Dangerous Prisoner Review Form</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Dangerous Prisoner Review List',array('controller'=>'Dangerous','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Dangerous',array('class'=>'form-horizontal'));?>
                    <?php 
                        echo $this->Form->input('id',array('type'=>"hidden"));
                    ?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner Station :<?php echo MANDATORY; ?></label>
                                <div class="controls">
                                <?php 
                                $originPrisonList = $prisonList;
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                    $originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
                                }
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                    $originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
                                }
                                echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$originPrisonList, 'empty'=>'','required','id'=>'prisoner_station','title'=>"Please Select Prison"));
                                ?>
                                </div>
                              <!-- <div class="controls">
                                   <?php //echo $this->Form->input('prisoner_station', array('type'=>'select','class'=>'form-control pmis_select','id'=>'prisoner_station','options'=>$prisonList,'empty'=>'--All--','div'=>false,'label'=>false,'onchange'=>''))?>
                                </div> -->
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner Number :<?php echo MANDATORY; ?></label>
                                <div class="controls">
                                <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>array(), 'empty'=>'','required','id'=>'prisoner_id','title'=>"Please Select Prisoner Number"));?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="span12" style="padding-left: 30px;">
                        <table class="table table-responsive table-bordered">
                            <tr>
                                <th>S No.</th>
                                <th>List</th>
                                <th>Description <?php echo MANDATORY; ?></th>
                                <th>Rating <?php echo MANDATORY; ?></th>
                            </tr>
                            <?php $i = 0; ?>
                            <tr>
                                <td>1.</td>
                                <td>
                                    Character
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Character'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Threat Analysis
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Threat Analysis'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Past History
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Past History'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    By current situation
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'By current situation'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Public interest
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Public interest'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    By number of convictions
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'By number of convictions'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'required'=>true,'readonly'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    By nature of offence
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'By nature of offence'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'readonly'=>true,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    By Perception
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'By Perception'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    By body build
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'By body build'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    By conduct(In prison or Out of Prison)
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'By conduct(In prison or Out of Prison)'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Personality
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Personality'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Escape
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Escapee'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'readonly'=>true,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Foreigners
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Foreigners'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'readonly'=>true,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Terminally Ill
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Terminally Ill'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                     
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Old age
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Old age'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Expectant mothers
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Expectant mothers'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'readonly'=>true,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Physically impaired
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Physically impaired'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Mentally ill
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Mentally ill'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'readonly'=>true,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Under age
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Under age'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'text','class'=>'form-control','label'=>false,'readonly'=>true,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i+1 ?>.</td>
                                <td>
                                    Contagious sicknes
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.id', array('type'=>'hidden'));
                                    echo $this->Form->input('DangerousDetail.'.$i.'.type', array('type'=>'hidden','value'=>'Contagious sicknes'));
                                    ?>
                                </td>
                                <td>
                                    <?php                   
                                    echo $this->Form->input('DangerousDetail.'.$i.'.dangerous_condition', array('type'=>'select','class'=>'form-control pmis_select','options'=>$options,'empty'=>'','div'=>false,'label'=>false,'required'=>true,'title'=>'Please select description'));
                                    ?>                    
                                </td>
                                <td>
                                    <div class="rate">
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==10) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating10" value="10" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating10" title="5 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==9) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating9" value="9" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating9" title="4 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==8) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating8" value="8" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating8" title="4 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==7) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating7" value="7" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating7" title="3 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==6) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating6" value="6" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating6" title="3 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==5) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating5" value="5" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating5" title="2 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==4) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating4" value="4" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating4" title="2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==3) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating3" value="3" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating3" title="1 1/2 stars"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==2) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating2" value="2" title="Please select rating" /><label for="DangerousDetail<?php echo $i; ?>rating2" title="1 star"></label>
                                        <input type="radio" required="required" <?= (isset($this->request->data['DangerousDetail'][$i]['rating']) && $this->request->data['DangerousDetail'][$i]['rating']==1) ? 'checked="checked"' : '';  ?> name="data[DangerousDetail][<?php echo $i; ?>][rating]" id="DangerousDetail<?php echo $i; ?>rating1" value="1" title="Please select rating" /><label class="half" for="DangerousDetail<?php echo $i; ?>rating1" title="1/2 star"></label>
                                    </div>
                                    <label for="data[DangerousDetail][<?php echo $i; ?>][rating]" generated="true" class="error" style="display: none;">Please select rating</label>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        </table>
                      </div>
                    </div>       
                    <!-- <div class="form-actions" align="center">
                                <button type="submit" class="btn btn-success" id="submit">Submit</button>
                    </div>  -->
                        <div class="form-actions" align="center">
                            <?php echo $this->Form->button('Submit', array('type'=>'submit','class'=>'btn btn-success','div'=>false,'label'=>false))?>
                        </div>
                        <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$ajaxJudgeUrl =  $this->Html->url(array('controller'=>'Dangerous','action'=>'getPrisoner'));
$ajaxDetailsUrl =  $this->Html->url(array('controller'=>'Dangerous','action'=>'getDetails'));
echo $this->Html->scriptBlock("

",array('inline'=>false));
?>
<script type="text/javascript">
    $(document).ready(function(){
        <?php
        if(isset($this->data['Dangerous']['prisoner_id']) && $this->data['Dangerous']['prisoner_id']!=''){
        ?>
        var url = '<?= $ajaxJudgeUrl ?>';
        $.post(url, {'prison_id':$('#prisoner_station').val()}, function(res){
            $('#prisoner_id').html(res);
            $('#prisoner_id').select2('val', '<?php echo $this->data['Dangerous']['prisoner_id']; ?>');
            var url = '<?= $ajaxDetailsUrl ?>';
            $.post(url, {'prisoner_id':$('#prisoner_id').val()}, function(res){
                var dataArr = JSON.parse(res)
                $.each(dataArr, function( index, value ) {
                  $("#"+index).val(value);
                });
            });
        });
        <?php
        }
        ?>
        $('#prisoner_station').on('change', function(e){
            var url = '<?= $ajaxJudgeUrl ?>';
            $.post(url, {'prison_id':$('#prisoner_station').val()}, function(res){
                $('#prisoner_id').html(res);                
                $('#prisoner_id').select2('val', '');                    
            });
        });  
        $('#prisoner_id').on('change', function(e){
            var url = '<?= $ajaxDetailsUrl ?>';
            $.post(url, {'prisoner_id':$('#prisoner_id').val()}, function(res){
                var dataArr = JSON.parse(res)
                $.each(dataArr, function( index, value ) {
                  $("#"+index).val(value);
                });
            });
        }); 
    });
    $(function(){
      $("#DangerousAddForm").validate({
          ignore: "",
          
      });
  });
</script>