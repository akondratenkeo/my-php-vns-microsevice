<?php

class DpdController extends Controller
{
    use SendTrait;

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function postcodes()
    {
        $postcode = Request::post('postcode', true);
        $postcode = str_replace(' ', '', $postcode);

        $data = DpdModel::getPostcodeInfo($postcode);

        $response = $this->prepareResponseForAjax($data);
        $this->View->renderJSON($response);
    }

    public function updatePostcodes()
    {
        require Config::get('PATH_CONTROLLER') . 'ErrorController.php';

        $ErrorController = new ErrorController();
        $ErrorController->error403();

        /*$data = DpdModel::updatePostcodesInfo();

        $response = $this->prepareResponseForAjax($data);
        $this->View->renderJSON($response);*/
    }
}
