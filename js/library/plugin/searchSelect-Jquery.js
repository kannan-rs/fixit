(function( $ ) {
  $.widget( "custom.combobox", {
    _create: function() {
      this.wrapper = $( "<span>" )
        .addClass( "custom-combobox" )
        .insertAfter( this.element );

      this.element.hide();

      if($(this.element[0]).attr("hidden")) {
        $(this.wrapper).hide();
      }
      this._createAutocomplete();
      this._createShowAllButton();
      if( this.element[0].id.indexOf("city") >= 0 ) {
        var id_prefix = this.element[0].id.indexOf("property_") == 0 ? "property_" : "";
        this._addCityEventsToInput( id_prefix );
        var cityDbVal = $("#"+id_prefix+"cityDbVal").val();
        $( this.input ).attr("placeholder", "Enter first 3 letter of the city").val(cityDbVal);
        $("#"+id_prefix+"city").val(cityDbVal);
        this._createError({id : id_prefix+"cityError", "text" : ""});
      }
    },

    _createAutocomplete: function() {
      var selected = this.element.children( ":selected" ),
        value = selected.val() ? selected.text() : "";

      this.input = $( "<input>" )
        .appendTo( this.wrapper )
        .val( value )
        .attr( "title", "" )
        .attr( "id", this.element[0].id+"_jqDD" )
        .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
        .autocomplete({
          delay: 0,
          minLength: 0,
          source: $.proxy( this, "_source" )
        })
        .tooltip({
          tooltipClass: "ui-state-highlight"
        });

      this._on( this.input, {
        autocompleteselect: function( event, ui ) {
          ui.item.option.selected = true;
          this._trigger( "select", event, {
            item: ui.item.option
          });
        },

        autocompletechange: "_removeIfInvalid"
      });
    },

    _createShowAllButton: function() {
      var input = this.input,
        wasOpen = false;

      this.a = $( "<a>" )
        .attr( "tabIndex", -1 )
        .attr( "title", "Show All Items" )
        .tooltip()
        .appendTo( this.wrapper )
        .button({
          icons: {
            primary: "ui-icon-triangle-1-s"
          },
          text: false
        })
        .removeClass( "ui-corner-all" )
        .addClass( "custom-combobox-toggle ui-corner-right" )
        .mousedown(function() {
          wasOpen = input.autocomplete( "widget" ).is( ":visible" );
        })
        .click(function() {
          input.focus();

          // Close if already visible
          if ( wasOpen ) {
            return;
          }

          // Pass empty string as value to search for, displaying all results
          input.autocomplete( "search", "" );
        });

        var height = $(this.a).prev().outerHeight() - $(this.a).css("border-width").substr(0,1) * 2;
        $(this.a).css({'height' : height+"px"});
    },
    _createError: function( options ) {
      $( "<div>" ).css({"clear" : "both"})
        .appendTo( this.wrapper);
      $("<div>").attr("id", options.id).addClass(options.class).text(options.text).css({"display": "none"}).appendTo(this.wrapper);
    },
    _addCityEventsToInput: function() {
        $( this.input ).on({
          keyup : function() { 
              var id_prefix = $(this).attr('id').indexOf("property") == 0 ? "property_" : "";
              $(this).removeClass("form-error").next().removeClass("form-error");
              $("#"+id_prefix+"cityError").css({"display" : "none"}).removeClass("form-error");
              _utils.getAndSetMatchCity(this.value,'', $(this).attr('id'));
          },
          blur : function() {
            
            var id_prefix = $(this).attr('id').indexOf("property") == 0 ? "property_" : "";

            if(this.value != $("#"+id_prefix+"city").val()) {
                $("#"+id_prefix+"city").val("");
            } else {
              $(this).removeClass("form-error").next().removeClass("form-error");
              $("#cityError").css({"display" : "none"}).removeClass("form-error");
              _utils.setAddressByCity($(this).attr('id'));
            }
          }
        })
    },

    _source: function( request, response ) {
      var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
      response( this.element.children( "option" ).map(function() {
        var text = $( this ).text();
        if ( this.value && ( !request.term || matcher.test(text) ) )
          return {
            label: text,
            value: text,
            option: this
          };
      }) );
    },

    _removeIfInvalid: function( event, ui ) {

      // Selected an item, nothing to do
      if ( ui.item ) {
        return;
      }

      // Search for a match (case-insensitive)
      var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
      this.element.children( "option" ).each(function() {
        if ( $( this ).text().toLowerCase() === valueLowerCase ) {
          this.selected = valid = true;
          return false;
        }
      });

      // Found a match, nothing to do
      if ( valid ) {
        return;
      }

      // Remove invalid value
      this.input
        .val( "" )
        .attr( "title", value + " didn't match any item" )
        .tooltip( "open" );
      this.element.val( "" );
      this._delay(function() {
        this.input.tooltip( "close" ).attr( "title", "" );
      }, 2500 );
      this.input.autocomplete( "instance" ).term = "";
    },

    _destroy: function() {
      this.wrapper.remove();
      this.element.show();
    }
  });
})( jQuery );