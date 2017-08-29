<h1><?=$this->e($title)?></h1>
<h2>Friends</h2>
<ul>
    <?php foreach($friends as $friend): ?>
        <li>
            <?=$this->e($friend['name'])?>
        </li>
    <?php endforeach ?>
</ul>