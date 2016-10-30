<?php

namespace App\Http\Controllers;

use Factotum\Helpers\OutputResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Factotum\Helpers\OutputResponse
     */
    protected $response;

    /**
     * Controller constructor.
     *
     * @param \Factotum\Helpers\OutputResponse $outputResponse
     */
    public function __construct(OutputResponse $outputResponse)
    {
        $this->response = $outputResponse;
    }
}
