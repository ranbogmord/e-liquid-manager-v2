<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalServerErrorException;
use App\Flavour;
use App\Services\FlavourService;
use Illuminate\Http\Request;

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
        return response()->json(Flavour::all());
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
        $this->authorize('create', Flavour::class);

        $data = $this->validate($request, [
            'name' => 'required|min:2',
            'is_vg' => 'required|boolean',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);
        $data['author_id'] = auth()->id();

        try {
            $flavour = $this->flavourService->create($data);
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response()->json($flavour);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Flavour $flavour
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Flavour $flavour)
    {
        $this->authorize('view', $flavour);

        return response()->json($flavour);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Flavour $flavour
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Flavour $flavour)
    {
        $this->authorize('update', $flavour);

        $data = $this->validate($request, [
            'name' => 'required|min:2',
            'is_vg' => 'required|boolean',
            'vendor_id' => 'required|exists:vendors,id',
        ]);

        try {
            $this->flavourService->update($flavour, $data);
        } catch (\Exception $exception) {
            throw new InternalServerErrorException($exception->getMessage());
        }

        return response()->json($flavour);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Flavour $flavour
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Flavour $flavour)
    {
        $this->authorize('delete', $flavour);

        try {
            $flavour->delete();
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response(null, 204);
    }
}
