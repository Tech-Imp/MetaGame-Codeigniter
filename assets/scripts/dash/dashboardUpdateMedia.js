// Generated by CoffeeScript 1.7.1
(function() {
  var dashboardUpdateMedia,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  dashboardUpdateMedia = (function(_super) {
    __extends(dashboardUpdateMedia, _super);

    dashboardUpdateMedia.prototype.debug = true;

    function dashboardUpdateMedia() {
      this.deleteMedia = __bind(this.deleteMedia, this);
      this.saveEdits = __bind(this.saveEdits, this);
      this.setupEvents = __bind(this.setupEvents, this);
      if (this.debug) {
        console.log("dashboardUpdateMedia.constructor");
      }
      dashboardUpdateMedia.__super__.constructor.call(this);
      this.setupEvents();
    }

    dashboardUpdateMedia.prototype.setupEvents = function() {
      if (this.debug) {
        console.log("dashboardUpdateMedia.setupEvents");
      }
      $("#saveEdits").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.saveEdits();
        };
      })(this));
      return $("#permDelete").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.deleteMedia();
        };
      })(this));
    };

    dashboardUpdateMedia.prototype.saveEdits = function() {
      if (this.debug) {
        console.log("dashboardUpdateMedia.saveEdits");
      }
      return $.ajax({
        url: this.base_url + "/post/saveMediaEdit",
        type: 'post',
        dataType: 'json',
        data: {
          title: $("#mediaTitle").val(),
          stub: $("#mediaStub").val(),
          visibleWhen: $("#mediaWhen").val(),
          mediaID: $('#mediaID').val(),
          loggedOnly: $('#mediaLogged').val(),
          vintage: $('#mediaVintage').val()
        },
        success: (function(_this) {
          return function(response) {
            if (response.success) {
              return _this.textBodyResponse("Edit saved to database", "#userMessage", false, "#textArea-alert");
            } else if (response.debug) {
              return console.log("debug");
            } else if (response.error) {
              return _this.textBodyResponse(response.error, "#userMessage", true, "#textArea-alert");
            }
          };
        })(this)
      });
    };

    dashboardUpdateMedia.prototype.deleteMedia = function() {
      if (this.debug) {
        console.log("dashboardUpdateMedia.deleteMedia");
      }
      return $.ajax({
        url: this.base_url + "/post/deleteSpecificMedia",
        type: 'post',
        dataType: 'json',
        data: {
          mediaID: $('#mediaID').val()
        },
        success: (function(_this) {
          return function(response) {
            if (response.success) {
              console.log("Success");
              return location.reload();
            } else if (response.debug) {
              return console.log("debug");
            } else if (response.error) {
              return _this.textBodyResponse(response.error, "#userMessage", true, "#textArea-alert");
            }
          };
        })(this)
      });
    };

    return dashboardUpdateMedia;

  })(window.classes.dashboardIndex);

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.dashboardUpdateMedia = dashboardUpdateMedia;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.dashboardUpdateMedia = new dashboardUpdateMedia;
  });

}).call(this);
