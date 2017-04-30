// Generated by CoffeeScript 1.12.4
(function() {
  var adminUserAdjust,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    hasProp = {}.hasOwnProperty;

  adminUserAdjust = (function(superClass) {
    extend(adminUserAdjust, superClass);

    adminUserAdjust.prototype.debug = true;

    function adminUserAdjust() {
      this.updateUser = bind(this.updateUser, this);
      this.setupEvents = bind(this.setupEvents, this);
      if (this.debug) {
        console.log("adminUserAdjust.constructor");
      }
      adminUserAdjust.__super__.constructor.call(this);
      this.setupEvents();
    }

    adminUserAdjust.prototype.setupEvents = function() {
      if (this.debug) {
        console.log("adminUserAdjust.setupEvents");
      }
      $("#setRoleNorm").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#setSectAdmin").prop("disabled", "disabled");
          $("#setRoleContrib").prop("disabled", "disabled");
          $("#setRoleNorm").prop("disabled", "disabled");
          return _this.updateUser("norm");
        };
      })(this));
      $("#setRoleContrib").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#setSectAdmin").prop("disabled", "disabled");
          $("#setRoleContrib").prop("disabled", "disabled");
          $("#setRoleNorm").prop("disabled", "disabled");
          return _this.updateUser("contrib");
        };
      })(this));
      return $("#setSectAdmin").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#setSectAdmin").prop("disabled", "disabled");
          $("#setRoleContrib").prop("disabled", "disabled");
          $("#setRoleNorm").prop("disabled", "disabled");
          return _this.updateUser("sect");
        };
      })(this));
    };

    adminUserAdjust.prototype.updateUser = function(myRank) {
      if (myRank == null) {
        myRank = null;
      }
      if (this.debug) {
        console.log("adminUserAdjust.removeUser");
      }
      return $.ajax({
        url: this.base_url + "/admin/securepost/setRole",
        type: 'post',
        dataType: 'json',
        data: {
          userID: $('#assocID').val(),
          rank: myRank
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

    return adminUserAdjust;

  })(window.classes.dashboardIndex);

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.adminUserAdjust = adminUserAdjust;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.adminUserAdjust = new adminUserAdjust;
  });

}).call(this);
