
class adminSectionEdit extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "adminSectionEdit.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "adminSectionEdit.setupEvents"
          
          $("#sectionUsage").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
           
          $("#saveEdits").unbind().bind "click", (event)=>
               $("#saveEdits").prop("disabled", "disabled")    
               @saveSection()
            

          $('#sectionController').keyup ->
               empty = false
               $('.textReq').each ->
                    if $(this).val() == ''
                         empty = true
               if empty
                    console.log "remain locked"
                    $("#saveEdits").prop("disabled", "disabled")
               else
                    console.log "open"
                    $("#saveEdits").prop("disabled", false)
     
   
     saveSection:()=>
          if @debug then console.log "adminSectionEdit.saveSection"
          
          if $.trim($("#sectionUsage").html())
               $.ajax
                    url: @base_url+"/admin/securepost/editSection"
                    type: 'post'
                    dataType: 'json'
                    data:
                         id: $("#assocID").val()
                         usage: $('#sectionUsage').html()
                         visibility: $("#linkVis").val()
                         where: $("#linkLoc").val()
                              
                    success: (response)=>
                         if response.success
                              console.log "Success"
                              @textBodyResponse("Section updated", "#sectionMessage", false, "#sectionArea-alert", "#saveEdits")
                              $("#saveEdits").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              $("#saveEdits").prop("disabled", false)
                         else if response.error
                              console.log "error"
                              @textBodyResponse(response.error,  "#sectionMessage", true, "#sectionArea-alert", "#saveEdits")  
                              $("#saveEdits").prop("disabled", false)
          else
               @textBodyResponse("You need fill in Usage field!", "#sectionMessage", true, "#sectionArea-alert", "#saveEdits")
     
     

     
window.classes ?= {}
window.classes.adminSectionEdit = adminSectionEdit
window.objs ?= {}

$(document).ready ->
     window.objs.adminSectionEdit = new adminSectionEdit
