<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<?php 
//------------Code For Edit Functiobality--------------

if(isset($this->request->data['User']['id']) && $this->request->data['User']['id'] !=''){
//debug($this->request->data);
$utid = $this->request->data['User']['usertype_id'];
$pid = $this->request->data['User']['prison_id'];
//debug($pid);
$disabled = 'disabled';
}else{
  $disabled = 'notdisabled';  
}
if(isset($utid) && ($utid == 15 || $utid == 9)){

    $display1="display:none;";
    $display2="display:block;";
    $disabled1 = 'notdisabled';
    $false1='false';
    $false2='true';
}else{
    $display1="display:block;";
    $display2="display:none;";
    $disabled1 = 'disabled';
    $false1='true';
    $false2='false';
}
// if(isset($pid) && strpos($pid, ',') !== false){
   
//     $pid = explode(',', $pid);
// }
// else{
//     $pid = isset($this->request->data['User']['prison_id'])?$this->request->data['User']['prison_id']:'';
// }
if(isset($this->request->data['User']['prison_id']) && $this->request->data['User']['prison_id'] != ''){
    $this->request->data['User']['prison_id'] = explode(',', $this->request->data['User']['prison_id']);
}
//-----------------------------------------------
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New User</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Users List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('User',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <?php echo $this->Form->input('is_enable', array('type'=>'hidden','value'=>1))?>
                    <?php echo $this->Form->input('is_trash', array('type'=>'hidden','value'=>0))?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">First Name<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('first_name',array('div'=>false,'label'=>false,'class'=>'span11 alpha','type'=>'text','placeholder'=>'Enter First Name','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Last Name<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('last_name',array('div'=>false,'label'=>false,'class'=>'span11 alpha','type'=>'text','placeholder'=>'Enter First Name','required'));?>
                                </div>
                            </div>
                        </div> 
                        <div class="clearfix"></div>                       
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Mail Id<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('mail_id',array('div'=>false,'label'=>false,'class'=>'span11','type'=>'email','required','placeholder'=>'Enter Mail Id',));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">User Name<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('username',array('div'=>false,'label'=>false,'class'=>'span11','type'=>'text','placeholder'=>'Enter User Name','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Password<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('password',array('div'=>false,'label'=>false,'class'=>'span11','type'=>'password','placeholder'=>'Enter Password','required'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">User Type<?php echo $req; ?></label>
                                <div class="controls">
                                    <?php echo $this->Form->input('usertype_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$usertypeList,'empty'=>'', 'required','onChange'=>'getGatekeperData(this.value)',$disabled));?>
                                </div>
                            </div>
                        </div> 
                        <div class="clearfix"></div>                       
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Designation<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('designation_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$designationList,'empty'=>'','required'));?>
                                </div>
                            </div>
                        </div> 
                          
                       
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Region :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('state_id',array('id'=>'state_id', 'div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$stateList,'empty'=>'', 'onchange'=>'javascript:getDistrict();'));?>
                                </div>
                            </div>
                        </div> 
                         <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">District :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('district_id',array('id'=>'district_id', 'div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$districtList,'empty'=>'',));?>
                                </div>
                            </div>
                        </div>   
                         
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Department :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('department_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$departmentList,'empty'=>'',));?>
                                </div>
                            </div>
                        </div>    
                        <div class="clearfix"></div>     
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Mobile No.:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('mobile_no',array('div'=>false,'label'=>false,'class'=>'span11 mobile','type'=>'text','placeholder'=>'Enter Mobile No.','required'=>false,));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6" id="prison_div" style="<?php echo $display1;?>">
                            <div class="control-group">
                                <label class="control-label">Prison<?php echo $req; ?> :</label>
                                <div class="controls"">
                                    <?php echo $this->Form->input('prison_id',array('id'=>'prison_id1', 'div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$prisonList,'empty'=>'','required'=>$false1));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6" id="compound_div">
                            <div class="control-group">
                                <label class="control-label">Compounds<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('compound_id',array('id'=>'compound_id', 'div'=>false,'label'=>false,'class'=>'span11 pmis_select','options'=>$compoundList,'empty'=>'','required'=>$false1));?>
                                </div>
                                
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Force Number <?php echo $req; ?>:</label>
                                <div class="controls">
                                  <?php
                                 
                                      echo $this->Form->input('force_number',array(
                                        'div'=>false,
                                        'label'=>false,
                                        'class'=>'span11',
                                        'placeholder'=>'Enter Force Number ',
                                        'type'=>'text',
                                        'required'=>true
                                      ));
                                   ?>
                                </div>
                          </div>
                        </div>
                    </div>           
                    <div class="form-actions" align="center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'users','action'=>'getDistrict'));
echo $this->Html->scriptBlock("
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
<script type="text/javascript">
    $(document).ready(function(){
         // getGatekeperData(15);
         <?php
         if(isset($this->data['User']['usertype_id']) && $this->data['User']['usertype_id']==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            ?>
            $("#compound_div").show();
            $("#prison_div").hide();
            $('#prison_id1').removeAttr('required','required');
            $('#compound_id').attr('required','required');
            <?php
         }
         ?>
         
    });
    $(function(){
        $("#UserAddForm").validate({
        });
    }); 
    $(document).on('change',"#UserUsertypeId", function () {
        if($(this).val()==15){
            $("#compound_div").show();
            $("#prison_div").hide();
            $('#prison_id1').removeAttr('required','required');
            $('#compound_id').attr('required','required');
        }else if($(this).val()==9){
            $("#compound_div").hide();
            $("#prison_div").hide();
            $('#prison_id1').removeAttr('required','required');
            $('#compound_id').removeAttr('required','required');
        }
        else{
            $("#compound_div").hide();
            $("#prison_div").show();
            $('#prison_id1').attr('required','required');
            $('#compound_id').removeAttr('required','required');
        }
    })
    
    function getGatekeperData(id) 
{ 
    var user_id = <?php echo isset($this->request->data['User']['id']) &&  $this->request->data['User']['id'] != ''?$this->request->data['User']['id']:0?>;
    
    if(id == 15)
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'users','action'=>'getGatekeperData'));?>';
    
        $.post(strURL,{"id":id,"user_id":user_id},function(data){  
            $('#prison_id2').html(data);

            
            
        });
    }
}
</script>