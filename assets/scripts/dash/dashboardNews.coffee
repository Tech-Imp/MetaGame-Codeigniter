
class dashboardNews extends window.classes.dashboardIndex
     debug: on
     constructor:->
          if @debug then console.log "dashboardIndex.constructor"
          super()
          @setupEvents()
 
  
  
     setupEvents:=>
          if @debug then console.log "dashboardIndex.setupEvents"
          
          
          $("#mceNewsArea").tinymce
               script_url : @base_url+'/assets/scripts/tinymce/tinymce.min.js',
               theme : "modern",
               plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste" 
                    ],
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          
           
          $("#saveNewArticle").unbind().bind "click", (event)=>
               $("#saveNewArticle").prop("disabled", "disabled")    
               @saveNews()

          $("#clearArticle").unbind().bind "click", (event)=>
               @cleanAreas()


          $('body').keyup ->
               empty = false
               $('.textReq').each ->
                    if $(this).val() == ''
                         empty = true
               if empty
                    console.log "remain locked"
                    $("#saveNewArticle").prop("disabled", "disabled")  
               else
                    console.log "open"
                    $("#saveNewArticle").prop("disabled", false)  

   
     saveNews:()=>
          if @debug then console.log "dashboardIndex.saveNews"
          
          if $.trim($("#mceNewsArea").html())
               $.ajax
                    url: @base_url+"/post/addNews"
                    type: 'post'
                    dataType: 'json'
                    data:
                         title:  $("#articleTitle").val()
                         stub: $("#articleStub").val()
                         visibleWhen: $("#articleWhen").val()
                         bodyText: $('#mceNewsArea').html()
                              
                    success: (response)=>
                         if response.success
                              console.log "Success"
                              @cleanAreas()
                              @textBodyResponse("Item added to news database", "#userMessage", false, "#textArea-alert", "#saveNewArticle")
                              $("#saveNewArticle").prop("disabled", false)
                         else if response.debug
                              console.log "debug"
                              $("#saveNewArticle").prop("disabled", false)
                         else if response.error
                              console.log "error"
                              @textBodyResponse(response.error,  "#userMessage", true, "#textArea-alert", "#saveNewArticle")  
                              $("#saveNewArticle").prop("disabled", false)
          else
               @textBodyResponse("You need to add something to the body of the article!", "#userMessage", true, "#textArea-alert", "#saveNewArticle")
     
                          
     
window.classes ?= {}
window.classes.dashboardNews = dashboardNews
window.objs ?= {}

$(document).ready ->
     window.objs.dashboardNews = new dashboardNews
