<?php
$pageTitle = 'ページタイトルが入る';
$pageDescription = 'ページの説明が入る';
?>

<?php require_once INCLUDE_APP_BASE_DIR . '/view/layout/header.php'; ?>
<?php /* START CONTENTS */ ?>
<div class="wrap">
    <h1>WSTフレームワーク</h1>
    <p>Hello World.</p>
    <p>このフレームワークは、WebStudioTANIが開発したPHPの簡易フレームワークです。MITライセンスですので、ご自由にお使いくださいませ。</p>
    <p>ただし、本フレームワークを利用することで発生した損失・損害・事故などいかなるトラブル・問題は一切責任を負いません。あらかじめご了承ください。</p>
    <h2><?php //echo $F->h($X['tests'][0]['name']); ?></h2>
</div>

<?php /* END CONTENTS */ ?>
<?php require_once INCLUDE_APP_BASE_DIR . '/view/layout/footer.php'; ?>