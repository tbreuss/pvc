<?php $this->pageTitle = 'Error 404' ?>
<?php /** @var \Throwable $error */ ?>
<div class="container">
    <h2>Error 404</h2>
    <p><?= $error->getMessage() ?></p>
    <pre><?= $error->getTraceAsString() ?></pre>
</div>