// Generated by CoffeeScript 1.10.0
(function() {
  var adminTools,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    hasProp = {}.hasOwnProperty;

  adminTools = (function(superClass) {
    extend(adminTools, superClass);

    adminTools.prototype.debug = true;

    function adminTools() {
      this.addUser = bind(this.addUser, this);
      this.saveSection = bind(this.saveSection, this);
      this.setupEvents = bind(this.setupEvents, this);
      if (this.debug) {
        console.log("adminTools.constructor");
      }
      adminTools.__super__.constructor.call(this);
      this.setupEvents();
    }

    adminTools.prototype.setupEvents = function() {
      if (this.debug) {
        console.log("adminTools.setupEvents");
      }
      $("#sectionUsage").tinymce({
        script_url: this.base_url + '/assets/scripts/tinymce/tinymce.min.js',
        theme: "modern",
        plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
      });
      $("#saveNewSection").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveNewSection").prop("disabled", "disabled");
          return _this.saveSection();
        };
      })(this));
      $("#saveNewContact").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveNewContact").prop("disabled", "disabled");
          return _this.addUser();
        };
      })(this));
      $("#clearSection").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.cleanAreas();
        };
      })(this));
      return $('#sectionController').keyup(function() {
        var empty;
        empty = false;
        $('.textReq').each(function() {
          if ($(this).val() === '') {
            return empty = true;
          }
        });
        if (empty) {
          console.log("remain locked");
          return $("#saveNewSection").prop("disabled", "disabled");
        } else {
          console.log("open");
          return $("#saveNewSection").prop("disabled", false);
        }
      });
    };

    adminTools.prototype.saveSection = function() {
      if (this.debug) {
        console.log("adminTools.saveSection");
      }
      if ($.trim($("#sectionUsage").html())) {
        return $.ajax({
          url: this.base_url + "/admin/securepost/addSection",
          type: 'post',
          dataType: 'json',
          data: {
            section: $('#secName').val(),
            usage: $('#sectionUsage').html(),
            sub_dir: $('#secDir').val()
          },
          success: (function(_this) {
            return function(response) {
              if (response.success) {
                console.log("Success");
                _this.cleanAreas();
                _this.textBodyResponse("Profile Info added to the database", "#sectionMessage", false, "#sectionArea-alert", "#saveNewSection");
                return $("#saveNewContact").prop("disabled", false);
              } else if (response.debug) {
                console.log("debug");
                return $("#saveNewContact").prop("disabled", false);
              } else if (response.error) {
                console.log("error");
                _this.textBodyResponse(response.error, "#sectionMessage", true, "#sectionArea-alert", "#saveNewSection");
                return $("#saveNewContact").prop("disabled", false);
              }
            };
          })(this)
        });
      } else {
        return this.textBodyResponse("You need fill in all the fields!", "#sectionMessage", true, "#sectionArea-alert", "#saveNewSection");
      }
    };

    adminTools.prototype.addUser = function() {
      if (this.debug) {
        console.log("adminTools.addUser");
      }
      return $.ajax({
        url: this.base_url + "/admin/securepost/addUserToSection",
        type: 'post',
        dataType: 'json',
        data: {
          section: $('#addToSection').val(),
          user: $('#addPerson').val()
        },
        success: (function(_this) {
          return function(response) {
            if (response.success) {
              console.log("Success");
              _this.cleanAreas();
              _this.textBodyResponse("Profile Info added to the database", "#roleMessage", false, "#roleArea-alert", "#saveNewContact");
              return $("#saveNewContact").prop("disabled", false);
            } else if (response.debug) {
              console.log("debug");
              return $("#saveNewContact").prop("disabled", false);
            } else if (response.error) {
              console.log("error");
              _this.textBodyResponse(response.error, "#roleMessage", true, "#roleArea-alert", "#saveNewContact");
              return $("#saveNewContact").prop("disabled", false);
            }
          };
        })(this)
      });
    };

    return adminTools;

  })(window.classes.dashboardIndex);

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.adminTools = adminTools;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.adminTools = new adminTools;
  });

}).call(this);