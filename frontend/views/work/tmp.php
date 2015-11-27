<?= Form::widget([
	'model' => $work->getTimesheet(),
	'form' => $form,
	'columns' => 3,
	'attributes' => [
		'date' => [
			'label' => 'Date', 
			'type' => Form::INPUT_WIDGET, 
			'widgetClass' => '\kartik\widgets\DatePicker',
			'widgetOptions' => ['convertFormat' => false, 'removeButton' => true],
		],
	],
]); ?>

<?= Form::widget([
	'model' => $work,
	'form' => $form,
	'columns' => 2,
	'attributes' => [
		'work_time' => [
			'label' => 'Work Time',
			'type' => Form::INPUT_TEXT,
			'options' => ['placeholder' => 'How long was your work?'],
		],
		'work_name' => [
			'label' => 'Work Details',
			'type' => Form::INPUT_TEXT,
			'options' => ['placeholder' => 'What did you do?'],
		],
		'comment' => [
			'label' => 'Comment',
			'type' => Form::INPUT_TEXTAREA,
		],
	],
]); ?>