<?php

namespace RabbitCMS\Templates\Http\Controllers\Backend;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory;
use Illuminate\View\View;
use RabbitCMS\Backend\Annotation\Permissions;
use RabbitCMS\Backend\Http\Controllers\Backend\Controller;
use RabbitCMS\Templates\DataProviders\TemplateDataProvider;
use RabbitCMS\Templates\Entities\Template;
use RabbitCMS\Templates\Http\Requests\StoreTemplateRequest;


/**
 * Class TemplateController
 *
 * @Permissions("templates.edit")
 */
class TemplateController extends Controller
{
    protected $module = 'templates';
    /**
     * @param Factory $view
     */
    public function before(Factory $view)
    {
        $view->composer([
            $this->viewName('templates.index'),
            $this->viewName('templates.edit')
        ], function (View $view) {
            $view->with('locales', Template::query()->distinct()->pluck('locale'));
        });
    }

    /**
     * Show templates table.
     *
     * @return View
     */
    public function index()
    {
        return $this->view('templates.index', [
            'names' => Template::query()->distinct()->pluck('name'),
        ]);
    }

    /**
     * Show template info.
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     */
    public function show($id)
    {
        /* @var Template $template */
        $template = Template::query()->findOrFail($id);

        return $this->view('templates.edit', compact($template));
    }

    /**
     * Grid data action.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid(Request $request)
    {
        return (new TemplateDataProvider())->response($request);
//        $grid = new Grid(
//            new Template(),
//            function (Template $template) {
//                $data = $template->attributesToArray();
//                $data['actions'] = [
//                    'show' => route('templates.templates.show', ['templates' => $template->id]),
//                    'edit' => route('templates.templates.edit', ['templates' => $template->id]),
//                    'delete' => route('templates.templates.destroy', ['templates' => $template->id])
//                ];
//                return $data;
//            },
//            function (EloquentBuilder $query, array $filters) {
//                if (array_key_exists('name', $filters) && $filters['name'] != '') {
//                    $query->where('name', $filters['name']);
//                }
//                if (array_key_exists('locale', $filters) && $filters['locale'] != '') {
//                    $query->where('locale', $filters['locale']);
//                }
//                if (array_key_exists('subject', $filters) && $filters['subject'] != '') {
//                    $query->where('subject', 'like', '%' . $filters['subject'] . '%');
//                }
//            }
//        );

//        return $grid->response($request);
    }

    /**
     * Show create form action.
     *
     * @return \Illuminate\View\View
     * @Permissions("other.templates.write")
     */
    public function create()
    {
        return view(
            'templates::templates.edit',
            [
                'template' => new Template(),
                'names' => Template::query()->distinct()->pluck('name'),
            ]
        );
    }

    /**
     * Store action.
     *
     * @param StoreTemplateRequest $request
     *
     * @return RedirectResponse
     * @Permissions("other.templates.write")
     */
    public function store(StoreTemplateRequest $request)
    {
        $this->saveTemplate(new Template(), $request);

        return \Redirect::route('templates.templates.index')->withErrors(['access' => trans('templates::common.success')],
            'info');
    }

    /**
     * Save template data.
     *
     * @param Template $template
     * @param Request $request
     *
     * @throws \Exception
     * @throws \Throwable
     * @return void
     * @Permissions("other.templates.write")
     */
    protected function saveTemplate(Template $template, Request $request)
    {
        $template->getConnection()->transaction(
            function () use ($template, $request) {
                $data = $request->only(/*$template->getFillable()*/
                    ['name', 'locale', 'subject', 'template']);
                $template->fill($data);
                $template->save();
            }
        );
    }

    /**
     * Update user action.
     *
     * @param int $id
     * @param UserStoreRequest $request
     *
     * @return RedirectResponse
     * @Permissions("other.templates.write")
     */
    public function update($id, StoreTemplateRequest $request)
    {
        /* @var Template $template */
        $template = Template::query()->find($id);

        if ($template === null) {
            return \Redirect::route('templates.templates.index')->withErrors([trans('templates::templates.NotFound')],
                'errors');
        }

        $this->saveTemplate($template, $request);

        return \Redirect::route('templates.templates.index')->withErrors(['access' => trans('templates::common.success')],
            'info');
    }

    /**
     * Delete template action.
     *
     * @param int $id
     *
     * @return RedirectResponse
     * @throws \Exception
     * @Permissions("other.templates.write")
     */
    public function destroy($id)
    {
        /* @var Template $template */
        $template = Template::query()->find($id);

        if ($template === null) {
            return \Redirect::route('templates.templates.index')->withErrors([trans('templates::templates.NotFound')],
                'errors');
        }

        $template->delete();

        return \Redirect::route('templates.templates.index')->withErrors(['access' => trans('templates::common.success')],
            'info');
    }
}
