<?php 
$i = 1;
?>
<style>
	table, th, td {

		border: 1px solid black
		border-style: solid;
		}
</style>
<table border="1" cellspacing="0" cellpadding="5" style="width :100%;">
 	<tr>
 		<th>No.</th>
 		<th>Tanggal</th>
 		<th>No Inv</th>
 		<th>Contact</th>
 		<th>No Faktur</th>
 		<th>Nominal</th>
 		<th>Pajak</th>
 	</tr>
<?php foreach($data as $als => $val) {?>
 	<tr>
 		<td><?= $i++; ?></td>
 		<td><?= $val["date"];  ?></td>
		<td><?= $val["title"];  ?></td>
		<td><?= $val["contact"];  ?></td>
		<td><?= $val["faktur"];  ?></td>
		<td><?= $val["nominal"];  ?></td>
		<td><?= $val["pajak"];  ?></td>
 	</tr>
<?php }?>
	<tr>
		<td colspan="5"><center>Total</center></td>
		<td>Rp.165.699.403</td>
		<td>Rp.18.226.935</td>
	</tr>
</table>
