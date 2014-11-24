/**
 * Phone editable input.
 */
(function ($) {
    "use strict";

    var Phone = function (options) {
        this.sourceCountryData = options.sourceCountry;
        this.init('phone', options, Phone.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Phone, $.fn.editabletypes.abstractinput);

    $.extend(Phone.prototype, {
        /**
         * Renders input from tpl
         *
         * @method render()
         */
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
         * Default method to show value in element. Can be overwritten by display option.
         *
         * @method value2html(value, element)
         */
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return;
            }
            var html = $('<div>').text(value.number).html(),
                countryId = value.country,
                countryText = ''
            ;

            $.each(this.sourceCountryData, function (i, v) {
                if (v.value == countryId) {
                    var regex = /.*\((.*)\)/gi,
                        match = regex.exec(v.text);
                    countryText = match[1];
                }
            });

            if (countryText !== '') {
                html = $('<div>').text(countryText).html() + ' ' + html;
            }

            if (value.isPreferred) {
                html += $('<div>').text(' (preferred)').html() ;
            }

            $(element).html(html);
        },

        /**
         * Gets value from element's html
         *
         * @method html2value(html)
         */
        html2value: function(html) {
            return null;
        },

        /**
         * Converts value to string.
         * Used in internal comparison (not for server-side)
         *
         * @method value2str(value)
         */
        value2str: function(value) {
            var str = '';
            if(value) {
                for(var k in value) {
                    str = str + k + ':' + value[k] + ';';
                }
            }
            return str;
        },

        /**
         * Converts string to value. Used for reading value from 'data-value' attribute.
         *
         * @method str2value(str)
         */
        str2value: function(str) {
            return str;
        },

        /**
         * Sets value of input.
         *
         * @method value2input(value)
         * @param {mixed} value
         */
        value2input: function(value) {
            if(!value) {
                return;
            }
            this.$input.filter('[name="type"]').val(value.type);
            this.$input.filter('[name="number"]').val(value.number);
            this.$select.val(value.country);
            if (value.isPreferred) {
                this.$input.filter('[name="preferred"]').attr('checked', 'checked');
            }
        },

        /**
         * Returns value of input.
         *
         * @method input2value()
         */
        input2value: function() {
            return {
                type: this.$input.filter('[name="type"]').val(),
                number: this.$input.filter('[name="number"]').val(),
                country: this.$select.val(),
                isPreferred: this.$input.filter('[name="preferred"]').is(':checked')
            };
        },

        /**
         * Activates input: sets focus on the first field.
         *
         * @method activate()
         */
        activate: function() {
            this.$input.filter('[name="type"]').focus();
        },

        /**
         * Attaches handler to submit form in case of 'showbuttons=false' mode
         *
         * @method autosubmit()
         */
        autosubmit: function() {
            this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        }
    });

    Phone.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-address"><label><span>Type: </span></label><input type="text" name="type" class="form-control input-small" placeholder="Home, Mobile, Office, etc"></div>'+
        '<div class="editable-address"><label><span>Country: </span></label><select name="country" class="form-control input-small"></select></div>'+
        '<div class="editable-address"><label><span>Number: </span></label><input type="tel" name="number" class="form-control input-small"></div>'+
        '<div class="editable-address"><label><span>Preferred: </span></label><input type="checkbox" name="preferred" ></div>'
        ,
        inputclass: '',
        sourceCountry: []
    });

    $.fn.editabletypes.phone = Phone;
}(window.jQuery));
