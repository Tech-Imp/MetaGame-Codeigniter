
class dashboardMedia extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "dashboardMedia.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "dashboardMedia.setupEvents"
          
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
                    title:  $("#uploadTitle").val()
                    stub: $("#uploadStub").val()
                    visibleWhen: $("#uploadWhen").val()
                    loggedOnly: $('#uploadLogged').val()
                    mediaType: "picture"
                    exFlag:  $("#exclusiveFlagPic").val()
                    section:  $("#sectionPic").val()
                    bodyText: $('#mceMediaBlurb').html()
          
          $("#mceEmbedBlurb").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
          $("#mceMediaBlurb").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
                    
          $("#saveEmbedded").unbind().bind "click", (event)=>
               $("#saveEmbedded").prop("disabled", "disabled")    
               @saveEmbed()

          $("#clearEmbed").unbind().bind "click", (event)=>
               @cleanAreas()


          $('#embedAccord').keyup ->
               empty = false
               $('#embedAccord .textReq').each ->
                    if $(this).val() == ''
                         empty = true
               if empty
                    console.log "remain locked"
                    $("#saveEmbedded").prop("disabled", "disabled")  
               else
                    console.log "open"
                    $("#saveEmbedded").prop("disabled", false)

   
     saveEmbed:()=>
          if @debug then console.log "dashboardMedia.saveEmbed"
          
          if $.trim($("#mceEmbedArea").val())
               $.ajax
                    url: @base_url+"/admin/post/postmedia/addEmbedMedia"
                    type: 'post'
                    dataType: 'json'
                    data:
                         title:  $("#embedTitle").val()
                         stub: $("#embedStub").val()
                         visibleWhen: $("#embedWhen").val()
                         embed: $('#mceEmbedArea').val()
                         mediaType: $("#mediaOptions").val()
                         exFlag:  $("#exclusiveFlagEmbed").val()
                         section:  $("#sectionEmbed").val()
                         bodyText: $('#mceEmbedBlurb').html()
                              
                    success: (response)=>
                         if response.success
                              console.log "Success"
                              @cleanAreas()
                              @textBodyResponse("Item added to media database", "#userEmbedMessage", false, "#textArea-alert", "#saveEmbedded")
                              $('#mceEmbedArea').val("")
                              $('#mceEmbedBlurb').val("")
                              $("#saveEmbedded").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              $("#saveEmbedded").prop("disabled", false)
                         else if response.error
                              console.log "error"
                              @textBodyResponse(response.error, true) 
                              $("#saveEmbedded").prop("disabled", false)
          else
               @textBodyResponse("You need to add embed from Youtube", "#userEmbedMessage", true, "#textArea-alert", "#saveEmbedded")
                    
     
   
  
     
window.classes ?= {}
window.classes.dashboardMedia = dashboardMedia
window.objs ?= {}
 
$(document).ready ->
     window.objs.dashboardMedia = new dashboardMedia
