<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Application For Transfer</h5>
                    <div style="float:right;padding-top: 7px;">
                    <?php echo $this->Html->link(__('Application For Transfer List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-primary')); ?>
                    &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php 
                    echo $this->Form->create('PrisonerTransferLogin',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                    echo $this->Form->input('id',array('type'=>'hidden'));
                    ?>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Original Station <?php echo $req; ?>:</label>
                                <div class="controls">
                                <?php 
                                $originPrisonList = $prisonList;
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                $originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
                                }
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                $originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
                                }
                                echo $this->Form->input('transfer_from_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$originPrisonList, 'empty'=>'','required','id'=>'transfer_from_station_id','title'=>"Please Enter Original Station","onchange"=>"showPrisonerTypeCount(this.value)"));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Destination Station <?php echo $req; ?>:</label>
                                <div class="controls">
                                <?php echo $this->Form->input('transfer_to_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'transfer_to_station_id','title'=>"Please Enter Destination"));?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date Of Transfer Request <?php echo $req; ?> :</label>
                                <div class="controls">
                                <?php echo $this->Form->input('date_of_transfer_request',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Transfer Date','required','readonly'=>'readonly','id'=>'transfer_date','title'=>"Please enter date of transfer",'value'=>date('d-m-Y')));?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">    
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Reason <?php echo $req; ?>:</label>
                                <div class="controls">
                                <?php echo $this->Form->input('reason',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Reason','required','type'=>'textarea','id'=>'reason','rows'=>2,'title'=>"Please provide reason",'required'));?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks <?php echo $req; ?>:</label>
                                <div class="controls">
                                <?php echo $this->Form->input('remarks',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Remarks','required','type'=>'textarea','id'=>'remarks','rows'=>2,'title'=>"Please provide remarks",'required'));?>
                                </div>
                            </div>
                        </div>                            
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Total No of Prisoners <?php echo $req; ?>:</label>
                                <div class="controls">
                                    <table  class="table table-bordered data-table table-responsive">
                                        <tr>
                                        <th style="text-align:center;">Prisoner Type :</th>
                                        <th style="text-align:center;">Remand :</th>
                                        <th style="text-align:center;">Transfered :</th>
                                      </tr>
                                      <tr>
                                        <td><strong>Convict</strong></td>
                                        <td><?php echo $this->Form->input('convict',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Convicted','required','readonly'=>'readonly'));?></td>
                                        <td><?php echo $this->Form->input('convict_rcv',array('div'=>false,'label'=>false,'type'=>'text',"onkeyup"=>"showConvicted(this.value)",'placeholder'=>'Enter Convicted','required'));?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Remand</strong></td>
                                        <td><?php echo $this->Form->input('remand',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter remand','required','readonly'=>'readonly'));?></td>
                                        <td><?php echo $this->Form->input('remand_rcv',array('div'=>false,'label'=>false,'type'=>'text',"onkeyup"=>"showRemand(this.value)",'placeholder'=>'Enter remand','required'));?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>Debtor</strong></td>
                                        <td><?php echo $this->Form->input('debtor',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter debtor','required','readonly'=>'readonly'));?></td>
                                        <td><?php echo $this->Form->input('debtor_rcv',array('div'=>false,'label'=>false,'type'=>'text',"onkeyup"=>"showDebtor(this.value)",'placeholder'=>'Enter debtor','required'));?></td>
                                      </tr>
                                       <tr>
                                        <td><strong>Total</strong></td>
                                        <td id="total"><?php 

                                             ?></td>
                                        <td id= "total_input">

                                            
                                        </td>       
                                        
                                      </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="form-actions" align="center">
                    <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
    });
    $("#pmis_loader").hide();
    $("#transfer_from_station_id").select2();
    function showPrisonerTypeCount(value){
        var strURL = '<?php echo $this->Html->url(array('controller'=>'PrisonerTransferLogins','action'=>'getPrisonerCount'));?>/'+value;
        $.post(strURL,{},function(data){
            var obj = JSON.parse(data); 
            console.log(obj);
            var sum = 0;
            $.each(obj, function( index, value ) {
                if(value.prisoner_type_id==<?php echo Configure::read('CONVICTED'); ?>){
                    $("#PrisonerTransferLoginConvict").val(value.total_count);
                    sum = sum + parseInt(value.total_count);

                }
                if(value.prisoner_type_id==<?php echo Configure::read('DEBTOR'); ?>){
                    $("#PrisonerTransferLoginDebtor").val(value.total_count);
                     sum = sum + parseInt(value.total_count);

                }
                if(value.prisoner_type_id==<?php echo Configure::read('REMAND'); ?>){
                    $("#PrisonerTransferLoginRemand").val(value.total_count);
                     sum = sum + parseInt(value.total_count);
                }
               
            });
             $("#total").html(sum);
            
        });

    var a = parseInt($("#PrisonerTransferLoginConvict").val());
    var b = parseInt($("#PrisonerTransferLoginRemand").val());
    var c = parseInt($("#PrisonerTransferLoginDebtor").val());
   // var d = a + b + c; 
   //alert(a);
   
    }

$("#PrisonerTransferLoginAddForm").validate({
      
});
function showConvicted(val) {
  // alert('1');
var datainfo = parseInt($("#PrisonerTransferLoginConvict").val());
var value = parseInt(val);
if ( datainfo < value) {
    alert("value should not greater than this");
    //document.getElementById('PrisonerTransferLoginConvictRcv').value = "";
    $('#PrisonerTransferLoginConvictRcv').val("");
     


}
total();
}
function showRemand(val) {
var datainfo_remand = parseInt($("#PrisonerTransferLoginRemand").val());
var remand_value = parseInt(val);
if ( datainfo_remand < remand_value) {
    alert("value should not greater than this");
    $('#PrisonerTransferLoginRemandRcv').val("");
    
}
total();
}
 function showDebtor(val){
    var datainfo_remand = parseInt($("#PrisonerTransferLoginDebtor").val());
var remand_value = parseInt(val);
if ( datainfo_remand < remand_value) {
    alert("value should not greater than this");
    $('#PrisonerTransferLoginDebtorRcv').val("");
    
 }
 total();
}
function total(){
	if ($('#PrisonerTransferLoginConvictRcv').val()!='') {
		var a =  parseInt($('#PrisonerTransferLoginConvictRcv').val());
	}else{
		var a = 0;
	}
	if ($('#PrisonerTransferLoginRemandRcv').val()!='') {
		var b =  parseInt($('#PrisonerTransferLoginRemandRcv').val());
	}else{
		var b = 0;
	}
	if ($('#PrisonerTransferLoginDebtorRcv').val()!='') {
		var c =  parseInt($('#PrisonerTransferLoginDebtorRcv').val());
	}else{
		var c = 0;
	}
	var d = parseInt(a) + parseInt(b) + parseInt(c);
	$("#total_input").html(d);
	//alert(d);
    
}
</script>