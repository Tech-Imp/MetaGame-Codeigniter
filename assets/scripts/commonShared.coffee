
class commonShared
     debug: on
     constructor:->
          if @debug then console.log "commonShared.constructor"
          @setupEvents()
          
        
     setupEvents:=>
          if @debug then console.log "commonShared.setupEvents"
          $(".nextPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    store=@whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"))
                    console.log store
                    if (store!=false)
                         @changePage(store, "next")
               
          $(".prevPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    store=@whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"))
                    console.log store
                    if (store!=false)
                         @changePage(store, "prev")
          
     
     # Determines which paging algorithm from paging controller to run and also determines where this data should be put 
     whichDatabase:(target, offset, selector)=>
           if @debug then console.log "commonShared.whichDatabase"
           # Here is used to determine which page we are on. It can only do paging on pages it is set for
           here=window.location.pathname
           switch here
               when "/main/photos" 
                    #Type determines vintage status, name is which database, origin is where to put the data
                    location=
                         orig: target.parents("div.tab-pane")
                         name: "image"     
                         offset: offset
                         type: selector
                    return location
               when "/main/video"
                    location=
                         orig: target.parents("div.tab-pane")
                         name: "video"
                         offset: offset
                         type: selector
                    return location     
               when "/main/news"
                    location=
                         orig: $("#mediaDatabase")
                         name: "news"
                         offset: offset
                         type: selector
                    return location
               when "/admin/dashboard/media"
                    location=
                         orig: $("#mediaTable")
                         name: "media"
                         offset: offset
                         type: selector
                    return location
               when "/admin/dashboard/article"
                    location=
                         orig: $("#mediaTable")
                         name: "articles"
                         offset: offset
                         type: selector
                    return location
               else
                    return false     
         
          
     changePage:(database=false, direction="next")=>
          if @debug then console.log "commonShared.changePage"
          location=direction+"Page"
          
          if database != false
               $.ajax
                    url: window.location.origin+"/paging/"+location
                    type: 'post'
                    dataType: 'json'
                    data:
                         offset: database.offset
                         database: database.name
                         type: database.type
                    success: (response)=>
                         if response.success
                              database.orig.html(response.success)
                              @setupEvents()
     
  
     
window.classes ?= {}
window.classes.commonShared = commonShared
window.objs ?= {}

$(document).ready ->
     window.objs.commonShared = new commonShared
