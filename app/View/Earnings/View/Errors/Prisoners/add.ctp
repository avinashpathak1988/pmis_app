<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
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
                    <div class="">
                        <ul class="nav nav-tabs">
                            <li><a href="#personal_info">Personal Information</a></li>
                            <li><a href="#id_proof_details">ID Proof Details</a></li>
                            <li><a href="#kin_details">Kin Details</a></li>
                            <li><a href="#child_details">Child Details</a></li>
                            <li><a href="#admission_details">Admission Details</a></li>
                            <li><a href="#special_needs">Special Needs</a></li>
                            <li><a href="#offence_details">Offence Details</a></li>
                            <li><a href="#offence_counts">Offence Counts Details</a></li>
                            <li><a href="#recaptured_details">Recaptured Details</a></li>
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="tabscontent">
                            <div id="personal_info">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">First Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter First Name','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Surname<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Surname','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Father's Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Father's Name",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Mother's Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('mother_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Mother's Name",'required'=>false,'id'=>'mother_name'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Birth','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Place Of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('place_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Place Of Birth','required','id'=>'place_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>                                                                     
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Gender<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$genderList, 'empty'=>'-- Select Gender --','required','id'=>'gender'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Country<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$countryList, 'empty'=>'-- Select Country --','required','id'=>'country_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Nationality :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Nationality','required'=>false,'id'=>'nationality_name','readonly'=>'readonly'));?>
                                            </div>
                                        </div>
                                    </div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">District:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$district_id, 'empty'=>'-- Select District --','required'=>false,'id'=>'district_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Tribe<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('tribe_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$tribeList, 'empty'=>'-- Select Tribe --','required','id'=>'tribe_id'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Photo<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'photo'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div>  

                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Permanent Address :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('permanent_address',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter permanent address','id'=>'permanent_address','rows'=>3,'required'=>false));?>
                                            </div>
                                        </div>
                                    </div>                           
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="id_proof_details" class="lorem">
                                <?php echo $this->Form->create('PrisonerIdDetail',array('class'=>'form-horizontal','url' => '/prisoners/prisnorsIdInfo'));?>
                                <?php  echo $this->Form->input('prisoner_id',array(
                                        'type'=>'hidden',
                                        'class'=>'prisoner_id'
                                      ));

                                echo $this->Form->input('count',array(
                                        'type'=>'hidden',
                                        'class'=>'count',
                                        'id'=>'count',
                                        'value'=>1
                                      ));
                                ?>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    <div class="span12">
                                            <table id="myTable_id" class=" table order-list table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Id Proof Name</th>
                                                            <th>Id Proof Number</th>
                                                            <th width="12px"><i class="icon icon-plus-sign add_on_ico" id="addrow_for_id"></i></th> 
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                            <?php
                                                            
                                                                 echo $this->Form->input('id_name.',array(
                                                                     'div'=>false,
                                                                     'label'=>false,
                                                                     'options'=>$id_name,
                                                                     'empty'=>'-- Select Id --',
                                                                     'required',
                                                                     'style'=>'width:200px',
                                                                   ));
                                                           ?>
                                                               
                                                            </td>
                                                            <td>
                                                               <?php
                                                                   echo $this->Form->input('id_number.',array(
                                                                     'div'=>false,
                                                                     'label'=>false,
                                                                     'type'=>'text',
                                                                     'required',

                                                                   ));
                                                                ?>
                                                            </td>
                                                            
                                                            <td></td>
                                                            
                                                        </tr>
                                                    </tbody>
                                            </table>        
                                    </div>
                                    
                                    <div class="clearfix"></div>  
                                </div>
                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn_iddetail" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="kin_details">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">First Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter First Name','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Surname<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Surname','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Relationship<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Relationship",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Gender<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$genderList, 'empty'=>'-- Select Gender --','required','id'=>'gender'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="clearfix"></div>                                        <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Phone Number :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Phone Number','required'=>false,'id'=>'phone_no','readonly'=>'readonly'));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Village<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$countryList, 'empty'=>'-- Select Village --','required','id'=>'country_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Parish<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('country_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$countryList, 'empty'=>'-- Select Parish --','required','id'=>'country_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">District:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$district_id, 'empty'=>'-- Select District --','required'=>false,'id'=>'district_id'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Name Of Chief:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Name of chief','required'=>false,'id'=>'phone_no','readonly'=>'readonly'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <div class="clearfix"></div>  

                                                          
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="child_details">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prisoner Number','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Name Of Child <?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Birth','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Place Of Birth<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('place_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Place Of Birth','required','id'=>'place_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Father's Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Father's name",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Gender<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('gender',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$genderList, 'empty'=>'-- Select Gender --','required','id'=>'gender'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Child Medical<br/> Condition :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Child Medical Condition','required'=>false,'id'=>'phone_no','readonly'=>'readonly'));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date of Handover:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Handover','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Name Of Person<br/> Receiving:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Father's name",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Address of person receiving :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Address of person receiving','required'=>false,'id'=>'phone_no','readonly'=>'readonly'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="clearfix"></div>                      
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                             <div id="admission_details">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Personal Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Personal Number','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_number',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prison Station<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Prison Station','required','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Offence<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Offence','required','id'=>'place_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Section Of Law<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Section Of Law",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court File No<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Court File No",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Case File No.<br/> Condition :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'CASE FILE No.','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">C.R.B No.:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Court",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">District where offence was committed :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'District where offence was committed','required'=>false,'id'=>'phone_no'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">No of Previous <br/>Conviction:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter No of Previous Conviction",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Committal:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Committal','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>  
                                     <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Sentence:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Sentence','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Conviction:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Conviction','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="clearfix"></div> 
                                   <h4 class="text-center"> Sentence Details</h4>
                                   <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Years:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Years','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Months:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Months','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  

                                   <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Days:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Days','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">No Of Strokes:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter No Of Strokes','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">E.M No. of days:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter E.M No. of days','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Class:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Class','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Fine (Amount) With Imprisonment:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Fine (Amount)','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Fine (Amount):</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Fine (Amount)','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Receipt Number :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Receipt Number ','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>    
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="special_needs">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prison Station<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prison Station','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_number',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Type Of Disability :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Type Of Disability','required','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>   
                                    
                                    <div class="clearfix"></div> 
                                        
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="offence_details">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Personal Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Personal Number','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Offence<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Offence','required','id'=>'place_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Section Of Law<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Section Of Law",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court File No<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Court File No",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="clearfix"></div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Case File No.<br/> Condition :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'CASE FILE No.','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">C.R.B No.:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Court",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">District where offence was committed :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'District where offence was committed','required'=>false,'id'=>'phone_no'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">No of Previous <br/>Conviction:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('father_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter No of Previous Conviction",'required','id'=>'father_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Committal:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Committal','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>
                                    </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="offence_counts">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Offence ID<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Offence','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Count Description<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('prisoner_number',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Count Description','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Committal:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Committal','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                     <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Sentence:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Sentence','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>  
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Conviction:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Conviction','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Requires Confirmation :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Requires Confirmation ','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>     
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Waiting For Confirmation :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Prisoner Waiting For Confirmation ','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="span6">
                                         <div class="control-group">
                                            <label class="control-label">Date of Confirmation:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Confirmation','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>     
                                    <div class="clearfix"></div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Appealed Against sentence:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Appealed Against sentence','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Appeal status:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Appeal status','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  
                                   <div class="clearfix"></div> 
                                   <div class="span6">
                                         <div class="control-group">
                                            <label class="control-label">Date of Dismissal of Appeal:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Dismissal of Appeal','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>     
                                    <div class="clearfix"></div>
                                   <h4 class="text-center"> Sentence Details</h4>
                                   <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Years:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Years','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Months:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Months','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  

                                   <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Days:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Days','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">No Of Strokes:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter No Of Strokes','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">E.M No. of days:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter E.M No. of days','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Class:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Class','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Fine (Amount) With Imprisonment:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Fine (Amount)','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>                             
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Fine (Amount):</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Fine (Amount)','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Receipt Number :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Receipt Number ','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>    
                                
                                
                                 <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court where sentence was awarded:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->text('nationality_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Court where sentence was awarded','required'=>false,'id'=>'phone_no',));?>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="clearfix"></div>                           
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Sections of Law:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('CRBNo',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Sections of Law','required','id'=>'CRBNo'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>   
                                    </div> 
                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                            <div id="recaptured_details">
                                <?php echo $this->Form->create('Prisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <div class="row" style="padding-bottom: 14px;">
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_number',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'last_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Surname<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Surname','id'=>'first_name'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Escape:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Escape','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                     <div class="span6">
                                       <div class="control-group">
                                            <label class="control-label">Date of Recapture:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_birth',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Recapture','required','readonly'=>'readonly','id'=>'date_of_birth'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div> 
                                        
                                </div>

                                <div class="form-actions" align="center">
                                    <button type="button" tabcls="next" id="saveBtn" class="btn btn-success">Save</button>
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                             <!--<div id="tab-3" class="lorem">
                             <?php //echo $this->Form->create('RelationshipDetail',array('class'=>'form-horizontal','url' => '/prisoners/prisnorsIdInfo'));?>
                              <?php //echo $this->Form->end();?>
                             </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function removeDataId(id,model){
         if(id == 0){
            $("#row_delete_id"+model).closest("tr").remove();
         }else{
            var url = "<?php echo $this->Html->url(array('controller'=>'Prisoners','action'=>'removeDataId'))?>";
            if(confirm('Are you sure want to delete?')){
                $.post(url,{'id':id,'model':model},function(res){ 
                    if(res.trim()=="SUCC"){
                        $("#row_delete_id"+id).closest("tr").remove();
                    }else{
                        alert('Problem in delete');
                    }             
                });     
            }
         }
    }
var select_var='<?php echo $idselct;?>';

$(document).ready(function () {
  $("#addrow_for_id").on("click", function () {
            var counter = $('#myTable_id >tbody >tr').length;

            var newRow = $("<tr>");
            var cols = "";
            cols += '<td>'+select_var+'</td>';
            cols += '<td><input id="PrisonerIdNumber" type="text" required="required" name="data[PrisonerIdDetail][id_number][]"></td>';

            //cols += '<td><input type="file" name="data[Route]['+counter+'][route_info]"/></td>';

            cols += '<td><a id="row_delete_id'+counter+'" onclick="removeDataId(0,'+counter+')"><i class="icon icon-trash delete_row"></i></a></td>';
           
            //cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " id="row_delete_route'+counter+'" onclick="removeDataRoute(0,'+counter+')"  value="Delete"></td>';
            newRow.append(cols);
            $("#myTable_id").append(newRow);
            counter++;
            $('#count').val(counter);
            $('.selectbox').select2();
    });
});
$(document).on('change', '#country_id', function() {
  var country_id=$(this).val();
   $.ajax(
    {
        type: "POST",
        dataType: "json",
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
          $("#nationality_name").val(data.nationality_name);
        },
        
    });
});  
$(function(){
    $("#PrisonerAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Prisoner][first_name]': {
                    required: true,
                },
                'data[Prisoner][last_name]': {
                    required: true,
                },
                'data[Prisoner][father_name]': {
                    required: true,
                },
                'data[Prisoner][mother_name]': {
                    required: true,
                },
                'data[Prisoner][date_of_birth]': {
                    required: true,
                },
                'data[Prisoner][place_of_birth]': {
                    required: true,
                },
                'data[Prisoner][gender]': {
                    required: true,
                },
                'data[Prisoner][country_id]': {
                    required: true,
                },
                'data[Prisoner][tribe_id]': {
                    required: true,
                },
                'data[Prisoner][photo]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Prisoner][first_name]': {
                    required: "Please enter first name.",
                },
                'data[Prisoner][last_name]': {
                    required: "Please enter last name.",
                },
                'data[Prisoner][father_name]': {
                    required: "Please enter father name.",
                },
                'data[Prisoner][mother_name]': {
                    required: "Please enter mother name.",
                },
                'data[Prisoner][date_of_birth]': {
                    required: "Please choose date of birth.",
                },
                'data[Prisoner][place_of_birth]': {
                    required: "Please enter place of birth.",
                },
                'data[Prisoner][gender]': {
                    required: "Please select gender.",
                },
                'data[Prisoner][country_id]': {
                    required: "Please select country.",
                },
                'data[Prisoner][tribe_id]': {
                    required: "Please select tribe.",
                },
                'data[Prisoner][photo]': {
                    required: "Please choose photo.",
                },
            },
               
    });
  });

$(document).on('click',"#saveBtn_iddetail", function () { // button name
    tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
    var action = $(this).attr('tabcls');
    $("#PrisonerIdDetailAddForm").ajaxForm({ //form name
        beforeSend: function(){
            // $.blockUI({ message: '<h1> Just a moment...</h1>' });
            // $("#submit").html('');
            // $("#submit").html('loading...');
        },
        success: function(html){
            tabs[action]();
            e.preventDefault();
        },
    }).submit();
});
</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
$userinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'personalInfo'));
$idinfoajaxUrl=$this->Html->url(array('controller'=>'prisoners','action'=>'prisnorsIdInfo'));
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
        $('#saveBtn').on('click', function(e){
            if(!$('#PrisonerAddForm').valid())
             {
                return false;
              }
            var photo = $('#photo').prop('files')[0];
            var first_name = $('#first_name').val();  
            var last_name= $('#last_name').val();
            var father_name= $('#father_name').val();
            var mother_name= $('#mother_name').val();
            var date_of_birth= $('#date_of_birth').val();
            var place_of_birth= $('#place_of_birth').val();
            var gender= $('#gender').val();
            var country_id= $('#country_id').val();
            var permanent_address=$('#permanent_address').val();

            var form_data = new FormData();                  
            form_data.append('photo', photo);
            form_data.append('first_name', first_name); 
            form_data.append('last_name', last_name);
            form_data.append('father_name', father_name);
            form_data.append('mother_name', mother_name);                          
            form_data.append('date_of_birth', date_of_birth);
            form_data.append('place_of_birth', place_of_birth);
            form_data.append('gender', gender);
            form_data.append('country_id', country_id);
            form_data.append('permanent_address', permanent_address);
            
            var action = $(this).attr('tabcls');
            var prisnor_id_set=$('.prisoner_id');
            $.ajax({
                        url: '".$userinfoajaxUrl."', // point to server-side PHP script 
                        dataType: 'json',  // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,                         
                        type: 'post',
                        success: function(data){
                            if(data.success=='success'){
                                prisnor_id_set.val(data.prisnor_id_set);
                                tabs[action]();
                                e.preventDefault();
                            }
                            else{
                                showJsonErrors(data.success);
                            }
                        }
             });
            
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
",array('inline'=>false));
?> 