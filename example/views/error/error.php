<?php $this->pageTitle = 'Error' ?>
<?php /** @var \Throwable $error */ ?>
<div class="container">
    <h2>Error</h2>
    <p><?= $error->getMessage() ?></p>
    <pre><?= $error->getTraceAsString() ?></pre>
</div>