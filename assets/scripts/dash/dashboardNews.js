// Generated by CoffeeScript 1.10.0
(function() {
  var dashboardNews,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    hasProp = {}.hasOwnProperty;

  dashboardNews = (function(superClass) {
    extend(dashboardNews, superClass);

    dashboardNews.prototype.debug = true;

    function dashboardNews() {
      this.saveNews = bind(this.saveNews, this);
      this.setupEvents = bind(this.setupEvents, this);
      if (this.debug) {
        console.log("dashboardIndex.constructor");
      }
      dashboardNews.__super__.constructor.call(this);
      this.setupEvents();
    }

    dashboardNews.prototype.setupEvents = function() {
      if (this.debug) {
        console.log("dashboardIndex.setupEvents");
      }
      $("#mceNewsArea").tinymce({
        script_url: this.base_url + '/assets/scripts/tinymce/tinymce.min.js',
        theme: "modern",
        plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
      });
      $("#saveNewArticle").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveNewArticle").prop("disabled", "disabled");
          return _this.saveNews();
        };
      })(this));
      $("#saveWrittenArticle").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveNewArticle").prop("disabled", "disabled");
          $("#saveWrittenNews").prop("disabled", "disabled");
          $("#saveWrittenArticle").prop("disabled", "disabled");
          return _this.saveNews("articles");
        };
      })(this));
      $("#saveWrittenNews").unbind().bind("click", (function(_this) {
        return function(event) {
          $("#saveNewArticle").prop("disabled", "disabled");
          $("#saveWrittenNews").prop("disabled", "disabled");
          $("#saveWrittenArticle").prop("disabled", "disabled");
          return _this.saveNews("news");
        };
      })(this));
      $("#clearArticle").unbind().bind("click", (function(_this) {
        return function(event) {
          return _this.cleanAreas();
        };
      })(this));
      return $('body').keyup(function() {
        var empty;
        empty = false;
        $('.textReq').each(function() {
          if ($(this).val() === '') {
            return empty = true;
          }
        });
        if (empty) {
          console.log("remain locked");
          $("#saveNewArticle").prop("disabled", "disabled");
          $("#saveWrittenNews").prop("disabled", "disabled");
          return $("#saveWrittenArticle").prop("disabled", "disabled");
        } else {
          console.log("open");
          $("#saveNewArticle").prop("disabled", false);
          $("#saveWrittenNews").prop("disabled", false);
          return $("#saveWrittenArticle").prop("disabled", false);
        }
      });
    };

    dashboardNews.prototype.saveNews = function(type) {
      if (type == null) {
        type = "news";
      }
      if (this.debug) {
        console.log("dashboardIndex.saveNews");
      }
      if ($.trim($("#mceNewsArea").html())) {
        return $.ajax({
          url: this.base_url + "/post/addNews",
          type: 'post',
          dataType: 'json',
          data: {
            title: $("#articleTitle").val(),
            stub: $("#articleStub").val(),
            visibleWhen: $("#articleWhen").val(),
            bodyText: $('#mceNewsArea').html(),
            section: $("#section").val(),
            exFlag: $("#exclusiveFlag").val(),
            type: type
          },
          success: (function(_this) {
            return function(response) {
              if (response.success) {
                console.log("Success");
                _this.cleanAreas();
                _this.textBodyResponse("Item added to news database", "#userMessage", false, "#textArea-alert", "#saveNewArticle");
                return $("#saveNewArticle").prop("disabled", false);
              } else if (response.debug) {
                console.log("debug");
                return $("#saveNewArticle").prop("disabled", false);
              } else if (response.error) {
                console.log("error");
                _this.textBodyResponse(response.error, "#userMessage", true, "#textArea-alert", "#saveNewArticle");
                return $("#saveNewArticle").prop("disabled", false);
              }
            };
          })(this)
        });
      } else {
        return this.textBodyResponse("You need to add something to the body of the article!", "#userMessage", true, "#textArea-alert", "#saveNewArticle");
      }
    };

    return dashboardNews;

  })(window.classes.dashboardIndex);

  if (window.classes == null) {
    window.classes = {};
  }

  window.classes.dashboardNews = dashboardNews;

  if (window.objs == null) {
    window.objs = {};
  }

  $(document).ready(function() {
    return window.objs.dashboardNews = new dashboardNews;
  });

}).call(this);
