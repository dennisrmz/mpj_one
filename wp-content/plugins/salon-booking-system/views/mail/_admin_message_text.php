<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:bold;font-family: 'Avenir-Medium',sans-serif,arial;padding: 10px 0 0 8px;">
	<?php echo "Administrador"; ?>,
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="5" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>
<tr>
    <td align="left" valign="top" style="font-size:16px;line-height:24px;color:#4d4d4d;font-weight:500;font-family: 'Avenir-Medium',sans-serif,arial;padding: 0 0 0 8px;" class="font1">
	<?php echo "esta es una notificación por correo electrónico de una nueva reserva"; ?>
	<?php
	    $_text = apply_filters('sln.new_booking.notifications.email.body.title', '', $booking);
	    $_text = $_text ? $_text : _e(' at ', 'salon-booking-system') . $plugin->getSettings()->getSalonName();
	?>
	<?php echo $_text ?>,
	<?php echo "por favor tome nota de los siguientes detalles de reserva"; ?>.
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="22" style="font-size:1px;line-height:1px;">&nbsp;</td>
</tr>