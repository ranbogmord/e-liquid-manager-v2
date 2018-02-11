<?php

namespace App\Http\Controllers\Admin;

use App\Services\VendorService;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
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
        $vendors = Vendor::all();

        return view('admin.vendors.index', [
            'items' => $vendors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendor = new Vendor();

        return view('admin.vendors.create', [
            'vendor' => $vendor
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'abbr' => 'nullable'
        ]);

        $data['author_id'] = auth()->id();

        $vendor = $this->vendorService->create($data);

        session()->flash('message:success', 'Vendor created');
        return redirect(route('admin.vendor.edit', $vendor->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', [
            'vendor' => $vendor
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'abbr' => 'nullable'
        ]);

        $data['author_id'] = auth()->id();

        $vendor = $this->vendorService->update($vendor, $data);

        session()->flash('message:success', 'Vendor updated');
        return redirect(route('admin.vendor.edit', $vendor->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        session()->flash('message:success', 'Vendor deleted');
        return redirect(route('admin.vendor.index'));
    }
}
