<?php
namespace Symplicity\PublicApiClient;

class ApiException extends \Exception
{
    private $errors;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        if ($this->isHtml($message)) {
            $message = substr($message, strpos($message, '<title>') + strlen('<title>'));
            $error = substr($message, 0, strpos($message, '</title>'));
        } else {
            $errors = json_decode($message, true);
            if (isset($errors['errors'])) {
                $error = 'Write Error';
                $this->errors = $errors['errors'];
            } else {
                $error = $this->deQuote($errors);
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
     * If (and only if) the entire $in parameter is enclosed in single or double quotes, strip them
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
