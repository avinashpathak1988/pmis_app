<?php
// debug($prisonerData);
?>
<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Prisoner name:</label>
            <div class="controls">
                <?php echo $prisonerData['Prisoner']['first_name']." ".$prisonerData['Prisoner']['first_name'];?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Tribe:</label>
            <div class="controls">
                <?php echo $funcall->getName($prisonerData['Prisoner']['tribe_id'],"Tribe",'name');?>
            </div>
        </div>
    </div>
</div> 
<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Offence:</label>
            <div class="controls">
                <?php 
                $offenceData = '';
                if(isset($prisonerCaseFileData['PrisonerOffence']) && is_array($prisonerCaseFileData['PrisonerOffence']) && count($prisonerCaseFileData['PrisonerOffence'])>0){
                    foreach ($prisonerCaseFileData['PrisonerOffence'] as $key => $value) {
                        $offenceData .= $value['offence'].',';
                    }
                    $offencArr = array();
                    if($offenceData!=''){
                        foreach (explode(",", $offenceData) as $key => $value) {
                            $offencArr[$value] = $funcall->getName($value,"Offence","name");
                        }
                    }
                }
                echo (isset($offencArr) && is_array($offencArr)) ? implode(", ", array_filter($offencArr)): '';
                ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Sentence:</label>
            <div class="controls">
                <?php 
                    $lpd = (isset($prisonerData['Prisoner']['sentence_length']) && $prisonerData['Prisoner']['sentence_length']!='') ? json_decode($prisonerData['Prisoner']['sentence_length']) : array();
                    $remission = array();
                    if(isset($lpd) && count((array)$lpd)>0){
                        foreach ($lpd as $key => $value) {
                            if($key == 'days'){
                                if($value > 0)
                                    $remission[2] = $value." ".$key;
                            }
                            if($key == 'years'){
                                if($value > 0)
                                    $remission[0] = $value." ".$key;
                            }
                            if($key == 'months'){
                                if($value > 0)
                                    $remission[1] = $value." ".$key;
                            }                        
                        }
                        ksort($remission);
                        echo implode(", ", $remission); 
                    } 
                ?>
            </div>
        </div>
    </div>
</div> 

<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Term:</label>
            <div class="controls">
                <?php echo ($prisonerData['Prisoner']['is_long_term_prisoner']==0) ? 'Short Term' : 'Long Term';?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Prisoner Type:</label>
            <div class="controls">
                <?php echo $funcall->getName($prisonerData['Prisoner']['prisoner_type_id'],"PrisonerType","name");?>
            </div>
        </div>
    </div>

</div> 

<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">EPD:</label>
            <div class="controls">
                <?php echo ($prisonerData['Prisoner']['epd']!='0000-00-00') ? date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($prisonerData['Prisoner']['epd'])) : '';?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Is Escapee?:</label>
            <div class="controls">
                <?php echo ($prisonerData['Prisoner']['is_escaped']) ? 'Yes' : 'No';?>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Is Restricted?:</label>
            <div class="controls">
                <?php echo ($prisonerData['Prisoner']['is_restricted']) ? 'Yes' : 'No';?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Is Dangerous?:</label>
            <div class="controls">
                <?php echo ($prisonerData['Prisoner']['is_restricted']) ? 'Yes' : 'No';?>
            </div>
        </div>
    </div>
</div>