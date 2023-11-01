<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',
            'location_id' => 'required|integer|exists:locations,id',
            'captain_id' => 'required|integer|exists:captains,id',
            'payment_type' => 'required|in:cash,bank',
            'products' => 'required|array',
            'products.*' => 'distinct',
            'products.*.id' => 'required_with:product.*',
            'coupon' => 'sometimes|string|max:255'
        ];
    }




    public function validateQuantitiesAndOptions($products) // need to simplify
    {
        $validationRules = [];

        foreach ($products as $index => $product) {
            $productKey = 'products.' . $index;
            $productOptionsNames = $product->options->pluck('name')->toArray();
            $productOptionsValues = $product->options->pluck('values')->flatten(1);
            $productOptionsValuesNames = $productOptionsValues->pluck('name')->toArray();

            if ($product->options->isNotEmpty()) {

                $validationRules[$productKey . '.option.name'] = [
                    'required_with:' . $productKey,
                    Rule::in($productOptionsNames),
                ];

                $selectedProductOptionValues = $this->selectedProductOptionValues($product, $index);
                $validationRules[$productKey . '.option.value.name'] = [
                    'required_with:' . $productKey,
                    Rule::in($productOptionsValuesNames, $selectedProductOptionValues),
                ];


                $selectedProductOptionValue = $this->selectedProductOptionValue($productOptionsValues, $index);

                $validationRules[$productKey . '.option.value.quantity'] = [
                    'required_with:' . $productKey . '.option.value',
                    'lte:' . $selectedProductOptionValue->quantity,
                ];
            } else {
                $validationRules[$productKey . '.quantity'] = [
                    'required',
                    'lte:' . $product->quantity,
                ];
            }
        }

        return $this->validate($validationRules);
    }






    protected function selectedProductOptionValues($product, $index)
    {

        $selectedProductOption = $product->options
            ->where('name', request()->input('products.' . $index . '.option.name'))
            ->first();
        if (!$selectedProductOption) {
            abort(response()->json([
                'message' => 'selected product option value is not available',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
        }

        return $selectedProductOption->values->pluck('name')->toArray();
    }

    protected function selectedProductOptionValue($productOptionsValues, $index)
    {
        $selectedProductOptionValue = $productOptionsValues
            ->where('name', request()->input('products.' . $index . '.option.value.name'))
            ->first();
        if (!$selectedProductOptionValue) {
            abort(response()->json([
                'message' => 'selected product option value is not available',
                'success' => false,
                'statuscode' => Response::HTTP_CONFLICT,
            ]));
        }

        return $selectedProductOptionValue;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
