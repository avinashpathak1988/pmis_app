<?php
 if(is_array($aftercareDetails) && count($aftercareDetails)>0){
    if(!isset($is_excel)){
    
    //debug($aftercareDetails);
?>
 <div class="span5" style="margin-top:-20px">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#aftercareList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'   => array(
                    'controller'            => 'Aftercare',
                    'action'                => 'aftercareAjax',
                  )
              ));         
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
         'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
     ));
    ?>
<?php
    $exUrl = "aftercareAjax";
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
  }
?>
    <?php
        // $exUrl = "indexAjax/id:$id/";
        // $urlExcel = $exUrl.'/reqType:XLS';
        // $urlDoc = $exUrl.'/reqType:DOC';
        // echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")), $urlExcel, array("escape" => false)));
        // echo '&nbsp;&nbsp;';
        // echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")), $urlExcel, array("escape" => false)));
    ?>
    </div>
<div class="widget-box">
    <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
         <h5> After care list</h5>
              <div style="float:right;padding-top: 7px;">
                 &nbsp;&nbsp;
             </div>
    </div>
</div>
<table class="table table-bordered data-table" id="cashidtbl">
    <thead>
    	<tr>
	    	<th>Sl No.</th>
	    	<th>Prisoner No.</th>
        <th>Description</th>
        <th>Prisoner Name</th>
        <th>Responsible Officer</th> 
        <th>Head Remark</th>

        <th>Add Activity</th>
        <th>Actions</th>   	
    	</tr>
    </thead>
    <tbody>
        <?php 
        $count =1;
        foreach($aftercareDetails as $data){
        ?> 
    	<tr>
          <td><?php echo $count; ?></td>
          <td><?php echo $data['Aftercare']['prisoner_id']; ?></td>
          <td><?php echo $data['Aftercare']['description']; ?></td>
          <td><?php echo $data['Aftercare']['name']; ?></td>
          <td><?php echo $funcall->getname($data['Aftercare']['officer'],"User","name"); ?></td>
          <td><?php echo $data['Aftercare']['head_remark']; ?></td>

          <td><button type="button" class="btn btn-mini btn-info" onclick="addHeadRemark(<?php echo $data['Aftercare']['id'] ?>);">Add Activity</button></td>
    	    <td>
              <?php echo $this->Form->create('AfterCareEdit',array('url'=>'/Aftercare/addAfterCare','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Aftercare']['id']));
                    
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

                   <?php echo $this->Form->create('AfterCareDelete',array('url'=>'/Aftercare/index/','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Aftercare']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php 
                    echo $this->Form->end();
                     ?> 
              </td>
      </tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 

<?php } ?>
<script type="text/javascript">
           function addHeadRemark(id){
    $('#AftercareIndexForm #aftercare_id').val(id);
    $('#addHeadRemarkModal').modal('show');
  }
</script>