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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Vendor::class);

        $data = $this->validate($request, [
            'name' => 'required',
            'abbr' => 'required',
        ]);
        $data['author_id'] = auth()->id();

        try {
            $vendor = $this->vendorService->create($data);
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response()->json($vendor);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Vendor $vendor
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);
        $data = $this->validate($request, [
            'name' => 'required',
            'abbr' => 'required',
        ]);

        try {
            $vendor = $this->vendorService->update($vendor, $data);
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }


        return response()->json($vendor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor $vendor
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);
        try {
            $vendor->delete();
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response(null, 204);
    }
}
