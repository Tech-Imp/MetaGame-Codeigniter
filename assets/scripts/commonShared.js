// Generated by CoffeeScript 1.10.0
(function() {
  var commonShared,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  commonShared = (function() {
    commonShared.prototype.debug = true;

    function commonShared() {
      this.changePage = bind(this.changePage, this);
      this.whichDatabase = bind(this.whichDatabase, this);
      this.setupEvents = bind(this.setupEvents, this);
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
          var store;
          if (!($(event.currentTarget).parent().hasClass("disabled"))) {
            store = _this.whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"));
            console.log(store);
            if (store !== false) {
              return _this.changePage(store, "next");
            }
          }
        };
      })(this));
      return $(".prevPage").unbind().bind("click", (function(_this) {
        return function(event) {
          var store;
          if (!($(event.currentTarget).parent().hasClass("disabled"))) {
            store = _this.whichDatabase($(event.currentTarget), $(event.currentTarget).attr("data-loc"), $(event.currentTarget).attr("data-type"));
            console.log(store);
            if (store !== false) {
              return _this.changePage(store, "prev");
            }
          }
        };
      })(this));
    };

    commonShared.prototype.whichDatabase = function(target, offset, selector) {
      var here, location;
      if (this.debug) {
        console.log("commonShared.whichDatabase");
      }
      here = window.location.pathname;
      switch (here) {
        case "/main/photos":
          location = {
            orig: target.parents("div.tab-pane"),
            name: "image",
            offset: offset,
            type: selector
          };
          return location;
        case "/main/video":
          location = {
            orig: target.parents("div.tab-pane"),
            name: "video",
            offset: offset,
            type: selector
          };
          return location;
        case "/main/news":
          location = {
            orig: $("#mediaDatabase"),
            name: "news",
            offset: offset,
            type: selector
          };
          return location;
        case "/admin/dashboard/media":
          location = {
            orig: $("#mediaTable"),
            name: "media",
            offset: offset,
            type: selector
          };
          return location;
        case "/admin/dashboard/article":
          location = {
            orig: $("#mediaTable"),
            name: "articles",
            offset: offset,
            type: selector
          };
          return location;
        default:
          return false;
      }
    };

    commonShared.prototype.changePage = function(database, direction) {
      var location;
      if (database == null) {
        database = false;
      }
      if (direction == null) {
        direction = "next";
      }
      if (this.debug) {
        console.log("commonShared.changePage");
      }
      location = direction + "Page";
      if (database !== false) {
        return $.ajax({
          url: window.location.origin + "/paging/" + location,
          type: 'post',
          dataType: 'json',
          data: {
            offset: database.offset,
            database: database.name,
            type: database.type
          },
          success: (function(_this) {
            return function(response) {
              if (response.success) {
                database.orig.html(response.success);
                return _this.setupEvents();
              }
            };
          })(this)
        });
      }
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