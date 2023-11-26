(function ($) {

    $.intercode = function (element, options) {

        var defaults = {
            country: '',
            code: '',
            flag: '',
            apiToken: null,
            selectClass: null,
            intercodeFile: '',
            showFlag: false,
            default: null,
            lang: '',
            disabled: false,
            orginalCountryCode: false
        }
        var plugin = this;

        var $element = $(element),
            element = element;

        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);

            var originalFieldName = $(element).attr('name');
            var number = '';
            var selectClass = "countrycode form-control countrycode_" + originalFieldName + " ";

            var codeselect = '<span class="select"><select name="countrycode_' + originalFieldName + '"';

            if (plugin.settings.disabled) {
                codeselect += ' disabled="disabled"';
            }

            if (plugin.settings.selectClass) {

                selectClass += plugin.settings.selectClass;
            }
            codeselect += ' class="' + selectClass + '" >';

            // Get Country if the We don't have a Default
            if (!plugin.settings.country) {

                var APIurl = "https://ipinfo.io";

                // Add token for paid versions
                if (plugin.settings.apiToken) {
                    APIurl += '?token=' + plugin.settings.apiToken;
                }

                $.get(APIurl, function (response) {
                    plugin.settings.country = response.country;
                }, "jsonp")

            }
            $.getJSON(plugin.settings.intercodeFile, function (json) {
                var selected = false;
                $.each(json, function (index) {

                    if (json[index].cca3 == plugin.settings.country) {
                        selected = true;
                        var orginalCountryCode = json[index].callingCode[0];
                    } else {
                        selected = false;
                    }

                    if (json[index].callingCode[0]) {
                        codeselect += '<option  value="+' + json[index].callingCode[0] + '"';

                        codeselect += 'data-country="' + json[index].cca3 + '"';

                        if (selected) {
                            codeselect += ' selected="selected"';
                        }
                        if (plugin.settings.showFlag) {
                            codeselect += '>' + json[index].flag
                        } else {
                            codeselect += '>';
                        }
                        if (plugin.settings.lang == 'en') {
                            var country = json[index].name.common
                        } else {
                            var country = json[index].translations[plugin.settings.lang].common
                        }

                        codeselect += ' ' + country + ' (+' + json[index].callingCode[0] + ')</option>';
                    }
                });

                codeselect += '</select></span>';


                if ((plugin.settings.default).length) {
                    number = plugin.settings.code + '' + plugin.settings.default;
                }
                if ((plugin.settings.orginalCountryCode).length) {
                    number = plugin.settings.orginalCountryCode + '' + plugin.settings.default;
                }

                var hiddenPhoneField = '<input name="' + originalFieldName + '" type="hidden" class="original_phone_field" value="' + number + '" />';

                var countryCode = '<input name="country_code_' + originalFieldName + '" type="hidden" value="' + plugin.settings.country + '" />';

                $(element)
                    .before(codeselect)
                    .val(plugin.settings.default)
                    .addClass('phonefield')
                    .addClass('phonefield_' + originalFieldName)
                    .attr('name', 'phonefield_' + originalFieldName)
                    .after(hiddenPhoneField)
                    .after(countryCode);
                var code = plugin.settings.code;
                var phone = $('input[name="phonefield_' + originalFieldName + '"]').val();

                $('input[name="phonefield_' + originalFieldName + '"]').change(function () {
                    var code = $('select[name="countrycode_' + originalFieldName + '"]').val();

                    phone = $(this).val();
                    plugin.updatePhoneField(code, phone, originalFieldName);
                });
                $('select[name="countrycode_' + originalFieldName + '"]').change(function () {

                    code = $(this).val();
                    var countryCode = $('select[name="countrycode_' + originalFieldName + '"] option:selected').data('country');

                    $('input[name="country_code_' + originalFieldName + '"]').val(countryCode);

                    plugin.updatePhoneField(code, phone, originalFieldName);
                });
            });


        }

        plugin.updatePhoneField = function (code, phone, originalFieldName) {


            if ((plugin.settings.default).length && code + "" + phone == plugin.settings.default) {

                var number = (plugin.settings.default).replace(plugin.settings.code, '')

                $('input[name="phonefield_' + originalFieldName + ']').val(number);

            } else {

                $('input[name="' + originalFieldName + '"]').val(code + "" + phone);
            }

        }

        plugin.init();
    }

    $.fn.intercode = function (options) {

        return this.each(function () {

            if (undefined == $(this).data('intercode')) {

                var plugin = new $.intercode(this, options);
                $(this).data('intercode', plugin);

            }

        });

    }
})(jQuery);
