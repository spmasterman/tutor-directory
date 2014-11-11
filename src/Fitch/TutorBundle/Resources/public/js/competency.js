/**
 * Competency editable input.
 */
(function ($) {
    "use strict";

    var Competency = function (options) {
        this.sourceCompetencyTypes = options.sourceCompetencyTypes,
        this.sourceCompetencyLevels = options.sourceCompetencyLevels,
        this.init('competency', options, Competency.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Competency, $.fn.editabletypes.abstractinput);

    $.extend(Competency.prototype, {
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

            fillItems(this.$select.filter('[name="type"]'), this.sourceCompetencyTypes);
            fillItems(this.$select.filter('[name="level"]'), this.sourceCompetencyLevels);
        },

        /**
         * Default method to show value in element. Can be overwritten by display option.
         *
         * @method value2html(value, element)
         */
        value2html: function(value, element) {
            if(!value || value.type == 0 ) {
                $(element).empty();
                return;
            }
            var typeId = value.type,
                typeText = ''
            ;

            $.each(this.sourceCompetencyTypes, function (i, v) {
                if (v.value == typeId) {
                    typeText = v.text;
                }
            });

            if (typeText !== '') {
                $(element).html($('<div>').text(typeText).html());
            } else {
                $(element).html('not set');
            }
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
            this.$input.filter('[name="note"]').val(value.note);
            this.$select.filter('[name="type"]').val(value.type);
            this.$select.filter('[name="level"]').val(value.level);
        },

        /**
         * Returns value of input.
         *
         * @method input2value()
         */
        input2value: function() {
            return {
                note: this.$input.filter('[name="note"]').val(),
                type: this.$select.filter('[name="type"]').val(),
                level: this.$select.filter('[name="level"]').val()
            };
        },

        /**
         * Activates input: sets focus on the first field.
         * @method activate()
         **/
        activate: function() {
            this.$select.filter('[name="type"]').focus();
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

    Competency.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-competency"><label><span>Competency: </span></label><select name="type" class="form-control input-small"></select></div>' +
        '<div class="editable-competency"><label><span>Level: </span></label><select name="level" class="form-control input-small"></select></div>' +
        '<div class="editable-competency"><label><span>Notes: </span></label><input type="text" name="note" class="form-control input-small" placeholder="Additional notes here"></div>'
        ,
        inputclass: '',
        sourceCompetencyTypes: [],
        sourceCompetencyLevels: []
    });

    $.fn.editabletypes.competency = Competency;
}(window.jQuery));
