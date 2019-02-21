<style>
.row-fluid [class*="span"]{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Discharge Summary Board</h5>
                    <div style="float:right;padding-top:2px;">
                        <?php echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    
                    <div class="">
                    <?php echo $this->Form->create('DischargeSummary',array('class'=>'form-horizontal'));?>
                        <h3 align="center">DISCHARGE BOARD SUMMARY</h3>
                        <h5 align="center">(To be completed three months before the month of discharge)</h5>

                        <div class="row">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Prison : </label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['name'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Name (in full) : </label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['name'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Former employment : </label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Address on discharge in non fixed, state town to which proceeding</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                        <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">What he wishes to do</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                        <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Any offer of help or employment</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                        <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Vocational and spare time training</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                        <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">General remarks and suggestions for after care</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                        <?php echo $this->Form->input('offence_victim',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Amount of private cash</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                        <?php echo $this->Form->input('offence_victim',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required','title'=>'Please provide description'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Earliest date of discharge</label>
                                    <div class="controls">
                                        <?php echo date("d-m-Y", strtotime($prisonerDetails['Prisoner']['epd']));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Licence expires (if has any)</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                <h3 align="center">Discharge board summary- continued</h3>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Superintendent’s opinion and recommendation</label>
                                    <div class="controls">
                                        <?php echo $this->data['Prison']['offence_victim'];?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
                        </div>
                    <?php echo $this->Form->end();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

