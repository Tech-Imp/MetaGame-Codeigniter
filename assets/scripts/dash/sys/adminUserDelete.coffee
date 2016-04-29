
class adminUserDelete extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "adminUserDelete.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "adminUserDelete.setupEvents"
           
            
          $("#permDelete").unbind().bind "click", (event)=>
               $("#permDelete").prop("disabled", "disabled")    
               @removeUser()
          
     
     removeUser:()=>
          if @debug then console.log "adminUserDelete.removeUser"
          
          $.ajax
               url: @base_url+"/admin/securepost/deleteUserFromSection"
               type: 'post'
               dataType: 'json'
               data:
                    entryID: $('#assocID').val()
                         
               success: (response)=>
                    if response.success
                         console.log "Success"
                         location.reload()
                    else if response.debug
                         console.log "debug"
                    else if response.error
                         @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert")

     
window.classes ?= {}
window.classes.adminUserDelete = adminUserDelete
window.objs ?= {}

$(document).ready ->
     window.objs.adminUserDelete = new adminUserDelete
