<?php
require 'vendor/autoload.php';

use \Mailjet\Resources;

// namespace App;

class Authentication
{

    private $key1 = "67c4d4d645f67424a560843d6e441254";
    private $key2 = "2dc3f909943025d3323595ede0d24949";
    private $mj;


    public function __construct()
    {
        // Use your saved credentials, specify that you are using Send API v3.1
        $this->mj = new \Mailjet\Client($this->key1, $this->key2, true, ['version' => 'v3.1']);
    }

    public function displayVerifyPage(){
        require_once "views/verifyPage.php";
    }

    function sendEmail($u_email, $subject, $html)
    {
        // Define your request body
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "omenkumar2208@gmail.com",
                        'Name' => "Sahil Verma"
                    ],
                    'To' => [
                        [
                            'Email' => $u_email,
                            'Name' => "user"
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "Greetings from Mailjet!",
                    'HTMLPart' => $html,
                    'CustomID' => "AppGettingStartedTest"
                ]
            ]
        ];

        // All resources are located in the Resources class
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        // Read the response
        // $response->success() && var_dump($response->getData());

        if ($response->success()) {
            return "Success";
        } else {
            return "Failure";
        }
    }

    public function verifyMail()
    {
        $token = $_GET['token'];
        var_dump($token);

        $obj = new \App\Database();

        $sql = <<<EOF
            SELECT * FROM users WHERE token='{$token}' and u_id={$_SESSION['u_id']};
        EOF;

        $data = $obj->postgres_query_row($sql);
        var_dump($data);
        if (is_array($data) && $data != false) {
            $sql = <<<EOF
            UPDATE users set verifiedUser=true where u_id={$_SESSION['u_id']};
            EOF;

            $response = $obj->postgres_query($sql);
            if ($response === "Success") {
                $_SESSION['isVerified']=true;
                var_dump($_SESSION['isVerified']);
                $page = "/";
                $sec = "0.1";
                header("Refresh: $sec; url=$page");
                exit;
            }
        }
    }
}
