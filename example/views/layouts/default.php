<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $this->pageTitle ?> / PVC Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="/css/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/css/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="/css/grids-responsive-min.css">
    <!--<![endif]-->
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="container">
    <div class="pure-g header">
        <div class="pure-u-1-3">
            <a class="pure-menu-heading" href="<?= $this->url(['/']) ?>">PVC<span>Example</span></a>
        </div>
        <div class="pure-u-2-3">
            <div class="pure-menu pure-menu-horizontal" style="text-align:right">
                <ul class="pure-menu-list">
                    <li class="pure-menu-item">
                        <a class="pure-menu-link"
                           href="<?= $this->url(['index/index']) ?>">Home</a></li>
                    <li class="pure-menu-item">
                        <a class="pure-menu-link" href="<?= $this->url(['index/features']) ?>">Features</a>
                    </li>
                    <li class="pure-menu-item">
                        <a class="pure-menu-link"
                           href="<?= $this->url(['index/contact', 'a' => 1, 'b' => 2, '#' => 'anchor']) ?>">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $content ?>
<div class="footer">
    <div class="container">
        <div class="pure-g">
            <div class="pure-u-1 pure-u-md-3-5 footer-about">
                <h3 class="footer-about__heading">About PVC Example</h3>
                <p class="footer-about__text">
                    A simple example project based on PVC, a minimal [M]VC framework.
                    Just for my own experimentation and without any expectations.
                </p>
            </div>
            <div class="pure-u-1 pure-u-md-1-5 footer-links">
                <h3 class="footer-links__heading">Links</h3>
                <ul class="footer-links__list">
                    <li><a target="_blank" href="http://www.tebe.ch">About me</a></li>
                </ul>
            </div>
            <div class="pure-u-1 pure-u-md-1-5 footer-tools">
                <h3 class="footer-tools__heading">Toolset</h3>
                <ul class="footer-tools__list">
                    <li><a target="_blank" href="https://purecss.io">Pure.css</a></li>
                    <li><a target="_blank" href="https://github.com/tbreuss/pvc">PVC Framework</a></li>
                </ul>
            </div>
        </div>
        <hr class="footer-ruler">
        <div class="pure-g">
            <div class="pure-u-1 footer-copyright">
                A tiny <a target="_blank" href="https://www.tebe.ch">tebe.ch</a> project
            </div>
        </div>
    </div>
</div>
</body>
</html>