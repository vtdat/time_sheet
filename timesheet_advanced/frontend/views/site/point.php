<?php
use yii\helpers\Html;
use common\models\User;

$this->title = 'điểm trung bình trong 1 tháng';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    $result=[];
    $users=User::find()->all();
    foreach($users as $user){
        $result[$user->username]=User::calPoint($user->id,date('Y-m-d'));
    }
?>
<div class="container-fluid">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>
    <table class="table table-striped table-bordered">
        <tr>
            <th>Username</th>
            <th>Point</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <?php
        foreach($result as $username=>$point){
        ?>
        <tr>
            <td><?=$username?></td>
            <td><?=$point?></td>
        </tr>
        <?php  }?>   
</table>
</div>
