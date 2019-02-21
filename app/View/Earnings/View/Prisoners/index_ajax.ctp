<?php
if(is_array($datas) && count($datas)>0){
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
?>

<div class="row-fluid">
<?php  

    //$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $rowCnt = 0;
    foreach($datas as $data){  
        $uuid = $data["Prisoner"]["uuid"];
        $prisoner_unique_no = $data["Prisoner"]['prisoner_unique_no'];
        $prisoner_link = $this->Html->url(array('controller'=>'prisoners','action'=>'details',$data["Prisoner"]["uuid"]));
        $prisonerClass = 'normalPrisoner';
        if($data["Prisoner"]['habitual_prisoner'] == 1 || $data["Prisoner"]["is_restricted"] == 1)
        {
            $prisonerClass = 'DangerousPrisoner';
        }
        $isCondemned = $funcall->isCondemnedPrisoner($data["Prisoner"]['id']);
?>
    <div class="span3 prisonerDiv" id="<?php echo $uuid;?>">
        <div class="prisoner-box <?php echo $prisonerClass;?>">
            <?php if($data["Prisoner"]["is_restricted"] == 1){?>
                <div class="myCircle">R</div>
            <?php }?>
             <?php if($data["Prisoner"]["is_dangerous"] == 1){?>
                <div class="myCircle">D</div>
            <?php }?>
            <?php if($funcall->getStage($data["Prisoner"]['id']) == Configure::read('SPECIAL-STAGE')){?>
                <div class="myCircleBlue">S</div>
            <?php }?>
            <?php 
            if(isset($data['Prisoner']['prisoner_type_id']) && $data['Prisoner']['prisoner_type_id'] == Configure::read('CONVICTED'))
            { 
                if($funcall->getConvictRemand($data["Prisoner"]['id']) > 0)
                {?>
                    <div class="myCircleBlue">CR</div>
            <?php }
            }?>
            
            <?php $funcall->getStage($data["Prisoner"]['id']); ?>
            <div class="text-center">
                
            <?php  $image = '';    
            //debug($data); //exit; 
            if($data['Prisoner']['photo'] != '')
            {
                $filename = 'files/prisnors/'.$data["Prisoner"]["photo"];
                $is_image = '';
                if(file_exists($filename))
                {
                    $is_image = getimagesize($filename);
                }
                if(file_exists($filename) && is_array($is_image))
                { 
                    $image = $this->Html->image('../files/prisnors/'.$data["Prisoner"]["photo"], array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }
                else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE'))
                { 
                   $image = $this->Html->image('../files/prisnors/female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }else
                {
                    $image = $this->Html->image('../files/prisnors/male.png', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }   
            }
            else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE')){
                $image = $this->Html->image('female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
            }else{
                $image = $this->Html->image('male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
            }      
            echo $this->Html->link($image , array('controller'=>'prisoners', 'action'=>'details', $data["Prisoner"]["uuid"]), array('escape'=>false));     
            ?>         
            </div>
            <h5 class="text-center"><?php echo $data["Prisoner"]["prisoner_no"]; ?><?php echo ($funcall->checkLodger($data["Prisoner"]['id'])) ? '/L' : '' ?></h5>
            <p class="text-center"><?php echo substr($data['Prisoner']['fullname'], 0, 10); echo ($data["Prisoner"]["also_known_as"]!='') ? "(".substr($data['Prisoner']['also_known_as'], 0, 10).")" : '';?></p>
            <?php 
            if($isCondemned > 0)
            {?>
                <p class="text-center" style="color:red">Condemned</p>
            <?php }
            if(isset($data["Prisoner"]["age"]) && ($data["Prisoner"]["age"] > 0))
            {?>
                <p class="text-center"><?php echo $data["Prisoner"]["age"]." Years old.";?></p>
            <?php }
            else 
            {
                echo '<p class="text-center">Age not set.</p>';
            }?>
            
            <div class="text-center" id="prisonerStatus-<?php echo $data["Prisoner"]["id"];?>">
                <?php //echo $this->Html->link('<i class="icon-eye-open"></i>' , array('controller'=>'prisoners', 'action'=>'details', $uuid), array('escape'=>false, 'class'=>'btn btn-success'));

                 ?>
                <!-- <a  class="btn btn-info" ><i class="icon-check"></i></a>  -->
                
                <?php 
                if($usertype_id == Configure::read('RECEPTIONIST_USERTYPE'))
                {

                    if($data["Prisoner"]["is_final_save"] == 0 && $data['Prisoner']['present_status'] == 1)
                    {
                        if($data["Prisoner"]["status"] == 'Draft')
                        {
                            echo $this->Html->link('<i class="icon-save" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-success prisonerAction','title'=>'Final Save','onclick'=>"javascript:finalSavePrisoner('$uuid');"));
                        
                            echo '&nbsp;&nbsp;'.$this->Html->link('<i class="icon-trash" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-danger prisonerAction','title'=>'Delete','onclick'=>"javascript:prisonerDeleteConfirm('$uuid','trashPrisoner');"));
                        }
                    }
                    else 
                    {
                        if($data["Prisoner"]["status"] == 'Rejected')
                        {
                            echo '<span style="color:red;font-weight:bold;">Rejected!</span>';
                        }
                        else 
                        {
                            //check if prisoner escaped
                            $is_escaped = 0;
                            $escapeCount = $funcall->getPrisonerEscapeStatus($data["Prisoner"]['id']);
                            $escapeCount = json_decode($escapeCount);
                            if($escapeCount->display_recapture_form == 1)
                                $is_escaped = 1;

                            //check if prisoner released on bail
                            $is_bail = 0; $readmitted_after_bail = 0;
                            $bailCount = $funcall->getPrisonerBailStatus($data["Prisoner"]['id']);
                            $bailCount = json_decode($bailCount);
                            //debug($bailCount); exit;
                            if($bailCount->display_bail_form == 1)
                                $is_bail = 1;
                            if($bailCount->display_bail_form == 0 && $bailCount->display_bail_tab == 1)
                                $readmitted_after_bail = 1;

                            if($data['Prisoner']['present_status'] == 0)
                            {
                                if($is_escaped == 1)
                                    echo $this->Html->link('Recapture','/prisoners/edit/'.$uuid.'#recaptured_details',array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Recapture'));
                                else if($is_bail == 1)
                                {
                                    echo $this->Html->link('Re-entry','/prisoners/edit/'.$uuid.'#bail_details',array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Recapture'));
                                }
                                else 
                                {
                                    if($readmitted_after_bail == 1)
                                    {
                                        echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Readmitted after bail</span>';
                                    }
                                    else if($data['Prisoner']['is_death'] == 0)
                                        echo $this->Html->link('Re-admit','/prisoners/add/'.$prisoner_unique_no,array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Re admission'));
                                    else
                                        echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Dead</span>';
                                }
                            }
                            else 
                            {
                                if($is_escaped == 1)
                                {

                                }
                                else 
                                {
                                    if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0){
                            
                                        echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini '.$prisonerClass.'">Not verified yet!</span>';
                                    }
                                    else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0){

                                        echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini '.$prisonerClass.'">Verified but not approve!</span>';
                                    }
                                    else if($data["Prisoner"]["is_approve"] == 1){

                                        echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini '.$prisonerClass.'">Approved !</span>';
                                    } 
                                }
                                
                            }
                        }
                    }
                    
                }
                else if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE'))
                {
                    $is_escaped = 0;
                    $escapeCount = $funcall->getPrisonerEscapeStatus($data["Prisoner"]['id']);
                    $escapeCount = json_decode($escapeCount);
                    if($escapeCount->display_recapture_form == 1)
                        $is_escaped = 1;

                    if($is_escaped == 1)
                    {
                        echo $this->Html->link('Recaptured','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-warning btn-mini','onclick'=>"javascript:void(0);"));
                    }
                    else 
                    {
                        if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0){
                            echo $this->Html->link('Verify','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-warning btn-mini','onclick'=>"javascript:verifyPrisonerSetData('".$data["Prisoner"]["id"]."');"));
                           
                        }else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_verify_reject"] == 1){
                            echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Rejected !</span>';
                        }
                        else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0){
                            echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Not approve yet!</span>';
                        }
                        else if($data["Prisoner"]["is_approve"] == 1){
                            echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;">Approved !</span>';
                        }
                    }
                    

                }
                else if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE'))
                {
                    $is_escaped = 0;
                    $escapeCount = 0;
                    $escapeCount = $funcall->getPrisonerEscapeStatus($data["Prisoner"]['id']);
                    //debug($escapeCount);
                    $escapeCount = json_decode($escapeCount);
                    if($escapeCount->display_recapture_form == 1)
                        $is_escaped = 1;

                    if($is_escaped == 1)
                    {
                        echo $this->Html->link('Recaptured','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-warning btn-mini','onclick'=>"javascript:void(0);"));
                    }
                    else
                    {
                        if($data["Prisoner"]["status"] == 'Admitted')
                        {
                            echo $this->Html->link('Verify','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-success','onclick'=>"javascript:verifyNewAdmittedPrisoner('".$data["Prisoner"]["id"]."');"));
                        }
                        else 
                        {
                            if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0)
                            {
                                echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini '.$prisonerClass.'">Not verified yet!</span>';
                            }
                            else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0 && $data["Prisoner"]["is_reject"] == 0){
                               echo $this->Html->link('Approve','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-success','onclick'=>"javascript:verifyPrisonerSetData('".$data["Prisoner"]["id"]."');"));
                            }else if($data["Prisoner"]["is_approve"] == 1){
                                echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;">Approved !</span>';
                            }else if($data["Prisoner"]["is_reject"] == 1){

                                echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Rejected !</span>';
                            }
                        }
                    }
                }
                else if($usertype_id == Configure::read('GATEKEEPER_USERTYPE'))
                {
                    //debug($data["Prisoner"]["status"]);
                    if($data["Prisoner"]["status"] == 'G-Draft')
                    {
                        echo $this->Html->link('<i class="icon-save" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-success prisonerAction','title'=>'Final Save','onclick'=>"javascript:finalSavePrisoner('$uuid');"));
                    }
                }
                if($data["Prisoner"]["is_recaptured"] == 1)
                {
                    echo ' | '.$this->Html->link('Recaptured','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-warning btn-mini','onclick'=>"javascript:void(0);"));
                }
                ?>
            </div>
        </div> 
    </div>
<?php
        $rowCnt++;
        if($rowCnt % 4 == 0){
?>
</div>
<br />
<div class="row-fluid">
<?php            
        }
    }
?>
</div>
<?php    
//pagination start 
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#pmis_loader").show();',
        'complete'                  => '$("#pmis_loader").hide();',
        'url'                       => array(
            'controller'            => 'prisoners',
            'action'                => 'indexAjax',
            'prisoner_no'           => $prisoner_no,
            'prisoner_name'         => $prisoner_name,
            'age_from'              => $age_from,
            'age_to'                => $age_to,
            'epd_from'              => $epd_from,
            'epd_to'                => $epd_to,
            'prisoner_type_id'      => $prisoner_type_id,
            'prisoner_sub_type_id'  => $prisoner_sub_type_id,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 pull-right" style="padding:5px 10px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>
<?php 
//pagination end 
}else{

   echo Configure::read('NO-RECORD');
   
}
?> 
