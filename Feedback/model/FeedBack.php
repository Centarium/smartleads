<?php
namespace BDProvider;

include_once __DIR__.'/../bundle/Config.php';

use PDO;
use Exception;
use Bundles\Config;

class FeedBack
{
    public function __construct()
    {
        $this->conn = $this->getConnection(Config::get('db:user'), Config::get('db:pass'));
    }

    /*
     * Без геттеров - сеттеров и прочей атрибутики вроде rules() для тестового
     */
    public function insertFeedback()
    {
        $email = $this->filter($_POST['feedback_email']);
        $user_name = $this->filter($_POST['feedback_name']);
        $feedback_text = $this->filter($_POST['feedback_text']);

        $query = $this->conn->prepare("
            INSERT INTO feedback(email,user_name,text)
            VALUES (:email, :user_name, :text  )
        ");

        $query->bindParam(':email', $email );
        $query->bindParam(':user_name', $user_name );
        $query->bindParam(':text', $feedback_text );

        $query->execute();
    }

    /*
     * Базовая защита от кавычек и от тегов
     */
    public function filter(string $value):string
    {
        $value = htmlspecialchars($value);
        return strip_tags($value);
    }

    public function migrateUp()
    {
        $query = $this->conn->query("
              SELECT table_catalog 
              FROM information_schema.tables 
              WHERE table_schema = 'public' AND table_name = 'feedback'"
        );
        $query->execute();

        $res = $query->fetch(\PDO::FETCH_ASSOC);

        if($res) return true;

        $this->conn->query(
            "CREATE TABLE feedback (
              message_id SERIAL NOT NULL, 
              email VARCHAR(50),
              user_name VARCHAR(100),
              text TEXT NOT NULL,
              timestamp TIMESTAMP DEFAULT current_timestamp, 
              PRIMARY KEY(message_id) )"
        );
    }

    public function migrateDown()
    {
        $this->conn->query("DROP TABLE feedback");
    }

    /**
     * @param string $user
     * @param string $pass
     * @return PDO
     */
    protected function getConnection(string $user, string $pass): PDO
    {
        $dbType = Config::get('db:dbtype');
        $host = Config::get('db:host');
        $dbname = Config::get('db:dbname');

        return new PDO("$dbType:host=$host;dbname=$dbname", $user, $pass);
    }
}