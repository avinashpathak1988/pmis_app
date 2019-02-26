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
            'action'                => 'prisonerAgeVerificationAjax', 
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
            <th>Verify Age</th>
            <th>View Age</th>
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
               <?php $isverify = $funcall->isVerifyAge($data['Prisoner']['id']); 
               // debug($isverify);

             if ($isverify == 0 ) {

             ?>
            

            <?php if(@$file_type != 'pdf') { ?>
               <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['Prisoner']['id']; ?>" onclick="showDetails(<?php echo $data['Prisoner']['id']; ?>,<?php echo $data['Prisoner']['id']; ?>,<?php echo $data['Prisoner']['id']; ?>)">Verify Age</button>

            <!-- Modal -->
            <div id="myModal<?php echo $data['Prisoner']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Verify Age</h4>
                  </div>
                  <div class="modal-body" id="show_details<?php  ?>">
                    <?php  echo $this->Form->create('PrisonerAgeVerification',array('class'=>'form-horizontal','url'=>'prisonerAgeVerification', 'enctype'=>'multipart/form-data')); ?>
                     <?php echo $this->Form->input('prisoner_id',array('type'=>'hidden','value'=> $data['Prisoner']['id']));                   ?>

                     <div class="span12">
                        <div class="control-group ">
                                                
                                                <label class="control-label" style="margin-right: 10px">Medical Examination<?php echo $req; ?> </label>
                                                <div class="controls uradioBtn">
                                                    <?php 
                                                    $age = "Under Age";
                                                    $options2= $verify_age;
                                                    $attributes2 = array(
                                                        'legend' => false, 
                                                        'value' => $age,
                                                        'style' => 'float:left; margin-left: 10px'
                                                    );
                                                    echo $this->Form->radio('age', $options2, $attributes2);
                                                    ?>

                                                </div>
                                            </div>
                                          </div>
                                            <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label">
                                                    Age Verification  
                                                    <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type photo!" id='example'></i>
                                                    <?php echo $req; ?>:
                                                </label>
                                            <div class="controls">
                                                <div id="prevImage" class="" style="margin-top: 10px;">
                                                <?php $is_photo = '';
                                                    if(isset($this->request->data["PrisonerAgeVerification"]["photo"]) && !is_array($this->request->data["PrisonerAgeVerification"]["photo"]))
                                                    {
                                                        $is_photo = 1;?>
                                                       <a class="example-image-link" href="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["PrisonerAgeVerification"]["photo"];?>" data-lightbox="example-set"> <img src="<?php echo $this->webroot; ?>app/webroot/files/prisnors/<?php echo $this->request->data["PrisonerAgeVerification"]["photo"];?>" alt="" width="150px" height="150px"></a>
                                                    <?php }?>
                                                </div>
                                                <!--<span id="previewPane" class="img_preview_panel">-->
                                                <span id="previewPane" class="">
                                                    <a class="example-image-link preview_image" href="" data-lightbox="example-set"><img id="img_prev" src="#" class="img_prev" /></a>
                                                    <span id="x" class="remove_img">[X]</span>
                                                </span>
                                                <div class="clear"></div>
                                                <?php echo $this->Form->input('photo',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'photo', 'onchange'=>'readURL(this);', 'required'=>false));?>
                                                <?php echo $this->Form->input('is_photo',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'is_photo', 'value'=>$is_photo));?>
                                            </div>
                                        </div>
                                        <div class="form-actions " align="center" style="background:#fff;">
                                          <?php echo $this->Form->button('Verify',array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success on', 'id'=>'verifyBtn'))?>
                                      </div>
                                      
                                    </div> 
<?php echo $this->Form->end();
                    ?>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            <?php } ?>
            <?php } else{
              echo "Age Verified";
              // debug($data['PrisonerAgeVerification']['photo']);
          } ?>

          
           

            </td>
             <td>
                <?php if ($data["PrisonerAgeVerification"]["photo"] != '') {
                     echo $this->Html->link('View', '../files/prisnors/'.$data["PrisonerAgeVerification"]["photo"], array('escape'=>false,'target'=>'_blank', 'class'=>'btn btn-primary btn-mini'));
                }
                else{
                    echo Configure::read('NA');
                } ?>
            
                 
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
<?php $ajaxFeedbackUrl = $this->Html->url(array('controller'=>'medicalRecords','action'=>'prisonerAgeVerificationAjax'));  ?>
<script>
  // alert(2);
  function verifyAge() {
    alert(1);

    
     

  }
</script>
