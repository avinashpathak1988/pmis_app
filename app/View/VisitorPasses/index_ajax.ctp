<?php
//debug($datas);
if(is_array($datas) && count($datas)>0){?>
<style type="text/css">
  body input[type="text"],body input[type="textarea"]
    {
        border: 0;
        border-bottom: 1px solid #ccc;
        outline: 0;
        margin-left:2px;
        margin-right:2px;

    }
    .form-row{
      margin-bottom: 20px;
    }
    .form-text-full{
      width:100%;
    }
    textarea{
       background-image: -webkit-linear-gradient(left, white 10px, transparent 10px), -webkit-linear-gradient(right, white 10px, transparent 10px), -webkit-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
      background-image: -moz-linear-gradient(left, white 10px, transparent 10px), -moz-linear-gradient(right, white 10px, transparent 10px), -moz-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
      background-image: -ms-linear-gradient(left, white 10px, transparent 10px), -ms-linear-gradient(right, white 10px, transparent 10px), -ms-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
      background-image: -o-linear-gradient(left, white 10px, transparent 10px), -o-linear-gradient(right, white 10px, transparent 10px), -o-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
      background-image: linear-gradient(left, white 10px, transparent 10px), linear-gradient(right, white 10px, transparent 10px), linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
      background-size: 100% 100%, 100% 100%, 100% 31px;
      border: 1px solid #ccc;
      border-radius: 8px;
      line-height: 31px;
      font-family: Arial, Helvetica, Sans-serif;
      padding: 8px;
      border:0;
    }

</style>
<?php
 // debug($datas);
  if(!isset($is_excel)){
?>
<style type="text/css">
  .prisoner-item-show{
    padding-left: 20px;
  }
</style>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'VisitorPasses',
            'action'                => 'indexAjax',
        )+$searchData
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>

<?php
    $exUrl = $this->Html->url(array('controller'=>'VisitorPasses','action'=>'indexAjax')+$searchData,true);
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
  $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
  echo '&nbsp;&nbsp;';
  echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
  ?>
    </div>
</div>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
          <th>Sl no</th>  
          <th>Gate Pass Number</th>                
          <th>Prison</th> 
          <th>Prisoner Number</th>
          <th>Prisoner Type</th>
          <th>Purpose</th>
          <th>Visit Date</th>
         <!--  <th>Valid Till</th>
          <th>Visit days</th> -->
          <th>Issue Date</th>
          <th>View pass</th>
          <th>Actions</th>

  </thead>
  <tbody> 
    <?php
      $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
      foreach($datas as $data){ ?>
            <tr>
              <td><?php echo $rowCnt ?></td>
              <td><?php echo $data['VisitorPass']['gate_pass'] ?></td>
              <td><?php echo $data['Prison']['name'] ?></td>
              <td><?php echo $data['Prisoner']['prisoner_no'] ?></td>
              <td><?php echo $funcall->getPrisonerType($data['Prisoner']['prisoner_type_id']); ?></td>
              <td><?php echo $data['VisitorPass']['purpose'] ?></td>
              <td>
                <?php if($data['VisitorPass']['is_suspended'] == '1'){ ?>
                    Previous Date :<br/>
                    <?php echo $data['VisitorPass']['valid_form'] != ''?date('d-m-Y',strtotime($data['VisitorPass']['valid_form'])):''; ?> <br/><br/>
                    New Date of Visit:<br/>

                <?php echo $data['VisitorPass']['suspended_date'] != ''?date('d-m-Y',strtotime($data['VisitorPass']['suspended_date'])):''; ?>
                <?php }else{ ?>

                    <?php echo $data['VisitorPass']['valid_form'] != ''?date('d-m-Y',strtotime($data['VisitorPass']['valid_form'])):''; ?> 
                <?php } ?>
                  

                </td>
              <!-- <td><?php echo $data['VisitorPass']['valid_till'] != ''?date('d-m-Y',strtotime($data['VisitorPass']['valid_till'])):''; ?></td>
              <td><?php echo $data['VisitorPass']['days'] ?></td> -->

              <td><?php echo $data['VisitorPass']['issue_date'] != ''?date('d-m-Y',strtotime($data['VisitorPass']['issue_date'])):''; ?></td>
              <td><button type="button" class="btn btn-success btn-mini button-gap" data-toggle="modal" data-target="#printModal_<?php echo $data["VisitorPass"]["id"]; ?>">
                  View
                </button>
                <div class="modal fade" id="printModal_<?php echo $data['VisitorPass']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                  <?php echo $this->Form->create('VisitorPass',array('class'=>'','style'=>"width:100%",'id'=>'PassPrintForm_'.$data["VisitorPass"]["id"]));?>

                    <div class="modal-content">
                          
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Prisoner Pass</h5> 
                       <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button> -->
                      </div>
                      <div class="modal-body">
                                <?php $passVisitors = $data['PassVisitor']; ?>
                              <div style="height:65px;width:100%;margin-bottom:10px;background: #902d2b;">
                                  <div style="position: absolute;left: 32%;">
                                    <img src="<?php echo $this->webroot;?>ugandalogo.png" class="img" alt="Uganda Prisons Service" style="height: 55px;float: left;">
                                    <img src="<?php echo $this->webroot;?>theme/img/logo1.png" alt="Uganda Prisons Service" title="Uganda Prisons Service" style="margin-left: 10px;float: left;width: 130px;margin-top: 3px;">
                                  </div>
                                  
                              </div>
                              <div class="span12" >
                                <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','type'=>'hidden','value'=>$data["VisitorPass"]["id"],'required'=>false))?>
                                 <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="form-text" style="width:10%">Pass No :</span>
                                    <span class="" >
                                            <?php echo $this->Form->input('gate_pass',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:30%;','type'=>'text','value'=>$data["VisitorPass"]["gate_pass"],'required'=>false))?>
                                    </span>

                                    <span class="form-text" style="width:10%">Prison station :</span>
                                    <span class="">
                                            <?php echo $this->Form->input('prison',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:30%;','type'=>'text','value'=>$data["Prison"]["name"],'required'=>false))?>
                                    </span>

                                </div>
                                <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="form-text" style="width:10%">Prisoner to be Visit:</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('prisoner',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:75%;','type'=>'text','value'=>$data["Prisoner"]["prisoner_no"],'required'=>false))?>
                                    </span>
                                    
                                </div>
                                <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="form-text" style="width:10%"> Date & Time For Visit:</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('date_time',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:73%;','type'=>'text','value'=>date('d-m-Y H:i:s',strtotime($data["VisitorPass"]["valid_form"])),'required'=>false))?>
                                    </span>
                                    
                                </div>
                                 <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="form-text" style="width:10%">Purpose:</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('purpose',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:87%;','type'=>'text','value'=>$data["VisitorPass"]["purpose"],'required'=>false))?>
                                    </span>
                                    
                                </div>
                                <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="form-text" style="width:10%">Issue Date:</span>
                                    <span class="" style="">
                                            <?php echo $this->Form->input('issue_date',array('div'=>false,'label'=>false,'class'=>'dotted-input','readonly','style'=>'width:85%;','type'=>'text','value'=>date('d-m-Y H:i:s',strtotime($data["VisitorPass"]["issue_date"])),'required'=>false))?>
                                    </span>
                                    
                                </div>
                                <br/>
                                <div class="visitors-wrapper">
                                  <div style="text-align: center;width: 100%;font-size:16px;font-weight:700;line-height: 1.42857143;color: #a03230;">Visitors Allowed</div>
                                  <table style="width: 100%;" class="table table-bordered">
                                    <thead>
                                          <tr>
                                            <th style="border: 1px solid;">Id Details</th>
                                            <th style="border: 1px solid;">Contact</th>
                                            <th style="border: 1px solid;">Relation</th>
                                            <th style="border: 1px solid;">Profession</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      
                                <?php foreach ($passVisitors as $passVisitor) { ?>
                                <tr>
                                  <td style="border: 1px solid;"><?php echo $passVisitor['Iddetail']['name'] ?> : <?php echo $passVisitor['nat_id'];?></td>
                                  <td style="border: 1px solid;"><?php echo $passVisitor['contact'];?></td>
                                  <td style="border: 1px solid;"><?php echo isset($passVisitor['Relationship']['name'])?$passVisitor['Relationship']['name']:'';?></td>
                                  <td style="border: 1px solid;"><?php echo $passVisitor['profession'];?></td>

                                </tr>
                                <?php }?>
                                    </tbody>

                                  </table>

                                </div>
                                <br/>
                                <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="" >
                                            <?php echo $this->Form->input('comm',array('div'=>false,'label'=>false,'class'=>'dotted-input','style'=>'width:50%;float:right;margin-right:5%;text-align:center;','type'=>'text','readonly','required'=>false,'value'=>$comm['User']['name']))?>
                                    </span>
                                    
                                </div>
                                <div class="form-row" style="margin-bottom: 20px;">
                                    <span class="form-text"  style="float: right;width:50%;">
                                            Commissioner General of Prisons
                                    </span>
                                    
                                </div><br/><br/><br/>
                                <div class="form-row" style="margin-bottom: 20px;">
                                   <h5> Notes to Visitors: </h5>
                                   <ul style="list-style-type: decimal;">
                                      <li>The visitor is advised to co-operate with the authority and to confine his visit for the purpose stated in this pass only.</li>
                                      <li>The pass is valid for the person named in the pass only. </li>
                                      <li>The Commissioner General of Prisons can invalidate or suspend the pass if he considered such action is necessary</li>
                                      <li>Unwarranted interfence in the internal security and administration of the Prison in an offence.</li>
                                   </ul>
                                </div>
                        </div>

                          
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary printPass" data-id="<?php echo $data['VisitorPass']['id']; ?>" onclick="printForm(<?php echo $data['VisitorPass']['id']; ?>)">Print</button>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
                      </div>

                    </div>
                                <?php echo $this->Form->end();?>

                  </div>
                </div>
                <!-- modal end -->

              </td>

              <td>

              <?php echo $this->Form->create('VisitorPassEdit',array('url'=>'/VisitorPasses/add','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['VisitorPass']['id']));
                    
                    ?>
                    <button class="btn btn-success" style="margin-bottom: 10px;" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

                   <?php echo $this->Form->create('VisitorPassDelete',array('url'=>'/VisitorPasses/index','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['VisitorPass']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php 
                    echo $this->Form->end();
                     ?> 
                     <br/>
                     <br/>
                     <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
                        { ?>

                      <?php if($data['VisitorPass']['is_valid'] == '1'){ ?>

                      <button type="button" class="btn btn-warning btn-mini button-gap suspend" data-id="<?php echo $data['VisitorPass']['id']; ?>">
                        Suspend
                      </button><br/>
                        <button type="button" class="btn btn-warning btn-mini button-gap invalid" style="margin-top: 5px;" data-id="<?php echo $data['VisitorPass']['id']; ?>">
                        Mark Invalid
                      </button>
                      <?php }else{ ?>
                        <span style="color: red;">Invalid Pass</span>
                      <?php } ?>
                      

                     <?php }
                      ?>
                      
              </td>
              


            </tr>

      <?php $rowCnt++; } ?>
  </tbody>
</table>


<?php
}else{
echo Configure::read("NO-RECORD");    
}

}
?>
