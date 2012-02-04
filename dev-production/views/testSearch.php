<?php
// checks if a search has been submitted
if(!empty($_REQUEST['search']))
{
  // the table to search
  $table = "yourTable";
  // explode search words into an array
  $arraySearch = explode(" ", $search);
  // table fields to search
  $arrayFields = array(0 => "title", 1 => "content");
  $countSearch = count($arraySearch);
  $a = 0;
  $b = 0;
  $query = "SELECT * FROM ".$table." WHERE (";
  $countFields = count($arrayFields);
  while ($a < $countFields)
  {
    while ($b < $countSearch)
    {
      $query = $query."$arrayFields[$a] LIKE '%$arraySearch[$b]%'";
      $b++;
      if ($b < $countSearch)
      {
        $query = $query." AND ";
      }
    }
    $b = 0;
    $a++;
    if ($a < $countFields)
    {
      $query = $query.") OR (";
    }
  }
  $query = $query.")";
  $query_result = mysql_query($query);
  // print title
  echo '<h1>Your Search Results</h1>'."\n\n";
  if(mysql_num_rows($query_result) < 1)
  {
    echo '<p>No matches found for "'.$search.'"</p>';
  }
  else
  {
    echo '<p>Search Results for "'.$search.'":</p>'."\n\n";
    // output list of articles
    while($row = mysql_fetch_assoc($query_result))
    {
      // output whatever you want here for each search result
      echo '<a href="index.php?id='.$row['id'].'">'.$row['title'].'</a><br />';
    }
  }
}
else
{
  // display a welcome page
}
?>

<p><form method="get">
  <input type="text" name="search" value="<?php echo $_REQUEST['search'] ?>" />
  <input type="submit" value="Search" />
</form></p>