@extends(\Request::ajax() ? 'backend::layouts.empty' : 'backend::layouts.master')
@section('content')
    <div class="portlet box blue-hoki ajax-portlet" data-require="">
        <div class="portlet-title">
            <div class="caption">
                {{$template->exists ? trans('templates::templates.edit') : trans('templates::templates.create')}}</div>
            <div class="actions">
                <a class="btn btn-default btn-sm" rel="back" href="{{route('backend.templates.index')}}">
                    <i class="fa fa-arrow-left"> {{trans('backend::common.back')}}</i>
                </a>
            </div>
        </div>

        <div class="portlet-body form">
            <form class="horizontal-form" method="post" autocomplete="off" action="{{$template->exists ? route('backend.templates.update', ['id' => $template->id]) : route('backend.templates.store')}}">
                {{csrf_field()}}

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.name')}}</label>
                                <input type="text" class="form-control" name="name" id="name_select2" value="{{$template->name}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.subject')}}</label>
                                <input type="text" class="form-control" name="subject" value="{{$template->subject}}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.locale')}}</label>
                                <select class="form-control" name="locale">
                                    @foreach($locales as $locale)
                                        <option value="{{$locale->locale}}" @if($template->locale === $locale->locale) selected @endif>{{$locale->caption}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.extends')}}</label>
                                <select class="form-control" name="extends">
                                    <option value="">{{trans('backend::common.not_selected')}}</option>
                                    @foreach($extends as $item)
                                        <option value="{{$item->name}}" @if($template->extends === $item->name) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.status')}}</label>
                                <select class="form-control" name="enabled">
                                    <option value="0" @if(!$template->enabled) selected @endif>{{trans('templates::templates.status_0')}}</option>
                                    <option value="1" @if($template->enabled) selected @endif >{{trans('templates::templates.status_1')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.plain')}}</label>
                                <textarea class="form-control" name="plain" rows="5">{{$template->plain}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{trans('templates::templates.template')}}</label>
                                <textarea class="form-control" name="template" id="template_editor" rows="7">{{$template->template}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <div class="pull-right">
                        <a class="btn red" rel="cancel" href="{{route('backend.templates.index')}}">
                            <i class="fa fa-times"></i> {{trans('backend::common.cancel')}}</a>
                        <button class="btn green" type="submit">
                            <i class="fa fa-save"></i> {{trans('backend::common.save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
