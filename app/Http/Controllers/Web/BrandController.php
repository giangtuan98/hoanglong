<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\BrandRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    protected $brandRepository;

    public function __construct(
        BrandRepository $brandRepository
    ) {
        $this->brandRepository = $brandRepository;
    }
    public function index(Request $request)
    {
        $brands = $this->brandRepository->allActive();

        $key = md5(vsprintf('%s.%s.%s', [
            'web',
            'allActive',
            'brand'
        ]));

        // $brands = Cache::remember($key, 1000, function () {
        //     return $this->brandRepository->allActive();
        // });
        $brands = $this->brandRepository->allActive();
        
        $searchBrand = isset($request->id) ? $brands->find($request->id) : null;

        return view('web.brand.index', [
            'brands' => $brands,
            'searchBrand' => $searchBrand
        ]);
    }
}
