<?php

namespace RabbitCMS\Templates\Http\Controllers\Backend;


use ABC\Modules\Common\Entities\Localization as LocalizationEntity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory;
use Illuminate\View\View;
use RabbitCMS\Backend\Annotation\Permissions;
use RabbitCMS\Backend\Http\Controllers\Backend\Controller;
use RabbitCMS\Templates\DataProviders\TemplateDataProvider;
use RabbitCMS\Templates\Entities\Template as TemplateEntity;
use RabbitCMS\Templates\Http\Requests\TemplateRequest;

/**
 * Class TemplateController
 * @Permissions("templates.read")
 */
class TemplateController extends Controller
{
    protected $module = 'templates';

    /**
     * @param Factory $factory
     */
    public function before(Factory $factory)
    {
        $factory->composer(
            [
                $this->viewName('templates.index'),
                $this->viewName('templates.form'),
            ],
            function (View $view) {
                $locales = LocalizationEntity::query()
                    ->where('enabled', '=', 1)
                    ->get(['caption', 'locale']);

                $view->with('locales', $locales);
            }
        );
    }

    /**
     * Templates table.
     *
     * @return View
     */
    public function index()
    {
        return $this->view('templates.index');
    }

    /**
     * Templates table data.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function grid(Request $request)
    {
        return (new TemplateDataProvider)->response($request);
    }

    /**
     * Template create form.
     *
     * @return View
     * @Permissions("templates.create")
     */
    public function create()
    {
        $template = new TemplateEntity;

        $extends = TemplateEntity::query()
            ->select('name')
            ->whereNull('extends')
            ->distinct()
            ->get();

        return $this->view('templates.form', compact('template', 'extends'));
    }

    /**
     * Template create action.
     *
     * @param TemplateRequest $request
     * @Permissions("templates.create")
     */
    public function store(TemplateRequest $request)
    {
        $template = new TemplateEntity;

        return $this->save($template, $request);
    }

    /**
     * Template update form.
     *
     * @param $id
     *
     * @return View
     */
    public function edit($id)
    {
        /* @var TemplateEntity $template */
        $template = TemplateEntity::query()
            ->findOrFail($id);

        $extends = TemplateEntity::query()
            ->select('name')
            ->whereNull('extends')
            ->where('name', '<>', $template->name)
            ->distinct()
            ->get();

        return $this->view('templates.form', compact('template', 'extends'));
    }

    /**
     * Template Update action.
     *
     * @param                 $id
     * @param TemplateRequest $request
     * @Permissions("templates.update")
     */
    public function update($id, TemplateRequest $request)
    {
        /* @var TemplateEntity $template */
        $template = TemplateEntity::query()
            ->findOrFail($id);

        return $this->save($template, $request);
    }

    /**
     * Template delete action.
     *
     * @param $id
     * @Permissions("templates.delete")
     */
    public function delete($id)
    {
        TemplateEntity::query()
            ->findOrFail($id)
            ->delete();
    }

    /**
     * Template save action.
     *
     * @param TemplateEntity  $template
     * @param TemplateRequest $request
     *
     * @return void
     */
    protected function save(TemplateEntity $template, TemplateRequest $request)
    {
        $data = $request->only(['name', 'locale', 'subject', 'plain', 'enabled', 'template']);

        $extends = $request->input('extends');
        $data['extends'] = $extends !== '' ? $extends : null;

        $template->fill($data);
        $template->save();
    }
}
