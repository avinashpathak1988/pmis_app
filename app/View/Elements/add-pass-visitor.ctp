<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div style="width: 100%;
    margin-left: 112px;;">
        <div class="scountDiv2" id="visitorItemsDiv"> 
                
            <?php 
            $total_scount = 0; $editPhysicalPropertyItemData = '';
            
            if(isset($this->request->data['PassVisitor']) && count($this->request->data['PassVisitor']) > 0)
            {
                $VisitorItem  =   $this->request->data['PassVisitor'];
            }
            if(isset($VisitorItem)  && count($VisitorItem) > 0)
            {
                $i = 0;
                $total_scount = count($VisitorItem);
                //echo '<pre>'; print_r($VisitorItem); exit;
                ?>

                <div class="row-fluid">
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Nat. Id Type<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Nat. Id No.<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Profession<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Contact<span style="color:red;">*</span></label>
                    </div>
                     <div class="span2">
                    <label class="control-label" style="text-align: left;">Relationship<span style="color:red;"></span></label>
                    </div>
                </div>
                <?php
                foreach($VisitorItem as $VisitorItem)
                {
                    //$VisitorItem = $VisitorItem['VisitorItem'];
                     //if($VisitorItem["item_status"]=="Incoming"){
                    ?>

                    <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <div class="span2">
                                <?php echo $this->Form->input('PassVisitor.'.$i.'.nat_id_type',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$natIdList,'required','title'=>'Please select national id type'));?>
                    </div>
                    <div class="span2">

                        <?php echo $this->Form->input('PassVisitor.'.$i.'.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'National Id No.','required','title'=>'Please enter visitor nat.id no'));?>
                    </div>
                    <div class="span2">

                        <?php echo $this->Form->input('PassVisitor.'.$i.'.profession',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'Visitors Profession.','required','title'=>'Please enter visitor profession'));?>
                    </div>
                    <div class="span2">

                        <?php echo $this->Form->input('PassVisitor.'.$i.'.contact',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'Visitor Contact No.','required','title'=>'Please enter visitor contact'));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('PassVisitor.'.$i.'.relation',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$relation,'title'=>'Please select relationship'));?>
                    </div>
                    <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-plus"></span>
                                </button>
                            </span>
                        <?php }
                        else 
                        {?>
                            <span class="input-group-btn">

                                <button class="btn btn-danger btn-remove" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-minus"></span>
                                </button>
                            </span>
                        <?php }?>
                </div>

                   
                <?php $i++;
           // }
                }
            }
            else 
            {?>

                <div class="row-fluid">
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Nat. Id Type<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Nat. Id No.<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Profession<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                    <label class="control-label" style="text-align: left;">Contact<span style="color:red;">*</span></label>
                    </div>
                     <div class="span2">
                    <label class="control-label" style="text-align: left;">Relationship<span style="color:red;"></span></label>
                    </div>
                </div>
                <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <?php echo $this->Form->input('PassVisitor.0.id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'hidden' ))?>
                    
                    <div class="span2">
                                <?php echo $this->Form->input('PassVisitor.0.nat_id_type',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$natIdList,'required','title'=>'Please select national id type'));?>
                    </div>
                    <div class="span2">

                        <?php echo $this->Form->input('PassVisitor.0.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'National Id No.','required','title'=>'Please enter visitor nat.id no'));?>
                    </div>
                    <div class="span2">

                        <?php echo $this->Form->input('PassVisitor.0.profession',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'Visitors Profession','required','title'=>'Please enter visitor profession'));?>
                    </div>
                    <div class="span2">

                        <?php echo $this->Form->input('PassVisitor.0.contact',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'', 'placeholder'=>'Visitor Contact No.','required','title'=>'Please enter visitor contact'));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('PassVisitor.0.relation',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'','type'=>'select','empty'=>'--Select--','options'=>$relation,'title'=>'Please select relationship'));?>
                    </div>
                    
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
            <?php }?>
        </div>
    </div>
</div>     
<style type="text/css">
    .scountDiv2 input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
$(function()
{
    var scount = 0;
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();
        $("#visitorItemsDiv select").select2("destroy");
        //$("select").select2('val','');
        var controlForm = $('#visitorItemsDiv');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#visitorItemsDiv .visitorItemEntry:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('.quan').val('');
        controlForm.find('.visitorItemEntry:not(:first) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');
        //change name of inputs 
        scount = parseInt($('#visitorItemsDiv input[name*="nat_id"]').length);
        scount = scount-1; 
        var nat_id_name = "data[PassVisitor]["+scount+"][nat_id]";
        var nat_id_id = "PassVisitor"+scount+"item";
        $('#visitorItemsDiv input[name*="nat_id"]:last').attr('name',nat_id_name);
        $('#visitorItemsDiv input[name*="nat_id"]:last').attr('id',nat_id_id);
        $('#visitorItemsDiv input[name*="nat_id"]:last').attr('required','required');

        $('#'+nat_id_id).val('');


        var nat_id_type_name = "data[PassVisitor]["+scount+"][nat_id_type]";
        var nat_id_type_id = "PassVisitor"+scount+"nat_id_type";
        $('#visitorItemsDiv select[name*="nat_id_type"]:last').attr('name',nat_id_type_name);
        $('#visitorItemsDiv select[name*="nat_id_type"]:last').attr('id',nat_id_type_id);
        $('#visitorItemsDiv select[name*="nat_id_type"]:last').attr('required','required');

        $('#'+nat_id_type_id).val('');

        var nat_id_name = "data[PassVisitor]["+scount+"][profession]";
        var nat_id_id = "PassVisitor"+scount+"profession";
        $('#visitorItemsDiv input[name*="profession"]:last').attr('name',nat_id_name);
        $('#visitorItemsDiv input[name*="profession"]:last').attr('id',nat_id_id);
        $('#visitorItemsDiv input[name*="profession"]:last').attr('required','required');

        $('#'+nat_id_id).val('');

        var nat_id_name = "data[PassVisitor]["+scount+"][contact]";
        var nat_id_id = "PassVisitor"+scount+"contact";
        $('#visitorItemsDiv input[name*="contact"]:last').attr('name',nat_id_name);
        $('#visitorItemsDiv input[name*="contact"]:last').attr('id',nat_id_id);
        $('#visitorItemsDiv input[name*="contact"]:last').attr('required','required');

        $('#'+nat_id_id).val('');



        var nat_id_type_name = "data[PassVisitor]["+scount+"][relation]";
        var nat_id_type_id = "PassVisitor"+scount+"relation";
        $('#visitorItemsDiv select[name*="relation"]:last').attr('name',nat_id_type_name);
        $('#visitorItemsDiv select[name*="relation"]:last').attr('id',nat_id_type_id);
        $('#visitorItemsDiv select[name*="relation"]:last').attr('required','required');
        
        $('#'+nat_id_type_id).val('');
        
        $('#visitorItemsDiv .visitorItemEntry:last label.error').remove();
               
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.visitorItemEntry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>