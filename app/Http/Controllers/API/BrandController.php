<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Intervention\Image\Facades\Image;
class BrandController extends BaseController
{
    public function info(Request $request){
        return $this->sendError(json_encode( phpinfo() ));
    }

    public function karakter_temizle($string)
    {
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        return $string;
    }
    // Brand Ekle
    public function addBrand(Request $request){
        $validator = Validator::make($request->all(),
            [
                'brand_name' => 'required',
                'image_file' => 'required|mimes:jpg,png,mp4,mov,mkv,avi,jpeg|max:262144'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        if ($files = $request->file('image_file')) {

            $filesInfo = pathinfo($request->file("image_file")->getClientOriginalName());
            $fileName = $this->karakter_temizle($filesInfo["filename"]) . "-" . time() . "." . $request->file("image_file")->getClientOriginalExtension();
            //store file into document folder
            $image = $request->file('image_file');
            $imgFile = Image::make($image->getRealPath());
            $imgFile->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path("/files/".$fileName));
            //$file = $request->file("image_file")->move(public_path("files"), $fileName);
            $document = new Brand();
            $document->brand_name = $request->get('brand_name');
            $document->image_file = $fileName;
            $document->save();
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => env("APP_URL") . "/files/" .$fileName,
            ]);

        }else{
            return $this->sendError("Else'e düştü");
        }
    }
    // Brand Product Fotolarını güncelle
    public function updateProduct(Request $request){
        $myarr = [];
        foreach ($request->product_images as $image){
                $filesInfo = pathinfo($image->getClientOriginalName());
                $fileName = $this->karakter_temizle($filesInfo["filename"]) . "-" . time() . "." . $image->getClientOriginalExtension();
                //store file into document folder
                $file = $image->move(public_path("/files/"), $fileName);
                $myarr[] = $fileName;
        }
        $myjson = json_encode($myarr, true);
        DB::table("brands")->where("id", $request->id)->update(["product_images"=>$myjson]);

    }

    public function getBrandList(){
        $brands = Brand::select("brand_name","image_file","localekey")->get();
        foreach ($brands as $brand){
            $brand->image_file = env("APP_URL") . "/files/" .$brand->image_file;
        }
        return $this->sendResponse($brands,"success");
    }

}
