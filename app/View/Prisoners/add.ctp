<?php
if(isset($this->data['Prisoner']['date_of_birth']) && $this->data['Prisoner']['date_of_birth'] != ''){
    $this->request->data['Prisoner']['date_of_birth']=date('d-m-Y',strtotime($this->data['Prisoner']['date_of_birth']));
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
#nationality_name2, #nationality_name2_note
{
    margin-top: 15px;
}
.topMargin div{margin-top: 10px;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Prisoner</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
              
                           
                                <?php 
                                echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/prisoners/add/'));
                                // echo $this->Form->input('status',array(
                                //         'type'=>'hidden',
                                //         'value'=>'Admitted'
                                //     ));
                                if(isset($this->request->data['Prisoner']['id']))
                                {
                                    $prisoner_unique_no = $this->request->data['Prisoner']['prisoner_unique_no'];
                                    echo $this->Form->input('prisoner_unique_no',array(
                                        'type'=>'hidden',
                                        'value'=>$prisoner_unique_no
                                    ));
                                    echo $this->Form->input('personal_no',array(
                                        'type'=>'hidden',
                                        'value'=>$this->request->data['Prisoner']['personal_no']
                                    ));
                                    $this->Form->control('id', array('value' => ''));
                                    echo $this->Form->input('exp_photo_name',array(
                                        'type'=>'hidden',
                                        'class'=>'exp_photo_name',
                                        'value'=>$this->request->data['Prisoner']['photo']
                                    ));
                                    echo $this->Form->input('is_ext',array(
                                        'type'=>'hidden',
                                        'class'=>'is_ext',
                                        'value'=>1
                                    ));
                                    echo $this->Form->input('present_status',array(
                                        'type'=>'hidden',
                                        'class'=>'present_status',
                                        'value'=>1
                                    ));
                                }
                                ?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">DOA<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('doa',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'Enter Date Of Admission','id'=>'doa', 'default'=>date('d-m-Y'), 'readonly'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">First Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Enter First Name','id'=>'first_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Middle name :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('middle_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Middle name','required'=>false,'id'=>'middle_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Surname :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','placeholder'=>'Enter Surname','required'=>false,'id'=>'last_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Also Known As :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('also_known_as',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Also Known As','required'=>false,'id'=>'also_known_as','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Father's Name<?php //echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Father's Name",'required'=>false,'id'=>'father_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11 prisoner_dob','type'=>'text', 'placeholder'=>'Enter Date of Birth','required','id'=>'date_of_birth','readonly'=>'readonly'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Mother's Name<?php //echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('mother_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>"Enter Mother's Name",'required'=>false,'id'=>'mother_name','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label" style="width: 170px;">Require Ascertaining Age?:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('suspect_on_age',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox' ));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Type<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$prisonerTypeList, 'empty'=>'','required','id'=>'prisoner_type_id','onchange'=>'showPrisonerSubType(this.value);'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>  
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
                                                            <div id="prevImage" class="" style="margin-top: 10px;">
                                                            <?php $is_photo4 = '';
                                                            if(isset($this->request->data["Prisoner"]["repatriation_order"]) && !is_array($this->request->data["Prisoner"]["repatriation_order"]))
                                                            {
                                                                $is_photo1 = 1;?>
                                                               <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["repatriation_order"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["Prisoner"]["repatriation_order"];?>" alt="" width="150px" height="150px"></a>
                                                            <?php }?>
                                                            </div>
                                                            <span id="previewPane1" class="">
                                                                <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="img_prev6" src="#" class="img_prev" /></a>
                                                                <span id="x6" class="remove_img">[X]</span>
                                                            </span>
                                                            <div class="clear"></div>
                                                            <?php echo $this->Form->input('repatriation_order',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'repatriation_order', 'onchange'=>'readURL(this, 6);', 'required'=>false));?>
                                                            <?php echo $this->Form->input('is_photo4',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'is_photo4', 'value'=>$is_photo4));?>
                                                                
                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                          
                                       
                                    </div>
                                    </div>
                                </div>
                                <!-- Is refugee  -->
                                <div class="clearfix"></div>  
                                   
                                    
                                 <!--  <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Employment Type<?php// echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php //echo $this->Form->input('employment_type',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$employmentList, 'empty'=>'','required','id'=>'employment_type'));?>
                                            </div>
                                        </div>
                                    </div> -->


                                     
                                     
                                    <!-- <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Place Of Birth<?php //echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php //echo $this->Form->input('place_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Enter Place Of Birth','required','id'=>'place_of_birth','maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="clearfix"></div> 
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
                                                            <?php echo $this->Form->input('birth_district_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$birthDistrictList, 'empty'=>'','onChange'=>'showcounty(this.value)','required'=>false,'id'=>'birth_district_id'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">County:</label>
                                                        <div class="controls">
                                                        <?php
                                                        
                                                            echo $this->Form->input('county_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$allCountyList,'onChange'=>'showsubcounty(this.value)', 'empty'=>'','required'=>false,'id'=>'county_id'));
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
                                                            echo $this->Form->input('sub_county_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$allSubCountyList, 'empty'=>'','onChange'=>'showParish(this.value)','required'=>false,'id'=>'sub_county_id'));
                                                       ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Parish:</label>
                                                        <div class="controls">
                                                        <?php
                                                            echo $this->Form->input('parish_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$allParishList, 'empty'=>'','onChange'=>'showVillage(this.value)', 'required'=>false,'id'=>'parish_id'));
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
                                                            echo $this->Form->input('village_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$allVillageList, 'empty'=>'','required'=>false,'id'=>'village_id'));
                                                       ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Serving Forces code -- START partha-->
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Is a Serving Member Of Forces ?:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('is_smforce',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'onClick'=>'showServing()'));?>
                                            </div>
                                        </div>  
                                    </div>
                                    
                                    <div class="container-fluid">
                                        <div class="span12 secondDiv widget-box" id="serving_div" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; margin-left: 15px !important; display:none;">
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
                                                <?php echo $this->Form->input('ug_force_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$ugForceList, 'empty'=>'-- Select UG Force --','required'=>false,'id'=>'ug_force_id'));?>
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
                                                <?php //echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>'', 'empty'=>'-- Select Prisoner Sub Type --','required', 'id'=>'prisoner_sub_type_id'));?>
                                            </div>
                                        </div>
                                    </div>   -->
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
                                                echo $this->Form->input('continent_id',array('div'=>false,'label'=>false,'onChange'=>'showCountries(this.value)','class'=>'pmis_select form-control span11','type'=>'select','options'=>$continentList, 'empty'=>'','required','id'=>'continent_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Country of origin<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'onChange'=>'showDistricts(this.value)','class'=>'pmis_select form-control span11','type'=>'select','options'=>$countryList, 'empty'=>'','required'=>false,'id'=>'country_id','default'=>1));?>
                                                <?php echo $this->Form->input('other_country',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Country','required'=>false,'id'=>'other_country','style'=>'display:none;', 'maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">District Of Origin:</label>
                                            <div class="controls" id="district_id_div">
                                                <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$districtList, 'empty'=>'','required'=>false,'id'=>'district_id'));?>
                                                <?php echo $this->Form->input('other_district',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'District','required'=>false,'id'=>'other_district','style'=>'display:none;', 'maxlength'=>'30'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="container-fluid">
                                        <div class="row-fluid formSepBox">

                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" style="width: 210px;">Is the Prisoner a dual citizen?:</label>
                                                    <div class="controls uradioBtn" style="margin-left: 240px;">
                                                        <?php 
                                                        $is_dual_citizen = 0;
                                                        if(isset($this->data['Prisoner']['is_dual_citizen']))
                                                            $is_dual_citizen = $this->data['Prisoner']['is_dual_citizen'];
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
                                     
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Nationality:</label>
													<div class="controls">
														<?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text', 'placeholder'=>'Nationality','required','id'=>'nationality_name', 'readonly', 'maxlength'=>'30'));?>
		
														<?php echo $this->Form->input('nationality_name2',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$nationalityList, 'empty'=>'','required'=>false,'id'=>'nationality_name2'));?>
		
														<?php echo $this->Form->input('nationality_name2_note',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required'=>false, 'placeholder'=>'Dual Citizen Note','id'=>'nationality_name2_note'));?>
													</div>
												</div>
											</div> 

                                        </div>
                                    </div>
										
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Link with Biometric:</label>
                                            <div class="controls">
                                                <?php 
                                                echo $this->Form->input('link_biometric',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$this->requestAction('/Biometrics/getUnlinkedBioUser'), 'empty'=>'','required'=>false,'id'=>'link_biometric'));
                                                // echo $this->Form->input('link_biometric',array(
                                                //     'type'=>'hidden',
                                                //     'id'=>'link_biometric',
                                                //   ));
                                                  ?>
                                                  <span id="link_biometric_span"></span>
                                                  <?php
                                                //echo $this->Form->button('Get Biometric Data', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning','id'=>'link_biometric_button',"onclick"=>"checkData()"));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Tribe<span id="tribeValid" style="display: none;"><?php echo $req; ?></span> :</label>
                                            <div class="controls topMargin">
                                                <?php echo $this->Form->input('tribe_id',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$tribeList, 'empty'=>'','required','id'=>'tribe_id','onChange'=>'openOtherField("tribe");'));?>
                                            </div>
                                        </div>
                                    </div>                                                           
                                    <div class="span6" id="classification_div" style="display: none;">
                                        <div class="control-group">
                                            <label class="control-label">Classification<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('classification_id',array('div'=>false,'label'=>false, 'type'=> 'hidden', 'id'=>'classification_id'));

                                                echo $this->Form->input('classification_id_display',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$classificationList, 'empty'=>'','required'=>false,'id'=>'classification_id_display', 'readonly', 'disabled'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Permanent Address<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('permanent_address',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter permanent address','id'=>'permanent_address','rows'=>3,'required'=>false));?>
                                            </div>
                                        </div>

                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Desired District of<br>Release<?php echo $req; ?>:</label>
                                            <div class="controls">
                                           <?php echo $this->Form->input('desired_districts_relese',array('div'=>false,'label'=>false,'class'=>'pmis_select form-control span11','type'=>'select','options'=>$districtList, 'empty'=>'','required'=>'required','id'=>'desired_districts_relese', 'title'=>'Please select Desired District of Release'));?>
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

                                <div class="form-actions" align="center">
                                <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true))?>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>"resetForm('PrisonerAddForm');"))?>
                                    
                                </div>
                                <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$uganda_country_id = Configure::read('COUNTRY-UGANDA');
$remand_type = Configure::read('REMAND');
?>
<script type="text/javascript">
function showNationality2(isdual)
{
    if(isdual == 1)
    {
        $('#nationality_name2').show();
        $('#nationality_name2_note').show();
        $('#nationality_name2').select2({
            placeholder: "-- Select --",
            allowClear: true
        });
        $('#s2id_nationality_name2').css('margin-top','15px');
    }
    else 
    {
        $('#nationality_name2_note').hide();
        $('#nationality_name2_note').hide('');
        $('#nationality_name2').hide();
        $('#nationality_name2').val('');
        $("#nationality_name2").select2('destroy'); 
    }
}
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
          var img = input.value;
            $('#img_prev'+cnt).attr('src',img).width(100);
        }
        $('#prevImage'+cnt).hide();
        $('#img_prev'+cnt).show();
        $("#x"+cnt).show().css("margin-right","10px");
    }
}
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
        var other_field = '<div><input name="data[Prisoner][other_'+fname+']" class="form-control span11" placeholder="'+placeholder_val+'" id="other_'+fname+'" style="" maxlength="30" type="text" required></div>';
        $( other_field ).insertAfter('#'+field_id);
    }
    else 
    {
        if ($('#'+other_field_id).length)
        {
            //remove validation error message
            //var $validator = $("#PrisonerAddForm").validate();
            //errors = { 'data[Prisoner][other_tribe]': "" };
            //$validator.showErrors(errors);  
            $('#'+other_field_id).parent().find('label').remove();
            //remove other field  
            $('#'+other_field_id).remove();
        }
    }
}
$(document).ready(function() {
    //auto focus on prisoner's first name 
    //if($('#first_name').val().trim() == '')
    //{
        // alert(1);
        // showReparitaion();
        $('#first_name').focus();   
    //}
    //if other country selected 
    if($('#country_id').val() == 'other')
    {
        $('#other_district').show();
        $('#s2id_district_id').hide();
        $('#other_country').show();   
    }
    else 
    {
        $('#other_district').hide();
        $('#s2id_district_id').show();
        $('#other_country').hide();  
    }
    //if other tribe selected 
    // if($('#tribe_id').val() == 'other')
    // {
    //     var other_field = '<div><input name="data[Prisoner][other_tribe]" class="form-control span11" placeholder="Tribe" id="other_tribe" style="" type="text" required></div>';
    //     $( other_field ).insertAfter('#tribe_id');
    // }
    // else 
    // {
    //     if ($('#'+other_field_id).length)
    //     {
    //         $('#'+other_field_id).remove();
    //     }
    // }
    $('#x6').click(function() {
        $('#repatriation_order').val();
        $("#img_prev6").attr("src",'');
        $('#img_prev6').hide();



        $("#x6").hide();  
        $('span.filename').html('');
        $('#prevImage6').show();
        $('#is_photo4').val('');
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
});
$(document).ready(function(){
    // alert(1);
    $('#country_id').select2('val', '1');
    $('#continent_id').select2('val', '1');
    $("#country_id option[value='1']").attr("selected","selected");
    $("#continent_id option[value='1']").attr("selected","selected");
        
});
function onCountryChange(country_id)
{
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
}
function showCountries(id)
{
    var strURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'countryList'));?>';
    $.post(strURL,{"continent_id":id},function(data){  
        
        if(data) { 
            $('#country_id').html(data); 
            if(id == 1)
            {
                $('#country_id').val(1);
            }
            else 
            {
                $('#country_id').val(0);
            }
            $('#country_id').select2({
                placeholder: "-- Select --",
                allowClear: true
            });
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
function showDistricts(id) 
{
    if(id != 'other')
    {
        var uganda = "<?php echo $uganda_country_id;?>";
        if(id == uganda)
        {
            $('#tribeValid').show();
        }
        else 
        {
            $('#tribeValid').hide();
            $('#tribe_id').next('label.error').remove();
        }
        if(document.getElementById('other_country') != null)
        {
            $('#other_country').remove();
            $('#other_district').hide();
            $('#other_district').val('');
            $('#s2id_district_id').show();
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
        $('#s2id_country_id').append('<input type="text" name="data[Prisoner][other_country]" placeholder="Country" id="other_country" class="form-control span11 alpha" style="width: 100%; margin-top:10px;" maxlength="30">');
        $('#other_district').show();
        $('#s2id_district_id').hide();
    }
}

function showcounty(id){

            /*aakash code*/
                  
                  $('#county_id').html("<option value=''></option>");
                  $('#sub_county_id').html('<option value=""></option>'); 
                  $('#parish_id').html('<option value=""></option>'); 
                  $('#village_id').html('<option value=""></option>'); 

            /*aakash code end*/
                    
          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getCounty'));?>';
      
          $.post(strURL,{"district_id":id},function(data){  
              
              if(data) { 
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
  function showsubcounty(id){
            
            /*aakash code*/
                  
                  $('#sub_county_id').html('<option value=""></option>'); 
                  $('#parish_id').html('<option value=""></option>'); 
                  $('#village_id').html('<option value=""></option>'); 

            /*aakash code end*/
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
      
               /*aakash code*/
                  
                  $('#parish_id').html('<option value=""></option>'); 
                  $('#village_id').html('<option value=""></option>'); 

            /*aakash code end*/
            
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
      
               /*aakash code*/
                  
                  $('#village_id').html('<option value=""></option>'); 

            /*aakash code end*/
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
$(document).ready(function () {

    $("select").trigger("change");

    $('#date_of_birth').datepicker({
        onSelect: function(value, ui) {
            var prisoner_type_id = $('#prisoner_type_id').val();
            getPrisonerClass(prisoner_type_id, value);
        },
        maxDate: '+0d',
        yearRange: '1920:2010',
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
    });
  
    $(document).on('change', '#country_id', function() {
        var country_id=$(this).val();
        $('#nationality_name').val('');
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
    if($('#continent_id').val() == '' || $('#continent_id').val() == '0') 
    {
        $('#continent_id').val('1').trigger('change');
    }
    //If continent selected 
    if($('#continent_id').val() != '')
    {
        showCountries($('#continent_id').val());
    }
    //if prisoner type selected 
    if($('#prisoner_type_id').val() == '' || $('#prisoner_type_id').val() == '0') 
    {
        showPrisonerSubType($('#prisoner_type_id').val());
    }
    //check if is dual citizen is clicked
    if ($('#PrisonerIsDualCitizen1:checked').val() == 1)
    {
        $('#nationality_name2').show();
        $('#nationality_name2_note').show();
    }
    else 
    {
        $('#nationality_name2').hide();
        $('#nationality_name2_note').hide();
    }

    var dis_id='';
    <?php if(isset($this->request->data['Prisoner']['birth_district_id'])){?>
       dis_id = '<?php echo $this->request->data['Prisoner']['birth_district_id'];?>';
        showcounty(dis_id);
    <?php }?>
}); 
$(function(){
$.validator.addMethod('filesize', function(value, element, param) {
    // param = size (en bytes) 
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param) 
});
     $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
        $("#PrisonerAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Prisoner][first_name]': {
                    required: true,
                },
                // 'data[Prisoner][last_name]': {
                //     required: true,
                // },
                // 'data[Prisoner][father_name]': {
                //     required: true,
                // },
                // 'data[Prisoner][mother_name]': {
                //     required: true,
                // },
                'data[Prisoner][continent_id]': {
                    required: true,
                },
                // 'data[Prisoner][nationality_name]': {
                //     required: true,
                // },
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
                'data[Prisoner][prisoner_sub_type_id]': {
                    // required: true,
                    // valueNotEquals: "0"
                    required: function(element){
                        return $("#prisoner_type_id").val()=="<?php echo $remand_type;?>";
                    }
                },
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
                'data[Prisoner][is_photo]': {
                    required: true,
                },
                'data[Prisoner][is_photo1]': {
                    required: true,
                },
                'data[Prisoner][is_photo2]': {
                    required: true,
                },
                'data[Prisoner][permanent_address]': {
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
                // 'data[Prisoner][nationality_name]': {
                //     required: "Please enter nationality.",
                // },
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
                'data[Prisoner][prisoner_sub_type_id]': {
                    required: "Please select prisoner subtype.",
                },
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
                    extension: "Please upload (jpg,jpeg,png,gif) type front photo",
                    filesize:"File size must be 2MB.",
                },
                'data[Prisoner][left_photo]': {
                    extension: "Please upload (jpg,jpeg,png,gif) type left side photo",
                    filesize:"File size must be 2MB.",
                },
                'data[Prisoner][right_photo]': {
                    extension: "Please upload (jpg,jpeg,png,gif) type right side photo",
                    filesize:"File size must be 2MB.",
                },
                'data[Prisoner][is_photo]': {
                    required: "Please choose front photo.",
                },
                'data[Prisoner][is_photo1]': {
                    required: "Please choose left photo.",
                },
                'data[Prisoner][is_photo2]': {
                    required: "Please choose right photo.",
                },
                'data[Prisoner][permanent_address]': {
                    required: "Please enter permanent address.",
                    loginRegex: "Permanent address must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 250 characters.",
                },
            }, 
    });
  });
function showServing()
{
    if($('#PrisonerIsSmforce').is(":checked"))
    {
        $('#serving_div').show();
        //add validation
        $('#service_number').prop('required', true);
        $('#service_name').prop('required', true);
        $('#service_rank').prop('required', true);
        $('#service_unit').prop('required', true);
    }
    else 
    {
        $('#serving_div').hide();
        //remove validation
        $('#service_number').prop('required', false);
        $('#service_name').prop('required', false);
        $('#service_rank').prop('required', false);
        $('#service_unit').prop('required', false);
    }
}
function showReparitaion() {
      if($('#PrisonerAddittedReparitation').is(":checked"))
    {
        $('#reparitation').show();
        //add validation
       
    }
    else 
    {
        // alert(3);
        $('#reparitation').hide();
        //remove validation
       
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


</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
$userinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'personalInfo'));
$idinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'prisnorsIdInfo'));
$biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'getLastUser'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
$getClassificationUrl  = $this->Html->url(array('controller'=>'app','action'=>'getPrisonerClass'));
echo $this->Html->scriptBlock("
   var tabs;
    jQuery(function($) {
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });  
       
    }); 
    function getDistrict(){
        var url = '".$ajaxUrl."';
        $.post(url, {'state_id':$('#state_id').val()}, function(res) {
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
    //get prisoner sub type list
    function showPrisonerSubType(){
       // alert(1);
        

        var url = '".$getPrisonerSubajaxUrl."';
        var prisoner_type_id = $('#prisoner_type_id').val();
        if(prisoner_type_id==2) {
            $('#admit_under').show();
        }else{
        //alert(2);
            $('#admit_under').hide();
            $('#reparitation').hide();

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
    
    function getPrisonerClass(prisoner_type_id,dob)
    {
        if(prisoner_type_id != '' && dob != '')
        {
            if(prisoner_type_id == 2)
            {
                $('#classification_id').attr('required','required');
                var classification_url = '".$getClassificationUrl."';
                $.post(classification_url, {'dob':dob, 'prisoner_type_id':prisoner_type_id}, function(class_res) {
                    if (class_res) {
                        $('#classification_id_display').val(class_res).trigger('change');
                        $('#classification_id').val(class_res);
                    }
                });
                $('#classification_div').show();
            }
            else 
            {
                $('#classification_id_display').val('').trigger('change');
                $('#classification_id').val('');
                $('#classification_id').removeAttr('required','');
                $('#classification_div').hide();
            }
        }
    }

    function checkData(){
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
                    alert('Please register in biometric first');
                }  
            },
            async:false
        });
    }

",array('inline'=>false));
?> 
