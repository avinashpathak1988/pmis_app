
<div class="span12">
    <div style="width: 90%;margin: 0 auto;">
        <div class="scountDiv3" style="background-color: #f0f0f0;padding: 10px 20px;margin-top: 10px;margin-left: 20px;border: 1px solid #ddd; box-shadow: 0 0 7px #ddd;"> 
            <?php 
            $total_scount = 0; $editPhysicalCashData = '';
            if(isset($this->request->data['CashItem']) && count($this->request->data['CashItem']) > 0)
            {
                $editPhysicalCashData  =   $this->request->data['CashItem'];
            }
            if($editPhysicalCashData != '' && count($editPhysicalCashData) > 0)
            {
                $i = 0;
                $total_scount = count($editPhysicalCashData);
                //echo '<pre>'; print_r($editPhysicalCashData); exit;
                ?>
                <div class="row-fluid">
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Amount<span style="color:red;">*</span></label>
                    </div>
                    <div class="span3">
                        <label class="control-label" style="text-align: left;">Currency<span style="color:red;">*</span></label>
                    </div>
                    
                </div>
                <?php
                foreach($editPhysicalCashData as $PhysicalCash)
                {?>
                    <div class="entry3 input-group row-fluid uradioBtn">
                        <div class="span3">
                            <?php echo $this->Form->input('CashItem.'.$i.'.amount',array('div'=>false,'label'=>false,'class'=>'form-control numeric','type'=>'text', 'placeholder'=>'Amount','required'=>false,'value'=>$PhysicalCash["amount"]));?>
                        </div>
                        <div class="span3">
                            <?php echo $this->Form->input('CashItem.'.$i.'.currency_id',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$currencyList, 'empty'=>array(''=>'-- Select Currency --'),'required'=>false, 'style'=>'width:90%'));?>
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
                }
            }
            else 
            {?>
                <div class="row-fluid">
                    <div class="span5">
                        <label class="control-label" style="text-align: left;">Amount<span style="color:red;">*</span></label>
                    </div>
                    <div class="span6">
                        <label class="control-label" style="text-align: left;">Currency<span style="color:red;">*</span></label>
                    </div>
                    
                </div>
                <div class="entry3 input-group row-fluid uradioBtn">
                    <?php 
                    if($current_usertype_id == Configure::read('GATEKEEPER_USERTYPE'))
                    {
                        echo $this->Form->input('CashItem.0.status',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> 'G-Draft'));
                    }
                    ?>
                <div class="span5">
                        <?php echo $this->Form->input('CashItem.0.amount',array('div'=>false,'label'=>false,'class'=>'form-control numeric','type'=>'text', 'placeholder'=>'Amount','required'=>false));?>
                    </div>
                    <div class="span6">
                        <?php echo $this->Form->input('CashItem.0.currency_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$currencyList, 'empty'=>'','required'=>false, 'style'=>'width:90%'));?>
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
    .scountDiv3 input {
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
        $("select").select2("destroy");

        var controlForm = $('.scountDiv3');
        //currentEntry   =   $('#dataFormat .entry');
        var currentEntry   =   $('.entry3:last');
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry3:not(:first) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="icon icon-minus"></span>');

        //change name of inputs 
        scount = parseInt($('.scountDiv3 input[name*="amount"]').length);
        scount = scount-1; 
        var amount_name = "data[CashItem]["+scount+"][amount]";
        var amount_id = "CashItem"+scount+"amount";
        
        $('.scountDiv3 input[name*="amount"]:last').attr('name',amount_name);
        $('.scountDiv3 input[name*="amount"]:last').attr('id',amount_id);
       
        var status_name = "data[CashItem]["+scount+"][status]";
        $('.scountDiv3 input[name*="status"]:last').attr('name',status_name);
        $('.scountDiv3 input[name*="status"]:last').val('G-Draft');

        var currency_id_name = "data[CashItem]["+scount+"][currency_id]";
        var currency_id_id = "CashItem"+scount+"currency_id";
        $('.scountDiv3 select[name*="currency_id"]:last').attr('name',currency_id_name);
        $('.scountDiv3 select[name*="currency_id"]:last').attr('id',currency_id_id);

        

        $('.scountDiv3 select[name*="currency_id"]:last option:selected').removeAttr("selected");

        $('.scountDiv3 .entry3:last label.error').remove();

        $('select').select2({minimumResultsForSearch: Infinity});
               
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry3:last').remove();
        e.preventDefault();
        return false;
  });
});
</script>