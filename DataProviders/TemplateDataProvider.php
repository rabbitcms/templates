<?php

namespace RabbitCMS\Templates\DataProviders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use RabbitCMS\Carrot\Support\Grid2;
use RabbitCMS\Templates\Entities\Template;

class TemplateDataProvider extends Grid2
{
    public function getModel() :Eloquent
    {
        return new Template();
    }

    protected function prepareRow(Eloquent $row) :array
    {
        return $row->attributesToArray() + ['actions' => $this->prepareActions($row)];
    }

    protected function prepareActions(Eloquent $row)
    {
        return [
            [
                'edit' => route('backend.templates.edit', ['id' => $row->getKey()]),
            ],
            [
                'delete' => route('backend.templates.delete', ['id' => $row->getKey()])
            ]
        ];
    }

    protected function filters(Builder $query, array $filters) :Builder
    {
        if (array_key_exists('name', $filters) && $filters['name'] != '') {
            $query->where('name', $filters['name']);
        }
        if (array_key_exists('locale', $filters) && $filters['locale'] != '') {
            $query->where('locale', $filters['locale']);
        }
        if (array_key_exists('subject', $filters) && $filters['subject'] != '') {
            $query->where('subject', 'like', '%' . $filters['subject'] . '%');
        }
        return $query;
    }
}