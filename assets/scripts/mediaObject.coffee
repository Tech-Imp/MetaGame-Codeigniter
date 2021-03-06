
class mediaObject
     debug: on
     constructor:(@uid, @database, @name, @creator, @when,  @slug="", @glob="", @visFlag)->
          if @debug then console.log "mediaIndex.constructor"
          @addToRoster()
      
 
     addToRoster:=>  
          if @debug then console.log "bookIndex.addToRoster"
          if @stock > 0
               isAvail="In Stock"
          else
               isAvail="Out of Stock"

          wishFlag=""
          inWish=""          
          if @wish
               wishFlag="wishColor"
               inWish=", In Wishlist"
               
          $("#mediaDatabase").append """
               <div id="#{@uid}" class="col-lg-2 col-md-4 col-xs-12 well #{visFlag}">
                    <div><strong>#{@name} </strong></div>
                    <img class='visible-lg-block' src='#{@img}' width='98%'>
                    <div>Author: #{@author} </div>
                    <div>Date: #{@when} </div>
                    <img class='visible-lg-block' src='#{@img}' width='98%'>
                    <div>#{isAvail}#{inWish}</div>
                    <div>
                         <button type="button" class="btn btn-info mediaVisible">
                              <span class="glyphicon glyphicon-eye-open"></span>
                         </button>
                         <button type="button" class="btn btn-info mediaInvisible">
                              <span class="glyphicon glyphicon-eye-close"></span>
                         </button>
                         <button type="button" class="btn btn-warning editMedia">
                              <span class="glyphicon glyphicon-pencil"></span>
                         </button>
                         <button type="button" class="btn btn-primary cartBuy">
                              <span class="glyphicon glyphicon-shopping-cart"></span>
                         </button>
                    </div>
               </div>
          """
#                
          # $("#mediaDatabase").append """
               # <div id="#{@uid}" class="col-lg-2 col-md-4 col-xs-12 well #{wishFlag}">
                    # <div><strong>#{@name}, by #{@author}</strong></div>
                    # <img class='visible-lg-block' src='#{@img}' width='98%'>
                    # <div>#{isAvail}#{inWish}</div>
                    # <div>
                         # <button type="button" class="btn btn-info inspect">
                              # <span class="glyphicon glyphicon-eye-open"></span>
                         # </button>
                         # <button type="button" class="btn btn-success wishAdd">
                              # <span class="glyphicon glyphicon-star"></span>
                         # </button>
                         # <button type="button" class="btn btn-primary cartBuy">
                              # <span class="glyphicon glyphicon-shopping-cart"></span>
                         # </button>
                    # </div>
               # </div>
          # """
          
          
          $("#"+@uid+" .inspect").unbind().bind "click", (event)=>
               console.log "clicked" +@uid
               @populateInspect()
               
          $("#"+@uid+" .cartBuy").unbind().bind "click", (event)=>
               @addToCart()
               
          $("#"+@uid+" .editMedia").unbind().bind "click", (event)=>
               @addToWish()
     
     populateInspect:=>
          if @debug then console.log "bookIndex.populateInspect"
          
          $("#bookTitle").html(@name)
          $("#bookAuthor").html(@author)
          $("#bookPages").html(@pages)
          $("#bookPrice").html(@price)
          if @stock>0
               $("#bookStock").html(@stock)
          else
               $("#bookStock").html("Out of Stock") 
          if @wish
               $("#bookWish").html("In Wishlist")
          else
               $("#bookWish").html("")
               
          $("#bookModalImg").attr("src", @img)
          
          $("#bookReviewModal").modal("show")
            
     addToCart:=>
          if @debug then console.log "bookIndex.addToCart"
          # info=$("##{uid}").data("bookInfo")
          # info.uid
          
          
     addToWish:=>
          if @debug then console.log "bookIndex.addToWish"
          $.ajax
               url: "post/addToWishlist"
               type: 'post'
               dataType: 'json'
               data:
                    uid: @uid
                    title: @name
                    author: @author
               success: (response)=>
                    if response.success
                         @updateWishlist()
                         
     
     updateWishlist:=>
          if @debug then console.log "bookIndex.updateWishlist"
                       
                         
window.classes ?= {}
window.classes.bookObject = bookObject
