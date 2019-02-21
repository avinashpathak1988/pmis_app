<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Countries</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Manage Countries',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Country',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="control-group">
                        <label class="control-label">Continent Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('continent_id',array('div'=>false,'label'=>false,'placeholder'=>'Enter Continent Name','empty'=>'','options'=>$Continents,'class'=>'span11 pmis_select','required'));?>
                        </div>
                    </div>
                     <div class="control-group">
                        <label class="control-label">Countries<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Countries','class'=>'form-control','required'));?>
                        </div>
                    </div>
                     <div class="control-group">
                        <label class="control-label">Citizen<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('citizen',array('div'=>false,'label'=>false,'placeholder'=>'Enter Citizen','class'=>'form-control','required'));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Nationality Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Nationality Name','class'=>'form-control','required'));?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Is Enabled ?</label>
                        <div class="controls">
                            <?php echo $this->Form->checkbox('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$is_enables,'default'=>1,));?>
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