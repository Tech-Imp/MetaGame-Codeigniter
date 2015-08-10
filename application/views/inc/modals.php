<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="loginLabel">Login to Books Inc</h4>
      </div>
      <div class="modal-body">
       	<label>User Name:</label> <input type="text" id="loginID"><br>
  		<label>Password:</label> <input type="password" id="loginPass"><br>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary">Login</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="cartLabel">Shopping Cart</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Proceed to Checkout</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="wishlistModal" tabindex="-1" role="dialog" aria-labelledby="wishlistLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="wishlistLabel">My Wishlist</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bookReviewModal" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="bookModalLabel">Book Info</h4>
      </div>
      <div class="modal-body">
      		<img id="bookModalImg">
      		<table>
      			<tr>
      				<td><strong>Title</strong></td>
      				<td id="bookTitle"></td>
      				<td><strong>Price</strong></td>
      				<td id="bookPrice"></td>
      			</tr>
      			<tr>
      				<td><strong>Author</strong></td>
      				<td id="bookAuthor"></td>
      				<td><strong># of Pages</strong></td>
      				<td id="bookPages"></td>
      			</tr>
      			<tr>
      				<td><strong># in Stock</strong></td>
      				<td id="bookStock"></td>
      				<td></td>
      				<td></td>
      			</tr>
      		</table>
      		<div id="bookWish"></div>
      		<hr />
      		<div>
      			<label>Synoppsis:</label>
      			
      		</div>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary wishAdd">Add to Wishlist</button>
        <button type="button" class="btn btn-primary cartBuy">Add to Cart</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>