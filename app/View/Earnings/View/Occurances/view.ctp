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
                    <h5>Occurances Book</h5>
                     <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Form->button('Print', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success btn-mini','id'=>'printBtn' ,'onclick'=>"printDiv('printableArea')"))?>
                        <?php echo $this->Html->link(__('view occurance Book'), array('action' => '/occurnce'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    <div style="float:right;padding-top: 7px;">
                        
                    </div>
                </div>
                <div class="widget-content nopadding" id="printableArea">
                    <table class="table table-bordered">
                        <tr>
                            <td>Name:</td>
                            <td>
                                <?php
                                echo $this->data['Occurance']['name'];
                                ?>
                            </td>
                            <td>Force Number:</td>
                            <td>
                            <?php
                            echo $this->data['Occurance']['force_number'];
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Date:</td>
                            <td>
                            <?php
                            echo $this->data['Occurance']['date'];
                            ?>
                            </td>
                            <td>Time:</td>
                            <td>
                            <?php
                            echo $this->data['Occurance']['time'];
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Rank:</td>
                            <td>
                                <?php
                                echo $this->data['Occurance']['rank'];
                                ?>
                            </td>
                            <td>Shift:</td>
                            <td>
                            <?php
                             echo $funcall->getName($this->data['Occurance']['shift_id'],"Shift","name");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Area Of deployment:</td>
                        </tr>
                        <tr>
                            <td colspan="4" id="areaof"></td>
                        </tr>
                        <tr>
                            <td colspan="4">Lockup Details:</td>
                        </tr>
                        <tr>
                            <td colspan="4" id="lockup"></td>
                        </tr>
                        <tr>
                            <td>No. Of Absent Staff:</td>
                            <td>
                                <?php
                                echo $this->data['Occurance']['number_of_absent_stafs'];
                                ?>
                            </td>
                            <td>Responsibility:</td>
                            <td>
                            <?php
                             echo $this->data['Occurance']['responsibility'];
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Occurances Details:</td>
                            <td>
                                <?php
                                echo $this->data['Occurance']['occurance_details'];
                                ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Remarks:</td>
                            <td>
                                <?php
                                echo $this->data['Occurance']['remarks'];
                                ?>
                            </td>
                            <td>Action:</td>
                            <td>
                                <?php
                                echo $this->data['Occurance']['action'];
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $( document ).ready(function() {
        <?php
        if(isset($this->data['Occurance']['id']) && $this->data['Occurance']['id']!=''){
            ?>
            getShiftId(<?php echo $this->data['Occurance']['shift_id']; ?>);
            getLockupAjax(<?php echo $this->data['Occurance']['shift_id']; ?>);
            <?php
        }
        ?>
    });
    $("#OccuranceIndexForm").validate({
        ignore: "",
    });
function getShiftId(id){
    var shift_date='<?php echo date("d-m-Y", strtotime($this->data['Occurance']['date'])); ?>';
    var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'getShiftId'));?>/'+id+'/'+shift_date;
    $.post(strURL,{},function(data){
      if(data) { 
       //alert(data);
          $('.areaof').show();
          $('#areaof').html(data);

      }
      else
      {
          $('.areaof').hide();
          alert("Error...");  
      }
    });
  }

  function getLockupAjax(id){
      var shift_date='<?php echo date("d-m-Y", strtotime($this->data['Occurance']['date'])); ?>';
      var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'lockupReportAjax'));?>/'+id+'/'+shift_date;
      $.post(strURL,{},function(data){
          if(data) { 
           //alert(data);
              $('#lockup').html(data);

          }
          else
          {
              $('#lockup').html('');
              alert("Error...");  
          }
      });
  }

  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

</script>
