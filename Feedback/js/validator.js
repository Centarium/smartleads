class Validator
{
    constructor(form)
    {
        this.message_container = '.error_message';
        this.field_container = '.field_container';

        this.state = {
            error : {
                mail:{message:'Email not valid',elems:[]},
                required:{message:'Field is required',elems:[]}
            }
        };

        this.validateEmail(form+" input[type='email']", this.state.error.mail.elems);
        this.validateRequired(form+" input[required]", this.state.error.required.elems);
        this.validateRequired(form+" textarea[required]", this.state.error.required.elems);
    }

    /**
     *   this.state.error хранит в себе текущее состояние валидации по элементам.
     *   @param {jQuery} elem
     *   @param {object} error_storage
     */
    initialErrorStorage(elem, storage)
    {
        $(elem).each( (index, target) => {
            let name = $(target).attr('name');
            storage.push(name);
        });
    }


    validateEmail(elems, errorStorage)
    {
        this.initialErrorStorage(elems, errorStorage);

        $(document).on('input',elems,{},() =>
        {
            let target = $(event.target);
            let value = target.val();
            //let RegExp = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            let RegExp = /^[a-zA-Z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-_]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;

            if( !RegExp.test( value ))
            {
                this.error( target, errorStorage);
            }
            else
            {
                this.success(target, errorStorage);
            }

            this.checkErrors(target);
        })
    }


    validateRequired(elems, errorStorage)
    {
        this.initialErrorStorage(elems, errorStorage);

        $(document).on('input',elems,{},() =>
        {
            let target = $(event.target);
            let value = target.val();

            if( value === '' )
            {
                this.error( target, errorStorage);
            }
            else
            {
                this.success(target, errorStorage);
            }

            this.checkErrors(target);
        })
    }

    /**
     * @param {jQuery} elem
     * @param {object} error_storage
     */
    error(elem, error_storage)
    {
        let name = elem.attr('name'),
            errorindex = error_storage.indexOf(name);


        if( errorindex === -1)
        {
            error_storage.push(name);
        }
    }

    /**
     *
     * @param {jQuery} elem
     * @param {object} error_storage
     */
    success(elem, error_storage)
    {
        let name = elem.attr('name'),
            errorindex = error_storage.indexOf(name);

        if( errorindex !== -1)
        {
            error_storage.splice(errorindex, 1);
        }
    }

    checkErrors(elem)
    {
        let field_container = elem.parent(this.field_container),
            error_message = elem.prevAll(this.message_container),
            ErrorMessage = '',
            name = elem.attr('name');

        for (let i in this.state.error)
        {
            if( this.state.error[i].elems.indexOf(name) !== -1 )
            {
                ErrorMessage += this.state.error[i].message + "<br/>";
            }
        }

        if(ErrorMessage !== '')
        {
            field_container.addClass('has-error has-feedback');

            error_message.html(ErrorMessage);
            error_message.show();
        }
        else {
            field_container.removeClass('has-error has-feedback');
            field_container.addClass('has-success');

            error_message.hide().text('');
        }
    }

    hasErrors()
    {
        for (let i in this.state.error)
        {
            if( this.state.error[i].elems.length > 0 ) return true;
        }
        return false;
    }
}
