<?php

namespace Src;

use App\Model\Task;
use Src\Controller\AbstractController;
use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class Controller extends AbstractController
{
    public function view($template, $params = [])
    {
        $loader = new FilesystemLoader(ROOT_FOLDER . '\app\views');
        $twig = new Environment($loader);
        $twig->addGlobal('_get', $_GET);
        echo $twig->render($template, $params);
    }
}