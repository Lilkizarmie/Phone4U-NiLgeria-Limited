<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'en_product_name'=>'required',
            'en_brand_name'=>'required',
            'en_category_name'=>'required',
            'price'=>'required',
            // 'discount'=>'required',
            // 'discount_price'=>'required',
            'status'=>'required',
            'primary_image'=>'required',
            'en_about'=>'required',
            'en_description'=>'required',
            // 'en_shippingreturn'=>'required',
            // 'en_additionalinformation'=>'required',
            // 'fr_product_name'=>'required',
            // 'fr_description'=>'required',
            // 'fr_shippingreturn'=>'required',
            // 'fr_additionalinformation'=>'required',
        ];
    }
}
