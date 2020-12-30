<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Elixir Documentations</title>
  <link rel="stylesheet" href="css/uikit.min.css" />
  <script src="js/uikit.min.js"></script>
  <script src="js/uikit-icons.min.js"></script>
</head>
<body>
  <div class="uk-container uk-container-large">
  <h1 class="uk-text-center">Elixir Documentation</h1>

  <?PHP
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  function getFileList($dir, $recurse = FALSE, $depth = FALSE)
  {
    $retval = [];

    // add trailing slash if missing
    if(substr($dir, -1) != "/") {
      $dir .= "/";
    }

    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
    while(FALSE !== ($entry = $d->read())) {
      // skip hidden files
      if($entry{0} == ".") continue;
      if(is_dir("{$dir}{$entry}")) {
        $retval[] = [
          'name' => "{$dir}{$entry}/",
          'type' => filetype("{$dir}{$entry}"),
          'size' => 0,
          'lastmod' => filemtime("{$dir}{$entry}")
        ];
        if($recurse && is_readable("{$dir}{$entry}/")) {
          if($depth === FALSE) {
            $retval = array_merge($retval, getFileList("{$dir}{$entry}/", TRUE));
          } elseif($depth > 0) {
            $retval = array_merge($retval, getFileList("{$dir}{$entry}/", TRUE, $depth-1));
          }
        }
      } elseif(is_readable("{$dir}{$entry}")) {
        $retval[] = [
          'name' => "{$dir}{$entry}",
          'type' => mime_content_type("{$dir}{$entry}"),
          'size' => filesize("{$dir}{$entry}"),
          'lastmod' => filemtime("{$dir}{$entry}")
        ];
      }
    }
    $d->close();

    return $retval;
  }
?>
<?PHP
  $dirlist = getFileList("./hexpm", TRUE, 1);

  echo "<div uk-filter=\"target: .js-filter\">

    <ul>
        <li uk-filter-control=\"sort: data-color\"><a href=\"#\">Sort</a></li>
    </ul>";

    echo "<div class=\"uk-column-1-3\">";
    echo "<ul class=\"js-filter\">";

      foreach($dirlist as $file) {
        $match = preg_match('/\.\/hexpm\/\w+\/\d+\.\d+\.\d+\/$/', $file['name']);
        if(($file['type'] != 'dir') || !$match) {
          continue;
        }
        $str_disp = substr($file['name'], 8);
        $str_disp2 = substr($str_disp, 0, -1);
        $str_disp3 = str_replace("/", " - ", $str_disp2);
        echo "<li data-color=\"{$str_disp3}\"><a href=\"{$file['name']}\">",$str_disp3,"</a></li>\n";
      }
      echo "</ul>";
      echo "</div>";
  echo "</div>";
?>

</div>
</body>
</html>