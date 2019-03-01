<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Select prisoner to add to Reception Board</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Reception Board list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
              
                <div class="widget-content nopadding">
                	<div class="row-fluid">
                        <div class="span12 ">
                        <!-- form2 -->
                        <div class="aftercareform">
                            <?php echo $this->Form->create('SelectPrisoner',array('class'=>'form-horizontal'));?>
                        <div class="row phy-prop-list">
                            
                            <div class="span6">
                                  
                                <div class="control-group">
                                    <label class="control-label">Prisoner:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonersList, 'class'=>'form-control','required', 'id'=>'prisoner_id') ) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                
                            </div>
                            <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Continue', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash' ))?>
                                
                            </div>   
                            </div>

                        <?php echo $this->Form->end();?>
                        </div>
                    </div> 
                    </div> <!--  end row fluid -->


                </div>
            </div>
        </div>
    </div>
</div>           
<script type="text/javascript">
    $( document ).ready(function() {
        $('#prisoner_id').select2();
    });
</script>