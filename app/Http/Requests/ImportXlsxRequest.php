<?php

namespace App\Http\Requests;

use App\Rules\ImportFileLimit;
use Illuminate\Foundation\Http\FormRequest;

class ImportXlsxRequest extends FormRequest
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
            'file' => ['required',new ImportFileLimit()],
            'extension' => 'sometimes|required|in:xlsx'
        ];
    }


    /**
     * change data before validate
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $data = $this->all();
        if(isset($data['file'])){
            $data['extension'] = strtolower($data['file']->getClientOriginalExtension());
        }
        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }
}
