
<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div>
        <div class="scountDiv33"> 
            <?php 
           //debug($prionerKinLIst);
            $total_scount = 0; //$prionerKinLIst = '';
            //debug($this->request->data);
            // if(isset($prionerKinLIst) && count($prionerKinLIst) > 0)
            // {
            //     //$editPhysicalCashData  =   $this->request->data['VisitorName'];
            // }
            if($prionerKinLIst != '' && count($prionerKinLIst) > 0)
            { 
               // echo 'aaaaa';
                $i = 0;
                $total_scount = count($prionerKinLIst);
                //echo '<pre>'; print_r($editPhysicalCashData); exit;
                ?>

                <!-- <div class="row">
                    <div class="offset2 span2">
                        <label class="control-label" style="text-align: left;">First Name<span style="color:red;">*</span></label>
                    </div>
                    <div class="offset2 span2">
                        <label class="control-label" style="text-align: left;">Middle Name<span style="color:red;">*</span></label>
                    </div>
                    <div class="offset2 span2">
                        <label class="control-label" style="text-align: left;">Last Name<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Relationship<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Photo<span style="color:red;">*</span></label>
                    </div>
                    <div class="span2">
                        <label class="control-label" style="text-align: left;">Nat.ID No</label>
                    </div>
                    
                </div> -->
                    
                <div id="kinDetails">
                </div>
                    
                <?php 
                
            }
            ?>
        </div>
    </div>
</div>     
<style type="text/css">
    .scountDiv33 input {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">

$(function()
{
    var scount = 0;
    $(document).on('click', '.btn-addss', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");

        var controlForm = $('.scountDiv33');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('.entry33:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry33:not(:first) .btn-addss')
            .removeClass('btn-addss').addClass('btn-removes')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('.scountDiv33 input[name*="[name]"]').length);
        scount = scount-1; 
        //alert(scount);
        var name = "data[VisitorName]["+scount+"][name]";
        var name_id = "VisitorName"+scount+"name";
        $('.scountDiv33 input[name*="[name]"]:last').attr('name',name);
        $('.scountDiv33 input[name*="[name]"]:last').attr('id',name_id);

        var name = "data[VisitorName]["+scount+"][mname]";
        var name_id = "VisitorName"+scount+"mname";
        $('.scountDiv33 input[name*="mname"]:last').attr('name',name);
        $('.scountDiv33 input[name*="mname"]:last').attr('id',name_id);


        var name = "data[VisitorName]["+scount+"][lname]";
        var name_id = "VisitorName"+scount+"lname";
        $('.scountDiv33 input[name*="lname"]:last').attr('name',name);
        $('.scountDiv33 input[name*="lname"]:last').attr('id',name_id);

        var relation = "data[VisitorName]["+scount+"][relation]";
        var relation_id = "VisitorName"+scount+"relation";
        $('.scountDiv33 select[name*="relation"]:last').attr('name',relation);
        $('.scountDiv33 select[name*="relation"]:last').attr('id',relation_id);

        $('#'+relation_id).val('');

        
        var natIdType = "data[VisitorName]["+scount+"][nat_id_type]";
        var natIdType_id = "VisitorName"+scount+"nat_id_type";
        $('.scountDiv33 select[name*="nat_id_type"]:last').attr('name',natIdType);
        $('.scountDiv33 select[name*="nat_id_type"]:last').attr('id',natIdType_id);
        $('.scountDiv33 select[name*="nat_id_type"]:last').attr('required','required');
        $('#'+natIdType_id).val('');
        
        
        var photo = "data[VisitorName]["+scount+"][photo]";
        var photo_id = "VisitorName"+scount+"photo";
        $('.scountDiv33 input[name*="photo"]:last').attr('name',photo);
        $('.scountDiv33 input[name*="photo"]:last').attr('id',photo_id);

        var nat_id = "data[VisitorName]["+scount+"][nat_id]";
        var nat_id_id = "VisitorName"+scount+"nat_id";
        $('.scountDiv33 input[name*="nat_id"]:last').attr('name',nat_id);
        $('.scountDiv33 input[name*="nat_id"]:last').attr('id',nat_id_id);
       

        

        $('.scountDiv33 select[name*="currency_id"]:last option:selected').removeAttr("selected");

        $('.scountDiv33 .entry33:last label.error').remove();

        $('select').select2({minimumResultsForSearch: Infinity});
    }).on('click', '.btn-removes', function(e)
    { 
        $(this).parents('.entry33:last').remove();
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