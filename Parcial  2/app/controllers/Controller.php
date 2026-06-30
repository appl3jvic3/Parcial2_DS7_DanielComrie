<?php
abstract class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        require_once __DIR__ . "/../views/{$view}.php";
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    protected function setMessage($message, $type = 'success')
    {
        $_SESSION['message'] = ['text' => $message, 'type' => $type];
    }

    protected function getMessage()
    {
        if (isset($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            unset($_SESSION['message']);
            return $msg;
        }
        return null;
    }

    protected function setOld($data)
    {
        $_SESSION['old'] = $data;
    }

    protected function getOld()
    {
        if (isset($_SESSION['old'])) {
            $old = $_SESSION['old'];
            unset($_SESSION['old']);
            return $old;
        }
        return null;
    }
}
