import DataTableTools from './tools/datatable/datatable.tools';

$(function() {
    let tableListSelector = $('#dt-vehicle');
    let url = window.location.href;
    let columnDefs = [
        {
            name: "vd.id",
            targets: 8,
            orderable: true,
            render: function(data, type, row) {
                console.log(data);
                var btn = '<a title="modifier" class="link-dark p-2" href="' + tableListSelector.data('edit-url').replace('id', data) + '">Modifier</i></a>';
                btn += '<a title="supprimer" class="link-dark text-danger p-2" onclick="confirm(\'Voulez-vous vraiment supprimer?\')" data-href="' + tableListSelector.data('delete-url').replace('id', data) + '">Supprimer</i></a>';
                return btn;
            }
        },
        { name: "vd.numeroDeFiche", targets: 0, orderable: true, },
        { name: "vd.compteAffaire", targets: 1, orderable: true, },
        { name: "vd.compteEvenementVeh", targets: 2, orderable: true, },
        { name: "vd.libelleCivilite", targets: 3, orderable: true, },
        { name: "vd.proprietaireActuelDuVehicule", targets: 4, orderable: true, },
        { name: "vd.nom", targets: 5, orderable: true, },
        { name: "vd.prenom", targets: 6, orderable: true, },
        { name: "vd.numeroEtNomDeLaVoie", targets: 7, orderable: true, },
        { name: "vd.complementAdresse1", targets: 8, orderable: true, },
    ];

    let datatableList = DataTableTools.ajax(tableListSelector, url, columnDefs);
    // DataTableTools.search($('#dt-salary_search'), datatableList);

    // $(document).on('click', '.archive', function(e) {
    //     e.preventDefault();
    //     var href = $(this).data('href');
    //     var name = $(this).data('name');
    //
    //     SwalTools.firePopup({
    //         title: "Voulez-vous vraiment archiver le salarié <span class='text-orange'>" + name + "</span> ?",
    //         icon: "question"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             location.href = href;
    //         }
    //     });
    // });

    // $(document).on('click', '.delete', function(e) {
    //     e.preventDefault();
    //     var href = $(this).data('href');
    //     var name = $(this).data('name');
    //
    //     SwalTools.firePopup({
    //         title: "Voulez-vous vraiment supprimer le salarié <span class='text-orange'>" + name + "</span> ?",
    //         icon: "question"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             location.href = href;
    //         }
    //     });
    // });

});