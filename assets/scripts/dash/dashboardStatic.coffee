
class dashboardStatic extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "dashboardStatic.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "dashboardStatic.setupEvents"
          
          @saveAvatar()
          
          $("#mceContact").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
           
          $("#saveNewContact").unbind().bind "click", (event)=>
               $("#saveNewContact").prop("disabled", "disabled")    
               @saveProfile()
          
          $("#saveNewSocial").unbind().bind "click", (event)=>
               $("#saveNewSocial").prop("disabled", "disabled")    
               @saveSocial()

          $("#clearArticle").unbind().bind "click", (event)=>
               @cleanAreas()


     saveAvatar:()=>
       $('.nUpload').plupload
               runtimes: 'html5,flash,silverlight,html4'
               url: @base_url+'/admin/post/postmedia/CIUpload'
               max_file_size: '4mb'
               chunk_size: '1mb'
               filters: [
                    {
                         title: 'Image files'
                         extensions: 'jpg,gif,png'
                    }
                    {
                         title: 'Zip files'
                         extensions: 'zip,avi'
                    }
               ]
               rename: true
               sortable: true
               dragdrop: true
               views:
                    list: true
                    thumbs: true
                    active: 'thumbs'
               flash_swf_url: '/plupload/js/Moxie.swf'
               silverlight_xap_url: '/plupload/js/Moxie.xap'
          
          myUploader=$('.nUpload').plupload('getUploader')
               
          myUploader.bind 'BeforeUpload', (up, file)=> 
               up.settings.multipart_params= 
                    title: $("#avatarNotes").val()
                    stub: 'Avatar/Logo upload'
                    loggedOnly: 0
                    mediaType: $("#mediaOptions").val()
                    exFlag:  $("#exclusiveFlagAvatar").val()
                    section:  $("#sectionAvatar").val()
   
     saveProfile:()=>
          if @debug then console.log "dashboardStatic.saveProfile"
          
          if $.trim($("#mceContact").html())
               $.ajax
                    url: @base_url+"/admin/post/postprofile/addProfile"
                    type: 'post'
                    dataType: 'json'
                    data:
                         title: $('#contactTitle').val()
                         bodyText: $('#mceContact').html()
                         avatarID: $('#avatarUsed').val()
                         profileName: $('#contactName').val()
                         section: $('#sectionProfile').val()
                         exFlag: $('#exclusiveFlagProfile').val()
                              
                    success: (response)=>
                         if response.success
                              console.log "Success"
                              @cleanAreas()
                              @textBodyResponse("Profile Info added to the database", "#userMessage", false, "#textArea-alert", "#saveNewContact")
                              $("#saveNewContact").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              $("#saveNewContact").prop("disabled", false)
                         else if response.error
                              console.log "error"
                              @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert", "#saveNewContact")  
                              $("#saveNewContact").prop("disabled", false)
          else
               @textBodyResponse("You need to add Contact Info!", "#userMessage", true, "#textArea-alert", "#saveNewContact")
     
     
     saveSocial:()=>
          if @debug then console.log "dashboardStatic.saveProfile"
          
          $.ajax
               url: @base_url+"/admin/post/postprofile/addSocial"
               type: 'post'
               dataType: 'json'
               data:
                    target: $('#socialTarget').val()
                    logoID: $('#logoUsed').val()
                    facebook: $('#facebookSocial').val()
                    youtube: $('#youtubeSocial').val()
                    twitter: $('#twitterSocial').val()
                    tumblr: $('#tumblrSocial').val()
                    email: $('#emailSocial').val()
                    twitch: $('#twitchSocial').val()
                    bodyText: $('#mceSocial').html()
                    section: $('#sectionSocial').val()
                    exFlag: $('#exclusiveFlagSocial').val()
               success: (response)=>
                    if response.success
                         console.log "Success"
                         @cleanAreas()
                         @textBodyResponse("Social Info added to the database", "#socialMessage", false, "#socialArea-alert", "#saveNewContact")
                         $("#saveNewSocial").prop("disabled", false)
                    else if response.debug
                         console.log "debug"
                         $("#saveNewSocial").prop("disabled", false)
                    else if response.error
                         console.log "error"
                         @textBodyResponse(response.error,  "#socialMessage", true, "#socialArea-alert", "#saveNewContact")  
                         $("#saveNewSocial").prop("disabled", false)
     
                          
     
window.classes ?= {}
window.classes.dashboardStatic = dashboardStatic
window.objs ?= {}

$(document).ready ->
     window.objs.dashboardStatic = new dashboardStatic
