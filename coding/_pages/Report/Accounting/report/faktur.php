<?php
var_dump($data[0]);

$all =[
		['1','20 Mei 2022','0066/INV/SAI/05/22','0','Rp.0','Rp.0'],
		['2','25 Mei 2022','0048/INV/SAI/05/22','PT. GUDANG GARAM Tbk','Rp.52.802.750','Rp.5.808.303'],
		['3','18 Mei 2022','0051/INV/SAI/05/22','Universitas Muhammadiyah Gombong - Prodi. Teknik Industri','Rp.29.830.000','Rp.3.281.300'],
		['4','10 Mei 2022','0056/INV/SAI/05/22','Rp.1.250.000','Rp.137.500'],
		['5','12 Mei 2022','0057/INV/SAI/05/22','Axiom Space - Aerospace','Rp.6.175.000','Rp.679.250']
	];
?>

<h1>DAFTAR INVOICE</h1>

<?php foreach($data as $als) : ?>
<ul>	
	<li><?= $als["name_comp"];  ?></li>
	<li><?= $als["phone_no_comp"];  ?></li>
	<li><?= $als["title_reff"];  ?></li>
	<li><?= $als["company_reff"];  ?></li>
	<li><?php echo $als["inserted_reff"];  ?></li>
</ul>
<?php endforeach; ?>

