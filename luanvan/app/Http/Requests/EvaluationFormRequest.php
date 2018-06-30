<?php

namespace App\Http\Requests;

use App\Model\EvaluationCriteria;
use Illuminate\Foundation\Http\FormRequest;

class EvaluationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $arrRules = array();

        $evaluationCriterias = EvaluationCriteria::whereIn('id',ARRAY_EVALUATION_CRITERIAS_VALIDATE)->get();
        foreach($evaluationCriterias as $key => $value){
            $arrRules["score.$value->id"]  = "between:$value->mark_range_from,$value->mark_range_to";
        }

        return $arrRules;
    }

//    public function messages()
//    {
//        return [
//            'txtTitle.required' => 'Vui lòng nhập tên title',
//            'txtTitle.unique' => 'Tên title đã tồn lại',
//            'fImages.required' => 'Chọn hình ảnh'
//        ];
//    }
}
