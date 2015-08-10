
class dashboardUpdateMedia extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "dashboardUpdateMedia.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "dashboardUpdateMedia.setupEvents"
          
          $("#saveEdits").unbind().bind "click", (event)=>
               # $("#saveEditsded").prop("disabled", "disabled")    
               @saveEdits()

          $("#permDelete").unbind().bind "click", (event)=>
               # $("#saveEditsded").prop("disabled", "disabled")    
               @deleteMedia()
   
     saveEdits:()=>
          if @debug then console.log "dashboardUpdateMedia.saveEdits"
          
          
          $.ajax
               url: @base_url+"/post/saveMediaEdit" 
               type: 'post'
               dataType: 'json'
               data:
                    title:  $("#mediaTitle").val()
                    stub: $("#mediaStub").val()
                    visibleWhen: $("#mediaWhen").val()
                    mediaID: $('#mediaID').val() 
                    loggedOnly: $('#mediaLogged').val()
                    vintage: $('#mediaVintage').val()  

                         
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
  

     deleteMedia:()=>
          if @debug then console.log "dashboardUpdateMedia.deleteMedia"
          
          
          $.ajax
               url: @base_url+"/post/deleteSpecificMedia" 
               type: 'post'
               dataType: 'json'
               data:
                    mediaID: $('#mediaID').val() 

                         
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
window.classes.dashboardUpdateMedia = dashboardUpdateMedia
window.objs ?= {}
 
$(document).ready ->
     window.objs.dashboardUpdateMedia = new dashboardUpdateMedia
