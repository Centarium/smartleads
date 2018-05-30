<?php
namespace Controllers;

include __DIR__.'/../model/FeedBack.php';

use BDProvider\FeedBack;

/**
 * Все как обычно - валидация на клиенте и сервере,
 * общение с базой через модель, параметризация параметров(Плейсхолдеры),
 * фильтрация содержимого $_POST против SQL и XSS,
 * CSRF уже делать не стал,морочиться с токенами только для тестового,
 * как и "правильной" валидации да беке,
 * как и перехвата ошибок и т.д.
 * Class FeedbackController
 * @package Controllers
 */
class FeedbackController
{
    private $errors=[];

    public function getErrors():array
    {
        return $this->errors;
    }

    public function setErrors(array $errors):void
    {
        $this->errors = $errors;
    }

    /**
     * Вообще, проверка полей и errors должны быть в модели, но если все делать "по уму",
     * будет достаточно долго, не вижу смысла для тестового
     */
    public function index()
    {
        if( count($_POST) < 1 ) return;

        $errors = $this->getErrors();
        $required_fields = ['feedback_name','feedback_email','feedback_text'];



        foreach ($required_fields as $field)
        {
            if( !isset($_POST[$field]) || $_POST[$field] === ''  )
            {
                $errors[$field] = 'Field is required';
            }
        }



        if( isset($_POST['feedback_email']) )
        {
            $match = preg_match('/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-_]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',$_POST['feedback_email']);

            if($match ===0 || $match === false)
            {
                if(isset($errors['feedback_email']))
                {
                    $errors['feedback_email'] .= "\n Email invalid";
                }
                else
                {
                    $errors['feedback_email'] = "Email invalid";
                }
            }
        }



        $this->setErrors($errors);

        if(count($errors) ===0)
        {
            $model = new FeedBack();
            $model->insertFeedback();
            header('Location: /');
        }
    }

    /**
     * @param string $field
     * @return string
     */
    public function getFieldError(string $field):string
    {
        $errors = $this->getErrors();
        return $errors[$field] ?? '';
    }

}