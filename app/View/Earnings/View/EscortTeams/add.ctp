<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Escort Team</h5>
                    <div style="float:right;padding-top: 6px;">
                        <?php echo $this->Html->link(__('Escort Team List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-primary btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('EscortTeam',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                    <div class="span6" style="display: <?php echo ($this->Session->read('Auth.User.prison_id')!='') ? 'none': 'block'; ?>;">
                            <div class="control-group">
                                <label class="control-label">Prison <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prison_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$prisonList, 'empty'=>'--Select--','onchange'=>"showMembers(this.value)","default"=>$this->Session->read('Auth.User.prison_id')));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                                           
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Escort Team Name  <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Escort Team name','class'=>'form-control','required','title'=>"Please provide team name"));?>
                                </div>
                            </div>
                        </div>    
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Escort Type  <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('escort_type', array('type'=>'select','label'=>false,'required'=>true,'empty'=>"--select type--" ,'options' => array("Transfer"=>"Transfer","Hospital"=>"Hospital","Court"=>"Court","Labour party"=>"Labour party","Dicharge"=>"Dicharge","Lodger Out"=>"Lodger Out"),"title"=>"Please select Escort Type")); ?>
                                </div>
                            </div>
                        </div>               
                    </div>
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Incharge of escort team  <?php echo MANDATORY; ?> :</label>
                                <div class="controls" id="escortinchargeteam">
                                    <?php 
                                        echo $this->Form->input('EscortTeam.incharge', array('type'=>'select','label'=>false,'required'=>true,'empty'=>'--select--','options' => $escortingOfficerList,"title"=>"Please select members",'onchange'=>'getEscortTeam(this.value)')); //, 'selected' => $selected
                                    ?>
                                </div>
                                <!-- <label for="data[EscortTeam][members][]" generated="true" id="member_error" class="error" style="display:none;">Please select atleast one member</label> -->
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Escort Team Member  <?php echo MANDATORY; ?> :</label>
                                <div class="controls" id="escortteam">
                                    Please select prison name
                                </div>
                                <!-- <label for="data[EscortTeam][members][]" generated="true" id="member_error" class="error" style="display:none;">Please select atleast one member</label> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Is Enabled ?</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'form-control','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','onclick'=>"test()",'formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl      = $this->Html->url(array('controller'=>'EscortTeams','action'=>'members'));
echo $this->Html->scriptBlock("   

    function showMembers(value,selected){
        var url = '".$ajaxUrl."';
        url = url + '/prison_id:' + value;
        url = url + '/selected:' + selected;
        $.post(url, {}, function(res) {
            if (res) {
                $('#escortteam').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  

<script type="text/javascript">
    $(document).ready(function(){
        <?php
        if(isset($this->data['EscortTeam']['prison_id']) && $this->data['EscortTeam']['prison_id']!=''){
            ?>
            showMembers(<?php echo $this->data['EscortTeam']['prison_id']; ?>,'<?php echo $this->data['EscortTeam']['members']; ?>');
            <?php
        }else{
            ?>
            showMembers(<?php echo $this->Session->read('Auth.User.prison_id'); ?>,'');
            <?php
        }
        ?>
    });
    function test(){
         var node= $( "label.error" );
        $( "label.error"  ).remove();
        $( "#escortteam" ).append(node);
        $( "#member_error" ).show();

    }
  $(function(){
    
    $("#EscortTeamAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[EscortTeam][name]': {
                    required: true,
                },
                'data[EscortTeam][prison_id]': {
                    required: true,
                },
                'data[EscortTeam][members][]': {
                    required: true,
                },
           },
            messages: {
                'data[EscortTeam][name]': {
                    required: "Please provide team name.",
                },
                'data[EscortTeam][prison_id]': {
                    required: "Please select prison name",
                },
                'data[EscortTeam][members][]': {
                    required: "Please select atleast one member",
                },
            },
               
    });
  });
function getEscortTeam(id){
$('#EscortTeamMembers').select2('val', id);
}



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
</script> -->
