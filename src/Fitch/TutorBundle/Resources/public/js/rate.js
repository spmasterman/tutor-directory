/**
 * Rate editable input.
 */
(function ($) {
    "use strict";

    var Rate = function (options) {
        this.init('rate', options, Rate.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Rate, $.fn.editabletypes.abstractinput);

    $.extend(Rate.prototype, {
        /**
         * Renders input from tpl
         *
         * @method render()
         */
        render: function() {
            this.$input = this.$tpl.find('input');
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
            var html = $('<div>').text(value.amount).html();

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
         * It is used in internal comparison (not for sending to server)
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
            this.$input.filter('[name="name"]').val(value.name);
            this.$input.filter('[name="amount"]').val(value.amount);

        },

        /**
         * Returns value of input.
         *
         * @method input2value()
         */
        input2value: function() {
            return {
                type: this.$input.filter('[name="name"]').val(),
                streetPrimary: this.$input.filter('[name="amount"]').val()
            };
        },

        /**
         * Activates input: sets focus on the first field.
         * @method activate()
         **/
        activate: function() {
            this.$input.filter('[name="name"]').focus();
        },

        /**
         * attaches handler to submit form in case of 'showbuttons=false' mode
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

    Rate.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-rate"><label><span>Name/type: </span></label><input type="text" name="name" class="form-control input-small" placeholder="Day, Evening, etc"></div>'+
        '<div class="editable-rate"><label><span>Amount: </span></label><input type="text" name="amount" class="form-control input-small"></div>',
        inputclass: ''
    });

    $.fn.editabletypes.rate = Rate;
}(window.jQuery));
