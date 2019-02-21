
<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div>
        <div class="scountDiv3"> 
            <?php 
            $total_scount = 0; $editPhysicalCashData = '';
            //debug($this->request->data);
            if(isset($this->request->data['VisitorName']) && count($this->request->data['VisitorName']) > 0)
            {
                $editPhysicalCashData  =   $this->request->data['VisitorName'];
            }
            if($editPhysicalCashData != '' && count($editPhysicalCashData) > 0)
            {
                $i = 0;
                $total_scount = count($editPhysicalCashData);
                //echo '<pre>'; print_r($editPhysicalCashData); exit;
                ?>
                <div class="row">
                    <div class="offset2 span2">
                        <label class="control-label" style="text-align: left;">Name<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Relationship<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Photo<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Nat.ID No.<span style="color:red;">*</span></label>
                    </div>
                    
                </div>
                <?php
                foreach($editPhysicalCashData as $PhysicalCash)
                {?>
                    <div class="entry3 input-group row uradioBtn">
                        <div class="offset2 span2">
                        <?php echo $this->Form->input('VisitorName.'.$i.'.id', array('type'=>'hidden'))?>
                            <?php echo $this->Form->input('VisitorName.'.$i.'.name',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text', 'placeholder'=>'Visitor Name','required'=>true,'style'=>'width: 150px;','value'=>$PhysicalCash["name"]));?>
                        </div>
                        <div class="span2">
                            <?php echo $this->Form->input('VisitorName.'.$i.'.relation',array('div'=>false,'label'=>false,'class'=>'form-control relation','style'=>'width: 150px;','type'=>'select','empty'=>'--Select--','options'=>$relation,'required'=>true));?>
                        </div>
                        <div class="span2">
                            <?php echo $this->Form->input('VisitorName.'.$i.'.photo',array('div'=>false,'label'=>false,'class'=>'form-control','style'=>'width: 150px;','type'=>'file','required'=>true));?>
                        </div>
                        <div class="span2">
                            <?php echo $this->Form->input('VisitorName.'.$i.'.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control nid','style'=>'width: 150px;','type'=>'text', 'placeholder'=>'Amount','required'=>true,'value'=>$PhysicalCash["nat_id"]));?>
                        </div>
                        <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-adds" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
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
                }
            }
            else 
            {?>
                <div class="row">
                    <div class="offset2 span2">
                        <label class="control-label" style="text-align: left;">Name<span style="color:red;">*</span></label>
                    </div>
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Relationship<span style="color:red;">*</span></label>
                    </div>
                    <div class="span1">
                        <label class="control-label" style="text-align: left;">Photo<span style="color:red;">*</span></label>
                    </div>
                     <div class="span2">
                        <label class="control-label" style="text-align: left;">Nat.Id No.<span style="color:red;">*</span></label>
                    </div>
                    
                </div>
                <div class="entry3 input-group row uradioBtn">
                <div class="offset2 span2">
                        <?php echo $this->Form->input('VisitorName.0.name',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text','style'=>'width: 150px;', 'placeholder'=>'Visitor Name','required'=>true));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorName.0.relation',array('div'=>false,'label'=>false,'class'=>'form-control relation','type'=>'select','empty'=>'--Select--','options'=>$relation,'required'));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorName.0.photo',array('div'=>false,'label'=>false,'class'=>'form-control numeric','type'=>'file','style'=>'width: 150px;','required'=>false,'required'));?>
                    </div>
                    <div class="span2">
                        <?php echo $this->Form->input('VisitorName.0.nat_id',array('div'=>false,'label'=>false,'class'=>'form-control nid','type'=>'text','style'=>'width: 150px;', 'placeholder'=>'National Id No.','required'=>false,'required'));?>
                    </div>
                    
                   
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-adds" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
            <?php }?>
        </div>
    </div>
</div>     
<style type="text/css">
    .scountDiv3 input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">

$(function()
{
    var scount = 0;
    $(document).on('click', '.btn-adds', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");

        var controlForm = $('.scountDiv3');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('.entry3:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry3:not(:first) .btn-adds')
            .removeClass('btn-adds').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('.scountDiv3 input[name*="name"]').length);
        scount = scount-1; 

        var name = "data[VisitorName]["+scount+"][name]";
        var name_id = "VisitorName"+scount+"name";
        $('.scountDiv3 input[name*="name"]:last').attr('name',name);
        $('.scountDiv3 input[name*="name"]:last').attr('id',name_id);

        var relation = "data[VisitorName]["+scount+"][relation]";
        var relation_id = "VisitorName"+scount+"relation";
        $('.scountDiv3 select[name*="relation"]:last').attr('name',relation);
        $('.scountDiv3 select[name*="relation"]:last').attr('id',relation_id);

        $('#'+relation_id).val('');

        

        var photo = "data[VisitorName]["+scount+"][photo]";
        var photo_id = "VisitorName"+scount+"photo";
        $('.scountDiv3 input[name*="photo"]:last').attr('name',photo);
        $('.scountDiv3 input[name*="photo"]:last').attr('id',photo_id);

        var nat_id = "data[VisitorName]["+scount+"][nat_id]";
        var nat_id_id = "VisitorName"+scount+"nat_id";
        $('.scountDiv3 input[name*="nat_id"]:last').attr('name',nat_id);
        $('.scountDiv3 input[name*="nat_id"]:last').attr('id',nat_id_id);
       

        

        $('.scountDiv3 select[name*="currency_id"]:last option:selected').removeAttr("selected");

        $('.scountDiv3 .entry3:last label.error').remove();

        $('select').select2({minimumResultsForSearch: Infinity});
        $('#prisoner_no').select2();
        $('#pp_cash').select2();
        $('.relation').select2();
    }).on('click', '.btn-remove', function(e)
    { 
        $(this).parents('.entry3:last').remove();
        e.preventDefault();
        return false;
  });
});
       $('.nid').keyup(function()
    {
        var your = $(this).val();
        re = /[`~!@#$%^&*()_|\+=?;:'",.<>\{\}\[\]]/gi;
        var isSpl = re.test(your);
        if(isSpl)
        {
            var no_spl = your.replace(/[`~!@#$%^&*()_|\+=?;:'",.<>\{\}\[\]]/gi, '');
            $(this).val(no_spl);
        }
    });
</script>