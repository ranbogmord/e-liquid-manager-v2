<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalServerErrorException;
use App\Services\VendorService;
use App\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * @var VendorService
     */
    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Vendor::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor $vendor
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Vendor $vendor)
    {
        $this->authorize('view', $vendor);
        return response()->json($vendor);
    }
}
