<div class="widget-box">
                
                    <div class="widget-content nopadding">
                        <div class="">
                            <ul class="nav nav-tabs">
                                <li><a href="<?php echo $this->webroot;?>CorrectionEducationProgrammes/formalEducation" id="menu_correction_education_program">Correction Education Programmes</a></li>
                                <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/socialisationProgrammes"  id="menu_social_rehab_program">Social Rehabiliation Programme</a></li>
                            </ul>
                        </div>
                    </div> 
                </div> 
                <div class="widget-box" style="display: none;" id="sub_menu_social_rehab_program">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Social Rehabilitation Programme</h5>
                </div>
                    <div class="widget-content nopadding">
                        <div class="">
                            <ul class="nav nav-tabs">
                                <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/socialisationProgrammes" id="tab_socialisation">Socialisation programmes</a></li>
                                <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/counsellingAndGuidance" id="tab_councelling">Counselling and Guidance</a></li>
                                <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/spiritualAndMoralRehabiliation" id="tab_spiritual">Spiritual and Moral rehabiliation</a></li>
                                <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/behaviourLifeSkillTrainings" id="tab_behaviour">Behaviour Life Skill Trainings</a></li>
                                <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/livelihoodSkillsTraining" id="tab_livelihood">Livelihood skills Training</a></li>
                                 <li><a href="<?php echo $this->webroot;?>SocialRehabiliationProgramme/specificCaseTreatment" id="tab_specific_case">Specific Case Treatment</a></li>
                            </ul>
                        </div>
                    </div> 
                </div>  
                <div class="widget-box" style="display: none;" id="sub_menu_correction_education_program">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Correction Education Programmes</h5>
                </div>
                    <div class="widget-content nopadding">
                        <div class="">
                            <ul class="nav nav-tabs">
                                
                                <li><a href="<?php echo $this->webroot;?>CorrectionEducationProgrammes/formalEducation" id="tab_formal">Formal Education</a></li>
                                <li><a href="<?php echo $this->webroot;?>CorrectionEducationProgrammes/nonFormalEducation" id="tab_non_formal">Non Formal Education</a></li>
                                
                            </ul>
                        </div>
                    </div> 
                </div>
<script type="text/javascript">
    $( document ).ready(function() {
        console.log(location.href);
        var currLocation = location.href;
        if(currLocation.indexOf('socialisation') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_socialisation').attr('aria-selected','true');
            $('#tab_socialisation').parent().addClass('active');
        }else if(currLocation.indexOf('counselling') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_councelling').attr('aria-selected','true');
            $('#tab_councelling').parent().addClass('active');
        }
        else if(currLocation.indexOf('spiritual') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_spiritual').attr('aria-selected','true');
            $('#tab_spiritual').parent().addClass('active');
        }
        else if(currLocation.indexOf('behaviour') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_behaviour').attr('aria-selected','true');
            $('#tab_behaviour').parent().addClass('active');
        }
        else if(currLocation.indexOf('livelihood') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_livelihood').attr('aria-selected','true');
            $('#tab_livelihood').parent().addClass('active');
        }
        else if(currLocation.indexOf('specificCase') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_specific_case').attr('aria-selected','true');
            $('#tab_specific_case').parent().addClass('active');
        }
          else if(currLocation.indexOf('specificCase') > -1){
            $('#menu_social_rehab_program').attr('aria-selected','true');
            $('#menu_social_rehab_program').parent().addClass('active');
            $('#sub_menu_social_rehab_program').css('display','block');
            $('#tab_specific_case').attr('aria-selected','true');
            $('#tab_specific_case').parent().addClass('active');
        }
        else if(currLocation.indexOf('formalEducation') > -1){
            $('#menu_correction_education_program').attr('aria-selected','true');
            $('#menu_correction_education_program').parent().addClass('active');
            $('#sub_menu_correction_education_program').css('display','block');
            $('#tab_formal').attr('aria-selected','true');
            $('#tab_formal').parent().addClass('active');
        }
        else if(currLocation.indexOf('nonFormalEducation') > -1){
            $('#menu_correction_education_program').attr('aria-selected','true');
            $('#menu_correction_education_program').parent().addClass('active');
            $('#sub_menu_correction_education_program').css('display','block');
            $('#tab_non_formal').attr('aria-selected','true');
            $('#tab_non_formal').parent().addClass('active');
        }

       
    });
</script>                