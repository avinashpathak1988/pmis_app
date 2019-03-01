<div class="span12">
    <div class="widget-box">
       <!--  <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5></h5>
           
        </div> -->
        <div class="widget-content nopadding">
            <div class="row-fluid">
                <div class="span6">
                   <div class="control-group">
                        <label class="control-label">Date of Escape:</label>
                        <div class="controls">
                            <?php echo $this->Form->input('Lodger.escape_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text', 'placeholder'=>'Enter Date of Escape','required'=>false,'readonly'=>'readonly','id'=>'escape_date', 'required', 'value'=>date("d-m-Y h:i A", strtotime($data['Discharge']['escape_date']))));?>
                        </div>
                    </div>
                </div>  
                <div class="span6">                          
                     <div class="control-group">
                        <label class="control-label">Date of Recapture<?php echo $req; ?>:</label>
                        <div class="controls">
                            <?php $currentDate = date('d-m-Y');
                            echo $this->Form->input('Lodger.recapture_date',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate span11','type'=>'text', 'placeholder'=>'Enter Date of Recapture','required'=>false,'readonly'=>'readonly','default'=>$currentDate));?>
                        </div>
                    </div>                         
                </div>  
                <div class="span6">                          
                     <div class="control-group">
                        <label class="control-label">Place of capture <?php echo $req; ?>:</label>
                        <div class="controls">
                            <?php $currentDate = date('d-m-Y');
                            echo $this->Form->input('Lodger.place_of_recapture',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate span11','type'=>'text', 'placeholder'=>'Enter Date of Recapture','required'=>false));?>
                        </div>
                    </div>                         
                </div>                          
            </div>
        </div>
    </div>
</div>



    