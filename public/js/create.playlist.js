// Create playlist js function activities.
$(document).ready(function() {

    // urls


    // login url
    //app.gopogo.signin_url = app.gopogo.baseurl + 'User/Account/login/';
    //$().debugLog('forgotSubmitBox');

    /*
    //function to add click event
    $(".<class name>").click(function(){

    });

    // function to extand
    $.fn.<function name> = function(){

    };
    */

    // add create playlist event
    //$(".clsCreatePlaylist").click(function(){
    $(".clsCreatePlaylist").click(function(){

        $().debugLog('clsCreatePlaylist');

        // reset new playlist data
        $().playlistNewReset();

        // show create playlist popup.
        $().playlistNewPopup();

        // add search places event on create playlist popup


        // add next event on create playlist popup


        // add events for back create playlist button
        $(".clsCrtPopupBack").click(function(){
            $().debugLog('clsCrtPopupBack');
            $().resetCreatePlaylistButtons();
            //$('.clsCrtPopupBack').removeClass('d1').addClass("d2");
            if(app.gopogo.playlistData.newPlaylist.mode==2)
                {
                    app.gopogo.playlistData.newPlaylist.mode=1;
                    //$('.clsCrtPopupBack').removeClass('d1').addClass("d2");
                    $('.clsCrtPopupNext').removeClass('d1').addClass("d2");
                    $().createPlaylistTabsOnOff(1);
                }
            else if(app.gopogo.playlistData.newPlaylist.mode==3)
                {
                    app.gopogo.playlistData.newPlaylist.mode=2;
                    $('.clsCrtPopupBack').removeClass('d1').addClass("d2");
                    $('.clsCrtPopupNext').removeClass('d1').addClass("d2");
                    $().createPlaylistTabsOnOff(2);
                }
            
        });
        // add events for next create playlist button
        $(".clsCrtPopupNext").click(function(){
            $().debugLog('clsCrtPopupNext');
            $().resetCreatePlaylistButtons();


            //app.gopogo.playlistData.newPlaylist.mode = 1;

            if(app.gopogo.playlistData.newPlaylist.mode==1)
                {
                    app.gopogo.playlistData.newPlaylist.mode=2;
                    $('.clsCrtPopupBack').removeClass('d1').addClass("d2");
                    $('.clsCrtPopupNext').removeClass('d1').addClass("d2");
                    $().createPlaylistTabsOnOff(2);
                }
            else if(app.gopogo.playlistData.newPlaylist.mode==2)
                {
                    app.gopogo.playlistData.newPlaylist.mode=3;
                    $('.clsCrtPopupBack').removeClass('d1').addClass("d2");
                    $('.clsCrtPopupCreatePlaylist').removeClass('d1').addClass("d2");
                    $().createPlaylistTabsOnOff(3);
                }

            
        });
        // add events for create playlist button
        $(".clsCrtPopupCreatePlaylist").click(function(){
            $().debugLog('clsCrtPopupCreatePlaylist');
            $().resetCreatePlaylistButtons();
            $('.clsCrtPopupBack').removeClass('d1').addClass("d2");
            $('.clsCrtPopupCreatePlaylist').removeClass('d1').addClass("d2");
            //app.gopogo.playlistData.newPlaylist.mode = 4;
        });

    });

    // hide create playlist buttons
    $.fn.resetCreatePlaylistButtons = function(){
        $().debugLog('resetCreatePlaylistButtons');
        $('.clsCrtPopupBack').removeClass('d2').addClass("d1");
        $('.clsCrtPopupNext').removeClass('d2').addClass("d1");
        $('.clsCrtPopupCreatePlaylist').removeClass('d2').addClass("d1");
    };

    // Create Playlis tTabs On Off
    $.fn.createPlaylistTabsOnOff = function(ontab){
        $().debugLog('createPlaylistTabsOnOff');
        $('.clsCreateTab').parent().removeClass('tab-on').addClass("tab-off");
        $('.tabs-contents').removeClass('d2').addClass("d1");
        // create-tabs-contents-
        if(ontab==1){
            $('.clsCreateTab.stop').parent().removeClass('tab-off').addClass("tab-on");
            $('.content-stops').removeClass('d1').addClass("d2");
        }
        else if(ontab==2){
            $('.clsCreateTab.detail').parent().removeClass('tab-off').addClass("tab-on");
            $('.content-details').removeClass('d1').addClass("d2");
        }
        else if(ontab==3){
            $('.clsCreateTab.tag').parent().removeClass('tab-off').addClass("tab-on");
            $('.content-tags').removeClass('d1').addClass("d2");
        }
            
    };


    // function to handle reset new playlist data
    $.fn.playlistNewReset = function(){
        $().debugLog('playlistNewReset');
        // reset new playlist data
        app.gopogo.playlistData = {};
        app.gopogo.playlistData.newPlaylist = {};

        app.gopogo.playlistData.newPlaylist.mode = 1;

        app.gopogo.playlistData.newPlaylist.count = 0;
        app.gopogo.playlistData.newPlaylist.data = [];

        app.gopogo.playlistData.newPlaylist.title = '';
        app.gopogo.playlistData.newPlaylist.description = '';
        app.gopogo.playlistData.newPlaylist.status = 0;
        app.gopogo.playlistData.newPlaylist.image = {};
        app.gopogo.playlistData.newPlaylist.image.name = '';
        app.gopogo.playlistData.newPlaylist.image.url = '';
        app.gopogo.playlistData.newPlaylist.image.size = '';
        app.gopogo.playlistData.newPlaylist.image.croppedinfo = [];

        app.gopogo.playlistData.newPlaylist.comment = '';


        app.gopogo.playlistData.newPlaylist.mood = '';
        app.gopogo.playlistData.newPlaylist.crowdType = '';
        app.gopogo.playlistData.newPlaylist.dressCode = '';
        app.gopogo.playlistData.newPlaylist.ageRange = '';
        app.gopogo.playlistData.newPlaylist.goodFor = '';
        app.gopogo.playlistData.newPlaylist.bestWhen = '';
        app.gopogo.playlistData.newPlaylist.bestTimeToGo = '';
        app.gopogo.playlistData.newPlaylist.transportation = '';
        app.gopogo.playlistData.newPlaylist.timeSpent = '';
        app.gopogo.playlistData.newPlaylist.pricePerPerson = '';

    }; // end of playlistNewReset

    // functions to handle create playlist popup
    $.fn.playlistNewPopup = function(){

        $().debugLog('playlistNewPopup');

        // render create new playlist popup html
        //$().debugLog($('#createNewPlaylist').length);
        // load create_playlist_popup partial if not exists
        //if($('#createNewPlaylist').length==0)
            //$().loadAjaxHtml(['create_playlist_popup']);

        $().displayModalBox("#createNewPlaylistBox");

        // pg-popup-head-close
        // attach close button event
        $(".clsPopupClose").click(function(){

            $().debugLog('clsPopupClose');

            $().finish();
            
        });



        $(".crtplAddStop").click(function(){

            $().debugLog('crtplAddStop');

            $().displaySearchbox();

        });

    }; // end of playlistNewPopup

    // display search box
    $.fn.displaySearchbox = function(){
        $().debugLog('displaySearchbox');
        // hide playlist box
        $('.create-tabs-contents').removeClass('d2').addClass("d1");
        // hide progerss process
        $('.create-tabs').removeClass('d2').addClass("d1");

        // show search stop box

        $('.playlist-search-box').removeClass('d1').addClass("d2");

        // attach back event from search box
        $(".clsCrtPopupBackSearch").click(function(){
            $().debugLog('clsCrtPopupBackSearch');
            $().hideSearchbox();
        });

        // attach seach event

        // attach back event from search box
        $(".clsCrtPopupSearch").click(function(){
            $().debugLog('clsCrtPopupSearch');
            $().searchStops(1);
            $('#crtpl-search-content').ajaxLoader();
            $('#crtpl-search-content').html('Searching...');
        });

    };
    // hide search box
    $.fn.hideSearchbox = function(){
        $().debugLog('hideSearchbox');
        // show playlist box
        $('.create-tabs-contents').removeClass('d1').addClass("d2");
        // show progerss process
        $('.create-tabs').removeClass('d1').addClass("d2");

        // hide search stop box
        $('.playlist-search-box').removeClass('d2').addClass("d1");

    };

    // $('.save-username').ajaxLoader();
    // $('.playlist-search-content').ajaxLoader();
    // remove loader

    $.fn.crtPlRemoveLoader = function ( ){        
        $('#crtpl-search-content').ajaxLoaderRemove();
        
    };

    // search result
    $.fn.searchStops = function(indexSearch){
        $().debugLog('searchStops');

        var search = {};

        var url = app.gopogo.yahoo_search_url1;

        switch(indexSearch){
            case 1:
                url = app.gopogo.yahoo_search_url1;
                break;
            case 2:
                url = app.gopogo.yahoo_search_url2;
                break;
            case 3:
                url = app.gopogo.yahoo_search_url3;
                break;
            default:
                url = app.gopogo.yahoo_search_url1;
        }

        var place = $('.inpclsPlaylistSearchPlace').val();

        var sdata = {'place':place};

        search = {'url':url,'sdata':sdata};

        $().searchSendStops(search);
    };

    // search result
    $.fn.searchSendStops = function(search){
        $.ajax({
          url: search.url,
          type: 'get',
          dataType: 'json',
          data:search.sdata,
          timeout: 99999,
          error: function(resp){
                
                $().debugLog('searchSendStops-error');
                $().debugLog(resp);
                if(resp.readyState == 4) {

                }
          },
          success: function(resp){

              $().debugLog('searchSendStops-success');
              $().debugLog(resp);
              // do something with resp
              if(resp.status == 1) {
                  $().crtPlRemoveLoader();                  
                  $().displaySearchedPlaces(resp.data);
              }
              else {
                                 
              }
           },
           complete: function(resp) {
               $().debugLog('searchSendStops-complete');
               $().debugLog(resp);
               if(resp.readyState == 4) {

               }
           }
        });
    };

    // display searched places
    $.fn.displaySearchedPlaces = function(result){
        $().debugLog('displaySearchedPlaces');
        $('#crtpl-search-content').empty();
        
    };

    // init new playlist data
    $().playlistNewReset();





});
