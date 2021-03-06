<?php
namespace App\Helpers;

// A class containing functions for computing diffs and formatting the output.
class CompareDiff{

  // define the constants
  const UNMODIFIED = 0;
  const DELETED    = 1;
  const INSERTED   = 2;

  /* Returns the diff for two strings. The return value is an array, each of
   * $string1           - the first string
   * $string2           - the second string
   * $compareCharacters - true to compare characters, and false to compare
   *                      lines; this optional parameter defaults to false
   */
  public static function compare(
      $string1, $string2, $compareCharacters = false){

    // initialise the sequences and comparison start and end positions
    $start = 0;
    if ($compareCharacters){
      $sequence1 = $string1;
      $sequence2 = $string2;
      $end1 = strlen($string1) - 1;
      $end2 = strlen($string2) - 1;
    }else{
      $sequence1 = preg_split('/\R/', $string1);
      $sequence2 = preg_split('/\R/', $string2);
      $end1 = count($sequence1) - 1;
      $end2 = count($sequence2) - 1;
    }

    // skip any common prefix
    while ($start <= $end1 && $start <= $end2
        && $sequence1[$start] == $sequence2[$start]){
      $start ++;
    }

    // skip any common suffix
    while ($end1 >= $start && $end2 >= $start
        && $sequence1[$end1] == $sequence2[$end2]){
      $end1 --;
      $end2 --;
    }

    // compute the table of longest common subsequence lengths
    $table = self::computeTable($sequence1, $sequence2, $start, $end1, $end2);

    // generate the partial diff
    $partialDiff =
        self::generatePartialDiff($table, $sequence1, $sequence2, $start);

    // generate the full diff
    $diff = array();
    for ($index = 0; $index < $start; $index ++){
      $diff[] = array($sequence1[$index], self::UNMODIFIED);
    }
    while (count($partialDiff) > 0) $diff[] = array_pop($partialDiff);
    for ($index = $end1 + 1;
        $index < ($compareCharacters ? strlen($sequence1) : count($sequence1));
        $index ++){
      $diff[] = array($sequence1[$index], self::UNMODIFIED);
    }

    // return the diff
    return $diff;

  }

  /* Returns the diff for two files. The parameters are:
   *
   * $file1             - the path to the first file
   * $file2             - the path to the second file
   * $compareCharacters - true to compare characters, and false to compare
   *                      lines; this optional parameter defaults to false
   */
  public static function compareFiles(
      $file1, $file2, $compareCharacters = false){

    // return the diff of the files
    return self::compare(
        file_get_contents($file1),
        file_get_contents($file2),
        $compareCharacters);

  }

  /* Returns the table of longest common subsequence lengths for the specified
   * sequences. The parameters are:
   *
   * $sequence1 - the first sequence
   * $sequence2 - the second sequence
   * $start     - the starting index
   * $end1      - the ending index for the first sequence
   * $end2      - the ending index for the second sequence
   */
  private static function computeTable(
      $sequence1, $sequence2, $start, $end1, $end2){

    // determine the lengths to be compared
    $length1 = $end1 - $start + 1;
    $length2 = $end2 - $start + 1;

    // initialise the table
    $table = array(array_fill(0, $length2 + 1, 0));

    // loop over the rows
    for ($index1 = 1; $index1 <= $length1; $index1 ++){

      // create the new row
      $table[$index1] = array(0);

      // loop over the columns
      for ($index2 = 1; $index2 <= $length2; $index2 ++){

        // store the longest common subsequence length
        if ($sequence1[$index1 + $start - 1]
            == $sequence2[$index2 + $start - 1]){
          $table[$index1][$index2] = $table[$index1 - 1][$index2 - 1] + 1;
        }else{
          $table[$index1][$index2] =
              max($table[$index1 - 1][$index2], $table[$index1][$index2 - 1]);
        }

      }
    }

    // return the table
    return $table;

  }

  /* Returns the partial diff for the specificed sequences, in reverse order.
   * The parameters are:
   *
   * $table     - the table returned by the computeTable function
   * $sequence1 - the first sequence
   * $sequence2 - the second sequence
   * $start     - the starting index
   */
  private static function generatePartialDiff(
      $table, $sequence1, $sequence2, $start){

    //  initialise the diff
    $diff = array();

    // initialise the indices
    $index1 = count($table) - 1;
    $index2 = count($table[0]) - 1;

    // loop until there are no items remaining in either sequence
    while ($index1 > 0 || $index2 > 0){

      // check what has happened to the items at these indices
      if ($index1 > 0 && $index2 > 0
          && $sequence1[$index1 + $start - 1]
              == $sequence2[$index2 + $start - 1]){

        // update the diff and the indices
        $diff[] = array($sequence1[$index1 + $start - 1], self::UNMODIFIED);
        $index1 --;
        $index2 --;

      }elseif ($index2 > 0
          && $table[$index1][$index2] == $table[$index1][$index2 - 1]){

        // update the diff and the indices
        $diff[] = array($sequence2[$index2 + $start - 1], self::INSERTED);
        $index2 --;

      }else{

        // update the diff and the indices
        $diff[] = array($sequence1[$index1 + $start - 1], self::DELETED);
        $index1 --;

      }

    }

    // return the diff
    return $diff;

  }

  /* Returns a diff as an HTML table. The parameters are:
   *
   * $diff        - the diff array
   * $indentation - indentation to add to every line of the generated HTML; this
   *                optional parameter defaults to ''
   * $separator   - the separator between lines; this optional parameter
   *                defaults to '<br>'
   */
  public static function toTable($diff, $indentation = '', $separator = '<br>'){

    // initialise the HTML
    $html = $indentation . "<table class=\"diff\">\n";

    // loop over the lines in the diff
    $index = 0;
    while ($index < count($diff)){

      // determine the line type
      switch ($diff[$index][1]){

        // display the content on the left and right
        case self::UNMODIFIED:
          $leftCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::UNMODIFIED);
          $rightCell = $leftCell;
          break;

        // display the deleted on the left and inserted content on the right
        case self::DELETED:
          $leftCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::DELETED);
          $rightCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::INSERTED);
          break;

        // display the inserted content on the right
        case self::INSERTED:
          $leftCell = '';
          $rightCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::INSERTED);
          break;

      }

      // extend the HTML with the new row
      $html .=
          $indentation
          . "  <tr>\n"
          . $indentation
          . '    <td class="diff'
          . ($leftCell == $rightCell
              ? 'Unmodified'
              : ($leftCell == '' ? 'Blank' : 'Deleted'))
          . '">'
          . $leftCell
          . "</td>\n"
          . $indentation
          . '    <td class="diff'
          . ($leftCell == $rightCell
              ? 'Unmodified'
              : ($rightCell == '' ? 'Blank' : 'Inserted'))
          . '">'
          . $rightCell
          . "</td>\n"
          . $indentation
          . "  </tr>\n";

    }

    // return the HTML
    return $html . $indentation . "</table>\n";

  }

  public static function toTableAndCount($diff, $indentation = '', $separator = '<br>'){
    $similar = 0;
    $difference = 0;

    // initialise the HTML
    $html = $indentation . "<table class=\"diff\">\n";

    // loop over the lines in the diff
    $index = 0;
    while ($index < count($diff)){

      // determine the line type
      switch ($diff[$index][1]){

        // display the content on the left and right
        case self::UNMODIFIED:
          $similar++;
          $leftCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::UNMODIFIED);
          $rightCell = $leftCell;
          break;

        // display the deleted on the left and inserted content on the right
        case self::DELETED:
          $difference++;
          $leftCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::DELETED);
          $rightCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::INSERTED);
          break;

        // display the inserted content on the right
        case self::INSERTED:
          $difference++;
          $leftCell = '';
          $rightCell =
              self::getCellContent(
                  $diff, $indentation, $separator, $index, self::INSERTED);
          break;

      }

      // extend the HTML with the new row
      $html .=
          $indentation
          . "  <tr>\n"
          . $indentation
          . '    <td class="diff'
          . ($leftCell == $rightCell
              ? 'Unmodified'
              : ($leftCell == '' ? 'Blank' : 'Deleted'))
          . '">'
          . $leftCell
          . "</td>\n"
          . $indentation
          . '    <td class="diff'
          . ($leftCell == $rightCell
              ? 'Unmodified'
              : ($rightCell == '' ? 'Blank' : 'Inserted'))
          . '">'
          . $rightCell
          . "</td>\n"
          . $indentation
          . "  </tr>\n";

    }

    // return the HTML
    return [
      'similar'=>$similar,
      'difference'=>$difference,
      'table'=>$html . $indentation . "</table>\n"
    ];

  }

  /* Returns the content of the cell, for use in the toTable function. The
   * parameters are:
   *
   * $diff        - the diff array
   * $indentation - indentation to add to every line of the generated HTML
   * $separator   - the separator between lines
   * $index       - the current index, passes by reference
   * $type        - the type of line
   */
  private static function getCellContent(
      $diff, $indentation, $separator, &$index, $type){

    // initialise the HTML
    $html = '';

    // loop over the matching lines, adding them to the HTML
    while ($index < count($diff) && $diff[$index][1] == $type){
      if($type == self::INSERTED)
      {
          $class = 'bg-success';
      }else if($type == self::DELETED){
        $class = 'bg-danger';
      }else{
        $class = '';
      }

      $html .=
          '<span class='.$class.'>'
          . htmlspecialchars($diff[$index][0])
          . '</span>'
          . $separator;
      $index ++;
    }

    // return the HTML
    return $html;

  }

}

?>
