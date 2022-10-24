<?php foreach ($rows as $row) { ?>
    <li>
        <?= anchor('services/service/'.$row->url_string,  $row->service_name ) ?>
    </li>
<?php } ?>