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
            if (store !== false) {
              return _this.changePage(store, "prev");
            }
          }
        };
      })(this));
    };

    commonShared.prototype.whichDatabase = function(target, offset, selector) {
      var currentEnd, here, location;
      if (this.debug) {
        console.log("commonShared.whichDatabase");
      }
      here = window.location.pathname;
      location = {
        current: here,
        offset: offset,
        type: selector
      };
      currentEnd = here.split("/");
      switch (currentEnd.pop()) {
        case "photos":
          location["orig"] = target.parents("div.tab-pane");
          break;
        case "video":
          location["orig"] = target.parents("div.tab-pane");
          break;
        case "news":
          location["orig"] = $("#mediaDatabase");
          break;
        case "multimedia":
          location["orig"] = $("#mediaTable");
          break;
        case "articles":
          location["orig"] = $("#mediaDatabase");
          break;
        case "written":
          location["orig"] = $("#mediaTable");
          break;
        case "media":
          location["orig"] = target.parents("div.tab-pane");
          break;
        default:
          location = false;
      }
      return location;
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
            database: database.current,
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
