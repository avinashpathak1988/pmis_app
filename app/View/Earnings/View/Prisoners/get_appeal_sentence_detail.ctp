<?php  //debug($sentenceAppeal);
$cnt=0;
foreach($sentenceAppeal as $key => $value){?>
    
<div class="row-fluid widget-box <?php echo $displaySentenceData;?>" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;" id="appeal_count_details">
    <div style="padding: 5px;border-bottom: 2px solid #a03230;"><?php echo $value['PrisonerCaseFile']['file_no'].': '.$value['PrisonerOffence']['offence_no'];?></div>
     <div class="widget-content">
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Case File No:</label>
                <div class="controls">
                    <?php echo $this->Form->input('case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'required'=>false,'id'=>'appeal_count_case_file_no','readonly', 'value'=>$value['PrisonerCaseFile']['case_file_no']));?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Offence:</label>
                <div class="controls">
                    <?php echo $this->Form->input('sentence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'required'=>false,'id'=>'appeal_count_offence','readonly','value'=>$value['Offence']['name']));?>
                </div>
            </div>
        </div> 
        <!-- <div class="clearfix"></div> --> 
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Sentence:</label>
                <div class="controls">
                    <?php echo $this->Form->input('sentence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'required'=>false,'id'=>'appeal_count_sentence','readonly','value'=>$value['PrisonerSentence']['sentenceData']));?>
                </div>
            </div>
        </div>
    </div>       
</div>
<?php }?>