// Generated by CoffeeScript 1.7.1
(function() {
  var dashboardMedia,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  dashboardMedia = (function(_super) {
    __extends(dashboardMedia, _super);

    dashboardMedia.prototype.debug = true;

    function dashboardMedia() {
      this.saveEmbed = __bind(this.saveEmbed, this);
      this.setupEvents = __bind(this.setupEvents, this);
      if (this.debug) {
        console.log("dashboardMedia.constructor");
      }
      dashboardMedia.__super__.constructor.call(this);
      this.setupEvents();
    }

    dashboardMedia.prototype.setupEvents = function() {
      var myUploader;
      if (this.debug) {
        console.log("dashboardMedia.setupEvents");
      }
      $('.nUpload').plupload({
        runtimes: 'html5,flash,silverlight,html4',
        url: this.base_url + '/post/CIUpload',
        max_file_size: '4mb',
        chunk_size: '1mb',
        filters: [
          {
            title: 'Image files',
            extensions: 'jpg,gif,png'
          }, {
            title: 'Zip files',
            extensions: 'zip,avi'
          }
        ],
        rename: true,
        sortable: true,
        dragdrop: true,
        views: {
          list: true,
          thumbs: true,
          active: 'thumbs'
        },
        flash_swf_url: '/plupload/js/Moxie.swf',
        silverlight_xap_url: '/plupload/js/Moxie.xap'
      });
      myUploader = $('.nUpload').plupload('getUploader');
      myUploader.bind('BeforeUpload', (function(_this) {
        return function(up, file) {
          return up.settings.multipart_params = {
            title: $("#uploadTitle").val(),
            stub: $("#uploadStub").val(),
            visibleWhen: $("#uploadWhen").val(),
            loggedOnly: $('#uploadLogged').val()
          };
        };
      })(this));
      $("#saveEmbedded").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveEmbedded").prop("disabled", "disabled");
          return _this.saveEmbed();
        };
      })(this));
      $("#clearEmbed").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.cleanAreas();
        };
      })(this));
      return $('#embedAccord').keyup(function() {
        var empty;
        empty = false;
        $('#embedAccord .textReq').each(function() {
          if ($(this).val() === '') {
            return empty = true;
          }
        });
        if (empty) {
          console.log("remain locked");
          return $("#saveEmbedded").prop("disabled", "disabled");
        } else {
          console.log("open");
          return $("#saveEmbedded").prop("disabled", false);
        }
      });
    };

    dashboardMedia.prototype.saveEmbed = function() {
      if (this.debug) {
        console.log("dashboardMedia.saveEmbed");
      }
      if ($.trim($("#mceEmbedArea").val())) {
        return $.ajax({
          url: this.base_url + "/post/addEmbedMedia",
          type: 'post',
          dataType: 'json',
          data: {
            title: $("#embedTitle").val(),
            stub: $("#embedStub").val(),
            visibleWhen: $("#embedWhen").val(),
            embed: $('#mceEmbedArea').val()
          },
          success: (function(_this) {
            return function(response) {
              if (response.success) {
                console.log("Success");
                _this.cleanAreas();
                _this.textBodyResponse("Item added to media database", "#userEmbedMessage", false, "#textArea-alert", "#saveEmbedded");
                $('#mceEmbedArea').val("");
                return $("#saveEmbedded").prop("disabled", false);
              } else if (response.debug) {
                console.log("debug");
                return $("#saveEmbedded").prop("disabled", false);
              } else if (response.error) {
                console.log("error");
                _this.textBodyResponse(response.error, true);
                return $("#saveEmbedded").prop("disabled", false);
              }
            };
          })(this)
        });
      } else {
        return this.textBodyResponse("You need to add embed from Youtube", "#userEmbedMessage", true, "#textArea-alert", "#saveEmbedded");
      }
    };

    return dashboardMedia;

  })(window.classes.dashboardIndex);

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.dashboardMedia = dashboardMedia;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.dashboardMedia = new dashboardMedia;
  });

}).call(this);
