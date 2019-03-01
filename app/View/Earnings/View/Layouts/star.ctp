<?php
$cakeDescription = __d('cake_dev', 'Uganda Prisons Service');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $cakeDescription ?>
        </title>    
          <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
                <?php
                    echo $this->fetch('meta');
                    echo $this->fetch('css');
                    echo $this->Html->meta('icon');

                    echo $this->Html->css(array('../theme/css/bootstrap.min.css', 'jquery-ui','../theme/css/bootstrap-responsive.min','../theme/css/select2','../theme/css/matrix-style','../theme/css/matrix-media','../theme/font-awesome/css/font-awesome.css','http://fonts.googleapis.com/css?family=Open+Sans:400,700,800','../alertify/themes/alertify.core','../alertify/themes/alertify.default', 'style','../theme/css/bootstrap-datetimepicker.min','../theme/css/lightbox','../theme/css/datepicker','../theme/css/rating'));
                //'../theme/css/datepicker',
                   // ,'timepicki'

                    echo $this->Html->script(array('../theme/js/lightbox-plus-jquery.min','../theme/js/jquery.min', 'jquery-ui','../theme/js/jquery.ui.custom','../theme/js/bootstrap.min','../theme/js/select2.min','../theme/js/jquery.dataTables.min','../theme/js/matrix','../alertify/src/alertify','../theme/js/jquery.validate','../theme/js/additional-methods.min','tabbedcontent.min', 'analytics.min','../theme/js/bootstrap-datetimepicker.min','timepicki','../theme/js/bootstrap-datepicker','../theme/js/rating','../highchart/code/highcharts'));

                    //'../theme/js/bootstrap-datepicker',
                  echo $this->fetch('script');
                ?> 
   
    <script type="text/javascript">

    $(function () {

      $('.pmis_select').select2({
        placeholder: "-- Select --",
        allowClear: true
      });

      //Hide loader -- START -- 
      setTimeout(function(){
        $('#pmis_loader').hide();
      }, 700);
      //Hide loader -- END -- 

 // console.write("<!doctype html><html><head><meta charset=utf-8></head><body><p>You cannot find this in the page source. (Your page needs to be in this document.write argument.)</p></body></html>")
});  
            $(document).on('keypress','.alpha',function (event){
                var regex = new RegExp("^[a-zA-z ]+$"); 
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 39 || event.keyCode == 40){ 
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
            //Dynammic modal popup
          $(document).on('click','.pop',function (event){
            var pageTitle = $(this).attr('pageTitle');
            var pageBody = $(this).attr('pageBody');
            $("#dynamic-modal .modal-title").html(pageTitle);
            $("#dynamic-modal .modal-body").html(pageBody);
            $("#dynamic-modal").modal("show");
            return false;
          });
        $(document).ready(function(){
          //alert('hi');
          // var currentInnerHtml;
          // var element = new Image();
          // var elementWithHiddenContent = document.querySelector("body");
          // var innerHtml = elementWithHiddenContent.innerHTML;

          // // element.__defineGetter__("class", function() {
          // //     currentInnerHtml = "";
          // // });

          // setInterval(function() {
          //     currentInnerHtml = innerHtml;
          //     console.log(element);
          //    console.clear();
          //     elementWithHiddenContent.innerHTML = innerHtml;
          // }, 500);

          jQuery.validator.addMethod("greaterThan", 
            function(value, element, params) {

                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) > new Date($(params).val());
                }
                // return isNaN(value) && isNaN($(params).val()) 
                //     || (Number(value) > Number($(params).val())); 
            },'Must be greater than {0}.');

          jQuery.validator.addMethod("greaterThanOrEqual", 
            function(value, element, params) {
              if(value == '')
              {
                return 1;
              }
              else {
                var date1 = value.split('-'); 
                var cdate1 = (date1[1])+'/'+date1[0]+'/'+date1[2];
                var date2 = $(params).val().split('-');
                var cdate2 = (date2[1])+'/'+date2[0]+'/'+date2[2];
                var fdate1 = new Date(cdate1);
                var fdate2 = new Date(cdate2);
                return fdate1.getTime() >= fdate2.getTime();
              }
            },'Must be greater than or equal {0}.');

          jQuery.validator.addMethod("greaterThanOrEqualAmount", 
            function(value, element, params) {
              if(value == '')
              {
                return 1;
              }
              else {
                var next_amount = $(params).val(); 
                if(parseFloat(value) >= parseFloat(next_amount))
                {
                  return 1;
                }
                else 
                {
                  return 0;
                }
              }
            },'Must be greater than or equal {0}.');

          

          // jQuery.validator.addMethod("lessThanOrEqual", 
          //   function(value, element, params) {

          //       if (!/Invalid|NaN/.test(new Date(value))) {
          //           return new Date(value) <= new Date($(params).val());
          //       }

          //       // return isNaN(value) && isNaN($(params).val()) 
          //       //     || (Number(value) <= Number($(params).val())); 
          //   },'Must be less than or equal {0}.');

          jQuery.validator.addMethod("lessThanOrEqual", 
            function(value, element, params) {
              if(value == '')
              {
                return 1;
              }
              else {
                var date1 = value.split('-'); 
                var cdate1 = (date1[1])+'/'+date1[0]+'/'+date1[2];
                var date2 = $(params).val().split('-');
                var cdate2 = (date2[1])+'/'+date2[0]+'/'+date2[2];
                var fdate1 = new Date(cdate1);
                var fdate2 = new Date(cdate2);
                return fdate1.getTime() <= fdate2.getTime();
              }
            },'Must be greater than or equal {0}.');
                      
          $.validator.addMethod("loginRegex", function(value, element) {
              return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
          }, "Username must contain only letters, numbers, or dashes.");

          $.validator.addMethod("datevalidateformat", function(value, element) {
            //return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
            var dtRegex = new RegExp("^([0]?[1-9]|[1-2]\\d|3[0-1])-(01|02|03|04|05|06|07|08|09|10|11|12)-[1-2]\\d{3}$", 'i');
            return dtRegex.test(value);
          });

          $.validator.addMethod("datevalidateformatnew", function(value, element) {
            var temp = value.split('-');
            var tempyr = temp[2].split(' ');
            var d = new Date(tempyr[0] + '-' + temp[0] + '-' + temp[1]);
            return (d && (d.getMonth() + 1) == temp[0] && d.getDate() == Number(temp[1]) && d.getFullYear() == Number(tempyr[0]));
          });

           $.validator.addMethod("valueNotEquals", function(value, element, arg){
              return arg !== value;
          }, "Please select valid data");

          $.validator.addMethod("check_date_of_birth", function(value, element) {

              var res = value.split("-");
              var day = res[0];
              var month = res[1];
              var year = res[2];
              var age =  18;

              var mydate = new Date(); 
              mydate.setFullYear(year, month-1, day);

              var currdate = new Date(); 
              currdate.setFullYear(currdate.getFullYear() - age);

              return currdate > mydate;

          }, "You must be at least 18 years of age.");

          jQuery.validator.addMethod("alphanumeric", function(value, element) {
                  return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
          });
          jQuery.validator.addMethod("alphanumericsp", function(value, element) {
                  return this.optional(element) || /^[ A-Za-z0-9/-]*$/.test(value);
          });
         
          //prisoner no validation 
          $.validator.addMethod("prisonerNo", function(value, element) {
            return this.optional(element) || /^[a-z0-9\/\s]+$/i.test(value);
          }, "Only letters, numbers, and slashes.");

          //$('select').select2('val', '');
          $("input").on("keypress", function(e) {
              if (e.which === 32 && !this.value.length)
                  e.preventDefault();
          });
          $("input.nospace").on("keypress", function(e) {
              if (e.which === 32)
                  e.preventDefault();
          });
            
            $(document).on('keypress','.alphanumeric',function (event){
                var regex = new RegExp("^[a-zA-Z0-9/ ]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode); 
                if(event.which == 91 || event.which == 93 || event.which == 96)
                {
                  return false;
                }
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            }); 

            $(document).on('keypress','.alphanumericone',function (event){
                var regex = new RegExp("^[a-zA-Z0-9/ ]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode); 
                if(event.which == 91 || event.which == 93 || event.which == 96)
                {
                  return false;
                }
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });

            $(document).on('keyup','.numeric',function (event){
                  if (/\D/g.test(this.value))
                  {
                    // Filter non-digits from input value.
                    this.value = this.value.replace(/\D/g, '');
                  }
            }); 
            $(document).on('keypress','.mobile',function (event){
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
            $(document).on('keypress','.phone',function (event){
                var regex = new RegExp("^[0-9-]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if(event.which == 45 && $(this).val().indexOf('-') != -1) {
                    event.preventDefault();
                    return false;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
            //code by Itishree -- START -- 
            document.addEventListener('contextmenu', function(e) {
              e.preventDefault();
            });
          
            $('[data-toggle="tooltip"]').tooltip();
            var currentDate = new Date();
            var CurrentYear = currentDate.getFullYear();
            var MinYear = CurrentYear-120;
            var MaxYear = CurrentYear-18;
            var current_Month = parseInt(currentDate.getMonth())+1;
            var current_Date = parseInt(currentDate.getDate())-1;
            var MaxDate = current_Date+'-'+current_Month+'-'+MaxYear;
            var MinDate = currentDate.getDate()+'-'+current_Month+'-'+MinYear;
            
            // alert(current_Date);

            //code by Itishree -- END -- 
            $("#from_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                maxDate:'0',
                format: 'dd-mm-yyyy',
                onSelect: function( selectedDate ) {
                    $( "#to_date" ).datepicker( "option", "minDate", selectedDate );
                },
                changeMonth: true,
                changeYear: true,
                autoclose:true
            });
            $("#to_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    $( "#from_date" ).datepicker( "option", "maxDate", selectedDate );
                },
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
                autoclose:true
            }); 
            $("#bail_cancel_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                maxDate:$( "#bail_start_date" ).val(),
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
                autoclose:true
            }); 
            // $(".from_date").datepicker({
            //     defaultDate: new Date(),
            //     changeMonth: true,
            //     numberOfMonths: 1,
            //     maxDate:'0',
            //     onSelect: function( selectedDate ) {
            //         $( ".to_date" ).datepicker( "option", "minDate", selectedDate );
            //     },
            //     dateFormat: 'dd-mm-yy',
            //     changeMonth: true,
            //     changeYear: true
            // });
            $('.from_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                
                                                           
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('.to_date').datepicker('setStartDate', minDate);
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            $('.to_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('.from_date').datepicker('setEndDate', minDate);
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            // $(".to_date").datepicker({
            //     defaultDate: new Date(),
            //     changeMonth: true,
            //     numberOfMonths: 1,
            //     onSelect: function( selectedDate ) {
            //         $( ".from_date" ).datepicker( "option", "maxDate", selectedDate );
            //     },
            //     dateFormat: 'dd-mm-yy',
            //     changeMonth: true,
            //     changeYear: true
            // });  
            $('.mydate').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true
            }).on('changeDate', function (ev) {
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            $('.mydate1').datepicker({
                format: 'mm-dd-yyyy',
                autoclose:true
            }).on('changeDate', function (ev) {
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            $('.enddate').datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',
                changeYear: true       
            });
            $('.dob').datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',
                changeYear: true ,
                yearRange: "-105:-5"
            });
            $('.mydatetimepicker1').datetimepicker({
                showMeridian: false,
                defaultTime:false,
                format: 'dd-mm-yyyy hh:ii',
                autoclose:true
            }).on('changeDate', function (ev) {
                 $(this).datetimepicker('hide');
                 $(this).blur();
            });
            $('.mydatetimepicker2').datetimepicker({
                showMeridian: false,
                defaultTime:true,
                format: 'dd-mm-yyyy hh:ii',
                autoclose:true,
                endDate: new Date(),
                
            }).on('changeDate', function (ev) {
                 $(this).datetimepicker('hide');
                 $(this).blur();
            });
            $('.mydatetimepicker3').datetimepicker({
                showMeridian: false,
                defaultTime:true,
                format: 'mm-dd-yyyy hh:ii',
                autoclose:true,
                startDate: new Date(),
                
            }).on('changeDate', function (ev) {
                 $(this).datetimepicker('hide');
                 $(this).blur();
            });
            $('.timepicker1').datetimepicker({
                startView: 1,
                maxView: 1,
                pickDate: false,
                showMeridian: false,
                defaultTime:false,
                format: 'hh:ii',
                autoclose:true
            }).on('changeDate', function (ev) {
                 $(this).datetimepicker('hide');
                 $(this).blur();
            });
            $('.prisoner_dob').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                startDate: MinDate,
                endDate: MaxDate,
            }).on('changeDate', function (ev) {
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            
             $('.maxCurrentDate').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                endDate: new Date(),
            }).on('changeDate', function (ev) {
                 $(this).datepicker('hide');
                 $(this).blur();
            });

            

            $('.minCurrentDate').datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                startDate: new Date(),
            }).on('changeDate', function (ev) {
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            //  $('.mydatetimepicker1').datetimepicker();
            // $('.mytime').timepicki();
        });
        
</script>
 </head>
<body>
<img src="<?php echo $this->webroot;?>theme/img/uganda_flag2.gif" style="position:fixed; left:50%; top:50%; z-index:9;" id="pmis_loader">
<!--Header-part-->
<div id="header">
  
  <h1 style="background:none;">
      <?php 
      $siteUrl = $this->Html->url(array('controller'=>'sites','action'=>'dashboard'));
      //echo $this->Html->link(SITE_TITLE,array('controller'=>'sites','action'=>'dashboard'));
      ?>
      <a href="<?php echo $siteUrl;?>" title="<?php echo SITE_TITLE;?>">
        <img src="<?php echo $this->webroot;?>ugandalogo.png" class="img" alt="Uganda Prisons Service" style="height: 55px;float: left;">
        <img src="<?php echo $this->webroot;?>theme/img/logo1.png" alt="Uganda Prisons Service" title="Uganda Prisons Service" style="margin-left: 10px;float: left;width: 130px;margin-top: 3px;">
      </a>
  </h1>
</div>
<!--close-Header-part--> 


<!--close-top-serch--> 
<?php echo $this->element('topheader'); ?>
<?php echo $this->element('leftmenu'); ?>
<!--sidebar-menu-->

<div id="content">
  <?php echo $this->element('breadcrumbs'); ?>
  <?php echo $this->fetch('content'); ?>
  <?php echo $this->element('dynamic-modal');?> 
  <?php echo $this->element('confirmation-modal');?> 
    <?php
  if($this->Session->read('message') != '' && $this->Session->read('message_type') == 'success'){
  ?>

    <script type="text/javascript">
      alertify.success("<?php echo $this->Session->read('message'); ?>");
    </script>
  <?php
  $_SESSION['message']=null;
  $_SESSION['message_type']=null;
  }
     ?>
     <?php
  if($this->Session->read('message') != '' && $this->Session->read('message_type') == 'error'){
  ?>

     <script type="text/javascript">
       alertify.error("<?php echo $this->Session->read('message'); ?>");
     </script>
  <?php
  $_SESSION['message']=null;
  $_SESSION['message_type']=null;
  }
      ?>
</div>
<!--Footer-part-->
<div class="row-fluid">
    <!-- <div id="footer" class="span12"> <?php echo date('Y'); ?> &copy; <?php echo SITE_TITLE; ?>. Developed By <a href="http://luminousinfoways.com">Luminous Infoways</a> </div> -->
    <div id="footer" class="span12"> 
      <?php echo date('Y'); ?> &copy; <a href="http://luminousinfoways.com" target="_blank">Luminous Infoways</a> 
      &nbsp;|&nbsp;
      <a href="http://sybyl.com/" target="_blank">Sybyl Limited</a>
      &nbsp;|&nbsp;
      <a href="<?php echo $this->webroot; ?>sites/dashboard" target="_blank"><?php echo SITE_TITLE;?></a>

    </div>
</div>

</body>


</html>
<script>

  $(document).ready(function(){

    var action_class = "<?php echo $funcall->request['controller']; ?>";
    var sublink = "<?php echo $funcall->request['action']; ?>";

    $("li[link='"+action_class+"']").closest(".submenu").addClass( "open active" );
    if($("li[link='"+action_class+"']").length > 1) 
      $("li[link='"+action_class+"'][sublink='"+sublink+"']").addClass( "active" );
    else 
      $("li[link='"+action_class+"']").addClass( "active" );

    //reset select box on reset of form 
    $("select").closest("form").on("reset",function(ev){
      var targetJQForm = $(ev.target);
      setTimeout((function(){
          this.find("select").trigger("change");
      }).bind(targetJQForm),0);
    });
  
  });
  //reset form
function resetForm(formId) { 
    document.getElementById(formId).reset();
    $('#'+formId).closest('form').find("label.error").remove();
    $('#'+formId).closest('form').find(".error").removeClass('error');
    $('#'+formId).closest('form').find(".img_prev").hide();
    $('#'+formId).closest('form').find(".remove_img").hide();
    //$('#'+formId+' .img_prev').html('');
    //$('#'+formId+' .remove_img').hide();
}
//Dynamic confirmation modal
function AsyncConfirmYesNo(msg, yesTitle, noTitle, yesFn, noFn, formId='', confType = '', funcName = '') {
    var $confirm = $("#modalConfirmYesNo");
    $confirm.modal('show');
    $("#lblMsgConfirmYesNo").html(msg);
    $('#btnYesConfirmYesNo').html(yesTitle);
    $('#btnNoConfirmYesNo').html(noTitle);
    //$('#btnYesConfirmYesNo').css('display','block');
    //$('#btnNoConfirmYesNo').css('display','block');
    $('#btnYesConfirmYesNo').show();
    $('#btnNoConfirmYesNo').show();
    $("#btnYesConfirmYesNo").off('click').click(function () {
      if(formId != '')
      {
        if(confType == 'Delete')
        {
          yesFn(formId, funcName);
        }
        else 
        {
          yesFn(formId);
        }
      }
      else 
      {
        yesFn();
      }
      $confirm.modal("hide");
    });
    $("#btnNoConfirmYesNo").off('click').click(function () {
        noFn();
        $confirm.modal("hide");
    });
}
//Edit Dynamic confirmation modal -- START --
function ShowEditConfirm(formId) {
    AsyncConfirmYesNo(
            "Are you sure want to edit?",
            'Edit',
            'Cancel',
            MyYesEdit,
            MyNoEdit,
            formId
        );
}
function MyYesEdit(formId) 
{
  $('#'+formId).submit();
}
function MyNoEdit() {
}
//Edit Dynamic confirmation modal -- END --
//show div by class 
function showDiv(className)
{
  //$('.'+className).removeClass('hide');
  $('.'+className).toggle('slow');
}
function dynamicAlertBox(pageTitle, pageBody)
{
  $("#dynamic-modal .modal-title").html(pageTitle);
  $("#dynamic-modal .modal-body").html(pageBody);
  $("#dynamic-modal").modal("show");
}


var SessionTime = 900000;
var tickDuration = 1000;
//900000

var myInterval = setInterval(function() {
    SessionTime = SessionTime - tickDuration
    //$("label").text(SessionTime);
}, 1000);
var myTimeOut = setTimeout(SessionExpireEvent, SessionTime);

$("body").mouseover(function(){
clearTimeout(myTimeOut);
    SessionTime=900000;
 myTimeOut=setTimeout(SessionExpireEvent,SessionTime);
});
function SessionExpireEvent() {
    clearInterval(myInterval);
    alert(window.location.href);
    // alert("Session expired");
    // window.location.href="<?php //echo $this->Html->url(array('controller'=>'users','action'=>'logout'))?>";
}
</script>
