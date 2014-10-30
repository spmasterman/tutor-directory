/**
 * EmailContact editable input.
 **/
(function ($) {
    "use strict";

    var EmailContact = function (options) {
        this.init('emailContact', options, EmailContact.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(EmailContact, $.fn.editabletypes.abstractinput);

    $.extend(EmailContact.prototype, {
        /**
         Renders input from tpl

         @method render()
         **/
        render: function() {
            this.$input = this.$tpl.find('input');
        },

        /**
         Default method to show value in element. Can be overwritten by display option.

         @method value2html(value, element)
         **/
        value2html: function(value, element) {
            if(!value || !value.address) {
                $(element).empty();
                return;
            }
            var html = $('<div>').text(value.address).html();

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
            this.$input.filter('[name="address"]').val(value.address);
        },

        /**
         Returns value of input.

         @method input2value()
         **/
        input2value: function() {
            return {
                type: this.$input.filter('[name="type"]').val(),
                address: this.$input.filter('[name="address"]').val()
            };
        },

        /**
         Activates input: sets focus on the first field.

         @method activate()
         **/
        activate: function() {
            this.$input.filter('[name="address"]').focus();
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

    EmailContact.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-email"><label><span>Type: </span></label><input type="text" name="type" class="form-control input-small"></div>'+
        '<div class="editable-email"><label><span>Email: </span></label><input type="email" name="address" class="form-control input-small"></div>',
        inputclass: ''
    });

    $.fn.editabletypes.emailContact = EmailContact;
}(window.jQuery));
