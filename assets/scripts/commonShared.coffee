
class commonShared
     debug: on
     constructor:->
          if @debug then console.log "commonShared.constructor"
          @setupEvents()
          
        
     setupEvents:=>
          if @debug then console.log "commonShared.setupEvents"
          $(".nextPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    console.log $(event.currentTarget).parent().html()
                    console.log $(event.currentTarget).attr("data-loc")
                    
                    @changePage($(event.currentTarget).attr("data-loc"), "next", "News")
               
          $(".prevPage").unbind().bind "click", (event)=>
               if !($(event.currentTarget).parent().hasClass("disabled"))
                    console.log $(event.currentTarget).parent().html()
                    console.log $(event.currentTarget).attr("data-loc")
                    
                    @changePage($(event.currentTarget).attr("data-loc"), "prev", "News")
          
     
      
     
     changePage:(offset=0, direction="next", type="News", orig=false)=>
          if @debug then console.log "commonShared.changePage"
          $("#bookDatabase").html("")
          # TODO need to finish this area
          location=direction+type
          $.ajax
               url: window.location.origin+"/paging/"+location
               type: 'post'
               dataType: 'json'
               data:
                    offset: offset
               success: (response)=>
                    if response.success
                         if(orig==false)
                              $("#mediaDatabase").html(response.success)
                         else
                              console.log "Data returned, need location to export"
                         @setupEvents()
                         # for book in response.success
                              # new window.classes.bookObject(book.uid, book.title, book.auth, book.page, book.cost, book.stock, book.wish, book.img)
                    # else if response.debug
                         # # uid, name, author, pages, price, stock, wish, img
                         # new window.classes.bookObject(123, "20000 Leagues Under the Sea", "Jules Verne", 503, "$20", 13, 0 )
                         # new window.classes.bookObject(456, "The Great Gatsby", "F. Scott Fitzgerald ", 320, "$15", 3, 1 )
                         # new window.classes.bookObject(789, "Animal Farm", "George Orwell", 290, "$25", 1, 1 )
                         # new window.classes.bookObject(321, "The Hobbit", "J.R.R. Tolkien", 345, "$23", 0, 1 )
                    # else if response.error
                         # $("#bookDatabase").html("No books found.")        
#      
     
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
