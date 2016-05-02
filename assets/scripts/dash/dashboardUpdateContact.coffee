
class dashboardUpdateContact extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "dashboardUpdateContact.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "dashboardUpdateContact.setupEvents"
          
          $("#mceContact").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
          
          $("#saveEdits").unbind().bind "click", (event)=>
               # $("#saveEditsded").prop("disabled", "disabled")    
               @saveEdits()

          $("#permDelete").unbind().bind "click", (event)=>
               # $("#saveEditsded").prop("disabled", "disabled")    
               @deleteMedia()
   
     saveEdits:()=>
          if @debug then console.log "dashboardUpdateContact.saveEdits"
          
          if $.trim($("#mceContact").html())
               $.ajax
                    url: @base_url+"/admin/post/postprofile/editProfile" 
                    type: 'post'
                    dataType: 'json'
                    data:
                         profileID: $('#profileID').val() 
                         title: $('#contactTitle').val()
                         bodyText: $('#mceContact').html()
                         avatarID: $('#avatarUsed').val()
                         profileName: $('#contactName').val()
                         section: $('#section').val()
                         exFlag: $('#exclusiveFlag').val()
                         
                    success: (response)=>
                         if response.success
                              # console.log "Success"
                              # @cleanAreas()
                              @textBodyResponse("Edit saved to database", "#userMessage", false, "#textArea-alert")
                              # $("#saveEditsded").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              # $("#saveEditsded").prop("disabled", false)
                         else if response.error
                              # console.log "error"
                              @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert") 
                              # $("#saveEditsded").prop("disabled", false)
          else
               @textBodyResponse("You need to add something to the body of the article!", "#userMessage", true, "#textArea-alert")       

     deleteMedia:()=>
          if @debug then console.log "dashboardUpdateContact.deleteMedia"
          
          
          $.ajax
               url: @base_url+"/admin/post/postprofile/deleteProfile" 
               type: 'post'
               dataType: 'json'
               data:
                    profileID: $('#profileID').val() 

                         
               success: (response)=>
                    if response.success
                         console.log "Success"
                         location.reload()
                    else if response.debug
                         console.log "debug"
                         # $("#saveEditsded").prop("disabled", false)
                    else if response.error
                         # console.log "error"
                         @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert") 
  
  
  
     
window.classes ?= {}
window.classes.dashboardUpdateContact = dashboardUpdateContact
window.objs ?= {}
 
$(document).ready ->
     window.objs.dashboardUpdateContact = new dashboardUpdateContact
