    
    <?php
    $i = 0;
    if(isset($kinData) && is_array($kinData) && count($kinData)>0){
    foreach($kinData as $kin)
        //debug($kin);
    {?>

                    <div class="entry33 input-group row uradioBtn">
                        <div class="" style="margin-left: 50px;">
                            <div class="span3">
                                 <label class="control-label" style="text-align: left;">First Name<span style="color:red;">*</span></label>
                                <?php echo $this->Form->input('VisitorName.'.$i.'.name',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text', 'placeholder'=>'Visitor First Name','required','style'=>'','value'=>$kin['PrisonerKinDetail']["first_name"],'title'=>'Please Enter Visitor name'));?>
                            </div>
                            <div class="span3">
                                 <label class="control-label" style="text-align: left;">Middle Name<span style="color:red;"></span></label>
                                <?php echo $this->Form->input('VisitorName.'.$i.'.mname',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text', 'placeholder'=>'Visitor Middle Name','required'=>false,'style'=>'','value'=>$kin['PrisonerKinDetail']["middle_name"]));?>
                            </div>
                            <div class="span3">
                             <label class="control-label" style="text-align: left;">Last Name<span style="color:red;"></span></label>
                                <?php echo $this->Form->input('VisitorName.'.$i.'.lname',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text', 'placeholder'=>'Visitor Last Name','required'=>false,'style'=>'','value'=>$kin['PrisonerKinDetail']["last_name"]));?>
                            </div>
                            <div class="span3 relationship_div">
                            <label class="control-label" style="text-align: left;">Relationship<span style="color:red;">*</span></label>
                                 <?php echo $this->Form->input('VisitorName.'.$i.'.relation', array('type'=>'hidden','value'=>$kin['PrisonerKinDetail']["relationship"]))?>
                                  <?php echo $this->Form->input('VisitorName.'.$i.'.relation',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$relation,'required'=>false,'default'=>$kin['PrisonerKinDetail']["relationship"]));?>
                            </div>
                        </div>
                        <div class="" style="margin-left: 50px;">
                            <div class="span3">
                             <label class="control-label" style="text-align: left;">Photo<span style="color:red;"></span></label>
                                <?php echo $this->Form->input('VisitorName.'.$i.'.photo',array('div'=>false,'label'=>false,'class'=>'form-control photo','style'=>'width:90%;border:1px solid #ccc;','type'=>'file','required'=>false,'title'=>'Please choose visitor photo'));?>
                            </div>
                            
                             <div class="span3">
                              <label class="control-label" style="text-align: left;">Nat.ID Type<span style="color:red;">*</span></label>
                                <?php echo $this->Form->input('VisitorName.'.$i.'.nat_id_type',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$natIdList,'required','onChange'=>'selectNatId(this.value)','title'=>'Please select national id type'));?>
                            </div>
                            <div class="span3">
                             <label class="control-label" style="text-align: left;">Nat.ID No<span style="color:red;">*</span></label>
                                <?php echo $this->Form->input('VisitorName.'.$i.'.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control alphanumeri','type'=>'text','required', 'placeholder'=>'Visitor Nat.Id No.','style'=>''));?>
                            </div>
                            <div class="span3" style="margin-top: 40px;">
                        
                            <?php if($i == 0)
                            {?>

                                <!-- <span class="input-group-btn">
                                    <button class="btn btn-success btn-addss" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                        <span class="icon icon-plus"></span>
                                    </button>
                                </span> -->
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-removes" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                        <span class="icon icon-minus"></span>
                                    </button>
                                </span>
                            <?php }
                            else 
                            {?>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-removes" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                        <span class="icon icon-minus"></span>
                                    </button>
                                </span>
                            <?php }?>
                            </div>
                        </div>
                        </div>
                        <?php
                        $i++;
                            }
                        }else{
                            ?>
                            <div class="entry33 input-group row uradioBtn">
                            <div class="" style="margin-left: 50px;">
                                <div class="span3">
                                <label class="control-label" style="text-align: left;">First Name<span style="color:red;">*</span></label>
                                    <?php echo $this->Form->input('VisitorName.0.name',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text','style'=>'','id'=>'private_visitor_name', 'placeholder'=>'Visitor First Name','required','title'=>'Please enter visitor name','title'=>'Please Enter Visitor name'));?>
                                </div>
                                <div class="span3">
                                <label class="control-label" style="text-align: left;">Middle Name<span style="color:red;"></span></label>
                                    <?php echo $this->Form->input('VisitorName.0.mname',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text','style'=>'', 'placeholder'=>'Visitor Middle Name','required'=>false,'title'=>'Please enter visitor name'));?>
                                </div>
                                <div class="span3">
                                <label class="control-label" style="text-align: left;">Last Name<span style="color:red;"></span></label>
                                    <?php echo $this->Form->input('VisitorName.0.lname',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text','style'=>'', 'placeholder'=>'Visitor Last Name','required'=>false,'title'=>'Please enter visitor name'));?>
                                </div>
                                <div class="span3 relationship_div">
                                 <label class="control-label" style="text-align: left;">Relationship<span style="color:red;">*</span></label>
                                    <?php echo $this->Form->input('VisitorName.0.relation',array('div'=>false,'label'=>false,'class'=>'form-control relation','type'=>'select','empty'=>'--Select--','options'=>$relation,'required'=>false,'title'=>'Please select visitor relation'));?>
                                </div>
                            </div>
                            <div class="" style="margin-left: 50px;">
                                <div class="span3">
                                 <label class="control-label" style="text-align: left;">Photo<span style="color:red;"></span></label>
                                    <?php echo $this->Form->input('VisitorName.0.photo',array('div'=>false,'label'=>false,'class'=>'form-control photo','type'=>'file','style'=>'width: 90%;border1px solid #ccc;','required'=>false,'title'=>'Please choose visitor photo'));?>
                                </div>
                                
                                <div class="span3">
                                 <label class="control-label" style="text-align: left;">Nat.ID Type<span style="color:red;">*</span></label>
                                <?php echo $this->Form->input('VisitorName.0.nat_id_type',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$natIdList,'onChange'=>'selectNatId(this.value)','required','title'=>'Please select national id type'));?>
                                </div>
                                <div class="span3">
                                <label class="control-label" style="text-align: left;">Nat.ID No<span style="color:red;">*</span></label>
                                        <?php echo $this->Form->input('VisitorName.0.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'National Id No.','required','title'=>'Please enter visitor nat.id no'));?>
                                </div>
                                    
                                   
                                <span class="span3 input-group-btn" style="margin-top: 40px;">
                                    <button class="btn btn-success btn-addss" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                        <span class="icon icon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div> 
                            <?php
                        }
                        ?>
