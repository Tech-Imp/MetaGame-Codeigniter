
class adminSectionDelete extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "adminSectionDelete.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "adminSectionDelete.setupEvents"
           
          $("#permDelete").unbind().bind "click", (event)=>
               $("#permDelete").prop("disabled", "disabled")    
               @removeSection()

   
     removeSection:()=>
          if @debug then console.log "adminSectionDelete.removeSection"
          
          $.ajax
               url: @base_url+"/admin/securepost/deleteSection"
               type: 'post'
               dataType: 'json'
               data:
                    sectionID: $('#assocID').val()
                         
               success: (response)=>
                    if response.success
                         console.log "Success"
                         location.reload()
                    else if response.debug
                         console.log "debug"
                    else if response.error
                         @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert") 

     
window.classes ?= {}
window.classes.adminSectionDelete = adminSectionDelete
window.objs ?= {}

$(document).ready ->
     window.objs.adminSectionDelete = new adminSectionDelete
