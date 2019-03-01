<?php
    $data = $this->request->data;
    $showProhibited = false;
    if(isset($data['Propertyitem']['id'])){
        $isAllowed = 'Yes';
        $isProhibited ='No';
        $propertyType='';

            if($data['Propertyitem']['is_allowed'] == 1){
              $isAllowed = 'Yes';
              $isProhibited ='No';
              $propertyType='';
            }else if($data['Propertyitem']['is_prohibited'] == 1){
              $isAllowed = 'No';
              $isProhibited = 'Yes';
              $propertyType=$data['Propertyitem']['property_type_prohibited'];

            }
        

        if( $isAllowed == 'Yes'){
            $this->request->data['Propertyitem']['is_provided'] = 'Allowed';
        }else if($isProhibited == 'Yes'){
            $this->request->data['Propertyitem']['is_provided'] = 'Prohibited';
            $showProhibited = true;
        }


    }
    
    //debug($this->request->data);
 ?>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Property Item</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Property Item List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Propertyitem',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">     
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Property Item<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Property Item','class'=>'form-control','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Is Enabled ?</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row adminOnly">     
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prohibited property <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                <div class="controls uradioBtn">
                                   <?php 
                                   $Prohibited = array('Allowed'=>'Allowed items','Prohibited'=>'Prohibited items');
                                   echo $this->Form->radio('is_provided', $Prohibited,array("legend"=>false,'class'=>'verification_type radio','required'=>true,'onclick'=>'getPropertyType(this.value)'));?>
                                   <div style="clear:both;"></div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group prohibited" style="display:none;">
                                <label class="control-label">Property Type:</label>
                                <div class="controls">
                                    <?php 
                                    $nokList1=array('Destroyed' => 'Destroyed','In Store' => 'In Store');
                                    echo $this->Form->input('property_type_prohibited',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$nokList1, 'empty'=>'-- Select property type --','required'=>false,'onchange'=>'getwitness(this.value)'));?>
                                </div>
                            </div> 
                        </div>
                        <!-- <div class="span6">
                            <div class="control-group">
                                    <label class="control-label">Is Prohibited Item <?php echo MANDATORY; ?> :</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('is_prohibited',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>0,));?>
                                </div>
                                
                            </div>
                        </div> -->
                        
                    </div>

                    <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
     <?php  if(($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') ) ){ ?>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.adminOnly').hide();
            });
        </script>

        <?php
            }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data['Propertyitem']['added_by_recep'] == 0){ ?>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.adminOnly').hide();
            });
        </script>
        <?php
            }
        ?>
<?php if($showProhibited){ ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.prohibited').show();
            });
        </script>
<?php }    ?>
<script type="text/javascript">
    $(document).ready(function(){


        $('#PropertyitemIsAllowed').on('change',function(){
            var res = $(this).val();
            if(res == 1){
                $('#PropertyitemIsProhibited').val(0).change();

            }else{
                $('#PropertyitemIsProhibited').val(1).change();
            }
        });

        $('#PropertyitemIsProhibited').on('change',function(){
            var res = $(this).val();
            if(res == 1){
                $('#PropertyitemIsAllowed').val(0);
            }else{
                $('#PropertyitemIsAllowed').val(1);
            }
        });


    });

function getPropertyType(val){
    if(val=='Prohibited'){
        $('.prohibited').show();
        $('#PropertyitemPropertyTypeProhibited').attr('required','required');
      
    }else if(val=='Allowed'){
        $('.prohibited').hide();
        $('#PropertyitemPropertyTypeProhibited').removeAttr('required');

    }

}


  $(function(){
    $("#PropertyitemAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Propertyitem][name]': {
                    required: true,
                },
           },
            messages: {
                'data[Propertyitem][name]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
  </script>
<!-- <script type="text/javascript">
function validateForm(){
    var errcount = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            errcount++;
            $(this).addClass('error-text');
            $(this).removeClass('success-text'); 
        }else{
            $(this).removeClass('error-text');
            $(this).addClass('success-text'); 
        }        
    });        
    if(errcount == 0){            
        if(confirm('Are you sure want to save?')){  
            return true;            
        }else{               
            return false;           
        }        
    }else{   
        return false;
    }  
}
</script>
 -->