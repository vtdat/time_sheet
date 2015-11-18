<?php 

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\DatePicker;
use kartik\datecontrol\DateControl;
use yii\i18n\Formatter;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\models\Process;

use app\models\TeamMember;
=======
use frontend\models\Team;
use frontend\models\TeamMember;
use common\models\User;


$this->title = 'Create timesheet';

$formatter = Yii::$app->formatter;

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);

?>

<h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

<?php if(Yii::$app->session->hasFlash('CreateTimesheetFailed')) { ?>
<div class="alert alert-danger">
	Cannot create/update timesheet because it has been marked!
</div>
<?php } ?>

<?= Form::widget([
	'formName' => 'createForm',
	'form' => $form,
	'columns' => 6,
	'columnOptions' => ['colSpan' => 2],
	'autoGenerateColumns' => false,
	'attributes' => [
		'date' => [
			'type' => Form::INPUT_WIDGET,
			'widgetClass' => 'kartik\widgets\DatePicker',
			'options' => [
				'pluginOptions' => [
					'format' => 'yyyy-mm-dd',
					'autoClose' => true,
				],
				'removeButton' => false,
			],
		],
		'work_time' => [
			'type' => Form::INPUT_TEXT,
			'options' => ['placeholder' => 'Work time (hours)'],
		],
		'process_name' => [
			'type' => Form::INPUT_WIDGET,
			'widgetClass' => '\kartik\widgets\Select2',
			'options' => [
				'options' => ['placeholder' => 'Process',],
				'data' => ArrayHelper::map(Process::find()->orderBy('process_name')->asArray()->all(), 'process_name', 'process_name'),
			],
		],
		'team_name' => [
			'type' => Form::INPUT_WIDGET,
			'widgetClass' => '\kartik\widgets\Select2',
			'options' => [
				'options' => ['placeholder' => 'Team'],
				'data' => ArrayHelper::map(User::getUserTeams(Yii::$app->user->identity->id),'team_name','team_name'),
			],
		],
		'work_name' => [
			'type' => Form::INPUT_TEXT,
			'options' => ['placeholder' => 'Work details'],
		],
		'comment' => [
			'type' => Form::INPUT_TEXTAREA,
			'options' => [
				'placeholder' => 'Comment something',
			],
		],
	],
]); ?>

<div class="form-group">
	<?= Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>
	<?= Html::submitButton('Create',['class' => 'btn btn-success']) ?>	
</div>


<?php ActiveForm::end(); ?>