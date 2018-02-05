<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalServerErrorException;
use App\Http\Requests\StoreLiquidRequest;
use App\Liquid;
use App\Services\LiquidService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LiquidController extends Controller
{
    /**
     * @var LiquidService
     */
    private $liquidService;

    public function __construct(LiquidService $liquidService)
    {
        $this->liquidService = $liquidService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->role === "admin" && $request->query('include_all') === "1") {
            return response()->json($this->liquidService->getAllLiquids());
        } else {
            return response()->json($this->liquidService->getUserLiquids(auth()->id()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLiquidRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreLiquidRequest $request)
    {
        $this->authorize('create', Liquid::class);

        $data = $request->validated();
        $data['author_id'] = auth()->id();

        try {
            $liquid = $this->liquidService->create($data);
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response()->json($liquid);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Liquid $liquid
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Liquid $liquid)
    {
        $this->authorize('view', $liquid);

        return response()->json($liquid);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreLiquidRequest $request
     * @param  \App\Liquid $liquid
     *
     * @return Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreLiquidRequest $request, Liquid $liquid)
    {
        $this->authorize('update', $liquid);

        $data = $request->validated();
        $data['author_id'] = auth()->id();

        try {
            $liquid = $this->liquidService->update($liquid, $data);
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response()->json($liquid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Liquid $liquid
     *
     * @return \Illuminate\Http\Response
     * @throws InternalServerErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Liquid $liquid)
    {
        $this->authorize('delete', $liquid);

        try {
            $liquid->delete();
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response(null, 204);
    }
}
