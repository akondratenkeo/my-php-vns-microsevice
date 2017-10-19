<?php

class IndexController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index action
     */
    public function index()
    {
        require Config::get('PATH_CONTROLLER') . 'ErrorController.php';

        $ErrorController = new ErrorController();
        $ErrorController->error403();
    }
}
