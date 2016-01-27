
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
                    # console.log store
                    if (store!=false)
                         @changePage(store, "next")
               
          $(".prevPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    store=@whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"))
                    # console.log store
                    if (store!=false)
                         @changePage(store, "prev")
          
     
     # Determines which paging algorithm from paging controller to run and also determines where this data should be put 
     whichDatabase:(target, offset, selector)=>
          if @debug then console.log "commonShared.whichDatabase"
          # Here is used to determine which page we are on. It can only do paging on pages it is set for
          here=window.location.pathname
          # console.log here
          #Type determines vintage status, origin is where to put the data
          location=
               current: here
               offset: offset
               type: selector
          currentEnd=here.split("/")
               
          switch currentEnd.pop()
               when "photos" 
                    location["orig"]= target.parents("div.tab-pane")
               when "video"
                   location["orig"]=target.parents("div.tab-pane")
               when "news"
                    location["orig"]=$("#mediaDatabase")
               when "media"
                    location["orig"]=$("#mediaTable")
               when "article"
                    location["orig"]=$("#mediaTable")
               when "media" 
                    location["orig"]= target.parents("div.tab-pane")
               else
                    location=false     
          return location
          
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
                         database: database.current
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
