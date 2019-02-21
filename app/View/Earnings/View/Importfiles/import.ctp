<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Import Files</h5>
                    <div style="float:right;padding-top: 7px;">
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Importfile',array('class'=>'form-horizontal','enctype' => 'multipart/form-data'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Import Files :</label>
                                <div class="controls" style="width:575px;">
                                    <?php echo $this->Form->input('excel', array('type'=>'file','class'=>'form-control','id'=>'state_id','div'=>false,'label'=>false,'onchange'=>'javascript:showDistrict(this.value);'))?>
									 Sample Format (prison_file.xls)
                                     <?php echo $this->Html->link(__('Download'), array('action' => 'download'), array('escape'=>false,'style'=>'color:blue;')); ?>
								 </div>
                            </div>
							
                        </div>                      

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Submit', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:import()'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?> 
                </div>
            </div>
        </div>
    </div>
</div>

