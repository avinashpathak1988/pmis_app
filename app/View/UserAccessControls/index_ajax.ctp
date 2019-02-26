<?php 
//debug($data); exit;
$aceessData = '';
if(isset($data) && count($data)>0)
{
    foreach($data as $key=>$val)
    {
        if(isset($val['UserAccessControl']['module_id']) && !empty($val['UserAccessControl']['module_id']))
        {
            $menu_id = $val['UserAccessControl']['menu_id'];
            $aceessData[$menu_id]['id'] = $val['UserAccessControl']['id'];
            $aceessData[$menu_id]['menu_id']      =  $val['UserAccessControl']['menu_id'];
            $aceessData[$menu_id]['is_add']      =  $val['UserAccessControl']['is_add'];
            $aceessData[$menu_id]['is_edit']     =  $val['UserAccessControl']['is_edit'];
            $aceessData[$menu_id]['is_delete']   =  $val['UserAccessControl']['is_delete'];
            $aceessData[$menu_id]['is_view']     =  $val['UserAccessControl']['is_view'];
            $aceessData[$menu_id]['is_review']   =  $val['UserAccessControl']['is_review'];
            $aceessData[$menu_id]['is_approve']  =  $val['UserAccessControl']['is_approve'];
        }
    }
}
//debug($aceessData);
if(isset($module_ids) && count($module_ids) > 0)
{?>
    <div class="clear" style="margin-top:20px;"></div>
    <div class="row-fluid">
        <div class="span2 text-right"><strong>Menu</strong></div>
        <div class="span1"></div>
        <div class="span9 text-center">
            <div class="span2"><strong>Add</strong></div>
            <div class="span2"><strong>Edit</strong></div>
            <div class="span2"><strong>Delete</strong></div>
            <div class="span2"><strong>View</strong></div>
            <div class="span2"><strong>Review</strong></div>
            <div class="span2"><strong>Approve</strong></div>
        </div>
    </div>
    <?php $i = 0; 
    foreach($module_ids as $key=>$name)
    {
        $is_add = ''; $is_edit = ''; $is_delete = ''; $is_view = ''; $is_review = ''; $is_approve = '';
        $id = '';
        if(is_array($aceessData) && count($aceessData)>0)
        {
            if(array_key_exists($key, $aceessData))
            {
                $id = $aceessData[$key]['id'];
                if(isset($aceessData[$key]['is_add']) && ($aceessData[$key]['is_add'] == 1))
                {
                    $is_add = 'checked';
                }
                if(isset($aceessData[$key]['is_edit']) && ($aceessData[$key]['is_edit'] == 1))
                {
                    $is_edit = 'checked';
                }
                if(isset($aceessData[$key]['is_delete']) && ($aceessData[$key]['is_delete'] == 1))
                {
                    $is_delete = 'checked';
                }
                if(isset($aceessData[$key]['is_view']) && ($aceessData[$key]['is_view'] == 1))
                {
                    $is_view = 'checked';
                }
                if(isset($aceessData[$key]['is_review']) && ($aceessData[$key]['is_review'] == 1))
                {
                    $is_review = 'checked';
                }
                if(isset($aceessData[$key]['is_approve']) && ($aceessData[$key]['is_approve'] == 1))
                {
                    $is_approve = 'checked';
                }
            }
        }?>
        <div class="row-fluid">
            <div class="span2 text-right">
                <?php echo ucfirst($name);?>
                <?php echo $this->Form->input('UserAccessControl.'.$i.'.module_id', array('type'=>'hidden','value'=>$module_id));?>
                <?php echo $this->Form->input('UserAccessControl.'.$i.'.menu_id', array('type'=>'hidden','value'=>$key))?>
                <?php echo $this->Form->input('UserAccessControl.'.$i.'.prison_id', array('type'=>'hidden','class'=>'prison_id', 'value'=>$prison_id))?>
                <?php echo $this->Form->input('UserAccessControl.'.$i.'.user_type', array('type'=>'hidden','class'=>'user_type', 'value'=>$user_type))?>
                 <?php echo $this->Form->input('UserAccessControl.'.$i.'.id', array('type'=>'hidden','class'=>'user_type'))?>
            </div>
            <div class="span1"></div>
            <div class="span9 text-center">
                <div class="span2">
                    <?php echo $this->Form->input('UserAccessControl.'.$i.'.id', array('type'=>'hidden','value'=>$id));
                    echo $this->Form->input('UserAccessControl.'.$i.'.is_add',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false, $is_add));?>
                </div>
                <div class="span2">
                    <?php echo $this->Form->input('UserAccessControl.'.$i.'.is_edit',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false, $is_edit));?>
                </div>
                <div class="span2">
                    <?php echo $this->Form->input('UserAccessControl.'.$i.'.is_delete',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false, $is_delete));?>
                </div>
                <div class="span2">
                    <?php echo $this->Form->input('UserAccessControl.'.$i.'.is_view',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false, $is_view));?>
                </div>
                <div class="span2">
                    <?php echo $this->Form->input('UserAccessControl.'.$i.'.is_review',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false, $is_review));?>
                </div>
                <div class="span2">
                    <?php echo $this->Form->input('UserAccessControl.'.$i.'.is_approve',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','required'=>false, $is_approve));?>
                </div>
            </div>
        </div>
        <?php $i = $i+1;
    }
}
?>
<div class="form-actions" align="center">
    <?php echo $this->Form->input('Save', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
</div>   