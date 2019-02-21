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
                    <h5>Create Article/Item</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Item/Article List',array('controller'=>'Earnings','action'=>'itemList'),array('escape'=>false,'class'=>'btn btn-warning btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Item',array('class'=>'form-horizontal','url' => '/earnings/itemList','enctype'=>'multipart/form-data'));
                                echo $this->Form->input('id',array('type'=>'hidden'));
                                echo $this->Form->input('prison_id',array(
                                    'type'=>'hidden',
                                    'class'=>'prison_id',
                                    'value'=>$this->Session->read('Auth.User.prison_id')
                                  ));
                                  ?>
                                <div class="row-fluid" style="padding-bottom: 14px;">
                                    <div class="span6">
                                      <div class="control-group">
                                            <label class="control-label">Prison Station<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php $is_prison_disable = '';
                                                if(!empty($default_prison_id))
                                                    $is_prison_disable = 'disabled';
                                                echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'prison_id', 'default'=>$default_prison_id,$is_prison_disable));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Name Of Item <?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php $is_readonly = '';
                                                if(isset($this->data['Item']['is_added_by_admin']) && ($this->data['Item']['is_added_by_admin'] == 1))
                                                    $is_readonly= 'readonly';
                                                echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control span11','required','type'=>'text','placeholder'=>'Enter Name of Item','id'=>'name','maxlength'=>20, $is_readonly));?> 
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                    <div class="row-fluid">
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                      <div class="control-group">
                                            <label class="control-label">Price<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('price',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11','type'=>'text','placeholder'=>'Enter Price ','id'=>'price','maxlength'=>10));?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Comment :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('comment',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter remarks','class'=>'form-control span11','type'=>'text','required'=>false));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6 hidden">
                                        <div class="control-group">
                                            <label class="control-label">Is Enable?<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php 
                                                if(isset($this->request->data['Item']['is_enable']) && ($this->request->data['Item']['is_enable'] == 0))
                                                {
                                                    echo $this->Form->input('is_enable', array('checked'=>false,'div'=>false,'label'=>false));
                                                }
                                                else 
                                                {
                                                    echo $this->Form->input('is_enable', array('checked'=>true,'div'=>false,'label'=>false));
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                    
                                     
                                    
                                </div>

                              <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','onclick'=>"javascript:return validateForm();"))?>
                        </div>
                                <?php echo $this->Form->end();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
      $(function(){
        $("#ItemCreatearticleForm").validate({ 
            rules: {  
                'data[Item][prison_id]': {
                    required: true,
                },
                'data[Item][name]': {
                    required: true,
                },
                'data[Item][price]': {
                    required: true,
                },
                'data[Item][comment]':{
                    required: true,
                    maxlength:100
                }
            },
            messages: {
                'data[Item][prison_id]':{
                    required: 'Please select prison',
                },
                'data[Item][name]': {
                    required: 'Please enter Name',
                },
                'data[Item][price]': {
                    required: 'Please enter price',
                },
                'data[Item][comment]': {
                    required: 'Please Enter comment',
                    maxlength:"should be less than 100 characters"
                    }
            }
        });
    });
</script>    
