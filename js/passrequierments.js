if (typeof jQuery === 'undefined')
{
    throw new Error('PassRequirements requires jQuery')
}

+(function ($) 
{
    $.fn.PassRequirements = function (options) 
    {
        var defaults = 
        {
//                  defaults: true
        };
    if (
            !options || //if no options are passed                                  /*
            options.defaults == true || //if default option is passed with defaults set to true      * Extend options with default ones
            options.defaults == undefined   //if options are passed but defaults is not passed           */
        ) 
            {
                if (!options) 
                {                   //if no options are passed, 
                    options = {};               //create an options object
                }
                defaults.rules = $.extend(
                    {
                        minlength: 
                            {
                                text: "Minstens minLength karakters",
                                 minLength: 8
                            },
                        containSpecialChars: 
                            {
                                text: "Uw invoer dient minimaal minLength speciaal teken te bevaten",
                                minLength: 1,
                                regex: new RegExp('([^!,%,&,@,#,$,^,*,?,_,~])', 'g')
                            },
                        containLowercase: 
                            {
                                text: "Uw invoer dient minimaal minLength kleine letter",
                                 minLength: 1,
                                 regex: new RegExp('[^a-z]', 'g')
                            },
                        containUppercase: 
                            {
                                text: "Uw invoer dient minimaal minLength hoofdletter",
                                minLength: 1,
                                regex: new RegExp('[^A-Z]', 'g')
                            },
                        containNumbers: 
                            {
                                text: "Uw invoer dient minimaal minLength getal",
                                minLength: 1,
                                regex: new RegExp('[^0-9]', 'g')
                            }
                    }, options.rules);
            }
            else 
            {
                defaults = options;     //if options are passed with defaults === false
            }



        var i = 0;

        return this.each(function () 
        {
            if (!defaults.defaults &&!defaults.rules) 
            {
                console.error('You must pass in your rules if defaults is set to false. Skipping this input with id:[' + this.id + '] with class:[' + this.classList + ']');
                return false;
            }

            var requirementList = "";
            $(this).data('pass-req-id', i++);

            $(this).keyup(function () 
            {
                var this_ = $(this);
                varObject.getOwnPropertyNames(defaults.rules).forEach(function (val, idx, array) 
                    {
                        if (this_.val().replace(defaults.rules[val].regex, "").length > defaults.rules[val].minLength - 1) 
                        {
                            $('#' + val).css('text-decoration', 'line-through');
                        } 
                        else 
                        {
                            $('#' + val).css('text-decoration', 'none');
                        }
                    })

                try
                {
                    Object.getOwnPropertyNames(defaults.rules).forEach(function (val, idx, array) 
                    {
                        if (this_.val().replace(defaults.rules[val].regex, "").length > defaults.rules[val].minLength - 1)
                        {
                        $(this_).data('password-valid', true);
                        } 
                        else 
                        {
                        $(this_).data('password-valid', false);
                        throw BreakException;
                        }
                    })
                }
                catch (e)
                {

                }
            });

            Object.getOwnPropertyNames(defaults.rules).forEach(function (val, idx, array) 
            {
                requirementList += (("<li id='" + val + "'>" + defaults.rules[val].text).replace("minLength", defaults.rules[val].minLength));
            })
            try 
            {
                $(this).popover({
                title: 'Wachtwoord eisen',
                trigger: options.trigger ? options.trigger : 'focus',
                html: true,
                placement: options.popoverPlacement ? options.popoverPlacement : 'right',
                content: 'Uw wachtwoord moet:<ul>' + requirementList + '</ul>'
                //                        '<p>The confirm field is actived only if all criteria are met</p>'
                });
            }
            catch (e) 
            {
                throw new Error('PassRequirements requires Bootstraps Popover plugin');
            }
            $(this).focus(function () 
            {
                $(this).keyup();
            });
        });
    };

}(jQuery));