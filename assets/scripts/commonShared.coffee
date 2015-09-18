
class commonShared
     debug: on
     constructor:->
          if @debug then console.log "commonShared.constructor"
          @setupEvents()
          
        
     setupEvents:=>
          if @debug then console.log "commonShared.setupEvents"
          $(".nextPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    # console.log $(event.currentTarget).parent().html()
                    # console.log $(event.currentTarget).attr("data-loc")
                    store=@whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"))
                    console.log store
                    if (store!=false)
                         @changePage(store, "next")
               
          $(".prevPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    # console.log $(event.currentTarget).parent().html()
                    # console.log $(event.currentTarget).attr("data-loc")
                    store=@whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"))
                    console.log store
                    if (store!=false)
                         @changePage(store, "prev")
          
     
     # Determines which paging algorithm from paging controller to run and also determines where this data should be put 
     whichDatabase:(target, offset, selector)=>
           if @debug then console.log "commonShared.whichDatabase"
           here=window.location.pathname
           console.log here
           switch here
               when "/main/photos" 
                    # TODO Determine if normal or vintage
                    location=
                         orig: target.parents("div.tab-pane")
                         name: "image"     
                         offset: offset
                         type: selector
                    return location
               when "/main/video"
                    # TODO Determine if normal or vintage
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
     
     determineLoad:()=>
          if @debug then console.log "commonShared.getBooks"
          $("#bookDatabase").html("")     
     
     
     
     
     
     
          
     getData:(type="changes")=>
          if @debug then console.log "commonShared.getBooks"
          $("#bookDatabase").html("")
          
          $.ajax
               url: "get/getData"
               type: 'post'
               dataType: 'json'
               data:
                    type: type
               success: (response)=>
                    if response.success
                         for book in response.success
                              new window.classes.bookObject(book.uid, book.title, book.auth, book.page, book.cost, book.stock, book.wish, book.img)
                    else if response.debug
                         # uid, name, author, pages, price, stock, wish, img
                         new window.classes.bookObject(123, "20000 Leagues Under the Sea", "Jules Verne", 503, "$20", 13, 0 )
                         new window.classes.bookObject(456, "The Great Gatsby", "F. Scott Fitzgerald ", 320, "$15", 3, 1 )
                         new window.classes.bookObject(789, "Animal Farm", "George Orwell", 290, "$25", 1, 1 )
                         new window.classes.bookObject(321, "The Hobbit", "J.R.R. Tolkien", 345, "$23", 0, 1 )
                    else if response.error
                         $("#bookDatabase").html("No books found.")      
  
     
window.classes ?= {}
window.classes.commonShared = commonShared
window.objs ?= {}

$(document).ready ->
     window.objs.commonShared = new commonShared
