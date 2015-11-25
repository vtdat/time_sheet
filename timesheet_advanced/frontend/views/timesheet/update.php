<?php

use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;

use frontend\models\Process;
use frontend\models\Team;
use frontend\models\TeamMember;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Timesheet */

$formatter = Yii::$app->formatter;

$this->title = $formatter->asDate($model->date,'medium');


?>
<div class="timesheet-update">

<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
<br /><br /><br />
<?php 

$form = ActiveForm::begin();

echo TabularForm::widget([
	'form' => $form,
	'dataProvider' => $dataProvider,
	'attributes' => [
		'id' => [
			'type' => TabularForm::INPUT_TEXT,
			'columnOptions' => [
				'hidden' => true,
			],
		],
		'work_time' => [
			'type' => TabularForm::INPUT_TEXT,
			'label' => 'Work Time (hours)',
			'columnOptions' => [
				'width' => '3em',
			],
		],
		'process_id' => [
			'type' => TabularForm::INPUT_WIDGET,
			'widgetClass' => 'kartik\widgets\Select2',
			'options' => [
				'data' => ArrayHelper::map(Process::find()->orderBy('process_name')->asArray()->all(),'id','process_name'),
			],
			'label' => 'Process',
			'columnOptions' => [
				'hAlign' => 'center',
				'width' => '10em',
			],
		],
		'team_id' => [
			'type' => TabularForm::INPUT_WIDGET,
			'widgetClass' => 'kartik\widgets\Select2',
			'label' => 'Team',
			'options' => [
				'data' => ArrayHelper::map(User::getUserTeams(Yii::$app->user->identity->id),'id','team_name'),
			],
			'columnOptions' => [
				'hAlign' => 'center',
			],
		],
		'work_name' => [
			'type' => TabularForm::INPUT_TEXT,
			'label' => 'Work Details',
			'columnOptions' => [
				'hAlign' => 'center',
				'width' => '15em',
			],
		],
		'comment' => [
			'type' => TabularForm::INPUT_TEXTAREA,
			'label' => 'Comment',
			'columnOptions' => [
				'hAlign' => 'center',
			],
		],
	],
	'actionColumn' => [
		'template' => '{delete}',
		'controller' => 'work',
	],
	'checkboxColumn' => false,
]);

?>

<div class="form-group" style="text-align: right; margin-top: 20px;">
	<?= Html::a('Add row <span class="glyphicon glyphicon-plus"></span>',
		'',
		['class' => 'btn btn-primary']) ?>
	<?= Html::a('Delete all <span class="glyphicon glyphicon-trash"></span>', 
		Url::to(['timesheet/delete', 'id' => $model['id']]), 
		['class' => 'btn btn-danger', 'data-method' => 'POST', 'data-confirm' => 'Are you sure to delete this timesheet?']) ?>
	<?= Html::submitButton('Save <span class="glyphicon glyphicon-ok"></span>', 
		['class' => 'btn btn-success']) ?>
</div>

<?php

ActiveForm::end();

?>

</div>
