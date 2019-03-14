<style>
.table.detail th
{
    text-align:left;
}
table.viewHalf label{display:inline-block;color: #A03230}
.span12.heading{padding-left:10px;}
</style>
<?php //echo '<pre>'; print_r($data); exit;?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Details</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php 
                        $uuid = $data['Prisoner']['uuid'];
                        $prisoner_id = $data['Prisoner']['id'];
                        echo $this->Html->link('Detection of recidivism',array('action'=>'criminalRecord/'.$uuid),array('escape'=>false,'class'=>'btn btn-success btn-mini pull-left')); ?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <div class="row-fluid">
                            <div id="commonheader">
                                <!-- <div class="prisoner-box">
                                    <div class="span2">
                                        <div class="text-left">
                                            <?php 
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
                                                else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE')){
                                                   $image = $this->Html->image('../files/prisnors/female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                                }else{
                                                    $image = $this->Html->image('../files/prisnors/male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                                }   
                                            }else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE')){
                                                $image = $this->Html->image('female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                            }else{
                                                $image = $this->Html->image('male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                            }
                                            echo $this->Html->link($image, array('controller'=>'prisoners', 'action'=>'details', $data["Prisoner"]["uuid"]), array('escape'=>false));   ?>   
                                        </div>
                                    </div>
                                    <div class="span5">
                                        <h4> <?php echo $data["Prisoner"]["prisoner_no"]?></h4>
                                        <h5><?php echo $data["Prisoner"]["personal_no"]?></h5>
                                        <?php if(isset($data["Prisoner"]["also_known_as"]) && ($data["Prisoner"]["also_known_as"] != ''))
                                        {?>
                                            <h5>Also known as : <?php echo substr($data["Prisoner"]["also_known_as"], 0, 10)?></h5>
                                        <?php }?>
                                        <h5>Gender : <?php echo $data["Gender"]["name"]?></h5>
                                        <h5>Date of Birth : <?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data["Prisoner"]["date_of_birth"]))?></h5>
                                        <h5>Place of Birth : <?php echo $data["Prisoner"]["place_of_birth"]?></h5>
                                    </div>
                                    <div class="span5">
                                        <h4>
                                            <p><?php echo $data["Prisoner"]["fullname"]?></p>
                                        </h4>
                                        <h5>Father Name : <?php echo $data["Prisoner"]["father_name"]?></h5>
                                        <h5>Mother Name : <?php echo $data["Prisoner"]["mother_name"]?></h5>
                                        <h5>Country : <?php echo $data["Country"]["name"]?></h5>
                                        <h5>District : <?php echo $data["District"]["name"]?></h5>
                                    </div>
                                </div>  -->
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
                                                                 echo date('d-m-y', strtotime($data['Prisoner']['doa']));
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
                                                             <?php if ($data['Prisoner']['occupation_id']!='') {
                                                             	
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
                                                </tr>


                                               	</tbody>
                                               </table>

                                             

                                            </tbody>
                                        </table>
                                          <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Id Proof Details : </h5>
			                                </div>
			                              </div>
			                               <?php if(count($data['IdProof'])>0){?>
			                              <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		<?php foreach($data['IdProof'] as $idproof)
                                                {?>
			                              		 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																ID Name :   
																</label>
                                                          <?php echo $idproof['Iddetail']['name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">ID Number :  </label>
                                                            <?php echo $idproof['id_number'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <?php } ?>
			                              		
			                              	</tbody>
			                              </table>
			                              <?php } else{
			                              	 echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';	
			                              }?>
			                                <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Kin Details</h5>
			                                </div>
			                              </div>
			                               <?php if(count($data['Kin'])>0){?>
			                                 <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		 <?php foreach($data['Kin'] as $kin)
                                                {?>
			                              		 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																First Name :   
																</label>
                                                            <?php echo $kin['first_name'];?>
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Middle Name :  </label>
                                                           <?php echo $kin['middle_name'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
															Surname :  
																</label>
                                                            <?php echo $kin['last_name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Relationship :  </label>
                                                           <?php echo $kin['Relationship']['name'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
															Sex : 	 
																</label>
                                                              <?php echo $funcall->getName($kin['gender_id'],"Gender","name");?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">National Id Number :  </label>
                                                           <?php echo $kin['national_id_no'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Physical Address : 
																</label>
                                                            <?php echo $kin['physical_address'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">District :  </label>
                                                           <?php echo $kin['District']['district_id'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Sub Country : 
																</label>
                                                            <?php echo $kin['gombolola'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Parish : </label>
                                                           <?php echo $kin['parish'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Village : 
																</label>
                                                           <?php echo $kin['village'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Name of LC 1 Chairperson : </label>
                                                            <?php echo $kin['chief_name'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Passport Non : 
																</label>
                                                           <?php echo $kin['passport_no'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Voter ID No : </label>
                                                            <?php echo $kin['voter_id_no'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <?php } ?>
			                              		
			                              	</tbody>
			                              </table>
			                              <?php }else{
			                              	 echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';
			                              } ?>
			                               <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Admitting Children</h5>
			                                </div>
			                              </div>
			                              <?php if(count($data['Child'])>0){?>
			                                 <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		 <?php foreach($data['Child'] as $Child)
                                                    {?>
			                              		 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Name Of Child
																</label>
                                                            <?php echo $Child['name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Father's Name : </label>
                                                          <?php echo $Child['father_name'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
															Mother's Name : 
																</label>
                                                           <?php echo $Child['mother_name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Relationship with child : </label>
                                                           <?php echo $Child['relation_with_child'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
															Date of Birth : 	 
																</label>
                                                            <?php echo date('d-m-Y',strtotime($Child['dob']));?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Place Of birth : </label>
                                                           <?php echo $Child['birth_place'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Gender : 
																</label>
                                                           <?php echo $Child['Gender']['name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Child Medical Condition </label>
                                                            <?php echo $Child['medical_cond'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Child Physical Condition
																</label>
                                                             <?php echo $Child['medical_cond'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Age of Child </label>
                                                            <?php echo $Child['child_age'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Child Description
																</label>
                                                            <?php echo $Child['child_desc'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Born in hospital</label>
                                                           <?php echo $Child['is_hospital_birth'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Hospital Name
																</label>hospital_name
                                                             <?php echo $Child['Hospital']['hospital_name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                   
                                                </tr>
                                                <?php } ?>
			                              		
			                              	</tbody>
			                              </table>
			                              <?php }else{
			                                echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';
			                              } ?>
			                               <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Special Needs</h5>
			                                </div>
			                              </div>
			                              <?php if(count($data['SpecialNeed'])>0){?>
			                                 <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		<?php foreach($data['SpecialNeed'] as $SpecialNeed)
                                                
                                                {?>
			                              		 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Prison Station	
																</label>
                                                            <?php if(isset($prison_name))echo $prison_name;?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Prisoner Number </label>
                                                           <?php if(isset($SpecialNeed['prisoner_no']))echo $SpecialNeed['prisoner_no'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
															Type of Disability
																</label>
                                                            <?php if(isset($SpecialNeed['Disability']['name']))echo $SpecialNeed['Disability']['name'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Subcategory Disability </label>
                                                           <?php if(isset($SpecialNeed['SpecialCondition']['name']))echo $SpecialNeed['SpecialCondition']['name'];?>
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                
			                              		
			                              	</tbody>
			                              </table>
			                              <?php }else{
			                              	 echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';
			                              } ?>
			                               <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Sentence Capture</h5>
			                                </div>
			                              </div>
			                              <?php if(count($data['PrisonerSentence'])>0){?>
			                                 <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		<?php foreach($data['PrisonerSentence'] as $PrisonerSentence)
                                                
                                                {?>

			                              		 <tr>
			                              		 	<tr>
			                              		 		<th colspan="3">Prisoner Details</th>
			                              		 	</tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Personal Number
																</label>
                                                            <?php echo $data['Prisoner']['personal_no'];?>
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Prisoner Number </label>
                                                            <?php if ($data['Prisoner']['prisoner_no']!='') {
                                                              echo $data['Prisoner']['prisoner_no'];
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
                                                            <label class="control-label">Class  </label>
                                                            <?php echo $data['class'];?>                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
			                              		 	<tr>
			                              		 		<th colspan="3">
															Offence Details
															</th>
			                              		 	</tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Case File
																</label>
                                                               <?php echo $PrisonerSentence['case_id'];?>     
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Offence</label>
                                                           <?php echo $PrisonerSentence['offence_id'];?>     
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                    
                                                </tr>
                                                <?php } ?>
                                               
                                               
                                                
			                              		
			                              	</tbody>
			                              </table>
			                              <?php }else{
			                              	echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';

			                              } ?>
			                               <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Appeal</h5>
			                                </div>
			                              </div>
			                               <?php if(count($data['PrisonerSentenceAppeal'])>0){?>
			                                 <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		<?php foreach($data['PrisonerSentenceAppeal'] as $PrisonerSentenceAppeal)
                                                
                                                {?>
			                              		<tr>
			                              			<th colspan="2">
			                              					Appeal
			                              			</th>
			                              		</tr>
			                              		 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																File no	
																</label>
                                                          <?php echo $PrisonerSentenceAppeal['case_file_id'];?>     
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Count  </label>
                                                             <?php echo $PrisonerSentenceAppeal['offence_id'];?>     
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
															Type of appellant
																</label>
                                                             <?php echo $PrisonerSentenceAppeal['type_of_appeallant'];?>     
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Appeal Status </label>
                                                            <?php echo $PrisonerSentenceAppeal['appeal_status'];?>     
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                
			                              		
			                              	</tbody>
			                              </table>
			                              <?php }else{
			                              	echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';
			                              } ?>
			                               <div class="row-fluid">
			                                <div class="span12 heading">
			                                    <h5>Assign Ward</h5>
			                                </div>
			                              </div>
			                              <?php if(count($data['PrisonerWard'])>0){?>
			                                 <table class="table viewHalf detail table-bordered table-responsive">
			                              	<tbody>
			                              		<?php foreach($data['PrisonerWard'] as $PrisonerWard)
                                                
                                                {?>
			                              		 <tr>
                                                    <td>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
																Assigned Ward
																</label>
                                                             <?php echo $PrisonerWard['assigned_ward_id1'];?>    
                                                           
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div>
                                                        <div class="control-group">
                                                            <label class="control-label">Assigned Ward Cell  </label>
                                                             <?php echo $PrisonerWard['ward_cell_id1'];?>    
                                                           
                                                        </div>
                                                    </div>

                                                    </td>
                                                </tr>
                                                
                                                <?php } ?>
			                              		
			                              	</tbody>
			                              </table>
			                              <?php }else{
			                              		echo '<div class="row-fluid"><div class="span12 heading">...</div></div>';
			                              } ?>


                                       
<?php 
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