/// <reference path="../../../../backend/Assets/backend/dt/index.d.ts" />
import * as $ from "jquery";
import {DataTable} from "rabbitcms/datatable";
import {MicroEvent, State, Dialogs} from "rabbitcms/backend";
import {Form} from "rabbitcms/form";

class Templates extends MicroEvent {
    table(portlet: JQuery) {
        console.log($('.data-table', portlet));
        let dataTable = new DataTable({
            src: $('.data-table', portlet),
            dataTable: {
                columnDefs: [
                    {
                        targets: 5,
                        createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                            console.log(rowData);
                            $(cell).append(
                                $('<a>впизду</a>').on('click', ()=>alert('хомячка'))
                            );
                        }

                    }
                ]
            }
        });

        this.bind('updated', ()=>dataTable.submitFilter());

        portlet.on('click', '[rel="delete"]', (e: JQueryEventObject) => {
            e.preventDefault();
            Dialogs.onDelete({
                method: 'DELETE',
                url: $(e.target).attr('href')
            }).then(()=>dataTable.submitFilter());
        });

    }

    edit(portlet: JQuery, state: State) {
        new Form($('form', portlet), {
            state: state,
            validation: {
                rules: {
                    "name": {required: true},
                    "locale": {required: true}
                },
            },
            completeSubmit: ()=> this.trigger('updated'),
        });
    }

    history(portlet: JQuery) {

    }
}

export = new Templates();