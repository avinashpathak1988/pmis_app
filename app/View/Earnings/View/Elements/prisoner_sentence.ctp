<div class="" style="padding-bottom: 14px;">
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Offence Details</h5>
        </div>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Offence<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceIdList, 'empty'=>'-- Select Offence --','required','id'=>'class2'));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Class :</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('class',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$classificationList, 'empty'=>'-- Select Class --','required','id'=>'class2', 'disabled', 'selected'=>$prisoner_class));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offence detail END --> 
</div>

<script type="text/javascript">
var prev_sentence_capture_count = '<?php echo $total_sentence_capture_count;?>';

$(function()
{

    $('.pmis_select2').select2({
        placeholder: "-- Select --",
        allowClear: false
    });

    var scount = 0;
    $(document).on('click', '.offence-btn-add', function(e)
    {
        e.preventDefault();

        var offenceForm = $('#offence_list');

        //offenceForm.find('.offence_list:not(:first) select')
        //    .select2("destroy");
        //currentEntry   =   $('#dataFormat .entry');
        //var currentEntry   =   $('#offence_list .offence_list:last');
        var currentEntry   =   $('#offence_new').html();

        
        //newOffence = $(currentEntry.clone()).appendTo(offenceForm);

        newOffence = $(currentEntry).appendTo(offenceForm);

        //$("#offence_list select").select2("destroy");

        

        offenceForm.find('.offence_list:not(:first) .offence-remove')
            .removeClass('hidden');

        //newOffence.find('select').val('');
        // offenceForm.find('.sentence_capture_entry:not(:first) .sentence-capture-btn-add')
        //     .removeClass('sentence-capture-btn-add').addClass('btn-remove')
        //     .removeClass('btn-success').addClass('btn-danger')
        //     .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('#offence_list .offence_list').length);
        scount = scount-1; 

        var offence_name = "data[PrisonerAdmission][PrisonerOffence]["+scount+"][offence]";
        $('#offence_list select[name*="offence"]:last').attr('name',offence_name);

        var section_of_law_name = "data[PrisonerAdmission][[PrisonerOffence]]["+scount+"][section_of_law]";
        $('#offence_list select[name*="section_of_law"]:last').attr('name',section_of_law_name);

        $('#offence_list select[name*="offence"]:last option:selected').removeAttr("selected");
        $('#offence_list select[name*="section_of_law"]:last option:selected').removeAttr("selected");

        $('#offence_list select[name*="offence"]:last').attr("id",scount+"_offence_id");
        $('#offence_list select[name*="offence"]:last').attr("onchange","getSOLaws(this.value,"+scount+")");
        $('#offence_list select[name*="section_of_law"]:last').attr("id",scount+"_section_of_law_id");

        $('#offence_list select[name*="section_of_law"]:last').select2({minimumResultsForSearch: Infinity});
        $('#offence_list select[name*="offence"]:last').select2({placeholder: "-- Select --",
        allowClear: false});

        $('#offence_list select[name*="section_of_law"]:last').html('');
        $('#offence_list select[name*="section_of_law"]:last').select2({
            placeholder: "-- Select --",
            allowClear: false
        });
               
    }).on('click', '#offence_list .offence-remove', function(e)
    {
        $('#offence_list .offence_list:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>

<div id="offence_new" class="hidden">
    <div class="span12 offence_list" style="position:relative;">
        <button class="btn btn-add btn-remove btn-danger hidden offence-remove" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;top: 30%;"><span class="icon icon-minus"></span></button>
        <div class="span5">
            <div class="control-group">
                <label class="control-label">Offence<?php echo $req;?>:</label>
                <div class="controls">
                    <?php 
                    echo $this->Form->input('offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceList, 'empty'=>'-- Select Offence --','required'=>false, 'id'=>'offence_id'));?>
                </div>
            </div>
        </div>  
        <div class="span7">
            <div class="control-group">
                <label class="control-label">Section Of Law
                    <span id="admission_section_of_law_id_div"><?php echo $req; ?></span> :</label>
                <div class="controls">
                    <?php 
                    echo $this->Form->input('section_of_law',array('div'=>false,'label'=>false,'multiple'=>true,'class'=>'form-control span11','type'=>'select','empty'=>'-- Select Section Of Law --','required'=>false, 'selected'=>$selected_admission_sol, 'id'=>'section_of_law'));?>
                </div>
            </div>
        </div>
    </div>
</div>