
class adminTools extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "adminTools.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "adminTools.setupEvents"
          
          $("#sectionUsage").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
           
          $("#saveNewSection").unbind().bind "click", (event)=>
               $("#saveNewSection").prop("disabled", "disabled")    
               @saveSection()
            
          $("#saveNewContact").unbind().bind "click", (event)=>
               $("#saveNewContact").prop("disabled", "disabled")    
               @addUser()
          
          $("#regen").unbind().bind "click", (event)=>
               $("#regen").prop("disabled", "disabled")    
               @regen()
          
          
          $("#clearSection").unbind().bind "click", (event)=>
               @cleanAreas()

          $('#sectionController').keyup ->
               empty = false
               $('.textReq').each ->
                    if $(this).val() == ''
                         empty = true
               if empty
                    console.log "remain locked"
                    $("#saveNewSection").prop("disabled", "disabled")
               else
                    console.log "open"
                    $("#saveNewSection").prop("disabled", false)
     
          $(".normVis").unbind().bind "click", (event)=>
               $(".visAdj").prop("disabled", "disabled")
               @setNorm($(event.currentTarget).data("id"))
          
          $(".logVis").unbind().bind "click", (event)=>
               $(".visAdj").prop("disabled", "disabled")
               @setLogged($(event.currentTarget).data("id"))
          
          $(".lockVis").unbind().bind "click", (event)=>
               $(".visAdj").prop("disabled", "disabled")
               @setLocked($(event.currentTarget).data("id"))
          
          
     saveSection:()=>
          if @debug then console.log "adminTools.saveSection"
          
          if $.trim($("#sectionUsage").html())
               $.ajax
                    url: @base_url+"/admin/securepost/addSection"
                    type: 'post'
                    dataType: 'json'
                    data:
                         section: $('#secName').val()
                         usage: $('#sectionUsage').html()
                         sub_dir: $('#secDir').val()
                         visibility: $("#linkVis").val()
                         where: $("#linkLoc").val()
                              
                    success: (response)=>
                         if response.success
                              console.log "Success"
                              @cleanAreas()
                              @textBodyResponse("Section added to the database", "#sectionMessage", false, "#sectionArea-alert", "#saveNewSection")
                              $("#saveNewContact").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              $("#saveNewContact").prop("disabled", false)
                         else if response.error
                              console.log "error"
                              @textBodyResponse(response.error,  "#sectionMessage", true, "#sectionArea-alert", "#saveNewSection")  
                              $("#saveNewContact").prop("disabled", false)
          else
               @textBodyResponse("You need fill in all the fields!", "#sectionMessage", true, "#sectionArea-alert", "#saveNewSection")
     
     regen:()=>
          if @debug then console.log "adminTools.regen"
          
          
          $.ajax
               url: @base_url+"/admin/securepost/regenerateSections"
               type: 'post'
               dataType: 'json'
              
               success: (response)=>
                    if response.success
                         console.log "Success"
                         @cleanAreas()
                         @textBodyResponse(response.error,  "#sectionMessage", true, "#sectionArea-alert", "#saveNewSection")
                         $("#regen").prop("disabled", false)
                    else if response.debug
                         console.log "debug"
                         $("#regen").prop("disabled", false)
                    else if response.error
                         console.log "error"
                         @textBodyResponse(response.error,  "#sectionMessage", true, "#sectionArea-alert", "#saveNewSection")  
                         $("#regen").prop("disabled", false)
          
     
     
     addUser:()=>
          if @debug then console.log "adminTools.addUser"
          
          $.ajax
               url: @base_url+"/admin/securepost/addUserToSection"
               type: 'post'
               dataType: 'json'
               data:
                    section: $('#addToSection').val()
                    user: $('#addPerson').val()
                         
               success: (response)=>
                    if response.success
                         console.log "Success"
                         @cleanAreas()
                         @textBodyResponse("User/Section association added to the database", "#roleMessage", false, "#roleArea-alert", "#saveNewContact")
                         $("#saveNewContact").prop("disabled", false)
                    else if response.debug
                         console.log "debug"
                         $("#saveNewContact").prop("disabled", false)
                    else if response.error
                         console.log "error"
                         @textBodyResponse(response.error,  "#roleMessage", true, "#roleArea-alert", "#saveNewContact")  
                         $("#saveNewContact").prop("disabled", false)
                         
     setNorm:(sectId)=>
          if @debug then console.log "adminTools.setNorm"
          
          $.ajax
               url: @base_url+"admin/securepost"
               type: 'post'
               dataType: 'json'
               data:
                    section: sectId
               
               success: (response)=>
                    if response.success
                         @textBodyResponse("User/Section association added to the database", "#roleMessage", false, "#roleArea-alert", "#saveNewContact")
                         $(".visAdj").prop("disabled", false)   
                    else if response.error
                         console.log "error"
                         @textBodyResponse(response.error,  "#roleMessage", true, "#roleArea-alert", "#saveNewContact")  
                         $(".visAdj").prop("disabled", false)
                    
     setLogged:()=>
          if @debug then console.log "adminTools.setLogged"
          
          
     setLocked:()=>
          if @debug then console.log "adminTools.setLocked"
     
     
window.classes ?= {}
window.classes.adminTools = adminTools
window.objs ?= {}

$(document).ready ->
     window.objs.adminTools = new adminTools
