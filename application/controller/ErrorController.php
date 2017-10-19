<?php

/**
 * Class Error
 * This controller simply contains some methods that can be used to give proper feedback in certain error scenarios,
 * like a proper 404 response with an additional HTML page behind when something does not exist.
 */
class ErrorController extends Controller
{
    use SendTrait;

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function error404()
    {
        $response = $this->prepareResponseForAjax([
            'error' => 'This page does not exist.',
        ]);

        header('HTTP/1.0 404 Not Found', true, 404);
        $this->View->render('error/404');
    }

    public function error403()
    {
        $response = $this->prepareResponseForAjax([
            'error' => 'You don\'t have permission to access to this service.',
        ]);

        header('HTTP/1.0 403 Forbidden', true, 403);
        $this->View->render('error/403');
    }
}
