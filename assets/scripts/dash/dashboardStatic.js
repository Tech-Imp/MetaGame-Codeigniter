// Generated by CoffeeScript 1.7.1
(function() {
  var dashboardStatic,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  dashboardStatic = (function(_super) {
    __extends(dashboardStatic, _super);

    dashboardStatic.prototype.debug = true;

    function dashboardStatic() {
      this.saveContact = __bind(this.saveContact, this);
      this.setupEvents = __bind(this.setupEvents, this);
      if (this.debug) {
        console.log("dashboardStatic.constructor");
      }
      dashboardStatic.__super__.constructor.call(this);
      this.setupEvents();
    }

    dashboardStatic.prototype.setupEvents = function() {
      if (this.debug) {
        console.log("dashboardStatic.setupEvents");
      }
      $("#mceContact").tinymce({
        script_url: this.base_url + '/assets/scripts/tinymce/tinymce.min.js',
        theme: "modern",
        plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
      });
      $("#saveNewContact").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveNewContact").prop("disabled", "disabled");
          return _this.saveContact();
        };
      })(this));
      return $("#clearArticle").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.cleanAreas();
        };
      })(this));
    };

    dashboardStatic.prototype.saveContact = function() {
      if (this.debug) {
        console.log("dashboardStatic.saveContact");
      }
      if ($.trim($("#mceContact").html())) {
        return $.ajax({
          url: this.base_url + "/post/addContact",
          type: 'post',
          dataType: 'json',
          data: {
            title: $('#contactTitle').html(),
            bodyText: $('#mceContact').html()
          },
          success: (function(_this) {
            return function(response) {
              if (response.success) {
                console.log("Success");
                _this.cleanAreas();
                _this.textBodyResponse("Contact Info added to the database", "#userMessage", false, "#textArea-alert", "#saveNewContact");
                return $("#saveNewContact").prop("disabled", false);
              } else if (response.debug) {
                console.log("debug");
                return $("#saveNewContact").prop("disabled", false);
              } else if (response.error) {
                console.log("error");
                _this.textBodyResponse(response.error, "#userMessage", true, "#textArea-alert", "#saveNewContact");
                return $("#saveNewArticle").prop("disabled", false);
              }
            };
          })(this)
        });
      } else {
        return this.textBodyResponse("You need to add Contact Info!", "#userMessage", true, "#textArea-alert", "#saveNewArticle");
      }
    };

    return dashboardStatic;

  })(window.classes.dashboardIndex);

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.dashboardStatic = dashboardStatic;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.dashboardStatic = new dashboardStatic;
  });

}).call(this);
