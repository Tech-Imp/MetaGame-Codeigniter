
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
               @saveContact()

          $("#clearArticle").unbind().bind "click", (event)=>
               @cleanAreas()


     saveAvatar:()=>
       $('.nUpload').plupload
               runtimes: 'html5,flash,silverlight,html4'
               url: @base_url+'/post/CIUpload'
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
                    stub: $("#avatarNotes").val()
                    mediaType: "profilePic"
                    exFlag:  $("#exclusiveFlagAvatar").val()
                    section:  $("#sectionAvatar").val()
   
     saveContact:()=>
          if @debug then console.log "dashboardStatic.saveContact"
          
          if $.trim($("#mceContact").html())
               $.ajax
                    url: @base_url+"/post/addContact"
                    type: 'post'
                    dataType: 'json'
                    data:
                         title: $('#contactTitle').html()
                         bodyText: $('#mceContact').html()
                              
                    success: (response)=>
                         if response.success
                              console.log "Success"
                              @cleanAreas()
                              @textBodyResponse("Contact Info added to the database", "#userMessage", false, "#textArea-alert", "#saveNewContact")
                              $("#saveNewContact").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              $("#saveNewContact").prop("disabled", false)
                         else if response.error
                              console.log "error"
                              @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert", "#saveNewContact")  
                              $("#saveNewArticle").prop("disabled", false)
          else
               @textBodyResponse("You need to add Contact Info!", "#userMessage", true, "#textArea-alert", "#saveNewArticle")
     
                          
     
window.classes ?= {}
window.classes.dashboardStatic = dashboardStatic
window.objs ?= {}

$(document).ready ->
     window.objs.dashboardStatic = new dashboardStatic
