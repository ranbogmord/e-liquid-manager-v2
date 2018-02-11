<?php

namespace App\Http\Controllers\Admin;

use App\Flavour;
use App\Services\FlavourService;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlavourController extends Controller
{
    private $flavourService;

    public function __construct(FlavourService $flavourService)
    {
        $this->flavourService = $flavourService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flavours = Flavour::all();

        return view('admin.flavours.index', [
            'items' => $flavours
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::all()->map(function ($v) {
            return [
                'value' => $v->id,
                'label' => $v->name
            ];
        })->toArray();
        $flavour = new Flavour();

        return view('admin.flavours.create', [
            'flavour' => $flavour,
            'vendors' => $vendors
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
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_vg' => 'required|boolean'
        ]);

        $flavour = $this->flavourService->create($data);

        session()->flash('message:success', 'Flavour created');
        return redirect(route('admin.flavours.edit', $flavour->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Flavour  $flavour
     * @return \Illuminate\Http\Response
     */
    public function edit(Flavour $flavour)
    {
        $vendors = Vendor::all()->map(function ($v) {
            return [
                'label' => $v->name,
                'value' => $v->id
            ];
        })->toArray();

        return view('admin.flavours.edit', [
            'flavour' => $flavour,
            'vendors' => $vendors
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Flavour  $flavour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flavour $flavour)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_vg' => 'required|boolean'
        ]);

        $flavour = $this->flavourService->update($flavour, $data);

        session()->flash('message:success', 'Flavour updated');
        return redirect(route('admin.flavours.edit', $flavour->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Flavour  $flavour
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flavour $flavour)
    {
        $flavour->delete();

        session()->flash('message:success', 'Flavour deleted');
        return redirect(route('admin.flavours.index'));
    }
}
