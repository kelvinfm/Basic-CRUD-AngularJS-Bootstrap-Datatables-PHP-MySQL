/**
*** Description: Simple CRUD using AngularJS, Bootstrap, Datatables, PHP and MariaDB/MySQL
*** 
*** Author: Kelvin Fernandez
*** Date: 2019-04-04
*** email: kelvinfm@yahoo.com
*** github: https://github.com/kelvinfm
**/


var app = angular.module('appProperty', ['datatables']);

app.controller('ctrlProperty', function($scope, $http){
	
	$scope.new = function(){
		$scope.isUpdate = false;
		$scope.isCreate = true;
		$scope.isShowId = false;
		$scope.modal_title = 'New';
		angular.element('#modalProperty').modal('show');
		$scope.initFields();
	}

	$scope.findall = function(){
		$http.get('books.php?method=All').then(
			function successCallback(response) {
				console.log(response.data);
				$scope.rows = response.data;
			}, 
			function errorCallback(response) {
				alert('ERROR FINDALL');
			}
		);
	}

	$scope.find = function(id){
		$scope.isUpdate = true;
		$scope.isCreate = false;
		$scope.isShowId = true;
		$scope.modal_title = 'Edit';
		angular.element('#modalProperty').modal('show');
		$http.get('books.php?method=Find&id='+id).then(
			function successCallback(response) {
				console.log(response.data);
				$scope.setFields(response.data);
			}, 
			function errorCallback(response) {
				alert('ERROR FIND');
			}
		);
	}
	
	$scope.delete = function(id){
		if(confirm('Are you sure you want to remove it ('+id+')?')) {
			$http.delete('books.php?method=Delete&id='+id).then(
				function successCallback(response) {
					console.log(response.data);
					$scope.findall();
					alert(response.data.msg);
				}, 
				function errorCallback(response) {
					alert('ERROR DELETE');
				}
			);
		}
	}

	$scope.create = function(){
		$http.post('books.php?method=Create', $scope.request()).then(
			function successCallback(response) {
				angular.element('#modalProperty').modal('hide');
				console.log(response.data);
				$scope.findall();
				alert(response.data.msg);
			}, 
			function errorCallback(response) {
				alert('ERROR CREATE');
			}
		);
	}

	$scope.update = function(){
		$http.put('books.php?method=Update&id='+$scope.id, $scope.request()).then(
			function successCallback(response) {
				angular.element('#modalProperty').modal('hide');
				console.log(response.data);
				$scope.findall();
				alert(response.data.msg);
			}, 
			function errorCallback(response) {
				alert('ERROR UPDATE');
			}
		);
	}

	$scope.request = function() {
		return {
			title       : $scope.title, 
			description : $scope.description, 
			author      : $scope.author, 
			category    : $scope.category, 
			language    : $scope.language, 
			country     : $scope.country,
			status      : $scope.status,
			date        : $scope.date
		}
	}

	$scope.initFields = function(){
		$scope.title       = '';
		$scope.description = '';
		$scope.author      = '';
		$scope.category    = '';
		$scope.language    = '';
		$scope.country     = '';
		$scope.status      = 'Active'; 
		$scope.date        = $scope.getDate();
	}
	
	$scope.setFields = function(data){
		$scope.id          = data.id;
		$scope.title       = data.title;
		$scope.description = data.description;
		$scope.author      = data.author;
		$scope.category    = data.category;
		$scope.language    = data.language;
		$scope.country     = data.country;
		$scope.status      = data.status;
		$scope.date        = (data.date==null) ? $scope.getDate() : data.date;
	}

	$scope.getDate = function() {
		var newdate = new Date();
		var resp = newdate.getFullYear() + '-' + ('0' + (newdate.getMonth() + 1)).slice(-2) + '-' + ('0' + newdate.getDate()).slice(-2);
		return resp;
	}
	
});
