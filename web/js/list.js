(function(angular) {

    'use strict';
    angular.module('abmApp', ['datatables']).controller('UserController', function (
        $scope, $compile, $http, DTOptionsBuilder, DTColumnBuilder) {
        var vm = this;
        vm.dtOptions = DTOptionsBuilder.fromSource('/api/all')
            .withPaginationType('full_numbers');

        vm.dtColumns = [
            DTColumnBuilder.newColumn('id').withTitle('ID'),
            DTColumnBuilder.newColumn('names').withTitle('Nombres Apellidos'),
            DTColumnBuilder.newColumn('email').withTitle('Email'),
            DTColumnBuilder.newColumn('started').withTitle('Fecha Inicio'),
            DTColumnBuilder.newColumn('status').withTitle('Estado'),
            DTColumnBuilder.newColumn('id').withTitle('').renderWith(function(data, type, odata){
                return '<button class="mini positive ui button" onclick="javascrit:editItem('+odata.id+')">Edit</button>'
                + ' <a button class="mini negative ui button" onclick="javascrit:deleteItem('+odata.id+')">Delete</a>';


            })

        ];

    });


})(angular);


function editItem(id) {
    window.location.href='/crud/user/edit/'+id;
}

function deleteItem(id) {
    window.location.href='/crud/user/delete/'+id;
}

window.setTimeout(function(){
    $(".paginate_button").toggleClass('ui button');
    window.clearTimeout();
}, 5000);
