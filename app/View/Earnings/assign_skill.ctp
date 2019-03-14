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
                      <h5>Search Assign Skill</h5>
                    <a class="toggleBtn" href="#searchassignsearch" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))?>
                        <div style="float:right;padding-top: 7px;padding-right:5px;">
                            <?php echo $this->Html->link('Add Assign Skill','#addAssignSkill',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse")); ?>
                        </div>
                </div>
                <div id="searchassignsearch" class="collapse <?php if($isEdit == 0){echo 'in';}?>" <?php if($isEdit == 1){?> style="height: 0px;" <?php }?>>
                    <div class="">
                        <?php echo $this->Form->create('searchAssignSkill',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <div class="row-fluid"  style="padding-bottom: 14px;">
                            <div class="span6">
                                <div class="control-group">
                                <label class="control-label">Prisoner No:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'id'=>'prisoner_id_search','class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerlistSearch,'empty'=>''));?>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="span6">
                               
                                <div class="control-group">
                                     <label class="control-label">Skill:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('assign_skill_id',array('div'=>false,'label'=>false,'id'=>'assign_skill_id_search','class'=>'span11 pmis_select','type'=>'select','options'=>$gradeslist,'empty'=>'', 'required'));?>
                                    </div>
                                </div>
                            </div>
                             <div class="clearfix"></div> 
                             <div class="span6">
                                <div class="control-group">
                                    <label class="control-label maxCurrentDate">Effective Date :</label>
                                    <div class="controls">
                                        <?php $currentDate = date('d-m-Y');
                                        echo $this->Form->input('assignment_date',array('div'=>false,'label'=>false,'type'=>'text','id'=>'assignment_date_search','placeholder'=>'Enter Assignment/Prommotion date', 'data-date-format'=>"dd-mm-yyyy",
                                             'readonly'=>'readonly','class'=>'form-control maxCurrentDate','type'=>'text','required'));?>
                                    </div>
                                </div>
                            </div>
                          
                        </div> 
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate" onclick="showData()">Search</button>

                               <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchWorkingPartiesForm')"))?>
                                
                      </div>  
                      <?php echo $this->Form->end();?>         
                    </div>
                </div>

                <div id="addAssignSkill" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                    <div class="">
                            <?php echo $this->Form->create('AssignSkill',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array("type"=>"hidden"))?>
                            <?php echo $this->Form->input('uuid',array('type'=>'hidden'))?>
                            <div class="row-fluid  style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                    <label class="control-label">Prisoner No:<?php echo $req; ?> </label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonerlist,'empty'=>'','onchange'=>'getPrisonerGradelist(this.value);', 'required'));?>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                         <label class="control-label">Skill<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('assign_skill_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$gradeslist,'empty'=>'', 'required'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label maxCurrentDate">Effective Date<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php $currentDate = date('d-m-Y');
                                            echo $this->Form->input('assignment_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Assignment/Prommotion date', 'data-date-format'=>"dd-mm-yyyy",
                                                 'readonly'=>'readonly','class'=>'form-control maxCurrentDate','type'=>'text','required', 'default'=>$currentDate));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Is good conduct:</label>
                                         <div class="controls">
                                            <?php echo $this->Form->input('is_conduct',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox'));?>
                                         </div>
                                    </div>
                                </div>


                            </div> 
                            <div class="form-actions" align="center">
                                <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','onclick'=>"javascript:return validateForm();"))?>
                            </div>
                        <?php echo $this->Form->end();?>              
                    </div>
                </div>                      
            </div>
        </div>
        <div class="table-responsive" id="listingDiv">
            
        </div> 
    </div>
</div>

                
        
<script type="text/javascript">
  
jQuery(function($) {

         $('.toggleBtn').click(function(){
            $('.in.collapse').css('height','0');
            $('.in.collapse').removeClass('in');
         });
    });

// function validateForm(){
//     var errcount = 0;
//     $('.validate').each(function(){
//         if($(this).val() == ''){
//             errcount++;
//             $(this).addClass('error-text');
//             $(this).removeClass('success-text'); 
//         }else{
//             $(this).removeClass('error-text');
//             $(this).addClass('success-text'); 
//         }        
//     });        
//     if(errcount == 0){            
//         if(confirm('Are you sure want to save?')){  
//             return true;            
//         }else{               
//             return false;           
//         }        
//     }else{   
//         return false;
//     }  
// }

</script>
<?php
$workingPartyUrl = $this->Html->url(array('controller'=>'Earnings','action'=>'assignSkillAjax'));

$ajaxUrl        = $this->Html->url(array('controller'=>'Earnings','action'=>'assignSkillAjax'));
$getPrisonerGradeListUrl        = $this->Html->url(array('controller'=>'Earnings','action'=>'getPrisonerGradeList'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });

    function getPrisonerGradelist(val)
    {
        var url   = '".$getPrisonerGradeListUrl."';
        url = url + '/prisoner_id:'+val;
        $.post(url, {}, function(res) {
            var data = JSON.parse(res);
            $('#AssignSkillGradeId').html(data.gradelist);
            $('#prisoner_stage_id').val(data.currentStage);
            $('#prisoner_stage_name').val(data.currentStageName);
            $('#AssignSkillRemarks').val(data.stagePromotionRemark);
        });
    }
    function resetData(id){
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showDataWorkingParty();
    }


    function showData(){
        var url   = '".$workingPartyUrl."';
        //alert($('#assignment_date').val());
        url = url + '/prisoner_id_search:'+$('#prisoner_id_search').val();
        url = url + '/assign_skill_id_search:'+$('#assign_skill_id_search').val();
        url = url + '/assignment_date_search:'+$('#assignment_date_search').val();
       
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }
",array('inline'=>false));
?>
<script>
	 $(function(){
    $("#AssignSkillAssignSkillForm").validate({
     
      ignore: "",
            rules: {  
                'data[AssignSkill][prisoner_id]': {
                    required: true,
                },
                'data[AssignSkill][assign_skill_id]': {
                    required: true,
                },
           },
            messages: {
                'data[AssignSkill][prisoner_id]': {
                    required: "This Field is Required.",
                },
                'data[AssignSkill][assign_skill_id]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
</script>

