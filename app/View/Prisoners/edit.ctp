
<style>
.row-fluid [class*="span"]
{
    margin-left: 0px !important;
}
.lb-number{
    display: none !important;
}
</style>
<?php 
//debug($this->data['Prisoner']); 
$uganda_country_id = Configure::read('COUNTRY-UGANDA');
$remand_type = Configure::read('REMAND');
$convict_type = Configure::read('CONVICTED');
$gender_female = Configure::read('GENDER_FEMALE');
//debug($this->data); exit;
$prisoner_class = '';
if(isset($this->data['Prisoner']['classification_id']))
    $prisoner_class = $this->data['Prisoner']['classification_id'];

$prisoner_uuid = $this->request->data['Prisoner']['uuid'];
$prisoner_id = $this->request->data['Prisoner']['id'];
//echo $prisoner_class; exit;
// $readOnlyFather = array();
// $readOnlyMother = array();
//debug($this->data['Prisoner']); 
// if(isset($this->data['Prisoner']['gender_id'])){
//     if($this->data['Prisoner']['gender_id']==1){
//         $this->request->data['PrisonerChildDetail']['father_name'] = $this->request->data['Prisoner']['fullname'];
//         $readOnlyFather = array('readonly'=>true);
//     }
//     if($this->data['Prisoner']['gender_id']==2){
//         $this->request->data['PrisonerChildDetail']['mother_name'] = $this->request->data['Prisoner']['fullname'];
//         $readOnlyMother = array('readonly'=>true);
//     }
// }
// debug($readOnlyMother);
// debug($readOnlyFather);
//echo '<pre>'; print_r($this->data['PrisonerSentenceAppeal']); exit;
$next_date_after_6months = date('d-m-Y',strtotime("+6 months"));
?>
<div class="container-fluid">
    <input type="hidden" value="<?php echo $next_date_after_6months;?>" id="next_date_after_6months">
    <div class="row-fluid">
    <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Admission Details</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                
                <div class="widget-content nopadding">

           


                    <div class="">
                        <ul class="nav nav-tabs">
                            <li><a href="#personal_info">Personal Details</a></li>
                            <li><a href="#id_proof_details" id="id_proof">ID Proof Details</a></li>
                            <?php //if((isset($this->request->data['Prisoner']['status_of_women_id']) && ($this->request->data['Prisoner']['status_of_women_id'] != 3)) || (isset($this->request->data['Prisoner']['gender_id']) && ($this->request->data['Prisoner']['gender_id'] == 1)))
                            //{?>
                                <li><a href="#kin_details" id="kin_details_tab">KIN Details</a></li>
                            <?php //}?>
                            <li><a href="#child_details" id="child_details_tab">Admitting Of Children</a></li>
                            <li><a href="#special_needs" id="special_needs_tab">Special Needs</a></li>
                            <li><a href="#admission_details">Admission Details</a></li>
                            <?php 
                            if(isset($this->data["PrisonerAdmission"]['id']) && ($this->data["PrisonerAdmission"]['id'] != ''))
                            {
                                if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] != Configure::read('DEBTOR'))
                                {
                                    $no_sentence_of_options = array(
                                        Configure::read('DEATH'),
                                        Configure::read('LIFE-IN-IMPRISONMENT'),
                                        Configure::read('SENTENCE-OF-FINE')
                                    );
                                    //if(isset($this->data['PrisonerSentence']['sentence_of']) && !in_array($this->data['PrisonerSentence']['sentence_of'],$no_sentence_of_options))
                                    //{?>
                                        
                                    <?php  //}
                                    if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                    { 
                                        if(isset($this->data['Prisoner']['is_approve']) && $this->data['Prisoner']['is_approve'] == 1)
                                        {
                                            if(isset($returnFromCourtData) && !empty($returnFromCourtData) && count($returnFromCourtData) > 0)
                                            {?>
                                                <li><a href="#sentence_capture" id="sentence_capture_tab">Sentence Capture</a></li>
                                            <?php }
                                        }
                                    }
                                    else {?>
                                        <li><a href="#sentence_capture" id="sentence_capture_tab">Sentence Capture</a></li>
                                    <?php }
                                    if($is_wish_to_appeal > 0)
                                    {?>
                                        <li><a href="#appeal_against_sentence" id="appeal_against_sentence_tab">Appeal</a></li>
                                    <?php }
                                    if($is_confirmation > 0)
                                    {?>
                                        <li><a href="#confirmation_sentence" id="confirmation_sentence_tab">Confirmation</a></li>
                                    <?php }
                                }
                            }
                            if($is_petiton > 0)
                            {?>
                                <li><a href="#petition_tab" id="petition_tab_tab">Petition</a></li>
                            <?php }
                            $escapeCount = $funcall->getPrisonerEscapeStatus($this->request->data['Prisoner']['id']);
                            $escapeCount = json_decode($escapeCount);
                            //debug($escapeCount);
                            if($escapeCount->display_recapture_tab == 1)
                            {?>
                                <li><a href="#recaptured_details" id="recaptured_details_tab">Recaptured Details</a></li>
                            <?php }
                            $bailCount = $funcall->getPrisonerBailStatus($this->request->data['Prisoner']['id']);
                            $bailCount = json_decode($bailCount);
                            //debug($bailCount); 
                            if($bailCount->display_bail_tab == 1)
                            {?>
                                <li><a href="#bail_details" id="bail_details_tab">Bail Details</a></li>
                            <?php }?>
                            <li><a href="#assign_ward" id="assign_ward_tab">Assign Ward</a></li>
                            <!-- <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li> -->
                        </ul>
                        <div class="tabscontent">
                            <div id="personal_info">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id');?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span12" style="text-align: center; margin-bottom:10px;">
                                        <button type="button" class="btn btn-success" id="prisonerInfo" onclick="displayPrisonerDetail();">Prisoner Registration Info</button>
                                        <?php 
                                        $isPrevpersonalDetails = $funcall->getPreviouspersonaldetails($this->data['Prisoner']['personal_no'], $this->data['Prisoner']['id']);
                                        if(count($isPrevpersonalDetails) > 0)
                                        {
                                            echo $this->Html->link('Previous Personal Details',array('action'=>'prevPersonalDetails', $this->data['Prisoner']['uuid']),array('escape'=>false,'class'=>'btn btn-warning'));
                                        }?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div id="prisonerInfoDiv" style="display: none;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">DOA<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('doa',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'Enter Date Of Admission','id'=>'doa', 'default'=>date('d-m-Y'), 'readonly'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">First Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Enter First Name','id'=>'first_name', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Middle name :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('middle_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Middle name','required'=>false,'id'=>'middle_name1', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Surname :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Enter Surname','required'=>false,'id'=>'last_name', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Also Known As :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('also_known_as',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Also Known As','required'=>false,'id'=>'also_known_as', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Father's Name<?php// echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Father's Name",'required'=>false,'id'=>'father_name', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Mother's Name<?php// echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('mother_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Mother's Name",'id'=>'mother_name','required'=>false,'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    $date_of_birth = $this->data['Prisoner']['date_of_birth'];
                                                    $dateafter_18yrs_dob = date("d-m-Y", strtotime(date("Y-m-d", strtotime($date_of_birth)) . " + 18 years"));

                                                    echo $this->Form->input('dateafter_18yrs_dob',array('div'=>false,'label'=>false, 'type'=>'hidden', 'id'=>'dateafter_18yrs_dob','value'=>$dateafter_18yrs_dob));
                                                    echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control prisoner_dob span11','type'=>'text', 'placeholder'=>'Enter Date of Birth','required','id'=>'date_of_birth', 'readonly', 'onblur'=>'getPrisonerAge(this.value);'));?>
                                                </div>
                                            </div>
                                        </div>  
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Type<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonerTypeList, 'required','id'=>'prisoner_type_id','readonly','onchange'=>'showPrisonerSubType(this.value);'));?>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Require <br>Ascertaining Age? :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('suspect_on_age',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox'));?>
                                                </div>
                                            </div>
                                        </div> 
                                         <div class="span6" style="display: none;" id="admit_under">
                                        <div class="control-group">
                                            <label class="control-label">Admitted Under Repatriation:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('additted_reparitation',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'onClick'=>'showReparitaion()'));?>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Is a Refugee:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('is_refugee',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox'));?>
                                            </div>
                                        </div>  
                                    </div>
                                     
                                     <div class="container-fluid">
                                        <div class="span12 secondDiv widget-box" id="reparitation" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important; display:none;">
                                        <div class="widget-title">
                                            <h5>Admitted Under Repatriation</h5>
                                        </div>
                                        <div class="widget-content">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Country Name<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                     <?php echo $this->Form->input('reparitation_country_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$repritationcountryList, 'empty'=>'','required'=>false,'id'=>'reparitation_country_id'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Station Name<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                   <?php echo $this->Form->textfield('reparitation_station_name',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Station Name','class'=>'form-control','id' => 'reparitation_station_name','type'=>'text','required'=>false, 'title'=>'Station Name is required.'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label">Upload Repatriation Order <?php echo $req;?>:</label>
                                                        <div class="controls">
                                                            <div id="prevImage3" class="" style="margin-top: 10px;">
                                                            <?php $is_photo3 = '';
                                                            if(isset($this->request->data["Prisoner"]["repatriation_order"]) && !is_array($this->request->data["Prisoner"]["repatriation_order"]))
                                                            {
                                                                $is_photo3 = 1;?>
                                                               <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["repatriation_order"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["repatriation_order"];?>" alt="" width="150px" height="150px"></a>
                                                            <?php }?>
                                                            </div>
                                                            <span id="previewPanel3" class="">
                                                                <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="img_prev3" src="#" class="img_prev" /></a>
                                                                <span id="x3" class="remove_img">[X]</span>
                                                            </span>
                                                            <div class="clear"></div>
                                                            <?php echo $this->Form->input('repatriation_order',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'repatriation_order', 'onchange'=>'readURL(this, 3);', 'required'=>false));?>
                                                            <?php echo $this->Form->input('is_photo3',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'is_photo3', 'value'=>$is_photo3));?>
                                                                
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>  
                                    

                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place Of Birth<?php //echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php// echo $this->Form->input('place_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'textarea', 'placeholder'=>'Enter Place Of Birth','required','id'=>'place_of_birth', 'maxlength'=>'30','rows'=>"3",'cols'=>"30"));?>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="clearfix"></div>  -->  
                                        <div class="container-fluid">
                                            <div class="span12 secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important;">
                                                <div class="widget-title">
                                                    <h5>Place Of Birth</h5>
                                                </div>
                                                <div class="widget-content">
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">District:</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->input('birth_district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$birthDistrictList, 'empty'=>'','onChange'=>'showcounty(this.value)','required'=>false,'id'=>'birth_district_id'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">County:</label>
                                                            <div class="controls">
                                                            <?php
                                                            
                                                                echo $this->Form->input('county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allCountyList,'onChange'=>'showsubcounty(this.value)', 'empty'=>'','required'=>false,'id'=>'county_id'));
                                                            ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Sub County:</label>
                                                            <div class="controls">
                                                            <?php
                                                                echo $this->Form->input('sub_county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allSubCountyList, 'empty'=>'','onChange'=>'showParish(this.value)','required'=>false,'id'=>'sub_county_id'));
                                                           ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Parish:</label>
                                                            <div class="controls">
                                                            <?php
                                                                echo $this->Form->input('parish_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allParishList, 'empty'=>'','onChange'=>'showVillage(this.value)', 'required'=>false,'id'=>'parish_id'));
                                                           ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Village:</label>
                                                            <div class="controls">
                                                            <?php
                                                                echo $this->Form->input('village_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allVillageList, 'empty'=>'','required'=>false,'id'=>'village_id'));
                                                           ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Serving Forces code -- START-->
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Is a Serving Member Of Forces ?</label>
                                                <div class="controls">
                                                <?php $is_serving = '';
                                                if (isset($this->data['Prisoner']['is_smforce']) && $this->data['Prisoner']['is_smforce'] == 1) 
                                                {
                                                    $is_serving = 'checked';
                                                }
                                                echo $this->Form->input('is_smforce',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'onClick'=>'showServing()', $is_serving));
                                                 ?>
                                                </div>
                                            </div>  
                                        </div>
                                        
                                        <div class="container-fluid">
                                            <div class="span12 secondDiv widget-box" id="serving_div" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important; <?php if($is_serving ==''){?>display:none;<?php }?>">
                                                <div class="widget-title">
                                                    <h5>Is Serving Forces</h5>
                                                </div>
                                                <div class="widget-content">
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Service Number<?php echo $req; ?> :</label>
                                                            <div class="controls">
                                                               <?php echo $this->Form->textfield('service_number',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Service Number','class'=>'form-control','id' => 'service_number','type'=>'text','required'=>false, 'title'=>'Service Number is required.'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Rank<?php echo $req; ?> :</label>
                                                            <div class="controls">
                                                           <?php echo $this->Form->textfield('service_rank',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Rank','class'=>'form-control','id' => 'service_rank','type'=>'text','required'=>false, 'title'=>'Service Rank is required.'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Name<?php echo $req; ?> :</label>
                                                            <div class="controls">
                                                          <?php echo $this->Form->textfield('service_name',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Name','class'=>'form-control','type'=>'text','id' => 'service_name','required'=>false, 'title'=>'Service Name is required.'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Unit<?php echo $req; ?> :</label>
                                                            <div class="controls">
                                                           <?php echo $this->Form->textfield('service_unit',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Unit','class'=>'form-control','type'=>'text','id' => 'service_unit','required'=>false, 'title'=>'Service Unit is required.'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                      <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">UG Force :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('ug_force_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$ugForceList, 'empty'=>'','required'=>false,'id'=>'ug_force_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        <!-- Serving Forces code -- END-->
                                       
                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Sub Type<span id="prisonerSubTypeValid" style="display: none;"><?php echo $req; ?></span> :</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonerSubTypeList, 'empty'=>'-- Select Prisoner Sub Type --','required','id'=>'prisoner_sub_type_id'));?>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="clearfix"></div>                                      
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Sex<?php echo $req; ?> :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $gender = 1;
                                                    if(isset($this->data['Prisoner']['gender_id']))
                                                        $gender = $this->data['Prisoner']['gender_id'];
                                                    $options2= $genderList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value'  => $gender,
                                                        'onclick'=> 'dissplayStatusOfWomen(this.value);'
                                                    );
                                                    echo $this->Form->radio('gender_id', $options2, $attributes2);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Continent<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    echo $this->Form->input('continent_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$continentList, 'empty'=>'','required','id'=>'continent_id','default'=>1,'puthtml'=>'countrycont'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Country Of Origin<?php echo $req; ?> :</label>
                                                <div class="controls" id="countrycont">
                                                    <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'onChange'=>'showDistricts(this.value)','class'=>'form-control span11 pmis_select','type'=>'select','options'=>$countryList, 'empty'=>'','required','id'=>'country_id','default'=>1));?>
                                                    <?php
                                                        // if($this->data['Prisoner']['other_country']!=""){
                                                        //     echo $this->Form->input('other_country',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Country','required'=>false,'id'=>'other_country'));
                                                        // }
                                                        // else{
                                                            echo $this->Form->input('other_country',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Country','required'=>false,'id'=>'other_country','style'=>'display:none;', 'maxlength'=>'30'));
                                                        //}
                                                    ?>
                                                     
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Is the Prisoner a dual citizen?:</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $is_dual_citizen = 0;
                                                    if(isset($this->data['Prisoner']['is_dual_citizen']))
                                                    {
                                                        $is_dual_citizen = $this->data['Prisoner']['is_dual_citizen'];
                                                    }
                                                    $options2= array('0'=>'No','1'=>'Yes');
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $is_dual_citizen,
                                                        'onChange'=>'showNationality2(this.value)',
                                                    );
                                                    echo $this->Form->radio('is_dual_citizen', $options2, $attributes2);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Nationality<?php echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Nationality','required'=>false,'id'=>'nationality_name', 'maxlength'=>'30'));?>
                                                    <div class="clear" style="margin-top: 10px;"></div>
                                                    <?php 
                                                    if(isset($this->data['Prisoner']['is_dual_citizen']) && ($this->data['Prisoner']['is_dual_citizen'] == 1))
                                                    {
                                                        echo $this->Form->input('nationality_name2',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$nationalityList, 'empty'=>'','required'=>false,'id'=>'nationality_name2'));
                                                        echo $this->Form->input('nationality_name2_note',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Dual Citizen Note','required'=>false,'id'=>'nationality_name2_note'));
                                                    }
                                                    else 
                                                    {
                                                        echo $this->Form->input('nationality_name2',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$nationalityList, 'style'=> "display:none;", 'empty'=>'','required'=>false,'id'=>'nationality_name2'));

                                                        echo $this->Form->input('nationality_name2_note',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Dual Citizen Note','style'=> "display:none;",'required'=>false,'id'=>'nationality_name2_note'));
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">District Of Origin:</label>
                                                <div class="controls">
                                                <?php
                                                // if($this->data['Prisoner']['other_district']!=""){
                                                //     echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$districtList, 'empty'=>array('0'=>'-- Select District --'),'required'=>false,'style'=>'display:none;','id'=>'district_id'));
                                                //      echo $this->Form->input('other_district',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'District','required'=>false,'id'=>'other_district'));
                                                //     }
                                                //     else{
                                                        echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$districtList, 'empty'=>'','required'=>false,'id'=>'district_id'));
                                                     echo $this->Form->input('other_district',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'District','required'=>false,'id'=>'other_district','style'=>'display:none;', 'maxlength'=>'30'));
                                                    //}
                                               ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">County:</label>
                                                <div class="controls">
                                                <?php
                                                
                                                    //echo $this->Form->input('county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allCountyList,'onChange'=>'showsubcounty(this.value)', 'empty'=>'-- Select County --','required'=>false,'id'=>'county_id'));
                                                ?>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Sub County:</label>
                                                <div class="controls">
                                                <?php
                                                    //echo $this->Form->input('sub_county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allSubCountyList, 'empty'=>'-- Select Sub County --','onChange'=>'showParish(this.value)','required'=>false,'id'=>'sub_county_id'));
                                               ?>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="clearfix"></div> -->
                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Parish:</label>
                                                <div class="controls">
                                                <?php
                                                    //echo $this->Form->input('parish_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allParishList, 'empty'=>'-- Select Parish --','required'=>false,'id'=>'parish_id'));
                                               ?>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Village:</label>
                                                <div class="controls">
                                                <?php
                                                    //echo $this->Form->input('village_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'=>false,'id'=>'village_name','placeholder'=>'Village'));
                                                ?>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Link with barcode:</label>
                                                <div class="controls">
                                                    <?php 
                                                    if(isset($this->data['Prisoner']['mapped_with_bio']) && $this->data['Prisoner']['mapped_with_bio']!='Y'){
                                                        //echo $this->Form->input('link_biometric',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$funcall->getUnlinkedBioUser(), 'empty'=>'-- Select Biometric Prisoner --','required'=>false,'id'=>'tribe_id'));
                                                        echo $this->Form->input('link_biometric',array(
                                                            'type'=>'hidden',
                                                            'id'=>'link_biometric',
                                                          ));
                                                          ?>
                                                          <span id="link_biometric_span"></span>
                                                          <?php
                                                        echo $this->Form->button('Get Biometric Data', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'link_biometric_button',"onclick"=>"checkData()"));
                                                    }else{
                                                        echo "Already Linked";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6 tribehide">
                                            <div class="control-group">
                                                <label class="control-label">Tribe<span id="tribeValid" style="display: none;"><?php echo $req; ?></span> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('tribe_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$tribeList, 'empty'=>'','required','id'=>'tribe_id','onChange'=>'openOtherField("tribe");'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6" id="classification_div" <?php                                       if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] != $convict_type){?> style="display: none;"<?php }?>>
                                            <div class="control-group">
                                                <label class="control-label">Classification :</label>
                                                <div class="controls">
                                                    <?php $classification_readonly = 'disabled';
                                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                                                    {
                                                        $classification_readonly = '';
                                                    }
                                                    echo $this->Form->input('classification_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$classificationList, 'empty'=>'','required','id'=>'classification_id', $classification_readonly));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Permanent Address<?php echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('permanent_address',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter permanent address','id'=>'permanent_address','rows'=>3));?>
                                                </div>
                                            </div>
                                        </div> 
                                         <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Desired Districts Of Relese<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                   <?php echo $this->Form->input('desired_districts_relese',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$districtList, 'empty'=>'','required'=>false,'id'=>'desired_districts_relese'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Photo --START-- -->
                                    <div class="clearfix"></div> 
                                    <div class="container-fluid">
                                        <div class="span12 secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important;">
                                            <div class="widget-title">
                                                <h5>Photo</h5>
                                                <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type photo!" id='example'></i>
                                            </div>
                                            <div class="widget-content">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label" style="width:auto;">Left Side <?php echo $req;?>:</label>
                                                        <div class="controls" style="margin-left:100px;">
                                                            <div id="prevImage" class="" style="margin-top: 10px;">
                                                            <?php $is_photo1 = '';
                                                            if(isset($this->request->data["Prisoner"]["left_photo"]) && !is_array($this->request->data["Prisoner"]["left_photo"]))
                                                            {
                                                                $is_photo1 = 1;?>
                                                               <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["left_photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["left_photo"];?>" alt="" width="150px" height="150px"></a>
                                                            <?php }?>
                                                            </div>
                                                            <span id="previewPane1" class="">
                                                                <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="img_prev1" src="#" class="img_prev" /></a>
                                                                <span id="x1" class="remove_img">[X]</span>
                                                            </span>
                                                            <div class="clear"></div>
                                                            <?php echo $this->Form->input('left_photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'left_photo', 'onchange'=>'readURL(this,1);', 'required'=>false));?>
                                                            <?php echo $this->Form->input('is_photo1',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'is_photo1', 'value'=>$is_photo1));?>
                                                                
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label" style="width:auto;">Profile PIC<?php echo $req; ?>:</label>
                                                        <div class="controls" style="margin-left:100px;">
                                                            <div id="prevImage" class="" style="margin-top: 10px;">
                                                            <?php $is_photo = '';
                                                            if(isset($this->request->data["Prisoner"]["photo"]) && !is_array($this->request->data["Prisoner"]["photo"]))
                                                            {
                                                                $is_photo = 1;?>
                                                               <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["photo"];?>" alt="" width="150px" height="150px"></a>
                                                            <?php }?>
                                                            </div>
                                                            <span id="previewPane" class="">
                                                                <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="img_prev" src="#" class="img_prev" /></a>
                                                                <span id="x" class="remove_img">[X]</span>
                                                            </span>
                                                            <div class="clear"></div>
                                                            <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'photo', 'onchange'=>'readURL(this);', 'required'=>false));?>
                                                            <?php echo $this->Form->input('is_photo',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'is_photo', 'value'=>$is_photo));?>
                                                                

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label" style="width:auto;">Right Side <?php echo $req;?>:</label>
                                                        <div class="controls" style="margin-left:100px;">
                                                            <div id="prevImage" class="" style="margin-top: 10px;">
                                                            <?php $is_photo2 = '';
                                                            if(isset($this->request->data["Prisoner"]["right_photo"]) && !is_array($this->request->data["Prisoner"]["right_photo"]))
                                                            {
                                                                $is_photo2 = 1;?>
                                                               <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["right_photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["right_photo"];?>" alt="" width="150px" height="150px"></a>
                                                            <?php }?>
                                                            </div>
                                                            <span id="previewPane2" class="">
                                                                <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="img_prev2" src="#" class="img_prev" /></a>
                                                                <span id="x2" class="remove_img">[X]</span>
                                                            </span>
                                                            <div class="clear"></div>
                                                            <?php echo $this->Form->input('right_photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'right_photo', 'onchange'=>'readURL(this,2);', 'required'=>false));?>
                                                            <?php echo $this->Form->input('is_photo2',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'is_photo2', 'value'=>$is_photo2));?>
                                                                
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Photo -- END -- --> 
                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Employment Type<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('employment_type',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$employelist, 'empty'=>'','required','id'=>'employment_type','title'=>'Select Employment Type'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Occupation at Arrest<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('occupation_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$occupationList, 'empty'=>'','required','id'=>'occupation_id','onChange'=>'openOtherField("occupation");'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Level Of Education<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('level_of_education_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$levelOfEducationList, 'empty'=>'','required','id'=>'level_of_education_id','onChange'=>'openOtherField("level_of_education");'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Skill<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('skill_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$skillList, 'empty'=>'','required','id'=>'skill_id','onChange'=>'openOtherField("skill");'));?>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Religion<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('apparent_religion_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$ApparentReligionList, 'empty'=>'','required','id'=>'apparent_religion_id','onChange'=>'openOtherField("apparent_religion");'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Height :</label>
                                            <div class="controls">
                                                <!-- <div class="span5"> -->
                                                    <?php echo $this->Form->input('height_feet',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$heightInFeetList, 'empty'=>'','required'=>false,'id'=>'height_feet'));?>
                                                <!-- </div> -->
                                                <!-- <div class="span5">
                                                    <?php echo $this->Form->input('height_inch',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$heightInInchList, 'empty'=>'','required'=>false,'id'=>'height_inch'));?>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Build :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('build_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$buildList, 'empty'=>'','required'=>false,'id'=>'build'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Face :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('face_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$faceList, 'empty'=>'','required'=>false,'id'=>'face_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Eyes :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('eyes_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$eyesList, 'empty'=>'','required'=>false,'id'=>'eyes_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Mouth :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('mouth_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$mouthList, 'empty'=>'','required'=>false,'id'=>'mouth_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Speech :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('speech_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$speechList, 'empty'=>'','required'=>false,'id'=>'speech_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Teeth :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('teeth_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$teethList, 'empty'=>'','required'=>false,'id'=>'teeth_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Lips :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('lips_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$lipList, 'empty'=>'','required'=>false,'id'=>'lips_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Ears :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('ears_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$earList, 'empty'=>'','required'=>false,'id'=>'ears_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Hair :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('hairs_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$hairList, 'empty'=>'','required'=>false,'id'=>'hairs_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Marital Status<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('marital_status_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$maritalStatusList, 'empty'=>'','required'=>false,'id'=>'marital_status_id','onChange'=>'openOtherField("marital_status");'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <?php 
                                        $status_of_women_display = ' style="display:none;"';
                                        if(isset($this->data['Prisoner']['gender_id']) && ($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_FEMALE')))
                                        {
                                            $status_of_women_display = '';
                                        }?>
                                        <div class="control-group" id="status_of_women_div" <?php echo $status_of_women_display;?>>
                                            <label class="control-label">Status Of Women<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('status_of_women_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$statusOfWomenList, 'empty'=>'','required','id'=>'status_of_women_id','onChange'=>'onStatusOfWomen(this.value)'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <?php 
                                        $age_of_pregnancy_display = ' style="display:none;"';
                                        if(
                                            isset($this->data['Prisoner']['gender_id']) && 
                                            ($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_FEMALE'))
                                             && 
                                             isset($this->data['Prisoner']['status_of_women_id']) 
                                             && (
                                                $this->data['Prisoner']['status_of_women_id'] == Configure::read('PREGNANT-WOMEN') 
                                                || ($this->data['Prisoner']['status_of_women_id'] == Configure::read('PREGNANT-WOMEN-WITH-KIN')
                                                    )
                                                )
                                            )
                                        {
                                            $age_of_pregnancy_display = '';
                                        }?>
                                        <div class="control-group" id="age_of_pregnancy_div" <?php echo $age_of_pregnancy_display;?>>
                                            <label class="control-label">Age Of Pregnancy :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('age_of_pregnancy',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Enter Age Of Pregnancy','id'=>'age_of_pregnancy','required'=>false, 'maxlength'=>2, 'minlength'=>1, 'min'=>1, 'max'=>12));?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Body mark --START-- -->
                                    <div class="clearfix"></div> 
                                    <div class="container-fluid">
                                        <div class="span12 secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important;">
                                            <div class="widget-title">
                                                <h5>Body Marks</h5>
                                            </div>
                                            <div class="widget-content">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Distinguish Marks :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('marks',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Distinguish Marks','id'=>'marks','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Head Marks :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('head_marks',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Head Marks','id'=>'marks','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Left Side Marks :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('left_side_marks',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Left Side Marks','id'=>'marks','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Right Side Marks :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('right_side_marks',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Right Side Marks','id'=>'marks','required'=>false));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Body mark -- END -- --> 
                                    
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Deformities :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('deformities',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Deformities','id'=>'deformities','rows'=>3,'required'=>false));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Habits :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('habits',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Habits','id'=>'habits','rows'=>3,'required'=>false));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Age On Admission:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('age_on_admission',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Age On Admission','required'=>false,  'readonly','id'=>'personal_age_on_admission'));?>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Current Age:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('age',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Current Age','required'=>false,  'readonly','id'=>'personal_current_age'));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Resident Address<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('resident_address',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Resident address','id'=>'resident_address','rows'=>3,'required'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Description :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('description',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Description','id'=>'description','rows'=>3,'required'=>false));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Habitual Prisoner :</label>
                                            <div class="controls uradioBtn">  
                                                <?php 
                                                $habitual_prisoner = 0;
                                                if(isset($this->data['Prisoner']['habitual_prisoner']))
                                                    $habitual_prisoner = $this->data['Prisoner']['habitual_prisoner'];
                                                $habitual_prisoner_options= array(
                                                    '0' =>  'No',
                                                    '1' =>  'Yes'
                                                );
                                                $habitual_prisoner_attributes = array(
                                                    'legend' => false, 
                                                    'value' => $habitual_prisoner,
                                                );
                                                echo $this->Form->radio('habitual_prisoner', $habitual_prisoner_options, $habitual_prisoner_attributes);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>                          
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="submit" id="personalDetailSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn', 'formnovalidate'=>true, 'id'=>'personalDetailResetBtn', 'onclick'=>"resetForm('PrisonerEditForm');"))?>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="id_proof_details" class="lorem">
                                <?php //if($editPrisoner == 1)
                                //{?>
                                    <?php echo $this->Form->create('PrisonerIdDetail',array('class'=>'form-horizontal','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'#id_proof_details'));?>
                                    <?php echo $this->Form->input('id');?>
                                    <?php  echo $this->Form->input('prisoner_id',array(
                                            'type'=>'hidden',
                                            'class'=>'prisoner_id',
                                            'value'=>$this->request->data['Prisoner']['id']
                                          ));

                                          echo $this->Form->input('puuid',array(
                                            'type'=>'hidden',
                                            'class'=>'prisoner_id',
                                            'value'=>$this->request->data['Prisoner']['uuid']
                                          ));
                                        $disableIdProofNo = 'readonly';
                                        if(isset($this->request->data['PrisonerIdDetail']['id']) && ($this->request->data['PrisonerIdDetail']['id'] != ''))
                                        {
                                            $disableIdProofNo = '';
                                        }
                                    ?>
                                    
                                    <div class="row-fluid" style="padding-bottom: 14px;">
                                       <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">ID Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('id_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$id_name, 'empty'=>'','required'=>false,'id'=>'id_name', 'onChange'=>'enableIdNumberField(this.value);'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">ID Number<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('id_number',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter ID Number','required'=>false,'id'=>'id_number', 'maxlength'=>'30', $disableIdProofNo));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <button type="submit" tabcls="next" id="saveBtn_iddetail" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                                        <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerIdDetailEditForm');"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php //}?>
                                <div id="personalid_listview">
                                </div>
                            </div>
                            <?php //if((isset($this->request->data['Prisoner']['status_of_women_id']) && ($this->request->data['Prisoner']['status_of_women_id'] != 3)) || (isset($this->request->data['Prisoner']['gender_id']) && ($this->request->data['Prisoner']['gender_id'] == 1)))
                            //{?>
                                <div id="kin_details">
                                    <?php //if($editPrisoner == 1)
                                    //{?>
                                        <?php echo $this->Form->create('PrisonerKinDetail',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/PrisonerKinDetail'));?>
                                        <?php echo $this->Form->input('id');?>
                                        <?php  echo $this->Form->input('prisoner_id',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['id']
                                              ));
                                              echo $this->Form->input('puuid',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['uuid']
                                              ));
                                        ?>
                                        <div class="" style="padding-bottom: 14px;">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">First Name<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11  alpha','type'=>'text','placeholder'=>'Enter First Name','required','id'=>'first_name', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Middle Name:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('middle_name',array('div'=>false,'label'=>false,'class'=>'form-control span11  alpha','type'=>'text','placeholder'=>'Enter Middle Name','required'=>false,'id'=>'middle_name', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Surname:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11  alpha','type'=>'text','placeholder'=>'Enter Surname','required'=>false,'id'=>'last_name', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Relationship<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('relationship',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$relationshipList, 'empty'=>'','required','id'=>'relationship'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="clearfix"></div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Sex<?php echo $req; ?> :</label>
                                                    <div class="controls uradioBtn">
                                                        <?php 
                                                        $kin_gender = 1;
                                                        if(isset($this->data['PrisonerKinDetail']['gender_id']))
                                                            $kin_gender = $this->data['PrisonerKinDetail']['gender_id'];
                                                        $options2= $genderList;
                                                        $attributes2 = array(
                                                            'legend' => false, 
                                                            'value' => $kin_gender,
                                                        );
                                                        echo $this->Form->radio('gender_id', $options2, $attributes2);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">National Id Number :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('national_id_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter National Id Number",'required'=>false,'id'=>'national_id_no', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>                   
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Phone Number <?php// echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <div class="span6">
                                                            <?php 
                                                            if(empty($countryCodeList))
                                                            {
                                                                echo $this->Form->input('country_phone_code',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text', 'placeholder'=>'Country Code','id'=>'country_phone_code', 'maxlength'=>'5'));
                                                            }
                                                            else 
                                                            {
                                                                echo $this->Form->input('country_phone_code',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$countryCodeList,'required','id'=>'country_phone_code'));
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="span6">
                                                            <?php echo $this->Form->input('phone_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric phone','type'=>'text', 'placeholder'=>'Phone Number','required'=>false,'id'=>'phone_no', 'maxlength'=>'12'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <div class="span6">
                                                            <?php 
                                                            if(empty($countryCodeList))
                                                            {
                                                                echo $this->Form->input('country_phone_code2',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text', 'placeholder'=>'Country Code','id'=>'country_phone_code2', 'maxlength'=>'5'));
                                                            }
                                                            else 
                                                            {
                                                                echo $this->Form->input('country_phone_code2',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$countryCodeList,'required','id'=>'country_phone_code2'));
                                                            }?>
                                                        </div>
                                                        <div class="span6">
                                                            <?php echo $this->Form->input('phone_no2',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric phone','type'=>'text', 'placeholder'=>'Phone Number','required'=>false,'id'=>'phone_no2', 'maxlength'=>'12'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Physical Address<?php// echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textarea('physical_address',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Physical Address','required'=>false,'id'=>'physical_address'));?>
                                                    </div>
                                                </div>
                                            </div>   
                                            <!-- <div class="clearfix"></div>                           
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Cater for Village<?php //echo $req; ?> :</label>
                                                    <div class="controls">
                                                        
                                                        <?php //echo $this->Form->input('village',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Village",'required','id'=>'village', 'maxlength'=>'30'));?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Parish<?php //echo $req; ?> :</label>
                                                    <div class="controls">
                                                        
                                                        <?php //echo $this->Form->input('parish',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Parish",'required','id'=>'parish', 'maxlength'=>'30'));?>
                                                    
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="clearfix"></div> 
                                             <!-- <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Sub Country:</label>
                                                    <div class="controls">
                                                        <?php //echo $this->Form->input('gombolola',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Sub Country",'required'=>false,'id'=>'gombolola', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div> -->
                                            
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">District:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required'=>false,'id'=>'district_id2','onChange'=>'showcounty_kin(this.value)'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">County:</label>
                                                <div class="controls">
                                                <?php
                                                
                                                    echo $this->Form->input('county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allCountyList,'onChange'=>'showsubcounty_kin(this.value)', 'empty'=>'','required'=>false,'id'=>'kin_county_id'));
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="clearfix"></div> 
                                            <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Sub County:</label>
                                                <div class="controls">
                                                <?php
                                                    echo $this->Form->input('gombolola',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allSubCountyList, 'empty'=>'','onChange'=>'showParish_kin(this.value)','required'=>false,'id'=>'gombolola'));
                                               ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Parish:</label>
                                                <div class="controls">
                                                <?php
                                                    echo $this->Form->input('parish',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allParishList, 'empty'=>'','required'=>false,'id'=>'kin_parish_id'));
                                               ?>
                                                </div>
                                            </div>
                                        </div> 
                                            <div class="clearfix"></div>
                                            <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Village:</label>
                                                <div class="controls">
                                                <?php
                                                    echo $this->Form->input('village',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'=>false,'id'=>'village_name','placeholder'=>'Village'));
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label"><!-- Name Of Chief -->Name of LC 1 Chairperson:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('chief_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Name of chief','required'=>false,'id'=>'chief_name', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Passport No.:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('passport_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric','type'=>'text', 'placeholder'=>'Enter Passport No.','required'=>false,'id'=>'passport_no', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Voter ID No.:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('voter_id_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric','type'=>'text', 'placeholder'=>'Enter Voter ID No.','required'=>false,'id'=>'voter_id_no', 'maxlength'=>'30'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            
                                            <div class="clearfix"></div>  

                                                                  
                                        </div>

                                        <div class="form-actions" align="center">
                                            <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn">Save</button>
                                            <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerKinDetailEditForm');"))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    <?php //}?>
                                    <div id="prisonerkindata_listview">
                                    </div>
                                </div>
                            <?php //}?>

                            <div id="child_details">
                                <?php //if($editPrisoner == 1)
                                //{?>
                                    <?php echo $this->Form->create('PrisonerChildDetail',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'/#child_details'));
                                        echo $this->Form->input('id');
                                        echo $this->Form->input('prisoner_id',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['id']
                                              ));
                                        echo $this->Form->input('puuid',array(
                                            'type'=>'hidden',
                                            'class'=>'prisoner_id',
                                            'value'=>$this->request->data['Prisoner']['uuid']
                                          ));
                                    ?>

                                    <div class="" style="padding-bottom: 14px;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Name Of Child <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'name', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Father's Name<?php// echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php 
                                                    $father_name = '';
                                                    $father_name_readonly = '';
                                                    // if(isset($this->data['PrisonerChildDetail']['id']) && !empty($this->data['PrisonerChildDetail']['id']))
                                                    // {
                                                    //     $father_name = $this->data['PrisonerChildDetail']['father_name'];
                                                    //     if($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_MALE'))
                                                    //     {
                                                    //         $father_name_readonly = 'readonly';
                                                    //     }
                                                    // }
                                                    // else 
                                                    // {
                                                    //     if($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_MALE'))
                                                    //     {
                                                    //         $father_name = $this->data['Prisoner']['fullname'];
                                                    //         $father_name_readonly = 'readonly';
                                                    //     }
                                                    // }
                                                    
                                                    echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Father's name",'id'=>'father_name', 'maxlength'=>'30','required'=>false));?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Mother's Name:</label>
                                                <div class="controls">
                                                    <?php 
                                                    $mother_name = '';
                                                    $mother_name_readonly = '';
                                                    // if(isset($this->data['PrisonerChildDetail']['id']) && !empty($this->data['PrisonerChildDetail']['id']))
                                                    // {
                                                    //     $mother_name = $this->data['PrisonerChildDetail']['mother_name'];
                                                    //     if($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_FEMALE'))
                                                    //     {
                                                    //         $mother_name_readonly = 'readonly';
                                                    //     }
                                                    // }
                                                    // else 
                                                    // {
                                                    //     if($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_FEMALE'))
                                                    //     {
                                                    //         $mother_name = $this->data['Prisoner']['fullname'];
                                                    //         $mother_name_readonly = 'readonly';
                                                    //     }
                                                    // }
                                                    echo $this->Form->input('mother_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Mother's name",'id'=>'mother_name', 'maxlength'=>'30', 'default'=>$mother_name, $mother_name_readonly));
                                                    ?>
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Relationship with child:</label>
                                                <div class="controls">
                                                    <?php 
                                                    // $mother_name = '';
                                                    // $mother_name_readonly = '';
                                                    // if(isset($this->data['PrisonerChildDetail']['id']) && !empty($this->data['PrisonerChildDetail']['id']))
                                                    // {
                                                    //     $mother_name = $this->data['PrisonerChildDetail']['relation_with_child'];
                                                    //     if($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_FEMALE'))
                                                    //     {
                                                    //         $mother_name_readonly = 'readonly';
                                                    //     }
                                                    // }
                                                    // else 
                                                    // {
                                                    //     if($this->data['Prisoner']['gender_id'] == Configure::read('GENDER_FEMALE'))
                                                    //     {
                                                    //         $mother_name = $this->data['Prisoner']['fullname'];
                                                    //         $mother_name_readonly = 'readonly';
                                                    //     }
                                                    // }
                                                    echo $this->Form->input('relation_with_child',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Relationship with child",'id'=>'relation_with_child', 'maxlength'=>'30',));?>
                                                </div>
                                            </div>
                                        </div> 
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('dob',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text', 'placeholder'=>'Enter Date of Birth','required','id'=>'child_dob','readonly','onblur'=>'getChildAge(this.value);'));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place Of Birth<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('birth_place',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'textarea', 'placeholder'=>'Enter Place Of Birth','required','id'=>'birth_place', 'maxlength'=>'30','rows'=>"3",'cols'=>"30"));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">District Of Birth<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('district_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required','id'=>'district_of_birth'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Gender<?php echo $req; ?> :</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $child_gender = 1;
                                                    if(isset($this->data['PrisonerChildDetail']['gender_id']))
                                                        $child_gender = $this->data['PrisonerChildDetail']['gender_id'];
                                                    $options2= $genderList;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $child_gender,
                                                    );
                                                    echo $this->Form->radio('gender_id', $options2, $attributes2);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Child Medical Condition :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->textarea('medical_cond',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Child Medical Condition','required'=>false,'id'=>'medical_cond'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Child Physical Condition :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->textarea('medical_cond',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Child Physical Condition','required'=>false,'id'=>'physical_cond'));?>
                                                </div>
                                            </div>
                                        </div>  

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Child Medical Report <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,doc,docx,pdf) type photo!" id='example'></i><?php// echo $req; ?> :</label>
                                                <div class="controls">
                                                    <div id="prevImage_child_medical_document">
                                                        <?php 
                                                        if(isset($this->request->data["PrisonerChildDetail"]["child_medical_document"]) && ($this->request->data["PrisonerChildDetail"]["child_medical_document"] != ''))
                                                        {?>
                                                           <a href="<?php echo $this->webroot; ?>app/webroot/files/childs/medical_document/<?php echo $this->request->data["PrisonerChildDetail"]["child_medical_document"];?>" class="example-image-link" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/childs/medical_document/<?php echo $this->request->data["PrisonerChildDetail"]["child_medical_document"];?>" alt="" width="100px" height="100px"></a>
                                                        <?php }?>
                                                    </div>
                                                    <span id="preview_panel_child_medical_document">
                                                        <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="prev_child_medical_document" class="img_prev" src="#" /></a>
                                                        <span id="remove_child_medical_document" class="remove_img" onclick="removePreview('child_medical_document');">[X]</span>
                                                    </span>
                                                    <div class="clear"></div>
                                                    <?php 
                                                    if(isset($this->request->data["PrisonerChildDetail"]["child_medical_document"]) && ($this->request->data["PrisonerChildDetail"]["child_medical_document"] != ''))
                                                    {
                                                        echo $this->Form->input('child_medical_document',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'child_medical_document', 'onchange'=>'readImage(this,"child_medical_document");','required'=>false));
                                                    }
                                                    else 
                                                    {
                                                        echo $this->Form->input('child_medical_document',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'child_medical_document', 'onchange'=>'readImage(this,"child_medical_document");'));
                                                    }?>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Child Photo <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png) type photo!" id='example'></i>:</label>
                                                <div class="controls">
                                                    <div id="prevImage_child_photo">
                                                        <?php if(isset($this->request->data["PrisonerChildDetail"]["child_photo"]))
                                                        {?>
                                                           <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/childs/photo/<?php echo $this->request->data["PrisonerChildDetail"]["child_photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/childs/photo/<?php echo $this->request->data["PrisonerChildDetail"]["child_photo"];?>" alt="" width="100px" height="100px"></a>
                                                        <?php }?>
                                                    </div>
                                                    <span id="preview_panel_child_photo" class="img_prev">
                                                        <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="prev_child_photo" src="#" class="img_preview_panel" /></a>
                                                        <span id="remove_child_photo" class="remove_img" onclick="removePreview('child_photo');">[X]</span>
                                                    </span>
                                                    <div class="clear"></div>
                                                    <?php echo $this->Form->input('child_photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'child_photo', 'onchange'=>'readImage(this,"child_photo");'));?>
                                                </div>
                                            </div>
                                        </div>            
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Age of Child:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('child_age',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Age of Child','required'=>false,'readonly','id'=>'child_age'));?>
                                                </div>
                                            </div>
                                        </div> 
                                       <!--  <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Probation report <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,doc,docx,pdf) type photo!" id='example'></i>:</label>
                                                <div class="controls">
                                                    <div id="prevImage_probation_report">
                                                        <?php 
                                                        //if(isset($this->request->data["PrisonerChildDetail"]["probation_report"]) && ($this->request->data["PrisonerChildDetail"]["probation_report"] != ''))
                                                        {?>
                                                           <a href="<?php //echo $this->webroot; ?>app/webroot/files/childs/medical_document/<?php //echo $this->request->data["PrisonerChildDetail"]["probation_report"];?>" class="example-image-link" data-lightbox="example-set"> <img src="<?php //echo $this->webroot; ?>app/webroot/files/childs/medical_document/<?php //echo $this->request->data["PrisonerChildDetail"]["probation_report"];?>" alt="" width="100px" height="100px"></a>
                                                        <?php }?>
                                                    </div>
                                                    <span id="preview_panel_probation_report">
                                                        <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="prev_probation_report" class="img_prev" src="#" /></a>
                                                        <span id="remove_probation_report" class="remove_img" onclick="removePreview('probation_report');">[X]</span>
                                                    </span>
                                                    <div class="clear"></div>
                                                    <?php //echo $this->Form->input('probation_report',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'probation_report', 'onchange'=>'readImage(this,"probation_report");','required'=>false));?>
                                                </div>
                                            </div>
                                        </div>  -->
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Child Description:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->textarea('child_desc',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Enter Child Description','required'=>false,'id'=>'child_desc'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Born in hospital ?:</label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $hospital_birth = 'N';
                                                    if(isset($this->data['PrisonerChildDetail']['is_hospital_birth']))
                                                        $hospital_birth = $this->data['PrisonerChildDetail']['is_hospital_birth'];
                                                    $options2= array('Y'=>'Yes','N'=>'No');
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value'  => $hospital_birth,
                                                        'onChange' => 'showHospital(this.value)',
                                                    );
                                                    echo $this->Form->radio('is_hospital_birth', $options2, $attributes2);
                                                    ?>
                                                    <?php 
                                                    //echo $this->Form->radio('child_desc',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Enter Child Description','required'=>false,'id'=>'child_desc'));?>
                                                </div>
                                            </div>
                                        </div>    
                                          <div class="span6" style="display: none;" id="hospita_div">
                                            <div class="control-group">
                                                <label class="control-label">Hospital Name:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('hospital_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$hospitalList, 'empty'=>'','required'=>false,'id'=>'hospital_name'));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div>                
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-actions" align="center">
                                        <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn" formnovalidate="true">Save</button>
                                        <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerChildDetailEditForm');"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php //}?>
                                <div id="prisonerchilddata_listview"></div>
                            </div>
                             <div id="admission_details">
                                <?php echo $this->Form->create('PrisonerAdmission',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'#admission_details'));
                                echo $this->Form->input('id',array('type'=>'hidden'));
                                echo $this->Form->input('prisoner_id',array(
                                        'type'=>'hidden',
                                        'class'=>'prisoner_id',
                                        'value'=>$this->request->data['Prisoner']['id']
                                      ));

                                echo $this->Form->input('created',array(
                                        'type'=>'hidden',
                                        'id'=> 'admission_date',
                                        'default'=> date('d-m-Y')
                                      ));
                                
                                //$isAddCase = $funcall->isAccess('prisoner_admission','is_add');
                                $isAddCase = 1;
                                if($isAddCase == 1)
                                {
                                //if debtor 
                                if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('DEBTOR'))
                                {?>
                                    <div class="" style="padding-bottom: 14px;">
                                        <?php 
                                        echo $this->element('prisoner-admission');
                                        echo $this->element('debtor-judgement');
                                        ?>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Convict/Remand Files? :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('convict_remand_files',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'onClick'=>'showConvictRemandFiles()'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="convict-remand-files" style="display:none;">
                                                <?php echo $this->element('debtor-prisoner-offence');?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                }
                                else 
                                {
                                    ?>
                                    <div class="" style="padding-bottom: 14px;">
                                        <?php 
                                        echo $this->element('prisoner-convict-admission');
                                        echo $this->element('prisoner-offence');
                                        ?>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Debtor Files? :</label>
                                                    <div class="controls">
                                                        <?php //debug($this->data['PrisonerAdmission']); exit;
                                                        $debtor_files = '';
                                                        $display_debtor_files =  'style="display:none;"';
                                                        if (isset($this->data['PrisonerAdmission']['debtor_files']) && $this->data['PrisonerAdmission']['debtor_files'] == 1) 
                                                        {
                                                            $debtor_files = 'checked';
                                                            $display_debtor_files = '';
                                                        }
                                                        echo $this->Form->input('debtor_files',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'onClick'=>'showDebtorFiles()', $debtor_files));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="debtor-files" <?php echo $display_debtor_files;?>>
                                                <?php echo $this->element('convict-debtor-judgement');?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                }?>
                                <div class="form-actions" align="center">
                                    <button type="save" tabcls="next" id="admissionSaveBtn" class="btn btn-success formSaveBtn">Save</button>
                                </div>
                                <?php }
                                echo $this->Form->end();?>
                                <div id="prisoner_files_listview"></div>
                            </div>
                            <div id="special_needs">
                                <?php //if($editPrisoner == 1)
                                //{?>
                                    <?php echo $this->Form->create('PrisonerSpecialNeed',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/prisonerSpecialNeed'));
                                    echo $this->Form->input('id',array('type'=>'hidden'));
                                    echo $this->Form->input('prisoner_id',array(
                                            'type'=>'hidden',
                                            'class'=>'prisoner_id',
                                            'value'=>$this->request->data['Prisoner']['id']
                                          ));
                                    echo $this->Form->input('puuid',array(
                                            'type'=>'hidden',
                                            'class'=>'prisoner_id',
                                            'value'=>$this->request->data['Prisoner']['uuid']
                                          ));
                                    echo $this->Form->input('prison_station',array(
                                            'type'=>'hidden',
                                            'class'=>'prison_station',
                                            'value'=>$prison_id
                                          ));
                                    ?>
                                    <div class="row" style="padding-bottom: 14px; margin-left: 0px;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prison Station<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prison_station_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prison Station','required','readonly','value'=>$prison_name, 'id'=>'prison_station_name'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner Number','required','id'=>'prisoner_no',  'readonly','value'=>$this->request->data['Prisoner']['prisoner_no']));?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="clearfix"></div> 
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Type of Disability<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('special_condition_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select', 'onChange'=>'getTypeOfDisability()', 'options'=>$specialConditionList, 'empty'=>'','id'=>'special_condition_id', 'required'));?>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Subcategory Disability :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('type_of_disability',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>'', 'options'=>$typeOfDisabilityList, 'empty'=>'','id'=>'type_of_disability', 'required'=>false));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                            
                                    </div>

                                    <div class="form-actions" align="center">
                                        <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn">Save</button>
                                        <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerSpecialNeedEditForm');"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php //}?>
                                <div id="specialneed_listview"></div>
                            </div>
                            <div id="sentence_capture">
                                <?php //if($editPrisoner == 1)
                                //{?>
                                    <?php echo $this->Form->create('PrisonerSentenceCapture',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'#sentence_capture'));
                                    echo $this->Form->input('id',array('type'=>'hidden'));
                                    echo $this->Form->input('prisoner_id',array(
                                            'type'=>'hidden',
                                            'class'=>'prisoner_id',
                                            'value'=>$this->request->data['Prisoner']['id']
                                          ));
                                    echo $this->Form->input('sentence_from',array(
                                            'type'=>'hidden',
                                            'value'=>'Sentence'
                                          ));

                                    //echo $this->element('prisoner_sentence');


                                    ?>
                                    <div class="" style="padding-bottom: 14px;">
                                        <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
                                            <div class="widget-title">
                                                <h5>Prisoner Details</h5>
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
                                                <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] != Configure::read('REMAND'))
                                                    {?>   
                                                        <div class="clearfix"></div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">Class :</label>
                                                               <div class="controls">
                                                                    <?php 
                                                                    echo $this->Form->input('class',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$classificationList, 'empty'=>'','required','id'=>'class2', 'disabled', 'selected'=>$prisoner_class));?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <!-- Offence detail END --> 
                                    </div>
                                    <?php echo $this->element('sentence');?>
                                    <div class="form-actions" align="center">
                                        <button type="save" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn" onclick="return validateSentenceCount('sentence_capture_count');">Save</button>
                                        <?php //echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerSentenceCaptureEditForm');"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php //}?>
                                <div id="sentence_capture_listview"></div>
                            </div>
                            <?php if($is_wish_to_appeal > 0){?>
                                <div id="appeal_against_sentence">
                                    <?php //if($editPrisoner == 1)
                                    //{?>
                                        <?php echo $this->Form->create('PrisonerSentenceAppeal',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'#appeal_against_sentence'));
                                        echo $this->Form->input('id',array('type'=>'hidden'));
                                        echo $this->Form->input('prisoner_id',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['id']
                                              ));
                                        echo $this->Form->input('prisoner_no',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_no',
                                                'value'=>$this->request->data['Prisoner']['prisoner_no']
                                              ));
                                        echo $this->Form->input('puuid',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['uuid']
                                              ));
                                        echo $this->element('appeal');
                                        ?>
                                        <div class="form-actions" align="center">
                                            <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn" onclick="return validateAppealForm();">Save</button>
                                            <button type="cancel" tabcls="next" id="cancelBtn" class="btn btn-danger";">Cancel</button>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    <?php //}?>
                                    <div id="appeal_listview"></div>
                                </div>
                            <?php }
                            if($is_wish_to_appeal > 0){?>
                                <div id="confirmation_sentence">
                                    <?php //if($editPrisoner == 1)
                                    //{?>
                                        <?php echo $this->Form->create('PrisonerSentenceConfirmation',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'#confirmation_sentence'));
                                        echo $this->Form->input('id',array('type'=>'hidden'));
                                        echo $this->Form->input('prisoner_id',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['id']
                                              ));
                                        echo $this->Form->input('prisoner_no',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_no',
                                                'value'=>$this->request->data['Prisoner']['prisoner_no']
                                              ));
                                        echo $this->Form->input('puuid',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['uuid']
                                              ));
                                        echo $this->element('confirmation');
                                        ?>
                                        <div class="form-actions" align="center">
                                            <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn">Save</button>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    <?php //}?>
                                    <div id="confirmation_listview"></div>
                                </div>
                            <?php }
                            if($is_petiton > 0){?>
                                <div id="petition_tab">

                                    <div id="pf98">
                                        <?php echo $this->Html->link('PF-98',array('controller'=>'ExtractPrisonersRecord','action'=>'add/'.$prisoner_id),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                                    </div>
                                    <?php //if($editPrisoner == 1)
                                    // debug($this->data);
                                    //{?>
                                        <?php echo $this->Form->create('PrisonerPetition',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/PrisonerPetition'));
                                        echo $this->Form->input('id',array('type'=>'hidden'));
                                        echo $this->Form->input('prisoner_id',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['id']
                                              ));
                                        echo $this->Form->input('prisoner_no',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_no',
                                                'value'=>$this->request->data['Prisoner']['prisoner_no']
                                              ));
                                        echo $this->Form->input('puuid',array(
                                                'type'=>'hidden',
                                                'class'=>'prisoner_id',
                                                'value'=>$this->request->data['Prisoner']['uuid']
                                              ));
                                          echo $this->Form->input('petition_type',array('type'=>'hidden', 'value'=>'Admission petition'));
                                        echo $this->element('petition');
                                        ?>
                                        <div class="form-actions petition_hide" align="center">
                                            <button type="submit" tabcls="next" id="petitionSaveBtn" class="btn btn-success formSaveBtn">Save</button>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    <?php //}?>
                                    <div id="petition_listview"></div>
                                </div>
                            <?php }
                            if($escapeCount->display_recapture_tab != '')
                            {?>
                                <div id="recaptured_details">
                                    <?php if(($escapeCount->display_recapture_form == 1) || isset($this->request->data['PrisonerRecaptureDetail']['id']))
                                    {?>
                                        <?php //if($editPrisoner == 1)
                                        //{?>
                                            <?php echo $this->Form->create('PrisonerRecaptureDetail',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/prisonerRecaptureDetail'));
                                            echo $this->Form->input('id',array('type'=>'hidden'));
                                            echo $this->Form->input('prisoner_id',array(
                                                    'type'=>'hidden',
                                                    'value'=>$this->request->data['Prisoner']['id']
                                                  ));
                                            echo $this->Form->input('puuid',array(
                                                    'type'=>'hidden',
                                                    'value'=>$this->request->data['Prisoner']['uuid']
                                                  ));
                                            echo $this->Form->input('escape_discharge_id',array(
                                                    'type'=>'hidden',
                                                    'value'=>$escapeCount->escape_discharge_id
                                                  ));
                                            ?>
                                            <div class="row" style="padding-bottom: 14px;">
                                                
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner Number','required','id'=>'prisoner_no',  'readonly','value'=>$this->request->data['Prisoner']['prisoner_no']));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Full Name of Prisoner:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('surname',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Surname','required','id'=>'surname', 'readonly', 'value'=>$this->request->data['Prisoner']['fullname'], 'maxlength'=>'30'));?>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="clearfix"></div> 
                                                <div class="span6">
                                                   <div class="control-group">
                                                        <label class="control-label">Date of Escape:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('escape_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text', 'placeholder'=>'Enter Date of Escape','required'=>false,'readonly'=>'readonly','id'=>'escape_date', 'required', 'value'=>$escapeCount->date_of_escape));?>
                                                        </div>
                                                    </div>
                                                </div>  
                                                
                                                 <div class="span6">
                                                   <div class="control-group">
                                                        <label class="control-label">Date of Recapture<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('recapture_date',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate span11','type'=>'text', 'placeholder'=>'Enter Date of Recapture','required'=>false,'readonly'=>'readonly','id'=>'recapture_date', 'required'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Place of capture :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('place_of_recapture',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Place of capture','required'=>false,'id'=>'place_of_recapture', 'maxlength'=>'30'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions" align="center">
                                                <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn">Save</button>
                                                <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerRecaptureDetailEditForm');"))?>
                                            </div>
                                            <?php echo $this->Form->end();?>
                                        <?php //}?>
                                    <?php }?>
                                    <div id="recapture_listview"></div>
                                </div>
                            <?php }
                            if($bailCount->display_bail_tab != '')
                            {?>
                                <div id="bail_details">
                                    <?php if(($bailCount->display_bail_form == 1) || isset($this->request->data['PrisonerBailDetail']['id']))
                                    {?>
                                        <?php //if($editPrisoner == 1)
                                        //{?>
                                            <?php echo $this->Form->create('PrisonerBailDetail',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/prisonerBailDetail'));
                                            echo $this->Form->input('id',array('type'=>'hidden'));
                                            echo $this->Form->input('prisoner_id',array(
                                                    'type'=>'hidden',
                                                    'value'=>$this->request->data['Prisoner']['id']
                                                  ));
                                            echo $this->Form->input('puuid',array(
                                                    'type'=>'hidden',
                                                    'value'=>$this->request->data['Prisoner']['uuid']
                                                  ));
                                            echo $this->Form->input('bail_discharge_id',array(
                                                    'type'=>'hidden',
                                                    'value'=>$bailCount->bail_discharge_id
                                                  ));
                                            ?>
                                            <div class="row" style="padding-bottom: 14px;">
                                                
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner Number','required','id'=>'prisoner_no',  'readonly','value'=>$this->request->data['Prisoner']['prisoner_no']));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Full Name of Prisoner:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Full Name of Prisoner','required','id'=>'prisoner_name', 'readonly', 'value'=>$this->request->data['Prisoner']['fullname'], 'maxlength'=>'30'));?>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="clearfix"></div> 
                                                <div class="span6">
                                                   <div class="control-group">
                                                        <label class="control-label">Bail Start Date:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('bail_start_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text', 'placeholder'=>'Bail Start Date','required'=>false,'readonly'=>'readonly','id'=>'bail_start_date', 'required', 'value'=>$bailCount->bail_start_date));?>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="span6">
                                                   <div class="control-group">
                                                        <label class="control-label">Bail End Date:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('bail_end_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text', 'placeholder'=>'Bail End Date','required'=>false,'readonly'=>'readonly','id'=>'bail_end_date', 'required', 'value'=>$bailCount->bail_end_date));?>
                                                        </div>
                                                    </div>
                                                </div> 
                                                
                                                 <div class="span6">
                                                   <div class="control-group">
                                                        <label class="control-label">Date of Renter<br> To Prison<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('reenter_to_prison_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 ','type'=>'text', 'placeholder'=>'Enter Date of Renter To Prison','readonly'=>'readonly','id'=>'reenter_to_prison_date', 'required', 'value'=>date('d-m-Y')));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Bail Cancel Date :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('bail_cancel_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Bail Cancel Date','required'=>false,'id'=>'bail_cancel_date', 'readonly'=>'readonly'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions" align="center">
                                                <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn">Save</button>
                                                <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerRecaptureDetailEditForm');"))?>
                                            </div>
                                            <?php echo $this->Form->end();?>
                                        <?php //}?>
                                    <?php }?>
                                    <div id="bail_listview"></div>
                                </div>
                            <?php }?>
                            <div id="assign_ward">
                                <?php 
                                //debug($wardHistoryList); exit;
                                if(count($wardHistoryList) > 0)
                                {?> 
                                    <div class="span4" style="float:right; text-align: right;">
                                        <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#wardHistory">Ward History</button>
                                        <!-- wardHistory modal start -->
                                         <div id="wardHistory" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Ward History</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th>Ward Name</th>
                                                                <th>Cell Name</th>
                                                                <th>Date</th>
                                                            </tr>
                                                            <?php 
                                                            foreach($wardHistoryList as $wHKey=>$wHValue)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $wHValue['Ward']['name'];?></td>
                                                                    <td><?php echo $wHValue['WardCell']['cell_name'];?></td>
                                                                    <td><?php echo date('d-m-Y H:i', strtotime($wHValue['PrisonerWardHistory']['created']));?></td>
                                                                </tr>
                                                                <?php 
                                                           }
                                                            ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- wardHistory modal end -->
                                    </div>
                                <?php }?>
                                <?php echo $this->Form->create('PrisonerWard',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/edit/'.$this->request->data['Prisoner']['uuid'].'#assign_ward'));
                                echo $this->Form->input('id',array('type'=>'hidden'));
                                echo $this->Form->input('prisoner_id',array(
                                        'type'=>'hidden',
                                        'value'=>$this->request->data['Prisoner']['id']
                                      ));
                                ?>
                                <?php 
                                $hideWardForm = '';
                                //debug($this->request); exit;
                                if(isset($this->request->data['Prisoner']['assigned_ward_id']) && ($this->request->data['Prisoner']['assigned_ward_id'] > 0))
                                {
                                    $hideWardForm = 'display:none';
                                    ?>
                                    <div class="row" style="padding-bottom: 14px;" id="displayWardDiv">
                                        <div class="span8">
                                            <div class="control-group">
                                                <label class="control-label">Assigned Ward :</label>
                                                <div class="controls" style="padding-top: 15px;">
                                                    <?php echo $this->Form->input('assigned_ward_id1',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$wardList, 'empty'=>'','required'=>false,'disabled','style'=>'width:70%', 'default'=> $this->request->data['Prisoner']['assigned_ward_id']));?>
                                                    <?php echo $this->Form->button('Edit', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success btn-mini', 'formnovalidate'=>true, 'onclick'=>"editWard();", 'style'=>'margin-left:10px; margin-top:2px;'))?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span8">
                                            <div class="control-group">
                                                <label class="control-label">Assigned Ward Cell:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('ward_cell_id1',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select', 'options'=>$wardCellList,'empty'=>'','required'=>false,'id'=>'ward_cell_id','disabled', 'style'=>'width:70%', 'default'=> $this->request->data['Prisoner']['assigned_ward_cell_id']));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                }?>
                                <div id="wardForm" class="row-fluid widget-box" style="padding-bottom: 14px; background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; <?php echo $hideWardForm;?>">
                                    <div class="row" style="padding-bottom: 14px;">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Ward <?php echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('assigned_ward_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','onchange'=>'showWardCell()','options'=>$wardList, 'empty'=>'','required','id'=>'assigned_ward_id', 'default'=> $this->request->data['Prisoner']['assigned_ward_id'], 'title'=>'Ward is required.'));?>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Ward Cell <?php echo $req; ?>::</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('ward_cell_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select', 'options'=>$wardCellList,'empty'=>'','required','id'=>'ward_cell_id', 'default'=> $this->request->data['Prisoner']['assigned_ward_cell_id'],'title'=>'Ward cell is required.'));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success formSaveBtn">Save</button>
                                        <?php //echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerRecaptureDetailEditForm');"))?>
                                        <?php //echo $this->Form->button('Cancel', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-gray', 'formnovalidate'=>true, 'onclick'=>"showWard();"))?>
                                    </div>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<style>
.ac-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, .6);
    z-index: 1001;
}
.popup {
    width: 555px;
    height: 180px;
    background: #FFFFFF;
    border: 5px solid #000;
    border-radius: 25px;
    -moz-border-radius: 25px;
    -webkit-border-radius: 25px;
    box-shadow: #64686e 0px 0px 3px 3px;
    -moz-box-shadow: #64686e 0px 0px 3px 3px;
    -webkit-box-shadow: #64686e 0px 0px 3px 3px;
    position: relative;
    top: 150px;
    left: 557px;
}
</style>
<div id="ac-wrapper" class="ac-wrapper" style='display:none'>
    <div id="popup">
        <center>
             <h3>Appeal Status is completed, so it redirected to Appeal</h3>
        </center>
    </div>
</div>
<div id="appeal_popup" class="ac-wrapper" style='display:none'>
    <div id="popup2" class='popup'>
        <center>
             <h3>Appeal Status is "Cause List", so it redirected to "To Court" of court attendance module.</h3>
        </center>
    </div>
</div>
<!--Modal-->
<?php $from_court = isset($this->request->pass[1]) ? $this->request->pass[1] : '';?>
<script type="text/javascript">
function PopUp(hideOrshow) {
    if (hideOrshow == 'hide') document.getElementById('ac-wrapper').style.display = "none";
    else document.getElementById('ac-wrapper').removeAttribute('style');
}
//open popup message for redirecting appeal to court -- START -- 
function appealPopUp(hideOrshow) {
    if (hideOrshow == 'hide') document.getElementById('appeal_popup').style.display = "none";
    else document.getElementById('appeal_popup').removeAttribute('style');
}
//open popup message for redirecting appeal to court -- END -- 
$( document ).ready(function() {
//open registration details if not filled -- START -- 
<?php 
if(trim($this->data['Prisoner']['permanent_address']) == '' && (int)$this->data['Prisoner']['desired_districts_relese'] == 0)
{?>
    displayPrisonerDetail();
<?php }?>
//open registration details if not filled -- END -- 
/* from return form court appeal status completed */
var from_court = "<?php echo $from_court;?>";

if(from_court != '')
{
	setTimeout(function () {
        PopUp('show');
        $('#appeal_against_sentence_tab').click();
    }, 0);
	
	setTimeout(function () {
        PopUp('hide');
    }, 3000);
}
/*--end */
/* Redirect to "To court" on appeal status cause list */

var to_court = "<?php echo $to_court;?>";
if(to_court!='')
{
    setTimeout(function () {
        appealPopUp('show');
    }, 0);
    
    setTimeout(function () {
        appealPopUp('hide');
        //redirect to to court tab
        var toCourtURL = '<?php echo $this->Html->url(array('controller'=>'Courtattendances','action'=>'index/'.$prisoner_uuid.'/'.$to_court.'#produceToCourt'));?>';
        window.location.href = toCourtURL;
    }, 3000);
}
/*--end */

     if ($('#PrisonerChildDetailIsHospitalBirthY:checked').val() == 'Y')
     {
        showHospital('Y');
     }
     else 
     {
        showHospital('N');
     }
  var dis_id='';
    <?php if(isset($this->request->data['Prisoner']['district_id'])){?>
       dis_id = '<?php echo $this->request->data['Prisoner']['district_id'];?>';
        showcounty(dis_id);
    <?php }?>
    <?php if(isset($this->request->data['PrisonerKinDetail']['district_id'])){?>
       dis_id = '<?php echo $this->request->data['PrisonerKinDetail']['district_id'];?>';
        showcounty_kin(dis_id);
    <?php }?>
    showPrisonerSubType();
    showReparitaion();
    

});

function makeMandatory(val){
    if(val==11){
        $('#highcourt').show();
        $('#high_court_case_no1').attr("required", "true");
    }else{
        $('#highcourt').hide();
        $('#high_court_case_no1').removeAttr('required');
    }
}
function onStatusOfWomen(val)
{
    $('#age_of_pregnancy_div').hide();
    if(val == 'other')
        openOtherField("status_of_women");
    else 
    {
        if(val =="<?php echo Configure::read('PREGNANT-WOMEN');?>" || val == '<?php echo Configure::read('PREGNANT-WOMEN-WITH-KIN');?>')
        {
            $('#age_of_pregnancy_div').show();
        }
    }
}
//read url and display pic on file upload -- START --
function readURL(input, cnt='') 
{
    var file = input.files[0];
    var fileType = file["type"];
    var ValidImageTypes = ["image/gif", "image/jpg", "image/jpeg", "image/png"];
    if ($.inArray(fileType, ValidImageTypes) < 0) 
    { 
        dynamicAlertBox('Error','Invalid image type.<br>Please upload (jpg, jpeg, png, gif) type photo.');
        $(input).val('');
    }
    else {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img_prev'+cnt)
                .attr('src', e.target.result)
                .width(100);
                $('#img_prev'+cnt).closest('.preview_image').attr('href', e.target.result);
                $('#is_photo'+cnt).val(1); 
            };
            reader.readAsDataURL(input.files[0]);
        }
        else {
            alert(2)
            var img = input.value;
            $('#img_prev'+cnt).attr('src',img).width(100);
        }
        $('#prevImage'+cnt).hide();
        $('#img_prev'+cnt).show();
        $("#x"+cnt).show().css("margin-right","10px");
    }
}
//read url and display pic on file upload -- END --
//open other field 
function openOtherField(fname)
{
    //alert(fname);
    var field_id = fname+'_id';
    var selected_val = $('#'+field_id).val();
    var other_field_id = 'other_'+fname;
    var placeholder_val = fname.charAt(0).toUpperCase() + fname.substr(1);
    placeholder_val = placeholder_val.replace(/_/g, " ");
    if(selected_val == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_'+fname+']" class="form-control span11" placeholder="'+placeholder_val+'" id="other_'+fname+'" style="" type="text" maxlength="30" required></div>';
        $( other_field ).insertAfter('#'+field_id);
    }
    else 
    {
        if ($('#'+other_field_id).length)
        {
            //remove error mesage
            $('#'+other_field_id).parent().find('label').remove();
            //remove other field  
            $('#'+other_field_id).remove();
        }
    }
}
// function to display ward form 
function editWard()
{
    AsyncConfirmYesNo(
      "Are you sure want to edit ward?",
      'Edit',
      'Cancel',
      function(){
        $('#wardForm').show();
        $('#displayWardDiv').hide();
      },
      function(){}
  );
}
function showWard()
{
    $('#wardForm').hide();
    $('#displayWardDiv').show();
}
$(function(){ 
    //open prisoner registration info if prisoner added by gate keeper -- START --
    <?php 
    if((isset($this->data['Prisoner']['permanent_address']) && $this->data['Prisoner']['permanent_address']== '') || (isset($this->data['Prisoner']['desired_districts_relese']) && $this->data['Prisoner']['desired_districts_relese']== '0'))
    {?>
        $('#prisonerInfo').click();
    <?php }?>
    //open prisoner registration info if prisoner added by gate keeper -- START --
    var sof = $('#sentence_of_capture').val();
    setSentence(sof);
});
function setSentence(sof,stype='sentenceCapture')
{ 
    $('#'+stype+'_receipt_number input').val('');
    $('#'+stype+'_fine_with_imprisonment input').val('');
    $('#'+stype+'_fine_amount input').val('');
    $('#'+stype+'_payment_date input').val('');
    //remove required validation
    $('#'+stype+'_receipt_number #receipt_number').prop('required',false);
    $('#'+stype+'_fine_with_imprisonment #fine_with_imprisonment').prop('required',false);
    $('#'+stype+'_fine_amount #fine_amount').prop('required',false);
    //wish to appeal fields
    $('#reciept_upload_div').hide();
    $('#uniform-PrisonerSentenceCaptureWishToAppeal0').show();
    $('#uniform-PrisonerSentenceCaptureWishToAppeal0').next("label").show();
    if(sof == 0 || sof == 4 || sof == 5)
    {
        $('#'+stype+'_receipt_number').hide();
        $('#'+stype+'_fine_with_imprisonment').hide();
        $('#'+stype+'_fine_amount').hide();
        $('#'+stype+'_payment_date').hide();
        $('.'+stype+'Div').hide();
        if(sof == 4)
        { 
            //For Death Prisoner --START--
            $('#PrisonerSentenceCaptureWishToAppeal0').prop('checked',false);
            $('#uniform-PrisonerSentenceCaptureWishToAppeal0').hide();
            $('#uniform-PrisonerSentenceCaptureWishToAppeal0').next("label").hide();
            $('#PrisonerSentenceCaptureWishToAppeal1').prop('checked',true);
            $('#PrisonerSentenceCaptureWishToAppeal1').click();
            //For Death Prisoner --END--
        }
    }
    if(sof == 1)
    { 
        $('#'+stype+'_receipt_number').hide();
        $('#'+stype+'_fine_with_imprisonment').hide();
        $('#'+stype+'_fine_amount').hide();
        $('#'+stype+'_payment_date').hide();
        $('.'+stype+'Div').show();
    } 
    if(sof == 2)
    {
        $('#'+stype+'_receipt_number').show();
        $('#'+stype+'_fine_with_imprisonment').show();
        $('#'+stype+'_fine_amount').hide();
        $('#'+stype+'_payment_date').show();
        $('.'+stype+'Div').show();
        $('#reciept_upload_div').show();
        $('#fine_with_imprisonment').attr('required','required');
        //addSentenceCount();
    }
    if(sof == 3)
    {
        $('#'+stype+'_receipt_number').show();
        $('#'+stype+'_fine_with_imprisonment').hide();
        $('#'+stype+'_fine_amount').show();
        $('#'+stype+'_payment_date').show();
        $('#reciept_upload_div').show();

        $('#fine_with_imprisonment').prop('required', false);

        //add required validation 
        $('#'+stype+'_receipt_number #receipt_number').prop('required',true);
        //$('#'+stype+'_fine_with_imprisonment #fine_with_imprisonment').prop('required',true);
        $('#'+stype+'_fine_amount #fine_amount').prop('required',true);

        $('.'+stype+'Div').hide();
    }
}
function showNationality2(isdual)
{
    if(isdual == 1)
    {
        $('#nationality_name2').show();
        $('#nationality_name2_note').show();
        $('#nationality_name2').select2();
    }
    else 
    {
        $('#s2id_nationality_name2').hide();
        $('#nationality_name2_note').hide();
        $('#nationality_name2_note').val('');
        $('#nationality_name2').hide();
        $('#nationality_name2').val('');
    }
}
function readImage(input, fieldname)
{   
    var file = input.files[0];
    var fileType = file["type"];
    var ValidImageTypes = ["image/gif", "image/jpg", "image/jpeg", "image/png"];
    if ($.inArray(fileType, ValidImageTypes) < 0) 
    { 
        dynamicAlertBox('Error','Invalid image type.<br>Please upload (jpg, jpeg, png, gif) type photo.');
    }
    else {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) { 
                $('#prev_'+fieldname)
                .attr('src', e.target.result)
                .width(100);
                $('#prev_'+fieldname).closest('.preview_image').attr('href', e.target.result);
                
            };
            reader.readAsDataURL(input.files[0]);
            $('#prevImage_'+fieldname).hide();
        }
        else {
          var img = input.value;
            $('#prev_'+fieldname).attr('src',img).width(100);
        }
        $('#preview_panel_'+fieldname).show();
        $('#prev_'+fieldname).show();
        $("#remove_"+fieldname).show().css("margin-right","10px");
    }
}
function removePreview(fieldname)
{
    $('#'+fieldname).val("");
    $("#prev_"+fieldname).attr("src",'');
    $('#prev_'+fieldname).hide();
    $("#remove_"+fieldname).hide();  
    $('#uniform-'+fieldname+' span.filename').html('');
    $('#prevImage_'+fieldname).show();
}
//goto offence count
function goToOffenceCount(id)
{
    $('#appeal_against_sentence_tab').click();
    //$("#PrisonerOffenceCountOffenceId option[value='" + id + "']").attr("selected", "true");
    $("select#PrisonerOffenceCountOffenceId").val(id);
}
// get offence list based on category 
function showOffence(id,solid)
{ 
    var _offence_category_id = $('#'+solid+'_offence_category_id').val(); 
    var solURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getOffenceList'));?>';
    
    $.post(solURL,{"_offence_category_id":_offence_category_id},function(data){  
        
        if(data) { 
            $('#'+solid+'_offence').html(data); 
        }
        else
        {
            alert("Error...");  
        }
    });
}

//get court details 
function getCourtDetails(court_id)
{
    var url = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getCourtDetails')); ?>';
    $.post(url, {'court_id':court_id}, function(res)
    {
        var result = jQuery.parseJSON(res); 
        $('#presiding_judge_id').html(result.judgeData);
        $('#magisterial_id').select2('val', result.magisterial_id);
        $('#presiding_judge_id').select2('val', '');
    });
}
//get court list 
var is_remand = 0;
<?php 
if($this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
{?>
    var is_remand = 1;
<?php }?>
function getCourtList(id, cnt)
{
    var is_valid = '';
    if(is_remand == 1)
    {
        is_valid = "<span style='color:red;'>*</span>";
    }
    $('#'+cnt+'_magistrate_level').text("Presiding Judicial Officer:");
    var strURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'courtList'));?>';
    $.post(strURL,{"courtlevel_id":id},function(data){ 
        
        if(data) { 
            $('#'+cnt+'_court_id').html(data); 
        }
        else
        {
            alert("Error...");  
        }
    }); 
    $('#'+cnt+'_court_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });

    if(id == 8)
    {
        $('#'+cnt+'_highcourt_file_no_reqd').removeClass('hidden');
        $('#'+cnt+'_court_file_no_reqd').addClass('hidden');
        $('#'+cnt+'_court_file_no').prop('required', false);
        $('#'+cnt+'_highcourt_file_no').prop('required', true);
    }
    else 
    {
        $('#'+cnt+'_highcourt_file_no_reqd').addClass('hidden');
        $('#'+cnt+'_court_file_no_reqd').removeClass('hidden', '');
        $('#'+cnt+'_highcourt_file_no').removeAttr('required', '');
        $('#'+cnt+'_court_file_no').attr('required', 'required');
    }
    if(id == 5 || id == 6)
    {
        //Magistrate Grade 1 / Magistrate Grade 2
        $('#'+cnt+'_magistrate_level').html("Magistrate"+is_valid+":");
        $('#'+cnt+'_judicial_officer').attr("placeholder","Magistrate");
        $('#'+cnt+'_judicial_officer').attr("title","Enter Magistrate");
    }
    if(id == 7)
    {
        //Chief Magistrate
        $('#'+cnt+'_magistrate_level').html("Chief Magistrate"+is_valid+":");
        $('#'+cnt+'_judicial_officer').attr("placeholder","Chief Magistrate");
        $('#'+cnt+'_judicial_officer').attr("title","Enter Chief Magistrate");
    }
    if(id == 8)
    {
        //High Court
        $('#'+cnt+'_magistrate_level').html("Judges"+is_valid+":");
        $('#'+cnt+'_judicial_officer').attr("placeholder","Judges");
        $('#'+cnt+'_judicial_officer').attr("title","Enter Judges");
    }
    if(id == 9 || id == 10)
    {
        //supreme court/court of appeal
        $('#'+cnt+'_magistrate_level').html("Panel Of Justices"+is_valid+":");
        $('#'+cnt+'_judicial_officer').attr("placeholder","Panel Of Justices");
        $('#'+cnt+'_judicial_officer').attr("title","Enter Panel Of Justices");
        $('#'+cnt+'_judges_btn').removeClass('hidden');
    }
    else 
    {	
        $('#'+cnt+'_judges_btn').addClass('hidden');
    }


    // if(id == 5 || id == 6 || id == 7)
    // {
    //     $('#'+cnt+'_crb_no_reqd').removeClass('hidden');
    //     $('#'+cnt+'_crb_no').prop('required',true);
    // }
    // else 
    // {
    //     $('#'+cnt+'_crb_no_reqd').addClass('hidden');
    //     $('#'+cnt+'_crb_no').prop('required',false);
    // }
}
//get section of laws
function getSOLaws(offence_id,case_cnt, cnt)
{ 
    var solURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getSectionOfLaws'));?>';

    var sol_id = '#'+case_cnt+'_'+cnt+'_section_of_law_id';
    var sol_div_id = '#'+case_cnt+'_'+cnt+'_section_of_law_id_div';
    
    $.post(solURL,{"offence_id":offence_id},function(data){  
        if(data.trim() != '') {
            var result = jQuery.parseJSON(data); 
            if(result.isValid == 1)
            {
                $(sol_id).attr('required','required');
                $(sol_id).show();
                $(sol_id).select2();
                $(sol_div_id).removeClass('hidden');
            }
            else if(result.isValid == 0)
            {
                $(sol_id).val('').trigger('change');
                $(sol_id).removeAttr('required','');
                $(sol_div_id).addClass('hidden');
            }
            $(sol_id).html(result.data); 
            $(sol_id).select2({placeholder: "-- Select --"});
        }
        else
        {
            alert("Error...");  
        }
    });
}
//get section of laws
function showSOLaws(id,solid)
{ 
    var offence_ids = $('#'+solid+'_offence_id').val(); 
    var solURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getSectionOfLaws'));?>';
    
    $.post(solURL,{"offence_id":offence_ids},function(data){  
        if(data.trim() != '') {
            var result = jQuery.parseJSON(data); 
            if(result.isValid == 1)
            {
                $('#'+solid+'_section_of_law_id').attr('required','required');
                $('#'+solid+'_section_of_law_id_div').show();
            }
            else if(result.isValid == 0)
            {
                $('#'+solid+'_section_of_law_id').val('').trigger('change');
                $('#'+solid+'_section_of_law_id').removeAttr('required','');
                $('#'+solid+'_section_of_law_id_div').hide();
            }
            $('#'+solid+'_section_of_law_id').html(result.data); 
        }
        else
        {
            alert("Error...");  
        }
    });
}
function showCountries(id)
{
    var strURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'countryList'));?>';
    $.post(strURL,{"continent_id":id},function(data){ 
        
        if(data) { 
            $('#country_id').html(data); 
        }
        else
        {
            alert("Error...");  
        }
        //call function on country change 
        onCountryChange($('#country_id').val());
        showDistricts($('#country_id').val());
    });
}
function onCountryChange(country_id)
{
    $('#nationality_name').val('');
    $('#district_id').select2('val', '');
    if(country_id=='other'){

        $('#other_district').show();
        $('#s2id_district_id').hide();
        $('#other_country').show();   
    }
    if(country_id != 'other')
    {
        $.ajax(
        {
            type: "POST",
            dataType: "html",
            url: "<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getNationName'));?>",
            data: {
                country_id: country_id,
            },
            cache: true,
            beforeSend: function()
            {  
              //$('tbody').html('');
            },
            error: function ( jqXHR, textStatus, errorThrown) {
                 alert("errorThrown: " + errorThrown + " textStatus:" + textStatus);
            },
            success: function (data) {
              $("#nationality_name").val(data);
            },
        });
        $("#nationality_name").attr('readonly','readonly');
    }
    else 
    {
        $("#nationality_name").removeAttr('readonly');
    }
}
function showDistricts(id) 
{
    $('#other_district').hide();
    $('#s2id_district_id').show();
    $('#other_country').hide();
    $('#other_district').val('');
    $('#other_country').val('');
    if(parseInt(id) != 0)
    { 
        var uganda = "<?php echo $uganda_country_id;?>";
        if(id == uganda)
        {
            $('.tribehide').show();
            $('#tribeValid').show();
        }
        else 
        {   $('.tribehide').hide();
            $('#tribeValid').hide();
            $('#tribe_id').next('label.error').remove();
        }       
        var strURL = '<?php echo $this->Html->url(array('controller'=>'Prisoners','action'=>'getDistrict'));?>';
    
        $.post(strURL,{"country_id":id},function(data){  
            
            if(data) { 
                $('#district_id').html(data); 
                
            }
            else
            {
                alert("Error...");  
            }
        });
    }
    else 
    {
        if(id == 'other')
        {
            $('#other_district').show();
            $('#s2id_district_id').hide();
            $('#other_country').show();
        }
    }
}

function showcounty(id)
{     
    $('#county_id').html("<option value=''></option>");
    $('#sub_county_id').html('<option value=""></option>'); 
    $('#parish_id').html('<option value=""></option>'); 
    $('#village_id').html('<option value=""></option>'); 

    var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getCounty'));?>';

    $.post(strURL,{"district_id":id},function(data){  
      
        if(data) 
        { 
            $('#county_id').html(data);
            var county_id='';
            <?php if(isset($this->request->data['Prisoner']['county_id'])){?>
              county_id = '<?php echo $this->request->data['Prisoner']['county_id'];?>';
                $('#county_id').val(county_id); 
                showsubcounty(county_id);
            <?php }?>
        }
        else
        {
            alert("Error...");  
        }
    });
    $('#county_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
    $('#sub_county_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
    $('#parish_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    }); 
    $('#village_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
  }
  function showsubcounty(id)
  {
    $('#sub_county_id').html('<option value=""></option>'); 
    $('#parish_id').html('<option value=""></option>'); 
    $('#village_id').html('<option value=""></option>');   
               
          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getSubCounty'));?>';
      
          $.post(strURL,{"county_id":id},function(data){  
              
              if(data) { 
                  $('#sub_county_id').html(data); 
                  var sub_county_id='';
                  <?php if(isset($this->request->data['Prisoner']['sub_county_id'])){?>
                      sub_county_id = '<?php echo $this->request->data['Prisoner']['sub_county_id'];?>';
                      $('#sub_county_id').val(sub_county_id); 
                       showParish(sub_county_id);
                  <?php }?>
              }
              else
              {
                  alert("Error...");  
              }
          });

    $('#sub_county_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
    $('#parish_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    }); 
    $('#village_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
  }
function showParish(id){
      
    $('#parish_id').html('<option value=""></option>'); 
    $('#village_id').html('<option value=""></option>'); 

          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getParish'));?>';
      
          $.post(strURL,{"sub_county_id":id},function(data){  
              
              if(data) { 
                  $('#parish_id').html(data); 
                  var parish_id='';
                  <?php if(isset($this->request->data['Prisoner']['parish_id'])){?>
                      parish_id = '<?php echo $this->request->data['Prisoner']['parish_id'];?>';
                      $('#parish_id').val(parish_id); 
                  <?php }?>
              }
              else
              {
                  alert("Error...");  
              }
          });

    $('#parish_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    }); 
    $('#village_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
  }
  function showVillage(id){
      
    $('#village_id').html('<option value=""></option>'); 

          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getVillageList'));?>';
      
          $.post(strURL,{"parish_id":id},function(data){  
              
              if(data) { 
                  $('#village_id').html(data); 
                  var village_id='';
                  <?php if(isset($this->request->data['Prisoner']['village_id'])){?>
                      village_id = '<?php echo $this->request->data['Prisoner']['village_id'];?>';
                      $('#village_id').val(village_id); 
                  <?php }?>
              }
              else
              {
                  alert("Error...");  
              }
          });
     
    $('#village_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    }); 
  }

  ///////////////////////////////prisoner kin detail//////////////////////////////////////
  function showcounty_kin(id){
      
    $('#kin_county_id').html("<option value=''></option>");
    $('#gambolola').html('<option value=""></option>'); 
    $('#kin_parish_id').html('<option value=""></option>'); 
    //$('#village_name').html('<option value=""></option>');

          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getCounty'));?>';
      
          $.post(strURL,{"district_id":id},function(data){  
              
              if(data) { 
                  $('#kin_county_id').html(data);

                  var county_id='';
                  <?php if(isset($this->request->data['PrisonerKinDetail']['county_id'])){?>
                      county_id = '<?php echo $this->request->data['PrisonerKinDetail']['county_id'];?>';
                      $('#kin_county_id').val(county_id); 
                      showsubcounty_kin(county_id);
                  <?php }?>
                  
              }
              else
              {
                  alert("Error...");  
              }
          });
    $('#kin_county_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
    $('#gambolola').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
    $('#kin_parish_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    }); 
    // $('#village_name').select2({
    //     placeholder: "-- Select --",
    //     allowClear: true
    // }); 
  }
  function showsubcounty_kin(id){
      
    $('#gambolola').html('<option value=""></option>'); 
    $('#kin_parish_id').html('<option value=""></option>'); 
    //$('#village_name').html('<option value=""></option>');

          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getSubCounty'));?>';
      
          $.post(strURL,{"county_id":id},function(data){  
              
              if(data) { 
                  $('#gombolola').html(data); 
                  var sub_county_id='';
                  <?php if(isset($this->request->data['PrisonerKinDetail']['gombolola'])){?>
                      sub_county_id = '<?php echo $this->request->data['PrisonerKinDetail']['gombolola'];?>';
                      $('#gombolola').val(sub_county_id); 
                       showParish_kin(sub_county_id);
                  <?php }?>
              }
              else
              {
                  alert("Error...");  
              }
          });
    $('#gombolola').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
    $('#kin_parish_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    }); 
  }
function showParish_kin(id){
      
    $('#kin_parish_id').html('<option value=""></option>'); 
    //$('#village_name').html('<option value=""></option>');

          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getParish'));?>';
      
          $.post(strURL,{"sub_county_id":id},function(data){  
              
              if(data) { 
                  $('#kin_parish_id').html(data); 
                  var parish_id='';
                  <?php if(isset($this->request->data['PrisonerKinDetail']['parish_id'])){?>
                      parish_id = '<?php echo $this->request->data['PrisonerKinDetail']['parish_id'];?>';
                      $('#kin_parish_id').val(parish_id); 
                  <?php }?>
              }
              else
              {
                  alert("Error...");  
              }
          });
    $('#kin_parish_id').select2({
        placeholder: "-- Select --",
        allowClear: true
    });
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////
function displayPrisonerDetail()
{
    $( "#prisonerInfoDiv" ).toggle( "slow");
}

//validate sentence count on click on submit button
function validateSentenceCount(sformid)
{
    if($('#'+sformid+'_err_msg').length > 0)
    {
        $('#'+sformid+'_err_msg').remove();
    }
    if(sformid == 'sentence_capture_count')
    {
        if($('#sentence_of_capture').val() != '1'  && $('#sentence_of_capture').val() != '2')
        {
            return true;
        }
        else 
        {
            var years_val = $('#'+sformid+' input[name="data[PrisonerSentenceCapture][years]"]').val();
            var months_val = $('#'+sformid+' input[name="data[PrisonerSentenceCapture][months]"]').val();
            var days_val = $('#'+sformid+' input[name="data[PrisonerSentenceCapture][days]"]').val();

            if(years_val == '' && months_val == '' && days_val == '')
            {
                var scount_err_msg = '<div id="'+sformid+'_err_msg" style="text-align:center;"><font color="red">Please enter sentence count values.</font></div>';
                $('#'+sformid+'').prepend(scount_err_msg);
                return false;
            }
        }
    }
    // if(sformid == 'sentence_appeal_count')
    // {
    //     if($('#type_of_appeallant').val() != 'Convicted')
    //     {
    //         isReturn = true;
    //     }
    // }
    
    
    
    // var no_of_scount = parseInt($('#'+sformid+' input[name*="years"]').length);
    // if(no_of_scount>0)
    // {
    //     var serr = 0;
    //     for(i=0; i<no_of_scount; i++)
    //     {
    //         var years_val = $('#'+sformid+' input[name="data[PrisonerSentenceCapture]['+i+'][years]"]').val();
    //         var months_val = $('#'+sformid+' input[name="data[PrisonerSentenceCapture][months]"]').val();
    //         var days_val = $('#'+sformid+' input[name="data[PrisonerSentenceCapture][days]"]').val();
    //         if(years_val == '' && months_val == '' && days_val == '')
    //         {
    //             serr++;
    //         }
    //     }
    //     if(serr > 0)
    //     {
    //         var scount_err_msg = '<div id="'+sformid+'_err_msg" style="text-align:center;"><font color="red">Please enter sentence count values.</font></div>';
    //         $('#'+sformid+'').prepend(scount_err_msg);
    //         return false;
    //     }
    // }
    // alert(sformid);
}
$(document).on('click', '#prisone_id_edit', function() {

    var prisonerDetailId=$(this).attr("prisonerDetailId");
    $.ajax(
    {
        type: "POST",
        dataType: "json",
        url: "<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'prisnoriddetailedit'));?>",
        data: {
            prisonerDetailId: prisonerDetailId,
           
        },
        cache: true,
        beforeSend: function()
        {  
          //$('tbody').html('');
        },
        error: function ( jqXHR, textStatus, errorThrown) {
             alert("errorThrown: " + errorThrown + " textStatus:" + textStatus);
        },
        success: function (data) {

          $('#id_name option[value='+data.id_name+']').attr('selected','selected');
          //$("#id_name").val(data.id_name);
          $("#PrisonerIdDetailId").val(data.id);
          $("#id_number").val(data.id_number);
          $("#id").val(data.id);
          location.reload();
        },
        
    });
});

// $('#magisterial_id').on('change', function(e){
//     var url = '<?php //echo $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial')); ?>';
//     $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
//         $('#court_id').html(res);
//         $('#court_id').select2('val', '');
//         $('#court_level').val('');
//         $('#presiding_judge_id').html('<option value="">--Select Judge--</option>');
//         $('#presiding_judge_id').select2('val', '');
//     });
// });

// $('#court_id').on('change', function(e){
//     var url = '<?php //echo $this->Html->url(array('controller'=>'courtattendances','action'=>'getJudgeByCourt')); ?>';
//     $.post(url, {'court_id':$('#court_id').val()}, function(res){
//         $('#presiding_judge_id').html(res);
//         $('#presiding_judge_id').select2('val', '');
//     });
// });

$(document).on('change', '#country_id', function() {
    var country_id=$(this).val();
    
    $('#nationality_name').val('');
    if(country_id=='other'){
        $('#other_district').show();
        $('#s2id_district_id').hide();
        $('#other_country').show();   
    }
    if(country_id != 'other')
    {
        $.ajax(
        {
            type: "POST",
            dataType: "html",
            url: "<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'getNationName'));?>",
            data: {
                country_id: country_id,
            },
            cache: true,
            beforeSend: function()
            {  
              //$('tbody').html('');
            },
            error: function ( jqXHR, textStatus, errorThrown) {
                 alert("errorThrown: " + errorThrown + " textStatus:" + textStatus);
            },
            success: function (data) {
              $("#nationality_name").val(data);
            },
        });
        $("#nationality_name").attr('readonly','readonly');
    }
    else 
    {
        $("#nationality_name").removeAttr('readonly');
    }
});  
//If continent selected 
if($('#continent_id').val() != '')
{
    $('#country_id').select2('val', '');
    var cnid = '';
    <?php 
    if(isset($this->request->data['Prisoner']['continent_id']))
    {
        ?>
        cnid = '<?php echo $this->request->data['Prisoner']['continent_id'];?>';
        <?php 
    }
    ?>
    var current_cnid = $('#continent_id').val();
    if(cnid != current_cnid)
    { 
        showCountries($('#continent_id').val());
    }
}
//check if is dual citizen is clicked
if ($('#PrisonerIsDualCitizen1:checked').val() == 1)
{ 
    $('#nationality_name2').show();
}
function dissplayStatusOfWomen(gender_id)
{
    var male = "<?php echo Configure::read('GENDER_MALE');?>";
    var female = "<?php echo Configure::read('GENDER_FEMALE');?>";
    if(gender_id == female)
    {
        $('#status_of_women_div').show();
    }
    else 
    {
        $('#status_of_women_div').hide();
    }
}
$(document).ready(function(){

    $('#x3').click(function() {
        $('#repatriation_order').val();
        $("#img_prev3").attr("src",'');
        $('#img_prev3').hide();
        $("#x3").hide();  
        $('span.filename').html('');
        $('#prevImage3').show();
        $('#is_photo3').val('');
    });

    
  $("#x1").click(function() {
    $('#left_photo').val("");
    $("#img_prev1").attr("src",'');
    $('#img_prev1').hide();
   
    
    $("#x1").hide();  
    $('span.filename').html('');
    $('#prevImage1').show();
    $('#is_photo1').val('');
  });
  $("#x").click(function() {
    $('#photo').val("");
    $("#img_prev").attr("src",'');
    $('#img_prev').hide();
    $("#x").hide();  
    $('span.filename').html('');
    $('#prevImage').show();
    $('#is_photo').val('');
  });
  $("#x2").click(function() {
    $('#right_photo').val("");
    $("#img_prev2").attr("src",'');
    $('#img_prev2').hide();
    $("#x2").hide();  
    $('span.filename').html('');
    $('#prevImage2').show();
    $('#is_photo2').val('');
  });

    // $('#admission_offence_category_id').select2({
    //     placeholder: "Select Offence Category",
    //     allowClear: true
    // });
    // $('#admission_offence_id').select2({
    //     placeholder: "Select Offence",
    //     allowClear: false
    // });
    // $('#admission_section_of_law_id').select2({
    //     placeholder: "Select Section Of Law",
    //     allowClear: false
    // });

    // $('#sentence_offence_category_id').select2({
    //     placeholder: "Select Offence Category",
    //     allowClear: true
    // });
    // $('#sentence_offence_id').select2({
    //     placeholder: "Select Offence",
    //     allowClear: false
    // });

    // $('#sentence_section_of_law_id').select2({
    //     placeholder: "Select Section Of Law",
    //     allowClear: true
    // });

    

    //get date of conviction 
    $('.date_of_commital').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true,
        endDate: new Date(),
    }).on('changeDate', function (ev) {
         $(this).datepicker('hide');
         $(this).blur();
         //console.log(this.value);
         getSentenceDate(this.value);
    });

    // $('.date_of_commital2').datepicker({
    //     format: 'dd-mm-yyyy',
    //     autoclose:true,
    //     endDate: new Date(),
    // }).on('changeDate', function (ev) {
    //      $(this).datepicker('hide');
    //      $(this).blur();
    //      //console.log(this.value);
    //      getSentenceDate2(this.value);
    // });

    $('.sentence_appeal_date_of_confirmation').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true,
        endDate: new Date(),
    }).on('changeDate', function (ev) {
         $(this).datepicker('hide');
         $(this).blur();
         //var appeal_sentence_date_of_conviction = $( "#appeal_sentence_date_of_conviction" ).val();
         //alert(appeal_sentence_date_of_conviction);
    });

    

    //if other country selected 
    if($('#country_id').val() == 'other')
    {
        $('#other_district').show();
        $('#s2id_district_id').hide();
        $('#other_country').show();   
    }
    else 
    {
        //showDistricts($('#country_id').val());
        $('#other_district').hide();
        $('#s2id_district_id').show();
        $('#other_country').hide();  
    }
    //if other tribe selected 
    if($('#tribe_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_tribe]" class="form-control span11" placeholder="Tribe" id="other_tribe" style="" type="text" required></div>';
        $( other_field ).insertAfter('#tribe_id');
    }
    //if other occupation selected 
    if($('#occupation_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_occupation]" class="form-control span11" placeholder="Occupation" id="other_occupation" style="" type="text" required></div>';
        $( other_field ).insertAfter('#occupation_id');
    } 
    //if other level_of_education selected 
    if($('#level_of_education_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_level_of_education]" class="form-control span11" placeholder="Level Of Education" id="other_level_of_education" style="" type="text" required></div>';
        $( other_field ).insertAfter('#level_of_education_id');
    } 
    //if other skill selected 
    if($('#skill_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_skill]" class="form-control span11" placeholder="Skill" id="other_skill" style="" type="text" required></div>';
        $( other_field ).insertAfter('#skill_id');
    } 
    //if other ug_force selected 
    if($('#ug_force_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_ug_force]" class="form-control span11" placeholder="Ug Force" id="other_ug_force" style="" type="text" required></div>';
        $( other_field ).insertAfter('#ug_force_id');
    } 
    //if other apparent_religion selected 
    if($('#apparent_religion_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_apparent_religion]" class="form-control span11" placeholder="Religion" id="other_apparent_religion" style="" type="text" required></div>';
        $( other_field ).insertAfter('#apparent_religion_id');
    } 
    //if other marital_status selected 
    if($('#marital_status_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_marital_status]" class="form-control span11" placeholder="Marital Status" id="other_marital_status" style="" type="text" required></div>';
        $( other_field ).insertAfter('#marital_status_id');
    } 
    //if other marital_status selected 
    if($('#status_of_women_id').val() == 'other')
    {
        var other_field = '<div><input name="data[Prisoner][other_status_of_women]" class="form-control span11" placeholder="Status Of Women" id="other_status_of_women" style="" type="text" required></div>';
        $( other_field ).insertAfter('#status_of_women_id');
    } 

    //display women status if women 
    var gender_id = $("input[name='data[Prisoner][gender_id]']:checked").val();
    dissplayStatusOfWomen(gender_id);
    //display select second nationality 
    var is_dual_citizen = $("input[name='data[Prisoner][is_dual_citizen]']:checked").val();
    showNationality2(is_dual_citizen);
    var editPrisoner = '<?php echo $editPrisoner;?>';
    if(parseInt(editPrisoner) == 0)
    {
        $('#PrisonerEditForm input').attr("disabled", "disabled");
        $('#PrisonerEditForm textarea').attr("disabled", "disabled");
        //$('#PrisonerEditForm  button').hide();
        $('#PrisonerEditForm  #prisonerInfo').show();
        $('#PrisonerEditForm  #uniform-photo').remove();
        $('#PrisonerEditForm  select').attr("disabled", "disabled");
        //$('#PrisonerEditForm  select').select2().enable(false);
        //
        
        $('#personalDetailResetBtn').hide();

        $('#PrisonerSentenceEditForm input').attr("disabled", "disabled");
        $('#PrisonerSentenceEditForm textarea').attr("disabled", "disabled");
        $('#PrisonerSentenceEditForm  button').hide();
        $('#PrisonerSentenceEditForm  #prisonerInfo').show();
        $('#PrisonerSentenceEditForm  #uniform-photo').remove();
        $('#PrisonerSentenceEditForm  select').attr("disabled", "disabled");
        //$('#PrisonerSentenceEditForm  select').select2().enable(false);
        //
        <?php 
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            ?>
            $('#PrisonerEditForm  #PrisonerId').removeAttr("disabled");
            $('#PrisonerEditForm  #classification_id').removeAttr("disabled");
            $('#PrisonerSentenceEditForm  #personalDetailSaveBtn').show();
            <?php 
        }
        else {?>
            $('#personalDetailSaveBtn').hide();
        <?php }?>
    }
    var isAdd = '<?php echo $isAdd;?>';
    if(isAdd == 0 && parseInt(editPrisoner) == 0)
    {
        $('input').attr("disabled", "disabled");
        $('textarea').attr("disabled", "disabled");
        $('select').attr("disabled", "disabled");
        $('button').hide();
        $('#prisonerInfo').show();
        $('#PrisonerIdDetailEditForm').hide();
        $('#PrisonerKinDetailEditForm').hide();
        $('#PrisonerChildDetailEditForm').hide();
        $('#PrisonerSpecialNeedEditForm').hide();
        $('#PrisonerSentenceCaptureEditForm').hide();
    }
    //if status approved and prisoner type remand open sentence fields of admission 
    <?php 
    if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND') && $this->data['Prisoner']['status'] == 'Approved')
    {
        if(($login_user_type_id == Configure::read('RECEPTIONIST_USERTYPE') && $this->data['Prisoner']['type_change_status'] == 'Draft') || ($login_user_type_id == Configure::read('PRINCIPALOFFICER_USERTYPE') && $this->data['Prisoner']['type_change_status'] == 'Saved') || ($login_user_type_id == Configure::read('OFFICERINCHARGE_USERTYPE') && $this->data['Prisoner']['type_change_status'] == 'Reviewed'))
        {?>
            $('#PrisonerSentencePrisonerId').removeAttr('disabled');
            $('#PrisonerSentenceSentenceFrom').removeAttr('disabled');
            $('#PrisonerSentenceId').removeAttr('disabled');
            $('#date_of_conviction_admission').removeAttr('disabled');
            $('#date_of_sentence_admission').removeAttr('disabled');
            $('#track_proceeding').removeAttr('disabled');
            $('#sentence_of').removeAttr('disabled');
            $('#admission_sentence_count input').removeAttr('disabled');
            $('#admission_sentence_count select').removeAttr('disabled');
            $('#admissionSentence_fine_with_imprisonment input').removeAttr('disabled');
            $('#admissionSentence_receipt_number input').removeAttr('disabled');
            $('#admissionSaveBtn').show();
        <?php }
        if($login_user_type_id == Configure::read('RECEPTIONIST_USERTYPE'))
        {
            ?>$('#admissionSaveBtn').html('Save & Forward');<?php 
        }
        if($login_user_type_id == Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            ?>$('#admissionSaveBtn').html('Review');<?php 
        }
        if($login_user_type_id == Configure::read('OFFICERINCHARGE_USERTYPE') && $this->data['Prisoner']['type_change_status'] == 'Reviewed')
        {
            ?>$('#admissionSaveBtn').html('Approve');<?php 
        }
    }
    ?>
    $('#assigned_ward_id').removeAttr("disabled");
    $('#wardSaveBtn').show();
    var editcontinent_id="<?php echo $this->data["Prisoner"]["continent_id"];?>"; 
    var editcountry_id="<?php echo $this->data["Prisoner"]["country_id"];?>"; 
    if(editcontinent_id==0){
        //$('#continent_id').select2('val', '1');    
        $("#continent_id option[value='1']").attr("selected","selected");
    }
    if(editcountry_id==0){
        //$('#country_id').select2('val', '1');
        $("#country_id option[value='1']").attr("selected","selected");
    }
    
    //set class 
    var prisoner_class  = '<?php echo $prisoner_class;?>';
    if(prisoner_class != '')
    {
        $('#class').select2('val', prisoner_class);
        $('#class2').select2('val', prisoner_class);
        $('#class3').select2('val', prisoner_class);
    }
});
$(document).on('change', '#continent_id', function(e){

        var continent_id=$(this).val();
        
            showCountries(continent_id);
            // $.ajax(
            //   {
            //       type: "POST",
            //       url: "<?php echo $this->Html->url(array('controller'=>'Prisoners','action'=>'countryList'));?>",
            //       data: {
            //           continent_id:continent_id,
                      
            //       },
            //       cache: true,
            //       beforeSend: function()
            //       {  
            //         //$('#delete'+countdata).html('Loading....');
            //       },
            //       success: function (data) {
                    
            //         $("#countrycont").html(data);
            //         $('#country_id').select2();
                   
            //       },
            //       error: function (errormessage) {
            //         alert(errormessage.responseText);
            //       }
            //   });
          

   });
$(function(){

    $('#date_of_birth').datepicker({
        onSelect: function(value, ui) {
            var prisoner_type_id = $('#prisoner_type_id').val(); 
            getPrisonerClass(prisoner_type_id, value);
            //getPrisonerAge(value);
        },
        maxDate: '+0d',
        yearRange: '1920:2010',
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
    });

$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param) 
});
$.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
    //validate personal details 
    $('#personalDetailSaveBtn').click(function(){

        setTimeout(
            function() {
                var isvalidForm = $('#prisonerInfoDiv').find('label.error').length;
                if(parseInt(isvalidForm) > 0)
                {
                    $('#prisonerInfoDiv').show();
                }
            },
            500);
    });

    //get confirmation of sentence value 
    // var confirmation_sentence = $("input[type=radio][name='data[PrisonerSentence][waiting_for_confirmation]']:checked").val();
    // alert(confirmation_sentence);
    // setConfirmation(confirmation_sentence);

    <?php if(!isset($this->data['PrisonerSentenceAppeal']['id']))
    {?>
    //get prisoner waiting confirmation of sentence value 
    var prisoner_waiting_confirmation = $("input[type=radio][name='data[PrisonerSentenceAppeal][prisoner_waiting_confirmation]']:checked").val();
    showDateOfConfirmation(prisoner_waiting_confirmation);
    <?php }?>

    //get appealed against sentence value 
    var appealed_against_sentence = $("input[type=radio][name='data[PrisonerSentenceAppeal][appealed_against_sentence]']:checked").val();
    setAppeal(appealed_against_sentence);

    //get appealed status 
    // var type_of_appeallant = $("input[type=select][name='data[PrisonerSentenceAppeal][type_of_appeallant]']:selected").val();
    // getAppellantTypeInfo(type_of_appeallant);

    var appeal_status = $("#appeal_status").val();
    getAppeledStatusInfo(appeal_status);

    var appeal_result = $("#appeal_result").val();
    getAppeledResultInfo(appeal_result);

    <?php if(isset($this->data['PrisonerSentenceAppeal']['id']) && !empty($this->data['PrisonerSentenceAppeal']['id']))
    {?>
        $('#new_date_of_confirmation_div').show();
    <?php }?>

    $("#PrisonerEditForm").validate({
     
      ignore: "",
            rules: {  
                
                'data[Prisoner][first_name]': {
                    required: true,
                },
                // 'data[Prisoner][last_name]': {
                //     required: true,
                // },
                // 'data[Prisoner][father_name]': {
                //     required: false,
                // },
                // 'data[Prisoner][mother_name]': {
                //     required: false,
                // },
                'data[Prisoner][continent_id]': {
                    required: true,
                },
                'data[Prisoner][nationality_name]': {
                    required: true,
                },
                'data[Prisoner][date_of_birth]': {
                    required: true,
                    datevalidateformat: true,
                    check_date_of_birth: true
                },
                'data[Prisoner][place_of_birth]': {
                    required: true,
                },
                'data[Prisoner][gender]': {
                    required: true,
                },
                'data[Prisoner][prisoner_type_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                // 'data[Prisoner][prisoner_sub_type_id]': {
                //     required: function(element){
                //         return $("#prisoner_type_id").val()=="<?php echo $remand_type;?>";
                //     }
                // },
                'data[Prisoner][continent_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][country_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][tribe_id]': {
                    required: function(element){
                        return $("#country_id").val()=="<?php echo $uganda_country_id;?>";
                    }
                },
                'data[Prisoner][photo]': {
                    extension: "png|jpg|jpeg|gif|PNG|JPG|JPEG|GIF",
                    filesize: 2000000,
                },
                'data[Prisoner][left_photo]': {
                    extension: "png|jpg|jpeg|gif|PNG|JPG|JPEG|GIF",
                    filesize: 2000000,
                },
                'data[Prisoner][right_photo]': {
                    extension: "png|jpg|jpeg|gif|PNG|JPG|JPEG|GIF",
                    filesize: 2000000,
                },
                'data[Prisoner][occupation_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][level_of_education_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][skill_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                // 'data[Prisoner][ug_force_id]': {
                //     required: true,
                //     valueNotEquals: "0"
                // },
                'data[Prisoner][apparent_religion_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][marital_status_id]': {
                    required: true,
                    valueNotEquals: "0"
                },
                'data[Prisoner][status_of_women_id]': {
                    required: function(element){
                        return $("#gender_id").val()=="<?php echo $gender_female;?>";
                    }
                },
                'data[Prisoner][permanent_address]': {
                    required: true,
                    loginRegex: true,
                    maxlength: 250
                },
                'data[Prisoner][marks]': {
                    loginRegex: true,
                    maxlength: 250
                },
                'data[Prisoner][deformities]': {
                    loginRegex: true,
                    maxlength: 250
                },
                'data[Prisoner][habits]': {
                    loginRegex: true,
                    maxlength: 250
                },
                'data[Prisoner][resident_address]': {
                    required: true,
                    loginRegex: true,
                    maxlength: 250
                },
            },
            messages: {
                'data[Prisoner][first_name]': {
                    required: "Please enter first name.",
                },
                // 'data[Prisoner][last_name]': {
                //     required: "Please enter surname.",
                // },
                // 'data[Prisoner][father_name]': {
                //     required: "Please enter father's name.",
                // },
                // 'data[Prisoner][mother_name]': {
                //     required: "Please enter mother's name.",
                // },
                'data[Prisoner][continent_id]': {
                    required: "Please select continent.",
                },
                'data[Prisoner][nationality_name]': {
                    required: "Please enter nationality.",
                },
                'data[Prisoner][date_of_birth]': {
                    required: "Please choose date of birth.",
                    datevalidateformat: "Wrong Date Format"
                },
                'data[Prisoner][place_of_birth]': {
                    required: "Please enter place of birth.",
                },
                'data[Prisoner][gender]': {
                    required: "Please select gender.",
                },
                'data[Prisoner][prisoner_type_id]': {
                    required: "Please select prisoner type.",
                    valueNotEquals: "Please select prisoner type.",
                },
                // 'data[Prisoner][prisoner_sub_type_id]': {
                //     required: "Please select prisoner subtype."
                // },
                'data[Prisoner][continent_id]': {
                    required: "Please select continent.",
                    valueNotEquals: "Please select continent.",
                },
                'data[Prisoner][country_id]': {
                    required: "Please select country.",
                    valueNotEquals: "Please select country.",
                },
                'data[Prisoner][tribe_id]': {
                    required: "Please select tribe."
                },
                'data[Prisoner][photo]': {
                    extension: "Please upload (jpg,jpeg,png,gif) type photo",
                    filesize:"File size must be 2MB."
                },
                'data[Prisoner][left_photo]': {
                    extension: "Please upload (jpg,jpeg,png,gif) type left side photo",
                    filesize:"File size must be 2MB.",
                },
                'data[Prisoner][right_photo]': {
                    extension: "Please upload (jpg,jpeg,png,gif) type right side photo",
                    filesize:"File size must be 2MB.",
                },
                'data[Prisoner][occupation_id]': {
                    required: "Please select occupation.",
                    valueNotEquals: "Please select occupation.",
                },
                'data[Prisoner][level_of_education_id]': {
                    required: "Please select level of education.",
                    valueNotEquals: "Please select level of education.",
                },
                'data[Prisoner][skill_id]': {
                    required: "Please select skill.",
                    valueNotEquals: "Please select skill.",
                },
                // 'data[Prisoner][ug_force_id]': {
                //     required: "Please select ug force.",
                //     valueNotEquals: "Please select ug force.",
                // },
                'data[Prisoner][apparent_religion_id]': {
                    required: "Please select religion.",
                    valueNotEquals: "Please select religion.",
                },
                'data[Prisoner][marital_status_id]': {
                    required: "Please select marital status.",
                    valueNotEquals: "Please select marital status.",
                },
                'data[Prisoner][status_of_women_id]': {
                    required: "Please select status of women."
                },
                'data[Prisoner][permanent_address]': {
                    required: "Please enter permanent address.",
                    loginRegex: "Permanent address must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters.",
                },
                'data[Prisoner][marks]': {
                    loginRegex: "Mark must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters.",
                },
                'data[Prisoner][deformities]': {
                    loginRegex: "Deformities must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters.",
                },
                'data[Prisoner][habits]': {
                    loginRegex: "Habits must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters.",
                },
                'data[Prisoner][resident_address]': {
                    required: "Please enter Resident Address.",
                    loginRegex: "Resident Address must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters.",
                },
            },
               
    });

//validate prisoner id details
    $("#PrisonerIdDetailEditForm").validate({
         
      ignore: "",
            rules: {  
                'data[PrisonerIdDetail][id_name]': {
                    
                    valueNotEquals: "0"
                },
                'data[PrisonerIdDetail][id_number]': {
                    required: true,
                    alphanumeric: true,
                    maxlength: 16
                },
                
                
            },
            messages: {
                'data[PrisonerIdDetail][id_name]': {
                    //required: "Please select id name.",
                    required: {
                        message: "Please select id name."
                    },
                },
                'data[PrisonerIdDetail][id_number]': {
                    required: "Please enter id number.",
                    alphanumeric: "Please enter only letters and numbers",
                    maxlength: "Please enter maximum 16 characters"
                },
                
            },
               
    });

    $("#PrisonerWardEditForm").validate({
     
      ignore: "",
            rules: {  
                
                'data[PrisonerWard][assigned_ward_id]': {
                    required: true,
                }
            },
            messages: {
                'data[PrisonerWard][assigned_ward_id]': {
                    required: "Please select ward.",
                }
            }
    });

    $("#PrisonerKinDetailEditForm").validate({
         
          ignore: "",
                rules: {  
                    'data[PrisonerKinDetail][first_name]': {
                        required: true,
                    },
                    // 'data[PrisonerKinDetail][last_name]': {
                    //     required: true,
                    // },
                    'data[PrisonerKinDetail][relationship]': {
                        required: true,
                        valueNotEquals: "0"
                    },
                    'data[PrisonerKinDetail][national_id_no]': {
                        alphanumeric: true,
                    },
                    'data[PrisonerKinDetail][phone_no]': {
                        required: false,
                    },
                    'data[PrisonerKinDetail][physical_address]': {
                        required: false,
                        rangelength: [1, 250],
                        loginRegex: true,
                    },
                    // 'data[PrisonerKinDetail][village]': {
                    //     required: true,
                    // },
                    // 'data[PrisonerKinDetail][parish]': {
                    //     required: true,
                    // },
                    
                },
                messages: {
                    'data[PrisonerKinDetail][first_name]': {
                        required: "Please enter first name.",
                    },
                    // 'data[PrisonerKinDetail][last_name]': {
                    //     required: "Please enter last name.",
                    // },
                    'data[PrisonerKinDetail][relationship]': {
                        required: "Please select relationship.",
                        valueNotEquals: "Please select relationship."
                    },
                    'data[PrisonerKinDetail][national_id_no]': {
                        alphanumeric: "Please enter only letters and numbers"
                    },
                    'data[PrisonerKinDetail][phone_no]': {
                        required: "Please enter phone number.",
                    },
                    'data[PrisonerKinDetail][physical_address]': {
                        required: "Please enter physical address.",
                        rangelength: function(range, input) {
                                    return [
                                        'You are only allowed between ',
                                        range[0],
                                        'and ',
                                        range[1],
                                        ' You have typed ',
                                        $(input).val().length,
                                        ' characters'                                
                                    ].join('');

                                },
                        loginRegex: "Physical address must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    },
                    // 'data[PrisonerKinDetail][village]': {
                    //     required: "Please enter village.",
                    // },
                    // 'data[PrisonerKinDetail][parish]': {
                    //     required: "Please enter parish.",
                    // },
                },
                   
    });

    $("#PrisonerChildDetailEditForm").validate({
         
          ignore: "",
                rules: {  
                    'data[PrisonerChildDetail][name]': {
                        required: true,
                    },
                    'data[PrisonerChildDetail][father_name]': {
                        required: false,
                    },
                    'data[PrisonerChildDetail][dob]': {
                        required: true,
                        datevalidateformat: true,
                    },
                    'data[PrisonerChildDetail][birth_place]': {
                        required: true,
                    
                    },
                    'data[PrisonerChildDetail][district_of_birth]': {
                        valueNotEquals: "0",
                    
                    },
                    'data[PrisonerChildDetail][gender_id]': {
                        required: true,
                    
                    },
                    
                    'data[PrisonerChildDetail][child_medical_document]': {
                        <?php 
                        if(!isset($this->request->data["PrisonerChildDetail"]["child_medical_document"]))
                        {?>
                        required: false,
                        <?php }?>
                        extension: "docx|doc|pdf|png|jpg|jpeg|PNG|JPG|JPEG|DOC|PDF|DOCX",
                        filesize: 2000000,
                        
                    
                    },
                    
                    'data[PrisonerChildDetail][child_photo]': {
                        extension: "png|jpg|jpeg|PNG|JPG|JPEG",
                        filesize: 2000000,
                    
                    },
                    
                },
                messages: {
                    'data[PrisonerChildDetail][name]': {
                        required: "Please enter name of child.",
                    },
                    'data[PrisonerChildDetail][father_name]': {
                        required: "Please enter father's name.",
                    },
                    'data[PrisonerChildDetail][dob]': {
                        required: "Please enter date of birth",
                        datevalidateformat: "Wrong Date Format"
                    },
                    'data[PrisonerChildDetail][birth_place]': {
                        required: "Please enter place of birth.",
                    },
                    'data[PrisonerChildDetail][district_of_birth]': {
                        required: "Please select district",
                    },
                    'data[PrisonerChildDetail][gender_id]': {
                        required: "Please choose gender.",
                    },
                    'data[PrisonerChildDetail][child_medical_document]': {
                        required: "Please browse child medical report.",
                        extension: "Please upload (doc,pdf,jpg,jpeg,png) type photo",
                        filesize:"File size must be 2MB.",
                    },
                    'data[PrisonerChildDetail][child_photo]': {
                        extension: "Please upload (doc,pdf,jpg,jpeg,png) type photo",
                        filesize:"File size must be 2MB.",
                    },
                },
                   
    });
    //validate special needs 
    $("#PrisonerSpecialNeedEditForm").validate({
     
      ignore: "",
            rules: {  
                // 'data[PrisonerSpecialNeed][type_of_disability]': {
                //     required: true,
                // },
                'data[PrisonerSpecialNeed][special_condition_id]': {
                    required: true,
                    valueNotEquals: "0",
                },
                // 'data[PrisonerSpecialNeed][type_of_disability]': {
                //     required: true,
                //     valueNotEquals: "0",
                // },
                
            },
            messages: {
                // 'data[PrisonerSpecialNeed][type_of_disability]': {
                //     required: "Please select type of disability.",
                // },
                'data[PrisonerSpecialNeed][special_condition_id]': {
                    required: "Please select Type of Disability",
                    valueNotEquals: "Please select Type of Disability"
                },
                // 'data[PrisonerSpecialNeed][type_of_disability]': {
                //     required: "Please select subcategory of Disability",
                //     valueNotEquals: "Please select subcategory of Disability"
                // },
            },
               
    });

    //validate prisoner admission 


// $('#PrisonerAdmissionEditForm').validate({

//         ignore: "",
//             rules: {  
//                 'data[PrisonerAdmission][PrisonerOffence][0][offence]': {
//                     required: true
//                 },
//                 // 'data[PrisonerAdmission][0][section_of_law][]': {
//                 //     required: true
//                 // },
//                 'data[PrisonerAdmission][court_file_no]': {
//                     required: function(element){
//                         return $("#courtlevel_id").val()!=8;
//                     },
//                     alphanumericsp: true,
//                 },
//                 'data[PrisonerAdmission][highcourt_file_no]': {
//                     required: function(element){
//                         return $("#courtlevel_id").val()==8;
//                     },
//                     alphanumericsp: true,
//                 },
//                 'data[PrisonerAdmission][case_file_no]': {
//                     required: true,
//                     alphanumericsp: true,
//                 },
//                 'data[PrisonerAdmission][date_of_warrant]':{
//                     required: true,
//                 },
//                 'data[PrisonerAdmission][crb_no]': {
//                     required: function(element){

//                         return ($("#courtlevel_id").val()==5 || $("#courtlevel_id").val()==6 || $("#courtlevel_id").val()==7);

//                         // if($("#courtlevel_id").val()==5 || $("#courtlevel_id").val()==6 || $("#courtlevel_id").val()==7)
//                         // {
//                         //     return true;
//                         // }
//                     },
//                     alphanumericsp: true,
//                 },
//                 'data[PrisonerAdmission][courtlevel_id]': {
//                     required: true,
//                     valueNotEquals: "0"
//                 },
//                 'data[PrisonerAdmission][court_id]': {
//                     required: true,
//                     valueNotEquals: "0"
//                 }
//             },
//             messages: {
//                 'data[PrisonerAdmission][PrisonerOffence][0][offence]': {
//                     required: "Please select offence."
//                 },
//                 // 'data[PrisonerAdmission][PrisonerOffence][0][section_of_law][]': {
//                 //     required: "Please select section of law.",
//                 // },
//                 'data[PrisonerAdmission][court_file_no]': {
//                     required: "Please enter court file no.",
//                     alphanumericsp: "Please enter only letters,numbers and special characters(-,/)",
//                 },
//                 'data[PrisonerAdmission][highcourt_file_no]': {
//                     required: "Please enter high court file no.",
//                     alphanumericsp: "Please enter only letters,numbers and special characters(-,/)",
//                 },
//                 'data[PrisonerAdmission][case_file_no]': {
//                     required: "Please enter case file no.",
//                     alphanumeric: "Please enter only letters and numbers"
//                 },
//                 'data[PrisonerAdmission][date_of_warrant]':{
//                     required: "Please select date of warrant."
//                 },
//                 'data[PrisonerAdmission][crb_no]': {
//                     required: "Please enter C.R.B no.",
//                     alphanumeric: "Please enter only letters and numbers"
//                 },
//                 'data[PrisonerAdmission][courtlevel_id]': {
//                     required: "Please select court category.",
//                     valueNotEquals: "Please select court category."
//                 },
//                 'data[PrisonerAdmission][court_id]': {
//                     required: "Please select court.",
//                     valueNotEquals: "Please select court."
//                 },
//             }
//         });

<?php 
if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
{?>
    //validate prisoner sentence capture
    $('#PrisonerSentenceCaptureEditForm').validate({

        ignore: "",
            rules: {  
                'data[PrisonerSentenceCapture][offence_id]': {
                    required: true
                },
                'data[PrisonerSentenceCapture][is_convicted][]': {
                    required: true,
                },
                // 'data[PrisonerSentenceCapture][class]': {
                //     valueNotEquals: "0"
                // },
                'data[PrisonerSentenceCapture][sentence_of]': {
                    required: function(element){

                        return $("#awaiting_sentence").val()=="2";
                    }
                },
                // 'data[PrisonerSentenceCapture][date_of_committal]': {
                //     required: true,
                //     datevalidateformat: true,
                //     greaterThanOrEqual: "#dateafter_18yrs_dob"
                // },
                
                'data[PrisonerSentenceCapture][date_of_conviction]': {
                    required: function(element){

                        return $("#awaiting_sentence").val()=="2";
                    },
                    greaterThanOrEqual: "#dateafter_18yrs_dob"
                },
                'data[PrisonerSentenceCapture][date_of_sentence]': {
                    required: function(element){

                        return $("#awaiting_sentence").val()=="2";
                    },
                    greaterThanOrEqual: "#date_of_conviction2"
                }
            },
            messages: {
                'data[PrisonerSentenceCapture][offence_id]': {
                    required: "Please select offence."
                },
                'data[PrisonerSentenceCapture][is_convicted][]': {
                    required: "Awaiting/Sentence Awarded.",
                },
                // 'data[PrisonerSentenceCapture][class]': {
                //     required: "Please select class.",
                // },
                'data[PrisonerSentenceCapture][sentence_of]': {
                    required: "Please select sentence of.",
                },
                // 'data[PrisonerSentenceCapture][date_of_committal]': {
                //     required: "Please select date of commital.",
                //     datevalidateformat: "",
                //     greaterThanOrEqual: "Should be 18years greater than prisoner date of birth.",
                // },
                
                'data[PrisonerSentenceCapture][date_of_conviction]': {
                    required: "Please select date of conviction.",
                    greaterThanOrEqual: "Should be 18years greater than prisoner date of birth."
                },
                'data[PrisonerSentenceCapture][date_of_sentence]': {
                    required: "Please select date of sentence.",
                    greaterThanOrEqual: "Should be greater than or equal to date of conviction."
                }
            },
        });

<?php }
else{?>
    //validate prisoner sentence capture
    $('#PrisonerSentenceCaptureEditForm').validate({

        ignore: "",
            rules: {  
                'data[PrisonerSentenceCapture][offence_id]': {
                    required: true
                },
                'data[PrisonerSentenceCapture][is_convicted]': {
                    required: true,
                },
                // 'data[PrisonerSentenceCapture][class]': {
                //     valueNotEquals: "0"
                // },
                'data[PrisonerSentenceCapture][sentence_of]': {
                    required: function(element){

                        return $("#awaiting_sentence").val()=="2";
                    }
                },
                'data[PrisonerSentenceCapture][date_of_conviction]': {
                    required: function(element){

                        return $("#awaiting_sentence").val()=="2";
                    },
                    greaterThanOrEqual: "#dateafter_18yrs_dob"
                },
                'data[PrisonerSentenceCapture][date_of_sentence]': {
                    required: function(element){

                        return $("#awaiting_sentence").val()=="2";
                    },
                    greaterThanOrEqual: "#date_of_conviction2"
                }
            },
            messages: {
                'data[PrisonerSentenceCapture][offence_id]': {
                    required: "Please select offence."
                },
                'data[PrisonerSentenceCapture][is_convicted]': {
                    required: "Awaiting/Sentence Awarded.",
                },
                // 'data[PrisonerSentenceCapture][class]': {
                //     required: "Please select class.",
                // },
                'data[PrisonerSentenceCapture][sentence_of]': {
                    required: "Please select sentence of.",
                },
                'data[PrisonerSentenceCapture][date_of_conviction]': {
                    required: "Please select date of conviction.",
                    greaterThanOrEqual: "Should be 18years greater than prisoner date of birth."
                },
                'data[PrisonerSentenceCapture][date_of_sentence]': {
                    required: "Please select date of sentence.",
                    greaterThanOrEqual: "Should be greater than or equal to date of conviction."
                }
            },
        });

<?php }?>

    //validate prisoner appeal sentence form  
    $("#PrisonerSentenceAppealEditForm").validate({
     
      ignore: "",
            // rules: {  
            //     'data[PrisonerSentenceAppeal][case_file_id]': {
            //         required: true
            //     }
            // },
            // messages: {
            //     'data[PrisonerSentenceAppeal][case_file_id]': {
            //         required: "Please select File No.",
            //     }
            // },
    });
    //validate prisoner recapture details
    $("#PrisonerRecaptureDetailEditForm").validate({
     
      ignore: "",
            rules: {  
                'data[PrisonerRecaptureDetail][escape_date]': {
                    required: true,
                },
                'data[PrisonerRecaptureDetail][recapture_date]': {
                    required: true,
                    datevalidateformat: true,
                    greaterThanOrEqual: "#escape_date"
                },
            },
            messages: {
                'data[PrisonerRecaptureDetail][escape_date]': {
                    required: "Please select Date of escape.",
                },
                'data[PrisonerRecaptureDetail][recapture_date]': {
                    required: "Please select Date of escape.",
                    datevalidateformat: "Wrong Date Format",
                    greaterThanOrEqual: "Recapture date must be greater than or equal to escape date."
                },
            },
    });
    //validate prisoner bail details
    $("#PrisonerBailDetailEditForm").validate({
     
      ignore: "",
            rules: { 
                'data[PrisonerBailDetail][bail_cancel_date]': {
                    greaterThanOrEqual: function(element){
                        if($("#bail_cancel_date").val()!="")
                            return "#bail_start_date";
                    }
                },
            },
            messages: {
                'data[PrisonerBailDetail][bail_cancel_date]': {
                    greaterThanOrEqual: "Bail cancel date date must be greater than or equal to bail start date."
                }
            },
    });
    
    //alert(getChildAge('01-07-2018'));
});

function getChildAge(dateString) {

    var birth_data = dateString.split('-');
    var dobDay = birth_data[0];
    var dobMonth = birth_data[1];
    var dobYear = birth_data[2];

    var bthDate, curDate, days;
    var ageYears, ageMonths, ageDays;
    bthDate = new Date(dobYear, dobMonth-1, dobDay);
    curDate = new Date();
    if (bthDate>curDate) return;
    days = Math.floor((curDate-bthDate)/(1000*60*60*24));
    ageYears = Math.floor(days/365);
    ageMonths = Math.floor((days%365)/31);
    ageDays = days - (ageYears*365) - (ageMonths*31);

    // if(ageYears >= 2)
    // {
    //     $('#child_dob').val('');
    //     $('#child_age').val('');
    //     dynamicAlertBox('Error','child age should not be greater than 2 years' );
    // }
    // else 
    // {
        var agedata = '';
        if (ageYears>0) {
            agedata += ageYears+" year";
            if (ageYears>1) agedata += "s"; 
            if ((ageMonths>0)||(ageDays>0)) agedata +=", ";
        }
        if (ageMonths>0) 
        {
            agedata += ageMonths+" month";
            if (ageMonths>1) agedata += "s"; 
            if (ageDays>0) agedata += ", "; 
        }
        if (ageDays>0) 
        {
            agedata += ageDays+" day";
            if (ageDays>1) agedata += "s"; 
        }
        $('#child_age').val(agedata);
    //}
}
function checkSubsistenceAllowance(val)
    {
        var paid_days = '';
        if(val != '')
        {
            //var next_date_after_6months = $('#next_date_after_6months').val();
            var rate = $('#rate_per_day').val();
            var max_subsistence_allowance = parseInt(rate)*parseInt(180);
            if(val > max_subsistence_allowance)
            {
                dynamicAlertBox('Subsistence allowance should not greater than '+max_subsistence_allowance+' USh');
                $('#subsistence_allowance').val('');
            }
            else
            {
                paid_days = parseInt(val)/parseInt(rate);
                paid_days = Math.floor(paid_days);
            }
        }
        $('#no_of_days_for_amount').val(paid_days);
        // var admission_date = $('#admission_date').val();
        // var current_date = new Date(admission_date);
        var current_date = new Date();
        current_date.setDate(current_date.getDate() + paid_days);
        var dd = current_date.getDate();
        var mm = current_date.getMonth() + 1;
        var y = current_date.getFullYear();
        var next_payment_date = dd + '-' + mm + '-' + y;
        
        $('#next_payment_date').val(next_payment_date);
        $('#date_of_release').val(next_payment_date);
    }
    function checkDebtorAmountReceved(val)
    {
        var value_of_debt = parseFloat($('#value_of_debt').val());
        
        if(parseFloat(val) > value_of_debt)
        {
            dynamicAlertBox('Amount recieved should not greater than value of debt: '+value_of_debt+ ' USh');
                $('#amount_recieved').val('');
        }
    }
</script>
<?php

$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
$ajaxUrl_id_proof = $this->Html->url(array('controller'=>'prisoners','action'=>'idProofAjax'));
$deleteIdProofUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteIdProof'));
$getPrisonerIdNameListAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getIdProofNameList'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {

        showPrisonerFiles();

         showDataIdProof();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#id_proof').on('click', function(e) {
            showDataIdProof();
            e.preventDefault();
       });
    }); 
    
    function showDataIdProof(){
        var url = '".$ajaxUrl_id_proof."';
        url = url + '/prisoner_id:'+'".$prisoner_uuid."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#personalid_listview').html(res);
            }
        });
    }

    //delete id proof 
    function deleteIdProof(paramId){
        if(paramId){
            //if(confirm('Are you sure to delete?')){
                var url = '".$deleteIdProofUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataIdProof();
                        showPrisonerIdNameList()
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            //}
        }
    }

    //get prisoner id proof name list
    function showPrisonerIdNameList(){
        var url = '".$getPrisonerIdNameListAjaxUrl."';
        $.post(url, {'prisoner_id':".$prisoner_id."}, function(res) {
            if (res) {
                $('#id_name').html(res);
            }
        });
    }

    function enableIdNumberField(id_name)
    { 
        if(parseInt(id_name) > 0)
        {
            $('#id_number').val('');
            $('#id_number').removeAttr('readonly','');
            $('#id_number').focus();
        }
        else 
        { 
            $('#id_number').attr('readonly','readonly');
        }
    }

",array('inline'=>false));

//get kin details list
$ajaxUrl_kindetail = $this->Html->url(array('controller'=>'prisoners','action'=>'kinDetailAjax'));
$deleteKinUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteKin'));
echo $this->Html->scriptBlock("
      
    var tabs;
    jQuery(function($) {
         showDataKin();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
        $('#id_name').on('change', function(e) {
            var id_name = $('#id_name').val();
            enableIdNumberField(id_name);
        });  
       $('#kin_details_tab').on('click', function(e) {
            showDataKin();
            e.preventDefault();
       });
    }); 
    
    function showDataKin(){
        var url = '".$ajaxUrl_kindetail."';
        url = url + '/prisoner_id:'+'".$prisoner_uuid."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#prisonerkindata_listview').html(res);
            }
        });
    }

    //delete kin 
    function deleteKin(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteKinUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataKin();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

",array('inline'=>false));

//get child details list
$ajaxUrl_childdetail = $this->Html->url(array('controller'=>'prisoners','action'=>'childDetailAjax'));
$deleteChildUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteChild'));
$deleteSpecialNeedUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteSpecialNeed'));
$deleteSentenceUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteSentence'));
$deleteOffenceCountUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteOffenceCount'));
$deleteRecaptureUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteRecapture'));
$deleteBailUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deleteBail'));

echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
         showDataChild();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#child_details_tab').on('click', function(e) {
            showDataChild();
            e.preventDefault();
       });
    }); 
    
    function showDataChild(){
        var url = '".$ajaxUrl_childdetail."';
        url = url + '/prisoner_id:'+'".$prisoner_uuid."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#prisonerchilddata_listview').html(res);
            }
        });
    }

    //delete child 
    function deleteChild(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteChildUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataChild();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    //delete special need 
    function deleteSpecialNeed(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteSpecialNeedUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataSpecialNeeds();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    //delete offence
    function deleteSentence(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteSentenceUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataSentenceCapture();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    //delete offence count
    function deleteOffenceCount(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteOffenceCountUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataAppeals();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    //delete recapture details
    function deleteRecapture(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteRecaptureUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataRecapture();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

    //delete bail details
    function deleteBail(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteBailUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataBail();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    

",array('inline'=>false));
//get prisoner's special needs list
$ajaxUrl_specialneeds = $this->Html->url(array('controller'=>'prisoners','action'=>'specialNeedAjax'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
         showDataSpecialNeeds();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#special_needs_tab').on('click', function(e) {
            showDataChild();
            e.preventDefault();
       });
    }); 
    
    function showDataSpecialNeeds(){
        var url = '".$ajaxUrl_specialneeds."';
        url = url + '/prisoner_id:'+'".$prisoner_uuid."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#specialneed_listview').html(res);
            }
        });
    }
",array('inline'=>false));
//get prisoner's offence detail list
$ajaxUrl_sentence = $this->Html->url(array('controller'=>'prisoners','action'=>'sentenceCaptureAjax'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
         showDataSentenceCapture();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#sentence_capture_tab').on('click', function(e) {
            showDataSentenceCapture();
            e.preventDefault();
       });
    }); 
    
    function showDataSentenceCapture(){
        var url = '".$ajaxUrl_sentence."';
        url = url + '/prisoner_id:'+'".$prisoner_id."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#sentence_capture_listview').html(res);
            }
        });
    }
",array('inline'=>false));
//get prisoner's petition detail list
$ajaxUrl_petition = $this->Html->url(array('controller'=>'prisoners','action'=>'petitionAjax'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
         showDataPetition();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#petition_tab_tab').on('click', function(e) {
            showDataPetition();
            e.preventDefault();
       });
    }); 
    
    function showDataPetition(){
        var url = '".$ajaxUrl_petition."';
        url = url + '/prisoner_id:'+'".$prisoner_id."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#petition_listview').html(res);
              
            }
        });
    }
",array('inline'=>false));

$commitAppealUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'commitAppeal'));
$petetionResultUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'petetionResult'));
//get prisoner's offence count detail list
$ajaxUrl_appeal = $this->Html->url(array('controller'=>'prisoners','action'=>'appealAjax'));
echo $this->Html->scriptBlock("

//save petition result -- START -- 
function savePetitionResult(id)
{
    var petition_result = $('#myPetitionModal input[name:petition_result]:checked').val();
    if(id!='' && petition_result != '')
    {
        if(confirm('Are you sure to delete?')){
            var url = '".$petetionResultUrl."';
            $.post(url, {'id':id, 'petition_result':petition_result}, function(res) { 
                if(res == 'SUCC'){
                    showDataPetition();
                }else{
                    alert('Invalid request, please try again!');
                }
            });
        }
    }
}
//save petition result -- END -- 
//Commit Appeal -- START -- 
function commitAppeal(id)
{
  if(id)
  {
      if(confirm('Are you sure to delete?')){
          var url = '".$commitAppealUrl."';
          $.post(url, {'id':id}, function(res) { 
              if(res == 'SUCC'){
                  showDataAppeals();
              }else{
                  alert('Invalid request, please try again!');
              }
          });
      }
  }
}
//Commit Appeal -- END -- 


   var tabs;
    jQuery(function($) {
         showDataAppeals();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#appeal_against_sentence_tab').on('click', function(e) {
            showDataAppeals();
            e.preventDefault();
       });
    }); 
    
    function showDataAppeals(){
        var url = '".$ajaxUrl_appeal."';
        url = url + '/prisoner_id:'+'".$prisoner_id."' + '/puuid:'+'".$prisoner_uuid."/editPrisoner:".$editPrisoner."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#appeal_listview').html(res);
            }
        });
    }
",array('inline'=>false));

//get prisoner's recapture detail list
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$prisoner_id = $this->request->data['Prisoner']['id'];
$uuid = $this->request->data['Prisoner']['uuid'];
$ajaxUrl_recapture = $this->Html->url(array('controller'=>'prisoners','action'=>'recaptureDetailAjax'));
$ajaxUrl_bail = $this->Html->url(array('controller'=>'prisoners','action'=>'bailDetailAjax'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
$getPrisonerDisabilityAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getTypeOfDisability'));
$getwardcellAjaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'showWardCell'));
$biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'getLastUser'));
$getSentenceDataAjaxUrl  =   $this->Html->url(array('controller'=>'app','action'=>'getSentenceData'));
$getSentenceCountDataAjaxUrl  =   $this->Html->url(array('controller'=>'app','action'=>'getSentenceCountDetails'));
$getClassificationUrl  = $this->Html->url(array('controller'=>'app','action'=>'getPrisonerClass'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {

        showCommonHeader();
        
         showDataRecapture();
         showDataBail();
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       $('#recaptured_details_tab').on('click', function(e) {
            showDataRecapture();
            e.preventDefault();
       });
       $('#bail_details_tab').on('click', function(e) {
            showDataBail();
            e.preventDefault();
       });
    }); 
    
    function showDataRecapture(){
        var url = '".$ajaxUrl_recapture."';
        url = url + '/prisoner_id:'+'".$prisoner_uuid."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#recapture_listview').html(res);
            }
        });
    }

    function showDataBail(){
        var url = '".$ajaxUrl_bail."';
        url = url + '/prisoner_id:'+'".$prisoner_uuid."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#bail_listview').html(res);
            }
        });
    }

    //common header
    function showCommonHeader(){
        var prisoner_id = ".$prisoner_id.";
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }
    //get prisoner sub type list
    function showPrisonerSubType(){
        var prisoner_type_id = $('#prisoner_type_id').val();
        var remand_type = '".$remand_type."';
        // if(prisoner_type_id == remand_type)
        // {
        //     $('#prisonerSubTypeValid').show();
        // }
        // else 
        // {
        //     $('#prisonerSubTypeValid').hide();
        //     $('#prisoner_sub_type_id').next('label.error').remove();
        // }
        // var url = '".$getPrisonerSubajaxUrl."';
        // $.post(url, {'prisoner_type_id':$('#prisoner_type_id').val()}, function(res) {
        //     if (res) {
        //                         $('#prisoner_sub_type_id').html(res);
        //         //$('#prisoner_sub_type_id').removeAttr('readonly','');
        //     }
        //     else 
        //     {
        //         //$('#prisoner_sub_type_id').attr('readonly','readonly');
        //     }
        // });
        // $('#prisoner_sub_type_id').select2('val', '');
        // var dob = $('#date_of_birth').val();
        var prisoner_type_id = $('#prisoner_type_id').val();
        getPrisonerClass(prisoner_type_id,dob);
    }
    //get type of disability list 
    function getTypeOfDisability()
    { 
        var url = '".$getPrisonerDisabilityAjaxUrl."';
        $.post(url, {'special_condition_id':$('#special_condition_id').val()}, function(res) {
            if (res) {
                $('#type_of_disability').html(res);
            }
        });
    }
    // code by partha
    function showWardCell()
    {
        var url = '".$getwardcellAjaxUrl."';
        $.post(url, {'assigned_ward_id':$('#assigned_ward_id').val()}, function(res) {
            if (res) {
                $('#ward_cell_id').html(res);
            }
        });
    }
    //get sentence data
    function getSentenceData(id)
    {
        var url = '".$getSentenceDataAjaxUrl."';
        $('#sentence_date_of_committal').val('');
        $('#appeal_sentence_date_of_conviction').val('');
        $('#sentence_date_of_sentence').val('');
        $('#age_on_admission').val('');
        $('#current_age').val('');
        $.post(url, {'sentence_id':id}, function(res) {
            console.log(res);
            if (res) {
                var data = jQuery.parseJSON(res);
                if(data != '')
                {
                    $('#sentence_date_of_committal').val(data.date_of_committal);
                    $('#appeal_sentence_date_of_conviction').val(data.date_of_conviction);
                    $('#sentence_date_of_sentence').val(data.date_of_sentence);
                    $('#age_on_admission').val(data.age_on_admission);
                    $('#current_age').val(data.age);
                    $('#sentence_count_id').html(data.sentence_counts);
                    $('#appeal_scount_years').val(data.years);
                    $('#appeal_scount_months').val(data.months);
                    $('#appeal_scount_days').val(data.days);
                }
            }
        });
    }
    function getSentenceCountDetails(id)
    {
        var url = '".$getSentenceCountDataAjaxUrl."';
        $.post(url, {'count_id':id}, function(res) {
            console.log(res);
            if (res) {
                var data = jQuery.parseJSON(res);
                if(data != '')
                {
                    $('#appeal_scount_years').val(data.years);
                    $('#appeal_scount_months').val(data.months);
                    $('#appeal_scount_days').val(data.days);
                }
            }
        });
    }
    
    function showDateOfConfirmation(val)
    {
        if(val == '0')
        {
            $('#date_of_confirmation_div').hide();
            $('#new_date_of_confirmation_div').hide();
            $('#sentence_appeal_date_of_confirmation').val('');
            $('#ndoc').val('');
        }
        else 
        {
            $('#date_of_confirmation_div').show();
        }
    }
    function setAppeal(val)
    {
        if(val == '0')
        {
            $('#is_appeal_sentence_confirm').show();
            $('#is_sentence_appealed').hide();
        }
        else 
        {
            $('#is_appeal_sentence_confirm').hide();
            $('#is_sentence_appealed').show();
        }
    }
    function getAppellantTypeInfo(val)
    { 
        // if(val == 'Convicted')
        // {
        //     $('#appeal_sentence').show();
        // }
        // else 
        // {
        //     $('#appeal_sentence').hide();
        // }
    }
    function getAppeledStatusInfo(val)
    {
        // if(val == 'Dismissed')
        // {
        //     $('#date_of_dismissal_appeal').show();
        // }
        // else 
        // {
        //     $('#date_of_dismissal_appeal').hide();
        // }

        if(val == 'Completed')
        {
            $('#appeal_result_div').show();
        }
        else 
        {
            $('#appeal_result_div').hide();
        }
        
    }
    function getAppeledResultInfo(val)
    {
        $('#appeal_scount_years').val('');
        $('#appeal_scount_months').val('');
        $('#appeal_scount_days').val('');
        $('#date_of_dismissal_appeal').val('');
        if(val == 'Dismissed')
        {
            $('#date_of_dismissal_appeal').show();
        }
        else 
        {
            $('#date_of_dismissal_appeal').hide();
            //display sentence count details
        }
        if(val == 'Enhanced' || val == 'Reduced')
        {
            $('#new_sentence_count_details').show();
            $('#appeal_scount_years').removeAttr('readonly');
            $('#appeal_scount_months').removeAttr('readonly');
            $('#appeal_scount_days').removeAttr('readonly');
        }
        else 
        {
            $('#new_sentence_count_details').hide();
            $('#appeal_scount_years').attr('readonly','readonly');
            $('#appeal_scount_months').attr('readonly','readonly');
            $('#appeal_scount_days').attr('readonly','readonly');
        }
    }
    
    //get prisoner class based on prisoner type and date of birth
    function getPrisonerClass(prisoner_type_id,dob)
    {
        //alert(prisoner_type_id); //alert(dob);
        if(prisoner_type_id != '' && dob != '')
        {
            if(prisoner_type_id == 2)
            {
                $('#classification_id').attr('required','required');
                var classification_url = '".$getClassificationUrl."';
                $.post(classification_url, {'dob':dob, 'prisoner_type_id':prisoner_type_id}, function(class_res) {
                    if (class_res) { 
                        $('#classification_id').val(class_res).trigger('change');
                    }
                });
                $('#classification_div').show();
            }
            else 
            {
                $('#classification_id').val('').trigger('change');
                $('#classification_id').removeAttr('required','');
                $('#classification_div').hide();
            }
        }
    }



    function getDebtorAmountRecieved(days)
    {
        var amount_recieved = 0;
        if(days !='')
        {
           var rate = $('#rate_per_day').val();
           amount_recieved = parseInt(rate)*parseInt(days);
        }
        $('#amount_recieved').val(amount_recieved);
        var value_of_debt = $('#value_of_debt').val();
        if(value_of_debt != '')
        {
            getDebtorNextPaymentDate(value_of_debt);
        }
    }
    function getDebtorNextPaymentDate(total_amount)
    {
        total_amount = $('#value_of_debt').val();
        var amount_recieved = $('#amount_recieved').val();
        var rate = $('#rate_per_day').val();
        var pending_days = 0;
        if(total_amount !='')
        {
            var amount_diff = parseInt(total_amount)-parseInt(amount_recieved); 
            //alert(amount_diff)
            if(parseInt(amount_diff) >= 0)
            {
                pending_days = parseInt(amount_diff)/rate;
                var current_date = new Date();
                // get next payment date
                current_date.setDate(current_date.getDate() + pending_days);
                var dd = current_date.getDate();
                var mm = current_date.getMonth() + 1;
                var y = current_date.getFullYear();
                var next_payment_date = dd + '-' + mm + '-' + y;
                if(parseInt(amount_diff) > 0)
                    $('#next_payment_date').val(next_payment_date);

                var paid_days = parseInt($('#no_of_days_for_amount').val());

                var date_of_release = new Date();
                date_of_release.setDate(date_of_release.getDate() + paid_days);
                
                var dd2 = date_of_release.getDate();
                var mm2 = date_of_release.getMonth() + 1;
                var y2 = date_of_release.getFullYear();

                var date_of_release2 = dd2 + '-' + mm2 + '-' + y2;
                $('#date_of_release').val(date_of_release2);
            }
            else {
                $('#next_payment_date').val('');
                $('#date_of_release').val('');
            }
        }
    }

    function checkData(){
        alert(1);
        var url = '".$biometricSearchAjax."';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    $('#link_biometric').val(res.trim());
                    $('#link_biometric_span').html(res.trim());
                    $('#link_biometric_button').hide();
                }else{
                    alert('Please press figure on biometric');
                }  
            },
            async:false
        });
    }
    //get prisoner age 
    function getPrisonerAge(birth_date)
    {
        if(birth_date != '')
        {
            today_date = new Date();
            today_year = today_date.getFullYear();
            today_month = today_date.getMonth();
            today_month = +today_month + 1;
            today_day = today_date.getDate();

            var birth_data = birth_date.split('-');
            var birth_day = birth_data[0];
            var birth_month = birth_data[1];
            var birth_year = birth_data[2];

            var age = today_year - birth_year;

            if ( today_month < (birth_month - 1))
            {
                age--;
            }
            if (((birth_month - 1) == today_month) && (today_day < birth_day))
            {
                age--;
            }
            $('#personal_age_on_admission').val(age);
            $('#personal_current_age').val(age);
        }
    }
    //get age 

    // function getChildAge(birthday)
    // {
    //     birthday = new Date(birthday);
    //     var ageDifMs = Date.now() - birthday.getTime();
    //     var ageDate = new Date(ageDifMs); // miliseconds from epoch
    //     var age = Math.abs(ageDate.getUTCFullYear() - 1970);
    //     alert(age);
    // }
    
    function getChildAge2(birth_date2)
    {
        var birth_date = $('#child_dob').val();
        today_date = new Date();
        today_year = today_date.getFullYear();
        today_month = today_date.getMonth();
        today_month = +today_month + 1;
        today_day = today_date.getDate();
        //alert(birth_date);
        var birth_data = birth_date.split('-');
        var birth_day = birth_data[0];
        var birth_month = birth_data[1];
        var birth_year = birth_data[2];
        //alert(birth_year);
        //birth_month,birth_day,birth_year;
        age = today_year - birth_year;

        if ( today_month < (birth_month - 1))
        {
        age--; 
        }
        if (((birth_month - 1) == today_month) && (today_day < birth_day))
        {
        age--; 
        }
        // var mdiff = 0;
        // if(today_month > birth_month)
        // {
        //     mdiff = Math.abs(today_month-birth_month);
        // }
        // var daydiff = 0;
        // if(today_day > birth_day)
        // {
        //     daydiff = today_day-birth_day;
        // }
        // else
        // {

        // }

        var ageMonth = Math.abs(birth_month - today_month);

        //ageMonth = ageMonth-1;
        var ageDays = Math.abs(birth_day -today_day);
        if(+age >= 18 && age != 'NaN'){
        $('#child_dob').val('');
        $('#child_age').val('');
        dynamicAlertBox('Error','child age cannot be greater than 18 years' );
        }

        if(+age <= 18 && age != 'NaN'){
            $('#child_age').val(age + ' years ' + ageMonth +' months ' + ageDays + ' days ' );
        }
        //alert( age);
    }

    

",array('inline'=>false));
?> 
<script>
    function showPrisonerSubType(){
       // alert(1);
        

        var url = '".$getPrisonerSubajaxUrl."';
        var prisoner_type_id = $('#prisoner_type_id').val();
        if(prisoner_type_id==2) {
            $('#admit_under').show();
        }else{
            $('#admit_under').hide();

        }
        //alert(prisoner_type_id)
        var remand_type = '".$remand_type."';
        // if(prisoner_type_id == remand_type)
        // {
        //     $('#prisonerSubTypeValid').show();
        // }
        // else 
        // {
        //     $('#prisonerSubTypeValid').hide();
        //     $('#prisoner_sub_type_id').next('label.error').remove();
        // }
        // $.post(url, {'prisoner_type_id':prisoner_type_id}, function(res) {
        //     if (res) {
        //         $('#prisoner_sub_type_id').html(res);
        //         //$('#prisoner_sub_type_id').removeAttr('readonly');
        //     }
        //     else 
        //     {
        //         //$('#prisoner_sub_type_id').attr('readonly','readonly');
        //     }
        // });
        // $('#prisoner_sub_type_id').select2('val', '');
        var dob = $('#date_of_birth').val();
        getPrisonerClass(prisoner_type_id,dob);
    }
    
function getSentenceDate(val)
{
    if(val != '')
    {
        var date2 = val.split('-');
        var d2 = new Date(date2[2], date2[1], date2[0]);
        updated_doc = d2.getDate()+'-'+d2.getMonth()+'-'+d2.getFullYear();
        $('.date_of_commital').val(val);
    } 
    else 
    {
        $('.date_of_commital').val(val);
    }
}
// function getSentenceDate2(val)
// {
//     if(val != '')
//     {
//         var date2 = val.split('-');
//         var d2 = new Date(date2[2], date2[1], date2[0]);
//         updated_doc = d2.getDate()+'-'+d2.getMonth()+'-'+d2.getFullYear();
//         $('.date_of_commital2').val(val);
//     } 
//     else 
//     {
//         $('.date_of_commital2').val(val);
//     }
// }



//validate prisoner admission form -- START --
$('form#PrisonerAdmissionEditForm').on('submit', function(event) {
    //Add validation rule for dynamically generated name fields
    // $('.court_file_no').each(function() {
    //     $(this).rules("add", 
    //         {
    //             required: true,
    //             messages: {
    //                 required: "Court File No is required",
    //             }
    //         });
    // });
    // $('.case_file_no').each(function() {
    //     $(this).rules("add", 
    //         {
    //             required: true,
    //             messages: {
    //                 required: "Case File No is required",
    //             }
    //         });
    // });
    // $('.crb_no').each(function() {
    //     $(this).rules("add", 
    //         {
    //             required: true,
    //             messages: {
    //                 required: "C.R.B No is required",
    //             }
    //         });
    // });
    $('.date_of_warrant').each(function() {
        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Select Date of warrant",
                }
            });
    });
    var case_count = parseInt($('#case_list .case_list').length);
});
//$("#PrisonerAdmissionEditForm").validate();

$("#PrisonerPetitionEditForm").validate();

//validate prisoner admission form -- START --

$(document).ready(function () {
    
    //showPrisonerFiles();
    
    // $('.pmis_select').select2({
    //     placeholder:'Select'   
    // })
    
    $.validator.setDefaults({
        ignore: []
    });
        
    $('#PrisonerAdmissionEditForm').validate({
        errorElement: 'span',
        errorClass: 'error',
        rules: {
            singleselect:'required',
            multipleselect:'required',
            name:'required'
        },
        
        highlight: function (element, errorClass, validClass) {
            
            var elem = $(element);
            
            elem.addClass(errorClass);
            
        },
        unhighlight: function (element, errorClass, validClass) {
            var elem = $(element);
            
            if(elem.hasClass('pmis_select')) {
                elem.siblings('.pmis_select').find('.select2-choice').removeClass(errorClass);
            } else {
                elem.removeClass(errorClass);
            }
        }
    });
});
function showReparitaion() {
      if($('#PrisonerAddittedReparitation').is(":checked"))
    {
        $('#reparitation').show();
        //add validation
       
    }
    else 
    {
        $('#reparitation').hide();
        //remove validation
       
    }

}
function showHospital(isdual) {
    if (isdual=='Y') {
        $('#hospita_div').show();
    }else{
        $('#hospita_div').hide();
    }

}
function showServing()
{
    if($('#PrisonerIsSmforce').is(":checked"))
    {
        $('#serving_div').show();
    }
    else 
    {
        $('#serving_div').hide();
    }
}

var prisonerFilesAjax = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'prisonerFilesAjax'));?>';
var prisoner_id = '<?php echo $prisoner_id;?>';
var editPrisoner = '<?php echo $editPrisoner;?>';
//Show Prisoner File List START --
function showPrisonerFiles()
{
    var url = prisonerFilesAjax;
    url = url + '/prisoner_id:'+prisoner_id+'/editPrisoner:'+editPrisoner;
    $.post(url, {}, function(res) {
        if (res) {
            $('#prisoner_files_listview').html(res);
        }
    });
}
//Show Prisoner File List END --
function showDebtorFiles() 
{
    if($('#PrisonerAdmissionDebtorFiles').is(":checked"))
    {
        $('#debtor-files').show();
        $('.convict').attr("required", "true");
    }
    else 
    {
        $('#debtor-files').hide();
        $('.convict').removeAttr("required");
    }
}
function showConvictRemandFiles() 
{
    if($('#PrisonerAdmissionConvictRemandFiles').is(":checked"))
    {
        $('#convict-remand-files').show();
        $('.debtor').attr("required", "true");
    }
    else 
    {
        $('#convict-remand-files').hide();
        $('.debtor').removeAttr("required");
    }
}
//Add Panel Of justices START -- 
function addJudge(cnt)
{
    var res = $('#'+cnt+'_judges input:first').clone();
    $('#'+cnt+'_judges').append(res);
    $('#'+cnt+'_judges input:last').val('');
    $('#'+cnt+'_judges input:last').css('margin-top','5px');
    var count = parseInt($('#'+cnt+'_judges input').length);
    if(count > 1)
    {
        $('#'+cnt+'_judges_remove_btn').removeClass('hidden');
    }
}
//Add Panel Of justices END --
//delete prisoner case file -- START --
function deleteCaseFile(paramId){
    if(paramId){
        var url = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'deletePrisonerCaseFile'));?>';
        $.post(url, {'paramId':paramId}, function(res) { 
            if(res == 'SUCC'){
                showPrisonerFiles();
            }else{
                dynamicAlertBox('Error', 'Invalid request, please try again!');
            }
        });
    }
} 
function showRefugee() {
    // alert(1);

    if($('#PrisonerIsRefugee').is(":checked"))
    {
        $('#refugee').show();
        
       
    }
    else 
    {
        $('#refugee').hide();
        
       
    }
   

}
//delete prisoner case file -- END --
</script>
