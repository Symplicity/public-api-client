<?php
namespace Symplicity\PublicApiClient;

class ApiException extends \Exception
{
    private $errors;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        if ($this->isHtml($message)) {
            // When our server results in some very general 'on-screen' error
            $message = substr($message, strpos($message, '<title>') + strlen('<title>'));
            $error = substr($message, 0, strpos($message, '</title>'));
        } else {
            $errors = json_decode($message, true);
            if ($errors) {
                if (isset($errors['errors'])) {
                    // Legitimate API write errors caused by validations, etc.
                    $error = 'Write Error';
                    $this->errors = $errors['errors'];
                } elseif (
                    // app Controller::outputError/outputMessage
                    isset($errors['messages']) &&
                    isset($errors['messages'][0]) &&
                    isset($errors['messages'][0]['type']) &&
                    $errors['messages'][0]['type'] == 'error'
                ) {
                    $error = $errors['messages'][0]['text'];
                } else {
                    $error = $this->deQuote($errors);
                }
            } else {
                $error = $this->deQuote($message);
            }
        }

        parent::__construct($error, $code, $previous);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function showErrors()
    {
        $log = 'Status ' . $this->getCode() . ': ' . $this->getMessage();
        echo "$log\n";
        $errors = $this->getErrors();
        if ($errors) {
            print_r($errors);
        }
    }

    /**
     * If (and only if) the entire $string is enclosed in single or double quotes, strip them
     **/
    private function deQuote($string)
    {
        $first = substr($string, 0, 1);
        $last = substr($string, -1, 1);
        if ($first == $last && ($first == '"' || $first == "'")) {
            $string = substr($string, 1, strlen($string) - 2);
        }
        return $string;
    }

    private function isHtml($string)
    {
        return ($string != strip_tags($string));
    }
}
