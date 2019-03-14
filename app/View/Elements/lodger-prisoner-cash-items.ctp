<style type="text/css">
    #s2id_pp_cash{
        width:180px;
    }
</style>
<div class="visitorPrisonerCashItemsWrapper">
<h5>Prisoner Cash Items</h5>
<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
    <div style="width: 100%;
    margin-left: 112px;" >
            

        <div class="scountDiv2" id="visitorPrisonerCashItemsDiv"> 
            <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Cash Details<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Amount<span style="color:red;"></span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Currency<span style="color:red;"></span></label>
                    </div>
                </div>
            <?php 
            $total_scount = 0; $editPhysicalPropertyItemData = '';
            
            if(isset($this->request->data['LodgerPrisonerCashItem']) && count($this->request->data['LodgerPrisonerCashItem']) > 0)
            {
                $LodgerPrisonerCashItem  =   $this->request->data['LodgerPrisonerCashItem'];
            }
            if(isset($LodgerPrisonerCashItem)  && count($LodgerPrisonerCashItem) > 0)
            {
                $i = 0;
                $total_scount = count($LodgerPrisonerCashItem);
                //echo '<pre>'; print_r($VisitorItem); exit;
                ?>
                <!-- <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;">*</span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;">*</span></label>
                    </div>
                </div> -->
                
                <?php
                foreach($LodgerPrisonerCashItem as $VisitorItem)
                {

                    ?>

                    <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                    <?php echo $this->Form->input('LodgerPrisonerCashItem.'.$i.'.id', array('type'=>'hidden'))?>
                        

                        <?php echo $this->Form->input('LodgerPrisonerCashItem.'.$i.'.is_collected',array('div'=>false,'label'=>false,'type'=>'hidden' ,'required'=>false,'value'=> $VisitorItem["is_collected"]==1?'true':'false'));?>
                    <div class="span3">
                            
                                    <?php echo $this->Form->input('LodgerPrisonerCashItem.'.$i.'.cash_details',array('div'=>false,'label'=>false,'placeholder'=>'Enter Cash Details','class'=>'form-control  alphanumericone','id'=>'cash_details','required'));?>
                    </div> 
                    <div class="span3">
                         <?php echo $this->Form->input('LodgerPrisonerCashItem.'.$i.'.pp_amount',array('div'=>false,'label'=>false,'placeholder'=>'Enter pp cash amount','class'=>'form-control  numeric','id'=>'pp_amount','required'=>'Please enter PP cash amount','required'));?>
                    </div>
                    <div class="span3">
                        <?php echo $this->Form->input('LodgerPrisonerCashItem.'.$i.'.pp_cash',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','id'=>'pp_cash','empty'=>'','options'=>$ppcash,'required','style'=>'width:180px;','title'=>'Please select type of pp cash'));?>
                    </div>
                    
                    
                         
                    <?php if($i == 0)
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add3" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-plus"></span>
                                </button>
                            </span>
                        <?php }
                        else 
                        {?>
                            <span class="input-group-btn">
                                <button class="btn btn-danger btn-remove3" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                                    <span class="icon icon-minus"></span>
                                </button>
                            </span>
                        <?php }?>
                        <span>
                           <?php if( $VisitorItem["is_collected"] ==1 ){ ?>
                                <span style="color:green;">Collected</span>
                           <?php }else{ ?>
                                <span style="color:red;">Not yet collected</span>
                           <?php } ?>
                        </span>
                </div>

                   
                <?php $i++;
           // }
                }
            }
            else 
            {?>
                <!-- <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Items<span style="color:red;">*</span></label>
                    </div>
                    <div class="span3">
                    <label class="control-label" style="text-align: left;">Quantity<span style="color:red;">*</span></label>
                    </div>
                    
                </div> -->
                <div class="entry2 visitorItemEntry input-group row-fluid uradioBtn">
                              
                    <div class="span3">
                        
                                    <?php echo $this->Form->input('LodgerPrisonerCashItem.0.cash_details',array('div'=>false,'label'=>false,'placeholder'=>'Enter Cash Details','class'=>'form-control  alphanumericone','id'=>'cash_details','required'=>false));?>
                    </div>
                    <div class="span3">

                        
                                        <?php echo $this->Form->input('LodgerPrisonerCashItem.0.pp_amount',array('div'=>false,'label'=>false,'placeholder'=>'Enter pp cash amount','class'=>'form-control  numeric','id'=>'pp_amount','style'=>'width:180px;','required'=>false));?>
                                 
                    </div>
                    <div class="span3">

                                           <?php echo $this->Form->input('LodgerPrisonerCashItem.0.pp_cash',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','id'=>'pp_cash','empty'=>'--Select--','options'=>$ppcash,'required'=>false,'title'=>'Please select type of pp cash'));?>
                    </div>
                    
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add3" type="button" style="padding: 8px 8px;margin-bottom: 13px;">
                            <span class="icon icon-plus"></span>
                        </button>
                    </span>
                </div> 
            <?php }?>
        </div>
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
    var scount2 = 0;
    $(document).on('click', '.btn-add3', function(e)
    {
        e.preventDefault();
        $("select").select2("destroy");
        //$("select").select2('val','');
        var controlForm = $('#visitorPrisonerCashItemsDiv');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('#visitorPrisonerCashItemsDiv .visitorItemEntry:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        controlForm.find('.visitorItemEntry:not(:first) .btn-add3')
            .removeClass('btn-add3').addClass('btn-remove3')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');
        //change name of inputs 
        scount2 = parseInt($('#visitorPrisonerCashItemsDiv input[name*="cash_details"]').length);
        scount2 = scount2-1; 
        //alert(scount2);
        var item_id_name = "data[LodgerPrisonerCashItem]["+scount2+"][cash_details]";
        var item_id_id = "LodgerPrisonerCashItem"+scount2+"cash_details";
        $('#visitorPrisonerCashItemsDiv input[name*="cash_details"]:last').attr('name',item_id_name);
        $('#visitorPrisonerCashItemsDiv input[name*="cash_details"]:last').attr('id',item_id_id);
        $('#visitorPrisonerCashItemsDiv input[name*="cash_details"]:last').attr('required','required');
        $('#'+item_id_id).val('');

        var quantity_name = "data[LodgerPrisonerCashItem]["+scount2+"][pp_amount]";
        var quantity_name_id = "LodgerPrisonerCashItem"+scount2+"pp_amount";
        $('#visitorPrisonerCashItemsDiv input[name*="pp_amount"]:last').attr('name',quantity_name);
        $('#visitorPrisonerCashItemsDiv input[name*="pp_amount"]:last').attr('id',quantity_name_id);
        $('#visitorPrisonerCashItemsDiv input[name*="pp_amount"]:last').attr('required','required');

        var currency_name = "data[LodgerPrisonerCashItem]["+scount2+"][pp_cash]";
        var currency_name_id = "LodgerPrisonerCashItem"+scount2+"pp_cash";
        $('#visitorPrisonerCashItemsDiv select[name*="pp_cash"]:last').attr('name',currency_name);
        $('#visitorPrisonerCashItemsDiv select[name*="pp_cash"]:last').attr('id',currency_name_id);
        $('#visitorPrisonerCashItemsDiv select[name*="pp_cash"]:last').attr('required','required');

        $("#LodgerPrisonerCashItem"+scount2+"pp_amount").val('');
        $('#prisoner_no').select2();
        $('#pp_cash').select2();
        $('.relation').select2();

        $('#visitorPrisonerCashItemsDiv .visitorItemEntry:last label.error').remove();
               
    }).on('click', '.btn-remove3', function(e)
    {
        $(this).parents('#visitorPrisonerCashItemsDiv .visitorItemEntry:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>