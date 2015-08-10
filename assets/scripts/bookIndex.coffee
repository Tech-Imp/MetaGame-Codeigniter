
class bookIndex
     debug: on
     constructor:->
          if @debug then console.log "bookIndex.constructor"
          @setupEvents()
          
          @getBooks()
 
  
  
     setupEvents:=>
          if @debug then console.log "bookIndex.setupEvents"
          $(".modal").on "hidden.bs.modal", ->
               console.log "Hiding"
               $(".modal").removeData "bs.modal"
          
          $("#myCart").unbind().bind "click", (event)=>
               console.log "OVERRIDE"

          $(".unavail").unbind().bind "click", (event)=>
               alert("Sorry, these buttons would alter the database query appropriately, but theres no database for them to act on. They are unavailable for practical use in this demo.");
               @getBooks()
     
      
     
     getBooks:(genre="all")=>
          if @debug then console.log "bookIndex.getBooks"
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
          if @debug then console.log "bookIndex.getBooks"
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
window.classes.bookIndex = bookIndex
window.objs ?= {}

$(document).ready ->
     window.objs.bookIndex = new bookIndex
