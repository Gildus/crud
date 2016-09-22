(function(angular) {

    'use strict';
    var formApp = angular.module('abmApp', []).config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }).config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }]);

    formApp.controller('formController', formController);

    function formController($scope, $http, $log) {

        $scope.formData = {};
        $scope.processForm = function(e) {
            e.preventDefault();

            var form = angular.element(e.target);

            $http({
                method  : form.attr("method"),
                url     : form.attr("action"),
                data    : $.param($scope.formData),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }

            })
            .success(function(data) {
                if (!data.success) {

                    $('p#messages').parent()
                        .removeClass("hidden")
                        .addClass("ui message visible error");

                    $scope.message = data.error;

                } else {
                    $('p#messages').parent()
                        .removeClass("hidden")
                        .addClass("ui message visible success");

                    $scope.message = 'Success!!!';

                    window.location.href =  '/crud';

                }
            });
            return true;
        };

        if (typeof populateDataForm!= 'undefined') {
            populateDataForm($scope);
        }


    }


})(angular);
