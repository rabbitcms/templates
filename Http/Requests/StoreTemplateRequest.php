<?php

namespace RabbitCMS\Templates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
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
        $id = $this->route()->hasParameter('id')
            ? $this->route()->parameter('id') . ',id'
            : 'NULL,NULL';
        return [
            'name'      => ['required', 'max:255', 'unique:mail_templates,name,' . $id . ',locale,' . $this->input('locale')],
            'locale'    => ['required' , 'max:10', 'unique:mail_templates,locale,' . $id . ',name,' . $this->input('name')],
            'subject'   => 'required|max:255',
            'template'  => 'required|max:65535',
        ];
    }
}
