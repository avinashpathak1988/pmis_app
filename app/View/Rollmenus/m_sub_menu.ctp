
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Sub Menu</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Sub Menu List',array(
                                    
                                    'action'=>'m_sub_menu_list'
                                ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
                                )); ?>
              <?php //echo $this->Html->link('Users List',array(
                  //'action'=>'index',
                 // array('escape'=>false,'class'=>'btn btn-success'),
              //));
              ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding">
    <?php echo $this->Form->create('MSubMenu',array('url'=>'/Rollmenus/m_sub_menu','class'=>'form-horizontal','admin'=>false)); ?>  
<?php echo $this->Form->input('id',array('type'=>'hidden','class'=> 'tbox','label'=>false,'div'=>false)); ?>
               
               <div class="control-group">
                 <label class="control-label">Menu Name :<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                 <?php echo $this->Form->input('m_menu_id', array('label'=> false,'type'=>'select','empty'=>'--select--','options' => $menuList,'class'=>'span11','id' => 'm_menu_id','required')); ?>
                 
                  
                 </div>
               </div>
               <div class="control-group">
                 <label class="control-label">Sub Menu Name :<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                 <?php echo $this->Form->input('name',array('type'=>'text','class'=> 'span11','label'=>false,'div'=>false,'required')); ?>
                 </div>
               </div>
               <div class="control-group">
                 <label class="control-label">Sub Menu Url :</label>
                 <div class="controls">
                  <?php echo $this->Form->input('sub_menu_url',
		                  array(
		                  'type'=>'text',
		                  'class'=> 'span11',
		                  'label'=>false,
		                  'div'=>false,
		                  'placeholder'=>'Sub Menu URL'
		                  )); 
                  ?>
                  
                 </div>
               </div>
               <div class="control-group">
                 <label class="control-label">Sub Menu Icon :<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                  <?php echo $this->Form->input('sub_menu_icon',array('type'=>'text','class'=> 'span11','label'=>false,'div'=>false,'placeholder'=>'Menu Icon','required')); ?>
                  
                 </div>
               </div> 
               <div class="control-group">
                 <label class="control-label">Sub Menu Order :</label>
                 <div class="controls">
                 <?php echo $this->Form->input('sub_menu_order',array('type'=>'text','class'=> 'span11','size' => 1,'maxlength' => 2,'label'=>false,'div'=>false,'placeholder'=>'Menu Oredr')); ?>
                 
                  
                 </div>
               </div>
               <div class="control-group">
                 <label class="control-label">Is Enable ?</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('is_enable',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$is_enable,
                         'default'=>1
                       ));
                    ?>
                 </div>
               </div>
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  

