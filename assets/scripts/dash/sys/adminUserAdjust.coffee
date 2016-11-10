
class adminUserAdjust extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "adminUserAdjust.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "adminUserAdjust.setupEvents"
           
            
          $("#setRoleNorm").unbind().bind "click", (event)=>
               $("#setSectAdmin").prop("disabled", "disabled")    
               $("#setRoleContrib").prop("disabled", "disabled") 
               $("#setRoleNorm").prop("disabled", "disabled") 
               @updateUser("norm")
          
          $("#setRoleContrib").unbind().bind "click", (event)=>
               $("#setSectAdmin").prop("disabled", "disabled")    
               $("#setRoleContrib").prop("disabled", "disabled") 
               $("#setRoleNorm").prop("disabled", "disabled")   
               @updateUser("contrib")
               
          $("#setSectAdmin").unbind().bind "click", (event)=>
               $("#setSectAdmin").prop("disabled", "disabled")    
               $("#setRoleContrib").prop("disabled", "disabled") 
               $("#setRoleNorm").prop("disabled", "disabled") 
               @updateUser("sect")
     
     updateUser:(myRank=null)=>
          if @debug then console.log "adminUserAdjust.removeUser"
          
          $.ajax
               url: @base_url+"/admin/securepost/setRole"
               type: 'post'
               dataType: 'json'
               data:
                    userID: $('#assocID').val()
                    rank: myRank
                         
               success: (response)=>
                    if response.success
                         console.log "Success"
                         location.reload()
                    else if response.debug
                         console.log "debug"
                    else if response.error
                         @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert")

     
window.classes ?= {}
window.classes.adminUserAdjust = adminUserAdjust
window.objs ?= {}

$(document).ready ->
     window.objs.adminUserAdjust = new adminUserAdjust
