<?php
defined('_JEXEC') or die('Restricted access');
//JHTML::_( 'behavior.modal' );
?>
<?php
# load categories from front_categories if exist
if ($this->categories and VmConfig::get('show_categories', 1)) echo $this->renderVmSubLayout('categories',array('categories'=>$this->categories));

# Show template for : topten,Featured, Latest Products if selected in config BE
if (!empty($this->products) ) {
//	$products_per_row = VmConfig::get ( 'homepage_products_per_row', 3 ) ;
//	echo $this->renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$products_per_row,'showRating'=>$this->showRating)); //$this->loadTemplate('products');
	echo $this->renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'showRating'=>$this->showRating)); //$this->loadTemplate('products');
}

?>
</div>
