<style>
.table.detail th
{
    text-align:left;
}
table.viewHalf label{display:inline-block;color: #A03230}
.span12.heading{padding-left:10px;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Details</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php 
                        $prisoner_id = $data['Prisoner']['id'];
                        echo $this->Html->link('Back',array('action'=>'edit/'.$uuid),array('escape'=>false,'class'=>'btn btn-success btn-mini pull-left')); ?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <div class="row-fluid">
                            <div id="commonheader">
                            </div>
                            
                            <div class="row-fluid">
                                <div class="span12 heading">
                                    <h5>Personal Details</h5>
                                </div>
                            </div>
                           
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table viewHalf detail table-bordered table-responsive">
                                           
                                            <tbody>
                                                <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">First Name<?php  ?> :</label>
                                                            <?php if ($data['Prisoner']['first_name']!='') {
                                                               echo $data['Prisoner']['first_name'];
                                                            } else{echo Configure::read('NA');} ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Middle Name<?php  ?> :</label>
                                                            <?php if ($data['Prisoner']['middle_name']!=''){
                                                               echo $data['Prisoner']['middle_name'];
                                                            }else{
                                                                echo Configure::read('NA');
                                                            }
                                                            
                                                                

                                                            
                                                            ?>
                                                            
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Surname Name<?php  ?> :</label>
                                                            <?php if ($data['Prisoner']['last_name']!=''){echo $data['Prisoner']['last_name'];
                                                            }else{ echo Configure::read('NA');} ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Also Known As <?php  ?> :</label>
                                                            <?php if ($data['Prisoner']['also_known_as']=='') {
                                                            	echo Configure::read('NA');
                                                            	
                                                            } echo $data['Prisoner']['also_known_as']; ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Father's Name<?php  ?> :</label>
                                                            <?php if ($data['Prisoner']['father_name']!='') {
                                                            	echo $data['Prisoner']['father_name'];
                                                            	}else{ echo Configure::read('NA');}  ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Mother's Name <?php  ?> :</label>
                                                            <?php if ($data['Prisoner']['mother_name']!='') {
                                                                echo $data['Prisoner']['mother_name'];
                                                            }else{
                                                                echo Configure::read('NA');
                                                            } ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Date of Birth :</label>
                                                             <?php echo date('d-m-Y',strtotime($data['Prisoner']['date_of_birth']));?>

                                                             
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Prisoner Type <?php  ?> :</label>
                                                             <?php if ($data['Prisoner']['prisoner_type_id']!='') {
                                                                  echo $funcall->getName($data['Prisoner']['prisoner_type_id'],"PrisonerType","name");
                                                             } else{
                                                               echo Configure::read('NA');
                                                             } ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Require
                                                                    Ascertaining Age?</label>
                                                            <?php  if ($data['Prisoner']['suspect_on_age']==1) {
                                                               echo "Yes";
                                                            }else{echo "No";} ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                      <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Is a Refugee : </label>
                                                             <?php if ($data['Prisoner']['is_refugee']==1) {
                                                                 echo "Yes";
                                                             }else{echo "No";} ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                   
                                                      <td>
                                                   		<div class="span6">
                                                        	<div class="control-group">
                                                            <label class="control-label">DOA : </label>
                                                             <?php if ($data['Prisoner']['doa']!='') {
                                                                 echo date('d-m-Y', strtotime($data['Prisoner']['doa']));
                                                             }else{echo Configure::read('NA');} ?>
                                                           
                                                        	</div>
                                                		</div>
                                                    </td>

                                                </tr>
                                                
                                                <tr>
                                                	<th colspan="2">
														Place Of Birth
														</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <div d="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">District : </label>
                                                           <?php if ($data['Prisoner']['birth_district_id']!=''){echo $data['Prisoner']['birth_district_id'];
                                                       }else{echo Configure::read('NA');} ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">County : </label>
                                                              <?php if ($data['Prisoner']['county_id']!=''){echo $data['Prisoner']['county_id'];
                                                       }else{echo Configure::read('NA');} ?>
                                                          
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Sub County : </label>
                                                             <?php if ($data['Prisoner']['sub_county_id']!=''){echo $data['Prisoner']['sub_county_id'];
                                                       }else{echo Configure::read('NA');} ?>
                                                          
                                                          
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Parish : </label>
                                                             <?php if ($data['Prisoner']['parish_id']!=''){echo $data['Prisoner']['parish_id'];
                                                       }else{echo Configure::read('NA');} ?>
                                                         
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                	<td>
                                                		 <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Village : </label>
                                                            <?php 
                                                            if ($data['Prisoner']['village_id']) {
                                                            	 echo $data['Prisoner']['village_id'];
                                                            }else{
                                                            	echo Configure::read('NA');
                                                            }


                                                            ?>	
                                                           
                                                        </div>
                                                    </div>

                                                	</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                	<td>
                                                		
                                                	</td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <table class="table viewHalf detail table-bordered table-responsive">
                                                	<tbody>
                                                <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Is a Serving Member Of Forces ?
																</label>
                                                            <?php if($data['Prisoner']['is_smforce']==1){
                                                                echo "Yes";
                                                            } else{
                                                                echo "No";
                                                            } ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Sex : </label>

                                                            <?php echo $funcall->getName($data['Prisoner']['gender_id'],"Gender","name");?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                   <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Continent : 
																</label>
                                                                 <?php echo $funcall->getName($data['Prisoner']['continent_id'],"Continent","name");?>
                                                          
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Country Of Origin : </label>
                                                             <?php echo $funcall->getName($data['Prisoner']['country_id'],"Country","name");?>
                                                            
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Is the Prisoner a dual citizen?
																</label>
                                                            <?php if ($data['Prisoner']['is_dual_citizen']==1) {
                                                               echo "Yes";
                                                            }else{echo "No";}  ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Nationality : </label>
                                                         <?php echo $data['Prisoner']['nationality_name']; ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																District Of Origin : 
																</label>
                                                            <?php if ($data['Prisoner']['district_id']!='') {
                                                               echo $data['Prisoner']['district_id'];
                                                            } else{
                                                                echo Configure::read('NA');
                                                            } ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Tribe :</label>
                                                            <?php echo $funcall->getName($data['Prisoner']['tribe_id'],"Tribe","name");?>
                                                           
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Permanent Address : 
																</label>
                                                           <?php echo $data['Prisoner']['permanent_address']; ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Desired Districts Of Relese : </label>
                                                               <?php echo $funcall->getName($data['Prisoner']['desired_districts_relese'],"District","name");?>
                                                          
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Employment Type : 
																</label>
                                                                  <?php if ($data['Prisoner']['employment_type']!='') {
                                                                  	 echo $funcall->getName($data['Prisoner']['employment_type'],"Employment","name");
                                                                  
                                                                  } else{
                                                                  	echo Configure::read('NA');
                                                                  }
                                                                 ?>
                                                         
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Occupation at Arrest : </label>
                                                             <?php if ($data['Prisoner']['occupation_id']!=0) {
                                                             	
                                                             echo $funcall->getName($data['Prisoner']['occupation_id'],"Occupation","name");
                                                        	 }else{
                                                        	 	echo Configure::read('NA');
                                                        	 }


                                                             ?>
                                                           
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Level Of Education : 
																</label>
                                                                 <?php 
                                                                 if ($data['Prisoner']['level_of_education_id']!=''&& $data['Prisoner']['level_of_education_id']!=0) {
                                                                 	
                                                                 echo $funcall->getName($data['Prisoner']['level_of_education_id'],"LevelOfEducation","name");
                                                                }else{
                                                                	echo Configure::read('NA');
                                                                }?>
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Skill :</label>
                                                              <?php 
                                                              if ($data['Prisoner']['skill_id']!=0) {
                                                              	
                                                              echo $funcall->getName($data['Prisoner']['skill_id'],"Skill","name");
                                                               }else{
                                                               	echo Configure::read('NA');
                                                               }
                                                              ?>
                                                           
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Religion : 
																</label>
                                                                <?php 
                                                                if ($data['Prisoner']['apparent_religion_id']!=0) {
                                                                	
                                                                echo $funcall->getName($data['Prisoner']['apparent_religion_id'],"Religion","name");
                                                            	}else{
                                                            			echo Configure::read('NA');
                                                            	}
                                                                ?>
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Height  : </label>
                                                             <?php if ($data['Prisoner']['height_feet']!=0) {
                                                                  echo $data['Prisoner']['height_feet'];
                                                             }else{echo Configure::read('NA');} ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>


                                                  <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Build :   
																</label>
                                                              <?php if ($data['Prisoner']['build_id']!=0) {
                                                                echo $funcall->getName($data['Prisoner']['build_id'],"Build","name");
                                                              } else{
                                                                echo Configure::read('NA');
                                                              } ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Face :</label>
                                                             <?php if ($data['Prisoner']['face_id']!=0) {
                                                                 echo $funcall->getName($data['Prisoner']['face_id'],"Face","name");
                                                             }else{ echo Configure::read('NA');}  ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Eyes :  	 
																</label>
                                                             <?php if($data['Prisoner']['eyes_id']!=0){echo $funcall->getName($data['Prisoner']['eyes_id'],"Eyes","name");
                                                         }else{echo Configure::read('NA');} ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Mouth : </label>
                                                            <?php if($data['Prisoner']['mouth_id']!=0){echo $funcall->getName($data['Prisoner']['mouth_id'],"Mouth","name");
                                                         }else{echo Configure::read('NA');} ?>
                                                           
                                                           
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Speech :   
																</label>
                                                                  <?php if($data['Prisoner']['speech_id']!=0){echo $funcall->getName($data['Prisoner']['speech_id'],"Speech","name");
                                                         }else{echo Configure::read('NA');} ?>
																 
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Teeth : </label>
                                                            <?php if($data['Prisoner']['teeth_id']!=0){echo $funcall->getName($data['Prisoner']['teeth_id'],"Teeth","name");
                                                         }else{echo Configure::read('NA');} ?>
                                                                 
                                                            
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Lips :   
																</label>
                                                                  <?php if($data['Prisoner']['lips_id']!=0){echo $funcall->getName($data['Prisoner']['lips_id'],"Lips","name");
                                                         }else{echo Configure::read('NA');} ?>
                                                        
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Ears :</label>
                                                             <?php if($data['Prisoner']['ears_id']!=0){echo $funcall->getName($data['Prisoner']['ears_id'],"Ears","name");
                                                         }else{echo Configure::read('NA');} ?>
                                                           
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Hair :   
																</label>
                                                                  <?php if($data['Prisoner']['hairs_id']!=0){echo $funcall->getName($data['Prisoner']['hairs_id'],"Hair","name");
                                                                    }else{echo Configure::read('NA');} ?>
                                                           
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Marital Status : </label>
                                                             <?php 
                                                             if ($data['Prisoner']['marital_status_id']!=0) {
                                                             
                                                             echo $funcall->getName($data['Prisoner']['marital_status_id'],"MaritalStatus","name");
                                                         	}else{
                                                         		echo Configure::read('NA');
                                                         	}?>

                                                            
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>

                                               <table class="table viewHalf detail table-bordered table-responsive">
                                               	<tbody>
                                               		<tr>
                                               			 <th colspan="2">
																	Body Marks : 
																	</th>
                                               		</tr>
                                               		<tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Distinguish Marks :  
																</label>
                                                                  <?php if($data['Prisoner']['marks']!=''){echo $data['Prisoner']['marks'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                          
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Head Marks : </label>
                                                            <?php if($data['Prisoner']['head_marks']!=''){echo $data['Prisoner']['mahead_marksks'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Left Side Marks :   
																</label>
                                                                <?php if($data['Prisoner']['left_side_marks']!=''){echo $data['Prisoner']['left_side_marks'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                          
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Right Side Marks : </label>
                                                             <?php if($data['Prisoner']['right_side_marks']!=''){echo $data['Prisoner']['right_side_marks'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                         
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
                                                                Deformities :    
                                                                </label>
                                                                 <?php if($data['Prisoner']['deformities']!=''){echo $data['Prisoner']['deformities'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                          
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Habits :  </label>
                                                            <?php if($data['Prisoner']['habits']!=''){echo $data['Prisoner']['habits'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                            
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
                                                                Age On Admission :    
                                                                </label>
                                                           <?php echo $data['Prisoner']['age_on_admission']; ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Current Age :  </label>
                                                           <?php echo $data['Prisoner']['age']; ?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
                                                                Resident Address :    
                                                                </label>
                                                           <?php
                                                           if ($data['Prisoner']['resident_address']!='') {
                                                                
                                                           echo $data['Prisoner']['resident_address'];}
                                                           else{
                                                            echo Configure::read('NA');
                                                           }
                                                            ?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Description :  </label>
                                                             <?php if($data['Prisoner']['description']!=''){echo $data['Prisoner']['description'];
                                                                    }else{echo Configure::read('NA');} ?>
                                                       
                                                        </div>
                                                    </div>

                                                    </td>

                                               	</tbody>
                                               </table>

                                            </tbody>
                                        </table>
                                          


                                       
<?php 
$prisoner_id = $data['Prisoner']['id'];
$uuid = $data['Prisoner']['uuid'];
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
echo $this->Html->scriptBlock("
    jQuery(function($) {
        //common prisoner detail data
        showCommonHeader();

    });
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
");
?>