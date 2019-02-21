

 <?php
// var_dump($nonFormalDatas);
 if(is_array($nonFormalDatas) && count($nonFormalDatas)>0){
    
?>
 <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#NonFormalEducationList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Education',
                                                    'action'                => 'NonFormalDataAjax',
                                                    
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
<div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5>Non Formal Education List</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
<table class="table table-bordered data-table" id="cashidtbl">
    <thead>
    	<tr>
	    	<th>Sr No.</th>
	    	<th>Counselor</th>
	    	<th>Prisoner No.</th>
	    	<th>Prisoner Name</th>
	    	<th>Date of Counselling</th>
	    	<th>Opinion By Prisoner</th>
	    	<th>Non Formal Program</th>
	    	<th>Module</th>
	    	<th>Module Stage</th>

    	</tr>



    </thead>

    <tbody>
        <?php 
        $count =1;
        foreach($nonFormalDatas as $data){
        ?> 
    	<tr>
            <td><?php echo $count; ?></td>
    		<td><?php echo $data['Councellor']['name']; ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['Prisoner']['first_name'] . ' ' . $data['Prisoner']['last_name']  ; ?></td>

            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['NonFormalEducation']['date_of_councelling']));?></td>
            <td><?php echo $data['NonFormalEducation']['prisoner_opinion']; ?></td>
            <td><?php echo $data['NonFormalProgram']['name']; ?></td>
            <td><?php echo $data['NonFormalProgramModule']['name']; ?></td>
            <td><?php echo $data['ModuleStage']['name']; ?></td>

            
    	</tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 

<?php } ?>