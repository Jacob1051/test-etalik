import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-bs5';
import 'datatables.net-fixedcolumns';
import 'datatables.net-fixedheader';
let language = require('./translations/datatable.fr.json');

class DataTableTools {

    paramsFilterDatatable = [];
    paramsFilterDatatableDynamic = [];
    orderDatatable = [];

    ajax(
        selector,
        url,
        columnDefs,
        isSearchable = true,
        drawCallback = function (settings) {
            $('.date-fr').each(function () {
                if ($(this).text().length == 10) {
                    moment.locale('fr')
                    var date = moment($(this).text()).format("DD MMMM YYYY");
                    $(this).text(date)
                }
            });
        },
        createdRow = null
    )
    {
        let self = this;
        return $(selector).DataTable({
            bProcessing: true,
            bFilter: true,
            bServerSide: true,
            iDisplayLength: 10,
            searching: isSearchable,
            lengthChange: true,
            order: self.orderDatatable,
            ajax: {
                url: url,
                data: function (data) {
                    data.order_by = data.order[0] ? data.columns[data.order[0].column].name + ' ' + data.order[0].dir : 'id desc';
                    for (let i = 0; i < self.paramsFilterDatatable.length; i++) {
                        data[self.paramsFilterDatatable[i]['key']] = self.paramsFilterDatatable[i]['value'];
                    }
                    for (let i = 0; i < self.paramsFilterDatatableDynamic.length; i++) {
                        data[self.paramsFilterDatatableDynamic[i]['key']] = self.paramsFilterDatatableDynamic[i]['value'].val();
                    }

                    let container = $(selector).closest('.table-container');
                    if (container.length) {
                        $(container).animate({
                            scrollTop: $(selector).offset().top
                        }, 500);
                    } else {
                        $(selector).animate({
                            scrollTop: $(selector).offset().top
                        }, 500);
                    }
                }
            },
            columnDefs: typeof columnDefs != "undefined" ? columnDefs : [],
            language: language,
            drawCallback: drawCallback,
            footerCallback: function (row, data, start, end, display) {
                let info = $('#'+ selector.attr('id') +'_info');
                info.appendTo('#'+ selector.attr('id') +'_custom_info');
                $('#' + selector.attr('id') + '_paginate').appendTo('#' + selector.attr('id') + '_custom_datatable_paginate');
            },
            createdRow: createdRow
        });
    }

	generateDatatable(selector, bPaginate = true, iDisplayLength = 10, fixedColumns = 0, fixedHeader = '') {
        var options = {
            iDisplayLength: iDisplayLength,
            language: language,
            bPaginate: bPaginate,
            ordering: false,
            drawCallback : function (settings) {
                $('.hide-parent').each(function () {
                    $(this).closest('td').addClass('d-none')
                })
            },
            footerCallback: function (row, data, start, end, display) {
                let pageLength = $('select[name=' + selector.attr('id') + '_length]');
                pageLength.attr('class', 'js-select form-select form-select-borderless w-auto')
                pageLength.appendTo('#' + selector.attr('id') + '_custom_length');
                $('#' + selector.attr('id') + '_paginate').appendTo('#' + selector.attr('id') + '_custom_datatable_paginate');
            },
            fnDrawCallback: function (oSettings) {
              var pgr = $('#' + selector.attr('id') + '_custom_datatable_paginate')
              if (oSettings._iDisplayLength + 1 > oSettings.fnRecordsDisplay()) {
                pgr.hide();
              } else {
                pgr.show()
              }
            }
        };

        if (fixedColumns > 0) {
            options.scrollX = true;
            options.fixedColumns = {
                leftColumns: fixedColumns
            };
        }

        if (fixedHeader != '') {
            options.fixedHeader =  true;
            options.scrollY = fixedHeader; 
        }

		return $(selector).DataTable(options);
	}

	search(inputSelector, datatable)
    {
        let inputFilterValue;
        let inputFilterTimeout = null;
        $(inputSelector).on('input', function () {
            inputFilterValue = this.value;
            clearTimeout(inputFilterTimeout);
            inputFilterTimeout = setTimeout(function () {
                datatable.search(inputFilterValue).draw();
            }, 200);
        });
    }

    destroy(datatable) {
        datatable.destroy();
    }

    addRow(datatable, row){
        datatable.row.add([row]).draw(false);
    }

    append(datatable, row){
        datatable.row.add(row).draw(false);
    }
}

export default new DataTableTools();