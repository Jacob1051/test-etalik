import DataTableTools from './tools/datatable/datatable.tools';

$(function() {
    function convertJsonDateToShortDate(data) {
        const dateValue = new Date(data);

        return dateValue.toLocaleDateString();
    }

    let tableListSelector = $('#dt-vehicle');
    let url = window.location.href;
    let columnDefs = [
        { name: "vd.compteAffaire", targets: 1, orderable: true, },
        { name: "vd.compteEvenementVeh", targets: 2, orderable: true, },
        { name: "vd.compteDernierEvenementVeh", targets: 3, orderable: true, },
        { name: "vd.numeroDeFiche", targets: 4, orderable: true, },
        { name: "vd.libelleCivilite", targets: 5, orderable: true, },
        { name: "vd.proprietaireActuelDuVehicule", targets: 6, orderable: true, },
        { name: "vd.nom", targets: 7, orderable: true, },
        { name: "vd.prenom", targets: 8, orderable: true, },
        { name: "vd.numeroEtNomDeLaVoie", targets: 9, orderable: true, },
        { name: "vd.complementAdresse1", targets: 10, orderable: true, },
        { name: "vd.codePostal", targets: 11, orderable: true, },
        { name: "vd.ville", targets: 12, orderable: true, },
        { name: "vd.telephoneDomicile", targets: 13, orderable: true, },
        { name: "vd.telephonePortable", targets: 14, orderable: true, },
        { name: "vd.telephoneJob", targets: 15, orderable: true, },
        { name: "vd.email", targets: 16, orderable: true, },
        { name: "vd.dateDeMiseEnCirculation", targets: 17, orderable: true,
            render: function(data, type, row) {
                if(!data) return "";
                return convertJsonDateToShortDate(data.date);
            }
        },
        { name: "vd.dateAchatDateDeLivraison", targets: 18, orderable: true,
            render: function(data, type, row) {
                if(!data) return "";
                return convertJsonDateToShortDate(data.date);
            }
        },
        { name: "vd.dateDernierEvenementVeh", targets: 19, orderable: true,
            render: function(data, type, row) {
                if(!data) return "";
                return convertJsonDateToShortDate(data.date);
            }
        },
        { name: "vd.libelleMarqueMrq", targets: 20, orderable: true, },
        { name: "vd.libelleModeleMod", targets: 21, orderable: true, },
        { name: "vd.version", targets: 22, orderable: true, },
        { name: "vd.vin", targets: 23, orderable: true, },
        { name: "vd.immatriculation", targets: 24, orderable: true, },
        { name: "vd.typeDeProspect", targets: 25, orderable: true, },
        { name: "vd.kilometrage", targets: 26, orderable: true, },
        { name: "vd.libelleEnergieEnerg", targets: 27, orderable: true, },
        { name: "vd.vendeurVN", targets: 28, orderable: true, },
        { name: "vd.vendeurVO", targets: 29, orderable: true, },
        { name: "vd.commentaireDeFacturationVeh", targets: 30, orderable: true, },
        { name: "vd.typeVNVO", targets: 31, orderable: true, },
        { name: "vd.numeroDeDossierVNVO", targets: 32, orderable: true, },
        { name: "vd.intermediaireDeVenteVN", targets: 33, orderable: true, },
        { name: "vd.dateEvenementVeh", targets: 34, orderable: true,
            render: function(data, type, row) {
                if(!data) return "";
                return convertJsonDateToShortDate(data.date);
            }
        },
        { name: "vd.origineEvenementVeh", targets: 35, orderable: true, },
        {
            name: "vd.id",
            targets: 0,
            orderable: false,
            render: function(data, type, row) {
                var btn = '<a title="modifier" class="link-dark p-2" href="' + tableListSelector.data('edit-url').replace('id', data) + '"><i class="fa fa-pencil fs-5"></i></a>';
                btn += '<a title="supprimer" class="link-dark text-danger p-2" onclick="return confirm(\'Voulez-vous vraiment supprimer?\')" href="' + tableListSelector.data('delete-url').replace('id', data) + '"><i class="fa fa-trash fs-5"></i></a>';

                return btn;
            }
        },
    ];
    var externalData = {
        'vd.numeroDeFiche': function(){ return $('#numFiche').val() },
        'vd.libelleCivilite': function(){ return $('#civilite').val() },
        'vd.libelleMarqueMrq': function(){ return $('#marque').val() },
        'vd.vin': function(){ return $('#vin').val() },
        'vd.typeDeProspect': function(){ return $('#typeProspect').val() },
        'vd.kilometrage': function(){ return $('#kilometrage').val() },
        'vd.typeVNVO': function(){ return $('#typeVNVO').val() },
    };

    const datatableList = DataTableTools.ajax(tableListSelector, url, columnDefs, externalData, false);

    $('#filter').on('click', function(e) {
        datatableList.ajax.reload();
    });
});
