
class dashboardUpdateSocial extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "dashboardUpdateSocial.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "dashboardUpdateSocial.setupEvents"
          
          # $("#mceContact").tinymce
               # script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               # theme : "modern",
               # plugins: [
                    # "advlist autolink lists link image charmap print preview anchor",
                    # "searchreplace visualblocks code fullscreen",
                    # "insertdatetime media table contextmenu paste" 
                    # ],
               # toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
          
          $("#saveEdits").unbind().bind "click", (event)=>
               # $("#saveEditsded").prop("disabled", "disabled")    
               @saveEdits()

          $("#permDelete").unbind().bind "click", (event)=>
               # $("#saveEditsded").prop("disabled", "disabled")    
               @deleteMedia()
   
     saveEdits:()=>
          if @debug then console.log "dashboardUpdateSocial.saveEdits"
          
          $.ajax
               url: @base_url+"/admin/post/postprofile/editSocial" 
               type: 'post'
               dataType: 'json'
               data:
                    infoID: $('#profileID').val() 
                    logoID: $('#logoUsed').val()
                    facebook: $('#facebookSocial').val() 
                    youtube: $('#youtubeSocial').val()
                    twitter: $('#twitterSocial').val()
                    tumblr: $('#tumblrSocial').val()
                    email: $('#emailSocial').val()
                    twitch: $('#twitchSocial').val()
                    bodyText: $('#mceSocial').html()
                    section: $('#section').val()
                    exFlag: $('#exclusiveFlag').val()
                    
               success: (response)=>
                    if response.success
                         @textBodyResponse("Edit saved to database", "#userMessage", false, "#textArea-alert")
                         # $("#saveEditsded").prop("disabled", false)
                    else if response.debug
                         console.log "debug"
                         # $("#saveEditsded").prop("disabled", false)
                    else if response.error
                         # console.log "error"
                         @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert") 
                         # $("#saveEditsded").prop("disabled", false)

     deleteMedia:()=>
          if @debug then console.log "dashboardUpdateSocial.deleteMedia"
          
          
          $.ajax
               url: @base_url+"/admin/post/postprofile/deleteSocial" 
               type: 'post'
               dataType: 'json'
               data:
                    infoID: $('#profileID').val() 

                         
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
window.classes.dashboardUpdateSocial = dashboardUpdateSocial
window.objs ?= {}
 
$(document).ready ->
     window.objs.dashboardUpdateSocial = new dashboardUpdateSocial
