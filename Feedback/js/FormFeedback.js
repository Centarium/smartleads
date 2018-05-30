var FormFeedback = {

    form : '#feedback_form',
    validator : {},

    send : function (button, form) {

        $(document).on('click',button,{},()=>
        {
            if( !this.validator.hasErrors() )
            {
                $(form).submit();
            }
        })
    },

    init : function () {

        this.validator = new Validator('#feedback_form');

        this.send( '#feedback_send', '#feedback_form' );
    }
}

FormFeedback.init();