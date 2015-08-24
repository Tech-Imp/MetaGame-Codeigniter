
class commonShared
     debug: on
     constructor:->
          if @debug then console.log "commonShared.constructor"
          @setupEvents()
          
         
 
  
  
     setupEvents:=>
          if @debug then console.log "commonShared.setupEvents"
          $("li.previous").unbind().bind "click", (event)=>
               console.log $("li.previous").attr("data-loc")
               
          $("li.next").unbind().bind "click", (event)=>
               console.log $("li.next").attr("data-loc")
          
     
      
     
     getBooks:(genre="all")=>
          if @debug then console.log "commonShared.getBooks"
          $("#bookDatabase").html("")
          
          $.ajax
               url: "get/getBook"
               type: 'post'
               dataType: 'json'
               data:
                    type: genre
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
