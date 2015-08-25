// Generated by CoffeeScript 1.7.1
(function() {
  var commonShared,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  commonShared = (function() {
    commonShared.prototype.debug = true;

    function commonShared() {
      this.getData = __bind(this.getData, this);
      this.determineLoad = __bind(this.determineLoad, this);
      this.changePage = __bind(this.changePage, this);
      this.setupEvents = __bind(this.setupEvents, this);
      if (this.debug) {
        console.log("commonShared.constructor");
      }
      this.setupEvents();
    }

    commonShared.prototype.setupEvents = function() {
      if (this.debug) {
        console.log("commonShared.setupEvents");
      }
      $(".nextPage").unbind().bind("click", (function(_this) {
        return function(event) {
          if (!($(event.currentTarget).parent().hasClass("disabled"))) {
            console.log($(event.currentTarget).parent().html());
            console.log($(event.currentTarget).attr("data-loc"));
            return _this.changePage($(event.currentTarget).attr("data-loc"), "prev", "News");
          }
        };
      })(this));
      return $(".prevPage").unbind().bind("click", (function(_this) {
        return function(event) {
          if (!($(event.currentTarget).parent().hasClass("disabled"))) {
            console.log($(event.currentTarget).parent().html());
            console.log($(event.currentTarget).attr("data-loc"));
            return _this.changePage($(event.currentTarget).attr("data-loc"), "next", "News");
          }
        };
      })(this));
    };

    commonShared.prototype.changePage = function(offset, direction, type) {
      if (offset == null) {
        offset = 0;
      }
      if (direction == null) {
        direction = "next";
      }
      if (type == null) {
        type = "News";
      }
      if (this.debug) {
        console.log("commonShared.getBooks");
      }
      return $("#bookDatabase").html("");
    };

    commonShared.prototype.determineLoad = function() {
      if (this.debug) {
        console.log("commonShared.getBooks");
      }
      return $("#bookDatabase").html("");
    };

    commonShared.prototype.getData = function(type) {
      if (type == null) {
        type = "changes";
      }
      if (this.debug) {
        console.log("commonShared.getBooks");
      }
      $("#bookDatabase").html("");
      return $.ajax({
        url: "get/getData",
        type: 'post',
        dataType: 'json',
        data: {
          type: type
        },
        success: (function(_this) {
          return function(response) {
            var book, _i, _len, _ref, _results;
            if (response.success) {
              _ref = response.success;
              _results = [];
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                book = _ref[_i];
                _results.push(new window.classes.bookObject(book.uid, book.title, book.auth, book.page, book.cost, book.stock, book.wish, book.img));
              }
              return _results;
            } else if (response.debug) {
              new window.classes.bookObject(123, "20000 Leagues Under the Sea", "Jules Verne", 503, "$20", 13, 0);
              new window.classes.bookObject(456, "The Great Gatsby", "F. Scott Fitzgerald ", 320, "$15", 3, 1);
              new window.classes.bookObject(789, "Animal Farm", "George Orwell", 290, "$25", 1, 1);
              return new window.classes.bookObject(321, "The Hobbit", "J.R.R. Tolkien", 345, "$23", 0, 1);
            } else if (response.error) {
              return $("#bookDatabase").html("No books found.");
            }
          };
        })(this)
      });
    };

    return commonShared;

  })();

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.commonShared = commonShared;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.commonShared = new commonShared;
  });

}).call(this);
