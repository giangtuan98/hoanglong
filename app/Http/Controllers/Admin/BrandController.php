<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\BrandRepository;
use App\Contracts\Repositories\ImageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Image;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    protected $brandRepository;
    protected $imageRepository;
    public function __construct(
        BrandRepository $brandRepository,
        ImageRepository $imageRepository
    ) {
        $this->brandRepository = $brandRepository;
        $this->imageRepository = $imageRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('brand.viewAny');
        
        $admin = auth('admin')->user();
        if ($admin->isSuperAdmin()) {
            $brands = $this->brandRepository->all();
        } else {
            $brands = [$admin->brand];
        }
        return view('admin.brand.index', [
            'brands' => $brands,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('brand.create');
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        $this->authorize('brand.create');
        try {
            $admin = auth('admin')->user();
            $data = $request->all();
            $mainImage = $request->file('mainImage');
            $name = time() . '.' . $mainImage->getClientOriginalName();
            $mainImageDesPath = public_path(config('picture.brand_image'));
            $mainImage->move($mainImageDesPath, $name);

            $image = $this->imageRepository->store([
                'url' => config('picture.brand_image') . '/' . $name,
                'admin_id'  => $admin->id
            ]);
        
            $dataBrand = [
                'name' => $data['name'],
                'phone' => json_encode($data['phones']),
                'bank' => json_encode($data['banks']),
                'description' => $data['description'],
                'notice' => $data['notice'] ?? '',
                'image' => $image->url,
            ];

            $brand = $this->brandRepository->store($dataBrand);

            if (isset($data['images']) && count($data['images']) > 0) {
                $brand->images()->attach($data['images']);
            }

            \Session::forget('imageUploaded');
            $key = md5(vsprintf('%s.%s.%s', [
                'web',
                'allActive',
                'brand'
            ]));
            // Cache::forget($key);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thêm nhà xe không thành công')->withInput();
        }

        return redirect()->route('admin.brand.index')->with('success', 'Thêm nhà xe thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $this->authorize('brand.update', $brand);
        $images = $brand->images;
        return view('admin.brand.edit', [
            'brand' => $brand,
            'images' => $images
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $brand)
    {
        // sync image, update mainImage
        $this->authorize('brand.update', $brand);
        try {
        $data = $request->all();
        $brand->name = $data['name'];
        $brand->phone = json_encode($data['phones']);
        $brand->bank = json_encode($data['banks']);
        $brand->description = $data['description'];
        $brand->notice = $data['notice'];

        if ($request->hasFile('mainImage')) {
            $mainImage = $request->file('mainImage');
            $name = time() . '.' . $mainImage->getClientOriginalName();
            $mainImageDesPath = public_path(config('picture.brand_image'));
            $mainImage->move($mainImageDesPath, $name);

            $image = $this->imageRepository->store([
                    'url' => config('picture.brand_image') . '/' . $name,
                    'admin_id'  => auth('admin')->user()->id
                ]);
            $brand->image = $image->url;
        }
            
        $brand->save();
        
        if (isset($data['images']) && count($data['images']) > 0) {
            $brand->images()->sync($data['images']);
        }

        \Session::forget('imageUploaded');
        $key = md5(vsprintf('%s.%s.%s', [
            'web',
            'allActive',
            'brand'
        ]));
        // Cache::forget($key);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thêm nhà xe không thành công')->withInput();
        }

        return redirect()->route('admin.brand.index')->with('success', 'Cập nhật nhà xe thành công');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $this->authorize('brand.delete', $brand);
        // try {
        $brand->is_active = false;
        $brand->save();
        Admin::where('brand_id', $brand->id)->update(['is_active' => $brand->is_active]);
        $key = md5(vsprintf('%s.%s.%s', [
            'web',
            'allActive',
            'brand'
        ]));
        // Cache::forget($key);
        // $imagesBrand = $brand->images();
        // $brand->images->detach();
        // $this->imageRepository->unlink($imagesBrand);

        // if (file_exists(public_path($brand->image))) {
        //     unlink(public_path($brand->image));
        // }
        // $brand->delete($id);
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Xóa nhà xe thất bại');
        // }
        return redirect()->route('admin.brand.index')->with('success', 'Ngưng kích hoạt nhà xe thành công');
    }

    public function images($id)
    {
        $images = [];
        if ($id == -1) {
            if (\Session::has('imageUploaded')) {
                $uploadedImage = \Session::get('imageUploaded');
                $images = Image::whereIn('id', $uploadedImage['listImageId'])->get();
            }
        } else {
            $brand = $this->brandRepository->findOrFail($id);
            $images = $brand->images;
        }
        
        $data = [];
        foreach ($images as $image) {
            $data[] = [
                'id' => $image->id,
                'url' => url($image->url),
                'name' => $image->title,
                'deleteRoute' => route('admin.image.delete', ['id' => $image->id])
            ];
        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function active(Brand $brand)
    {
        $this->authorize('brand.delete', $brand);
        $brand->is_active = config('constants.IS_ACTIVE_STATUS.ACTIVE');
        $brand->save();
        Admin::where([
            ['brand_id', $brand->id],
            ])
            ->whereHas('role', function ($query) {
                $query->where('slug', 'admin');
            })
            ->update(['is_active' => $brand->is_active]);
        return redirect()->route('admin.brand.index')->with('success', 'Kích hoạt nhà xe thành công');
    }
}
