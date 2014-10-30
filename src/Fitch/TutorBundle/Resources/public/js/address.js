/**
 * Address editable input.
 **/
(function ($) {
    "use strict";

    var Address = function (options) {
        this.sourceCountryData = options.sourceCountry;
        this.init('address', options, Address.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Address, $.fn.editabletypes.abstractinput);

    $.extend(Address.prototype, {
        /**
         Renders input from tpl

         @method render()
         **/
        render: function() {
            this.$input = this.$tpl.find('input');
            this.$select = this.$tpl.find('select');

            this.$select.empty();

            var fillItems = function ($el, data) {
                if ($.isArray(data)) {
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].children) {
                            $el.append(fillItems($('<optgroup>', {
                                label: data[i].text
                            }), data[i].children));
                        } else {
                            $el.append($('<option>', {
                                value: data[i].value
                            }).text(data[i].text));
                        }
                    }
                }
                return $el;
            };

            fillItems(this.$select, this.sourceCountryData);
        },

        /**
         Default method to show value in element. Can be overwritten by display option.

         @method value2html(value, element)
         **/
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return;
            }
            var html =
                    (value.streetPrimary ? $('<div>').text(value.streetPrimary).html() + ', ' : '') +
                    (value.streetSecondary ? $('<div>').text(value.streetSecondary).html() + ', ' : '') +
                    (value.city ? $('<div>').text(value.city).html() + ', ' : '') +
                    (value.state ? $('<div>').text(value.state).html() + ' ' : '') +
                    (value.zip ? $('<div>').text(value.zip).html() : ''),
                countryId = value.country,
                countryText = ''
            ;

            $.each(this.sourceCountryData, function (i, v) {
                if (v.value == countryId) {
                    countryText = v.text;
                }
            });

            if (countryText !== '') {
                html += ' ' + $('<div>').text(countryText).html();
            }

            $(element).html(html);
        },

        /**
         Gets value from element's html

         @method html2value(html)
         **/
        html2value: function(html) {
            return null;
        },

        /**
         Converts value to string.
         It is used in internal comparing (not for sending to server).

         @method value2str(value)
         **/
        value2str: function(value) {
            var str = '';
            if(value) {
                for(var k in value) {
                    str = str + k + ':' + value[k] + ';';
                }
            }
            return str;
        },

        /*
         Converts string to value. Used for reading value from 'data-value' attribute.

         @method str2value(str)
         */
        str2value: function(str) {
            /*
             this is mainly for parsing value defined in data-value attribute.
             If you will always set value by javascript, no need to overwrite it
             */
            return str;
        },

        /**
         Sets value of input.

         @method value2input(value)
         @param {mixed} value
         **/
        value2input: function(value) {
            if(!value) {
                return;
            }
            this.$input.filter('[name="type"]').val(value.type);
            this.$input.filter('[name="streetPrimary"]').val(value.streetPrimary);
            this.$input.filter('[name="streetSecondary"]').val(value.streetSecondary);
            this.$input.filter('[name="city"]').val(value.city);
            this.$input.filter('[name="state"]').val(value.state);
            this.$input.filter('[name="zip"]').val(value.zip);
            this.$select.val(value.country);
        },

        /**
         Returns value of input.

         @method input2value()
         **/
        input2value: function() {
            return {
                type: this.$input.filter('[name="type"]').val(),
                streetPrimary: this.$input.filter('[name="streetPrimary"]').val(),
                streetSecondary: this.$input.filter('[name="streetSecondary"]').val(),
                city: this.$input.filter('[name="city"]').val(),
                state: this.$input.filter('[name="state"]').val(),
                zip: this.$input.filter('[name="zip"]').val(),
                country: this.$select.val()
            };
        },

        /**
         Activates input: sets focus on the first field.

         @method activate()
         **/
        activate: function() {
            this.$input.filter('[name="type"]').focus();
        },

        /**
         Attaches handler to submit form in case of 'showbuttons=false' mode

         @method autosubmit()
         **/
        autosubmit: function() {
            this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        }
    });

    Address.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-address"><label><span>Type: </span></label><input type="text" name="type" class="form-control input-small"></div>'+
        '<div class="editable-address"><label><span>Street: </span></label><input type="text" name="streetPrimary" class="form-control input-small"></div>'+
        '<div class="editable-address"><label ><span>&nbsp;</span></label><input type="text" name="streetSecondary" class="form-control input-small"></div>'+
        '<div class="editable-address"><label><span>City: </span></label><input type="text" name="city" class="form-control input-small"></div>'+
        '<div class="editable-address"><label><span>State: </span></label><input type="text" name="state" class="form-control input-small"></div>'+
        '<div class="editable-address"><label><span>Zip: </span></label><input type="text" name="zip" class="form-control input-small"></div>'+
        '<div class="editable-address"><label><span>Country: </span></label><select name="country" class="form-control input-small"></select></div>',
        inputclass: '',
        sourceCountry: []
    });

    $.fn.editabletypes.address = Address;
}(window.jQuery));
