// Generated by CoffeeScript 1.11.1
(function() {
  var dashboardIndex,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  dashboardIndex = (function() {
    dashboardIndex.prototype.debug = true;

    function dashboardIndex() {
      this.textBodyResponse = bind(this.textBodyResponse, this);
      this.cleanAreas = bind(this.cleanAreas, this);
      this.setupCommon = bind(this.setupCommon, this);
      if (this.debug) {
        console.log("dashboardIndex.constructor");
      }
      this.setupCommon();
      this.base_url = window.location.origin;
    }

    dashboardIndex.prototype.setupCommon = function() {
      if (this.debug) {
        console.log("dashboardIndex.setupCommon");
      }
      $(".when").datepicker({
        dateFormat: "yy-mm-dd"
      });
      return $(".when").datepicker("setDate", new Date());
    };

    dashboardIndex.prototype.cleanAreas = function() {
      if (this.debug) {
        console.log("dashboardIndex.cleanAreas");
      }
      $('.cleanMe').html("");
      $('.cleanMe').val("");
      $(".textReq").val("");
      $(".textReq").trigger("keyup");
      return $(".when").datepicker("setDate", new Date());
    };

    dashboardIndex.prototype.textBodyResponse = function(message, messageID, error, alertID, uploadID) {
      if (error == null) {
        error = false;
      }
      if (uploadID == null) {
        uploadID = null;
      }
      console.log(message);
      $(messageID).html(message);
      if (error) {
        $(alertID).removeClass("alert-success").addClass("alert-danger");
      } else {
        $(alertID).removeClass("alert-danger").addClass("alert-success");
      }
      $(alertID).removeClass("noshow");
      return $(alertID).fadeTo(2000, 500).slideUp(500, function() {
        $(alertID).addClass("noshow");
        if (uploadID !== null) {
          return $(uploadID).prop("disabled", false);
        }
      });
    };

    return dashboardIndex;

  })();

  ({
    refreshData: (function(_this) {
      return function() {
        if (_this.debug) {
          console.log("dashboardIndex.refreshData");
        }
        return $(".submitButton").unbind().bind("click", function(event) {
          if (!($(event.currentTarget).parent().hasClass("disabled"))) {
            return _this.changePage();
          }
        });
      };
    })(this),
    changePage: (function(_this) {
      return function() {
        if (_this.debug) {
          console.log("dashboardIndex.changePage");
        }
        return $.ajax({
          url: window.location.origin + "/paging/specificPage",
          type: 'post',
          dataType: 'json',
          data: {
            offset: 0,
            database: window.location.pathname,
            type: "all"
          },
          success: function(response) {
            if (response.success) {
              $("#mediaTable").html(response.success);
              return _this.refreshData();
            }
          }
        });
      };
    })(this)
  });

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.dashboardIndex = dashboardIndex;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.dashboardIndex = new dashboardIndex;
  });

}).call(this);
