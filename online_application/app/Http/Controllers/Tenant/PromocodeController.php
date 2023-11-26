<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Quotation\QuotationHelpers;
use App\Helpers\School\PromocodeHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StorePromocodeRequest;
use App\Promocodes;
use App\Repository\PromocodeRepository;
use App\Tenant\Models\Course;
use App\Tenant\Models\Promocode;
use App\Tenant\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Response;

class PromocodeController extends Controller
{
    private $promocodeRepository;

    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->middleware('plan.features:quote_builder')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        $this->promocodeRepository = $promocodeRepository;
    }

    public function index()
    {
        //create an scope with the sign % or $ en dependecia de type
        return view('back.promocodes.index', [
            'params' => ['modelName'   => Promocode::getModelName()],
            'codes' => $this->promocodeRepository->all(),
        ]);
    }

    public function create()
    {
        return view('back.promocodes.create', [
            'types' => $this->promocodeRepository->types(),
            'quotations' => Arr::pluck(Quotation::all()->toArray(), 'title', 'id'),
        ]);
    }

    public function store(StorePromocodeRequest $request)
    {
        $request->validated();

        $promocodeService = new Promocodes($this->promocodeRepository);
        $promocode = $promocodeService->create(
            1,
            $request->reward,
            $request->type,
            $request->data ?? [],
            $request->commence_at,
            $request->expires_in = null,
            $request->quantity,
            $request->is_disposable ?? false,
            $request->is_automatic ?? false,
            $request->code ?? null
        );

        $promocodeModel = $this->promocodeRepository->byCode($promocode->first()['code'])->first();

        if ($request->quotations) {
            foreach ($request->quotations as $quotation_id) {
                $quotation = Quotation::findOrFail($quotation_id);
                $promocodeModel->quotations()->save($quotation);
            }
        }

        if ($promocode->isEmpty()) {
            $status = 'error';
            $message = 'Promotion code was not created!';
        } else {
            $status = 'success';
            $message = "Promo code {$promocode->first()['code']} created successfully!";
        }

        return redirect(route('promocodes.index'))
            ->with($status, $message);
    }

    public function show(Promocode $promocode)
    {
        return view('back.promocodes.show', [
            'promocode' => $promocode->load('quotations', 'users'),
            'users' => $promocode->users()->paginate(),
            'types'     => $this->promocodeRepository->types(),
            'promocode_quotations' => Arr::pluck($promocode->quotations->toArray(), 'title', 'id'),
            'quotations' => Arr::pluck(Quotation::all()->toArray(), 'title', 'id'),
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Promocode $promocode)
    {
        try {
            $promocode->expires_at = Carbon::now();
            $promocode->save();

            return Response::json([
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['removedId' => $promocode->id],
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status'   => 404,
                'response' => $e->getMessage(),
            ]);
        }
    }

    public function apply(Request $request)
    {
        $quotation_slug = $request['payload']['quotation'];
        $cart = json_decode($request['payload']['cart'], true);
        $quotation = Quotation::where('slug', $quotation_slug)->first();
        $code = $this->promocodeRepository->byCode($request['payload']['promocode'])->first();
        if (! $quotation || ! $code) {
            return Response::json([
                'status'   => 404,
                'response' => 'error',
            ], 200);
        }

        try {
            if ($this->allowedCode($quotation, $code)) {
                $courses = Course::whereIn(
                    'id',
                    $quotation->properties['courses']
                )->with('campuses', 'dates', 'addons')->get();

                list($price, $cart) = QuotationHelpers::calculatePrice(
                    $courses,
                    $quotation,
                    $cart,
                    $this->promocodeRepository,
                    $code
                );

                return Response::json([
                    'status'   => 200,
                    'response' => 'success',
                    'code' => $code,
                    'cart' => $cart,
                    'price' => $price,
                ], 200);
            } else {
                return Response::json([
                  'status'   => 404,
                  'response' => 'error',
                ], 200);
            }
        } catch (\Exception $e) {
            return Response::json([
                'status'   => 500,
                'response' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * If Quotation had this code or if this code its global and if its active
     * @param $quotation
     * @param $code
     * @return bool
     */
    protected function allowedCode($quotation, $code): bool
    {
        return
            (($quotation->promocodeables)->contains($code) ||
            ($this->promocodeRepository->getGlobalPromocodes())->contains($code)) &&
            $code->isActive();
    }
}
