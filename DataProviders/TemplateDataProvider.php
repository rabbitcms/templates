<?php

namespace RabbitCMS\Templates\DataProviders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use RabbitCMS\Carrot\Support\Grid2;
use RabbitCMS\Templates\Entities\Template as TemplateEntity;

class TemplateDataProvider extends Grid2
{
    public function getModel() :Eloquent
    {
        return new TemplateEntity;
    }

    protected function prepareRow(Eloquent $template) :array
    {
        /* @var TemplateEntity $template */

        $result = [
            'id'      => $template->id,
            'name'    => $template->name,
            'locale'  => $template->locale,
            'subject' => $template->subject,
            'actions' => [
                'edit'   => route('backend.templates.edit', ['id' => $template->id]),
                'delete' => route('backend.templates.delete', ['id' => $template->id]),
            ],
        ];

        return $result;
    }

    protected function filters(Builder $query, array $filters) :Builder
    {
        if (array_key_exists('name', $filters) && $filters['name'] !== '') {
            $query->where('name', $filters['name']);
        }
        if (array_key_exists('locale', $filters) && $filters['locale'] !== '') {
            $query->where('locale', $filters['locale']);
        }
        if (array_key_exists('subject', $filters) && $filters['subject'] !== '') {
            $query->where('subject', 'like', '%' . $filters['subject'] . '%');
        }

        return $query;
    }
}