<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.protostar
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$msgList = $displayData['msgList'];

$alert = array('error' => 'alert-error', 'warning' => '', 'notice' => 'alert-info', 'message' => 'alert-success');
?>
<div id="system-message-container">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<div id="system-message" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<?php foreach ($msgList as $type => $msgs) : ?>
				<div class="modal-dialog alert<?php echo $alert[$type]; ?>">
					<div class="modal-content">
				
						<?php if (!empty($msgs)) : ?>
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="alert-heading"><?php echo JText::_($type); ?></h4>
							</div>								
								<div class="modal-body">
									<?php foreach ($msgs as $msg) : ?>
										<p class="alert-message"><?php echo $msg; ?></p>
									<?php endforeach; ?>
								</div>
						<?php endif; ?>

					</div>
				</div>
			<?php endforeach; ?>
		</div>
<script type="text/javascript">
jQuery(document).ready(function($){
    $("#system-message").modal('show');
});
</script>
	<?php endif; ?>
</div>	