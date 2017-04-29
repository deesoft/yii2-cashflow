<div class="box box-solid" ng-repeat="team in $ctrl.teams">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user"></i> &nbsp; {{team.name}}</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-3" ng-repeat="book in team.books">
                <a ng-href="b/{{book.id}}">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h4>{{book.name}}</h4>
                            <p>&nbsp;{{book.description}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a uib-popover-template="'popover-new-book'" popover-title="New book">
                    <div class="small-box bg-gray">
                        <div class="inner">
                            <h4>&nbsp;Create book...</h4>
                            <p>&nbsp;</p>
                        </div>
                        <div class="icon"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php dee\angularjs\Template::begin(['id'=>'popover-new-book'])?>
<div>
    Test gan {{$ctrl.teams.length}}<br>
    Use widget
</div>
<?php dee\angularjs\Template::end()?>
