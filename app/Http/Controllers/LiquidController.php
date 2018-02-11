<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalServerErrorException;
use App\Http\Requests\StoreLiquidRequest;
use App\Liquid;
use App\Services\LiquidService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        return response()->json($this->liquidService->getAllLiquids($request->query()));
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

    /**
     * @param Liquid $liquid
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws InternalServerErrorException
     */
    public function newVersion(Liquid $liquid)
    {
        $flavours = $liquid->flavours;
        $newLiquid = $liquid->replicate();
        $newLiquid->id = null;

        if (preg_match('/.*V(\d+)/', $newLiquid->name, $matches)) {
            if (!empty($matches[1])) {
                $newVersion = intval($matches[1]) + 1;

                $newLiquid->name = preg_replace('/(.*?)V(\d+)/', '\1V' . $newVersion, $newLiquid->name);
            } else {
                $newLiquid->name .= ' V2';
            }
        } else {
            $newLiquid->name .= ' V2';
        }

        try {
            DB::transaction(function () use ($newLiquid, $flavours, $liquid) {
                $newLiquid->save();
                $flavours = collect($flavours)->mapWithKeys(function ($f) {
                    return [
                        $f->id => [
                            'percent' => $f->pivot->percent
                        ]
                    ];
                });

                $newLiquid->flavours()->sync($flavours);

                $liquid->next_version_id = $newLiquid->id;
                $liquid->save();
            });
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response()->json($newLiquid);
    }
}
