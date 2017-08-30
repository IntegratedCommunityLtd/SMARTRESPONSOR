<?php
defined('_JEXEC') or die('Restricted access');

	if(!class_exists('JFile')) 
		require(VMPATH_LIBS.DS.'joomla'.DS.'filesystem'.DS.'file.php');
	if (!class_exists ('VirtueMartModelUkcpu_user')) 
	{
		$fileClassHelper = JPATH_PLUGINS	. DS . 'vmextended'. DS .'ukcpu_ext' .DS. 'models'.DS.'ukcpu_user.php' ; 
		if( JFile::exists( $fileClassHelper  ) )
		{
			  require( $fileClassHelper );
			  $ModelUkcpu_user = new VirtueMartModelUkcpu_user () ;
		}
		else
		{
			$mes  = '<br/>'.vmText::sprintf('Необходим плагин <b>%1$s</b>' ,  'vmextended/ukcpu_ext' );
			JFactory::getApplication()->enqueueMessage( $mes , 'error');
			return false  ;
		} // end if
	} // end if
	
	$doc = JFactory::getDocument();
	$doc->addScript('/js/pasportBallans.js', 'text/javascript'); 
	$doc->addStyleSheet( '/css/pasportBallans.css' );
	
	$user = JFactory::getUser();
	if (!in_array( 12 , $user->groups)) { return false  ; }
	
	$Ballans = $ModelUkcpu_user->getUserBallans() ; 
	if( !$Ballans ){ ?>
		<button class="btn btn-primary" type="submit" onclick="jQuery('#bonusModal').modal('show') ">
        	<span class="glyphicon glyphicon-floppy-save"></span>
           	<span class="hidden-xs"><?= JText::_('Получить Бонус') ?></span>
        </button>
        
        <div id="bonusModal" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align: center;"><?= JText::_('Сколько денег нужно?') ?></h4>
              </div>
              <div class="modal-body">
					<form action="#">
                    	<input type="text" name="summa" maxlength="12"   style="border:1px solid #CCCCCC; text-align: center;" />
                        <input type="hidden" name="user_id" value="<?= $user->id ?>" />
                        <input type="hidden" name="task" value="addBallansJs" />
                        <?php echo JHTML::_( 'form.token' ); ?>
                    </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
	<?php
	} // end if
?>
 
 

