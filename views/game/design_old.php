<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
$base = Url::base();
$strSet = json_encode($set);

$js = <<< END

var Design = new RBEDesign("$base", $User->id); 
Design.set($strSet);
Design.init();
        
END;

$this->registerJsFile('@web/js/vendor/knockout-3.3.0.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/vendor/pixi.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/rbe-game/design.old.js', ['position' => \yii\web\View::POS_END, 'depends' => yii\jui\JuiAsset::className()]);
$this->registerJs($js, \yii\web\View::POS_END);
$this->title = 'EconoSim';
?>


<div id="panels" class="hidden">
    
    <div id="panel-roads" data-build="road" class="build-panel panel panel-default">
        <div class="panel-heading">
            Roads
            <div class="pull-right panel-actions">
                <a href="#" class="panel-close"  data-bind="click: hidePanel.bind($data, 'panel-roads')">
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="sr-only">Close</span>
                </a>
            </div>
        </div>
        <div class="panel-body">

            <h4>Type</h4>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="radio" value="straight" name="roadType" data-bind="bsChecked: roadType"> Straight
                </label>
                <label class="btn btn-default">
                    <input type="radio" value="arc" name="roadType" data-bind="bsChecked: roadType"> Arc
                </label>
            </div>

            <h4>Lanes</h4>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="radio" value="1" name="roadSize" data-bind="bsChecked: roadSize" /> 2
                </label>
                <label class="btn btn-default">
                    <input type="radio" value="2" name="roadSize" data-bind="bsChecked: roadSize" /> 4
                </label>
                <label class="btn btn-default">
                    <input type="radio" value="3" name="roadSize" data-bind="bsChecked: roadSize" /> 6
                </label>
            </div>

            <!--
            <div class="col-md-4">
                <h4>Roads List</h4>
                <div class="overflow-y">
                    <ol data-bind="foreach: roads">
                        <li>
                            <a href="#" data-bind="click: $parent.selectObject, event: { mouseover: $parent.hoverObject, mouseout: $parent.hoverOut }"><span data-bind="text: properties.distance().toFixed(1)"></span>m (<span data-bind="text: properties.size"></span> lanes)</a>
                            <a href="#" class="text-danger fa fa-trash" data-bind="click: $parent.deleteObject"></a>
                        </li>
                    </ol>
                </div>
            </div>
            -->

            <hr />

        </div>
    </div>
    
    <!-- Messages Panel -->
    <div id="messages" class="row">
        <div class="col-md-4 col-md-offset-4">
            <div data-bind="foreach: errorMessages">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span data-bind="text: message"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="panel-buildings" data-build="building" class="build-panel panel panel-default" style="min-width: 800px">
        <div class="panel-heading">
            Buildings
            <div class="pull-right panel-actions">
                <a href="#" class="panel-close"  data-bind="click: hidePanel.bind($data, 'panel-buildings')">
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="sr-only">Close</span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            
            <div class="row">
                <div class="col-md-6">
                    
                    <h4>Select</h4>
                    
                    <!-- ko if: set().length > 0 -->
                    <div class="form-group">
                        <div data-bind="foreachprop: set().buildings" class="btn-group">
                            <a data-bind="attr: {href: '#'+key}" class="btn btn-default" data-toggle="tab">
                                <span data-bind="text: key"></span>
                            </a>
                        </div>
                    </div>

                    <div data-bind="foreachprop: set().buildings" class="tab-content">
                        <div data-bind="attr: {id: key}" class="tab-pane">
                            <!-- <h5 data-bind="text: key"></h5>-->
                            <ul class="nav nav-pills nav-stacked" data-bind="foreach: value">
                                <li><a href="#" data-bind="text: name, click: $root.selectBuilding"></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /ko -->
                    
                </div>
                <div class="col-md-6">
                    <h4>Properties</h4>

                    <script id="recursivePropertiesTemplate" type="text/html">
                        
                        <!-- ko if: typeof $data === 'object' && !Array.isArray($data) -->
                        <table data-bind="attr:{'class': $parent instanceof RBEDesign ? 'table' : null}">
                            <tbody data-bind="foreachprop: $data">
                                <tr>
                                    <th data-bind="text: key"></th>
                                    <!-- ko if: typeof value === 'string' || typeof value === 'number' -->
                                    <td data-bind="text: value">></td>
                                    <!-- /ko -->
                                    <td>
                                        <!-- ko template: { name: 'recursivePropertiesTemplate',  data: value } -->
                                        <!-- /ko -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /ko -->
                        
                        <!-- ko if: Array.isArray($data) -->
                        <table>
                            <tbody data-bind="foreach: $data">
                                <!-- ko foreachprop: $data -->
                                <tr>
                                    <th data-bind="text: key"></th>
                                    <td data-bind="text: value"></td>
                                </tr>
                                <!-- /ko -->
                            </tbody>
                        </table>
                        <!-- /ko -->
                        
                    </script>    
                    
                    <div data-bind="if: selectedBuilding()" class="properties">
                        <div data-bind="template: { name: 'recursivePropertiesTemplate', data: selectedBuilding() }"></div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    
</div>
