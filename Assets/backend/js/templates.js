/// <reference path="../../../../backend/Assets/backend/dt/index.d.ts" />
define(["require", "exports", "jquery", "rabbitcms/datatable", "rabbitcms/backend", "rabbitcms/form", "tinymce/tinymce.min"], function (require, exports, $, datatable_1, backend_1, form_1) {
    "use strict";
    var Templates = (function (_super) {
        __extends(Templates, _super);
        function Templates() {
            _super.apply(this, arguments);
        }
        Templates.prototype.table = function (portlet) {
            var dataTable = new datatable_1.DataTable({
                src: $('#templates_templates_table', portlet),
                dataTable: {
                    lengthMenu: [
                        [30, 50, 100, 200, 500],
                        [30, 50, 100, 200, 500]
                    ],
                    pageLength: 30,
                    order: [
                        [0, "desc"]
                    ],
                    colReorder: {
                        fixedColumnsRight: 1,
                        fixedColumnsLeft: 1
                    },
                    columnDefs: [
                        {
                            targets: 4,
                            render: function (data, type, row, meta) {
                                return '<a class="btn btn-sm green" href="' + row.actions.edit + '"><i class="fa fa-pencil"></i></a> ' +
                                    '<a class="btn btn-sm red" rel="delete" href="' + row.actions.delete + '"><i class="fa fa-trash-o"></i></a> ';
                            }
                        }
                    ]
                }
            });
            this.bind('dt.update', function () { return dataTable.submitFilter(); });
            portlet.on('click', '[rel="delete"]', function (e) {
                e.preventDefault();
                backend_1.Dialogs.onDelete({
                    method: 'DELETE',
                    url: $(e.target).attr('href')
                }).then(function () { return dataTable.submitFilter(); });
            });
        };
        Templates.prototype.form = function (portlet) {
            var _this = this;
            var form = $('form', portlet);
            tinymce.remove();
            tinymce.init({
                selector: '#template_editor',
                content_css: '/modules/templates/backend/css/reset.css',
                height: 300,
                plugins: 'code table link image fullscreen',
                menubar: false,
                toolbar: "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright | table | link image | fullscreen | code",
            });
            new form_1.Form(form, {
                validation: {
                    rules: {
                        "name": {
                            required: true
                        },
                        "locale": {
                            required: true
                        },
                        "subject": {
                            required: true
                        },
                        "template": {
                            required: true
                        }
                    }
                },
                completeSubmit: function () {
                    _this.trigger('dt.update');
                    return false;
                }
            });
        };
        return Templates;
    }(backend_1.MicroEvent));
    return new Templates();
});
//# sourceMappingURL=templates.js.map