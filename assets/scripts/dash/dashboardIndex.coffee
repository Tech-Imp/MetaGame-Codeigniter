
class dashboardIndex
     debug: on
     
     constructor:->
          if @debug then console.log "dashboardIndex.constructor"
          @setupCommon()
          @base_url=window.location.origin     
  
  
     setupCommon:=>
          if @debug then console.log "dashboardIndex.setupCommon"
          $(".when").datepicker(
               dateFormat: "yy-mm-dd"
          )
          $(".when").datepicker("setDate", new Date())
          
          
     
     
     cleanAreas:()=>
          if @debug then console.log "dashboardIndex.cleanAreas"
          $('.cleanMe').html("")
          $('.cleanMe').val("")
          $(".textReq").val("")
          $(".when").datepicker("setDate", new Date()) 
     
                    
     
     
     textBodyResponse:(message, messageID, error=false, alertID, uploadID=null)=>
          console.log  message
          $(messageID).html(message)
          if error
               $(alertID).removeClass("alert-success").addClass("alert-danger")
          else
               $(alertID).removeClass("alert-danger").addClass("alert-success")
          $(alertID).removeClass("noshow")
          $(alertID).fadeTo(2000, 500).slideUp 500, ->
               $(alertID).addClass("noshow")
               if uploadID!=null  
                    $(uploadID).prop("disabled", false)
                          
     
  
     
window.classes ?= {}
window.classes.dashboardIndex = dashboardIndex
window.objs ?= {}

$(document).ready ->
     window.objs.dashboardIndex = new dashboardIndex
