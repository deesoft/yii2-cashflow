<div>
    <input ng-model="x"> * <input ng-model="y"> = {{x * y}}
</div>
<script>
function($scope) {
    $scope.x = 1/3;
    $scope.y = 3;
}
</script>