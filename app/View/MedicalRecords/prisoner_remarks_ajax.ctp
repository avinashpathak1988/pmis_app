<style>
#forwardBtnclinicalattendance
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>

<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $sortOption = array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'medicalRecords',
            'action'                => 'prisonerRemarksAjax', 
            // 'prisonerList'          => $prisonerList ,
            
        )
    );
    $this->Paginator->options($sortOption);         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('')
));
?>

    </div> 
</div>
  <div style="overflow-x:scroll;">                
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Prison Station</th>
            <th>Prisoner Name</th>
            <th>Prisoner Number</th>
            <th>Date Of Admission</th>
            <th>Date Of Birth</th>
            <th>Add Remark</th>
            <th>View Remark</th>
           
            <!-- <th>Date</th> -->
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
            ?>
            <!-- <th>Action</th> -->
            <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td> <?php echo $funcall->getName($data['Prisoner']['prison_id'],"Prison","name");?></td>
            <td><?php echo $data["Prisoner"]["first_name"];?></td>
            <td><?php echo $data["Prisoner"]["prisoner_no"];?></td>
            <td><?php if ($data["Prisoner"]["doa"]!='0000-00-00') {
               echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['Prisoner']['doa']));
            }else{
                echo Configure::read('NA');
            } ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['Prisoner']['date_of_birth'])); ?></td>
            <td>
              
            

           
            

           
               <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['Prisoner']['id']; ?>"onclick="showDetails(<?php echo $data['Prisoner']['id']; ?>,<?php echo $data['Prisoner']['id']; ?>,<?php echo $data['Prisoner']['id']; ?>)">Add Remark</button>

            <!-- Modal -->
            <div id="myModal<?php echo $data['Prisoner']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Remarks</h4>
                  </div>
                  <div class="modal-body" id="show_details">
                    <?php  echo $this->Form->create('PrisonerRemark',array('class'=>'form-horizontal','url'=>'prisonerRemarks', 'enctype'=>'multipart/form-data')); ?>
                    <?php echo $this->Form->input('priosner_id',array('type'=>'hidden','value'=> $data['Prisoner']['id']));?>
                    <?php echo $this->Form->input('prison_id',array('type'=>'hidden','value'=> $this->Session->read('Auth.User.prison_id')));?>
                    <?php echo $this->Form->input('user_id',array('type'=>'hidden','value'=> $this->Session->read('Auth.User.usertype_id')));?>
                     <?php echo $this->Form->input('prisonerdate',array('type'=>'hidden','value'=> date('Y-m-d')));?>
                                    <div class="control-group">
                                    <label class="control-label">Add Remark :</label>
                                      <div class="controls">
                                          <?php echo $this->Form->input('remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Remark','id'=>'remark','rows'=>3,'required','maxlength'=>1000));?>
                                      </div>
                                    </div>
                                      <div class="form-actions " align="center" style="background:#fff;">
                                          <?php echo $this->Form->button('Save',array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success on', 'id'=>'verifyBtn'))?>
                                      </div>
                      <?php echo $this->Form->end();?>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            </td>
            <td>
              <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModalview<?php echo $data['Prisoner']['id']; ?>">View</button>
      
       <!-- Modal -->
            <div id="myModalview<?php echo $data['Prisoner']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">View Details</h4>
                  </div>
        
                  <div class="modal-body">
            <table class="table table-bordered data-table table-responsive">
                        <tbody>
                            <tr>
                                <td><b>Remarks.</b></td>
                                <td><b>Date</b></td>
                            </tr>
                            <?php foreach ($funcall->getPrisonerRemark($data['Prisoner']['id']) as $key => $value) {
                              
                             ?>
                            <tr>
                                <td><?php echo $value['PrisonerRemark']['remark'] ?></td>
                                <td><?php echo date('d-m-Y', strtotime($value['PrisonerRemark']['prisonerdate'])) ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
         
                </div>

              </div>
            </div>
              
            </td>
           
            <!-- <td><?php //echo date('d-m-Y',strtotime($data["StateOfPrisoner"]["prisoner_date"]));?></td> -->
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
            ?>
            
            <?php
            }
            ?>
        </tr>
<?php
        $rowCnt++;
        echo $this->Js->writeBuffer();
    }
?>
    </tbody>
</table>
</div>
<?php
echo $this->Form->end();
}else{
?>
    <span style="color:red;">No records found!</span>
<?php    
}
?>   
<?php $ajaxFeedbackUrl = $this->Html->url(array('controller'=>'medicalRecords','action'=>'prisonerRemarksAjax'));  ?>
<script>
  // alert(2);
  function verifyAge() {
    alert(1);

    
     

  }
</script>
