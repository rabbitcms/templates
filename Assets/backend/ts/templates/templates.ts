/// <reference path="../../../../../backend/Assets/backend/dt/index.d.ts" />

import * as $ from "jquery";
import {DataTable} from "rabbitcms/datatable";
import {MicroEvent, Dialogs} from "rabbitcms/backend";
import {Form} from "rabbitcms/form";
import "tinymce/tinymce.min";

class Templates extends MicroEvent {

    table(portlet: JQuery) {
        let dataTable = new DataTable({
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

        this.bind('dt.update', () => dataTable.submitFilter());

        portlet.on('click', '[rel="delete"]', (e: JQueryEventObject) => {
            e.preventDefault();

            Dialogs.onDelete({
                method: 'DELETE',
                url: $(e.target).attr('href')
            }).then(() => dataTable.submitFilter());
        });

    }

    form(portlet: JQuery) {
        let form = $('form', portlet);

        tinymce.remove();
        tinymce.init({
            selector: '#template_editor',
            content_css: '/modules/templates/backend/css/reset.css',
            height: 300,
            plugins: 'code table link image fullscreen',
            menubar : false,
            toolbar: "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright | table | link image | fullscreen | code",
        });

        new Form(form, {
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
            completeSubmit: () => {
                this.trigger('dt.update');

                return false;
            }
        });
    }
}

export = new Templates();