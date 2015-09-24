// Generated by CoffeeScript 1.10.0
(function() {
  var mediaObject,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  mediaObject = (function() {
    mediaObject.prototype.debug = true;

    function mediaObject(uid, database, name, creator, when, slug, glob, visFlag1) {
      this.uid = uid;
      this.database = database;
      this.name = name;
      this.creator = creator;
      this.when = when;
      this.slug = slug != null ? slug : "";
      this.glob = glob != null ? glob : "";
      this.visFlag = visFlag1;
      this.updateWishlist = bind(this.updateWishlist, this);
      this.addToWish = bind(this.addToWish, this);
      this.addToCart = bind(this.addToCart, this);
      this.populateInspect = bind(this.populateInspect, this);
      this.addToRoster = bind(this.addToRoster, this);
      if (this.debug) {
        console.log("mediaIndex.constructor");
      }
      this.addToRoster();
    }

    mediaObject.prototype.addToRoster = function() {
      var inWish, isAvail, wishFlag;
      if (this.debug) {
        console.log("bookIndex.addToRoster");
      }
      if (this.stock > 0) {
        isAvail = "In Stock";
      } else {
        isAvail = "Out of Stock";
      }
      wishFlag = "";
      inWish = "";
      if (this.wish) {
        wishFlag = "wishColor";
        inWish = ", In Wishlist";
      }
      $("#mediaDatabase").append("<div id=\"" + this.uid + "\" class=\"col-lg-2 col-md-4 col-xs-12 well " + visFlag + "\">\n     <div><strong>" + this.name + " </strong></div>\n     <img class='visible-lg-block' src='" + this.img + "' width='98%'>\n     <div>Author: " + this.author + " </div>\n     <div>Date: " + this.when + " </div>\n     <img class='visible-lg-block' src='" + this.img + "' width='98%'>\n     <div>" + isAvail + inWish + "</div>\n     <div>\n          <button type=\"button\" class=\"btn btn-info mediaVisible\">\n               <span class=\"glyphicon glyphicon-eye-open\"></span>\n          </button>\n          <button type=\"button\" class=\"btn btn-info mediaInvisible\">\n               <span class=\"glyphicon glyphicon-eye-close\"></span>\n          </button>\n          <button type=\"button\" class=\"btn btn-warning editMedia\">\n               <span class=\"glyphicon glyphicon-pencil\"></span>\n          </button>\n          <button type=\"button\" class=\"btn btn-primary cartBuy\">\n               <span class=\"glyphicon glyphicon-shopping-cart\"></span>\n          </button>\n     </div>\n</div>");
      $("#" + this.uid + " .inspect").unbind().bind("click", (function(_this) {
        return function(event) {
          console.log("clicked" + _this.uid);
          return _this.populateInspect();
        };
      })(this));
      $("#" + this.uid + " .cartBuy").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.addToCart();
        };
      })(this));
      return $("#" + this.uid + " .editMedia").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.addToWish();
        };
      })(this));
    };

    mediaObject.prototype.populateInspect = function() {
      if (this.debug) {
        console.log("bookIndex.populateInspect");
      }
      $("#bookTitle").html(this.name);
      $("#bookAuthor").html(this.author);
      $("#bookPages").html(this.pages);
      $("#bookPrice").html(this.price);
      if (this.stock > 0) {
        $("#bookStock").html(this.stock);
      } else {
        $("#bookStock").html("Out of Stock");
      }
      if (this.wish) {
        $("#bookWish").html("In Wishlist");
      } else {
        $("#bookWish").html("");
      }
      $("#bookModalImg").attr("src", this.img);
      return $("#bookReviewModal").modal("show");
    };

    mediaObject.prototype.addToCart = function() {
      if (this.debug) {
        return console.log("bookIndex.addToCart");
      }
    };

    mediaObject.prototype.addToWish = function() {
      if (this.debug) {
        console.log("bookIndex.addToWish");
      }
      return $.ajax({
        url: "post/addToWishlist",
        type: 'post',
        dataType: 'json',
        data: {
          uid: this.uid,
          title: this.name,
          author: this.author
        },
        success: (function(_this) {
          return function(response) {
            if (response.success) {
              return _this.updateWishlist();
            }
          };
        })(this)
      });
    };

    mediaObject.prototype.updateWishlist = function() {
      if (this.debug) {
        return console.log("bookIndex.updateWishlist");
      }
    };

    return mediaObject;

  })();

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.bookObject = bookObject;

}).call(this);
