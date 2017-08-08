<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
  ^
  + Project: 		JS Jobs
 * File Name:	views/jobseeker/tmpl/filters.php
  ^
 * Description: template view for filters
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php if ($this->jobconfig['subcategories'] == 1) { ?>
    <div id="js_main_wrapper" style="max-height:<?php echo $this->jobconfig['subcategoeis_max_hight']; ?>px;overflow:hidden;">
        <?php
        $noofcols = $this->jobconfig['subcategories_colsperrow'];
        $allcategories = $this->jobconfig['subcategories_all'];
        $colwidth = round(100 / $noofcols);
        if (isset($this->subcategories)) {
            foreach ($this->subcategories as $category) {
                if ($allcategories == 0) { // show only those categories who have jobs
                    if ($category->jobsinsubcat > 0)
                        $printrecord = 1;
                    else
                        $printrecord = 0;
                } else
                    $printrecord = 1;
                if ($printrecord == 1) {
                    $lnks = 'index.php?option=com_jsjobs&c=subcategory&view=subcategory&layout=list_subcategoryjobs&jobsubcat=' . clean($category->subcategoryaliasid) . '&Itemid=' . $this->Itemid;
                    $lnks = JRoute::_($lnks);
                    ?>
                    <span class="js_column_layout" style="width:<?php echo $colwidth - 2; ?>%;" ><a href="<?php echo $lnks; ?>" ><?php echo $category->title; ?> (<?php echo $category->jobsinsubcat; ?>)</a></span>
                    <?php
                }
            }
        }
        ?>
    </div>
<?php } 
    function clean($string) {
        $string = strip_tags($string, "");
        $string = preg_replace("/[@!*%^(){}?&$\\\\#\\/]+/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }    
?>
