<style type="text/css">
	
	.submit-btn-container{
		margin-top: 20px;
		text-align: center;
	}
	.prohibited-property-list{
		list-style: none;
	}
	.prohibited-item{
		list-style: none;
		padding: 10px;
        display: inline-block;
	}
	div.checker{
		margin-top:-12px;
	}
</style>

<div class="container-fluid">
   <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prohibited properties</h5>
                   
                </div>
                <div class="widget-content nopadding"> 
                	<ul class="prohibited-property-list">
                		<form id="prohibitedItemsForm" name="prohibitedItemsForm">
                			
                		
                		<?php foreach ($propertyItemsList as $item) { ?>
                			<li class="prohibited-item">
                				<?php if($item['Propertyitem']['is_prohibited'] ==1) {?>
                				<input type="checkbox" checked name="property_<?php echo $item['Propertyitem']['id'] ?>"><?php echo $item['Propertyitem']['name'] ; ?>
                				<?php }else{ ?>

                					<input type="checkbox"  name="property_<?php echo $item['Propertyitem']['id'] ?>" onclick=""><?php echo $item['Propertyitem']['name'] ; ?>
                				<?php } ?>
                			</li>
                	 <?php	} ?>
                	 			<div class="submit-btn-container">
                	 				<button type="button" class="btn btn-success" onclick="submitProhibitedItems()">save</button>
                	 			</div>
                			</form>
                	</ul>
                </div>
             </div>
            
        </div>
    </div>    
</div>   
<?php
$ajaxUrlSubmitProhibited = $this->Html->url(array('controller'=>'Properties','action'=>'saveProhibitedProperty'));
?>
<script type="text/javascript">
	function submitProhibitedItems(){
		var url ='<?php echo $ajaxUrlSubmitProhibited; ?>';
    $.post(url,$('#prohibitedItemsForm').serialize(), function(res) {
       	window.location.reload();
    });
	}
</script>