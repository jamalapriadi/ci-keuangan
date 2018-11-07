<script type="text/javascript">
$(document).ready(function(){
	$(':input:not([type="submit"])').each(function() {
		$(this).focus(function() {
			$(this).addClass('hilite');
		}).blur(function() {
			$(this).removeClass('hilite');});
	});	
	
	$("#no_po").keyup(function(e){
		var isi = $(e.target).val();
		$(e.target).val(isi.toUpperCase());
		CariAnggota(isi);
	});
	
	$(".angka").keypress(function(data){
		if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) {
			return false;
		}
	});
	
	$("#view").show();
	$("#form").hide();
	
	$("#tambah").click(function(){
		$("#view").hide();
		$("#form").show();
		kosong();
		$("#no_po").focus();
		tampil_data();
		//$("#tampil_data").html('hai...');
	});
	
	function kosong(){
		//$(".kosong").val('');
		//$(".angka").val('');
		$("#detail_pesanan").val('');
		$("#ukuran").val('');
		$("#jumlah").val('');
		$("#no_rek").val('');
		$("#harga").val('');
		$("#ppn").val('');
		$("#total").val('');
	}
	
	$("#no_po").keyup(function(){
		CariNoPo();
	});
	
	$("#no_po").change(function(){
		CariNoPo();
	});
	
	function CariNoPo(){
		
		var no_po = $("#no_po").val();
		var string = "no_po="+no_po;
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/ref_json/CariNamaClient",
			data	: string,
			cache	: false,
			dataType: "json",
			success	: function(data){
				$("#id_client").val(data.id_client);
				$("#nama_client").val(data.nama_client);
				$("#tgl_po").val(data.tgl_po);
				$("#tgl_pemasangan").val(data.tgl_pemasangan);
			}
		});
	}
	
	function CariNoRek(){
		var no_rek = $("#no_rek").val();
		var string = "no_rek="+no_rek;
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/ref_json/CariNamaRek",
			data	: string,
			cache	: false,
			dataType: "json",
			success	: function(data){
				$("#nama_rek").val(data.nama_rek);
			}
		});
	}
	
	$("#simpan_1").click(function(){
		var no_po			= $("#no_po").val();
		var tgl_po			= $("#tgl_po").val();
		var tgl_pemasangan	= $("#tgl_pemasangan").val();
		var id_client		= $("#id_client").val();
		var nama_client		= $("#nama_client").val();
		var detail_pesanan	= $("#detail_pesanan").val();
		var ukuran 			= $("#ukuran").val();
		var jumlah 			= $("#jumlah").val();
		var no_rek 			= $("#no_rek").val();
		var harga			= $("#harga").val();
		var ppn				= $("#ppn").val();
		var total			= $("#total").val();
		
		var string = "no_po="+no_po+"&tgl_po="+tgl_po+"&tgl_pemasangan="+tgl_pemasangan+"&id_client="+id_client+"&detail_pesanan="+detail_pesanan+
					 "&ukuran="+ukuran+"&jumlah="+jumlah+"&no_rek="+no_rek+"&harga="+harga+"&ppn="+ppn+"&total="+total;
		
		if(no_po.length==0){
			$.messager.show({
				title:'Info',
				msg:'Maaf, No PO Tidak Boleh Kosong',
				timeout:2000,
				showType:'slide'
			});
			$("#no_po").focus();
			return false;
		}		
		
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/simpan",
			data	: string,
			cache	: false,
			success	: function(data){
				$.messager.show({
					title:'Info',
					msg:data,
					timeout:2000,
					showType:'slide'
				});

				console.log('tes');
				tampil_data();
				kosong();
				//location.reload();
				
			}
		});
		
	});
	
	function tampil_data(){
		//echo "tampil_data";
		var no_po = $("#no_po").val();
		var string = "no_po="+no_po;
		
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/DetailOrder",
			data	: string,
			cache	: false,
			success	: function(data){
				console.log(data);
				$("#tampil_data").html(data);				
				
			}
		});
	}
	
	$("#tambah_data").click(function(){
		//$(".kosong").val('');
		//$(".angka").val('');
		kosong();
		$("#nama_client").focus();
		

	});
	
	$("#kembali").click(function(){
		window.location.assign('<?php echo base_url();?>index.php/penjualan');
	});
});

function editData(id){
	var string = "id="+id;
	//alert(id);	
	$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/edit",
			data	: string,
			cache	: true,
			dataType : "json",
			success	: function(data){
				// var nopo = data.no_po;


				// console.log(data.no_po);
				
				$("#no_po2").val(data.no_po);
				$("#tgl_po").val(data.tgl_po);
				$("#id_client").val(data.id_client);
				$("#nama_client").val(data.nama_client);
				$("#tgl_pemasangan").val(data.tgl_pemasangan);
				//$("#no_rek").focus();
				
				$("#view").hide();
				$("#form").show();
				tampil_data();
			}
	});
	
	function tampil_data(){		
		var no_po = $("#no_po2").val();
		var string = "no_po="+no_po;
		
		$.ajax({
			type	: 'POST',
			url		: "<?php echo site_url(); ?>/penjualan/DetailOrder",
			data	: string,
			cache	: false,
			success	: function(data){
				$("#tampil_data").html(data);
					//alert ("testing");
					
			}
		});
	}
	
}
</script>
<div id="view">
<div style="float:left; padding-bottom:5px;">
<button type="button" name="tambah" id="tambah" class="easyui-linkbutton" data-options="iconCls:'icon-add'">Tambah Data</button>

<a href="<?php echo base_url();?>index.php/penjualan">
<button type="button" name="refresh" id="refresh" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">Refresh</button>
</a>
</div>
<div style="float:right; padding-bottom:5px;">
<form name="form" method="post" action="<?php echo base_url();?>index.php/penjualan">
Cari No.Jurnal/No.Rek : <input type="text" name="txt_cari" id="txt_cari" size="50" />
<button type="submit" name="cari" id="cari" class="easyui-linkbutton" data-options="iconCls:'icon-search'">Cari</button>
</form>
</div>
<div style="padding:10px;"></div>
<table id="dataTable" width="100%">
<tr>
	<th>No</th>
    <th>No PO</th>
    <th>Tanggal PO</th>
    <th>Tanggal Pemasangan</th>
	<th>Nama Client</th>
	<th>Total Nilai</th>
    <th>Opsi</th>
</tr>
<?php
	if($data->num_rows()>0){
		$jml_dr=0;
		$jml_kr=0;
		$no =1+$hal;
		foreach($data->result_array() as $db){  
		$tgl_po = $this->app_model->tgl_sql($db['tgl_po']);
		$tgl_pasang = $this->app_model->tgl_sql($db['tgl_pemasangan']);
		$nama_client = $this->app_model->ClientPenjualan($db['id_client']);
		?>    
    	<tr>
			<td align="center" width="20"><?php echo $no; ?></td>
            <td align="center" width="100" ><?php echo $db['no_po']; ?></td>
            <td align="center" width="100"><?php echo $tgl_po; ?></td>
			<td align="center" width="100"><?php echo $tgl_pasang; ?></td>
            <td align="center" ><?php echo $nama_client; ?></td>
			<td align="center" ><?php echo $db['total']; ?></td>
            
			
            <td align="center" width="80">
            <?php echo "<a href='javascript:editData(\"{$db['no_po']}\")'>";?>
			<img src="<?php echo base_url();?>asset/images/ed.png" title='Edit'>
			</a>
            <a href="<?php echo base_url();?>index.php/penjualan/hapus/<?php echo $db['no_po'];?>"
            onClick="return confirm('Anda yakin ingin menghapus nomor PO ini?')">
			<img src="<?php echo base_url();?>asset/images/del.png" title='Hapus'>
			</a>
            </td>
    </tr>
    <?php
		$jml_dr = $jml_dr+$db['harga'];
		$jml_kr = $jml_kr+$db['ppn'];
		$no++;
		}
	}else{
		$jml_dr = 0;
		$jml_kr = 0;
	?>
    	<tr>
        	<td colspan="9" align="center" >Tidak Ada Data</td>
        </tr>
    <?php	
	}
?>
<!-- <tr>
	<td align="right" colspan="6"><b>Jumlah</b></td>
    <td align="right"><b><?php echo number_format($jml_dr);?></b></td>
    <td align="right"><b><?php echo number_format($jml_kr);?></b></td>    
</tr>  -->      
</table>
<?php echo "<table align='center'><tr><td>".$paginator."</td></tr></table>"; ?>
</div>
<div id="form">
<fieldset>
<table width="100%">
<tr>
	<td width="50%" valign="top">
        <table width="100%">
        <tr>
            <td width="20%">No PO</td>
            <td width="5">:</td>
            <td>
            	<!-- <input type="" name="" id="no_po2"> -->
				<select name="no_po" id="no_po" class="kosong">
				<option value="">-PILIH-</option>
				<?php
				foreach($list_po->result_array() as $t){
				?>
				<option value="<?php echo $t['no_po'];?>"><?php echo $t['no_po'];?></option>
				<?php } ?>
				</select>
            </td>
			<td><input type="text" name="id_client" id="id_client" class="kosong" size="20" maxlength="12" readonly="readonly" hidden="hidden" /></td>
			 
        </tr>
		<tr>
		<td width="20%">Nama Client</td>
            <td width="5">:</td>
		<td><input type="text" name="nama_client" id="nama_client" class="kosong" size="50" maxlength="50" readonly="readonly" /></td>
		</tr>
        <!-- <tr>    
            <td>Nama Client</td>
            <td>:</td>
            <td><input type="text" name="nama_client" id="nama_client" class="kosong" size="50" maxlength="50" readonly="readonly" /></td>            
        </tr>-->
        </table>
	</td>
    <td width="50%" valign="top">
        <table width="100%">
        <tr>
            <td width="20%">Tanggal PO</td>
            <td width="5">:</td>
			<td><input type="text" name="tgl_po" id="tgl_po" class="kosong" size="30" maxlength="30" readonly="readonly" /></td>
        </tr>
        <tr>    
            <td width="20%">Tanggal Pemasangan</td>
            <td width="5">:</td>
			<td><input type="text" name="tgl_pemasangan" id="tgl_pemasangan" class="kosong" size="30" maxlength="30" readonly="readonly" /></td>
        </tr>
        </table>
	</td>
</tr>
</table>            
</fieldset>
<div style="margin:5px;"></div>
<fieldset class="atas">

<table width="100%">
<tr>
	<td width="50%" valign="top">
        <table width="100%">
        <tr>
            <td width="20%">Detail Pesanan</td>
            <td width="5">:</td>
            <td>
            <textarea name="detail_pesanan" id="detail_pesanan" style="width:300px; height:50px;"></textarea>
            </td>
        </tr>
        <tr>    
            <td>Ukuran</td>
            <td>:</td>
            <td><input type="text" name="ukuran" id="ukuran" size="20" maxlength="20" /></td>            
        </tr>
		<tr>
            <td width="20%">Jumlah</td>
            <td width="5">:</td>
            <td>
            <input type="text" name="jumlah" id="jumlah" size="20" maxlength="20" />
            </td>
        </tr>
        </table>
	
    <td width="50%" valign="top">
        <table width="100%">
        <tr>
            <td>Kode Rekening</td>
            <td>:</td>
			<td>
			<select name="no_rek" id="no_rek" class="kosong">
			<option value="">-PILIH-</option>
			<?php
			foreach($list_rek->result_array() as $t){
			?>
			<option value="<?php echo $t['no_rek'];?>"><?php echo $t['no_rek'];?> | <?php echo $t['nama_rek'];?></option>
			<?php } ?>
			</select>
			</td>
			
        </tr>
        <tr>    
            <td>Harga</td>
            <td>:</td>
            <td>
            <input type="text" name="harga" id="harga" size="20" maxlength="20" />
            </td>
        </tr>
		<tr>    
            <td>PPN</td>
            <td>:</td>
            <td>
            <input type="text" name="ppn" id="ppn" size="20" maxlength="20" />
            </td>
        </tr>
		<tr>    
            <td>Total</td>
            <td>:</td>
            <td>
            <input type="text" name="total" id="total" size="20" maxlength="20" />
            </td>
        </tr>
        </table>
	</td>
</tr>
</table>

<!-- <table width="100%">
<tr>
	<th>No Rek</th>
    <th>Nama Rekening</th>
    <th>Debet</th>
    <th>Kredit</th>
</tr>    
<tr>
	<td align="center">
    <select name="no_rek" id="no_rek" class="kosong">
    <option value="">-PILIH-</option>
    <?php
	foreach($list_rek->result_array() as $t){
	?>
    <option value="<?php echo $t['no_rek'];?>"><?php echo $t['no_rek'];?> | <?php echo $t['nama_rek'];?></option>
    <?php } ?>
    </select>
    </td>
    <td align="center"><input type="text" name="nama_rek" id="nama_rek" class="kosong" size="50" maxlength="50" readonly="readonly" /></td>
    <td align="center"><input type="text" name="dr" id="dr" class="angka" size="20" maxlength="20" onkeyup="formatNumber(this);" onchange="formatNumber(this);"/></td>
    <td align="center"><input type="text" name="kr" id="kr" class="angka" size="20" maxlength="20" onkeyup="formatNumber(this);" onchange="formatNumber(this);"/></td>
</tr>    
</table>-->
</fieldset>

<fieldset class="bawah">
<table width="100%">
<tr>
	<td colspan="3" align="center">
    <button name="simpan_1" id="simpan_1" class="easyui-linkbutton" data-options="iconCls:'icon-save'">SIMPAN</button>
    <button name="tambah_data" id="tambah_data" class="easyui-linkbutton" data-options="iconCls:'icon-add'">TAMBAH</button>
    <button type="button" name="kembali" id="kembali" class="easyui-linkbutton" data-options="iconCls:'icon-close'">TUTUP</button>
    </td>
</tr>
</table>  
</fieldset>   
</div>
<div id="tampil_data"></div>