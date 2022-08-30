<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
class ProductController extends BaseController
{
    public function getProducts(Request $request)
    {
        $brands = Brand::all();
        $brands = json_decode($brands,true);
        $titlearray = array_intersect_key(__("products.title",[],$request->lcl));
        $descriptionarray = array_intersect_key(__("products.description",[],$request->lcl));
        $brandsarrayresp = [];
        foreach ($brands as $brand){
            $brand["title"]=$titlearray[$brand["localekey"]];
            $brand["description"]=$descriptionarray[$brand["localekey"]];
            $brand["image_file"]= env("APP_URL") . "/files/" .$brand['image_file'];
            $brandsarrayresp[] = $brand;
        }
//        for ($i=0;$i<count($brands);$i++){
//            $brands[$i]["title"]=$titlearray[$brands[$i]["localekey"]];
//            $brands[$i]["description"]=$descriptionarray[$brands[$i]["localekey"]];
//        }
        return $this->sendResponse($brandsarrayresp,"success");
    }

    public function getProduct(Request $request){
        $product = DB::table("brands")->where("id", $request->id)->get();
        $product = json_decode($product,true);
        $product = $product[0];
        $titlearray = array_intersect_key(__("products.title",[],$request->lcl));
        $descriptionarray = array_intersect_key(__("products.description",[],$request->lcl));
        $usedtechsarray = array_intersect_key(__("products.description",[],$request->lcl));
        if($product != null){
            $product["product_images"] = substr($product["product_images"], 1, -1);
            $productimages = explode(",",$product["product_images"]);
            for ($i = 0 ; $i < count($productimages);$i++){
                $productimages[$i] =  env("APP_URL") . "/files/" .str_replace('"',"",$productimages[$i]);
            }
            $product["product_images"] = $productimages;
            $product["title"] = $titlearray[$product["localekey"]];
            $product["description"] = $descriptionarray[$product["localekey"]];
            $product["used_techs"] = $usedtechsarray[$product["localekey"]];
            return $this->sendResponse($product, "Ürün detayları");
        } else{
            return $this->sendResponse("Hata", "Ürün bulunamadı");
        }
    }
}
