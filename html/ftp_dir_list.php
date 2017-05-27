<?php 
if ($list):
    foreach($list as $ele) : 
?>
        <tr>
            <td><?= $ele ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td>No Files</td>
    </tr>
<?php endif; ?>
