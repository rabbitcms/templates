@extends(\Request::ajax() ? 'backend::layouts.empty' : 'backend::layouts.master')
@section('content')
    <div class="portlet box blue-hoki ajax-portlet" data-require="">
        <div class="portlet-title">
            <div class="caption">
                {{trans('templates::templates.list')}}</div>
            <div class="actions">
                @can('templates.create')
                    <a class="btn btn-default btn-sm" href="{{route('backend.templates.create')}}">
                        <i class="fa fa-plus"> {{trans('backend::common.create')}}</i>
                    </a>
                @endcan
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover" id="templates_templates_table" data-link="{{route('backend.templates.grid')}}">
                    <thead>
                    <tr>
                        <th data-name="id" data-data="id" style="width: 50px;">{{trans('backend::common.id')}}</th>
                        <th data-data="name" data-sortable="false">{{trans('templates::templates.name')}}</th>
                        <th data-data="locale" data-sortable="false">{{trans('templates::templates.locale')}}</th>
                        <th data-data="subject" data-sortable="false">{{trans('templates::templates.subject')}}</th>
                        <th data-data="actions" data-sortable="false" style="width: 110px;"></th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <button class="btn btn-sm yellow filter-submit" title="{{trans('backend::common.search')}}">
                                <i class="fa fa-search"></i></button>
                            <button class="btn btn-sm red filter-cancel" title="{{trans('backend::common.reset')}}">
                                <i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
