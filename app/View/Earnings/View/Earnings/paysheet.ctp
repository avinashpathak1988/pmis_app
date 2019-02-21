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
                    <h5>Paysheet</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('PrisonerPaysheet',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                                echo $this->Form->input('id',array('type'=>'hidden'));
                                echo $this->Form->input('prison_id',array(
                                    'type'=>'hidden',
                                    'class'=>'prison_id',
                                    'value'=>$this->Session->read('Auth.User.prison_id')
                                  ));
                                  echo $this->Form->input('total_balance',array('type'=>'hidden','id'=>'total_balance'));?>
                                 <div class="row-fluid" style="padding-bottom: 14px;">
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Prisoner No <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'onChange'=>'getPrisonerInfo(this.value)','class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                    
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Priosner Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','class'=>'form-control datepicker span11','type'=>'text','placeholder'=>'Enter Station Number ','id'=>'prisoner_name','readonly'));?>
                                            </div>
                                        </div>
                                    </div>
                                   </div>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    
                                    <div class="span6">
                                      
                                        <div class="control-group">
                                            <label class="control-label">Date Of Pay <?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_pay',array('div'=>false,'label'=>false,'class'=>'form-control span11','class'=>'form-control span11 mydate ','type'=>'text','readonly','placeholder'=>'Enter date of pay','id'=>'date_of_pay'));?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Amount<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('amount',array('div'=>false,'label'=>false,'class'=>'form-control span11','onBlur'=>'updateBalance(this.value)','type'=>'text','placeholder'=>'Enter Amount ','id'=>'amount'));?>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                    <div class="row-fluid">
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Balance<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('balance',array('div'=>false,'label'=>false,'class'=>'form-control span11','class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Balance ','id'=>'balance','readonly'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Checked By O/C <?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('checked_by_oc',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'EnterChecked By O/C','class'=>'form-control span11','required'));?>
                                            </div>
                                        </div>
                                    
                                    </div>
                                    </div> 
                                    <div class="row-fluid">
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Confirmed Prisoner  :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('confirmed_prisoner',array('div'=>false,'label'=>false,'class'=>'form-control span11','class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Confirmed Prisoner ','id'=>'confirmed_prisoner'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    </div>
                                     
                                    
                                </div>

                              <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                    </div>
                                <?php echo $this->Form->end();?>
                                    
                           
                             <div id="listview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
//get item price 
function getPrisonerInfo(id) 
{ 
    $('#prisoner_name').val('');
    $('#balance').val('');
    $('#total_balance').val('');
    $('#amount').val('');
    $('#amount').attr('readonly', false);
    if(id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'earnings','action'=>'getPrisonerInfo'));?>';
    
        $.post(strURL,{"prisoner_id":id},function(data){  
            
            if(data) { 

                var obj = jQuery.parseJSON(data);
                $('#prisoner_name').val(obj.prisoner_name); 
                $('#balance').val(obj.balance);
                $('#total_balance').val(obj.balance); 
                if(obj.balance == 0)
                {
                    $('#amount').attr('readonly', true);
                }
            }
        });
    }
}
//update balance 
function updateBalance(amnt)
{
    var total_balance = $('#total_balance').val(); 
    var balance = parseInt(total_balance)-parseInt(amnt);
    if(balance < 0)
    {
        alert('Amount should be less than balance');
    }
    else 
    {
        $('#balance').val(balance); 
    }
}
</script>
<?php
$prisonerPaysheetUrl = $this->Html->url(array('controller'=>'earnings','action'=>'prisonerPaysheetAjax'));
$deletePrisonerPaysheetUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deletePrisonerPaysheet'));
echo $this->Html->scriptBlock("
   
    jQuery(function($) {
         showData();
    }); 
    
    function showData(){
        var url = '".$prisonerPaysheetUrl."';
        $.post(url, {}, function(res) {
            if (res) {
                $('#listview').html(res);
            }
        });
    }

    //delete working party 
    function deletePrisonerPaysheet(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletePrisonerPaysheetUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showData();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

",array('inline'=>false));
?>