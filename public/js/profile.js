// declair global variables for profile
var profile = new Array(2);
$(document).ready(function() {
    loadMsgList();    
    
    $(".clsEUPro").click(function() {
       if($(".clsEUPro").hasClass('save') ) {
           if (profile[0] != $(".inpclsDivUN").val() || profile[1] != $(".inpclsDivUD").val()) {
                $().updateProfile("myinfo");
           } else {
               // suggest to change value
               var inpArr = new Array(2);
               inpArr[0] = new Array("inpclsDivUN","clsDivUN");
               inpArr[1] = new Array("inpclsDivUD","clsDivUD");
               $().inplaceEditor(inpArr,"save");
               $(".clsEUPro").removeClass('save');
               $("b", ".clsEUPro").fadeOut(function(){$(this).text("edit").fadeIn()});
           }
       } else {
           // assign value to global variable of profile
           profile[0] = $(".clsDivUN").text();
           profile[1] = $(".clsDivUD").text();
           var replacement = $("b", this).text() == "edit" ? "save" : "edit";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           inpArr = new Array(2);
           inpArr[0] = new Array("clsDivUN","clsDivUN","text","20");
           inpArr[1] = new Array("clsDivUD","clsDivUD","textarea");
           inpArr[2] = new Array("clsEUPro","clsProAction","href");
           $().inplaceEditor(inpArr,"edit");
       }
    });

    $.fn.inplaceEditor = function(inpArr, act) {
        switch(act) {
            case "edit" :
                var arrLen = inpArr.length;
                //alert(arrLength);
                for(i=0;i<arrLen-1;i++) {
                    var txtEditData;
                    if (inpArr[i][2] == "text") { 
                        txtEditData = $("<input type='"+inpArr[i][2]+"' name='' id='' maxlength='"+inpArr[i][3]+"' class='inp"+inpArr[i][0]+"' value='"+$("."+inpArr[i][0]).text()+"'/>");
                    }
                    if (inpArr[i][2] == 'textarea') {
                        txtEditData = $("<textarea name='' id='' style='width:210px' class='inp"+inpArr[i][0]+"'>"+$("."+inpArr[i][0]).text()+"</textarea>");
                    }
                    $("."+inpArr[i][1]).html(txtEditData);
                }
                switch(inpArr[arrLen-1][2]) {
                    case "button":
                        $("."+inpArr[arrLen-1][1]).html("<input type='button' class='inplace"+inpArr[arrLen-1][0]+"save' name='' value='Save'/>");
                    case "href":
                        $("."+inpArr[arrLen-1][0]).addClass("save");
                }
                break;
            case "save" :
                arrLen = inpArr.length;
                for(i=0;i<arrLen;i++) { 
                    $("."+inpArr[i][1]).html($("."+inpArr[i][0]).val());
                }
                break;
        }
    }
    //call ajax for update profile
    $.fn.updateProfile = function (area) {  
        switch(area) {
        case 'myinfo':
        var fdata = {'userName':$(".inpclsDivUN").val(),'userDesc':$(".inpclsDivUD").val()};
        $.ajax({
              url: app.gopogo.updatemyinfo_url,
              type: 'POST',
              data: fdata,
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              success: function(data) {
                      $('.clsProfileLoader').ajaxLoader();
                      //code to manage html after save process complete
                      var inpArr = new Array(2);
                      inpArr[0] = new Array("inpclsDivUN","clsDivUN");
                      inpArr[1] = new Array("inpclsDivUD","clsDivUD");
                      $().inplaceEditor(inpArr,"save");
                  if(data == 0) { 
                      $(".clsDivUN").text(profile[0]);
                      $(".clsDivUD").text(profile[1]);
                  }
                  $(".clsEUPro").removeClass('save');
                  $("b", ".clsEUPro").fadeOut(function(){$(this).text("edit").fadeIn()});
                  // do something with resp                  
               },
              complete: function() {
                    $('.clsProfileLoader').ajaxLoaderRemove();
              }
         });
        }
    }    
});

 function backMsgClick() {
     $(".clsMsgRply").hide();
     loadMsgList();
     $(".clsMsgList").show();
 }
    
//call ajax to display message detail
    function showMsgDtl() {
        $.ajax({
              url: app.gopogo.messagedtl_url,
              type: 'POST',
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              success: function(data) {
                    $(".clsMsgDiv").slideUp();
                    $(".dtlMsgDiv").html(data);
                    $(".clsBackMsg").show();
                    $(".clsDeleteMsg").show();
                    $(".dtlMsgDiv").show();
                  // do something with resp
               },
               complete: function() {

                }
         });
    }

//call ajax to reply for an message
    function doReplyMsg() {
        $.ajax({
              url: app.gopogo.replymessage_url,
              type: 'POST',
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              onload: function() {
                  
              },
              success: function(data) {
                    $('.gray_date_subject_bg').ajaxLoader();
                    $(".clsMsgList").hide();
                    $(".clsMsgRply").html(data);
                    $(".clsMsgRply").show();
                  // do something with resp
               },
               complete: function() {
                    $('.gray_date_subject_bg').ajaxLoaderRemove();
                }
         });
    }

    function showloader() {
                $().finish();
                $('.con-right-ro1').ajaxLoader();
            }

    function loadMsgList() {
        //call ajax to generate message list on load
        $.ajax({
              url: app.gopogo.msglist_url,
              type: 'POST',
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              success: function(data) {
                    $(".clsMsgList").html(data);
               },
               complete: function() {

                }
         });
    }