<?php //debug($prisonerAttendanceList); exit;
if(is_array($datas) && count($datas)>0){
  
    echo $this->Form->create('PrisonerAttendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/earnings/attendances'));
    ?>
    <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success btn-mini" style="display: none;">Generate Gatepass</button>                  
    <table class="table table-bordered data-table table-responsive">
        <thead>
            <tr>
                <?php
                if(!isset($is_excel)){
                ?> 
                    <th>
                        <?php echo $this->Form->input('checkAll', array(
                            'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                            'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));?>
                    </tdh>
                <?php }?> 
                <th>SL#</th>
                <th>Prisoner No</th>
                <th>Prisoner Name</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            echo $this->Form->input('Attendance.working_party_id',array(
                'type'=>'hidden',
                'class'=>'working_party_id',
                'value'=>$working_party_id
            ));
            echo $this->Form->input('Attendance.prison_id',array(
                'type'=>'hidden',
                'class'=>'prison_id',
                'value'=>$this->Session->read('Auth.User.prison_id')
            ));
            $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
            $i = 0;
            foreach($datas as $data)
            {
                $prisoner_id = $data['Prisoner']['id'];
                ?>
                <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                    <?php 
                    if(!isset($is_excel))
                    {?>
                        <td>
                            <?php 
                            if(isset($prisonerAttendanceList) && !empty($prisonerAttendanceList) && in_array($prisoner_id, $prisonerAttendanceList))
                            {
                                echo $this->Form->input('PrisonerAttendance.'.$i.'.prisoner_id', array(
                                  'type'=>'checkbox', 'value'=>$prisoner_id,'hiddenField' => false, 'class' =>'select_prisoner','label'=>false,'checked', 'data-id'=>$i, 'id'=>'select_prisoner_'.$i,
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                                ));
                            }
                            else 
                            {
                                echo $this->Form->input('PrisonerAttendance.'.$i.'.prisoner_id', array(
                                  'type'=>'checkbox', 'value'=>$prisoner_id,'hiddenField' => false,'class' =>'select_prisoner', 'data-id'=>$i, 'id'=>'select_prisoner_'.$i,'label'=>false,
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                                ));
                            }
                            ?>
                            </td>
                    <?php }?>
                    <td><?php echo $rowCnt; ?></td>
                    <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
                    <td><?php echo $data[0]['fullname']; ?></td>
                </tr>
                <?php $i++;
                $rowCnt++;
            }
            ?>
        </tbody>
    </table>
    <?php 

    echo $this->Form->end();

}else{
echo Configure::read('NO-RECORD');    
}
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'attendances'));
?> 
<script type="text/javascript">
$(document).ready(function(){ 

            
        $(".less_3").click(function(){ 
            var itemId = $(this).attr('data-id');
            if($(this).prop('checked')){
            $('#select_prisoner_'+itemId).prop('checked', this.checked);
            }
        });

        $(".select_prisoner").click(function(){ 
            var itemId = $(this).attr('data-id');
            if($(this).prop('checked')){
                //$('#less_3_'+itemId).prop('checked', this.checked);
            }
            else {
                $('#less_3_'+itemId).prop('checked', this.checked);
            }
        });

        $(".less_3").click(function(){ 
            var itemId = $(this).attr('data-id');
            if($(this).prop('checked')){
            $('.select_prisoner_'+itemId).prop('checked', this.checked);
            }
        });

        $("#checkAll").click(function(){ 
            $('.select_prisoner').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkall = $('input[id="checkAll"]:checked').length;
          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAll').attr('checked', false);
            $('#saveBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#saveBtn').show();
          }
          else 
          {
            $('#saveBtn').hide();
          }
        });
});
// function saveAttendance()
// {
//     vsr url = '<?php echo $ajaxUrl;?>';
//     $.post(url, $('#PrisonerAttendanceShowPrisonersForm').serialize(), function(res) {

//         if (res) {
//             //$('#listingDiv').html(res);
//         }
//     });
// }
</script>                                  