<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
Yii::info('common layout');
//check if user is admin. Save the result in the $userIsAdmin variable
$authorizationMethods = Yii::$app->authorizationMethods;
$userIsAdmin = $authorizationMethods::isAdmin();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => 'Yii2 Self-Learning',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'AdminPanel', 'url' => ['/login/admin-panel/index'], 'visible' => !Yii::$app->user->isGuest && $userIsAdmin],
                    ['label' => 'Trips', 'url' => ['/trip/trip/index'], 'visible' => !Yii::$app->user->isGuest && !$userIsAdmin],
                    Yii::$app->user->isGuest ? (
                            ['label' => 'Login', 'url' => ['/login/login/index']]
                            ) : (
                            '<li>'
                            . Html::beginForm(['/login/login/logout'], 'post')
                            . Html::submitButton(
                                    'Logout (' . Yii::$app->user->identity->email . ')', ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm()
                            . '</li>'

                            ),
                ],
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
