<div class="clearfix"></div>
<?php
if ($Set) {
    $cols = array(
        'pid' => CMS\MATERIAL_TYPE, 
        'name' => NAME, 
        'urn' => CMS\URN, 
        'meta_title' => CMS\META_TITLE, 
        'meta_keywords' => CMS\META_KEYWORDS, 
        'meta_description' => CMS\META_DESCRIPTION
    );
    ?>
    <p>
      <strong><?php echo CMS\META_CHECKER\ORDER_BY?>:</strong>
      <?php
      foreach ($cols as $key => $val) { 
          $temp = $url . '&sort=' . $key . '&order=' . ($sort == $key ? ($order == -1 ? 'asc' : 'desc') : '');
          if ($sort == $key) { 
              ?>
              <strong><a href="<?php echo htmlspecialchars($temp)?>"><?php echo $val . ' ' . ($order == -1 ? '&#9660;' : '&#9650;')?></a></strong>
          <?php } else { ?>
              <a href="<?php echo htmlspecialchars($temp)?>"><?php echo $val?></a>
          <?php } ?>
          &nbsp;&nbsp;
      <?php } ?>
    </p>
    <table class="table table-striped">
      <tbody>
        <?php foreach ($Set as $row) { ?>
            <tr <?php echo $row['counter'] ? ' class="error"' : ''?>>
              <td>
                <a name="<?php echo (int)$row['pid']?>.<?php echo (int)$row['id']?>"></a>
                <?php foreach ($cols as $key => $val) { ?>
                    <p>
                      <?php if ($row[$key . '_counter'] > 0) { ?>
                          <a href="#<?php echo $row[$key . '_href']?>" class="text-error"><strong ><?php echo $val?> (<?php echo (int)$row[$key . '_counter'] + 1?>): </strong></a>
                      <?php } else { ?>
                          <strong><?php echo $val?>: </strong> 
                      <?php 
                      } 
                      switch ($key) {
                          case 'pid':
                              if ((int)$row['pid']) {
                                  $temp = new \RAAS\CMS\Material_Type((int)$row['pid']);
                                  echo htmlspecialchars($temp->name);
                              } else {
                                  echo CMS\PAGE;
                              }
                              break;
                          case 'name': case 'urn':
                              ?>
                              <a href="?p=cms&action=edit_<?php echo (int)$row['pid'] ? 'material' : 'page'?>&id=<?php echo (int)$row['id']?>" target="_blank">
                                <?php echo htmlspecialchars($row[$key])?>
                              </a>
                              <?php
                              break;
                          default:
                              echo htmlspecialchars($row[$key]);
                              break;
                      }
                      ?>
                    </p>
                <?php } ?>
              </td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php
} else {
    echo CMS\META_CHECKER\NO_MATERIALS;
}
if ($Set && $Pages) { 
    include $VIEW->tmp('/pages.tmp.php');
}