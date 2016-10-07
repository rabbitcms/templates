<?php

namespace RabbitCMS\Templates\Http\Requests;


use Illuminate\Routing\Route;
use RabbitCMS\Carrot\Http\Request;

class TemplateRequest extends Request
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
     * @param Route $route
     *
     * @return array
     */
    public function rules(Route $route)
    {
        $id = $route->hasParameter('id') ? $route->parameter('id') : 'NULL';
        $locale = $this->input('locale');

        $rules = [
            'name'     => 'required|unique:mail_templates,name,' . $id . ',id,locale,' . $locale,
            'locale'   => 'required',
            'subject'  => 'required',
            'template' => 'required',
        ];

        return $rules;
    }
}
